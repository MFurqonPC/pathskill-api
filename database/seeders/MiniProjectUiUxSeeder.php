<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MiniProject;
use Illuminate\Database\Seeder;

class MiniProjectUiUxSeeder extends Seeder
{
    /**
     * Mini project untuk seluruh assignment praktik di track UI/UX
     * Designer (Modul 1-6). Judul assignment di sini HARUS PERSIS sama
     * dengan yang dibuat AddAssignmentsToExistingModulesSeeder (format
     * "Assignment N: {title}") — jalankan seeder itu DULU sebelum ini.
     *
     * Jalankan setelah AddAssignmentsToExistingModulesSeeder &
     * AssignmentDetailSeeder (assignment-nya harus sudah ada). Idempotent
     * lewat updateOrCreate berdasarkan assignment_id, aman dijalankan
     * berkali-kali.
     *
     * Jalankan:
     *   php artisan db:seed --class=MiniProjectUiUxSeeder
     */
    public function run(): void
    {
        $created = 0;
        $skipped = [];

        foreach ($this->projectData() as $assignmentTitle => $data) {
            $assignment = Assignment::where('title', $assignmentTitle)->first();

            if (! $assignment) {
                $skipped[] = $assignmentTitle;
                continue;
            }

            MiniProject::updateOrCreate(
                ['assignment_id' => $assignment->id],
                $data
            );
            $created++;
        }

        $this->command?->info("MiniProjectUiUxSeeder: {$created} mini project berhasil dibuat/diperbarui.");

        if (! empty($skipped)) {
            $this->command?->warn(
                'MiniProjectUiUxSeeder: assignment tidak ditemukan (jalankan AddAssignmentsToExistingModulesSeeder dulu) — '
                . implode(', ', $skipped)
            );
        }
    }

