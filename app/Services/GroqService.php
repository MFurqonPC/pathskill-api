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

        // defensive: kadang model tetap membungkus JSON dengan markdown fence
        // meskipun sudah diminta response_format json_object
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
            ->map(fn ($g) => "- {$g['skill_name']}: current {$g['current']}/5, industry butuh {$g['required']}/5")
            ->implode("\n");

        $moduleLines = collect($availableModules)
            ->map(fn ($m) => "- id={$m['id']} | {$m['title']}: {$m['description']}")
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

    /**
     * Validasi KETAT: setiap module_id yang dikembalikan Groq WAJIB ada
     * di daftar $availableModules yang kita kirim (whitelist check).
     * Ini mencegah AI "mengarang" module_id yang tidak ada di database.
     */
    private function validateStructure(array $decoded, array $availableModules): array
    {
        $validIds = collect($availableModules)->pluck('id')->all();

        $order = collect($decoded['order'] ?? [])
            ->filter(function ($item) use ($validIds) {
                return is_array($item)
                    && isset($item['module_id'])
                    && in_array($item['module_id'], $validIds, true);
            })
            ->map(fn ($item) => [
                'module_id' => (int) $item['module_id'],
                'reason' => (string) ($item['reason'] ?? ''),
            ])
            ->values();

        if ($order->isEmpty()) {
            throw new RuntimeException('Groq tidak mengembalikan urutan modul yang valid (semua module_id ditolak whitelist).');
        }

        // Jaga-jaga: kalau Groq gak nyebutin semua modul yang dikirim,
        // sisanya ditambahkan di akhir urutan (fallback, bukan dihilangkan).
        $mentionedIds = $order->pluck('module_id')->all();
        $missing = array_diff($validIds, $mentionedIds);
        foreach ($missing as $missingId) {
            $order->push(['module_id' => $missingId, 'reason' => 'Urutan default (tidak disebutkan AI)']);
        }

        return ['order' => $order->all()];
    }
}