<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

/**
 * Quiz untuk seluruh assignment career "Data Analyst"
 * (Modul 1-7: Python untuk Data Analysis, SQL untuk Data Analyst,
 * Data Cleaning & Preparation, Data Visualization,
 * Statistik Dasar untuk Analisis, R untuk Data Analysis,
 * Komunikasi Insight Data).
 *
 * Jalankan setelah LearningPathSeeder & AddAssignmentsToExistingModulesSeeder
 * (assignment-nya harus sudah ada). Idempotent: quiz di-updateOrCreate per
 * assignment, dan soal lama dihapus dulu sebelum di-recreate.
 */
class QuizDataAnalystSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->quizData() as $assignmentTitle => $data) {
            $assignment = Assignment::where('title', $assignmentTitle)->first();

            if (! $assignment) {
                $this->command?->warn("QuizDataAnalystSeeder: assignment tidak ditemukan — {$assignmentTitle}");
                continue;
            }

            $quiz = Quiz::updateOrCreate(
                ['assignment_id' => $assignment->id],
                ['title' => $data['title']]
            );

            $quiz->questions()->delete();

            foreach ($data['questions'] as $index => $q) {
                $question = $quiz->questions()->create([
                    'question' => $q['question'],
                    'explanation' => $q['explanation'],
                    'order' => $index + 1,
                ]);

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

    private function quizData(): array
    {
        return [

            // ============================================================
            // MODUL 1: Python untuk Data Analysis
            // ============================================================
            'Assignment 1: Sales Data Exploration with Pandas' => [
                'title' => 'Quiz: Pandas Dasar (Series, DataFrame & Eksplorasi Data)',
                'questions' => [
                    [
                        'question' => 'Kenapa Python banyak dipilih untuk analisis data dibanding bahasa pemrograman umum lainnya?',
                        'explanation' => 'Python punya ekosistem library khusus (pandas, NumPy, matplotlib, seaborn) yang dirancang buat mempermudah pengolahan data, sehingga alur kerja analisis data dari baca data sampai visualisasi bisa dilakukan dengan kode yang relatif ringkas.',
                        'options' => [
                            ['text' => 'Didukung ekosistem library lengkap (pandas, NumPy, matplotlib) yang dirancang khusus untuk data', 'correct' => true],
                            ['text' => 'Python adalah satu-satunya bahasa yang bisa membaca file CSV', 'correct' => false],
                            ['text' => 'Python tidak bisa dipakai untuk keperluan selain analisis data', 'correct' => false],
                            ['text' => 'Python berjalan lebih cepat dari semua bahasa kompilasi', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa perbedaan utama antara Series dan DataFrame di pandas?',
                        'explanation' => 'Series adalah struktur data satu dimensi (mirip satu kolom), sedangkan DataFrame adalah struktur dua dimensi berbentuk tabel dengan baris dan kolom — setiap kolom di DataFrame sebenarnya adalah sebuah Series.',
                        'options' => [
                            ['text' => 'Series satu dimensi (mirip satu kolom), DataFrame dua dimensi berbentuk tabel', 'correct' => true],
                            ['text' => 'Series hanya bisa menyimpan angka, DataFrame hanya bisa menyimpan teks', 'correct' => false],
                            ['text' => 'DataFrame adalah versi lama dari Series yang sudah tidak dipakai', 'correct' => false],
                            ['text' => 'Tidak ada bedanya, keduanya istilah untuk hal yang sama', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Fungsi pandas mana yang dipakai untuk melihat ringkasan statistik dasar (mean, min, max, dst) dari kolom numerik?',
                        'explanation' => 'describe() menghasilkan ringkasan statistik dasar seperti count, mean, std, min, dan max untuk kolom-kolom numerik pada DataFrame, berguna untuk eksplorasi awal data.',
                        'options' => [
                            ['text' => 'describe()', 'correct' => true],
                            ['text' => 'head()', 'correct' => false],
                            ['text' => 'columns()', 'correct' => false],
                            ['text' => 'read_csv()', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Fungsi apa yang dipakai untuk melihat tipe data tiap kolom serta jumlah nilai kosong di sebuah DataFrame?',
                        'explanation' => 'info() menampilkan ringkasan struktur DataFrame, termasuk tipe data tiap kolom dan jumlah nilai non-null, sehingga membantu mendeteksi missing values sejak tahap eksplorasi awal.',
                        'options' => [
                            ['text' => 'info()', 'correct' => true],
                            ['text' => 'sum()', 'correct' => false],
                            ['text' => 'sort_values()', 'correct' => false],
                            ['text' => 'plot()', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa index pada DataFrame itu penting untuk dipahami sejak awal?',
                        'explanation' => 'Index adalah label yang mengidentifikasi setiap baris dan dipakai bersama oleh seluruh kolom dalam satu DataFrame — banyak operasi pandas seperti penggabungan data atau pemilihan baris tertentu bergantung pada bagaimana index ini digunakan.',
                        'options' => [
                            ['text' => 'Banyak operasi pandas seperti penggabungan data dan pemilihan baris bergantung pada index', 'correct' => true],
                            ['text' => 'Index hanya dipakai untuk mempercantik tampilan tabel di layar', 'correct' => false],
                            ['text' => 'Index wajib berupa angka urut mulai dari 1', 'correct' => false],
                            ['text' => 'Index tidak berpengaruh sama sekali terhadap operasi pandas', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Customer Data Summary Report' => [
                'title' => 'Quiz: GroupBy & Fungsi Agregasi Pandas',
                'questions' => [
                    [
                        'question' => 'Fungsi pandas mana yang dipakai untuk mengelompokkan data berdasarkan kategori tertentu sebelum diringkas?',
                        'explanation' => 'groupby() mengelompokkan baris berdasarkan nilai pada kolom tertentu, sehingga fungsi agregasi seperti sum() atau mean() bisa diterapkan per kelompok, bukan ke seluruh data sekaligus.',
                        'options' => [
                            ['text' => 'groupby()', 'correct' => true],
                            ['text' => 'sort_values()', 'correct' => false],
                            ['text' => 'merge()', 'correct' => false],
                            ['text' => 'drop_duplicates()', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kalau ingin tahu total jumlah transaksi per kategori pelanggan, fungsi agregasi mana yang paling tepat dipakai setelah groupby()?',
                        'explanation' => 'sum() menjumlahkan seluruh nilai dalam satu kelompok, cocok dipakai untuk menghitung total (misalnya total transaksi) per kategori setelah data dikelompokkan dengan groupby().',
                        'options' => [
                            ['text' => 'sum()', 'correct' => true],
                            ['text' => 'head()', 'correct' => false],
                            ['text' => 'info()', 'correct' => false],
                            ['text' => 'columns()', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa fungsi eksplorasi (head, info, describe) dan agregasi (groupby, sum, mean) sering dianggap sebagai keterampilan inti seorang data analyst?',
                        'explanation' => 'Sebagian besar pertanyaan bisnis sehari-hari (misalnya total penjualan per produk atau rata-rata transaksi per bulan) pada dasarnya bisa dijawab dengan meringkas data menggunakan kombinasi eksplorasi dan agregasi ini, tanpa perlu teknik yang lebih rumit.',
                        'options' => [
                            ['text' => 'Sebagian besar pertanyaan bisnis bisa dijawab dengan meringkas data secara tepat', 'correct' => true],
                            ['text' => 'Karena hanya fungsi inilah yang tersedia di pandas', 'correct' => false],
                            ['text' => 'Karena fungsi lain di pandas sudah tidak didukung lagi', 'correct' => false],
                            ['text' => 'Karena groupby() menggantikan kebutuhan database sepenuhnya', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Diberikan DataFrame transaksi dengan kolom "kategori" dan "jumlah", perintah mana yang tepat untuk menghitung rata-rata jumlah per kategori?',
                        'explanation' => "data.groupby('kategori')['jumlah'].mean() mengelompokkan baris berdasarkan kategori, lalu menghitung rata-rata kolom jumlah pada tiap kelompok tersebut.",
                        'options' => [
                            ['text' => "data.groupby('kategori')['jumlah'].mean()", 'correct' => true],
                            ['text' => "data.sort_values('kategori').mean()", 'correct' => false],
                            ['text' => "data['jumlah'].describe('kategori')", 'correct' => false],
                            ['text' => "data.filter('kategori').average()", 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa manfaat utama menggunakan groupby() dibanding menghitung ringkasan secara manual satu per satu untuk tiap kategori?',
                        'explanation' => 'groupby() mengotomatiskan proses pengelompokan dan perhitungan ringkasan untuk seluruh kategori sekaligus dalam satu baris kode, jauh lebih efisien dan minim kesalahan dibanding menghitung manual kategori demi kategori.',
                        'options' => [
                            ['text' => 'Mengotomatiskan pengelompokan dan perhitungan ringkasan untuk seluruh kategori sekaligus', 'correct' => true],
                            ['text' => 'Menghapus kategori yang jarang muncul secara otomatis', 'correct' => false],
                            ['text' => 'Mengubah seluruh data menjadi format JSON', 'correct' => false],
                            ['text' => 'Membuat visualisasi grafik secara otomatis', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 2: SQL untuk Data Analyst
            // ============================================================
            'Assignment 1: Sales Database Query Challenge' => [
                'title' => 'Quiz: SELECT, WHERE & ORDER BY',
                'questions' => [
                    [
                        'question' => 'Perintah SQL mana yang dipakai untuk mengambil kolom tertentu dari sebuah tabel?',
                        'explanation' => 'SELECT dipakai untuk menentukan kolom mana yang ingin diambil dari sebuah tabel, misalnya SELECT nama, harga FROM produk.',
                        'options' => [
                            ['text' => 'SELECT', 'correct' => true],
                            ['text' => 'FILTER', 'correct' => false],
                            ['text' => 'GET', 'correct' => false],
                            ['text' => 'FETCH', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Klausa mana yang dipakai untuk menyaring baris berdasarkan kondisi tertentu, misalnya hanya transaksi dengan jumlah lebih dari 10?',
                        'explanation' => 'WHERE menyaring baris agar hanya baris yang memenuhi kondisi tertentu yang dikembalikan dalam hasil query.',
                        'options' => [
                            ['text' => 'WHERE', 'correct' => true],
                            ['text' => 'ORDER BY', 'correct' => false],
                            ['text' => 'GROUP BY', 'correct' => false],
                            ['text' => 'HAVING', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa SQL tidak bisa dianggap dieksekusi baris per baris seperti bahasa pemrograman pada umumnya?',
                        'explanation' => 'SQL diproses sebagai satu kesatuan pernyataan yang menghasilkan satu tabel hasil (result set), bukan dijalankan instruksi demi instruksi secara berurutan seperti kode prosedural biasa.',
                        'options' => [
                            ['text' => 'SQL diproses sebagai satu kesatuan pernyataan yang menghasilkan result set', 'correct' => true],
                            ['text' => 'SQL sebenarnya sama persis cara kerjanya dengan JavaScript', 'correct' => false],
                            ['text' => 'SQL tidak bisa memproses lebih dari satu baris data', 'correct' => false],
                            ['text' => 'SQL hanya bisa dijalankan sekali dalam satu sesi database', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Untuk mengurutkan hasil query dari transaksi terbaru ke terlama berdasarkan kolom tanggal, klausa yang tepat adalah?',
                        'explanation' => 'ORDER BY tanggal DESC mengurutkan hasil berdasarkan kolom tanggal secara menurun, sehingga data terbaru muncul lebih dulu.',
                        'options' => [
                            ['text' => 'ORDER BY tanggal DESC', 'correct' => true],
                            ['text' => 'SORT tanggal NEWEST', 'correct' => false],
                            ['text' => 'GROUP BY tanggal DESC', 'correct' => false],
                            ['text' => 'WHERE tanggal DESC', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa kemampuan menulis query SQL dasar dianggap sudah cukup untuk menyelesaikan sebagian besar permintaan data harian seorang data analyst?',
                        'explanation' => 'Sebagian besar permintaan data sehari-hari, seperti mengambil data penjualan pada periode tertentu atau mencari transaksi dengan kriteria khusus, sebenarnya cukup dijawab dengan kombinasi SELECT, WHERE, dan ORDER BY tanpa perlu query yang lebih kompleks.',
                        'options' => [
                            ['text' => 'Sebagian besar permintaan data harian bisa dijawab dengan kombinasi SELECT, WHERE, dan ORDER BY', 'correct' => true],
                            ['text' => 'Karena JOIN dan GROUP BY sudah tidak dipakai lagi di database modern', 'correct' => false],
                            ['text' => 'Karena database hanya mendukung tiga perintah tersebut', 'correct' => false],
                            ['text' => 'Karena data analyst tidak pernah butuh menggabungkan data dari beberapa tabel', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Multi-table Join Report' => [
                'title' => 'Quiz: JOIN, GROUP BY & HAVING',
                'questions' => [
                    [
                        'question' => 'Kenapa JOIN dibutuhkan saat data tersimpan di beberapa tabel terpisah, misalnya tabel transaksi dan tabel produk?',
                        'explanation' => 'JOIN menggabungkan data dari beberapa tabel berdasarkan kolom kunci yang menghubungkannya, sehingga informasi yang dibutuhkan (misalnya nama produk dari tabel produk) bisa ditampilkan bersama data transaksi.',
                        'options' => [
                            ['text' => 'Untuk menggabungkan data dari beberapa tabel berdasarkan kolom kunci yang menghubungkannya', 'correct' => true],
                            ['text' => 'Untuk menghapus tabel yang sudah tidak dipakai', 'correct' => false],
                            ['text' => 'Untuk mengubah struktur tabel secara permanen', 'correct' => false],
                            ['text' => 'Untuk mempercepat proses backup database', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa perbedaan utama antara INNER JOIN dan LEFT JOIN?',
                        'explanation' => 'INNER JOIN hanya mengembalikan baris yang punya kecocokan di kedua tabel, sedangkan LEFT JOIN tetap mengembalikan seluruh baris dari tabel pertama meskipun tidak ada kecocokan di tabel kedua.',
                        'options' => [
                            ['text' => 'INNER JOIN hanya baris yang cocok di kedua tabel, LEFT JOIN tetap ambil semua baris tabel pertama', 'correct' => true],
                            ['text' => 'INNER JOIN dan LEFT JOIN menghasilkan hasil yang selalu identik', 'correct' => false],
                            ['text' => 'LEFT JOIN hanya bisa dipakai pada tabel yang kosong', 'correct' => false],
                            ['text' => 'INNER JOIN hanya bisa menggabungkan maksimal dua kolom', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa risiko utama kalau memilih jenis JOIN yang salah pada kasus relasi data satu ke banyak?',
                        'explanation' => 'JOIN yang salah bisa menghasilkan data yang hilang (kalau pakai INNER JOIN padahal butuh semua baris) atau justru duplikat, sehingga angka hasil analisis seperti total penjualan bisa jadi tidak akurat.',
                        'options' => [
                            ['text' => 'Data bisa hilang atau justru terduplikasi, sehingga hasil analisis jadi tidak akurat', 'correct' => true],
                            ['text' => 'Database akan otomatis menghapus tabel yang di-JOIN', 'correct' => false],
                            ['text' => 'Query akan selalu gagal dijalankan tanpa terkecuali', 'correct' => false],
                            ['text' => 'Tidak ada risiko apapun, semua jenis JOIN hasilnya sama', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa HAVING dipakai untuk menyaring hasil setelah GROUP BY, bukan WHERE?',
                        'explanation' => 'WHERE hanya bisa menyaring data sebelum proses pengelompokan (GROUP BY) dilakukan, sedangkan HAVING dipakai untuk menyaring hasil agregasi setelah data dikelompokkan, misalnya hanya menampilkan produk dengan total penjualan di atas angka tertentu.',
                        'options' => [
                            ['text' => 'WHERE hanya menyaring sebelum pengelompokan, HAVING menyaring hasil agregasi setelah GROUP BY', 'correct' => true],
                            ['text' => 'HAVING dan WHERE sebenarnya fungsi yang sama persis', 'correct' => false],
                            ['text' => 'HAVING hanya bisa dipakai tanpa GROUP BY', 'correct' => false],
                            ['text' => 'WHERE hanya bisa dipakai pada kolom teks, HAVING pada kolom angka', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Query untuk menampilkan nama produk beserta total penjualannya, hanya produk dengan total penjualan lebih dari 50, membutuhkan kombinasi klausa apa?',
                        'explanation' => 'Kombinasi JOIN (menggabungkan tabel transaksi dan produk), GROUP BY (mengelompokkan per produk), fungsi agregat SUM(), dan HAVING (menyaring hasil agregasi) diperlukan untuk menjawab pertanyaan seperti ini.',
                        'options' => [
                            ['text' => 'JOIN, GROUP BY, SUM(), dan HAVING', 'correct' => true],
                            ['text' => 'Hanya SELECT dan WHERE saja', 'correct' => false],
                            ['text' => 'Hanya ORDER BY tanpa klausa lain', 'correct' => false],
                            ['text' => 'DELETE dikombinasikan dengan INSERT', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 3: Data Cleaning & Preparation
            // ============================================================
            'Assignment 1: Messy Dataset Cleanup' => [
                'title' => 'Quiz: Missing Values, Duplikat & Outlier',
                'questions' => [
                    [
                        'question' => 'Sebelum menangani missing values, hal apa yang sebaiknya dipahami lebih dulu?',
                        'explanation' => 'Memahami pola dan penyebab missing values penting karena strategi penanganan yang tepat berbeda-beda tergantung konteksnya — menghapus data tanpa memahami penyebabnya bisa membuat analisis jadi bias.',
                        'options' => [
                            ['text' => 'Pola dan penyebab missing values tersebut muncul', 'correct' => true],
                            ['text' => 'Jenis font yang dipakai pada laporan akhir', 'correct' => false],
                            ['text' => 'Warna tema dashboard yang akan dipakai nanti', 'correct' => false],
                            ['text' => 'Jumlah kolom yang ada di database secara keseluruhan', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Fungsi pandas mana yang dipakai untuk mengisi missing values dengan nilai tertentu, misalnya rata-rata kolom?',
                        'explanation' => "fillna() dipakai untuk mengisi nilai yang hilang dengan nilai tertentu, misalnya data['kolom'].fillna(data['kolom'].mean()) mengisi nilai kosong dengan rata-rata kolom tersebut.",
                        'options' => [
                            ['text' => 'fillna()', 'correct' => true],
                            ['text' => 'dropna_only()', 'correct' => false],
                            ['text' => 'replace_missing()', 'correct' => false],
                            ['text' => 'clean()', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa data duplikat perlu diidentifikasi dan dihapus sebelum analisis dilakukan?',
                        'explanation' => 'Data duplikat, yang biasanya muncul akibat kesalahan sistem, bisa menyebabkan angka analisis seperti total penjualan menjadi lebih besar dari kondisi sebenarnya kalau tidak dihapus.',
                        'options' => [
                            ['text' => 'Bisa membuat angka analisis seperti total penjualan jadi lebih besar dari kondisi sebenarnya', 'correct' => true],
                            ['text' => 'Data duplikat selalu membuat program menjadi error', 'correct' => false],
                            ['text' => 'Data duplikat wajib dihapus oleh regulasi pemerintah', 'correct' => false],
                            ['text' => 'Tidak ada dampak apapun, hanya soal kerapian tabel', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa outlier tidak selalu berarti kesalahan data yang harus langsung dihapus?',
                        'explanation' => 'Outlier kadang justru merepresentasikan kejadian nyata yang penting, misalnya transaksi dengan nilai sangat besar dari satu pelanggan tertentu — keputusan menghapus atau mempertahankannya perlu mempertimbangkan konteks bisnis.',
                        'options' => [
                            ['text' => 'Outlier bisa merepresentasikan kejadian nyata yang penting untuk dianalisis, bukan sekadar error', 'correct' => true],
                            ['text' => 'Outlier tidak pernah bisa dideteksi oleh metode statistik apapun', 'correct' => false],
                            ['text' => 'Outlier selalu berarti kesalahan input yang harus dihapus tanpa terkecuali', 'correct' => false],
                            ['text' => 'Outlier hanya muncul pada data yang berbentuk teks', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Metode IQR (Interquartile Range) dipakai untuk apa dalam proses data cleaning?',
                        'explanation' => 'IQR (selisih antara Q3 dan Q1) dipakai untuk menentukan batas atas dan bawah yang wajar pada suatu data, sehingga nilai yang berada jauh di luar batas tersebut dapat diidentifikasi sebagai kandidat outlier.',
                        'options' => [
                            ['text' => 'Mendeteksi outlier dengan menentukan batas atas/bawah berdasarkan sebaran data', 'correct' => true],
                            ['text' => 'Mengisi missing values secara otomatis', 'correct' => false],
                            ['text' => 'Menggabungkan dua tabel database yang berbeda', 'correct' => false],
                            ['text' => 'Membuat visualisasi grafik garis', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Customer Data Standardization' => [
                'title' => 'Quiz: Transformasi & Standarisasi Data',
                'questions' => [
                    [
                        'question' => 'Apa yang dimaksud dengan standarisasi data pada proses data cleaning?',
                        'explanation' => 'Standarisasi adalah proses menyeragamkan kategori atau format yang sebenarnya sama namun ditulis berbeda, misalnya "Jakarta", "jakarta", dan "JKT" yang seharusnya merujuk ke kota yang sama.',
                        'options' => [
                            ['text' => 'Menyeragamkan kategori/format yang sebenarnya sama namun ditulis berbeda-beda', 'correct' => true],
                            ['text' => 'Mengubah seluruh data numerik menjadi teks', 'correct' => false],
                            ['text' => 'Menghapus seluruh baris yang mengandung huruf kapital', 'correct' => false],
                            ['text' => 'Mengurutkan data berdasarkan abjad', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa data seperti "Jakarta", "jakarta", dan "JKT" yang belum distandarisasi bisa merusak hasil groupby()?',
                        'explanation' => 'Tanpa standarisasi, data yang seharusnya satu kelompok justru terpecah menjadi beberapa kelompok berbeda karena dianggap sebagai nilai yang berbeda oleh pandas, sehingga hasil agregasi menjadi tidak akurat.',
                        'options' => [
                            ['text' => 'Data yang seharusnya satu kelompok jadi terpecah menjadi beberapa kelompok berbeda', 'correct' => true],
                            ['text' => 'groupby() akan otomatis menghapus seluruh baris yang mengandung teks tersebut', 'correct' => false],
                            ['text' => 'Python akan menampilkan error dan program berhenti total', 'correct' => false],
                            ['text' => 'Tidak ada dampak apapun terhadap hasil groupby()', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Selain penyeragaman teks, hal apa lagi yang perlu dipastikan saat melakukan transformasi data?',
                        'explanation' => 'Transformasi data juga mencakup memastikan tipe data pada setiap kolom sudah sesuai, misalnya kolom angka tidak tersimpan sebagai teks, serta menyeragamkan format tanggal.',
                        'options' => [
                            ['text' => 'Tipe data pada setiap kolom sudah sesuai (misal kolom angka tidak tersimpan sebagai teks)', 'correct' => true],
                            ['text' => 'Warna latar belakang file Excel yang dipakai', 'correct' => false],
                            ['text' => 'Ukuran font pada laporan yang akan dibuat', 'correct' => false],
                            ['text' => 'Jumlah sheet yang ada di file Excel', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Fungsi string pandas apa yang membantu menyeragamkan penulisan kategori menjadi huruf kecil semua?',
                        'explanation' => ".str.lower() mengubah seluruh teks pada kolom menjadi huruf kecil, langkah umum sebelum menyamakan kategori yang ditulis dengan variasi huruf kapital berbeda.",
                        'options' => [
                            ['text' => '.str.lower()', 'correct' => true],
                            ['text' => '.str.numeric()', 'correct' => false],
                            ['text' => '.str.duplicate()', 'correct' => false],
                            ['text' => '.str.merge()', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa proses transformasi dan standarisasi data sering memakan waktu cukup lama namun sangat menentukan hasil analisis?',
                        'explanation' => 'Meskipun memakan waktu, keakuratan standarisasi ini menentukan apakah proses agregasi dan analisis di tahap-tahap selanjutnya menghasilkan angka yang benar-benar mencerminkan kondisi data yang sebenarnya.',
                        'options' => [
                            ['text' => 'Keakuratan tahap ini menentukan apakah agregasi dan analisis selanjutnya menghasilkan angka yang benar', 'correct' => true],
                            ['text' => 'Karena tahap ini wajib dilakukan sebelum data disimpan ke database', 'correct' => false],
                            ['text' => 'Karena tahap ini menggantikan kebutuhan visualisasi data', 'correct' => false],
                            ['text' => 'Karena tanpa tahap ini data tidak bisa dibuka sama sekali', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 4: Data Visualization
            // ============================================================
            'Assignment 1: Sales Performance Dashboard' => [
                'title' => 'Quiz: Prinsip Visualisasi & Dashboard',
                'questions' => [
                    [
                        'question' => 'Apa tujuan utama visualisasi data dibandingkan hanya menampilkan angka dalam bentuk tabel?',
                        'explanation' => 'Visualisasi membantu audiens menangkap pola, tren, atau perbandingan dengan cepat, tanpa perlu menganalisis angka pada tabel satu per satu.',
                        'options' => [
                            ['text' => 'Membantu audiens menangkap pola atau tren dengan cepat tanpa membaca angka satu per satu', 'correct' => true],
                            ['text' => 'Membuat data terlihat lebih rumit dari sebenarnya', 'correct' => false],
                            ['text' => 'Mengganti kebutuhan menyimpan data mentah', 'correct' => false],
                            ['text' => 'Menyembunyikan angka asli agar tidak terlihat audiens', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa skala sumbu grafik yang tidak dimulai dari nol bisa menyesatkan audiens?',
                        'explanation' => 'Skala yang tidak dimulai dari nol bisa membuat perbedaan antar nilai terlihat jauh lebih besar dari kenyataan, sehingga audiens salah menafsirkan seberapa signifikan perbedaan tersebut.',
                        'options' => [
                            ['text' => 'Membuat perbedaan antar nilai terlihat lebih besar dari kenyataan sebenarnya', 'correct' => true],
                            ['text' => 'Grafik menjadi tidak bisa ditampilkan sama sekali', 'correct' => false],
                            ['text' => 'Warna grafik jadi tidak bisa diubah', 'correct' => false],
                            ['text' => 'Tidak ada dampak apapun terhadap penafsiran audiens', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Saat menyusun dashboard, kenapa penting menentukan metrik utama (key metrics) yang benar-benar relevan, bukan menampilkan semua data yang tersedia?',
                        'explanation' => 'Dashboard yang terlalu penuh dengan informasi justru membuat audiens kesulitan menemukan insight yang paling penting, sementara tujuan dashboard adalah membantu audiens memahami kondisi keseluruhan hanya dalam beberapa detik.',
                        'options' => [
                            ['text' => 'Dashboard yang terlalu penuh membuat audiens kesulitan menemukan insight yang paling penting', 'correct' => true],
                            ['text' => 'Karena tool dashboard membatasi jumlah maksimal metrik yang bisa ditampilkan', 'correct' => false],
                            ['text' => 'Karena metrik tambahan akan membuat dashboard menjadi error', 'correct' => false],
                            ['text' => 'Tidak ada alasan khusus, hanya soal estetika visual saja', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Prinsip penempatan elemen apa yang biasa dipakai di dashboard, di mana metrik paling penting ditempatkan lebih menonjol?',
                        'explanation' => 'Penempatan elemen pada dashboard mengikuti prinsip hierarki visual, di mana metrik paling penting biasanya diletakkan di posisi paling menonjol, misalnya di bagian atas atau dengan ukuran lebih besar.',
                        'options' => [
                            ['text' => 'Hierarki visual', 'correct' => true],
                            ['text' => 'Normalisasi database', 'correct' => false],
                            ['text' => 'Enkripsi data', 'correct' => false],
                            ['text' => 'Load balancing', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa manfaat utama dashboard yang dirancang dengan baik bagi tim bisnis?',
                        'explanation' => 'Dashboard yang baik memungkinkan tim bisnis memantau kondisi operasional secara mandiri tanpa harus selalu meminta laporan khusus dari data analyst setiap saat, sehingga menghemat waktu komunikasi.',
                        'options' => [
                            ['text' => 'Tim bisnis bisa memantau kondisi operasional secara mandiri tanpa selalu minta laporan khusus', 'correct' => true],
                            ['text' => 'Dashboard menggantikan seluruh kebutuhan analisis data secara permanen', 'correct' => false],
                            ['text' => 'Dashboard membuat database menjadi lebih aman secara otomatis', 'correct' => false],
                            ['text' => 'Dashboard menghapus kebutuhan data analyst di sebuah perusahaan', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Chart Type Comparison Report' => [
                'title' => 'Quiz: Pemilihan Jenis Chart',
                'questions' => [
                    [
                        'question' => 'Jenis chart mana yang paling cocok dipakai untuk membandingkan nilai antar kategori, misalnya penjualan per produk?',
                        'explanation' => 'Bar chart cocok dipakai untuk membandingkan nilai antar kategori yang terpisah, seperti membandingkan total penjualan beberapa produk yang berbeda.',
                        'options' => [
                            ['text' => 'Bar chart', 'correct' => true],
                            ['text' => 'Pie chart dengan 20 kategori', 'correct' => false],
                            ['text' => 'Scatter plot tanpa sumbu', 'correct' => false],
                            ['text' => 'Tabel tanpa visualisasi apapun', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Jenis chart mana yang paling tepat dipakai untuk menunjukkan tren penjualan bulanan sepanjang tahun?',
                        'explanation' => 'Line chart lebih tepat dipakai untuk menunjukkan tren data dari waktu ke waktu, karena garis yang menghubungkan titik-titik data memudahkan audiens melihat naik-turunnya nilai secara berurutan.',
                        'options' => [
                            ['text' => 'Line chart', 'correct' => true],
                            ['text' => 'Pie chart', 'correct' => false],
                            ['text' => 'Bar chart horizontal saja', 'correct' => false],
                            ['text' => 'Tabel pivot tanpa grafik', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa pie chart sebaiknya hanya dipakai kalau jumlah kategorinya sedikit?',
                        'explanation' => 'Pie chart dengan terlalu banyak kategori justru sulit dibaca, karena perbedaan ukuran antar potongan (slice) yang kecil-kecil menjadi susah dibedakan secara visual.',
                        'options' => [
                            ['text' => 'Pie chart dengan terlalu banyak kategori jadi sulit dibaca karena potongannya terlalu kecil', 'correct' => true],
                            ['text' => 'Tool visualisasi membatasi pie chart maksimal 2 kategori secara teknis', 'correct' => false],
                            ['text' => 'Pie chart tidak bisa menampilkan angka sama sekali', 'correct' => false],
                            ['text' => 'Pie chart hanya bisa dipakai untuk data numerik negatif', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Scatter plot paling cocok dipakai untuk menunjukkan apa?',
                        'explanation' => 'Scatter plot dipakai untuk menunjukkan hubungan antara dua variabel numerik, misalnya hubungan antara harga produk dan jumlah yang terjual.',
                        'options' => [
                            ['text' => 'Hubungan antara dua variabel numerik', 'correct' => true],
                            ['text' => 'Proporsi dari keseluruhan data dalam bentuk potongan', 'correct' => false],
                            ['text' => 'Urutan kronologis kejadian dalam bentuk teks', 'correct' => false],
                            ['text' => 'Struktur tabel database secara keseluruhan', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kesalahan umum apa yang sering terjadi terkait pemilihan jenis chart?',
                        'explanation' => 'Kesalahan umum adalah memaksakan satu jenis chart untuk semua jenis data, padahal pemilihan chart yang tepat sangat bergantung pada struktur data dan tujuan komunikasi yang ingin dicapai.',
                        'options' => [
                            ['text' => 'Memaksakan satu jenis chart untuk semua jenis data tanpa mempertimbangkan konteksnya', 'correct' => true],
                            ['text' => 'Menggunakan terlalu banyak warna berbeda pada satu bar chart', 'correct' => false],
                            ['text' => 'Menambahkan judul pada setiap grafik yang dibuat', 'correct' => false],
                            ['text' => 'Memberi label pada sumbu x dan y grafik', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 5: Statistik Dasar untuk Analisis
            // ============================================================
            'Assignment 1: Statistical Summary Report' => [
                'title' => 'Quiz: Statistik Deskriptif & Distribusi Data',
                'questions' => [
                    [
                        'question' => 'Kenapa median kadang lebih representatif dibanding mean pada data yang punya nilai ekstrem?',
                        'explanation' => 'Mean sangat sensitif terhadap outlier — satu nilai yang sangat tinggi bisa membuat mean terlihat jauh lebih besar dari kondisi sebagian besar data, sementara median (nilai tengah) tidak terlalu terpengaruh oleh nilai ekstrem tersebut.',
                        'options' => [
                            ['text' => 'Mean sangat sensitif terhadap outlier, sedangkan median tidak terlalu terpengaruh nilai ekstrem', 'correct' => true],
                            ['text' => 'Median selalu lebih besar nilainya dibanding mean pada data apapun', 'correct' => false],
                            ['text' => 'Mean hanya bisa dihitung pada data yang jumlahnya genap', 'correct' => false],
                            ['text' => 'Median tidak bisa dihitung pada data numerik', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang ditunjukkan oleh standar deviasi yang kecil pada suatu kumpulan data?',
                        'explanation' => 'Standar deviasi yang kecil menunjukkan data cenderung berkumpul di sekitar nilai rata-ratanya, artinya data tersebut relatif konsisten dan tidak terlalu bervariasi.',
                        'options' => [
                            ['text' => 'Data cenderung berkumpul di sekitar rata-rata (relatif konsisten)', 'correct' => true],
                            ['text' => 'Data pasti mengandung banyak nilai yang hilang', 'correct' => false],
                            ['text' => 'Data tersebut sudah pasti berdistribusi tidak normal', 'correct' => false],
                            ['text' => 'Data tersebut tidak bisa dianalisis lebih lanjut', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Bentuk distribusi seperti apa yang disebut distribusi normal?',
                        'explanation' => 'Distribusi normal berbentuk lonceng simetris, di mana sebagian besar data berkumpul di sekitar nilai rata-rata dan semakin sedikit data ditemukan semakin jauh dari rata-rata tersebut.',
                        'options' => [
                            ['text' => 'Berbentuk lonceng simetris dengan data terkumpul di sekitar rata-rata', 'correct' => true],
                            ['text' => 'Selalu berbentuk garis lurus menaik', 'correct' => false],
                            ['text' => 'Selalu berbentuk kotak dengan ukuran sama rata', 'correct' => false],
                            ['text' => 'Hanya berlaku untuk data berupa teks', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa memahami bentuk distribusi suatu data itu penting sebelum melakukan analisis lanjutan?',
                        'explanation' => 'Banyak metode statistik lanjutan mengasumsikan data mengikuti distribusi tertentu (misalnya distribusi normal) — data yang tidak berdistribusi normal mungkin memerlukan pendekatan analisis berbeda atau transformasi terlebih dahulu.',
                        'options' => [
                            ['text' => 'Banyak metode statistik lanjutan mengasumsikan bentuk distribusi tertentu pada data', 'correct' => true],
                            ['text' => 'Karena data yang tidak berdistribusi normal tidak bisa disimpan di database', 'correct' => false],
                            ['text' => 'Karena histogram hanya bisa dibuat pada data yang sudah normal', 'correct' => false],
                            ['text' => 'Distribusi data tidak berpengaruh sama sekali pada analisis lanjutan', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Visualisasi apa yang umum dipakai untuk melihat bentuk distribusi suatu data secara cepat?',
                        'explanation' => 'Histogram menampilkan sebaran frekuensi nilai data dalam bentuk batang, sehingga bentuk distribusi (misalnya lonceng simetris atau miring ke satu sisi) bisa langsung terlihat secara visual.',
                        'options' => [
                            ['text' => 'Histogram', 'correct' => true],
                            ['text' => 'Pie chart', 'correct' => false],
                            ['text' => 'Tabel pivot', 'correct' => false],
                            ['text' => 'Peta lokasi (map)', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Correlation Analysis Project' => [
                'title' => 'Quiz: Korelasi & Uji Hipotesis',
                'questions' => [
                    [
                        'question' => 'Apa arti nilai korelasi yang mendekati 1 antara dua variabel numerik?',
                        'explanation' => 'Nilai korelasi mendekati 1 menunjukkan hubungan positif yang kuat, artinya kedua variabel cenderung naik bersamaan.',
                        'options' => [
                            ['text' => 'Hubungan positif yang kuat, kedua variabel cenderung naik bersamaan', 'correct' => true],
                            ['text' => 'Kedua variabel sama sekali tidak berhubungan', 'correct' => false],
                            ['text' => 'Salah satu variabel pasti menyebabkan variabel lainnya berubah', 'correct' => false],
                            ['text' => 'Data mengandung banyak nilai yang hilang', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa korelasi tinggi antara dua variabel tidak bisa langsung disimpulkan sebagai hubungan sebab-akibat?',
                        'explanation' => 'Dua variabel bisa memiliki korelasi tinggi tanpa satu benar-benar memengaruhi yang lain — kesimpulan sebab-akibat memerlukan analisis lebih mendalam, bukan hanya dari nilai korelasi semata.',
                        'options' => [
                            ['text' => 'Dua variabel bisa berkorelasi tinggi tanpa satu benar-benar memengaruhi yang lain', 'correct' => true],
                            ['text' => 'Korelasi hanya bisa dihitung pada data kategori, bukan angka', 'correct' => false],
                            ['text' => 'Korelasi selalu bernilai 1 kalau memang ada hubungan sebab-akibat', 'correct' => false],
                            ['text' => 'Hubungan sebab-akibat tidak pernah bisa dibuktikan dengan data apapun', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Fungsi apa di pandas yang dipakai untuk menghitung korelasi antara dua kolom numerik?',
                        'explanation' => "Fungsi .corr() dipakai untuk menghitung nilai korelasi antara dua Series/kolom numerik, misalnya data['iklan'].corr(data['penjualan']).",
                        'options' => [
                            ['text' => '.corr()', 'correct' => true],
                            ['text' => '.groupby()', 'correct' => false],
                            ['text' => '.fillna()', 'correct' => false],
                            ['text' => '.drop_duplicates()', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa tujuan utama uji hipotesis dalam analisis data?',
                        'explanation' => 'Uji hipotesis dipakai untuk menguji apakah suatu perbedaan atau hubungan yang teramati pada data bersifat signifikan secara statistik, atau hanya terjadi akibat kebetulan.',
                        'options' => [
                            ['text' => 'Menguji apakah suatu perbedaan/hubungan pada data signifikan secara statistik atau hanya kebetulan', 'correct' => true],
                            ['text' => 'Menghapus data yang tidak relevan dari database', 'correct' => false],
                            ['text' => 'Mengubah data numerik menjadi kategori', 'correct' => false],
                            ['text' => 'Membuat visualisasi dashboard secara otomatis', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Nilai korelasi berapa yang menunjukkan tidak ada hubungan linear yang jelas antara dua variabel?',
                        'explanation' => 'Nilai korelasi yang mendekati 0 menunjukkan tidak ada hubungan linear yang jelas antara kedua variabel yang diukur.',
                        'options' => [
                            ['text' => 'Mendekati 0', 'correct' => true],
                            ['text' => 'Mendekati 1', 'correct' => false],
                            ['text' => 'Mendekati -1', 'correct' => false],
                            ['text' => 'Selalu tepat 0.5', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 6: R untuk Data Analysis
            // ============================================================
            'Assignment 1: R Data Manipulation with dplyr' => [
                'title' => 'Quiz: Pengenalan R & Manipulasi Data dengan dplyr',
                'questions' => [
                    [
                        'question' => 'Apa fokus utama bahasa pemrograman R sejak awal dikembangkan, berbeda dari Python yang serba guna?',
                        'explanation' => 'R dirancang khusus untuk analisis statistik dan visualisasi data sejak awal pengembangannya, berbeda dengan Python yang bersifat bahasa serba guna untuk berbagai keperluan.',
                        'options' => [
                            ['text' => 'Analisis statistik dan visualisasi data', 'correct' => true],
                            ['text' => 'Pengembangan aplikasi mobile', 'correct' => false],
                            ['text' => 'Pembuatan game 3D', 'correct' => false],
                            ['text' => 'Pengelolaan sistem operasi server', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa itu RStudio?',
                        'explanation' => 'RStudio adalah integrated development environment (IDE) paling umum dipakai untuk menulis dan menjalankan kode R, dengan tampilan yang terbagi menjadi beberapa panel seperti skrip, console, variabel, dan visualisasi.',
                        'options' => [
                            ['text' => 'IDE (integrated development environment) yang umum dipakai untuk menulis dan menjalankan kode R', 'correct' => true],
                            ['text' => 'Package tambahan untuk visualisasi data di Python', 'correct' => false],
                            ['text' => 'Nama lain dari bahasa pemrograman R itu sendiri', 'correct' => false],
                            ['text' => 'Database khusus untuk menyimpan data statistik', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Fungsi dplyr mana yang dipakai untuk menyaring baris berdasarkan kondisi tertentu?',
                        'explanation' => 'filter() dipakai untuk menyaring baris pada data berdasarkan kondisi tertentu, mirip fungsi WHERE pada SQL atau boolean indexing pada pandas.',
                        'options' => [
                            ['text' => 'filter()', 'correct' => true],
                            ['text' => 'select()', 'correct' => false],
                            ['text' => 'arrange()', 'correct' => false],
                            ['text' => 'mutate()', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa fungsi pipe operator (%>% atau |>) dalam penulisan kode dplyr?',
                        'explanation' => 'Pipe operator memungkinkan beberapa operasi dirangkai secara berurutan dengan cara yang mudah dibaca, mirip menjelaskan langkah-langkah pengolahan data secara naratif, tanpa perlu menyimpan hasil antara ke variabel terpisah.',
                        'options' => [
                            ['text' => 'Merangkai beberapa operasi data secara berurutan agar mudah dibaca', 'correct' => true],
                            ['text' => 'Menghapus baris yang mengandung missing values secara otomatis', 'correct' => false],
                            ['text' => 'Mengubah data R menjadi format Python', 'correct' => false],
                            ['text' => 'Membuat visualisasi grafik secara instan', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Fungsi dplyr mana yang dipakai untuk meringkas data, misalnya menghitung total per kelompok?',
                        'explanation' => "summarise() dipakai untuk meringkas data menjadi satu nilai per kelompok, biasanya dipasangkan dengan group_by() terlebih dahulu, misalnya group_by(produk) %>% summarise(total = sum(jumlah)).",
                        'options' => [
                            ['text' => 'summarise()', 'correct' => true],
                            ['text' => 'select()', 'correct' => false],
                            ['text' => 'arrange()', 'correct' => false],
                            ['text' => 'filter()', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Visualization with ggplot2' => [
                'title' => 'Quiz: Visualisasi Data dengan ggplot2',
                'questions' => [
                    [
                        'question' => 'Pendekatan apa yang dipakai ggplot2 dalam membangun sebuah grafik?',
                        'explanation' => 'ggplot2 dikenal karena pendekatan grammar of graphics, yaitu membangun grafik secara berlapis (layer), mulai dari data, jenis grafik, hingga elemen tambahan seperti label dan tema.',
                        'options' => [
                            ['text' => 'Grammar of graphics — membangun grafik secara berlapis (layer)', 'correct' => true],
                            ['text' => 'Drag and drop tanpa penulisan kode sama sekali', 'correct' => false],
                            ['text' => 'Hanya bisa membuat satu jenis grafik yaitu pie chart', 'correct' => false],
                            ['text' => 'Mengambil grafik langsung dari internet', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Fungsi mana yang menjadi titik awal dalam membangun grafik di ggplot2, untuk menentukan data dan pemetaan variabel?',
                        'explanation' => 'ggplot() adalah fungsi awal yang menentukan data dan aesthetic mapping (variabel mana yang dipetakan ke sumbu x, y, dsb), sebelum ditambahkan lapisan geometri seperti geom_bar() atau geom_line().',
                        'options' => [
                            ['text' => 'ggplot()', 'correct' => true],
                            ['text' => 'geom_bar()', 'correct' => false],
                            ['text' => 'labs()', 'correct' => false],
                            ['text' => 'theme()', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Operator apa yang dipakai untuk menambahkan lapisan (layer) baru pada grafik ggplot2, misalnya menambahkan geom_line() setelah ggplot()?',
                        'explanation' => 'Tanda plus (+) dipakai untuk menambahkan lapisan baru pada grafik ggplot2, misalnya ggplot(data, aes(...)) + geom_line() + labs(...).',
                        'options' => [
                            ['text' => 'Tanda plus (+)', 'correct' => true],
                            ['text' => 'Tanda titik dua (:)', 'correct' => false],
                            ['text' => 'Tanda pipe (%>%) saja', 'correct' => false],
                            ['text' => 'Tanda kurung kurawal ({})', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Fungsi geom_line() pada ggplot2 dipakai untuk membuat jenis grafik apa?',
                        'explanation' => 'geom_line() dipakai untuk membuat line chart, cocok untuk menunjukkan tren data dari waktu ke waktu, misalnya tren penjualan bulanan.',
                        'options' => [
                            ['text' => 'Line chart', 'correct' => true],
                            ['text' => 'Pie chart', 'correct' => false],
                            ['text' => 'Peta lokasi (map)', 'correct' => false],
                            ['text' => 'Tabel pivot', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa ggplot2 sering dipilih terutama untuk kebutuhan publikasi riset atau laporan formal?',
                        'explanation' => 'ggplot2 sering dianggap menghasilkan visualisasi yang lebih rapi secara default dibanding matplotlib di Python, sehingga cocok untuk kebutuhan publikasi riset atau laporan yang formal.',
                        'options' => [
                            ['text' => 'Sering dianggap menghasilkan visualisasi yang lebih rapi secara default', 'correct' => true],
                            ['text' => 'ggplot2 satu-satunya library yang bisa membuat grafik di dunia', 'correct' => false],
                            ['text' => 'ggplot2 tidak memerlukan data sama sekali untuk membuat grafik', 'correct' => false],
                            ['text' => 'ggplot2 hanya bisa dipakai oleh peneliti akademik', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 7: Komunikasi Insight Data
            // ============================================================
            'Assignment 1: Data Story Presentation' => [
                'title' => 'Quiz: Data Storytelling',
                'questions' => [
                    [
                        'question' => 'Apa perbedaan utama data storytelling dengan sekadar menampilkan angka dan grafik?',
                        'explanation' => 'Data storytelling menghubungkan temuan data dengan konteks bisnis yang relevan melalui narasi yang jelas, bukan sekadar menampilkan angka dan grafik tanpa penjelasan.',
                        'options' => [
                            ['text' => 'Menghubungkan temuan data dengan konteks bisnis lewat narasi yang jelas', 'correct' => true],
                            ['text' => 'Data storytelling tidak boleh menyertakan grafik sama sekali', 'correct' => false],
                            ['text' => 'Data storytelling berarti mengganti seluruh angka dengan cerita fiksi', 'correct' => false],
                            ['text' => 'Tidak ada perbedaan, keduanya istilah yang sama persis', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Struktur dasar apa yang umum dipakai dalam menyusun data story yang baik?',
                        'explanation' => 'Data story yang baik biasanya dimulai dari konteks/permasalahan, dilanjutkan temuan utama dari data, dan diakhiri dengan rekomendasi atau implikasi dari temuan tersebut.',
                        'options' => [
                            ['text' => 'Konteks/permasalahan → temuan utama → rekomendasi/implikasi', 'correct' => true],
                            ['text' => 'Rekomendasi → konteks → daftar seluruh angka mentah', 'correct' => false],
                            ['text' => 'Hanya angka tanpa urutan tertentu', 'correct' => false],
                            ['text' => 'Kode program → hasil query → tanpa penjelasan tambahan', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa kesalahan umum yang sering terjadi saat menyampaikan hasil analisis data kepada audiens?',
                        'explanation' => 'Kesalahan umum adalah menyampaikan terlalu banyak data sekaligus tanpa fokus yang jelas, sehingga audiens kesulitan menangkap pesan utama dari keseluruhan analisis.',
                        'options' => [
                            ['text' => 'Menyampaikan terlalu banyak data sekaligus tanpa fokus yang jelas', 'correct' => true],
                            ['text' => 'Menyertakan rekomendasi di akhir presentasi', 'correct' => false],
                            ['text' => 'Menjelaskan konteks permasalahan di awal presentasi', 'correct' => false],
                            ['text' => 'Menggunakan grafik untuk memperkuat temuan utama', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa kemampuan menyusun data story yang efektif dianggap membedakan data analyst yang berdampak besar dengan yang tidak?',
                        'explanation' => 'Analisis yang brilian namun tidak dapat dikomunikasikan dengan baik jarang berujung pada tindakan nyata, sehingga kemampuan storytelling menentukan seberapa besar dampak hasil analisis terhadap keputusan bisnis.',
                        'options' => [
                            ['text' => 'Analisis yang brilian namun tidak dikomunikasikan dengan baik jarang berujung pada tindakan nyata', 'correct' => true],
                            ['text' => 'Karena data storytelling menggantikan kebutuhan analisis data itu sendiri', 'correct' => false],
                            ['text' => 'Karena hasil analisis tidak pernah dibutuhkan tim manajemen', 'correct' => false],
                            ['text' => 'Karena storytelling adalah satu-satunya skill yang dibutuhkan data analyst', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Bagian mana dari data story yang menjelaskan "apa yang perlu dilakukan selanjutnya" berdasarkan hasil analisis?',
                        'explanation' => 'Bagian rekomendasi berisi implikasi atau langkah yang perlu diambil berdasarkan temuan utama yang sudah dijelaskan sebelumnya di dalam data story.',
                        'options' => [
                            ['text' => 'Rekomendasi/implikasi', 'correct' => true],
                            ['text' => 'Konteks/permasalahan', 'correct' => false],
                            ['text' => 'Daftar library yang dipakai', 'correct' => false],
                            ['text' => 'Metode pengambilan sampel data', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Executive Summary Report' => [
                'title' => 'Quiz: Komunikasi dengan Stakeholder Non-Teknis',
                'questions' => [
                    [
                        'question' => 'Apa yang biasanya lebih diminati stakeholder non-teknis seperti tim manajemen dari sebuah hasil analisis data?',
                        'explanation' => 'Stakeholder non-teknis biasanya lebih tertarik pada dampak bisnis dari suatu temuan, bukan pada detail metode statistik atau kode yang dipakai untuk menghasilkan temuan tersebut.',
                        'options' => [
                            ['text' => 'Dampak bisnis dari temuan tersebut, bukan detail metode statistik/teknisnya', 'correct' => true],
                            ['text' => 'Detail lengkap kode Python yang dipakai untuk analisis', 'correct' => false],
                            ['text' => 'Nilai p-value dari setiap uji statistik yang dilakukan', 'correct' => false],
                            ['text' => 'Struktur database yang dipakai untuk menyimpan data', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa menyampaikan angka dengan konteks (misalnya perbandingan dengan periode sebelumnya) lebih baik daripada hanya angka tunggal?',
                        'explanation' => 'Angka tunggal tanpa konteks sulit dipahami maknanya — membandingkan dengan periode sebelumnya membantu audiens non-teknis memahami apakah suatu angka menunjukkan peningkatan, penurunan, atau kondisi yang stabil.',
                        'options' => [
                            ['text' => 'Membantu audiens memahami apakah suatu angka menunjukkan peningkatan, penurunan, atau stabil', 'correct' => true],
                            ['text' => 'Angka tunggal tidak pernah bisa ditampilkan dalam laporan apapun', 'correct' => false],
                            ['text' => 'Konteks tambahan wajib disertakan menurut aturan statistik baku', 'correct' => false],
                            ['text' => 'Tidak ada bedanya, audiens non-teknis tidak peduli pada konteks angka', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa mendengarkan kebutuhan stakeholder sebelum memulai analisis itu penting?',
                        'explanation' => 'Mendengarkan kebutuhan stakeholder membantu data analyst memahami pertanyaan bisnis yang sebenarnya ingin dijawab, sehingga hasil analisis yang diberikan benar-benar relevan dan tidak melenceng dari kebutuhan awal.',
                        'options' => [
                            ['text' => 'Membantu memahami pertanyaan bisnis sebenarnya, sehingga hasil analisis relevan', 'correct' => true],
                            ['text' => 'Karena stakeholder selalu tahu metode statistik yang tepat untuk dipakai', 'correct' => false],
                            ['text' => 'Karena tanpa mendengarkan, data analyst tidak diizinkan mengakses database', 'correct' => false],
                            ['text' => 'Tidak ada manfaat praktis, hanya formalitas semata', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Contoh penyampaian mana yang lebih tepat untuk audiens non-teknis: "Korelasi 0.87 dengan p-value < 0.05" atau "Setiap kenaikan anggaran iklan cenderung diikuti kenaikan penjualan yang cukup konsisten"?',
                        'explanation' => 'Kalimat kedua menyampaikan pesan yang sama namun dengan bahasa yang relevan dan mudah dipahami audiens tanpa latar belakang statistik, tanpa istilah teknis seperti "korelasi" atau "p-value".',
                        'options' => [
                            ['text' => '"Setiap kenaikan anggaran iklan cenderung diikuti kenaikan penjualan yang cukup konsisten"', 'correct' => true],
                            ['text' => '"Korelasi 0.87 dengan p-value < 0.05"', 'correct' => false],
                            ['text' => 'Keduanya sama efektifnya untuk audiens non-teknis manapun', 'correct' => false],
                            ['text' => 'Tidak ada perbedaan penting antara keduanya', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa data analyst yang mampu menyesuaikan cara komunikasi dengan latar belakang audiens umumnya punya pengaruh lebih besar terhadap keputusan bisnis?',
                        'explanation' => 'Kemampuan menyesuaikan komunikasi menentukan seberapa besar hasil analisis benar-benar dipahami dan dipakai dalam pengambilan keputusan, bukan sekadar dianggap laporan teknis yang sulit dimengerti dan akhirnya diabaikan.',
                        'options' => [
                            ['text' => 'Hasil analisisnya lebih mudah dipahami sehingga lebih mungkin benar-benar dipakai untuk keputusan', 'correct' => true],
                            ['text' => 'Karena mereka tidak perlu lagi melakukan analisis data secara teknis', 'correct' => false],
                            ['text' => 'Karena stakeholder non-teknis tidak pernah membaca laporan apapun', 'correct' => false],
                            ['text' => 'Tidak ada hubungan antara cara komunikasi dan pengaruh terhadap keputusan bisnis', 'correct' => false],
                        ],
                    ],
                ],
            ],
        ];
    }
}