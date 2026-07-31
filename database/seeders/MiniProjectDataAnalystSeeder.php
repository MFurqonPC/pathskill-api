<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MiniProject;
use Illuminate\Database\Seeder;

class MiniProjectDataAnalystSeeder extends Seeder
{
    /**
     * Mini project untuk seluruh assignment praktik di track Data Analyst
     * (Modul 1-7). Judul assignment di sini HARUS PERSIS sama dengan yang
     * dibuat AddAssignmentsToExistingModulesSeeder (format
     * "Assignment N: {title}") — jalankan seeder itu DULU sebelum ini.
     *
     * Jalankan setelah AddAssignmentsToExistingModulesSeeder,
     * AssignmentDetailSeeder & CodingExerciseDataAnalystSeeder
     * (assignment-nya harus sudah ada). Idempotent lewat updateOrCreate
     * berdasarkan assignment_id, aman dijalankan berkali-kali.
     *
     * Jalankan:
     *   php artisan db:seed --class=MiniProjectDataAnalystSeeder
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

        $this->command?->info("MiniProjectDataAnalystSeeder: {$created} mini project berhasil dibuat/diperbarui.");

        if (! empty($skipped)) {
            $this->command?->warn(
                'MiniProjectDataAnalystSeeder: assignment tidak ditemukan (jalankan AddAssignmentsToExistingModulesSeeder dulu) — '
                . implode(', ', $skipped)
            );
        }
    }

    private function projectData(): array
    {
        return [

            // ============================================================
            // ---------- Modul 1: Python untuk Data Analysis ----------
            // ============================================================
            'Assignment 1: Sales Data Exploration with Pandas' => [
                'title' => 'Tantangan Mini Project: Eksplorasi Data Penjualan End-to-End',
                'brief' => 'Kembangkan Latihan Coding eksplorasi Pandas sebelumnya jadi notebook eksplorasi data penjualan yang lebih lengkap: mulai dari pemeriksaan struktur data hingga menjawab minimal 3 pertanyaan bisnis sederhana langsung dari DataFrame.',
                'objectives' => [
                    'Lakukan eksplorasi awal dataset penjualan (head, info, describe, cek missing value)',
                    'Susun data ke dalam struktur Series/DataFrame yang siap dianalisis lebih lanjut',
                    'Jawab minimal 3 pertanyaan bisnis sederhana (misal produk terlaris, bulan penjualan tertinggi) langsung dari data',
                ],
                'acceptance_criteria' => [
                    'Notebook menampilkan eksplorasi awal (head/info/describe) sebelum masuk ke analisis lanjutan',
                    'Setiap pertanyaan bisnis dijawab dengan kode yang jelas dan hasil yang benar',
                    'Interpretasi hasil ditulis dalam bahasa yang mudah dipahami, bukan cuma output kode mentah',
                    'Notebook tersusun rapi dengan heading/markdown yang menjelaskan tiap tahap',
                    'Tidak ada error saat notebook dijalankan dari awal hingga akhir (Run All)',
                ],
                'deliverables' => [
                    'File notebook (.ipynb) hasil eksplorasi',
                    'Link Google Colab/Kaggle (opsional, jika dikerjakan online)',
                    'File ZIP berisi notebook dan dataset yang dipakai, sertakan README singkat berisi ringkasan temuan',
                ],
            ],

            'Assignment 2: Customer Data Summary Report' => [
                'title' => 'Tantangan Mini Project: Laporan Ringkasan Pelanggan per Segmen',
                'brief' => 'Kembangkan Latihan Coding groupby sebelumnya jadi laporan ringkasan pelanggan yang lebih lengkap: kelompokkan data pelanggan berdasarkan minimal 2 dimensi berbeda (misal wilayah dan kategori produk) dan simpulkan insight-nya.',
                'objectives' => [
                    'Gunakan groupby() dan fungsi agregasi untuk meringkas data pelanggan pada minimal 2 dimensi berbeda',
                    'Bandingkan hasil ringkasan antar segmen untuk menemukan pola yang menonjol',
                    'Tulis kesimpulan singkat dari hasil ringkasan yang relevan untuk keputusan bisnis',
                ],
                'acceptance_criteria' => [
                    'Hasil groupby akurat dan sesuai dengan fungsi agregasi yang dipilih (sum, mean, count, dsb.)',
                    'Minimal 2 dimensi pengelompokan berbeda ditampilkan dan dibandingkan',
                    'Insight yang ditulis didukung langsung oleh angka hasil ringkasan, bukan asumsi tanpa dasar',
                    'Notebook tersusun rapi dengan penjelasan di setiap tahap analisis',
                    'Tidak ada error saat notebook dijalankan dari awal hingga akhir',
                ],
                'deliverables' => [
                    'File notebook (.ipynb) hasil ringkasan',
                    'Link Google Colab/Kaggle (opsional)',
                    'File ZIP berisi notebook dan dataset, sertakan README singkat berisi insight utama yang ditemukan',
                ],
            ],

            // ============================================================
            // ---------- Modul 2: SQL untuk Data Analyst ----------
            // ============================================================
            'Assignment 1: Sales Database Query Challenge' => [
                'title' => 'Tantangan Mini Project: Menjawab Pertanyaan Bisnis dengan SQL',
                'brief' => 'Kembangkan Latihan Coding query dasar sebelumnya jadi kumpulan minimal 5 query SQL yang menjawab pertanyaan bisnis nyata seputar penjualan (misal produk terlaris per bulan, pelanggan dengan transaksi terbanyak).',
                'objectives' => [
                    'Susun minimal 5 pertanyaan bisnis dan jawab masing-masing dengan satu query SQL',
                    'Gunakan WHERE dan ORDER BY secara tepat sesuai kebutuhan tiap pertanyaan',
                    'Dokumentasikan setiap query dengan penjelasan singkat maksud dan hasilnya',
                ],
                'acceptance_criteria' => [
                    'Setiap query menghasilkan jawaban yang benar dan sesuai pertanyaan bisnis yang diajukan',
                    'WHERE dan ORDER BY dipakai secara tepat, tidak asal ditambahkan',
                    'Query ditulis rapi dan mudah dibaca (indentasi, penamaan kolom/alias jelas)',
                    'Setiap query disertai komentar/penjelasan singkat tentang tujuannya',
                    'Tidak ada query yang mengembalikan hasil yang salah atau ambigu',
                ],
                'deliverables' => [
                    'File .sql berisi seluruh query beserta komentar',
                    'File ZIP/README singkat berisi daftar pertanyaan bisnis dan ringkasan jawabannya',
                ],
            ],

            'Assignment 2: Multi-table Join Report' => [
                'title' => 'Tantangan Mini Project: Laporan Agregat dari Banyak Tabel',
                'brief' => 'Kembangkan Latihan Coding JOIN sebelumnya jadi laporan agregat yang menggabungkan minimal 3 tabel berbeda, memakai kombinasi INNER JOIN dan LEFT JOIN sesuai kebutuhan, lalu diringkas dengan GROUP BY dan HAVING.',
                'objectives' => [
                    'Gabungkan minimal 3 tabel terkait memakai jenis JOIN yang tepat sesuai kebutuhan data',
                    'Ringkas hasil join dengan GROUP BY dan filter tambahan memakai HAVING',
                    'Pastikan hasil akhir tidak mengandung duplikasi data akibat join yang tidak tepat',
                ],
                'acceptance_criteria' => [
                    'Jenis JOIN yang dipilih (INNER/LEFT) sesuai dengan kebutuhan data (misal data yang boleh kosong pakai LEFT JOIN)',
                    'Hasil GROUP BY dan HAVING akurat dan menjawab kebutuhan laporan yang diminta',
                    'Tidak ada baris data yang terduplikasi akibat kesalahan kondisi join',
                    'Query didokumentasikan dengan komentar yang menjelaskan alur logikanya',
                    'Hasil akhir laporan mudah dibaca dan langsung bisa dipakai untuk keputusan bisnis',
                ],
                'deliverables' => [
                    'File .sql berisi seluruh query beserta komentar',
                    'File ZIP/README singkat berisi penjelasan struktur tabel yang di-join dan tujuan laporan',
                ],
            ],

            // ============================================================
            // ---------- Modul 3: Data Cleaning & Preparation ----------
            // ============================================================
            'Assignment 1: Messy Dataset Cleanup' => [
                'title' => 'Tantangan Mini Project: Pembersihan Dataset Berantakan End-to-End',
                'brief' => 'Kembangkan Latihan Coding data cleaning sebelumnya jadi proses pembersihan dataset yang lebih lengkap: tangani missing values, data duplikat, dan outlier sekaligus dalam satu alur kerja yang terdokumentasi.',
                'objectives' => [
                    'Identifikasi dan tangani missing values dengan strategi yang sesuai tiap kolom (hapus, isi rata-rata, isi modus, dsb.)',
                    'Deteksi dan tangani data duplikat pada dataset',
                    'Deteksi outlier menggunakan metode IQR dan putuskan penanganannya (hapus/cap/biarkan dengan alasan)',
                ],
                'acceptance_criteria' => [
                    'Setiap keputusan penanganan missing value disertai alasan yang masuk akal, bukan asal hapus semua baris',
                    'Data duplikat terdeteksi dan ditangani tanpa menghilangkan data yang sebenarnya valid',
                    'Outlier terdeteksi dengan metode IQR dan penanganannya dijelaskan dengan alasan yang jelas',
                    'Dataset hasil akhir bisa diverifikasi sudah bersih (tidak ada missing value/duplikat yang tersisa tanpa alasan)',
                    'Notebook terdokumentasi rapi, tiap tahap cleaning dijelaskan dengan markdown',
                ],
                'deliverables' => [
                    'File notebook (.ipynb) proses cleaning',
                    'File dataset hasil cleaning (.csv)',
                    'File ZIP/README singkat berisi ringkasan keputusan cleaning yang diambil',
                ],
            ],

            'Assignment 2: Customer Data Standardization' => [
                'title' => 'Tantangan Mini Project: Standarisasi Data Pelanggan Multi-Sumber',
                'brief' => 'Kembangkan Latihan Coding standarisasi sebelumnya jadi proses standarisasi data pelanggan yang lebih lengkap: seragamkan minimal 3 kolom kategori/teks yang formatnya berbeda-beda, plus perbaikan tipe data di seluruh dataset.',
                'objectives' => [
                    'Seragamkan format teks dan kategori pada minimal 3 kolom yang penulisannya tidak konsisten',
                    'Pastikan tipe data setiap kolom sudah sesuai (tanggal jadi datetime, angka jadi numeric, dsb.)',
                    'Validasi hasil standarisasi dengan groupby untuk memastikan kategori tidak terpecah lagi',
                ],
                'acceptance_criteria' => [
                    'Kategori yang sebelumnya tertulis berbeda-beda (misal "Jakarta"/"jakarta"/"JKT") sudah seragam',
                    'Tipe data setiap kolom sudah sesuai kebutuhan analisis (bukan semua object/string)',
                    'Hasil groupby pada kolom yang distandarisasi tidak lagi terpecah menjadi kategori yang seharusnya sama',
                    'Proses standarisasi didokumentasikan dengan jelas tahap demi tahap',
                    'Dataset hasil akhir siap dipakai untuk analisis/visualisasi lanjutan',
                ],
                'deliverables' => [
                    'File notebook (.ipynb) proses standarisasi',
                    'File dataset hasil standarisasi (.csv)',
                    'File ZIP/README singkat berisi daftar kolom yang distandarisasi dan aturannya',
                ],
            ],

            // ============================================================
            // ---------- Modul 4: Data Visualization ----------
            // ============================================================
            'Assignment 1: Sales Performance Dashboard' => [
                'title' => 'Tantangan Mini Project: Dashboard Performa Penjualan',
                'brief' => 'Kembangkan Latihan Coding dashboard sebelumnya jadi dashboard performa penjualan yang lebih lengkap: minimal 4 visual dengan metrik utama yang relevan, disusun dengan hierarki visual yang jelas untuk tim bisnis.',
                'objectives' => [
                    'Tentukan minimal 4 metrik utama yang relevan bagi tim bisnis (misal total revenue, tren bulanan, top produk, top wilayah)',
                    'Susun visual-visual tersebut dalam satu tampilan dashboard dengan hierarki yang jelas',
                    'Pastikan setiap visual punya label, judul, dan skala yang mudah dibaca tanpa penjelasan tambahan',
                ],
                'acceptance_criteria' => [
                    'Metrik yang ditampilkan relevan dan langsung berguna bagi pengambilan keputusan bisnis',
                    'Elemen dashboard disusun dengan hierarki visual jelas — metrik terpenting mudah ditemukan lebih dulu',
                    'Setiap grafik punya judul, label sumbu, dan satuan yang jelas',
                    'Tidak ada visual yang membingungkan atau berpotensi disalahartikan',
                    'Dashboard bisa dipahami dalam waktu singkat tanpa penjelasan lisan',
                ],
                'deliverables' => [
                    'File notebook (.ipynb) atau file gambar dashboard',
                    'File ZIP/README singkat berisi penjelasan metrik yang dipilih dan alasannya',
                ],
            ],

            'Assignment 2: Chart Type Comparison Report' => [
                'title' => 'Tantangan Mini Project: Laporan Perbandingan Jenis Chart',
                'brief' => 'Kembangkan Latihan Coding pemilihan chart sebelumnya jadi laporan yang membandingkan minimal 4 jenis chart berbeda (bar, line, pie, scatter) pada dataset yang sama, lengkap dengan penjelasan kapan tiap jenis chart tepat maupun tidak tepat dipakai.',
                'objectives' => [
                    'Buat minimal 4 jenis chart berbeda dari dataset yang sama untuk tujuan komunikasi yang berbeda-beda',
                    'Jelaskan alasan pemilihan tiap jenis chart sesuai struktur data dan tujuannya',
                    'Identifikasi minimal 1 contoh potensi kesalahan interpretasi jika jenis chart yang salah dipakai',
                ],
                'acceptance_criteria' => [
                    'Jenis chart yang dipilih sesuai dengan struktur data dan tujuan komunikasinya (misal line untuk tren waktu, bukan pie)',
                    'Setiap chart punya label, skala, dan warna yang jelas dan tidak menyesatkan',
                    'Penjelasan alasan pemilihan chart ditulis dengan logis, bukan sekadar preferensi visual',
                    'Contoh kesalahan interpretasi yang diberikan masuk akal dan relevan dengan chart yang dibahas',
                    'Laporan tersusun rapi dan mudah diikuti',
                ],
                'deliverables' => [
                    'File notebook (.ipynb) atau dokumen laporan berisi seluruh chart',
                    'File ZIP/README singkat berisi ringkasan perbandingan jenis chart',
                ],
            ],

            // ============================================================
            // ---------- Modul 5: Statistik Dasar untuk Analisis ----------
            // ============================================================
            'Assignment 1: Statistical Summary Report' => [
                'title' => 'Tantangan Mini Project: Laporan Statistik Deskriptif Dataset Nyata',
                'brief' => 'Kembangkan Latihan Coding statistik dasar sebelumnya jadi laporan statistik deskriptif yang lebih lengkap: hitung dan interpretasikan mean, median, dan standar deviasi pada minimal 3 kolom numerik dataset, termasuk kasus di mana mean dan median berbeda jauh.',
                'objectives' => [
                    'Hitung mean, median, dan standar deviasi untuk minimal 3 kolom numerik berbeda',
                    'Identifikasi minimal 1 kolom di mana mean dan median berbeda signifikan, lalu jelaskan penyebabnya',
                    'Interpretasikan standar deviasi untuk menjelaskan tingkat variasi data',
                ],
                'acceptance_criteria' => [
                    'Perhitungan statistik (mean, median, std) akurat sesuai data yang dipakai',
                    'Penjelasan kapan median lebih representatif dibanding mean didukung dengan contoh nyata dari dataset',
                    'Interpretasi standar deviasi ditulis dengan bahasa yang mudah dipahami, bukan cuma angka',
                    'Laporan disusun dengan struktur yang jelas (per kolom atau per metrik)',
                    'Tidak ada kesalahan interpretasi statistik (misal menyamakan variasi tinggi dengan data buruk tanpa konteks)',
                ],
                'deliverables' => [
                    'File notebook (.ipynb) hasil analisis statistik',
                    'File ZIP/README singkat berisi ringkasan temuan statistik utama',
                ],
            ],

            'Assignment 2: Correlation Analysis Project' => [
                'title' => 'Tantangan Mini Project: Analisis Korelasi Antar Variabel Bisnis',
                'brief' => 'Kembangkan Latihan Coding korelasi sebelumnya jadi analisis korelasi yang lebih lengkap: hitung korelasi antar minimal 3 pasang variabel numerik pada dataset, interpretasikan kekuatan hubungannya, dan tegaskan batasan korelasi vs kausalitas.',
                'objectives' => [
                    'Hitung korelasi antar minimal 3 pasang variabel numerik yang relevan secara bisnis',
                    'Interpretasikan kekuatan dan arah korelasi (positif/negatif, lemah/kuat) untuk tiap pasangan',
                    'Jelaskan dengan contoh konkret kenapa korelasi tidak selalu berarti sebab-akibat',
                ],
                'acceptance_criteria' => [
                    'Perhitungan koefisien korelasi akurat sesuai data yang dipakai',
                    'Interpretasi kekuatan dan arah korelasi konsisten dengan nilai koefisien yang dihasilkan',
                    'Disertakan minimal 1 visualisasi (scatter plot) yang mendukung interpretasi korelasi',
                    'Penjelasan korelasi vs kausalitas menggunakan contoh yang relevan dengan dataset yang dianalisis',
                    'Laporan tidak menyimpulkan hubungan sebab-akibat hanya dari korelasi semata',
                ],
                'deliverables' => [
                    'File notebook (.ipynb) hasil analisis korelasi',
                    'File ZIP/README singkat berisi ringkasan pasangan variabel dan hasil interpretasinya',
                ],
            ],

            // ============================================================
            // ---------- Modul 6: R untuk Data Analysis ----------
            // ============================================================
            'Assignment 1: R Data Manipulation with dplyr' => [
                'title' => 'Tantangan Mini Project: Pipeline Manipulasi Data dengan dplyr',
                'brief' => 'Kembangkan Latihan Coding dplyr sebelumnya jadi satu pipeline manipulasi data yang lebih lengkap di R: gabungkan filter, select, mutate, dan summarise dalam satu rangkaian pipe operator untuk menjawab pertanyaan analisis tertentu.',
                'objectives' => [
                    'Susun satu pipeline yang menggabungkan minimal 4 fungsi dplyr (filter, select, mutate, summarise) memakai pipe operator',
                    'Buat kolom baru hasil transformasi (mutate) yang relevan dengan tujuan analisis',
                    'Hasilkan ringkasan akhir (summarise) yang menjawab pertanyaan analisis yang diajukan',
                ],
                'acceptance_criteria' => [
                    'Pipeline dplyr tersusun rapi dan terbaca mengikuti alur logis dari filter hingga summarise',
                    'Kolom hasil mutate() dihitung dengan benar dan relevan dengan analisis',
                    'Hasil summarise() menjawab pertanyaan analisis yang telah ditetapkan di awal',
                    'Kode R menggunakan pipe operator secara konsisten, tidak dicampur gaya nested function tanpa alasan',
                    'Script berjalan tanpa error dari awal sampai akhir',
                ],
                'deliverables' => [
                    'File .R atau .Rmd berisi pipeline analisis',
                    'File ZIP/README singkat berisi pertanyaan analisis dan ringkasan hasilnya',
                ],
            ],

            'Assignment 2: Visualization with ggplot2' => [
                'title' => 'Tantangan Mini Project: Kumpulan Grafik Formal dengan ggplot2',
                'brief' => 'Kembangkan Latihan Coding ggplot2 sebelumnya jadi kumpulan minimal 3 grafik berbeda dari hasil pipeline dplyr sebelumnya, lengkap dengan label, judul, dan tema yang siap dipakai untuk laporan formal.',
                'objectives' => [
                    'Buat minimal 3 grafik berbeda menggunakan pendekatan grammar of graphics pada ggplot2',
                    'Tambahkan label sumbu, judul, dan tema yang konsisten di seluruh grafik',
                    'Pastikan jenis grafik yang dipilih sesuai dengan struktur data hasil pipeline dplyr sebelumnya',
                ],
                'acceptance_criteria' => [
                    'Setiap grafik menggunakan jenis geom yang tepat sesuai data dan tujuan visualisasinya',
                    'Seluruh grafik memiliki judul, label sumbu, dan satuan yang jelas',
                    'Tema visual konsisten di seluruh grafik (warna, font, gaya)',
                    'Grafik siap dipakai langsung untuk laporan formal tanpa perlu diedit ulang',
                    'Script berjalan tanpa error dari awal sampai akhir',
                ],
                'deliverables' => [
                    'File .R atau .Rmd berisi kode pembuatan grafik',
                    'File gambar hasil ekspor tiap grafik (.png)',
                    'File ZIP/README singkat berisi penjelasan singkat tiap grafik',
                ],
            ],

            // ============================================================
            // ---------- Modul 7: Komunikasi Insight Data ----------
            // ============================================================
            'Assignment 1: Data Story Presentation' => [
                'title' => 'Tantangan Mini Project: Presentasi Data Story dari Analisis Sebelumnya',
                'brief' => 'Gunakan hasil dashboard dan analisis korelasi kamu sebelumnya untuk menyusun satu presentasi data story lengkap dengan struktur konteks-temuan-rekomendasi, ditujukan untuk audiens non-teknis di sebuah perusahaan fiktif.',
                'objectives' => [
                    'Susun presentasi dengan struktur konteks-temuan-rekomendasi yang jelas dan runtut',
                    'Kaitkan minimal 2 temuan data dengan implikasi bisnis yang konkret',
                    'Gunakan visual pendukung (chart/dashboard) yang relevan dengan narasi yang dibangun',
                ],
                'acceptance_criteria' => [
                    'Struktur presentasi mengikuti alur konteks-temuan-rekomendasi, bukan sekadar kumpulan chart',
                    'Setiap temuan data dikaitkan dengan implikasi bisnis yang jelas, bukan hanya deskripsi angka',
                    'Visual pendukung yang dipakai relevan dan mendukung narasi, tidak sekadar tempelan',
                    'Rekomendasi yang diberikan konkret dan bisa ditindaklanjuti',
                    'Presentasi mudah diikuti oleh audiens non-teknis tanpa penjelasan istilah tambahan',
                ],
                'deliverables' => [
                    'File presentasi (.pptx atau link Google Slides)',
                    'File ZIP/README singkat berisi ringkasan temuan dan rekomendasi utama',
                ],
            ],

            'Assignment 2: Executive Summary Report' => [
                'title' => 'Tantangan Mini Project: Ringkasan Eksekutif untuk Manajemen',
                'brief' => 'Kembangkan Data Story Presentation sebelumnya jadi ringkasan eksekutif tertulis (1-2 halaman) yang bisa dibaca manajemen dalam waktu singkat, dengan bahasa yang bebas istilah teknis dan angka yang selalu diberi konteks pembanding.',
                'objectives' => [
                    'Tulis ringkasan eksekutif maksimal 2 halaman yang mencakup konteks, temuan utama, dan rekomendasi',
                    'Sampaikan setiap angka penting dengan konteks pembanding (misal dibanding bulan/periode sebelumnya)',
                    'Hindari istilah teknis data (seperti "outlier", "korelasi") tanpa penjelasan sederhana',
                ],
                'acceptance_criteria' => [
                    'Bahasa yang dipakai bisa dipahami audiens non-teknis tanpa latar belakang data',
                    'Setiap angka utama disertai konteks pembanding, bukan angka tunggal tanpa makna',
                    'Rekomendasi yang diberikan actionable dan spesifik, bukan saran umum',
                    'Struktur laporan jelas: ringkasan di awal, detail pendukung di bagian berikutnya',
                    'Panjang laporan sesuai batas yang ditentukan (maksimal 2 halaman)',
                ],
                'deliverables' => [
                    'File dokumen (.docx atau link Google Docs)',
                    'File ZIP/README singkat berisi versi final ringkasan eksekutif',
                ],
            ],
        ];
    }
}