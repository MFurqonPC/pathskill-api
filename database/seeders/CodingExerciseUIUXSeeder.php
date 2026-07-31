<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\CodingExercise;
use Illuminate\Database\Seeder;

class CodingExerciseUIUXSeeder extends Seeder
{
    /**
     * Coding exercise untuk career UI/UX Designer, mengikuti pola
     * per-career yang sama seperti CodingExerciseFullStackSeeder /
     * CodingExerciseBackendSeeder / CodingExerciseDataAnalystSeeder /
     * CodingExerciseDevOpsSeeder.
     *
     * Hanya Modul 4 (HTML & CSS untuk Designer) yang dibuatkan coding
     * exercise. Modul lain di career ini (Design Thinking & User
     * Research, Wireframing & Prototyping, Visual Design & Tipografi,
     * Usability Testing, Design Handoff & Kolaborasi dengan Developer)
     * sengaja TIDAK dibuatkan — assignment-nya berbasis Figma, riset,
     * atau dokumentasi (persona, journey map, style guide, usability
     * report, handoff doc), bukan "lengkapi starter code".
     *
     * Jalankan setelah LearningPathSeeder dan AssignmentDetailSeeder
     * (assignment-nya harus sudah ada). Idempotent lewat updateOrCreate
     * berdasarkan assignment_id.
     *
     * Jalankan:
     *   php artisan db:seed --class=CodingExerciseUIUXSeeder
     */
    public function run(): void
    {
        foreach ($this->exerciseData() as $assignmentTitle => $data) {
            $assignment = Assignment::where('title', $assignmentTitle)->first();

            if (! $assignment) {
                $this->command?->warn("CodingExerciseUIUXSeeder: assignment tidak ditemukan — {$assignmentTitle}");
                continue;
            }

            CodingExercise::updateOrCreate(
                ['assignment_id' => $assignment->id],
                $data
            );
        }
    }

    private function exerciseData(): array
    {
        return [

            // ============================================================
            // Modul 4: HTML & CSS untuk Designer
            // ============================================================

            'Assignment 1: Static Page from Figma Design' => [
                'title' => 'Latihan: Terjemahkan Desain Card Profil ke HTML/CSS',
                'description' => 'Lengkapi HTML dan CSS di bawah supaya card profil sesuai spesifikasi desain: foto bulat, nama, jabatan, dan tombol kontak, disusun rapi dengan elemen semantik yang benar.',
                'learning_objectives' => [
                    'Menerjemahkan spesifikasi desain (ukuran, spacing, warna) menjadi HTML dan CSS',
                    'Menggunakan elemen HTML semantik yang sesuai konteks kontennya',
                ],
                'requirements' => [
                    'Foto profil harus bulat sempurna (border-radius 50%) dengan ukuran 96x96px',
                    'Nama menggunakan elemen heading yang tepat (bukan <div> atau <span> biasa)',
                    'Card dibungkus dengan padding dan border-radius agar terlihat seperti kartu, bukan konten polos',
                    'Tombol kontak punya warna latar yang jelas berbeda dari background card',
                ],
                'test_cases' => [
                    'Foto profil tampil sebagai lingkaran, bukan persegi',
                    'Nama menggunakan tag heading (h2/h3), terverifikasi lewat inspect element',
                    'Card memiliki padding yang membuat konten tidak menempel ke tepi',
                    'Tombol kontak terlihat jelas sebagai elemen yang bisa diklik (kontras warna cukup)',
                ],
                'language' => 'html',
                'starter_code' => <<<'CODE'
<!-- Lengkapi card profil di bawah sesuai spesifikasi desain -->
<div class="card-profil">
  <img src="foto.jpg">
  <div>Nama Lengkap</div>
  <p>UI/UX Designer</p>
  <div>Hubungi</div>
</div>

<style>
.card-profil {
  /* TODO 1: tambahkan padding, border-radius, dan box-shadow ringan
     agar terlihat seperti kartu */
}

.card-profil img {
  /* TODO 2: buat foto jadi bulat (96x96px, border-radius 50%,
     object-fit: cover) */
}

/* TODO 3: ganti <div>Nama Lengkap</div> di HTML jadi elemen heading,
   lalu styling di sini kalau perlu */

/* TODO 4: styling tombol "Hubungi" agar terlihat sebagai tombol,
   bukan teks biasa (background color, padding, cursor: pointer) */
</style>
CODE,
                'hint' => 'Untuk foto: width: 96px; height: 96px; border-radius: 50%; object-fit: cover;. Ganti <div>Nama Lengkap</div> menjadi <h3>Nama Lengkap</h3>. Untuk tombol, ganti <div>Hubungi</div> menjadi <button> lalu beri background-color dan padding.',
            ],

            'Assignment 2: Responsive Landing Page Handoff' => [
                'title' => 'Latihan: Flexbox Responsif untuk Section Handoff',
                'description' => 'Lengkapi CSS di bawah supaya section "Kenapa Memilih Kami" menyusun tiga poin secara sejajar (flexbox) di desktop, dan otomatis bertumpuk vertikal di layar HP — sesuai batasan implementasi yang realistis untuk di-handoff ke developer.',
                'learning_objectives' => [
                    'Menerapkan flexbox dasar dengan flex-wrap agar layout menyesuaikan lebar layar',
                    'Mempertimbangkan batasan implementasi teknis (breakpoint sederhana) saat menyiapkan desain untuk handoff',
                ],
                'requirements' => [
                    'Container poin menggunakan display: flex agar item sejajar secara default',
                    'Di layar sempit (di bawah 600px), item bertumpuk vertikal (flex-direction: column atau flex-wrap)',
                    'Jarak antar item konsisten menggunakan gap, bukan margin manual di tiap item',
                    'Setiap item punya lebar yang proporsional dan tidak overflow di layar kecil',
                ],
                'test_cases' => [
                    'Di layar lebar (desktop), ketiga item tampil sejajar dalam satu baris',
                    'Di layar sempit (di bawah 600px), item tersusun vertikal, tidak saling tumpang tindih atau overflow',
                    'Jarak antar item terlihat konsisten di kedua ukuran layar',
                ],
                'language' => 'html',
                'starter_code' => <<<'CODE'
<section class="kenapa-kami">
  <div class="poin">
    <h3>Cepat</h3>
    <p>Proses pengerjaan yang efisien.</p>
  </div>
  <div class="poin">
    <h3>Terpercaya</h3>
    <p>Sudah dipakai banyak klien.</p>
  </div>
  <div class="poin">
    <h3>Fleksibel</h3>
    <p>Menyesuaikan kebutuhan proyekmu.</p>
  </div>
</section>

<style>
.kenapa-kami {
  /* TODO 1: tambahkan display: flex dan gap di sini */
}

.poin {
  box-sizing: border-box;
  padding: 16px;
  /* TODO 2: beri flex-basis/flex agar lebar tiap poin proporsional,
     misal flex: 1 */
}

/* TODO 3: tambahkan media query untuk layar di bawah 600px
   yang mengubah .kenapa-kami menjadi flex-direction: column */
</style>
CODE,
                'hint' => '.kenapa-kami { display: flex; gap: 16px; } .poin { flex: 1; } lalu @media (max-width: 600px) { .kenapa-kami { flex-direction: column; } }',
            ],
        ];
    }
}