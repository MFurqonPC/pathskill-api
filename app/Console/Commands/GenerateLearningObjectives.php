<?php

namespace App\Console\Commands;

use App\Models\LearningModule;
use App\Services\GroqService;
use Illuminate\Console\Command;

class GenerateLearningObjectives extends Command
{
    protected $signature = 'modules:generate-objectives {--delay=2 : Jeda detik antar request ke Groq} {--max-retries=3 : Percobaan ulang maksimal kalau kena rate limit (429)}';

    protected $description = 'Generate learning_objectives (via Groq) untuk semua learning module yang belum punya.';

    public function handle(GroqService $groq): int
    {
        $modules = LearningModule::whereNull('learning_objectives')->get();

        if ($modules->isEmpty()) {
            $this->info('Semua module sudah punya learning_objectives. Tidak ada yang perlu di-generate.');
            return self::SUCCESS;
        }

        $delaySeconds = (int) $this->option('delay');
        $maxRetries = (int) $this->option('max-retries');

        $this->info("Ditemukan {$modules->count()} module tanpa learning_objectives. Memulai generate...");
        $bar = $this->output->createProgressBar($modules->count());

        foreach ($modules as $module) {
            $lessonTitles = $module->lessons->pluck('title')->all();
            $attempt = 0;

            while (true) {
                $attempt++;

                try {
                    $result = $groq->generateLearningObjectives(
                        $module->title,
                        $module->description ?? '',
                        $lessonTitles
                    );

                    $module->update(['learning_objectives' => $result['objectives']]);
                    break; // sukses, lanjut ke module berikutnya
                } catch (\Throwable $e) {
                    $isRateLimited = str_contains($e->getMessage(), '429');

                    if ($isRateLimited && $attempt <= $maxRetries) {
                        // backoff bertahap: 5s, 10s, 20s, ...
                        $backoff = 5 * (2 ** ($attempt - 1));
                        $this->newLine();
                        $this->warn("Module #{$module->id} kena rate limit (percobaan {$attempt}/{$maxRetries}), tunggu {$backoff}s...");
                        sleep($backoff);
                        continue; // coba lagi module yang sama
                    }

                    $this->newLine();
                    $this->error("Gagal generate untuk module #{$module->id} ({$module->title}): {$e->getMessage()}");
                    break; // menyerah untuk module ini, lanjut ke berikutnya
                }
            }

            $bar->advance();

            // jeda antar module supaya tidak langsung kena rate limit lagi,
            // meskipun request sebelumnya sukses
            sleep($delaySeconds);
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Selesai.');

        return self::SUCCESS;
    }
}