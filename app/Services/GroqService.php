<?php

namespace App\Services;

use App\Models\Career;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GroqService
{
    private string $apiKey;
    private string $model;
    private string $baseUrl = 'https://api.groq.com/openai/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key', '');
        // PENTING: llama-3.3-70b-versatile resmi di-deprecate Groq per 17 Juni 2026,
        // shutdown 16 Agustus 2026. Model default sekarang pakai pengganti resminya,
        // openai/gpt-oss-120b. Cek ulang https://console.groq.com/docs/deprecations
        // sebelum deploy — daftar model & tanggal shutdown Groq sering berubah.
        $this->model = config('services.groq.model', 'openai/gpt-oss-120b');
    }

    /**
     * Rekomendasikan URUTAN modul yang SUDAH ADA (dibuat manual/seeder),
     * berdasarkan skill gap user. Groq TIDAK membuat modul/lesson/assignment
     * baru — cuma mengurutkan ulang ID modul yang dikirim, plus kasih alasan.
     *
     * @param Career $career
     * @param array $skillGaps [['skill_name' => 'React', 'current' => 2, 'required' => 4.5], ...]
     * @param array $availableModules [['id' => 3, 'title' => '...', 'description' => '...'], ...]
     * @return array{order: array} — [['module_id' => int, 'reason' => string], ...]
     */
    public function recommendModuleOrder(Career $career, array $skillGaps, array $availableModules): array
    {
        if (empty($this->apiKey)) {
            throw new RuntimeException('GROQ_API_KEY belum diset di .env');
        }

        if (empty($availableModules)) {
            throw new RuntimeException('Belum ada modul yang di-seed untuk career ini.');
        }

        $prompt = $this->buildPrompt($career, $skillGaps, $availableModules);

        $response = Http::withToken($this->apiKey)
            ->timeout(60)
            ->post($this->baseUrl, [
                'model' => $this->model,
                'temperature' => 0.3,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Kamu adalah AI career coach yang mengurutkan modul belajar yang SUDAH TERSEDIA berdasarkan prioritas skill gap. Kamu TIDAK BOLEH membuat modul baru, mengubah judul, atau menambah module_id yang tidak ada di daftar yang diberikan. Balas HANYA dengan JSON valid, tanpa teks tambahan.',
                    ],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if ($response->failed()) {
            Log::error('Groq API error', ['body' => $response->body()]);
            throw new RuntimeException('Gagal mengambil rekomendasi urutan dari Groq: ' . $response->status());
        }

        $content = $response->json('choices.0.message.content');

        $content = preg_replace('/^```(?:json)?\s*|\s*```$/', '', trim((string) $content));

        $decoded = json_decode($content, true);

        if (! is_array($decoded) || ! isset($decoded['order'])) {
            throw new RuntimeException('Format respons Groq tidak sesuai ekspektasi.');
        }

        return $this->validateStructure($decoded, $availableModules);
    }

    private function buildPrompt(Career $career, array $skillGaps, array $availableModules): string
    {
        $gapLines = collect($skillGaps)
            ->map(fn($g) => "- {$g['skill_name']}: current {$g['current']}/5, industry butuh {$g['required']}/5")
            ->implode("\n");

        $moduleLines = collect($availableModules)
            ->map(fn($m) => "- id={$m['id']} | {$m['title']}: {$m['description']}")
            ->implode("\n");

        return <<<PROMPT
Career tujuan: {$career->name}

Skill gap user saat ini:
{$gapLines}

Daftar modul yang SUDAH TERSEDIA (JANGAN buat modul baru, JANGAN ubah judul,
JANGAN pakai id di luar daftar ini):
{$moduleLines}

Tugas kamu HANYA mengurutkan ulang id modul di atas berdasarkan prioritas —
modul yang menutup skill gap TERBESAR ditaruh paling awal. Sertakan semua
module_id yang ada di daftar (jangan ada yang terlewat), dan kasih alasan
singkat (1 kalimat) kenapa modul itu diprioritaskan di urutan tersebut.

Balas dalam format JSON PERSIS seperti ini (tanpa markdown, tanpa penjelasan tambahan):
{
  "order": [
    { "module_id": 3, "reason": "string singkat" },
    { "module_id": 1, "reason": "string singkat" }
  ]
}
PROMPT;
    }

    private function validateStructure(array $decoded, array $availableModules): array
    {
        $validIds = collect($availableModules)->pluck('id')->all();

        $order = collect($decoded['order'] ?? [])
            ->filter(function ($item) use ($validIds) {
                return is_array($item)
                    && isset($item['module_id'])
                    && in_array($item['module_id'], $validIds, true);
            })
            ->map(fn($item) => [
                'module_id' => (int) $item['module_id'],
                'reason' => (string) ($item['reason'] ?? ''),
            ])
            ->values();

        if ($order->isEmpty()) {
            throw new RuntimeException('Groq tidak mengembalikan urutan modul yang valid (semua module_id ditolak whitelist).');
        }

        $mentionedIds = $order->pluck('module_id')->all();
        $missing = array_diff($validIds, $mentionedIds);
        foreach ($missing as $missingId) {
            $order->push(['module_id' => $missingId, 'reason' => 'Urutan default (tidak disebutkan AI)']);
        }

        return ['order' => $order->all()];
    }

    /**
     * Generate teks rekomendasi untuk halaman Skill Map: ringkasan fondasi,
     * area prioritas (skill dengan gap terbesar), dan estimasi waktu (minggu)
     * untuk menutup skill gap tersebut.
     *
     * @param Career $career
     * @param array $skillGaps [['skill_name' => 'React', 'current' => 2, 'required' => 4.5], ...]
     * @param float $currentLevel rata-rata current level user (skala 1-5)
     * @param float $requiredLevel rata-rata required level industri (skala 1-5)
     * @return array{foundation_summary: string, priority_areas: string, estimated_weeks: int, priority_skill_names: array}
     */
    public function generateSkillRecommendation(
        Career $career,
        array $skillGaps,
        float $currentLevel,
        float $requiredLevel
    ): array {
        if (empty($this->apiKey)) {
            throw new RuntimeException('GROQ_API_KEY belum diset di .env');
        }

        if (empty($skillGaps)) {
            throw new RuntimeException('Skill gap kosong, tidak ada yang bisa direkomendasikan.');
        }

        $prompt = $this->buildRecommendationPrompt($career, $skillGaps, $currentLevel, $requiredLevel);

        $response = Http::withToken($this->apiKey)
            ->timeout(60)
            ->post($this->baseUrl, [
                'model' => $this->model,
                'temperature' => 0.4,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Kamu adalah AI career coach yang memberi ringkasan singkat dan estimasi waktu belajar berdasarkan skill gap user. Balas HANYA dengan JSON valid, tanpa teks tambahan, dalam Bahasa Indonesia.',
                    ],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if ($response->failed()) {
            Log::error('Groq API error (recommendation)', ['body' => $response->body()]);
            throw new RuntimeException('Gagal mengambil rekomendasi dari Groq: ' . $response->status());
        }

        $content = $response->json('choices.0.message.content');
        $content = preg_replace('/^```(?:json)?\s*|\s*```$/', '', trim((string) $content));

        $decoded = json_decode($content, true);

        if (! is_array($decoded)) {
            throw new RuntimeException('Format respons Groq tidak sesuai ekspektasi.');
        }

        return $this->validateRecommendationStructure($decoded, $skillGaps);
    }

    private function buildRecommendationPrompt(
        Career $career,
        array $skillGaps,
        float $currentLevel,
        float $requiredLevel
    ): string {
        $gapLines = collect($skillGaps)
            ->map(fn($g) => "- {$g['skill_name']}: current {$g['current']}/5, industri butuh {$g['required']}/5")
            ->implode("\n");

        return <<<PROMPT
Career tujuan: {$career->name}

Level keterampilan user saat ini (rata-rata): {$currentLevel}/5
Level yang dibutuhkan industri (rata-rata): {$requiredLevel}/5

Detail skill gap per skill:
{$gapLines}

Tugas kamu:
1. Tulis 1-2 kalimat ringkasan fondasi user (foundation_summary) — apresiatif tapi jujur.
2. Sebutkan 2-3 skill dengan gap TERBESAR sebagai area prioritas (priority_skill_names), dan
   tulis 1-2 kalimat penjelasan kenapa (priority_areas).
3. Estimasikan berapa minggu (estimated_weeks, angka bulat) yang realistis untuk menutup
   skill gap ini dengan belajar konsisten (asumsikan ~5-8 jam belajar per minggu).

Balas dalam format JSON PERSIS seperti ini (tanpa markdown, tanpa penjelasan tambahan):
{
  "foundation_summary": "string",
  "priority_areas": "string",
  "priority_skill_names": ["skill1", "skill2"],
  "estimated_weeks": 12
}
PROMPT;
    }

    private function validateRecommendationStructure(array $decoded, array $skillGaps): array
    {
        $validSkillNames = collect($skillGaps)->pluck('skill_name')->all();

        $prioritySkillNames = collect($decoded['priority_skill_names'] ?? [])
            ->filter(fn($name) => in_array($name, $validSkillNames, true))
            ->values()
            ->all();

        if (empty($prioritySkillNames)) {
            $prioritySkillNames = collect($skillGaps)
                ->sortByDesc(fn($g) => $g['required'] - $g['current'])
                ->take(3)
                ->pluck('skill_name')
                ->values()
                ->all();
        }

        $estimatedWeeks = (int) ($decoded['estimated_weeks'] ?? 0);
        $estimatedWeeks = max(1, min(52, $estimatedWeeks));

        return [
            'foundation_summary' => (string) ($decoded['foundation_summary'] ?? ''),
            'priority_areas' => (string) ($decoded['priority_areas'] ?? ''),
            'priority_skill_names' => $prioritySkillNames,
            'estimated_weeks' => $estimatedWeeks,
        ];
    }

    /**
     * Generate 3-5 Learning Objectives untuk SATU modul yang sudah ada.
     * Dipanggil sekali saat modul dibuat (seeder/artisan command), BUKAN
     * setiap kali halaman module detail dibuka — objectives ini konten
     * statis per modul, bukan personalisasi per user, jadi tidak perlu
     * (dan tidak boleh) di-generate ulang tiap request.
     *
     * @param string $moduleTitle
     * @param string $moduleDescription
     * @param array $lessonTitles daftar judul lesson di modul ini, untuk konteks tambahan
     * @return array{objectives: array<int, string>}
     */
    public function generateLearningObjectives(
        string $moduleTitle,
        string $moduleDescription,
        array $lessonTitles = []
    ): array {
        if (empty($this->apiKey)) {
            throw new RuntimeException('GROQ_API_KEY belum diset di .env');
        }

        if (trim($moduleTitle) === '') {
            throw new RuntimeException('Judul modul kosong, tidak bisa generate objectives.');
        }

        $prompt = $this->buildLearningObjectivesPrompt($moduleTitle, $moduleDescription, $lessonTitles);

        $response = Http::withToken($this->apiKey)
            ->timeout(60)
            ->post($this->baseUrl, [
                'model' => $this->model,
                'temperature' => 0.4,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Kamu adalah instructional designer yang menulis Learning Objectives untuk modul belajar. Setiap objective harus dimulai dengan kata kerja aksi (Kuasai, Terapkan, Bangun, dsb), singkat (maksimal 1 kalimat), dan bisa diukur. Balas HANYA dengan JSON valid, tanpa teks tambahan, dalam Bahasa Indonesia.',
                    ],
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

        if ($response->failed()) {
            Log::error('Groq API error (learning objectives)', ['body' => $response->body()]);
            throw new RuntimeException('Gagal generate learning objectives dari Groq: ' . $response->status());
        }

        $content = $response->json('choices.0.message.content');
        $content = preg_replace('/^```(?:json)?\s*|\s*```$/', '', trim((string) $content));

        $decoded = json_decode($content, true);

        if (! is_array($decoded) || ! isset($decoded['objectives'])) {
            throw new RuntimeException('Format respons Groq tidak sesuai ekspektasi.');
        }

        return $this->validateObjectivesStructure($decoded);
    }

    private function buildLearningObjectivesPrompt(
        string $moduleTitle,
        string $moduleDescription,
        array $lessonTitles
    ): string {
        $lessonLines = empty($lessonTitles)
            ? '(belum ada lesson terdaftar)'
            : collect($lessonTitles)->map(fn($t) => "- {$t}")->implode("\n");

        return <<<PROMPT
Judul modul: {$moduleTitle}
Deskripsi modul: {$moduleDescription}

Daftar lesson di modul ini (untuk konteks cakupan materi):
{$lessonLines}

Tugas kamu: tulis 3-5 Learning Objectives untuk modul ini. Setiap objective
adalah 1 kalimat, dimulai dengan kata kerja aksi, dan menggambarkan apa yang
BISA DILAKUKAN peserta setelah menyelesaikan modul ini (bukan sekadar
"memahami" — fokus ke kemampuan praktis yang terukur).

Balas dalam format JSON PERSIS seperti ini (tanpa markdown, tanpa penjelasan tambahan):
{
  "objectives": [
    "Kuasai konsep fundamental ...",
    "Bangun proyek praktis yang ..."
  ]
}
PROMPT;
    }

    private function validateObjectivesStructure(array $decoded): array
    {
        $objectives = collect($decoded['objectives'] ?? [])
            ->filter(fn($item) => is_string($item) && trim($item) !== '')
            ->map(fn($item) => trim($item))
            ->take(5)
            ->values()
            ->all();

        if (empty($objectives)) {
            throw new RuntimeException('Groq tidak mengembalikan learning objectives yang valid.');
        }

        return ['objectives' => $objectives];
    }
}
