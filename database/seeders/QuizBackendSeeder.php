<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

/**
 * Quiz untuk seluruh assignment career "Backend Developer"
 * (Modul 1-7: Node.js Fundamentals, Membangun REST API,
 * Database SQL & NoSQL, Authentication & Security,
 * Git & Collaboration Workflow, Testing & Debugging Backend,
 * Server Architecture & Performance).
 *
 * Jalankan setelah LearningPathSeeder & AddAssignmentsToExistingModulesSeeder
 * (assignment-nya harus sudah ada). Idempotent: quiz di-updateOrCreate per
 * assignment, dan soal lama dihapus dulu sebelum di-recreate.
 */
class QuizBackendSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->quizData() as $assignmentTitle => $data) {
            $assignment = Assignment::where('title', $assignmentTitle)->first();

            if (! $assignment) {
                $this->command?->warn("QuizBackendSeeder: assignment tidak ditemukan — {$assignmentTitle}");
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
            // MODUL 1: Node.js Fundamentals
            // ============================================================
            'Assignment 1: Static File Server with Node.js' => [
                'title' => 'Quiz: Node.js & Modul Bawaan (http, fs, path)',
                'questions' => [
                    [
                        'question' => 'Apa yang membedakan Node.js dari JavaScript yang biasa jalan di browser?',
                        'explanation' => 'Node.js memungkinkan JavaScript berjalan di luar browser, langsung di server, dengan akses ke hal-hal seperti file system dan koneksi jaringan yang tidak dimiliki JavaScript di browser.',
                        'options' => [
                            ['text' => 'Node.js memberi akses ke file system, jaringan, dan hal-hal di luar browser', 'correct' => true],
                            ['text' => 'Node.js adalah bahasa pemrograman yang berbeda total dari JavaScript', 'correct' => false],
                            ['text' => 'Node.js hanya bisa dipakai untuk styling CSS', 'correct' => false],
                            ['text' => 'Node.js tidak bisa membaca file sama sekali', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Modul bawaan Node.js mana yang dipakai untuk membaca/menulis file dari disk?',
                        'explanation' => 'Modul fs (File System) menyediakan fungsi untuk membaca dan menulis file, baik secara synchronous maupun asynchronous.',
                        'options' => [
                            ['text' => 'fs', 'correct' => true],
                            ['text' => 'http', 'correct' => false],
                            ['text' => 'path', 'correct' => false],
                            ['text' => 'os', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa modul path dipakai untuk menggabungkan lokasi file, bukan sekadar menyambung string manual?',
                        'explanation' => 'Modul path menangani perbedaan format path antar sistem operasi (Windows vs Linux/Mac) secara otomatis, dan mencegah celah keamanan seperti path traversal saat menggabungkan path dari input pengguna.',
                        'options' => [
                            ['text' => 'Menangani perbedaan format path antar OS dan mencegah celah keamanan', 'correct' => true],
                            ['text' => 'Supaya file otomatis ter-compress', 'correct' => false],
                            ['text' => 'Karena string biasa tidak bisa berisi karakter "/"', 'correct' => false],
                            ['text' => 'Supaya server berjalan lebih cepat', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa versi asynchronous dari fungsi fs lebih disarankan dibanding versi synchronous di server yang menangani banyak request?',
                        'explanation' => 'Versi synchronous akan memblokir seluruh proses Node.js sampai operasi file selesai, sehingga request lain harus menunggu — versi asynchronous membiarkan proses lain tetap berjalan sambil menunggu file selesai dibaca.',
                        'options' => [
                            ['text' => 'Supaya proses lain tidak ikut terblokir menunggu operasi file selesai', 'correct' => true],
                            ['text' => 'Karena versi synchronous tidak bisa membaca file besar', 'correct' => false],
                            ['text' => 'Karena versi asynchronous tidak butuh modul fs', 'correct' => false],
                            ['text' => 'Tidak ada bedanya sama sekali', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Modul http bawaan Node.js dipakai untuk apa?',
                        'explanation' => 'Modul http memungkinkan Node.js membuat server web dari nol, menerima request HTTP dan mengirim response — menjadi dasar sebelum memakai framework seperti Express.',
                        'options' => [
                            ['text' => 'Membuat server yang menerima request HTTP dan mengirim response', 'correct' => true],
                            ['text' => 'Mengenkripsi password pengguna', 'correct' => false],
                            ['text' => 'Membaca file CSS dan JavaScript di frontend', 'correct' => false],
                            ['text' => 'Menjalankan query SQL ke database', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Async Task Queue Simulator' => [
                'title' => 'Quiz: Event Loop, NPM & Environment Variables',
                'questions' => [
                    [
                        'question' => 'Apa yang membuat Node.js bisa menangani ribuan koneksi sekaligus meski hanya single-threaded?',
                        'explanation' => 'Event Loop memungkinkan Node.js "menitipkan" operasi yang butuh waktu lama (seperti I/O) ke background, lalu lanjut mengerjakan hal lain, sehingga tidak perlu menunggu satu proses selesai sebelum mengerjakan proses lain.',
                        'options' => [
                            ['text' => 'Event Loop yang menangani operasi I/O secara non-blocking', 'correct' => true],
                            ['text' => 'Node.js sebenarnya multi-threaded seperti bahasa lain', 'correct' => false],
                            ['text' => 'Node.js membatasi jumlah request yang bisa masuk', 'correct' => false],
                            ['text' => 'Setiap request otomatis dijalankan di server terpisah', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Diberikan console.log("A"); setTimeout(() => console.log("B"), 0); console.log("C"); — urutan output yang benar adalah?',
                        'explanation' => 'Meskipun delay setTimeout 0 ms, callback-nya tetap dijalankan setelah seluruh kode synchronous (A dan C) selesai, karena masuk antrean Event Loop terlebih dulu — outputnya A, C, B.',
                        'options' => [
                            ['text' => 'A, C, B', 'correct' => true],
                            ['text' => 'A, B, C', 'correct' => false],
                            ['text' => 'B, A, C', 'correct' => false],
                            ['text' => 'C, B, A', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'File apa yang mencatat daftar dependency (library) yang dipakai sebuah proyek Node.js beserta versinya?',
                        'explanation' => 'package.json adalah "daftar belanja" proyek Node.js — mencatat nama proyek, versi, dan seluruh dependency yang dibutuhkan agar proyek bisa di-install ulang secara konsisten di komputer manapun.',
                        'options' => [
                            ['text' => 'package.json', 'correct' => true],
                            ['text' => '.env', 'correct' => false],
                            ['text' => 'node_modules.json', 'correct' => false],
                            ['text' => 'index.js', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa data konfigurasi sensitif (seperti password database) sebaiknya disimpan di file .env, bukan ditulis langsung di kode?',
                        'explanation' => 'File .env memisahkan data rahasia dari kode program, dan bisa dikecualikan dari Git (lewat .gitignore) sehingga tidak ikut ter-push ke repository publik dan bocor ke siapapun.',
                        'options' => [
                            ['text' => 'Supaya data rahasia tidak ikut ter-commit ke Git dan bocor ke publik', 'correct' => true],
                            ['text' => 'Supaya aplikasi berjalan lebih cepat', 'correct' => false],
                            ['text' => 'Karena Node.js tidak bisa membaca variabel dari kode langsung', 'correct' => false],
                            ['text' => 'Supaya password otomatis ter-enkripsi', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Perintah apa yang dipakai untuk meng-install semua dependency yang tercatat di package.json ke folder node_modules?',
                        'explanation' => 'npm install membaca daftar dependency di package.json dan mendownload semuanya ke folder node_modules, memastikan siapapun yang clone proyek bisa mendapat library yang sama persis.',
                        'options' => [
                            ['text' => 'npm install', 'correct' => true],
                            ['text' => 'node run', 'correct' => false],
                            ['text' => 'npm start', 'correct' => false],
                            ['text' => 'php artisan install', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 2: Membangun REST API
            // ============================================================
            'Assignment 1: Task Manager REST API' => [
                'title' => 'Quiz: Routing, HTTP Method & Express Middleware',
                'questions' => [
                    [
                        'question' => 'Kombinasi route + method mana yang tepat untuk "mengambil semua data tugas"?',
                        'explanation' => 'GET dipakai untuk mengambil data tanpa mengubah apapun di server — GET /tasks berarti "ambil semua tugas".',
                        'options' => [
                            ['text' => 'GET /tasks', 'correct' => true],
                            ['text' => 'POST /tasks', 'correct' => false],
                            ['text' => 'DELETE /tasks', 'correct' => false],
                            ['text' => 'PUT /tasks/all', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'HTTP method mana yang dipakai untuk membuat data tugas BARU?',
                        'explanation' => 'POST dipakai untuk membuat resource baru — misalnya POST /tasks untuk menambah tugas baru ke database.',
                        'options' => [
                            ['text' => 'POST', 'correct' => true],
                            ['text' => 'GET', 'correct' => false],
                            ['text' => 'DELETE', 'correct' => false],
                            ['text' => 'HEAD', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang dimaksud dengan middleware pada Express.js?',
                        'explanation' => 'Middleware adalah function yang "menangkap" request sebelum sampai ke handler akhir, dipakai untuk hal seperti parsing body, logging, atau autentikasi, sebelum memanggil next() untuk lanjut ke proses berikutnya.',
                        'options' => [
                            ['text' => 'Function yang memproses request sebelum sampai ke handler akhir', 'correct' => true],
                            ['text' => 'Database khusus milik Express', 'correct' => false],
                            ['text' => 'Nama lain dari routing di Express', 'correct' => false],
                            ['text' => 'Library untuk styling response API', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Middleware bawaan apa yang perlu diaktifkan di Express supaya req.body bisa membaca data JSON yang dikirim client?',
                        'explanation' => 'express.json() adalah middleware bawaan Express yang mem-parsing body request berformat JSON sehingga bisa diakses lewat req.body.',
                        'options' => [
                            ['text' => 'app.use(express.json())', 'correct' => true],
                            ['text' => 'app.use(express.parse())', 'correct' => false],
                            ['text' => 'app.get(express.body())', 'correct' => false],
                            ['text' => 'Tidak perlu apa-apa, otomatis aktif', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kalau middleware tidak memanggil next() dan tidak mengirim response, apa yang terjadi pada request tersebut?',
                        'explanation' => 'Request akan menggantung (hang) tanpa response, karena Express menunggu next() dipanggil untuk lanjut ke handler berikutnya, atau menunggu response dikirim — kalau tidak ada keduanya, client akan terus menunggu.',
                        'options' => [
                            ['text' => 'Request menggantung tanpa response sampai timeout', 'correct' => true],
                            ['text' => 'Request otomatis dilanjutkan ke handler berikutnya', 'correct' => false],
                            ['text' => 'Server langsung crash', 'correct' => false],
                            ['text' => 'Express otomatis mengirim response kosong', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Book Catalog API' => [
                'title' => 'Quiz: Params, Query, Body & Error Handling',
                'questions' => [
                    [
                        'question' => 'Untuk mengambil id dari URL /books/5, data itu diakses lewat properti apa di Express?',
                        'explanation' => 'req.params.id berisi bagian dinamis dari URL sesuai definisi route (misal /books/:id) — di sini isinya "5".',
                        'options' => [
                            ['text' => 'req.params', 'correct' => true],
                            ['text' => 'req.query', 'correct' => false],
                            ['text' => 'req.body', 'correct' => false],
                            ['text' => 'req.headers', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Untuk mengambil data dari URL /books?kategori=fiksi, data "fiksi" diakses lewat properti apa?',
                        'explanation' => 'req.query berisi parameter yang ada setelah tanda tanya (?) di URL — biasanya dipakai untuk filter, sorting, atau pagination.',
                        'options' => [
                            ['text' => 'req.query', 'correct' => true],
                            ['text' => 'req.params', 'correct' => false],
                            ['text' => 'req.body', 'correct' => false],
                            ['text' => 'req.cookies', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Status code HTTP mana yang tepat dikembalikan kalau input dari client tidak valid (misal judul buku kosong)?',
                        'explanation' => '400 Bad Request menandakan kesalahan ada di sisi client (data yang dikirim tidak sesuai format/aturan), berbeda dengan 500 yang menandakan error di server.',
                        'options' => [
                            ['text' => '400', 'correct' => true],
                            ['text' => '200', 'correct' => false],
                            ['text' => '500', 'correct' => false],
                            ['text' => '301', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa validasi input tetap wajib dilakukan di server, meskipun frontend sudah melakukan validasi juga?',
                        'explanation' => 'Frontend bisa "dilewati" — misalnya seseorang mengirim request langsung lewat Postman atau curl tanpa lewat form frontend sama sekali, jadi validasi di server adalah lapisan pertahanan yang tidak boleh diskip.',
                        'options' => [
                            ['text' => 'Karena request bisa dikirim langsung ke API tanpa lewat frontend sama sekali', 'correct' => true],
                            ['text' => 'Supaya kode di frontend jadi lebih pendek', 'correct' => false],
                            ['text' => 'Karena frontend tidak bisa melakukan validasi', 'correct' => false],
                            ['text' => 'Validasi di server tidak diperlukan kalau sudah ada di frontend', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Status code mana yang tepat dikembalikan kalau buku dengan id tertentu yang diminta tidak ditemukan di database?',
                        'explanation' => '404 Not Found menandakan resource yang diminta (buku dengan id tersebut) memang tidak ada, jelas berbeda maknanya dari 400 (input salah format) atau 500 (error server).',
                        'options' => [
                            ['text' => '404', 'correct' => true],
                            ['text' => '400', 'correct' => false],
                            ['text' => '201', 'correct' => false],
                            ['text' => '503', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 3: API Structure Refactor' => [
                'title' => 'Quiz: Struktur Proyek Routes-Controllers-Services',
                'questions' => [
                    [
                        'question' => 'Dalam arsitektur routes-controllers-services, lapisan mana yang HANYA berisi definisi endpoint (URL + method), tanpa logic bisnis?',
                        'explanation' => 'Routes hanya menghubungkan URL dan HTTP method ke controller yang sesuai — logic sesungguhnya ada di controller dan service.',
                        'options' => [
                            ['text' => 'Routes', 'correct' => true],
                            ['text' => 'Services', 'correct' => false],
                            ['text' => 'Models', 'correct' => false],
                            ['text' => 'Middleware autentikasi', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Lapisan mana yang bertugas menerima request, memanggil service, lalu mengembalikan response ke client?',
                        'explanation' => 'Controller menjadi penghubung antara HTTP request/response dengan logic bisnis di service — ia tidak menyimpan logic bisnis yang rumit, hanya mengorkestrasi alurnya.',
                        'options' => [
                            ['text' => 'Controllers', 'correct' => true],
                            ['text' => 'Routes', 'correct' => false],
                            ['text' => 'Views', 'correct' => false],
                            ['text' => 'Config', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa satu file server.js raksasa yang berisi semua endpoint jadi masalah ketika proyek berkembang?',
                        'explanation' => 'File besar yang berisi campuran semua fitur menyulitkan pencarian kode tertentu, rawan konflik Git saat dikerjakan banyak orang, dan sulit dites secara terpisah.',
                        'options' => [
                            ['text' => 'Sulit dicari, rawan konflik Git antar developer, dan sulit dites terpisah', 'correct' => true],
                            ['text' => 'Node.js membatasi ukuran file maksimal', 'correct' => false],
                            ['text' => 'Aplikasi otomatis berjalan lebih lambat', 'correct' => false],
                            ['text' => 'File besar tidak bisa di-deploy ke server', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Setelah refactor API menjadi struktur routes-controllers-services, hal yang PALING PENTING untuk dipastikan adalah?',
                        'explanation' => 'Tujuan refactor adalah mengubah struktur kode tanpa mengubah perilaku — endpoint yang sudah ada harus tetap berfungsi persis sama seperti sebelum di-refactor.',
                        'options' => [
                            ['text' => 'Seluruh endpoint tetap berfungsi sama seperti sebelum di-refactor', 'correct' => true],
                            ['text' => 'Jumlah file harus sebanyak mungkin', 'correct' => false],
                            ['text' => 'Nama variabel harus diubah semua ke bahasa Inggris', 'correct' => false],
                            ['text' => 'Endpoint lama boleh berubah perilaku asal strukturnya rapi', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa manfaat memisahkan logic database ke dalam lapisan services, terpisah dari controller?',
                        'explanation' => 'Service yang tidak bergantung pada req/res HTTP jadi lebih mudah dites secara terisolasi, dan bisa dipakai ulang oleh bagian lain aplikasi (misalnya dari command line script) tanpa perlu lewat HTTP.',
                        'options' => [
                            ['text' => 'Logic jadi lebih mudah dites terisolasi dan dipakai ulang di luar konteks HTTP', 'correct' => true],
                            ['text' => 'Supaya query database berjalan otomatis lebih cepat', 'correct' => false],
                            ['text' => 'Karena controller tidak boleh mengakses database sama sekali', 'correct' => false],
                            ['text' => 'Supaya tidak perlu memakai database sama sekali', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 3: Database SQL & NoSQL
            // ============================================================
            'Assignment 1: Library Database Schema Design' => [
                'title' => 'Quiz: Desain Skema & Normalisasi',
                'questions' => [
                    [
                        'question' => 'Apa yang dimaksud dengan normalisasi pada desain database?',
                        'explanation' => 'Normalisasi adalah proses merapikan skema database untuk menghindari duplikasi data yang tidak perlu, misalnya memisahkan data produk ke tabel tersendiri daripada mengulang nama produk di setiap baris order.',
                        'options' => [
                            ['text' => 'Proses merapikan skema untuk menghindari duplikasi data yang tidak perlu', 'correct' => true],
                            ['text' => 'Proses mengubah database SQL menjadi NoSQL', 'correct' => false],
                            ['text' => 'Proses mengenkripsi seluruh data di database', 'correct' => false],
                            ['text' => 'Proses menghapus tabel yang jarang dipakai', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kalau tabel peminjaman butuh tahu buku mana yang dipinjam, cara yang tepat (bukan mengulang detail buku) adalah?',
                        'explanation' => 'Tabel peminjaman cukup menyimpan book_id sebagai referensi (foreign key) ke tabel books, bukan menyalin ulang judul/penulis buku di setiap baris peminjaman.',
                        'options' => [
                            ['text' => 'Menyimpan book_id sebagai referensi ke tabel books', 'correct' => true],
                            ['text' => 'Menyalin ulang seluruh detail buku ke tabel peminjaman', 'correct' => false],
                            ['text' => 'Menyimpan detail buku dalam format gambar', 'correct' => false],
                            ['text' => 'Membuat tabel peminjaman terpisah untuk setiap buku', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa risiko utama dari skema database yang buruk (banyak duplikasi data) begitu aplikasi sudah berjalan di production dengan data asli?',
                        'explanation' => 'Mengubah struktur tabel setelah ada banyak data asli adalah pekerjaan berisiko tinggi — bisa menyebabkan data hilang/rusak kalau migrasinya tidak hati-hati, itu sebabnya desain skema perlu dipikirkan matang sejak awal.',
                        'options' => [
                            ['text' => 'Sulit dan berisiko diperbaiki belakangan setelah ada banyak data asli', 'correct' => true],
                            ['text' => 'Tidak ada risiko apapun, bisa diubah kapan saja dengan mudah', 'correct' => false],
                            ['text' => 'Database otomatis menghapus data duplikat sendiri', 'correct' => false],
                            ['text' => 'Aplikasi akan otomatis berhenti berjalan', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Diagram yang biasa dipakai untuk memvisualisasikan relasi antar tabel sebelum coding disebut?',
                        'explanation' => 'ERD (Entity Relationship Diagram) memvisualisasikan tabel, kolom, dan relasi antar tabel, membantu tim mendiskusikan desain skema sebelum benar-benar ditulis dalam SQL.',
                        'options' => [
                            ['text' => 'ERD (Entity Relationship Diagram)', 'correct' => true],
                            ['text' => 'Flowchart API', 'correct' => false],
                            ['text' => 'Wireframe', 'correct' => false],
                            ['text' => 'Sequence Diagram', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa denormalisasi (sengaja menduplikasi sedikit data) kadang dilakukan meskipun umumnya normalisasi lebih disarankan?',
                        'explanation' => 'Dalam kasus tertentu, sedikit duplikasi data bisa membuat query jadi lebih cepat (menghindari JOIN yang berat) — trade-off ini kadang diambil sadar untuk kebutuhan performa spesifik, bukan asal-asalan.',
                        'options' => [
                            ['text' => 'Untuk mempercepat query tertentu dengan menghindari JOIN yang berat', 'correct' => true],
                            ['text' => 'Karena normalisasi tidak didukung database modern', 'correct' => false],
                            ['text' => 'Karena wajib dilakukan di semua skema database', 'correct' => false],
                            ['text' => 'Supaya data lebih mudah dihapus', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Blog CRUD with MySQL' => [
                'title' => 'Quiz: Query SQL Dasar (SELECT, INSERT, UPDATE, DELETE)',
                'questions' => [
                    [
                        'question' => 'Kesalahan paling berbahaya yang bisa terjadi kalau lupa menambahkan klausa WHERE pada query UPDATE adalah?',
                        'explanation' => 'Tanpa WHERE, UPDATE akan mengubah SEMUA baris di tabel tersebut, bukan hanya baris yang dituju — ini kesalahan fatal yang sering jadi "horror story" di dunia kerja.',
                        'options' => [
                            ['text' => 'Semua baris di tabel ikut ter-update, bukan hanya yang dituju', 'correct' => true],
                            ['text' => 'Query akan otomatis ditolak oleh MySQL', 'correct' => false],
                            ['text' => 'Tidak ada baris sama sekali yang ter-update', 'correct' => false],
                            ['text' => 'Tabel akan otomatis ter-backup sebelum update', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Perintah SQL mana yang dipakai untuk menambahkan satu baris data baru ke tabel posts?',
                        'explanation' => 'INSERT INTO posts (...) VALUES (...) adalah perintah standar untuk menambahkan baris baru ke sebuah tabel.',
                        'options' => [
                            ['text' => 'INSERT INTO posts (...) VALUES (...)', 'correct' => true],
                            ['text' => 'CREATE TABLE posts (...)', 'correct' => false],
                            ['text' => 'ADD posts (...)', 'correct' => false],
                            ['text' => 'NEW posts (...)', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Untuk mengambil hanya postingan yang statusnya "published", klausa apa yang dipakai di SELECT?',
                        'explanation' => 'WHERE status = "published" menyaring baris yang dikembalikan hanya yang memenuhi kondisi tersebut.',
                        'options' => [
                            ['text' => "WHERE status = 'published'", 'correct' => true],
                            ['text' => "FILTER status = 'published'", 'correct' => false],
                            ['text' => "ONLY status = 'published'", 'correct' => false],
                            ['text' => "IF status = 'published'", 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa sebaiknya menjalankan dulu SELECT dengan kondisi yang sama sebelum menjalankan DELETE dengan kondisi tersebut?',
                        'explanation' => 'Menjalankan SELECT dulu memastikan kondisi WHERE benar-benar menyaring baris yang dimaksud saja — kalau hasilnya sesuai ekspektasi, baru aman menjalankan DELETE dengan kondisi yang sama.',
                        'options' => [
                            ['text' => 'Untuk memverifikasi baris mana saja yang akan terpengaruh sebelum benar-benar dihapus', 'correct' => true],
                            ['text' => 'Karena DELETE tidak bisa dijalankan tanpa SELECT sebelumnya', 'correct' => false],
                            ['text' => 'Supaya database otomatis membuat backup', 'correct' => false],
                            ['text' => 'Tidak ada alasan khusus, hanya kebiasaan saja', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Perintah mana yang dipakai untuk mengurutkan hasil query postingan dari yang terbaru?',
                        'explanation' => 'ORDER BY created_at DESC mengurutkan hasil berdasarkan kolom created_at secara menurun (data terbaru muncul lebih dulu).',
                        'options' => [
                            ['text' => 'ORDER BY created_at DESC', 'correct' => true],
                            ['text' => 'SORT created_at DESC', 'correct' => false],
                            ['text' => 'GROUP BY created_at DESC', 'correct' => false],
                            ['text' => 'ORDER created_at NEWEST', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 3: Product Catalog with MongoDB' => [
                'title' => 'Quiz: NoSQL & MongoDB',
                'questions' => [
                    [
                        'question' => 'Bagaimana MongoDB menyimpan data, dibandingkan tabel pada database SQL?',
                        'explanation' => 'MongoDB menyimpan data dalam bentuk document (mirip JSON), bukan tabel dengan kolom yang kaku — setiap document bisa punya struktur berbeda dari document lain kalau perlu.',
                        'options' => [
                            ['text' => 'Dalam bentuk document (mirip JSON) yang strukturnya fleksibel', 'correct' => true],
                            ['text' => 'Dalam bentuk tabel dengan kolom yang harus sama persis', 'correct' => false],
                            ['text' => 'Dalam bentuk file Excel', 'correct' => false],
                            ['text' => 'Dalam bentuk gambar terkompresi', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa MongoDB dianggap cocok untuk katalog produk yang atributnya bisa beda-beda antar produk (misalnya baju punya field ukuran, buku punya field jumlah halaman)?',
                        'explanation' => 'Struktur document yang fleksibel memungkinkan tiap produk punya field yang berbeda tanpa perlu mengubah struktur "tabel" secara keseluruhan seperti di SQL.',
                        'options' => [
                            ['text' => 'Struktur document-nya fleksibel, tidak perlu kolom yang seragam untuk semua produk', 'correct' => true],
                            ['text' => 'MongoDB tidak mendukung penyimpanan data produk', 'correct' => false],
                            ['text' => 'MongoDB hanya bisa menyimpan angka', 'correct' => false],
                            ['text' => 'MongoDB otomatis membuat kolom yang sama untuk semua produk', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa nama proses menyimpan data terkait langsung sebagai bagian dari satu document besar di MongoDB (bukan referensi terpisah)?',
                        'explanation' => 'Embedding adalah pendekatan menyimpan data terkait langsung di dalam document yang sama, berbeda dengan referencing yang menyimpan id ke document lain (mirip foreign key di SQL).',
                        'options' => [
                            ['text' => 'Embedding', 'correct' => true],
                            ['text' => 'Normalizing', 'correct' => false],
                            ['text' => 'Sharding', 'correct' => false],
                            ['text' => 'Indexing', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Method mana yang dipakai untuk menambahkan satu document baru ke collection MongoDB?',
                        'explanation' => 'insertOne() menambahkan satu document baru ke sebuah collection di MongoDB.',
                        'options' => [
                            ['text' => 'insertOne()', 'correct' => true],
                            ['text' => 'CREATE TABLE', 'correct' => false],
                            ['text' => 'addRow()', 'correct' => false],
                            ['text' => 'newDocument()', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kapan umumnya SQL lebih dipilih dibanding MongoDB/NoSQL?',
                        'explanation' => 'SQL lebih cocok saat data butuh relasi kompleks antar tabel dan konsistensi ketat (misalnya transaksi keuangan yang tidak boleh "nyangkut" di tengah proses).',
                        'options' => [
                            ['text' => 'Saat butuh relasi antar data yang kompleks dan konsistensi yang ketat', 'correct' => true],
                            ['text' => 'Saat data selalu berbentuk document tidak beraturan', 'correct' => false],
                            ['text' => 'SQL selalu lebih baik dari NoSQL dalam kondisi apapun', 'correct' => false],
                            ['text' => 'Saat aplikasi tidak butuh menyimpan data sama sekali', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 4: Authentication & Security
            // ============================================================
            'Assignment 1: JWT Authentication System' => [
                'title' => 'Quiz: Hashing Password & JWT',
                'questions' => [
                    [
                        'question' => 'Kenapa password pengguna tidak boleh disimpan dalam bentuk teks asli (plain text) di database?',
                        'explanation' => 'Kalau database bocor, semua password akan langsung terekspos apa adanya — hashing membuat password tersimpan dalam bentuk yang tidak bisa dibalikkan ke aslinya secara praktis.',
                        'options' => [
                            ['text' => 'Kalau database bocor, semua password langsung terekspos ke siapapun', 'correct' => true],
                            ['text' => 'Supaya aplikasi berjalan lebih cepat', 'correct' => false],
                            ['text' => 'Karena database tidak mendukung teks panjang', 'correct' => false],
                            ['text' => 'Supaya pengguna bisa melihat password mereka sendiri kapan saja', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Saat pengguna login, sistem TIDAK "membongkar" hash untuk dapat password asli — sebaliknya, sistem melakukan apa?',
                        'explanation' => 'Sistem melakukan hash ULANG terhadap password yang baru diketik pengguna, lalu membandingkan hasil hash itu dengan hash yang tersimpan di database — bukan mendekripsi hash lama.',
                        'options' => [
                            ['text' => 'Hash ulang password yang diketik, lalu bandingkan dengan hash tersimpan', 'correct' => true],
                            ['text' => 'Mendekripsi hash lama untuk mendapat password asli', 'correct' => false],
                            ['text' => 'Mengirim password asli lewat email ke pengguna', 'correct' => false],
                            ['text' => 'Menyimpan password baru menimpa yang lama tanpa dicek', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa itu JWT (JSON Web Token) dalam konteks autentikasi API?',
                        'explanation' => 'JWT adalah "kartu identitas digital" berisi data user yang ditandatangani secara digital oleh server, dipakai untuk membuktikan bahwa user sudah login tanpa perlu kirim ulang password di setiap request.',
                        'options' => [
                            ['text' => 'Token berisi data user yang ditandatangani digital oleh server', 'correct' => true],
                            ['text' => 'Password terenkripsi yang dikirim setiap request', 'correct' => false],
                            ['text' => 'Nama lain dari session ID', 'correct' => false],
                            ['text' => 'File konfigurasi server', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa data sensitif (seperti password) TIDAK BOLEH dimasukkan ke dalam payload JWT?',
                        'explanation' => 'JWT itu encoded (bisa dibaca siapapun yang tahu cara decode-nya), bukan encrypted (rahasia) — jadi data apapun di payload JWT sebenarnya bisa dibaca orang lain kalau mereka punya tokennya.',
                        'options' => [
                            ['text' => 'JWT hanya encoded, bukan encrypted — isinya bisa dibaca siapapun yang punya token', 'correct' => true],
                            ['text' => 'JWT tidak bisa menyimpan string sama sekali', 'correct' => false],
                            ['text' => 'JWT otomatis menghapus data sensitif', 'correct' => false],
                            ['text' => 'Payload JWT dibatasi maksimal 10 karakter', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Library hashing seperti bcrypt sengaja dibuat "lambat" — apa alasannya?',
                        'explanation' => 'Semakin lambat proses hashing, semakin susah bagi penyerang untuk mencoba jutaan kombinasi password secara brute force dalam waktu singkat.',
                        'options' => [
                            ['text' => 'Supaya penyerang lebih sulit mencoba jutaan kombinasi password (brute force)', 'correct' => true],
                            ['text' => 'Supaya server tidak kelebihan beban', 'correct' => false],
                            ['text' => 'Karena keterbatasan teknis library tersebut', 'correct' => false],
                            ['text' => 'Supaya pengguna menunggu lebih lama saat mendaftar tanpa alasan', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Secure Login API' => [
                'title' => 'Quiz: Middleware Autentikasi & Input Sanitization',
                'questions' => [
                    [
                        'question' => 'Apa fungsi utama middleware autentikasi pada sebuah endpoint API?',
                        'explanation' => 'Middleware autentikasi mengecek apakah request punya token valid sebelum mengizinkan request lanjut ke handler — kalau tidak valid, request langsung ditolak dengan status 401.',
                        'options' => [
                            ['text' => 'Mengecek token valid sebelum mengizinkan request lanjut ke handler', 'correct' => true],
                            ['text' => 'Mempercepat proses query database', 'correct' => false],
                            ['text' => 'Mengubah format response menjadi HTML', 'correct' => false],
                            ['text' => 'Menghapus data yang sudah kadaluarsa', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Status code berapa yang tepat dikembalikan kalau request tidak menyertakan token sama sekali ke endpoint yang butuh login?',
                        'explanation' => '401 Unauthorized menandakan request tidak memiliki kredensial yang valid untuk mengakses resource tersebut.',
                        'options' => [
                            ['text' => '401', 'correct' => true],
                            ['text' => '200', 'correct' => false],
                            ['text' => '404', 'correct' => false],
                            ['text' => '500', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa tujuan dari input sanitization pada data yang dikirim client?',
                        'explanation' => 'Sanitization membersihkan data dari karakter/kode berbahaya sebelum diproses atau disimpan, mencegah serangan seperti SQL injection atau XSS.',
                        'options' => [
                            ['text' => 'Membersihkan data dari karakter berbahaya untuk mencegah serangan seperti SQL injection', 'correct' => true],
                            ['text' => 'Mempercepat proses parsing JSON', 'correct' => false],
                            ['text' => 'Mengubah data menjadi huruf kapital semua', 'correct' => false],
                            ['text' => 'Mengkompresi ukuran data yang dikirim', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Dengan middleware autentikasi yang dipasang di banyak endpoint, keuntungan utamanya adalah?',
                        'explanation' => 'Logic verifikasi token cukup ditulis sekali di middleware, lalu "ditempelkan" ke endpoint manapun yang butuh proteksi — tidak perlu copy-paste logic cek token di setiap endpoint.',
                        'options' => [
                            ['text' => 'Logic verifikasi token cukup ditulis sekali, dipakai ulang di banyak endpoint', 'correct' => true],
                            ['text' => 'Endpoint jadi tidak perlu autentikasi sama sekali', 'correct' => false],
                            ['text' => 'Database otomatis ter-backup', 'correct' => false],
                            ['text' => 'Semua endpoint jadi bisa diakses publik', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa data user yang sudah didecode dari token biasanya ditempelkan ke req.user oleh middleware?',
                        'explanation' => 'Menempelkan data user ke req.user memungkinkan handler berikutnya (controller) langsung mengakses identitas user yang sedang login, tanpa perlu decode token lagi.',
                        'options' => [
                            ['text' => 'Supaya handler berikutnya bisa langsung akses identitas user tanpa decode ulang', 'correct' => true],
                            ['text' => 'Supaya password user tersimpan permanen di memori', 'correct' => false],
                            ['text' => 'Karena req.user wajib diisi oleh Express secara otomatis', 'correct' => false],
                            ['text' => 'Tidak ada alasan khusus, hanya konvensi penamaan', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 3: Rate-Limited Login Endpoint' => [
                'title' => 'Quiz: Rate Limiting & Session vs Token',
                'questions' => [
                    [
                        'question' => 'Apa tujuan utama menerapkan rate limiting pada endpoint login?',
                        'explanation' => 'Rate limiting membatasi berapa kali satu IP/client boleh mencoba login dalam periode waktu tertentu, mencegah serangan brute force yang mencoba ribuan kombinasi password secara otomatis.',
                        'options' => [
                            ['text' => 'Mencegah serangan brute force dengan membatasi jumlah percobaan login', 'correct' => true],
                            ['text' => 'Mempercepat proses login yang berhasil', 'correct' => false],
                            ['text' => 'Mengurangi ukuran database', 'correct' => false],
                            ['text' => 'Membuat password otomatis lebih kuat', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Status code HTTP berapa yang umum dikembalikan ketika rate limit terlampaui?',
                        'explanation' => '429 Too Many Requests adalah status code standar untuk menandakan client sudah mengirim request melebihi batas yang diizinkan dalam periode tertentu.',
                        'options' => [
                            ['text' => '429', 'correct' => true],
                            ['text' => '404', 'correct' => false],
                            ['text' => '200', 'correct' => false],
                            ['text' => '302', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Perbedaan utama session-based authentication dengan token-based (JWT) authentication adalah?',
                        'explanation' => 'Session bersifat stateful — server menyimpan data session di database/memory. Token/JWT bersifat stateless — server tidak perlu menyimpan apapun, cukup verifikasi tanda tangan token.',
                        'options' => [
                            ['text' => 'Session bersifat stateful (server menyimpan data), token bersifat stateless', 'correct' => true],
                            ['text' => 'Session hanya bisa dipakai di mobile app, token hanya di web', 'correct' => false],
                            ['text' => 'Keduanya persis sama, hanya beda penamaan', 'correct' => false],
                            ['text' => 'Token tidak bisa kadaluarsa, session selalu kadaluarsa', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa JWT/token-based authentication dianggap lebih mudah di-scale ke banyak server dibanding session-based?',
                        'explanation' => 'Server manapun bisa memverifikasi JWT sendiri (cukup cek tanda tangannya) tanpa perlu "bertanya" ke server lain, sementara session butuh mekanisme tambahan untuk berbagi data session antar banyak server.',
                        'options' => [
                            ['text' => 'Server manapun bisa verifikasi token sendiri tanpa perlu data tambahan dari server lain', 'correct' => true],
                            ['text' => 'Token tidak bisa dipakai di lebih dari satu server', 'correct' => false],
                            ['text' => 'Session lebih ringan diproses dibanding token', 'correct' => false],
                            ['text' => 'Token wajib disimpan di database pusat', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Rate limiting pada endpoint login biasanya dihitung berdasarkan apa untuk mengenali satu "client"?',
                        'explanation' => 'IP address adalah cara paling umum untuk mengidentifikasi client saat menerapkan rate limiting, meski beberapa sistem juga mengombinasikannya dengan identitas akun yang dicoba.',
                        'options' => [
                            ['text' => 'IP address pengirim request', 'correct' => true],
                            ['text' => 'Ukuran layar perangkat pengirim', 'correct' => false],
                            ['text' => 'Jenis browser yang dipakai', 'correct' => false],
                            ['text' => 'Warna tema aplikasi yang dipakai', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 5: Git & Collaboration Workflow
            // ============================================================
            'Assignment 1: Team Branching Simulation' => [
                'title' => 'Quiz: Git Branching & Merge Conflict',
                'questions' => [
                    [
                        'question' => 'Kenapa developer membuat branch terpisah alih-alih langsung edit branch main?',
                        'explanation' => 'Branch terpisah memungkinkan kerja bebas tanpa mengganggu kode di main, sehingga fitur yang belum selesai tidak ikut mempengaruhi kode yang sudah stabil.',
                        'options' => [
                            ['text' => 'Supaya bisa kerja bebas tanpa mengganggu kode stabil di main', 'correct' => true],
                            ['text' => 'Karena Git mewajibkan minimal 2 branch per proyek', 'correct' => false],
                            ['text' => 'Supaya ukuran repository lebih kecil', 'correct' => false],
                            ['text' => 'Branch hanya bisa dibuat oleh satu orang dalam tim', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kapan merge conflict biasanya terjadi?',
                        'explanation' => 'Merge conflict terjadi ketika dua perubahan berbeda mengedit BARIS YANG SAMA di file yang sama, sehingga Git tidak tahu versi mana yang harus dipakai.',
                        'options' => [
                            ['text' => 'Ketika dua perubahan berbeda mengedit baris yang sama di file yang sama', 'correct' => true],
                            ['text' => 'Setiap kali melakukan commit', 'correct' => false],
                            ['text' => 'Hanya ketika bekerja sendirian tanpa tim', 'correct' => false],
                            ['text' => 'Hanya terjadi di branch main', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Setelah menyelesaikan merge conflict secara manual di sebuah file, langkah selanjutnya adalah?',
                        'explanation' => 'Setelah menghapus penanda konflik dan memutuskan versi final, file perlu di-add dan di-commit untuk menandai konflik sudah terselesaikan.',
                        'options' => [
                            ['text' => 'git add file tersebut, lalu commit', 'correct' => true],
                            ['text' => 'Menghapus branch yang bermasalah', 'correct' => false],
                            ['text' => 'Restart aplikasi', 'correct' => false],
                            ['text' => 'Tidak perlu langkah tambahan apapun', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Pola penamaan branch feature/nama-fitur atau fix/nama-bug berguna untuk apa?',
                        'explanation' => 'Konvensi penamaan membantu tim langsung tahu dari nama branch-nya, branch itu dibuat untuk mengerjakan apa, tanpa perlu bertanya.',
                        'options' => [
                            ['text' => 'Membantu tim langsung memahami tujuan branch dari namanya', 'correct' => true],
                            ['text' => 'Membuat Git berjalan lebih cepat', 'correct' => false],
                            ['text' => 'Wajib secara teknis, Git akan menolak nama branch lain', 'correct' => false],
                            ['text' => 'Hanya berlaku untuk proyek open source', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Perintah apa yang dipakai untuk membuat branch baru sekaligus langsung berpindah ke branch tersebut?',
                        'explanation' => 'git checkout -b nama-branch membuat branch baru dari posisi saat ini dan langsung berpindah ke branch itu dalam satu perintah.',
                        'options' => [
                            ['text' => 'git checkout -b nama-branch', 'correct' => true],
                            ['text' => 'git commit -b nama-branch', 'correct' => false],
                            ['text' => 'git branch --switch nama-branch', 'correct' => false],
                            ['text' => 'git init nama-branch', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Pull Request Practice Repo' => [
                'title' => 'Quiz: Pull Request & Code Review',
                'questions' => [
                    [
                        'question' => 'Apa tujuan utama membuat Pull Request (PR) sebelum menggabungkan perubahan ke branch main?',
                        'explanation' => 'PR memungkinkan perubahan direview oleh anggota tim lain sebelum digabungkan, membantu menangkap bug atau kesalahan desain lebih awal.',
                        'options' => [
                            ['text' => 'Supaya perubahan bisa direview tim lain sebelum digabungkan ke main', 'correct' => true],
                            ['text' => 'Supaya kode otomatis bebas dari bug', 'correct' => false],
                            ['text' => 'Karena Git mewajibkan PR untuk semua commit', 'correct' => false],
                            ['text' => 'Untuk menghapus riwayat commit lama', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kalau reviewer meminta perubahan pada sebuah PR, apa yang perlu dilakukan?',
                        'explanation' => 'Cukup melakukan commit perbaikan baru di branch yang sama dan push lagi — PR akan otomatis ter-update dengan commit baru tersebut tanpa perlu membuat PR baru.',
                        'options' => [
                            ['text' => 'Commit perbaikan di branch yang sama lalu push ulang, PR otomatis ter-update', 'correct' => true],
                            ['text' => 'Membuat Pull Request yang benar-benar baru dari awal', 'correct' => false],
                            ['text' => 'Menghapus PR lama tanpa memperbaiki apapun', 'correct' => false],
                            ['text' => 'Mengabaikan permintaan reviewer', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang sebaiknya ada di dalam deskripsi sebuah Pull Request yang baik?',
                        'explanation' => 'Deskripsi PR yang baik menjelaskan APA yang diubah dan KENAPA, membantu reviewer memahami konteks perubahan tanpa harus menebak-nebak.',
                        'options' => [
                            ['text' => 'Penjelasan apa yang diubah dan alasannya', 'correct' => true],
                            ['text' => 'Hanya judul singkat tanpa penjelasan apapun', 'correct' => false],
                            ['text' => 'Daftar seluruh commit history proyek', 'correct' => false],
                            ['text' => 'Password akun GitHub pembuat PR', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa kebiasaan membuat PR yang deskriptif dianggap penting untuk karir sebagai developer?',
                        'explanation' => 'Recruiter dan tech lead sering mengecek riwayat PR calon karyawan di GitHub untuk melihat cara kerja dan komunikasi mereka, bukan hanya kemampuan coding semata.',
                        'options' => [
                            ['text' => 'Recruiter/tech lead sering menilai cara kerja dan komunikasi lewat riwayat PR', 'correct' => true],
                            ['text' => 'PR yang panjang otomatis dianggap kode yang lebih baik', 'correct' => false],
                            ['text' => 'Hanya berpengaruh pada jumlah bintang repository', 'correct' => false],
                            ['text' => 'Tidak berpengaruh apapun terhadap penilaian kerja', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Setelah PR disetujui (approved) oleh reviewer, langkah selanjutnya adalah?',
                        'explanation' => 'Setelah approve, perubahan bisa di-merge ke branch main lewat platform seperti GitHub/GitLab, biasanya lewat tombol "Merge pull request".',
                        'options' => [
                            ['text' => 'Merge PR tersebut ke branch main', 'correct' => true],
                            ['text' => 'Menghapus seluruh branch feature tanpa merge', 'correct' => false],
                            ['text' => 'Membuat PR baru dengan isi yang sama', 'correct' => false],
                            ['text' => 'Menunggu 24 jam sebelum bisa merge', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 6: Testing & Debugging Backend
            // ============================================================
            'Assignment 1: Unit Test Suite for Utility Functions' => [
                'title' => 'Quiz: Unit Testing dengan Jest',
                'questions' => [
                    [
                        'question' => 'Kenapa automated testing dianggap lebih baik dibanding mengecek fitur secara manual setiap kali ada perubahan kode?',
                        'explanation' => 'Automated test bisa dijalankan ulang kapan saja secara konsisten dengan satu perintah, sementara pengecekan manual lama-lama jadi tidak praktis dan rawan ada yang terlewat begitu proyek membesar.',
                        'options' => [
                            ['text' => 'Bisa dijalankan ulang kapan saja secara konsisten, tidak rawan terlewat', 'correct' => true],
                            ['text' => 'Automated test tidak pernah menghasilkan kesalahan', 'correct' => false],
                            ['text' => 'Manual testing sebenarnya lebih cepat dari automated testing', 'correct' => false],
                            ['text' => 'Automated testing menggantikan kebutuhan menulis kode fitur', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Pola arrange-act-assert dalam penulisan test berarti apa?',
                        'explanation' => 'Arrange (siapkan data/kondisi), act (jalankan fungsi yang ditest), assert (bandingkan hasil dengan yang diharapkan) — pola standar dalam menulis test yang jelas dan terstruktur.',
                        'options' => [
                            ['text' => 'Siapkan kondisi, jalankan fungsi, lalu bandingkan hasilnya dengan ekspektasi', 'correct' => true],
                            ['text' => 'Menjalankan fungsi tiga kali berturut-turut', 'correct' => false],
                            ['text' => 'Menulis tiga jenis komentar di setiap test', 'correct' => false],
                            ['text' => 'Mengurutkan test berdasarkan abjad', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Function apa di Jest yang dipakai untuk membandingkan hasil aktual dengan hasil yang diharapkan?',
                        'explanation' => 'expect(hasilAktual).toBe(hasilDiharapkan) adalah pola dasar assertion di Jest — expect() dipasangkan dengan matcher seperti toBe().',
                        'options' => [
                            ['text' => 'expect()', 'correct' => true],
                            ['text' => 'assume()', 'correct' => false],
                            ['text' => 'verify()', 'correct' => false],
                            ['text' => 'compare()', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa penting menguji skenario "edge case" (misalnya input kosong atau angka negatif), bukan hanya skenario normal?',
                        'explanation' => 'Edge case sering jadi sumber bug yang tidak terduga di production — menguji skenario ini sejak awal membantu menangkap masalah sebelum sampai ke pengguna nyata.',
                        'options' => [
                            ['text' => 'Edge case sering jadi sumber bug tak terduga yang baru muncul di production', 'correct' => true],
                            ['text' => 'Edge case tidak pernah terjadi di aplikasi nyata', 'correct' => false],
                            ['text' => 'Jest mewajibkan minimal satu edge case per file test', 'correct' => false],
                            ['text' => 'Untuk membuat jumlah test terlihat lebih banyak', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kalau sebuah fungsi diubah dan salah satu test yang sebelumnya lolos jadi gagal, apa artinya?',
                        'explanation' => 'Ini menandakan perubahan kode kemungkinan merusak perilaku yang sebelumnya benar — test berfungsi sebagai "jaring pengaman" yang memberi tahu sebelum bug ini sampai ke pengguna.',
                        'options' => [
                            ['text' => 'Kemungkinan perubahan kode merusak perilaku yang sebelumnya benar', 'correct' => true],
                            ['text' => 'Test tersebut harus langsung dihapus', 'correct' => false],
                            ['text' => 'Jest mengalami bug internal', 'correct' => false],
                            ['text' => 'Tidak ada artinya, bisa diabaikan begitu saja', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: API Integration Test with Supertest' => [
                'title' => 'Quiz: Integration Testing & Logging',
                'questions' => [
                    [
                        'question' => 'Apa yang membedakan integration test (pakai Supertest) dengan unit test biasa?',
                        'explanation' => 'Integration test menguji beberapa bagian sekaligus bekerja sama dengan benar (routing + middleware + handler), sementara unit test menguji satu fungsi kecil secara terisolasi.',
                        'options' => [
                            ['text' => 'Integration test menguji beberapa bagian (routing, middleware, handler) bekerja sama', 'correct' => true],
                            ['text' => 'Integration test tidak memerlukan assertion sama sekali', 'correct' => false],
                            ['text' => 'Integration test hanya bisa dipakai untuk frontend', 'correct' => false],
                            ['text' => 'Tidak ada bedanya sama sekali dengan unit test', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Keuntungan Supertest dibanding harus menyalakan server sungguhan untuk testing endpoint adalah?',
                        'explanation' => 'Supertest bisa mensimulasikan request HTTP langsung ke aplikasi Express tanpa perlu menyalakan server di port tertentu, membuat test lebih cepat dan tidak bentrok port.',
                        'options' => [
                            ['text' => 'Bisa mensimulasikan request tanpa perlu menyalakan server sungguhan', 'correct' => true],
                            ['text' => 'Supertest hanya bisa dipakai di production', 'correct' => false],
                            ['text' => 'Supertest menggantikan kebutuhan database', 'correct' => false],
                            ['text' => 'Supertest otomatis memperbaiki bug yang ditemukan', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa logging dengan level (info, warn, error) lebih berguna dibanding console.log biasa di production?',
                        'explanation' => 'Level logging memungkinkan filter log yang penting saja saat ada masalah, tanpa harus mencari di ribuan baris log biasa yang tidak relevan.',
                        'options' => [
                            ['text' => 'Bisa memfilter log penting tanpa mencari di ribuan baris log biasa', 'correct' => true],
                            ['text' => 'console.log tidak bisa dipakai sama sekali di Node.js', 'correct' => false],
                            ['text' => 'Level logging membuat aplikasi berjalan lebih cepat', 'correct' => false],
                            ['text' => 'Tidak ada bedanya, hanya gaya penulisan berbeda', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Skenario apa yang penting untuk dites saat menguji endpoint POST yang menerima data dari client?',
                        'explanation' => 'Selain skenario berhasil, penting juga menguji skenario gagal seperti validasi data yang salah (misal harus mengembalikan status 400), memastikan endpoint menangani kondisi tidak ideal dengan benar.',
                        'options' => [
                            ['text' => 'Skenario validasi gagal (misal data tidak lengkap harus mengembalikan 400)', 'correct' => true],
                            ['text' => 'Hanya skenario berhasil, skenario gagal tidak perlu diuji', 'correct' => false],
                            ['text' => 'Kecepatan render halaman HTML', 'correct' => false],
                            ['text' => 'Warna teks pada response API', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa manfaat menyertakan konteks yang cukup pada log error (misalnya order id dan alasan gagal), bukan hanya pesan generic "terjadi error"?',
                        'explanation' => 'Konteks yang jelas membantu tim langsung memahami masalahnya tanpa perlu mereproduksi bug dari awal, mempercepat proses debugging di production.',
                        'options' => [
                            ['text' => 'Membantu tim memahami masalah tanpa perlu mereproduksi bug dari awal', 'correct' => true],
                            ['text' => 'Membuat file log menjadi lebih kecil ukurannya', 'correct' => false],
                            ['text' => 'Wajib secara teknis, log tanpa konteks akan ditolak sistem', 'correct' => false],
                            ['text' => 'Tidak ada manfaat praktis, hanya kebiasaan menulis', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 7: Server Architecture & Performance
            // ============================================================
            'Assignment 1: Refactor to Layered Architecture' => [
                'title' => 'Quiz: Arsitektur Layered',
                'questions' => [
                    [
                        'question' => 'Dalam arsitektur layered, lapisan mana yang seharusnya TIDAK bergantung pada req/res HTTP sama sekali?',
                        'explanation' => 'Service berisi business logic murni dan tidak boleh bergantung pada detail HTTP, sehingga bisa dites secara terisolasi dan dipakai ulang di luar konteks web request.',
                        'options' => [
                            ['text' => 'Services', 'correct' => true],
                            ['text' => 'Routes', 'correct' => false],
                            ['text' => 'Controllers', 'correct' => false],
                            ['text' => 'Middleware autentikasi', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa arsitektur layered (routes-controllers-services) memudahkan proses code review?',
                        'explanation' => 'Setiap lapisan punya tanggung jawab yang jelas, sehingga reviewer bisa fokus memeriksa lapisan yang relevan dengan perubahan tanpa harus membaca seluruh kode campur aduk.',
                        'options' => [
                            ['text' => 'Tanggung jawab tiap lapisan jelas, reviewer bisa fokus ke bagian relevan', 'correct' => true],
                            ['text' => 'Layered architecture menghilangkan kebutuhan review sama sekali', 'correct' => false],
                            ['text' => 'Kode otomatis bebas dari bug', 'correct' => false],
                            ['text' => 'Hanya berlaku untuk proyek kecil', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Setelah refactor ke arsitektur layered, cara yang tepat memastikan tidak ada yang rusak adalah?',
                        'explanation' => 'Fungsionalitas tetap berjalan sama seperti sebelum refactor adalah kriteria utama — bisa dipastikan lewat testing manual/otomatis pada endpoint yang sama.',
                        'options' => [
                            ['text' => 'Memastikan seluruh endpoint tetap berfungsi seperti sebelum refactor', 'correct' => true],
                            ['text' => 'Menghapus seluruh test lama karena strukturnya sudah berbeda', 'correct' => false],
                            ['text' => 'Mengasumsikan otomatis benar karena struktur lebih rapi', 'correct' => false],
                            ['text' => 'Tidak perlu verifikasi apapun', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang idealnya dilakukan controller setelah menerima request dan validasi dasar?',
                        'explanation' => 'Controller memanggil service yang sesuai untuk menjalankan business logic, lalu mengembalikan response berdasarkan hasil dari service tersebut.',
                        'options' => [
                            ['text' => 'Memanggil service yang sesuai, lalu mengembalikan response berdasarkan hasilnya', 'correct' => true],
                            ['text' => 'Langsung menjalankan query SQL mentah tanpa lewat service', 'correct' => false],
                            ['text' => 'Mengabaikan request dan tidak melakukan apapun', 'correct' => false],
                            ['text' => 'Menyimpan seluruh business logic di dalam controller itu sendiri', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Redis Caching Layer' => [
                'title' => 'Quiz: Caching dengan Redis',
                'questions' => [
                    [
                        'question' => 'Apa itu caching dalam konteks performa aplikasi?',
                        'explanation' => 'Caching menyimpan hasil dari proses yang butuh waktu (seperti query database) ke media yang lebih cepat diakses, sehingga request berikutnya tidak perlu mengulang proses yang sama.',
                        'options' => [
                            ['text' => 'Menyimpan hasil proses yang butuh waktu ke media yang lebih cepat diakses', 'correct' => true],
                            ['text' => 'Menghapus data lama dari database secara otomatis', 'correct' => false],
                            ['text' => 'Mengenkripsi data sebelum dikirim ke client', 'correct' => false],
                            ['text' => 'Mengubah format response menjadi XML', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang dimaksud dengan "cache hit" dan "cache miss"?',
                        'explanation' => 'Cache hit terjadi ketika data sudah ada di cache dan langsung dikembalikan; cache miss terjadi ketika data belum ada di cache, sehingga perlu diambil dari database dulu baru disimpan ke cache.',
                        'options' => [
                            ['text' => 'Cache hit: data ada di cache; cache miss: data belum ada, perlu ambil dari database', 'correct' => true],
                            ['text' => 'Cache hit berarti Redis sedang down', 'correct' => false],
                            ['text' => 'Cache miss berarti data hilang secara permanen', 'correct' => false],
                            ['text' => 'Keduanya adalah istilah untuk error database', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa Redis dipilih untuk caching, dibanding menyimpan cache di database biasa?',
                        'explanation' => 'Redis menyimpan data di memori (RAM), yang jauh lebih cepat diakses dibanding disk yang dipakai database biasa — cocok untuk data yang sering diakses berulang kali.',
                        'options' => [
                            ['text' => 'Redis menyimpan data di memori (RAM), jauh lebih cepat diakses dibanding disk', 'correct' => true],
                            ['text' => 'Redis adalah satu-satunya database yang mendukung SQL', 'correct' => false],
                            ['text' => 'Redis otomatis mengenkripsi seluruh data', 'correct' => false],
                            ['text' => 'Redis tidak membutuhkan konfigurasi apapun', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang perlu dilakukan terhadap cache ketika data sumber (di database) berubah?',
                        'explanation' => 'Cache perlu diperbarui atau dihapus (invalidasi) saat data sumbernya berubah, supaya pengguna tidak menerima data lama/basi yang sudah tidak sesuai kondisi terkini.',
                        'options' => [
                            ['text' => 'Cache perlu diperbarui atau dihapus (invalidasi) agar tidak menampilkan data basi', 'correct' => true],
                            ['text' => 'Cache tidak perlu disentuh sama sekali, akan otomatis update sendiri', 'correct' => false],
                            ['text' => 'Database perlu dihapus supaya cache tetap valid', 'correct' => false],
                            ['text' => 'Redis akan otomatis restart setiap ada perubahan data', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 3: Health Check & Monitoring Endpoint' => [
                'title' => 'Quiz: Health Check & API Versioning',
                'questions' => [
                    [
                        'question' => 'Apa fungsi utama endpoint /health pada sebuah aplikasi backend?',
                        'explanation' => 'Endpoint /health memverifikasi status aplikasi beserta koneksi ke komponen pentingnya (seperti database), biasanya dipanggil berkala oleh sistem monitoring atau load balancer.',
                        'options' => [
                            ['text' => 'Memverifikasi status aplikasi dan koneksi ke komponen penting seperti database', 'correct' => true],
                            ['text' => 'Menampilkan seluruh data pengguna ke publik', 'correct' => false],
                            ['text' => 'Menghapus cache aplikasi', 'correct' => false],
                            ['text' => 'Mengubah versi API secara otomatis', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa API versioning (misalnya /api/v1/, /api/v2/) penting saat API sudah dipakai banyak client berbeda?',
                        'explanation' => 'Versioning memungkinkan perubahan besar dilakukan tanpa mengganggu client yang masih memakai versi API lama, karena keduanya bisa berjalan berdampingan.',
                        'options' => [
                            ['text' => 'Memungkinkan perubahan besar tanpa mengganggu client yang masih pakai versi lama', 'correct' => true],
                            ['text' => 'Membuat API berjalan otomatis lebih cepat', 'correct' => false],
                            ['text' => 'Wajib secara teknis, API tidak bisa berjalan tanpa versioning', 'correct' => false],
                            ['text' => 'Hanya berpengaruh pada tampilan dokumentasi', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa format response API sebaiknya konsisten (misalnya selalu punya field success, data, message)?',
                        'explanation' => 'Format yang konsisten memudahkan client (frontend/aplikasi lain) memproses response tanpa perlu menangani banyak variasi struktur berbeda-beda untuk tiap endpoint.',
                        'options' => [
                            ['text' => 'Memudahkan client memproses response tanpa menangani banyak variasi struktur', 'correct' => true],
                            ['text' => 'Format response tidak berpengaruh pada client sama sekali', 'correct' => false],
                            ['text' => 'Hanya untuk estetika kode saja', 'correct' => false],
                            ['text' => 'Wajib diseragamkan oleh hukum internasional', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kalau health check gagal berturut-turut, apa yang biasanya dilakukan sistem monitoring/load balancer?',
                        'explanation' => 'Server yang gagal health check beberapa kali bisa dikeluarkan sementara dari jalur distribusi traffic, dan tim engineering diberi notifikasi untuk segera menindaklanjuti.',
                        'options' => [
                            ['text' => 'Server dikeluarkan sementara dari jalur traffic dan tim diberi notifikasi', 'correct' => true],
                            ['text' => 'Aplikasi otomatis di-uninstall dari server', 'correct' => false],
                            ['text' => 'Tidak ada tindakan apapun yang diambil', 'correct' => false],
                            ['text' => 'Database otomatis dihapus', 'correct' => false],
                        ],
                    ],
                ],
            ],
        ];
    }
}