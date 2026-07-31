<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\CodingExercise;
use Illuminate\Database\Seeder;

class CodingExerciseDataAnalystSeeder extends Seeder
{
    /**
     * Coding exercise untuk seluruh assignment teknis di career
     * Data Analyst, mengikuti pola per-career yang sama seperti
     * CodingExerciseFullStackSeeder & CodingExerciseBackendSeeder.
     *
     * Modul 7 (Komunikasi Insight Data: "Data Story Presentation" dan
     * "Executive Summary Report") sengaja TIDAK dibuatkan coding
     * exercise — keduanya assignment presentasi/tulisan naratif untuk
     * audiens bisnis, bukan latihan kode.
     *
     * Jalankan setelah LearningPathSeeder dan AssignmentDetailSeeder
     * (assignment-nya harus sudah ada). Idempotent lewat updateOrCreate
     * berdasarkan assignment_id.
     *
     * Jalankan:
     *   php artisan db:seed --class=CodingExerciseDataAnalystSeeder
     */
    public function run(): void
    {
        foreach ($this->exerciseData() as $assignmentTitle => $data) {
            $assignment = Assignment::where('title', $assignmentTitle)->first();

            if (! $assignment) {
                $this->command?->warn("CodingExerciseDataAnalystSeeder: assignment tidak ditemukan — {$assignmentTitle}");
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
            // Modul 1: Python untuk Data Analysis
            // ============================================================

            'Assignment 1: Sales Data Exploration with Pandas' => [
                'title' => 'Latihan: Eksplorasi Awal Dataset Penjualan',
                'description' => 'Lengkapi kode di bawah untuk membaca dataset penjualan dan menampilkan informasi dasar (struktur data, tipe kolom, statistik ringkas) sebelum masuk ke analisis lebih lanjut.',
                'learning_objectives' => [
                    'Menggunakan head(), info(), dan describe() untuk eksplorasi awal DataFrame',
                    'Memahami tipe data tiap kolom sebelum melakukan analisis lebih lanjut',
                ],
                'requirements' => [
                    'Tampilkan 5 baris pertama data menggunakan head()',
                    'Tampilkan info tipe data & jumlah non-null tiap kolom menggunakan info()',
                    'Tampilkan statistik ringkas (mean, min, max, dll.) untuk kolom numerik menggunakan describe()',
                    'Cetak jumlah baris dan kolom dataset menggunakan .shape',
                ],
                'test_cases' => [
                    'Output head() menampilkan 5 baris data, bukan seluruh dataset',
                    'Output info() menunjukkan tipe data setiap kolom (int64, object, dll.)',
                    'Output describe() mencakup kolom numerik seperti harga/jumlah, bukan kolom teks',
                    'Shape dataset tercetak dalam format (jumlah_baris, jumlah_kolom)',
                ],
                'language' => 'python',
                'starter_code' => <<<'CODE'
import pandas as pd

df = pd.read_csv('sales_data.csv')

# TODO 1: tampilkan 5 baris pertama dataset

# TODO 2: tampilkan info tipe data & non-null count tiap kolom

# TODO 3: tampilkan statistik ringkas untuk kolom numerik

# TODO 4: cetak jumlah baris dan kolom dataset
CODE,
                'hint' => 'print(df.head()), print(df.info()), print(df.describe()), print(df.shape) — masing-masing method sudah bawaan pandas, tidak perlu parameter tambahan untuk kasus dasar ini.',
            ],

            'Assignment 2: Customer Data Summary Report' => [
                'title' => 'Latihan: Ringkas Data Pelanggan dengan GroupBy',
                'description' => 'Lengkapi kode di bawah untuk meringkas total transaksi pelanggan berdasarkan wilayah menggunakan groupby() dan fungsi agregasi, guna menjawab pertanyaan bisnis "wilayah mana penjualan tertinggi?".',
                'learning_objectives' => [
                    'Menggunakan groupby() untuk mengelompokkan data berdasarkan kategori',
                    'Menerapkan fungsi agregasi (sum, mean, count) pada hasil groupby',
                ],
                'requirements' => [
                    'Kelompokkan data berdasarkan kolom wilayah',
                    'Hitung total transaksi (sum) per wilayah dari kolom total_harga',
                    'Urutkan hasil dari total transaksi tertinggi ke terendah',
                    'Tampilkan wilayah dengan total transaksi tertinggi secara terpisah',
                ],
                'test_cases' => [
                    'Hasil groupby menunjukkan satu baris per wilayah unik, bukan per transaksi',
                    'Total transaksi per wilayah adalah hasil penjumlahan yang benar dari total_harga',
                    'Hasil terurut dari nilai tertinggi ke terendah',
                    'Wilayah dengan penjualan tertinggi berhasil diidentifikasi dan dicetak',
                ],
                'language' => 'python',
                'starter_code' => <<<'CODE'
import pandas as pd

df = pd.read_csv('customer_data.csv')

# TODO 1: kelompokkan data berdasarkan kolom 'wilayah'
#         dan hitung total dari kolom 'total_harga'
ringkasan = None

# TODO 2: urutkan ringkasan dari total tertinggi ke terendah

# TODO 3: cetak wilayah dengan total transaksi tertinggi
CODE,
                'hint' => 'ringkasan = df.groupby("wilayah")["total_harga"].sum().sort_values(ascending=False) lalu print(ringkasan.index[0]) untuk wilayah dengan total tertinggi.',
            ],

            // ============================================================
            // Modul 2: SQL untuk Data Analyst
            // ============================================================

            'Assignment 1: Sales Database Query Challenge' => [
                'title' => 'Latihan: Query Penjualan dengan WHERE & ORDER BY',
                'description' => 'Lengkapi query SQL di bawah untuk menjawab pertanyaan bisnis: transaksi apa saja yang nilainya di atas 500000, diurutkan dari yang terbesar.',
                'learning_objectives' => [
                    'Menulis query SELECT dengan filter WHERE yang tepat',
                    'Mengurutkan hasil query dengan ORDER BY sesuai kebutuhan analisis',
                ],
                'requirements' => [
                    'Ambil hanya kolom yang relevan (id_transaksi, tanggal, total_harga), bukan SELECT *',
                    'Filter transaksi dengan total_harga lebih dari 500000',
                    'Urutkan hasil dari total_harga terbesar ke terkecil',
                ],
                'test_cases' => [
                    'Hasil query hanya berisi transaksi dengan total_harga > 500000',
                    'Hasil terurut menurun berdasarkan total_harga',
                    'Kolom yang dikembalikan sesuai yang diminta, tidak menyertakan seluruh kolom tabel',
                ],
                'language' => 'sql',
                'starter_code' => <<<'CODE'
-- TODO 1: ambil kolom id_transaksi, tanggal, total_harga saja
-- TODO 2: filter transaksi dengan total_harga lebih dari 500000
-- TODO 3: urutkan dari total_harga terbesar ke terkecil

SELECT /* lengkapi kolom di sini */
FROM transaksi
WHERE /* lengkapi kondisi di sini */
ORDER BY /* lengkapi urutan di sini */;
CODE,
                'hint' => 'SELECT id_transaksi, tanggal, total_harga FROM transaksi WHERE total_harga > 500000 ORDER BY total_harga DESC;',
            ],

            'Assignment 2: Multi-table Join Report' => [
                'title' => 'Latihan: JOIN & Agregasi Multi-tabel',
                'description' => 'Lengkapi query di bawah untuk menggabungkan tabel transaksi dan pelanggan, lalu meringkas total belanja per pelanggan menggunakan GROUP BY dan HAVING.',
                'learning_objectives' => [
                    'Menggabungkan data dari beberapa tabel menggunakan JOIN yang tepat',
                    'Meringkas hasil join dengan GROUP BY dan memfilter hasil agregat menggunakan HAVING',
                ],
                'requirements' => [
                    'Gunakan JOIN antara tabel transaksi dan pelanggan berdasarkan id_pelanggan',
                    'Kelompokkan hasil berdasarkan nama pelanggan',
                    'Hitung total belanja (SUM) per pelanggan',
                    'Gunakan HAVING untuk hanya menampilkan pelanggan dengan total belanja di atas 1000000',
                ],
                'test_cases' => [
                    'Hasil query menggabungkan data dari kedua tabel tanpa duplikasi baris yang tidak perlu',
                    'Total belanja per pelanggan dihitung dengan benar dari SUM(total_harga)',
                    'Hanya pelanggan dengan total belanja > 1000000 yang muncul di hasil akhir',
                ],
                'language' => 'sql',
                'starter_code' => <<<'CODE'
-- TODO 1: JOIN tabel transaksi dengan pelanggan berdasarkan id_pelanggan
-- TODO 2: kelompokkan berdasarkan nama pelanggan
-- TODO 3: hitung total belanja per pelanggan
-- TODO 4: filter hanya pelanggan dengan total belanja > 1000000 (gunakan HAVING)

SELECT p.nama, /* lengkapi agregasi di sini */
FROM transaksi t
/* lengkapi JOIN di sini */
GROUP BY /* lengkapi di sini */
HAVING /* lengkapi kondisi di sini */;
CODE,
                'hint' => 'JOIN pelanggan p ON t.id_pelanggan = p.id ... SELECT p.nama, SUM(t.total_harga) AS total_belanja ... GROUP BY p.nama HAVING SUM(t.total_harga) > 1000000;',
            ],

            // ============================================================
            // Modul 3: Data Cleaning & Preparation
            // ============================================================

            'Assignment 1: Messy Dataset Cleanup' => [
                'title' => 'Latihan: Tangani Missing Values, Duplikat & Outlier',
                'description' => 'Lengkapi kode di bawah untuk membersihkan dataset yang masih punya missing values, baris duplikat, dan outlier pada kolom harga menggunakan metode IQR.',
                'learning_objectives' => [
                    'Mengidentifikasi dan menangani missing values dengan strategi yang sesuai konteks',
                    'Mendeteksi outlier menggunakan metode IQR (Interquartile Range)',
                ],
                'requirements' => [
                    'Cek jumlah missing values per kolom sebelum memutuskan strategi penanganannya',
                    'Hapus baris yang duplikat sepenuhnya (semua kolom sama)',
                    'Hitung Q1, Q3, dan IQR pada kolom harga, lalu tandai baris yang menjadi outlier',
                    'Baris outlier dikeluarkan dari dataset final (atau ditandai, sesuai kebutuhan analisis)',
                ],
                'test_cases' => [
                    'Dataset akhir tidak memiliki baris yang identik sepenuhnya (duplikat)',
                    'Jumlah missing values pada dataset akhir berkurang dari data mentah, sesuai strategi yang diterapkan',
                    'Nilai pada kolom harga yang berada di luar rentang [Q1 - 1.5*IQR, Q3 + 1.5*IQR] teridentifikasi sebagai outlier',
                ],
                'language' => 'python',
                'starter_code' => <<<'CODE'
import pandas as pd

df = pd.read_csv('messy_data.csv')

# TODO 1: cek jumlah missing values per kolom
print(df.isnull().sum())

# TODO 2: hapus baris duplikat sepenuhnya

# TODO 3: hitung Q1, Q3, dan IQR pada kolom 'harga'
Q1 = None
Q3 = None
IQR = None

# TODO 4: buat mask untuk baris yang termasuk outlier
#         (di luar rentang Q1 - 1.5*IQR sampai Q3 + 1.5*IQR)

# TODO 5: keluarkan baris outlier dari dataset
df_bersih = df
CODE,
                'hint' => 'Q1 = df["harga"].quantile(0.25); Q3 = df["harga"].quantile(0.75); IQR = Q3 - Q1; batas_bawah = Q1 - 1.5*IQR; batas_atas = Q3 + 1.5*IQR; df_bersih = df[(df["harga"] >= batas_bawah) & (df["harga"] <= batas_atas)]',
            ],

            'Assignment 2: Customer Data Standardization' => [
                'title' => 'Latihan: Seragamkan Format Kategori & Tipe Data',
                'description' => 'Lengkapi kode di bawah untuk menyeragamkan penulisan kategori pelanggan yang tertulis berbeda-beda (misal "Jakarta", "jakarta", "JAKARTA") menjadi satu format konsisten, dan memastikan tipe data kolom sudah sesuai.',
                'learning_objectives' => [
                    'Menyeragamkan format teks (kapitalisasi, spasi berlebih) agar kategori yang sama tidak terpecah',
                    'Mengonversi tipe data kolom (misal tanggal jadi datetime) sebelum analisis lebih lanjut',
                ],
                'requirements' => [
                    'Ubah seluruh isi kolom kota menjadi format Title Case dan hilangkan spasi berlebih di awal/akhir',
                    'Pastikan kolom tanggal_daftar bertipe datetime, bukan string',
                    'Setelah standarisasi, jumlah kategori unik pada kolom kota berkurang dibanding sebelumnya (kategori yang sebelumnya terpecah kini jadi satu)',
                ],
                'test_cases' => [
                    'df["kota"].nunique() setelah standarisasi lebih kecil atau sama dari sebelum standarisasi',
                    'Tidak ada lagi variasi penulisan kota yang sama (misal "jakarta" dan "Jakarta ") sebagai dua kategori berbeda',
                    'df["tanggal_daftar"].dtype menunjukkan tipe datetime, bukan object/string',
                ],
                'language' => 'python',
                'starter_code' => <<<'CODE'
import pandas as pd

df = pd.read_csv('customer_data.csv')

print('Jumlah kategori kota sebelum standarisasi:', df['kota'].nunique())

# TODO 1: hilangkan spasi berlebih di awal/akhir kolom 'kota'

# TODO 2: ubah kolom 'kota' menjadi format Title Case

# TODO 3: konversi kolom 'tanggal_daftar' menjadi tipe datetime

print('Jumlah kategori kota setelah standarisasi:', df['kota'].nunique())
CODE,
                'hint' => 'df["kota"] = df["kota"].str.strip().str.title() lalu df["tanggal_daftar"] = pd.to_datetime(df["tanggal_daftar"]).',
            ],

            // ============================================================
            // Modul 4: Data Visualization
            // ============================================================

            'Assignment 1: Sales Performance Dashboard' => [
                'title' => 'Latihan: Susun Grafik Dashboard Penjualan',
                'description' => 'Lengkapi kode di bawah untuk membuat dua grafik dasar (tren penjualan bulanan dan penjualan per kategori) yang bisa jadi fondasi dashboard, lengkap dengan label dan judul yang jelas.',
                'learning_objectives' => [
                    'Membuat line chart untuk data time-series (tren bulanan)',
                    'Membuat bar chart untuk perbandingan antar kategori',
                ],
                'requirements' => [
                    'Grafik tren bulanan menggunakan line chart dengan sumbu x berupa bulan',
                    'Grafik penjualan per kategori menggunakan bar chart',
                    'Setiap grafik punya judul, label sumbu x, dan label sumbu y yang jelas',
                    'Kedua grafik ditampilkan dalam satu figure menggunakan subplot',
                ],
                'test_cases' => [
                    'Grafik pertama berbentuk line chart, bukan bar/scatter',
                    'Grafik kedua berbentuk bar chart yang membandingkan kategori',
                    'Kedua grafik memiliki judul dan label sumbu yang terisi (tidak kosong)',
                    'Kedua grafik muncul bersebelahan/bersusun dalam satu figure, bukan dua figure terpisah',
                ],
                'language' => 'python',
                'starter_code' => <<<'CODE'
import pandas as pd
import matplotlib.pyplot as plt

df = pd.read_csv('sales_data.csv')

fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(12, 5))

# TODO 1: buat line chart tren penjualan bulanan di ax1
#         (misal df.groupby('bulan')['total_harga'].sum())

# TODO 2: beri judul dan label sumbu x/y untuk ax1

# TODO 3: buat bar chart penjualan per kategori di ax2
#         (misal df.groupby('kategori')['total_harga'].sum())

# TODO 4: beri judul dan label sumbu x/y untuk ax2

plt.tight_layout()
plt.show()
CODE,
                'hint' => 'tren = df.groupby("bulan")["total_harga"].sum(); ax1.plot(tren.index, tren.values); ax1.set_title("Tren Penjualan Bulanan"); ax1.set_xlabel("Bulan"); ax1.set_ylabel("Total Penjualan") — pola serupa untuk ax2.bar(...).',
            ],

            'Assignment 2: Chart Type Comparison Report' => [
                'title' => 'Latihan: Pilih Jenis Chart yang Tepat',
                'description' => 'Lengkapi kode di bawah untuk membuat tiga jenis chart berbeda (bar, line, scatter) dari dataset yang sama, lalu tentukan chart mana yang paling tepat untuk masing-masing pertanyaan analisis.',
                'learning_objectives' => [
                    'Memilih jenis chart yang sesuai dengan struktur data dan tujuan komunikasi',
                    'Memahami kapan bar chart, line chart, dan scatter plot masing-masing paling tepat digunakan',
                ],
                'requirements' => [
                    'Gunakan bar chart untuk membandingkan total penjualan antar kategori produk',
                    'Gunakan line chart untuk menunjukkan tren penjualan dari waktu ke waktu',
                    'Gunakan scatter plot untuk melihat hubungan antara harga dan jumlah terjual',
                    'Setiap chart diberi judul yang menjelaskan apa yang divisualisasikan',
                ],
                'test_cases' => [
                    'Chart perbandingan kategori dibuat dengan plt.bar, bukan plt.plot',
                    'Chart tren waktu dibuat dengan plt.plot, bukan plt.scatter',
                    'Chart hubungan harga vs jumlah terjual dibuat dengan plt.scatter, bukan plt.bar',
                ],
                'language' => 'python',
                'starter_code' => <<<'CODE'
import pandas as pd
import matplotlib.pyplot as plt

df = pd.read_csv('sales_data.csv')

# TODO 1: bar chart — total penjualan per kategori produk

# TODO 2: line chart — tren penjualan dari waktu ke waktu

# TODO 3: scatter plot — hubungan antara harga dan jumlah terjual

plt.show()
CODE,
                'hint' => 'Bar: plt.bar(kategori, total_per_kategori). Line: plt.plot(tanggal, total_per_hari). Scatter: plt.scatter(df["harga"], df["jumlah_terjual"]) — masing-masing sebaiknya di figure/subplot terpisah agar jelas.',
            ],

            // ============================================================
            // Modul 5: Statistik Dasar untuk Analisis
            // ============================================================

            'Assignment 1: Statistical Summary Report' => [
                'title' => 'Latihan: Hitung Mean, Median, dan Standar Deviasi',
                'description' => 'Lengkapi kode di bawah untuk menghitung mean, median, dan standar deviasi dari kolom pendapatan, lalu tentukan apakah median lebih representatif dibanding mean untuk dataset ini.',
                'learning_objectives' => [
                    'Menghitung dan menginterpretasikan mean, median, dan standar deviasi',
                    'Mengidentifikasi kapan median lebih representatif dibanding mean akibat outlier',
                ],
                'requirements' => [
                    'Hitung mean, median, dan standar deviasi dari kolom pendapatan',
                    'Bandingkan selisih antara mean dan median',
                    'Jika selisih mean-median cukup besar, cetak catatan bahwa data kemungkinan memiliki outlier/skewed',
                ],
                'test_cases' => [
                    'Nilai mean, median, dan std dihitung dengan benar menggunakan method pandas bawaan (bukan hardcode)',
                    'Program mencetak perbandingan mean vs median',
                    'Program memberi catatan tambahan ketika selisih mean-median melebihi ambang tertentu (misal 20% dari median)',
                ],
                'language' => 'python',
                'starter_code' => <<<'CODE'
import pandas as pd

df = pd.read_csv('income_data.csv')

# TODO 1: hitung mean, median, dan standar deviasi dari kolom 'pendapatan'
mean_val = None
median_val = None
std_val = None

print(f'Mean: {mean_val}, Median: {median_val}, Std: {std_val}')

# TODO 2: bandingkan mean dan median, cetak catatan jika selisihnya
#         signifikan (misal lebih dari 20% dari median)
CODE,
                'hint' => 'mean_val = df["pendapatan"].mean(); median_val = df["pendapatan"].median(); std_val = df["pendapatan"].std(); selisih = abs(mean_val - median_val) / median_val — kalau selisih > 0.2, kemungkinan ada outlier yang menarik mean.',
            ],

            'Assignment 2: Correlation Analysis Project' => [
                'title' => 'Latihan: Hitung & Interpretasi Korelasi Dua Variabel',
                'description' => 'Lengkapi kode di bawah untuk menghitung korelasi antara jam belajar dan nilai ujian, lalu interpretasikan kekuatan dan arah hubungannya tanpa menyimpulkan hubungan sebab-akibat secara berlebihan.',
                'learning_objectives' => [
                    'Menghitung koefisien korelasi antara dua variabel numerik',
                    'Menjelaskan perbedaan korelasi dengan hubungan sebab-akibat',
                ],
                'requirements' => [
                    'Hitung korelasi Pearson antara kolom jam_belajar dan nilai_ujian',
                    'Interpretasikan kekuatan korelasi (lemah/sedang/kuat) berdasarkan nilai koefisiennya',
                    'Tentukan arah korelasi (positif/negatif) berdasarkan tandanya',
                    'Tambahkan catatan bahwa korelasi tidak serta-merta berarti sebab-akibat',
                ],
                'test_cases' => [
                    'Koefisien korelasi dihitung dengan .corr(), bukan dihitung manual',
                    'Program mencetak interpretasi kekuatan korelasi berdasarkan nilai koefisien (misal >0.7 kuat, 0.3-0.7 sedang, <0.3 lemah)',
                    'Program mencetak catatan pengingat soal korelasi vs kausalitas',
                ],
                'language' => 'python',
                'starter_code' => <<<'CODE'
import pandas as pd

df = pd.read_csv('study_data.csv')

# TODO 1: hitung korelasi Pearson antara 'jam_belajar' dan 'nilai_ujian'
korelasi = None

print(f'Koefisien korelasi: {korelasi}')

# TODO 2: interpretasikan kekuatan korelasi (lemah/sedang/kuat)
#         berdasarkan nilai absolut korelasi

# TODO 3: cetak catatan bahwa korelasi bukan berarti sebab-akibat
CODE,
                'hint' => 'korelasi = df["jam_belajar"].corr(df["nilai_ujian"]) — untuk interpretasi, gunakan if/elif berdasarkan abs(korelasi) terhadap ambang 0.3 dan 0.7.',
            ],

            // ============================================================
            // Modul 6: R untuk Data Analysis
            // ============================================================

            'Assignment 1: R Data Manipulation with dplyr' => [
                'title' => 'Latihan: Olah Data dengan dplyr & Pipe Operator',
                'description' => 'Lengkapi kode R di bawah untuk memfilter, memilih kolom, dan meringkas data penjualan menggunakan fungsi dplyr yang dirangkai dengan pipe operator (%>%).',
                'learning_objectives' => [
                    'Menggunakan fungsi dasar dplyr: filter(), select(), mutate(), summarise()',
                    'Merangkai beberapa operasi data menggunakan pipe operator agar tetap terbaca',
                ],
                'requirements' => [
                    'Filter data hanya untuk transaksi dengan status "selesai"',
                    'Pilih kolom yang relevan saja (id_transaksi, kategori, total_harga)',
                    'Tambahkan kolom baru pajak yang nilainya 10% dari total_harga menggunakan mutate()',
                    'Ringkas total_harga per kategori menggunakan group_by() dan summarise()',
                ],
                'test_cases' => [
                    'Hasil akhir hanya berisi transaksi dengan status "selesai"',
                    'Kolom pajak muncul dengan nilai yang benar (10% dari total_harga)',
                    'Ringkasan per kategori menunjukkan total_harga yang terjumlah dengan benar',
                    'Seluruh operasi dirangkai dengan pipe operator %>%, bukan dipanggil terpisah baris demi baris',
                ],
                'language' => 'r',
                'starter_code' => <<<'CODE'
library(dplyr)

data_penjualan <- read.csv('penjualan.csv')

hasil <- data_penjualan %>%
  # TODO 1: filter hanya status == "selesai"
  # TODO 2: select kolom id_transaksi, kategori, total_harga
  # TODO 3: mutate kolom baru 'pajak' = 10% dari total_harga
  # TODO 4: group_by kategori, lalu summarise total total_harga per kategori

print(hasil)
CODE,
                'hint' => 'filter(status == "selesai") %>% select(id_transaksi, kategori, total_harga) %>% mutate(pajak = total_harga * 0.1) %>% group_by(kategori) %>% summarise(total = sum(total_harga))',
            ],

            'Assignment 2: Visualization with ggplot2' => [
                'title' => 'Latihan: Grafik Grammar of Graphics dengan ggplot2',
                'description' => 'Lengkapi kode R di bawah untuk membuat bar chart total penjualan per kategori menggunakan ggplot2, lengkap dengan judul, label sumbu, dan tema yang rapi untuk laporan formal.',
                'learning_objectives' => [
                    'Membangun grafik menggunakan pendekatan grammar of graphics (ggplot + geom + aes)',
                    'Menambahkan label, judul, dan tema agar grafik siap dipakai di laporan formal',
                ],
                'requirements' => [
                    'Gunakan geom_bar atau geom_col untuk menampilkan total penjualan per kategori',
                    'Tambahkan labs() untuk judul grafik, label sumbu x, dan label sumbu y',
                    'Gunakan salah satu tema bawaan ggplot2 (misal theme_minimal()) agar tampilan rapi',
                ],
                'test_cases' => [
                    'Grafik menampilkan satu batang per kategori dengan tinggi sesuai total penjualannya',
                    'Judul grafik dan label kedua sumbu terisi (tidak default/kosong)',
                    'Tema grafik bukan tema default abu-abu bawaan ggplot2',
                ],
                'language' => 'r',
                'starter_code' => <<<'CODE'
library(ggplot2)
library(dplyr)

ringkasan <- data_penjualan %>%
  group_by(kategori) %>%
  summarise(total = sum(total_harga))

# TODO 1: buat bar chart dari ringkasan menggunakan geom_col
#         dengan x = kategori, y = total

# TODO 2: tambahkan labs() untuk judul dan label sumbu x/y

# TODO 3: tambahkan tema (misal theme_minimal()) agar tampilan rapi

ggplot(ringkasan, aes(x = kategori, y = total)) 
CODE,
                'hint' => 'ggplot(ringkasan, aes(x = kategori, y = total)) + geom_col() + labs(title = "Total Penjualan per Kategori", x = "Kategori", y = "Total Penjualan") + theme_minimal()',
            ],
        ];
    }
}