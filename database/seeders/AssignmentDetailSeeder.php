<?php

namespace Database\Seeders;

use App\Models\Assignment;
use Illuminate\Database\Seeder;

class AssignmentDetailSeeder extends Seeder
{
    public function run(): void
    {
        $assignment = Assignment::where('title', 'Assignment 1: Personal Portfolio Page')->first();

        if (! $assignment) {
            return; // jalankan LearningPathSeeder dulu
        }

        $assignment->update([
            'learning_outcomes' => [
                'Membangun halaman portofolio pribadi yang responsif menggunakan HTML semantik dan CSS Grid/Flexbox',
                'Menerapkan praktik aksesibilitas dasar: atribut alt, kontras warna yang cukup, dan navigasi yang bisa diakses keyboard',
            ],
            'skills_learned' => ['HTML', 'CSS', 'Responsive Design'],
            'prerequisites' => ['HTML', 'CSS'],
            'tools' => ['VS Code', 'Chrome DevTools', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Struktur HTML', 'weight' => 20],
                ['criteria' => 'Layout Responsif', 'weight' => 30],
                ['criteria' => 'Aksesibilitas (A11y)', 'weight' => 20],
                ['criteria' => 'HTML Semantik', 'weight' => 15],
                ['criteria' => 'Presisi UI', 'weight' => 15],
            ],
        ]);
    }
}