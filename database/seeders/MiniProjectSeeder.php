<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MiniProject;
use Illuminate\Database\Seeder;

class MiniProjectSeeder extends Seeder
{
    /**
     * Isi mini project contoh untuk "Assignment 1: Personal Portfolio Page",
     * lanjutan dari Coding Exercise (Section Hero Responsif). Ini tahap
     * terakhir sebelum Pengumpulan. Jalankan setelah LearningPathSeeder,
     * AssignmentDetailSeeder & CodingExerciseSeeder (assignment-nya harus
     * sudah ada).
     */
    public function run(): void
    {
        $assignment = Assignment::where('title', 'Assignment 1: Personal Portfolio Page')->first();

        if (! $assignment) {
            return; // jalankan LearningPathSeeder dulu
        }

        MiniProject::updateOrCreate(
            ['assignment_id' => $assignment->id],
            [
                'title' => 'Tantangan Mini Project: Portofolio Developer',
                'brief' => 'Kembangkan Latihan Coding sebelumnya jadi halaman portofolio utuh untuk diri kamu sebagai calon frontend developer. Fokus utama: responsivitas dan aksesibilitas, bukan visual yang rumit.',
                'objectives' => [
                    'Tampilkan minimal 5 proyek terbaru dalam bentuk grid/list',
                    'Sediakan form kontak sederhana (nama, email, pesan)',
                    'Tulis kode yang rapi dan konsisten (clean code)',
                ],
                'acceptance_criteria' => [
                    'Responsif di layar mobile, tablet, dan desktop',
                    'Menggunakan elemen HTML semantik (header, main, section, footer)',
                    'CSS disusun dengan pendekatan Mobile First',
                    'Memenuhi dasar aksesibilitas (alt text pada gambar, kontras warna cukup, label pada form)',
                    'Kode terstruktur rapi dan gampang dibaca',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat (boleh cantumkan link repo/demo di sini)',
                ],
            ]
        );
    }
}