    private function projectData(): array
    {
        return [

            // ============================================================
            // ---------- Modul 1: Design Thinking & User Research ----------
            // ============================================================
            'Assignment 1: User Research & Persona Creation' => [
                'title' => 'Tantangan Mini Project: Riset Pengguna Mendalam & Persona Multi-Segmen',
                'brief' => 'Kembangkan riset pengguna sebelumnya jadi riset yang lebih mendalam: wawancarai minimal 3 calon pengguna dari segmen berbeda, lalu hasilkan minimal 2 user persona berbeda lengkap dengan goals, frustrations, dan pola perilaku.',
                'objectives' => [
                    'Lakukan wawancara terhadap minimal 3 calon pengguna dari latar belakang berbeda dengan panduan pertanyaan terstruktur',
                    'Susun minimal 2 user persona berbeda berdasarkan pola temuan wawancara, bukan asumsi pribadi',
                    'Identifikasi goals, frustrations, dan kebiasaan penggunaan produk untuk tiap persona',
                ],
                'acceptance_criteria' => [
                    'Minimal 3 sesi wawancara terdokumentasi dengan catatan/rekaman',
                    'Tiap persona memuat foto/ilustrasi, nama, latar belakang, goals, frustrations, dan quote representatif',
                    'Persona didukung oleh kutipan atau temuan nyata dari wawancara, bukan generik',
                    'Perbedaan antar persona terlihat jelas (bukan sekadar variasi kecil dari satu tipe pengguna)',
                    'Dokumen tersusun rapi dan mudah dipahami tim lain',
                ],
                'deliverables' => [
                    'Link file Figma/FigJam berisi persona',
                    'File PDF/ZIP ringkasan proses dan hasil wawancara',
                ],
            ],

            'Assignment 2: Customer Journey Map Project' => [
                'title' => 'Tantangan Mini Project: Customer Journey Map Multi-Skenario dengan Solusi',
                'brief' => 'Kembangkan journey map sebelumnya dengan menyusun skenario "current state" vs "future state" — tunjukkan bagaimana pain point yang ditemukan bisa diselesaikan lewat perbaikan desain.',
                'objectives' => [
                    'Petakan current state journey pengguna dari awal hingga akhir interaksi dengan produk',
                    'Rancang future state journey yang menunjukkan solusi atas pain point yang ditemukan',
                    'Prioritaskan pain point berdasarkan dampaknya terhadap pengalaman pengguna',
                ],
                'acceptance_criteria' => [
                    'Journey map mencakup tahapan, tindakan, pikiran, dan emosi pengguna di setiap tahap',
                    'Minimal 3 pain point teridentifikasi dengan jelas pada current state',
                    'Future state menunjukkan solusi konkret untuk tiap pain point utama',
                    'Prioritas perbaikan disusun berdasarkan dampak, bukan asal urut',
                    'Visual journey map mudah dibaca dan dipahami tanpa penjelasan tambahan',
                ],
                'deliverables' => [
                    'Link file Figma/FigJam',
                    'File ZIP/PDF ringkasan pain point dan rekomendasi solusi',
                ],
            ],

            // ============================================================
            // ---------- Modul 2: Wireframing & Prototyping ----------
            // ============================================================
            'Assignment 1: Low-Fidelity Wireframe Set' => [
                'title' => 'Tantangan Mini Project: Wireframe Set Multi-Flow dengan Alur Navigasi',
                'brief' => 'Kembangkan wireframe sebelumnya menjadi satu set wireframe yang mencakup alur navigasi utuh (minimal 5 halaman) untuk satu flow pengguna, bukan halaman lepas-lepas.',
                'objectives' => [
                    'Rancang wireframe minimal 5 halaman yang membentuk satu alur pengguna yang utuh',
                    'Terapkan hierarki visual yang konsisten di seluruh halaman',
                    'Tandai alur transisi antar halaman dengan jelas (arrow/flow annotation)',
                ],
                'acceptance_criteria' => [
                    'Seluruh halaman terhubung membentuk satu alur pengguna yang logis dan lengkap',
                    'Hierarki visual (proximity, alignment, whitespace) konsisten di seluruh halaman',
                    'Anotasi alur/transisi antar halaman tersedia dan mudah diikuti',
                    'Elemen wireframe (tombol, form, navigasi) konsisten penempatannya',
                    'File Figma tersusun rapi dengan penamaan frame yang jelas',
                ],
                'deliverables' => [
                    'Link file Figma',
                    'File PDF hasil export seluruh wireframe',
                ],
            ],

            'Assignment 2: Interactive Prototype in Figma' => [
                'title' => 'Tantangan Mini Project: Interactive Prototype dengan Micro-interaction',
                'brief' => 'Kembangkan prototype sebelumnya menjadi prototype interaktif penuh dengan micro-interaction (transisi/hover), state kondisional (misal form error), dan komponen reusable yang mengikuti design system sederhana.',
                'objectives' => [
                    'Bangun prototype yang dapat diklik menjelajahi seluruh alur utama, termasuk skenario error/kondisional',
                    'Terapkan micro-interaction (transisi, animasi sederhana) pada minimal 3 titik interaksi',
                    'Susun komponen reusable (button, input, card) yang konsisten mengikuti satu design system sederhana',
                ],
                'acceptance_criteria' => [
                    'Seluruh alur utama dapat dijelajahi lewat klik tanpa dead-end',
                    'Minimal 1 skenario kondisional (misal error state, empty state) tersedia di prototype',
                    'Micro-interaction diterapkan secara wajar, tidak mengganggu kejelasan alur',
                    'Komponen reusable digunakan konsisten di seluruh halaman prototype',
                    'File Figma terorganisir dengan page/section yang jelas',
                ],
                'deliverables' => [
                    'Link file Figma (mode present/prototype aktif)',
                    'File ZIP/PDF ringkasan alur dan komponen yang dipakai',
                ],
            ],

            // ============================================================
            // ---------- Modul 3: Visual Design & Tipografi ----------
            // ============================================================
            'Assignment 1: Mobile App Visual Design' => [
                'title' => 'Tantangan Mini Project: Visual Design Aplikasi Mobile Lintas Tema (Light/Dark)',
                'brief' => 'Kembangkan visual design aplikasi mobile sebelumnya dengan menambahkan varian tema dark mode, memastikan kontras dan keterbacaan tetap terjaga pada kedua tema.',
                'objectives' => [
                    'Terapkan palet warna primary/secondary/semantic yang konsisten pada 2 varian tema (light & dark)',
                    'Jaga skala tipografi dan hierarki teks tetap konsisten di kedua tema',
                    'Pastikan kontras warna memenuhi standar keterbacaan minimum pada kedua tema',
                ],
                'acceptance_criteria' => [
                    'Kedua varian tema menerapkan palet warna yang konsisten dan sesuai konteks',
                    'Skala tipografi identik/setara pada kedua tema, tanpa mengorbankan hierarki',
                    'Kontras teks terhadap background memenuhi standar keterbacaan dasar (AA)',
                    'Elemen UI (ikon, button, card) tetap dapat dibedakan jelas pada kedua tema',
                    'Kerapian dan konsistensi desain terjaga di seluruh halaman',
                ],
                'deliverables' => [
                    'Link file Figma',
                    'File export PNG/PDF untuk kedua varian tema',
                ],
            ],

            'Assignment 2: Design System Style Guide' => [
                'title' => 'Tantangan Mini Project: Design System Style Guide dengan Komponen Terdokumentasi',
                'brief' => 'Kembangkan style guide sebelumnya menjadi design system mini yang lebih lengkap: dokumentasikan varian tiap komponen (default, hover, disabled) beserta aturan penggunaannya.',
                'objectives' => [
                    'Susun style guide lengkap (warna, tipografi, spacing, grid) yang konsisten dengan visual design sebelumnya',
                    'Dokumentasikan minimal 3 komponen dasar beserta variannya (default, hover/active, disabled)',
                    'Tuliskan aturan penggunaan (do\'s & don\'ts) untuk tiap komponen',
                ],
                'acceptance_criteria' => [
                    'Style guide mencakup warna, tipografi, spacing, dan grid system secara lengkap',
                    'Minimal 3 komponen didokumentasikan lengkap dengan variannya',
                    'Aturan do\'s & don\'ts tersedia dan membantu konsistensi penggunaan',
                    'Grid system dan spacing berbasis kelipatan konsisten (misal 8px) diterapkan di seluruh komponen',
                    'Dokumen mudah dipahami dan langsung dapat dipakai tim developer/desainer lain',
                ],
                'deliverables' => [
                    'Link file Figma (Design System)',
                    'File PDF dokumentasi style guide',
                ],
            ],

            // ============================================================
            // ---------- Modul 4: HTML & CSS untuk Designer ----------
            // ============================================================
            'Assignment 1: Static Page from Figma Design' => [
                'title' => 'Tantangan Mini Project: Static Page Multi-Section dari Desain Figma',
                'brief' => 'Kembangkan hasil translasi desain ke kode sebelumnya menjadi halaman statis multi-section (hero, fitur, footer, dsb.) yang presisi mengikuti spesifikasi desain Figma.',
                'objectives' => [
                    'Terjemahkan desain Figma minimal 3 section berbeda menjadi struktur HTML semantik',
                    'Terapkan styling CSS (warna, tipografi, spacing) sesuai spesifikasi desain secara presisi',
                    'Susun kode dengan struktur file yang rapi dan mudah dipelihara',
                ],
                'acceptance_criteria' => [
                    'Tampilan hasil kode sesuai desain Figma dari sisi warna, tipografi, dan spacing',
                    'Struktur HTML menggunakan elemen semantik yang tepat (header, section, footer, dsb.)',
                    'Minimal 3 section berbeda diterjemahkan dengan akurat',
                    'Kode CSS terorganisir (tidak menumpuk semua di satu file tanpa struktur)',
                    'Halaman tampil rapi di ukuran layar desktop standar',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'File ZIP source code beserta link desain Figma sebagai referensi',
                ],
            ],

            'Assignment 2: Responsive Landing Page Handoff' => [
                'title' => 'Tantangan Mini Project: Responsive Landing Page dengan Spesifikasi Handoff',
                'brief' => 'Kembangkan landing page sebelumnya agar sepenuhnya responsif (mobile-desktop) menggunakan flexbox, sekaligus siapkan sebagai contoh handoff — sertakan catatan spesifikasi teknis untuk developer.',
                'objectives' => [
                    'Terapkan flexbox agar layout menyesuaikan dari ukuran mobile hingga desktop',
                    'Pertimbangkan keterbatasan implementasi teknis saat menyiapkan breakpoint responsif',
                    'Siapkan catatan spesifikasi handoff (breakpoint, spacing, font-size tiap ukuran layar)',
                ],
                'acceptance_criteria' => [
                    'Layout menyesuaikan dengan baik pada minimal 3 ukuran layar (mobile, tablet, desktop)',
                    'Flexbox diterapkan secara tepat tanpa elemen yang overflow/rusak di breakpoint tertentu',
                    'Tampilan hasil kode tetap sesuai desain asli pada tiap breakpoint',
                    'Catatan spesifikasi handoff (breakpoint, spacing, font-size) tersedia dan jelas',
                    'Kode rapi dan konsisten penamaan class-nya',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'File ZIP source code beserta dokumen spesifikasi handoff',
                ],
            ],

            // ============================================================
            // ---------- Modul 5: Usability Testing ----------
            // ============================================================
            'Assignment 1: Usability Testing Session Report' => [
                'title' => 'Tantangan Mini Project: Usability Testing dengan Multi-Partisipan & Rekomendasi',
                'brief' => 'Kembangkan sesi usability testing sebelumnya dengan melibatkan minimal 3 partisipan berbeda pada prototype yang sama, lalu susun laporan pola temuan lintas partisipan.',
                'objectives' => [
                    'Susun skenario tugas yang jelas dan jalankan sesi testing terhadap minimal 3 partisipan',
                    'Catat temuan observasi (waktu, kesalahan, komentar) secara sistematis untuk tiap partisipan',
                    'Identifikasi pola masalah yang berulang di antara partisipan, bukan sekadar kejadian tunggal',
                ],
                'acceptance_criteria' => [
                    'Minimal 3 sesi testing terdokumentasi dengan skenario tugas yang sama',
                    'Catatan observasi mencakup waktu penyelesaian, kesalahan, dan komentar tiap partisipan',
                    'Pola masalah yang muncul pada lebih dari 1 partisipan teridentifikasi dengan jelas',
                    'Laporan membedakan temuan opini pribadi vs temuan berbasis observasi',
                    'Laporan tersusun rapi dan actionable untuk perbaikan desain',
                ],
                'deliverables' => [
                    'Link file Figma/dokumen catatan sesi',
                    'File PDF/ZIP laporan lengkap hasil testing',
                ],
            ],

            'Assignment 2: Design Iteration Based on Feedback' => [
                'title' => 'Tantangan Mini Project: Iterasi Desain Sebelum-Sesudah dengan Justifikasi',
                'brief' => 'Kembangkan revisi desain sebelumnya dengan menyusun perbandingan before-after yang jelas untuk tiap perubahan, lengkap dengan justifikasi berdasarkan temuan usability testing.',
                'objectives' => [
                    'Prioritaskan temuan usability testing berdasarkan dampaknya terhadap pengalaman pengguna',
                    'Revisi desain untuk mengatasi minimal 3 temuan prioritas tertinggi',
                    'Susun perbandingan before-after untuk tiap perubahan beserta justifikasinya',
                ],
                'acceptance_criteria' => [
                    'Prioritas perbaikan didasarkan pada dampak temuan, bukan preferensi pribadi',
                    'Minimal 3 perubahan desain dilakukan untuk mengatasi temuan prioritas',
                    'Setiap perubahan disertai perbandingan before-after yang jelas',
                    'Justifikasi perubahan menjelaskan hubungan antara temuan dan solusi desain',
                    'Dokumentasi rapi dan mudah ditelusuri',
                ],
                'deliverables' => [
                    'Link file Figma (before & after)',
                    'File PDF/ZIP ringkasan iterasi dan justifikasi',
                ],
            ],

            // ============================================================
            // ---------- Modul 6: Design Handoff & Kolaborasi dengan Developer ----------
            // ============================================================
            'Assignment 1: Design Handoff Documentation' => [
                'title' => 'Tantangan Mini Project: Dokumentasi Handoff Multi-Halaman',
                'brief' => 'Kembangkan dokumentasi handoff sebelumnya untuk mencakup minimal 3 halaman desain sekaligus, lengkap dengan penamaan komponen yang konsisten mengikuti design system.',
                'objectives' => [
                    'Siapkan dokumentasi handoff lengkap (ukuran, warna, spacing, aset) untuk minimal 3 halaman desain',
                    'Pastikan seluruh komponen menggunakan nama yang konsisten mengikuti design system',
                    'Siapkan aset (ikon, gambar) dalam format siap pakai untuk developer',
                ],
                'acceptance_criteria' => [
                    'Spesifikasi handoff (ukuran, warna, spacing) lengkap untuk seluruh halaman yang dicakup',
                    'Penamaan komponen konsisten dan mengikuti design system yang sudah dibuat',
                    'Aset yang dibutuhkan sudah disiapkan dalam format yang sesuai (SVG/PNG, dsb.)',
                    'Dokumentasi terhubung jelas antara satu halaman dengan halaman lain',
                    'Developer dapat memahami spesifikasi tanpa perlu bertanya ulang ke desainer',
                ],
                'deliverables' => [
                    'Link file Figma (mode Inspect aktif)',
                    'File ZIP berisi aset & dokumentasi handoff',
                ],
            ],

            'Assignment 2: Design QA Checklist Exercise' => [
                'title' => 'Tantangan Mini Project: Design QA Checklist & Laporan Temuan Implementasi',
                'brief' => 'Kembangkan checklist Design QA sebelumnya dan gunakan untuk memeriksa hasil implementasi kode (misal dari assignment sebelumnya di modul HTML & CSS), lalu dokumentasikan temuan perbedaannya.',
                'objectives' => [
                    'Susun checklist Design QA yang mencakup warna, spacing, tipografi, dan border radius',
                    'Gunakan checklist untuk membandingkan hasil implementasi dengan desain asli',
                    'Dokumentasikan temuan perbedaan secara spesifik dan actionable untuk developer',
                ],
                'acceptance_criteria' => [
                    'Checklist QA mencakup minimal 4 aspek pemeriksaan (warna, spacing, tipografi, border radius)',
                    'Perbandingan dilakukan terhadap implementasi nyata (screenshot/link), bukan asumsi',
                    'Temuan perbedaan dicatat dengan detail (elemen mana, nilai desain vs implementasi)',
                    'Rekomendasi perbaikan untuk tiap temuan jelas dan dapat langsung dikerjakan',
                    'Dokumentasi checklist rapi dan dapat dipakai ulang untuk QA halaman lain',
                ],
                'deliverables' => [
                    'Link file Figma/checklist',
                    'File PDF/ZIP laporan temuan QA',
                ],
            ],
        ];
    }
}