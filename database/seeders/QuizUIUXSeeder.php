<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

/**
 * Quiz untuk seluruh assignment career "UI/UX Designer"
 * (Modul 1-6: Design Thinking & User Research, Wireframing & Prototyping,
 * Visual Design & Tipografi, HTML & CSS untuk Designer, Usability Testing,
 * Design Handoff & Kolaborasi dengan Developer).
 *
 * Jalankan setelah LearningPathSeeder & AddAssignmentsToExistingModulesSeeder
 * (assignment-nya harus sudah ada). Idempotent: quiz di-updateOrCreate per
 * assignment, dan soal lama dihapus dulu sebelum di-recreate.
 */
class QuizUIUXSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->quizData() as $assignmentTitle => $data) {
            $assignment = Assignment::where('title', $assignmentTitle)->first();

            if (! $assignment) {
                $this->command?->warn("QuizUIUXSeeder: assignment tidak ditemukan — {$assignmentTitle}");
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
            // MODUL 1: Design Thinking & User Research
            // ============================================================
            'Assignment 1: User Research & Persona Creation' => [
                'title' => 'Quiz: Design Thinking, Wawancara & User Persona',
                'questions' => [
                    [
                        'question' => 'Apa titik awal utama dalam pendekatan design thinking untuk memecahkan masalah?',
                        'explanation' => 'Design thinking berfokus pada kebutuhan pengguna sebagai titik awal, bukan pada asumsi atau preferensi pribadi desainer, sehingga solusi yang dihasilkan benar-benar relevan dengan masalah nyata pengguna.',
                        'options' => [
                            ['text' => 'Kebutuhan pengguna yang nyata, bukan asumsi atau preferensi pribadi desainer', 'correct' => true],
                            ['text' => 'Tren visual yang sedang populer di media sosial', 'correct' => false],
                            ['text' => 'Preferensi pribadi desainer terhadap warna tertentu', 'correct' => false],
                            ['text' => 'Jumlah fitur sebanyak mungkin yang bisa ditambahkan', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa proses design thinking (empathize-define-ideate-prototype-test) tidak selalu berjalan secara linear?',
                        'explanation' => 'Tim sering kembali ke tahap sebelumnya ketika menemukan informasi baru, misalnya kembali ke tahap define setelah hasil pengujian menunjukkan rumusan masalah sebelumnya kurang tepat.',
                        'options' => [
                            ['text' => 'Tim sering kembali ke tahap sebelumnya saat menemukan informasi baru dari pengujian', 'correct' => true],
                            ['text' => 'Karena tahap prototype dan test sebenarnya sama persis', 'correct' => false],
                            ['text' => 'Karena tahap empathize hanya dilakukan sekali di awal proyek dan tidak pernah diulang', 'correct' => false],
                            ['text' => 'Karena design thinking tidak punya tahapan yang jelas', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa pertanyaan terbuka lebih disarankan dibanding pertanyaan tertutup (ya/tidak) saat wawancara pengguna?',
                        'explanation' => 'Pertanyaan terbuka memungkinkan pengguna menjelaskan pengalamannya dengan kata-kata sendiri, memberikan pemahaman yang lebih mendalam dibanding pertanyaan tertutup yang hanya memerlukan jawaban ya/tidak.',
                        'options' => [
                            ['text' => 'Memungkinkan pengguna menjelaskan pengalaman dengan kata-katanya sendiri secara mendalam', 'correct' => true],
                            ['text' => 'Pertanyaan terbuka lebih cepat dijawab oleh peserta', 'correct' => false],
                            ['text' => 'Pertanyaan tertutup tidak diperbolehkan sama sekali dalam riset', 'correct' => false],
                            ['text' => 'Pertanyaan terbuka tidak butuh persiapan sebelumnya', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa fungsi utama user persona dalam proses desain?',
                        'explanation' => 'User persona membantu tim desain selalu mempertimbangkan sudut pandang pengguna nyata saat membuat keputusan desain, berdasarkan pola yang benar-benar ditemukan dari data riset, bukan tebakan semata.',
                        'options' => [
                            ['text' => 'Membantu tim mempertimbangkan sudut pandang pengguna nyata berdasarkan data riset', 'correct' => true],
                            ['text' => 'Menggantikan kebutuhan melakukan riset pengguna sepenuhnya', 'correct' => false],
                            ['text' => 'Sebagai dokumen resmi yang wajib diserahkan ke investor', 'correct' => false],
                            ['text' => 'Untuk menentukan warna utama yang akan dipakai di aplikasi', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa persona harus disusun berdasarkan data riset nyata, bukan sekadar imajinasi desainer?',
                        'explanation' => 'Persona bukanlah hasil akhir riset, melainkan alat bantu mengomunikasikan hasil riset — persona yang dibuat berdasarkan tebakan semata berisiko mengarahkan tim ke keputusan desain yang tidak relevan dengan pengguna sebenarnya.',
                        'options' => [
                            ['text' => 'Persona yang dibuat dari tebakan berisiko mengarahkan desain ke arah yang tidak relevan', 'correct' => true],
                            ['text' => 'Karena persona wajib disetujui oleh seluruh anggota tim marketing', 'correct' => false],
                            ['text' => 'Karena tool desain tidak bisa menyimpan persona hasil imajinasi', 'correct' => false],
                            ['text' => 'Tidak ada perbedaan penting antara persona berbasis data dan imajinasi', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Customer Journey Map Project' => [
                'title' => 'Quiz: User Journey Mapping & Analisis Kompetitor',
                'questions' => [
                    [
                        'question' => 'Apa yang digambarkan oleh sebuah user journey map?',
                        'explanation' => 'User journey map menggambarkan seluruh tahapan yang dilalui pengguna saat berinteraksi dengan suatu produk untuk mencapai tujuan tertentu, mulai dari awal hingga akhir.',
                        'options' => [
                            ['text' => 'Seluruh tahapan yang dilalui pengguna dari awal hingga akhir mencapai tujuan tertentu', 'correct' => true],
                            ['text' => 'Struktur folder desain di dalam file Figma', 'correct' => false],
                            ['text' => 'Daftar warna dan tipografi yang dipakai pada produk', 'correct' => false],
                            ['text' => 'Alur kode teknis di balik sebuah fitur aplikasi', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Elemen apa saja yang umum dicakup dalam sebuah journey map?',
                        'explanation' => 'Journey map biasanya mencakup tahapan yang dilalui pengguna, tindakan yang dilakukan, perasaan/emosi pada tiap tahap, serta permasalahan atau peluang perbaikan yang teridentifikasi.',
                        'options' => [
                            ['text' => 'Tahapan, tindakan, perasaan pengguna, serta pain point/peluang perbaikan', 'correct' => true],
                            ['text' => 'Hanya daftar warna yang dipakai di setiap halaman', 'correct' => false],
                            ['text' => 'Hanya kode HTML dari setiap halaman yang dilalui', 'correct' => false],
                            ['text' => 'Hanya nama-nama file yang ada di repository', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa journey mapping membantu tim menemukan peluang perbaikan yang mungkin tidak terlihat kalau hanya fokus pada satu fitur saja?',
                        'explanation' => 'Dengan memetakan keseluruhan perjalanan pengguna, tim bisa mengidentifikasi titik-titik kesulitan (pain point) di seluruh alur, bukan hanya pada satu bagian tertentu yang kebetulan sedang dikerjakan.',
                        'options' => [
                            ['text' => 'Journey map melihat pengalaman pengguna secara menyeluruh, bukan hanya satu titik interaksi', 'correct' => true],
                            ['text' => 'Journey map hanya bisa dibuat untuk aplikasi mobile', 'correct' => false],
                            ['text' => 'Journey map menggantikan kebutuhan wireframe sepenuhnya', 'correct' => false],
                            ['text' => 'Journey map tidak berhubungan dengan pain point pengguna', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa tujuan utama melakukan analisis kompetitor dalam proses desain?',
                        'explanation' => 'Tujuan analisis kompetitor bukan untuk meniru secara langsung, melainkan memahami standar dan ekspektasi yang sudah terbentuk di benak pengguna, serta mengidentifikasi peluang memberikan pengalaman yang lebih baik.',
                        'options' => [
                            ['text' => 'Memahami standar industri dan mengidentifikasi peluang memberi pengalaman lebih baik', 'correct' => true],
                            ['text' => 'Meniru seluruh tampilan kompetitor persis sama', 'correct' => false],
                            ['text' => 'Mengetahui password akun kompetitor', 'correct' => false],
                            ['text' => 'Menghindari riset pengguna sendiri sepenuhnya', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Selain kompetitor langsung, produk seperti apa yang juga relevan dipelajari saat analisis kompetitor?',
                        'explanation' => 'Analisis kompetitor juga mencakup produk dari industri berbeda yang menghadapi tantangan desain serupa, misalnya alur checkout, untuk mendapatkan wawasan dan perspektif tambahan di luar kompetitor langsung.',
                        'options' => [
                            ['text' => 'Produk dari industri berbeda yang menghadapi tantangan desain serupa', 'correct' => true],
                            ['text' => 'Hanya produk yang punya nama merek sama persis', 'correct' => false],
                            ['text' => 'Tidak perlu mempelajari produk lain sama sekali selain milik sendiri', 'correct' => false],
                            ['text' => 'Hanya produk yang sudah tidak aktif digunakan lagi', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 2: Wireframing & Prototyping
            // ============================================================
            'Assignment 1: Low-Fidelity Wireframe Set' => [
                'title' => 'Quiz: Wireframe & Hierarki Visual',
                'questions' => [
                    [
                        'question' => 'Apa fokus utama sebuah wireframe, dibanding desain visual yang sudah lengkap?',
                        'explanation' => 'Wireframe berfokus pada struktur dan tata letak elemen, bukan pada detail visual seperti warna atau tipografi, biasanya digambarkan dalam bentuk kotak, garis, dan teks placeholder.',
                        'options' => [
                            ['text' => 'Struktur dan tata letak elemen, bukan detail visual seperti warna/tipografi', 'correct' => true],
                            ['text' => 'Warna dan tipografi final yang akan dipakai di produk', 'correct' => false],
                            ['text' => 'Kode HTML dan CSS yang siap diimplementasikan', 'correct' => false],
                            ['text' => 'Animasi transisi antar halaman yang detail', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa tim biasanya membahas wireframe dulu sebelum masuk ke detail visual yang lebih kompleks?',
                        'explanation' => 'Wireframe memungkinkan tim fokus membahas struktur dan alur informasi terlebih dahulu — diskusi bisa berjalan lebih cepat, dan perubahan struktur besar bisa dilakukan tanpa membuang banyak pekerjaan visual yang sudah detail.',
                        'options' => [
                            ['text' => 'Perubahan struktur besar bisa dilakukan tanpa membuang banyak pekerjaan visual yang detail', 'correct' => true],
                            ['text' => 'Karena wireframe wajib dibuat sebelum riset pengguna dilakukan', 'correct' => false],
                            ['text' => 'Karena wireframe menggantikan kebutuhan prototype sepenuhnya', 'correct' => false],
                            ['text' => 'Karena tool desain tidak mengizinkan pembuatan desain visual langsung', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa perbedaan low-fidelity wireframe dan high-fidelity wireframe?',
                        'explanation' => 'Low-fidelity wireframe dibuat sangat sederhana (kadang berupa sketsa kasar) dan cocok untuk eksplorasi awal, sedangkan high-fidelity wireframe dibuat lebih detail dari segi tata letak dan ukuran, meski masih tanpa styling visual penuh.',
                        'options' => [
                            ['text' => 'Low-fidelity sangat sederhana untuk eksplorasi awal, high-fidelity lebih detail tata letaknya', 'correct' => true],
                            ['text' => 'Low-fidelity dan high-fidelity sebenarnya istilah yang sama persis', 'correct' => false],
                            ['text' => 'High-fidelity wireframe sudah termasuk warna final produk', 'correct' => false],
                            ['text' => 'Low-fidelity wireframe hanya bisa dibuat lewat kode, bukan tool desain', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Prinsip proximity dalam hierarki visual berarti apa?',
                        'explanation' => 'Proximity berarti elemen yang berkaitan sebaiknya diletakkan berdekatan, sementara elemen yang tidak berkaitan diberi jarak lebih jauh, sehingga pengguna dapat memahami pengelompokan informasi secara intuitif.',
                        'options' => [
                            ['text' => 'Elemen yang berkaitan diletakkan berdekatan, yang tidak berkaitan diberi jarak lebih jauh', 'correct' => true],
                            ['text' => 'Seluruh elemen harus memiliki ukuran yang sama persis', 'correct' => false],
                            ['text' => 'Seluruh teks harus memakai warna yang identik', 'correct' => false],
                            ['text' => 'Setiap halaman wajib memiliki jumlah elemen yang sama', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa hierarki visual yang baik membantu pengguna memahami suatu halaman dengan cepat?',
                        'explanation' => 'Mata secara alami tertarik ke elemen yang paling menonjol terlebih dahulu, sehingga hierarki visual yang jelas memandu pengguna melalui informasi sesuai tingkat kepentingannya tanpa perlu membaca setiap elemen secara detail.',
                        'options' => [
                            ['text' => 'Mata secara alami tertarik ke elemen paling menonjol lebih dulu, memandu urutan perhatian', 'correct' => true],
                            ['text' => 'Karena hierarki visual menghapus kebutuhan menulis konten teks', 'correct' => false],
                            ['text' => 'Karena hierarki visual wajib diterapkan oleh regulasi aksesibilitas', 'correct' => false],
                            ['text' => 'Karena tanpa hierarki visual, halaman tidak bisa ditampilkan sama sekali', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Interactive Prototype in Figma' => [
                'title' => 'Quiz: Prototype Interaktif & Design System Dasar',
                'questions' => [
                    [
                        'question' => 'Apa perbedaan utama prototype dengan wireframe biasa?',
                        'explanation' => 'Berbeda dengan wireframe yang bersifat statis, prototype adalah versi interaktif dari desain yang memungkinkan pengguna benar-benar mengklik dan berpindah antar halaman, sehingga bisa merasakan alur penggunaan secara lebih nyata.',
                        'options' => [
                            ['text' => 'Prototype bersifat interaktif (bisa diklik), wireframe biasa bersifat statis', 'correct' => true],
                            ['text' => 'Prototype hanya bisa dibuat dengan kode, wireframe dengan tool desain', 'correct' => false],
                            ['text' => 'Prototype tidak boleh menampilkan tata letak elemen', 'correct' => false],
                            ['text' => 'Wireframe selalu lebih detail dari prototype', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa prototype interaktif berguna sebelum produk benar-benar dikembangkan secara penuh?',
                        'explanation' => 'Prototype memungkinkan tim menguji dan memvalidasi alur pengguna terlebih dahulu — masalah yang teridentifikasi di tahap prototype jauh lebih murah dan cepat diperbaiki dibanding ditemukan setelah produk selesai dikembangkan.',
                        'options' => [
                            ['text' => 'Masalah pada alur bisa ditemukan dan diperbaiki lebih murah sebelum dikembangkan penuh', 'correct' => true],
                            ['text' => 'Prototype menggantikan kebutuhan pengembangan produk sepenuhnya', 'correct' => false],
                            ['text' => 'Prototype wajib dibuat setelah produk selesai dikembangkan', 'correct' => false],
                            ['text' => 'Prototype tidak bisa dites oleh pengguna sama sekali', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa itu design system?',
                        'explanation' => 'Design system adalah kumpulan komponen desain yang telah distandarkan beserta aturan penggunaannya, yang bisa dipakai berulang kali di seluruh bagian produk, sehingga proses desain lebih cepat dan konsisten.',
                        'options' => [
                            ['text' => 'Kumpulan komponen desain yang distandarkan dan bisa dipakai berulang di seluruh produk', 'correct' => true],
                            ['text' => 'Nama lain untuk file wireframe berukuran besar', 'correct' => false],
                            ['text' => 'Software khusus pengganti Figma', 'correct' => false],
                            ['text' => 'Daftar bug yang ditemukan saat usability testing', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa design system membuat konsistensi visual di seluruh produk lebih terjaga?',
                        'explanation' => 'Dengan design system, seluruh tim menggunakan komponen dan aturan visual (warna, tipografi, spacing) yang sama, sehingga tidak ada tampilan yang berbeda-beda untuk elemen yang seharusnya identik, seperti tombol yang sama fungsinya.',
                        'options' => [
                            ['text' => 'Seluruh tim memakai komponen dan aturan visual yang sama untuk elemen yang identik fungsinya', 'correct' => true],
                            ['text' => 'Design system membatasi jumlah desainer yang boleh bekerja pada satu proyek', 'correct' => false],
                            ['text' => 'Design system otomatis membuat kode aplikasi tanpa developer', 'correct' => false],
                            ['text' => 'Design system hanya relevan untuk produk dengan satu halaman saja', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Fitur Figma mana yang memungkinkan elemen menyesuaikan ukuran secara otomatis berdasarkan kontennya, mirip konsep flexbox pada CSS?',
                        'explanation' => 'Auto layout di Figma memungkinkan elemen menyesuaikan ukuran secara otomatis berdasarkan kontennya, konsepnya mirip dengan flexbox pada CSS yang akan dipelajari lebih lanjut pada materi HTML & CSS.',
                        'options' => [
                            ['text' => 'Auto layout', 'correct' => true],
                            ['text' => 'Component', 'correct' => false],
                            ['text' => 'Frame kosong', 'correct' => false],
                            ['text' => 'Prototyping mode saja', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 3: Visual Design & Tipografi
            // ============================================================
            'Assignment 1: Mobile App Visual Design' => [
                'title' => 'Quiz: Teori Warna & Prinsip Tipografi',
                'questions' => [
                    [
                        'question' => 'Apa fungsi warna semantic dalam desain antarmuka?',
                        'explanation' => 'Warna semantic dipakai untuk menyampaikan makna tertentu secara konsisten, misalnya merah untuk error, hijau untuk sukses, dan kuning untuk peringatan, sehingga pengguna bisa langsung mengenali jenis pesan hanya dari warnanya.',
                        'options' => [
                            ['text' => 'Menyampaikan makna tertentu secara konsisten, misalnya merah untuk error dan hijau untuk sukses', 'correct' => true],
                            ['text' => 'Menentukan jenis font yang dipakai di seluruh halaman', 'correct' => false],
                            ['text' => 'Hanya untuk keperluan estetika tanpa makna khusus', 'correct' => false],
                            ['text' => 'Menggantikan kebutuhan teks penjelasan sepenuhnya', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa kontras yang cukup antara warna teks dan latar belakang itu penting?',
                        'explanation' => 'Kontras yang cukup memastikan konten tetap mudah dibaca oleh pengguna, termasuk pengguna dengan gangguan penglihatan tertentu — ini juga menjadi bagian dari prinsip aksesibilitas dalam desain visual.',
                        'options' => [
                            ['text' => 'Memastikan konten tetap mudah dibaca, termasuk oleh pengguna dengan gangguan penglihatan', 'correct' => true],
                            ['text' => 'Kontras hanya berpengaruh pada kecepatan loading halaman', 'correct' => false],
                            ['text' => 'Kontras tidak berhubungan sama sekali dengan aksesibilitas', 'correct' => false],
                            ['text' => 'Kontras hanya relevan untuk desain berbasis cetak, bukan digital', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang dimaksud dengan line height dalam tipografi?',
                        'explanation' => 'Line height adalah jarak antar baris teks, yang memengaruhi kenyamanan membaca terutama pada paragraf panjang — line height yang terlalu rapat bisa membuat teks sulit dibaca.',
                        'options' => [
                            ['text' => 'Jarak antar baris teks yang memengaruhi kenyamanan membaca', 'correct' => true],
                            ['text' => 'Ketebalan huruf yang dipakai pada judul', 'correct' => false],
                            ['text' => 'Jenis huruf yang dipilih untuk body text', 'correct' => false],
                            ['text' => 'Warna latar belakang di balik teks', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa manfaat menggunakan skala tipografi (type scale) yang konsisten di seluruh halaman produk?',
                        'explanation' => 'Penggunaan skala tipografi yang konsisten membantu menjaga hierarki visual yang jelas di seluruh halaman, sehingga judul, subjudul, dan teks isi selalu punya peran yang mudah dikenali.',
                        'options' => [
                            ['text' => 'Menjaga hierarki visual yang jelas di seluruh halaman produk', 'correct' => true],
                            ['text' => 'Mengurangi jumlah warna yang boleh dipakai dalam desain', 'correct' => false],
                            ['text' => 'Menghilangkan kebutuhan menulis judul pada setiap halaman', 'correct' => false],
                            ['text' => 'Mempercepat proses loading gambar di halaman', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kesalahan umum apa yang perlu dihindari terkait tipografi pada satu halaman?',
                        'explanation' => 'Menggunakan terlalu banyak variasi ukuran dan jenis huruf dalam satu halaman justru bisa membuat tampilan terlihat tidak terstruktur dan membingungkan bagi pengguna.',
                        'options' => [
                            ['text' => 'Menggunakan terlalu banyak variasi ukuran dan jenis huruf dalam satu halaman', 'correct' => true],
                            ['text' => 'Menggunakan skala tipografi yang sudah ditentukan secara konsisten', 'correct' => false],
                            ['text' => 'Memberi ukuran lebih besar pada judul dibanding body text', 'correct' => false],
                            ['text' => 'Menyesuaikan line height agar paragraf lebih nyaman dibaca', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Design System Style Guide' => [
                'title' => 'Quiz: Design System, Grid System & Aksesibilitas Visual',
                'questions' => [
                    [
                        'question' => 'Apa yang biasanya dicakup dalam sebuah design system style guide?',
                        'explanation' => 'Style guide biasanya mencakup elemen dasar seperti warna, tipografi, spacing, serta komponen yang lebih kompleks seperti tombol, form, kartu, dan navigasi yang mengikuti aturan visual yang sama.',
                        'options' => [
                            ['text' => 'Warna, tipografi, spacing, dan komponen (tombol, form, kartu, navigasi)', 'correct' => true],
                            ['text' => 'Hanya kode HTML dan CSS dari seluruh halaman', 'correct' => false],
                            ['text' => 'Hanya daftar bug yang perlu diperbaiki developer', 'correct' => false],
                            ['text' => 'Hanya nama-nama anggota tim yang terlibat dalam proyek', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang dimaksud dengan grid system dalam desain antarmuka?',
                        'explanation' => 'Grid system adalah kerangka kerja yang membagi halaman menjadi kolom-kolom dan baris dengan jarak (gutter) tertentu, dipakai sebagai acuan untuk menempatkan elemen desain secara konsisten dan proporsional.',
                        'options' => [
                            ['text' => 'Kerangka kerja yang membagi halaman menjadi kolom-kolom untuk penempatan elemen yang konsisten', 'correct' => true],
                            ['text' => 'Fitur untuk mengelola nama file di dalam project Figma', 'correct' => false],
                            ['text' => 'Sistem penomoran versi desain', 'correct' => false],
                            ['text' => 'Alat untuk mengecek ejaan teks pada desain', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa sistem spacing berbasis kelipatan tertentu (misalnya kelipatan 8px) sering dipakai dibanding angka acak?',
                        'explanation' => 'Sistem spacing berbasis kelipatan menjaga konsistensi jarak di seluruh halaman produk, sehingga desain terlihat lebih rapi dan mudah diimplementasikan developer dibanding memakai nilai acak seperti 13px atau 27px.',
                        'options' => [
                            ['text' => 'Menjaga konsistensi jarak antar elemen di seluruh produk', 'correct' => true],
                            ['text' => 'Karena tool desain hanya mendukung angka kelipatan 8', 'correct' => false],
                            ['text' => 'Karena angka acak membuat file desain menjadi rusak', 'correct' => false],
                            ['text' => 'Tidak ada manfaat praktis, hanya kebiasaan tanpa alasan jelas', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa status error sebaiknya tidak hanya mengandalkan warna merah saja untuk menyampaikan informasinya?',
                        'explanation' => 'Prinsip aksesibilitas menekankan untuk tidak hanya mengandalkan warna dalam menyampaikan informasi penting — status error sebaiknya juga disertai ikon atau teks, agar tetap dipahami pengguna yang kesulitan membedakan warna tertentu.',
                        'options' => [
                            ['text' => 'Agar tetap dipahami pengguna yang kesulitan membedakan warna tertentu', 'correct' => true],
                            ['text' => 'Karena warna merah tidak boleh dipakai dalam desain apapun', 'correct' => false],
                            ['text' => 'Karena warna merah selalu diasosiasikan dengan sukses, bukan error', 'correct' => false],
                            ['text' => 'Karena ikon dan teks membuat halaman menjadi lebih lambat dimuat', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa mempertimbangkan aksesibilitas sejak tahap desain dianggap lebih baik dibanding menambahkannya belakangan?',
                        'explanation' => 'Mempertimbangkan aksesibilitas sejak awal proses desain umumnya jauh lebih mudah dan murah dibandingkan menambahkannya belakangan setelah produk hampir selesai dikembangkan.',
                        'options' => [
                            ['text' => 'Jauh lebih mudah dan murah dibanding menambahkannya setelah produk hampir selesai', 'correct' => true],
                            ['text' => 'Karena aksesibilitas tidak bisa ditambahkan setelah produk dirilis sama sekali', 'correct' => false],
                            ['text' => 'Karena aksesibilitas hanya relevan untuk produk pemerintahan', 'correct' => false],
                            ['text' => 'Tidak ada perbedaan biaya maupun usaha kapan pun aksesibilitas dipertimbangkan', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 4: HTML & CSS untuk Designer
            // ============================================================
            'Assignment 1: Static Page from Figma Design' => [
                'title' => 'Quiz: Struktur HTML & Dasar CSS',
                'questions' => [
                    [
                        'question' => 'Kenapa desainer dianggap perlu memahami dasar HTML dan CSS, meski tidak dituntut menulis kode profesional seperti developer?',
                        'explanation' => 'Pemahaman ini membantu desainer membuat keputusan desain yang realistis dari sisi implementasi, serta mempermudah komunikasi teknis dengan developer mengenai batasan maupun kemungkinan implementasi.',
                        'options' => [
                            ['text' => 'Membantu membuat keputusan desain yang realistis dan mempermudah komunikasi dengan developer', 'correct' => true],
                            ['text' => 'Karena desainer wajib menggantikan pekerjaan developer sepenuhnya', 'correct' => false],
                            ['text' => 'Karena tanpa HTML/CSS, file Figma tidak bisa dibuka sama sekali', 'correct' => false],
                            ['text' => 'Karena aturan hukum mewajibkan desainer menulis kode produksi', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Elemen HTML apa yang biasa dipakai sebagai kontainer umum untuk mengelompokkan elemen lain?',
                        'explanation' => 'Elemen div dipakai sebagai kontainer umum untuk mengelompokkan elemen-elemen lain dalam struktur HTML, sebelum diberi styling lebih lanjut lewat CSS.',
                        'options' => [
                            ['text' => 'div', 'correct' => true],
                            ['text' => 'img', 'correct' => false],
                            ['text' => 'br', 'correct' => false],
                            ['text' => 'hr', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Selector CSS mana yang dipakai untuk menargetkan elemen berdasarkan atribut class-nya?',
                        'explanation' => 'Selector class ditandai dengan tanda titik (misalnya .kartu-produk), dipakai untuk elemen tertentu yang diberi atribut class yang sama, berbeda dengan selector id yang ditandai tanda pagar (#) untuk satu elemen unik.',
                        'options' => [
                            ['text' => 'Tanda titik (.nama-class)', 'correct' => true],
                            ['text' => 'Tanda pagar (#nama-class)', 'correct' => false],
                            ['text' => 'Tanda kurung siku ([nama-class])', 'correct' => false],
                            ['text' => 'Tanda persen (%nama-class)', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Properti CSS mana yang mengatur jarak di dalam elemen, antara batas elemen dengan kontennya?',
                        'explanation' => 'padding mengatur jarak di dalam elemen (antara border dengan konten), berbeda dengan margin yang mengatur jarak di luar elemen terhadap elemen lain di sekitarnya.',
                        'options' => [
                            ['text' => 'padding', 'correct' => true],
                            ['text' => 'margin', 'correct' => false],
                            ['text' => 'color', 'correct' => false],
                            ['text' => 'font-size', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa kemampuan membaca kode CSS dasar berguna bagi desainer saat mengecek hasil implementasi developer?',
                        'explanation' => 'Kemampuan ini membantu desainer melakukan pengecekan cepat, misalnya memverifikasi apakah warna atau spacing yang diterapkan developer sudah sesuai dengan spesifikasi desain yang ditentukan di design system.',
                        'options' => [
                            ['text' => 'Membantu memverifikasi apakah warna/spacing hasil implementasi sudah sesuai spesifikasi desain', 'correct' => true],
                            ['text' => 'Karena developer tidak diizinkan menulis CSS tanpa persetujuan desainer', 'correct' => false],
                            ['text' => 'Karena CSS menggantikan kebutuhan Figma sepenuhnya', 'correct' => false],
                            ['text' => 'Tidak ada manfaat praktis bagi desainer dalam membaca CSS', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Responsive Landing Page Handoff' => [
                'title' => 'Quiz: Responsive Design & Batasan Implementasi',
                'questions' => [
                    [
                        'question' => 'Apa itu responsive design?',
                        'explanation' => 'Responsive design adalah kemampuan tampilan web untuk menyesuaikan susunan elemennya secara otomatis sesuai ukuran layar pengguna, mulai dari smartphone hingga desktop.',
                        'options' => [
                            ['text' => 'Kemampuan tampilan menyesuaikan susunan elemen secara otomatis sesuai ukuran layar', 'correct' => true],
                            ['text' => 'Teknik untuk mempercepat loading gambar di halaman', 'correct' => false],
                            ['text' => 'Fitur untuk menyimpan riwayat desain secara otomatis', 'correct' => false],
                            ['text' => 'Cara mengenkripsi kode CSS agar tidak bisa disalin', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Properti CSS mana yang perlu diaktifkan untuk mulai menggunakan flexbox pada sebuah kontainer?',
                        'explanation' => "display: flex mengaktifkan flexbox pada suatu kontainer, sehingga elemen-elemen di dalamnya bisa diatur secara fleksibel baik horizontal maupun vertikal.",
                        'options' => [
                            ['text' => 'display: flex', 'correct' => true],
                            ['text' => 'position: fixed', 'correct' => false],
                            ['text' => 'color: auto', 'correct' => false],
                            ['text' => 'border: flex', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa fungsi properti justify-content pada flexbox?',
                        'explanation' => 'justify-content mengatur posisi elemen secara horizontal di dalam kontainer flex, misalnya rata kiri, tengah, atau tersebar merata (space-between).',
                        'options' => [
                            ['text' => 'Mengatur posisi elemen secara horizontal di dalam kontainer', 'correct' => true],
                            ['text' => 'Mengubah warna latar belakang elemen', 'correct' => false],
                            ['text' => 'Menentukan jenis font yang dipakai', 'correct' => false],
                            ['text' => 'Menghapus elemen dari tampilan halaman', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa pemahaman mengenai batasan implementasi teknis penting bagi seorang desainer sebelum menyerahkan desain ke developer?',
                        'explanation' => 'Memahami batasan ini membantu desainer menilai secara realistis apakah suatu ide desain mudah diimplementasikan, memerlukan usaha tambahan signifikan, atau bahkan sulit dilakukan — sehingga diskusi trade-off dengan developer bisa lebih produktif.',
                        'options' => [
                            ['text' => 'Membantu menilai realistis tidaknya suatu ide desain diimplementasikan, dan berdiskusi trade-off dengan developer', 'correct' => true],
                            ['text' => 'Karena developer tidak akan pernah mau mengerjakan desain yang kompleks', 'correct' => false],
                            ['text' => 'Karena batasan teknis membuat kreativitas desainer harus sepenuhnya dihilangkan', 'correct' => false],
                            ['text' => 'Tidak ada manfaat praktis, hanya formalitas semata', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa mempertimbangkan responsive design sejak tahap desain dianggap penting mengingat kondisi pengguna saat ini?',
                        'explanation' => 'Mayoritas pengguna produk digital mengakses aplikasi dari berbagai jenis perangkat, sehingga desain yang tidak mempertimbangkan aspek responsif sejak awal berisiko menghasilkan pengalaman yang buruk pada perangkat tertentu.',
                        'options' => [
                            ['text' => 'Mayoritas pengguna mengakses dari berbagai jenis perangkat berbeda', 'correct' => true],
                            ['text' => 'Karena seluruh pengguna hanya memakai satu jenis perangkat yang sama', 'correct' => false],
                            ['text' => 'Karena responsive design hanya relevan untuk aplikasi desktop', 'correct' => false],
                            ['text' => 'Karena flexbox tidak bisa dipakai pada layar kecil', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 5: Usability Testing
            // ============================================================
            'Assignment 1: Usability Testing Session Report' => [
                'title' => 'Quiz: Usability Testing & Penyusunan Skenario',
                'questions' => [
                    [
                        'question' => 'Apa tujuan utama usability testing?',
                        'explanation' => 'Tujuan usability testing bukan untuk menguji kemampuan pengguna, melainkan mengidentifikasi bagian antarmuka yang membingungkan, sulit digunakan, atau menyebabkan pengguna melakukan kesalahan.',
                        'options' => [
                            ['text' => 'Mengidentifikasi bagian antarmuka yang membingungkan atau sulit digunakan', 'correct' => true],
                            ['text' => 'Menguji seberapa pintar peserta dalam menyelesaikan tugas', 'correct' => false],
                            ['text' => 'Menentukan siapa peserta yang paling cepat menyelesaikan tugas', 'correct' => false],
                            ['text' => 'Mengganti kebutuhan riset pengguna di awal proyek', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa desainer sebaiknya tidak langsung membantu peserta yang mengalami kesulitan selama sesi usability testing?',
                        'explanation' => 'Agar penyebab masalah bisa diamati secara objektif — kalau desainer langsung membantu, tim jadi tidak tahu apakah antarmuka tersebut memang sulit dipahami pengguna secara alami.',
                        'options' => [
                            ['text' => 'Agar penyebab masalah bisa diamati secara objektif tanpa campur tangan', 'correct' => true],
                            ['text' => 'Karena membantu peserta dilarang oleh aturan hukum', 'correct' => false],
                            ['text' => 'Karena peserta akan merasa tersinggung kalau dibantu', 'correct' => false],
                            ['text' => 'Tidak ada alasan khusus, hanya kebiasaan tanpa dasar yang jelas', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa instruksi tugas dalam skenario usability testing sebaiknya tidak diberi petunjuk yang terlalu spesifik?',
                        'explanation' => 'Petunjuk yang terlalu spesifik dapat memengaruhi perilaku peserta, sehingga hasil pengujian tidak lagi mencerminkan bagaimana pengguna sebenarnya berinteraksi dengan produk secara alami.',
                        'options' => [
                            ['text' => 'Petunjuk yang terlalu spesifik dapat memengaruhi perilaku peserta secara tidak alami', 'correct' => true],
                            ['text' => 'Karena instruksi yang detail membuat sesi pengujian berjalan lebih lama', 'correct' => false],
                            ['text' => 'Karena peserta tidak boleh mengetahui tugas yang harus dikerjakan', 'correct' => false],
                            ['text' => 'Tidak ada dampak apapun terhadap hasil pengujian', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Selain waktu penyelesaian tugas, apa lagi yang umum dicatat selama pengamatan usability testing?',
                        'explanation' => 'Beberapa hal yang umum dicatat meliputi jumlah kesalahan, bagian yang membuat pengguna berhenti cukup lama, serta komentar spontan yang diucapkan peserta selama menggunakan produk.',
                        'options' => [
                            ['text' => 'Jumlah kesalahan, bagian yang membuat pengguna berhenti lama, dan komentar spontan peserta', 'correct' => true],
                            ['text' => 'Hanya warna baju yang dipakai peserta saat pengujian', 'correct' => false],
                            ['text' => 'Hanya jumlah like yang didapat produk di media sosial', 'correct' => false],
                            ['text' => 'Hanya usia peserta tanpa mencatat perilaku apapun', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa masalah yang dialami oleh lebih banyak peserta usability testing perlu diprioritaskan lebih tinggi untuk diperbaiki?',
                        'explanation' => 'Semakin banyak peserta mengalami masalah yang sama, semakin tinggi kemungkinan masalah tersebut juga akan dialami oleh pengguna nyata secara luas, sehingga dampaknya lebih signifikan jika tidak segera diperbaiki.',
                        'options' => [
                            ['text' => 'Semakin banyak peserta mengalaminya, semakin besar kemungkinan dampaknya pada pengguna luas', 'correct' => true],
                            ['text' => 'Karena masalah yang dialami banyak peserta selalu lebih mudah diperbaiki', 'correct' => false],
                            ['text' => 'Karena masalah yang jarang dialami tidak perlu dicatat sama sekali', 'correct' => false],
                            ['text' => 'Tidak ada hubungan antara jumlah peserta dan prioritas perbaikan', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Design Iteration Based on Feedback' => [
                'title' => 'Quiz: Analisis Temuan & Iterasi Desain',
                'questions' => [
                    [
                        'question' => 'Kenapa tidak semua temuan usability testing memiliki tingkat kepentingan yang sama?',
                        'explanation' => 'Desainer perlu menentukan prioritas berdasarkan dampaknya terhadap pengalaman pengguna — masalah yang menyebabkan pengguna gagal menyelesaikan tugas biasanya punya prioritas lebih tinggi dibanding yang hanya sedikit memperlambat proses.',
                        'options' => [
                            ['text' => 'Prioritas ditentukan berdasarkan dampaknya terhadap pengalaman pengguna', 'correct' => true],
                            ['text' => 'Semua temuan wajib diperbaiki dengan urutan yang sama persis', 'correct' => false],
                            ['text' => 'Temuan yang muncul lebih dulu selalu lebih penting dari yang lain', 'correct' => false],
                            ['text' => 'Prioritas ditentukan berdasarkan siapa peserta yang melaporkannya', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa langkah yang dilakukan setelah masalah usability ditemukan dan dianalisis?',
                        'explanation' => 'Desainer melakukan iterasi dengan memperbaiki desain berdasarkan hasil pengujian, kemudian produk yang telah diperbaiki bisa diuji kembali untuk memastikan masalah sebelumnya telah teratasi.',
                        'options' => [
                            ['text' => 'Melakukan iterasi (perbaikan desain) berdasarkan temuan, lalu menguji ulang', 'correct' => true],
                            ['text' => 'Menghentikan seluruh proyek desain secara permanen', 'correct' => false],
                            ['text' => 'Mengabaikan temuan karena sudah terlanjur dikerjakan', 'correct' => false],
                            ['text' => 'Langsung merilis produk tanpa perubahan apapun', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa proses "uji-perbaiki-uji ulang" dianggap bagian penting dari siklus desain yang berpusat pada pengguna?',
                        'explanation' => 'Pendekatan iteratif ini membantu menghasilkan produk yang semakin mudah digunakan dari waktu ke waktu, karena tim jarang menghasilkan solusi terbaik hanya pada percobaan pertama.',
                        'options' => [
                            ['text' => 'Membantu menghasilkan produk yang semakin mudah digunakan dari waktu ke waktu', 'correct' => true],
                            ['text' => 'Karena siklus ini wajib dilakukan hanya sekali dalam satu proyek', 'correct' => false],
                            ['text' => 'Karena tim desain selalu menghasilkan solusi sempurna pada percobaan pertama', 'correct' => false],
                            ['text' => 'Karena iterasi menggantikan kebutuhan riset pengguna di awal proyek', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kalau ditemukan bahwa pengguna kesulitan menemukan tombol Checkout dalam usability testing, langkah paling tepat adalah?',
                        'explanation' => 'Temuan yang membuat pengguna gagal atau kesulitan menyelesaikan tugas inti (seperti checkout) biasanya masuk prioritas tinggi, sehingga perbaikan pada elemen tersebut sebaiknya menjadi fokus iterasi desain berikutnya.',
                        'options' => [
                            ['text' => 'Memprioritaskan perbaikan pada visibilitas/posisi tombol Checkout di iterasi berikutnya', 'correct' => true],
                            ['text' => 'Mengabaikan temuan tersebut karena hanya dialami sedikit peserta', 'correct' => false],
                            ['text' => 'Mengganti seluruh desain produk dari awal tanpa mempertimbangkan temuan lain', 'correct' => false],
                            ['text' => 'Menghapus fitur checkout sepenuhnya dari produk', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa penting menjelaskan perubahan yang dilakukan setelah iterasi desain, bukan hanya menerapkannya begitu saja?',
                        'explanation' => 'Menjelaskan perubahan membantu tim lain, termasuk developer dan stakeholder, memahami alasan di balik keputusan desain baru, sehingga perubahan tersebut lebih mudah diterima dan didiskusikan kalau ada masukan lebih lanjut.',
                        'options' => [
                            ['text' => 'Membantu tim lain memahami alasan di balik keputusan desain baru', 'correct' => true],
                            ['text' => 'Karena developer tidak diizinkan mengimplementasikan perubahan tanpa penjelasan', 'correct' => false],
                            ['text' => 'Karena penjelasan wajib dilampirkan menurut regulasi hukum', 'correct' => false],
                            ['text' => 'Tidak ada manfaat praktis, hanya formalitas dokumentasi semata', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 6: Design Handoff & Kolaborasi dengan Developer
            // ============================================================
            'Assignment 1: Design Handoff Documentation' => [
                'title' => 'Quiz: Design Handoff & Persiapan Aset',
                'questions' => [
                    [
                        'question' => 'Apa yang dicakup dalam proses design handoff, selain sekadar mengirim file desain?',
                        'explanation' => 'Design handoff mencakup penyampaian informasi yang diperlukan agar developer memahami cara desain seharusnya diwujudkan, seperti ukuran elemen, warna, tipografi, spacing, ikon, aset, hingga perilaku interaksi.',
                        'options' => [
                            ['text' => 'Informasi ukuran, warna, tipografi, spacing, aset, dan perilaku interaksi', 'correct' => true],
                            ['text' => 'Hanya link ke file Figma tanpa informasi tambahan apapun', 'correct' => false],
                            ['text' => 'Hanya daftar nama anggota tim desain', 'correct' => false],
                            ['text' => 'Hanya video rekaman proses desain dibuat', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Fitur Figma mana yang memudahkan developer melihat spesifikasi desain secara langsung saat proses handoff?',
                        'explanation' => 'Fitur inspect di Figma memudahkan developer melihat spesifikasi desain seperti ukuran, warna, dan jarak secara langsung tanpa perlu bertanya manual ke desainer.',
                        'options' => [
                            ['text' => 'Fitur inspect', 'correct' => true],
                            ['text' => 'Fitur comment saja', 'correct' => false],
                            ['text' => 'Fitur auto layout', 'correct' => false],
                            ['text' => 'Fitur prototyping mode', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa seluruh komponen sebaiknya diberi nama yang konsisten dan mengikuti design system sebelum handoff dilakukan?',
                        'explanation' => 'Penamaan yang konsisten memudahkan developer memahami fungsi setiap elemen tanpa harus bertanya berulang kali, mempercepat proses implementasi.',
                        'options' => [
                            ['text' => 'Memudahkan developer memahami fungsi tiap elemen tanpa bertanya berulang kali', 'correct' => true],
                            ['text' => 'Karena Figma tidak bisa menyimpan komponen tanpa nama', 'correct' => false],
                            ['text' => 'Karena penamaan komponen memengaruhi kecepatan loading aplikasi', 'correct' => false],
                            ['text' => 'Tidak ada manfaat praktis, hanya soal kerapian file semata', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang perlu diperiksa ulang sebelum melakukan design handoff kepada developer?',
                        'explanation' => 'Pemeriksaan ulang sebelum handoff penting untuk memastikan tidak ada frame yang belum selesai, warna yang belum sesuai, atau komponen yang masih menggunakan placeholder.',
                        'options' => [
                            ['text' => 'Frame yang belum selesai, warna yang belum sesuai, atau komponen yang masih placeholder', 'correct' => true],
                            ['text' => 'Jumlah like yang didapat desain di media sosial', 'correct' => false],
                            ['text' => 'Riwayat chat pribadi antar anggota tim', 'correct' => false],
                            ['text' => 'Jumlah folder yang ada di komputer desainer', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa manfaat utama dokumentasi handoff yang lengkap bagi proses pengembangan produk?',
                        'explanation' => 'Semakin lengkap dokumentasi yang diberikan, semakin kecil kemungkinan terjadi perbedaan antara desain dan hasil implementasi, sehingga mempercepat proses pengembangan dan mengurangi revisi.',
                        'options' => [
                            ['text' => 'Mengurangi kemungkinan perbedaan antara desain dan hasil implementasi', 'correct' => true],
                            ['text' => 'Menghapus kebutuhan developer untuk menulis kode sama sekali', 'correct' => false],
                            ['text' => 'Membuat proses desain menjadi lebih lambat secara keseluruhan', 'correct' => false],
                            ['text' => 'Tidak berpengaruh pada kecepatan maupun akurasi implementasi', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Design QA Checklist Exercise' => [
                'title' => 'Quiz: Design QA & Kolaborasi dengan Tim Produk',
                'questions' => [
                    [
                        'question' => 'Apa tujuan utama Design QA (Quality Assurance) setelah developer menyelesaikan implementasi?',
                        'explanation' => 'Design QA bertujuan memastikan hasil implementasi sudah sesuai dengan desain yang telah disepakati, mencakup ukuran, warna, tipografi, spacing, konsistensi layout, hingga perilaku interaksi.',
                        'options' => [
                            ['text' => 'Memastikan hasil implementasi sudah sesuai dengan desain yang disepakati', 'correct' => true],
                            ['text' => 'Menentukan siapa yang harus disalahkan atas kesalahan implementasi', 'correct' => false],
                            ['text' => 'Menghapus fitur yang tidak sesuai anggaran proyek', 'correct' => false],
                            ['text' => 'Mengganti seluruh desain dari awal setelah developer selesai bekerja', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa Design QA sebaiknya tidak dipandang sebagai proses mencari kesalahan developer?',
                        'explanation' => 'Design QA bertujuan menjaga kualitas produk sebelum dirilis kepada pengguna, bukan untuk menyalahkan developer — fokusnya pada perbaikan bersama agar hasil akhir sesuai standar yang disepakati.',
                        'options' => [
                            ['text' => 'Tujuannya menjaga kualitas produk bersama, bukan menyalahkan developer', 'correct' => true],
                            ['text' => 'Karena developer tidak pernah membuat kesalahan implementasi', 'correct' => false],
                            ['text' => 'Karena Design QA hanya dilakukan oleh developer sendiri tanpa desainer', 'correct' => false],
                            ['text' => 'Karena Design QA hanya relevan untuk produk yang sudah dirilis lama', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kalau komunikasi antara desainer dan developer terjadi seperti "animasi ini berat di perangkat lama" dan disepakati memakai animasi fade sederhana, apa yang ditunjukkan situasi ini?',
                        'explanation' => 'Situasi ini menunjukkan diskusi terbuka yang mempertimbangkan keterbatasan teknis, menghasilkan solusi yang tetap memenuhi kebutuhan pengguna tanpa membebani performa aplikasi.',
                        'options' => [
                            ['text' => 'Diskusi terbuka yang menghasilkan solusi realistis mempertimbangkan keterbatasan teknis', 'correct' => true],
                            ['text' => 'Developer menolak seluruh permintaan desainer tanpa diskusi', 'correct' => false],
                            ['text' => 'Desainer memaksakan animasi kompleks tanpa mempertimbangkan performa', 'correct' => false],
                            ['text' => 'Proyek dihentikan karena perbedaan pendapat yang tidak terselesaikan', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Setelah user research, wireframe, visual design, hingga usability testing, tahap apa yang biasanya dilakukan sebelum development dimulai?',
                        'explanation' => 'Berdasarkan alur kerja umum pengembangan produk digital, design handoff dilakukan setelah usability testing dan sebelum development, agar developer punya spesifikasi yang jelas untuk diimplementasikan.',
                        'options' => [
                            ['text' => 'Design handoff', 'correct' => true],
                            ['text' => 'User research dari awal lagi', 'correct' => false],
                            ['text' => 'Rilis produk ke publik', 'correct' => false],
                            ['text' => 'Pengumpulan feedback pengguna pasca-rilis', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa memahami keseluruhan workflow kolaborasi tim produk (dari riset sampai rilis) penting bagi seorang UI/UX Designer?',
                        'explanation' => 'Memahami workflow ini membantu desainer melihat bahwa desain bukan proses yang berdiri sendiri, melainkan bagian dari siklus pengembangan produk secara keseluruhan yang melibatkan Product Manager, Developer, QA, dan stakeholder lainnya.',
                        'options' => [
                            ['text' => 'Membantu memahami desain sebagai bagian dari siklus pengembangan produk secara keseluruhan', 'correct' => true],
                            ['text' => 'Karena desainer harus menggantikan peran Product Manager sepenuhnya', 'correct' => false],
                            ['text' => 'Karena workflow ini hanya relevan bagi developer, bukan desainer', 'correct' => false],
                            ['text' => 'Tidak ada manfaat praktis bagi pekerjaan sehari-hari seorang desainer', 'correct' => false],
                        ],
                    ],
                ],
            ],
        ];
    }
}