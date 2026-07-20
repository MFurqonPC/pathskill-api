<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Isi quiz contoh untuk "Assignment 1: Personal Portfolio Page",
     * supaya screen Quiz bisa langsung ditest. Jalankan setelah
     * LearningPathSeeder (assignment-nya harus sudah ada).
     */
    public function run(): void
    {
        $assignment = Assignment::where('title', 'Assignment 1: Personal Portfolio Page')->first();

        if (! $assignment) {
            return; // jalankan LearningPathSeeder dulu
        }

        $quiz = Quiz::updateOrCreate(
            ['assignment_id' => $assignment->id],
            ['title' => 'Quiz: HTML Semantik & Layout Responsif']
        );

        // hapus soal lama kalau seeder dijalankan ulang, biar tidak dobel
        $quiz->questions()->delete();

        $questions = [
            [
                'question' => 'Elemen HTML semantik apa yang paling tepat untuk membungkus navigasi utama pada landing page responsif?',
                'explanation' => '<nav> adalah elemen semantik yang secara spesifik dirancang untuk mengelompokkan tautan navigasi utama, berbeda dari <div> yang generik atau <header> yang membungkus area kop halaman.',
                'options' => [
                    ['text' => '<nav>', 'correct' => true],
                    ['text' => '<header>', 'correct' => false],
                    ['text' => '<div>', 'correct' => false],
                    ['text' => '<main>', 'correct' => false],
                ],
            ],
            [
                'question' => 'Properti CSS Grid mana yang digunakan untuk membuat kolom otomatis menyesuaikan ukuran layar (responsif)?',
                'explanation' => 'repeat(auto-fit, minmax(...)) memungkinkan jumlah kolom menyesuaikan otomatis berdasarkan lebar container, cocok untuk layout responsif tanpa media query tambahan.',
                'options' => [
                    ['text' => 'grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))', 'correct' => true],
                    ['text' => 'grid-template-columns: 3', 'correct' => false],
                    ['text' => 'grid-gap: responsive', 'correct' => false],
                    ['text' => 'display: flex-grid', 'correct' => false],
                ],
            ],
            [
                'question' => 'Atribut apa yang wajib ditambahkan pada tag <img> untuk mendukung aksesibilitas (A11y)?',
                'explanation' => 'Atribut alt menyediakan teks alternatif yang dibacakan screen reader dan ditampilkan jika gambar gagal dimuat.',
                'options' => [
                    ['text' => 'alt', 'correct' => true],
                    ['text' => 'title', 'correct' => false],
                    ['text' => 'longdesc', 'correct' => false],
                    ['text' => 'aria-hidden', 'correct' => false],
                ],
            ],
            [
                'question' => 'Pendekatan desain yang mengutamakan tampilan mobile terlebih dahulu baru diperluas ke layar lebih besar disebut?',
                'explanation' => 'Mobile First berarti menulis CSS dasar untuk layar kecil dulu, lalu menambah media query min-width untuk layar yang lebih lebar — bukan sebaliknya.',
                'options' => [
                    ['text' => 'Mobile First', 'correct' => true],
                    ['text' => 'Desktop First', 'correct' => false],
                    ['text' => 'Fluid Grid', 'correct' => false],
                    ['text' => 'Adaptive Design', 'correct' => false],
                ],
            ],
            [
                'question' => 'Unit CSS mana yang ukurannya relatif terhadap ukuran font elemen induk (bukan root)?',
                'explanation' => 'em relatif terhadap font-size elemen induknya, berbeda dengan rem yang selalu relatif terhadap elemen root (<html>).',
                'options' => [
                    ['text' => 'em', 'correct' => true],
                    ['text' => 'rem', 'correct' => false],
                    ['text' => 'px', 'correct' => false],
                    ['text' => 'vh', 'correct' => false],
                ],
            ],
            [
                'question' => 'Media query CSS mana yang benar untuk menerapkan style hanya pada layar dengan lebar maksimal 768px?',
                'explanation' => 'max-width: 768px berarti aturan di dalam blok ini berlaku selama lebar viewport tidak lebih dari 768px — pola umum untuk breakpoint tablet ke bawah.',
                'options' => [
                    ['text' => '@media (max-width: 768px) { ... }', 'correct' => true],
                    ['text' => '@media (width: 768px) { ... }', 'correct' => false],
                    ['text' => '@responsive (768px) { ... }', 'correct' => false],
                    ['text' => '@media (min-height: 768px) { ... }', 'correct' => false],
                ],
            ],
            [
                'question' => 'Rasio kontras warna minimum yang direkomendasikan WCAG AA untuk teks normal terhadap latar belakangnya adalah?',
                'explanation' => 'WCAG level AA mensyaratkan rasio kontras minimal 4.5:1 untuk teks normal, supaya tetap terbaca oleh pengguna dengan gangguan penglihatan rendah.',
                'options' => [
                    ['text' => '4.5:1', 'correct' => true],
                    ['text' => '1:1', 'correct' => false],
                    ['text' => '2:1', 'correct' => false],
                    ['text' => '10:1', 'correct' => false],
                ],
            ],
            [
                'question' => 'Properti CSS Flexbox mana yang mengatur arah sumbu utama (row atau column) dari sebuah flex container?',
                'explanation' => 'flex-direction menentukan apakah item flex disusun secara horizontal (row, default) atau vertikal (column).',
                'options' => [
                    ['text' => 'flex-direction', 'correct' => true],
                    ['text' => 'justify-content', 'correct' => false],
                    ['text' => 'align-items', 'correct' => false],
                    ['text' => 'flex-wrap', 'correct' => false],
                ],
            ],
            [
                'question' => 'Elemen HTML semantik apa yang paling tepat digunakan untuk membungkus konten utama/unik dari sebuah halaman?',
                'explanation' => '<main> menandai konten inti halaman yang unik, membantu screen reader melompat langsung ke bagian penting tanpa harus melewati navigasi/header berulang kali.',
                'options' => [
                    ['text' => '<main>', 'correct' => true],
                    ['text' => '<section>', 'correct' => false],
                    ['text' => '<article>', 'correct' => false],
                    ['text' => '<aside>', 'correct' => false],
                ],
            ],
        ];

        foreach ($questions as $index => $q) {
            $question = $quiz->questions()->create([
                'question' => $q['question'],
                'explanation' => $q['explanation'],
                'order' => $index + 1,
            ]);

            // acak urutan opsi supaya jawaban benar gak selalu di posisi
            // pertama — flag 'correct' ikut option-nya, jadi tetap valid.
            $options = $q['options'];
            shuffle($options);

            foreach ($options as $optIndex => $opt) {
                $question->options()->create([
                    'option_text' => $opt['text'],
                    'is_correct' => $opt['correct'],
                    'order' => $optIndex + 1,
                ]);
            }
        }
    }
}