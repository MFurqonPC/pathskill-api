<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

/**
 * Quiz untuk seluruh assignment career "Full Stack Developer"
 * (Modul 1-5: Frontend Fundamentals, Modern JavaScript & ES6+,
 * React Essentials, TypeScript for React, Advanced React Patterns).
 *
 * Jalankan setelah LearningPathSeeder (assignment-nya harus sudah ada).
 * Idempotent: quiz di-updateOrCreate per assignment, dan soal lama
 * dihapus dulu sebelum di-recreate, jadi aman dijalankan berkali-kali.
 */
class QuizFullStackSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->quizData() as $assignmentTitle => $data) {
            $assignment = Assignment::where('title', $assignmentTitle)->first();

            if (! $assignment) {
                $this->command?->warn("QuizFullStackSeeder: assignment tidak ditemukan — {$assignmentTitle}");
                continue;
            }

            $quiz = Quiz::updateOrCreate(
                ['assignment_id' => $assignment->id],
                ['title' => $data['title']]
            );

            // hapus soal lama kalau seeder dijalankan ulang, biar tidak dobel
            $quiz->questions()->delete();

            foreach ($data['questions'] as $index => $q) {
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

    private function quizData(): array
    {
        return [

            // ================================================
            // Assignment 1: Personal Portfolio Page
            // ================================================
            'Assignment 1: Personal Portfolio Page' => [
                'title' => 'Quiz: HTML Semantik & Layout Responsif',
                'questions' => [
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
                ],
            ],

            // ================================================
            // Assignment 2: Responsive Landing Page
            // ================================================
            'Assignment 2: Responsive Landing Page' => [
                'title' => 'Quiz: Box Model & Layout Multi-Section',
                'questions' => [
                    [
                        'question' => 'Bagian mana dari CSS Box Model yang mengatur jarak ANTARA batas elemen (border) dengan elemen lain di sekitarnya?',
                        'explanation' => 'Margin adalah ruang di LUAR border yang memisahkan satu elemen dari elemen tetangganya, berbeda dengan padding yang ada di DALAM border.',
                        'options' => [
                            ['text' => 'Margin', 'correct' => true],
                            ['text' => 'Padding', 'correct' => false],
                            ['text' => 'Content', 'correct' => false],
                            ['text' => 'Outline', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa efek dari properti box-sizing: border-box pada sebuah elemen?',
                        'explanation' => 'Dengan border-box, width dan height yang ditentukan sudah mencakup padding dan border, sehingga ukuran elemen tidak membengkak melebihi nilai width yang diset — memudahkan perhitungan layout multi-section.',
                        'options' => [
                            ['text' => 'Padding dan border dihitung di dalam width/height yang ditentukan', 'correct' => true],
                            ['text' => 'Margin dihitung di dalam width/height yang ditentukan', 'correct' => false],
                            ['text' => 'Elemen jadi tidak bisa diberi border', 'correct' => false],
                            ['text' => 'Width otomatis menyesuaikan konten, mengabaikan nilai yang diset', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Properti Flexbox mana yang digunakan untuk mengatur posisi item secara HORIZONTAL di dalam flex container (row)?',
                        'explanation' => 'justify-content mengatur distribusi item sepanjang sumbu utama (main axis) — pada flex-direction: row, ini berarti horizontal, misalnya space-between untuk menyebar navbar logo dan menu.',
                        'options' => [
                            ['text' => 'justify-content', 'correct' => true],
                            ['text' => 'align-items', 'correct' => false],
                            ['text' => 'align-content', 'correct' => false],
                            ['text' => 'flex-basis', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa gambar pada landing page responsif sebaiknya diberi style max-width: 100%?',
                        'explanation' => 'max-width: 100% membuat gambar menyusut mengikuti lebar container-nya di layar sempit, tapi tidak akan membesar melebihi ukuran asli gambar di layar lebar — mencegah gambar overflow keluar dari container di HP.',
                        'options' => [
                            ['text' => 'Supaya gambar tidak melebihi lebar container di layar kecil', 'correct' => true],
                            ['text' => 'Supaya gambar selalu tampil dalam ukuran penuh, seberapa pun lebar layarnya', 'correct' => false],
                            ['text' => 'Supaya gambar otomatis menjadi grayscale', 'correct' => false],
                            ['text' => 'Supaya gambar tidak bisa diklik', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Properti CSS apa yang digunakan untuk mengatur jarak antar item di dalam CSS Grid, tanpa perlu menambah margin manual di tiap item?',
                        'explanation' => 'gap (atau grid-gap pada spesifikasi lama) mengatur jarak seragam antar baris dan kolom grid sekaligus, lebih rapi dibanding mengatur margin satu-satu di tiap item grid.',
                        'options' => [
                            ['text' => 'gap', 'correct' => true],
                            ['text' => 'spacing', 'correct' => false],
                            ['text' => 'grid-margin', 'correct' => false],
                            ['text' => 'item-space', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Pada flexbox, properti flex-wrap: wrap digunakan untuk apa?',
                        'explanation' => 'flex-wrap: wrap membuat item flex yang tidak muat dalam satu baris otomatis "turun" ke baris berikutnya, alih-alih dipaksa menyempit atau overflow keluar container — penting untuk section fitur yang berisi banyak kartu.',
                        'options' => [
                            ['text' => 'Membuat item pindah ke baris baru jika tidak muat dalam satu baris', 'correct' => true],
                            ['text' => 'Membuat semua item selalu berada dalam satu baris, seberapa pun sempit layarnya', 'correct' => false],
                            ['text' => 'Membungkus teks di dalam item dengan garis bawah', 'correct' => false],
                            ['text' => 'Menghapus jarak antar item flex', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Elemen HTML semantik apa yang paling tepat untuk membungkus SATU bagian/topik pada landing page, misalnya bagian "Fitur" atau "Testimoni"?',
                        'explanation' => '<section> digunakan untuk mengelompokkan konten yang punya tema/topik tersendiri dalam sebuah halaman, seperti bagian Fitur, Testimoni, atau Harga pada landing page multi-section.',
                        'options' => [
                            ['text' => '<section>', 'correct' => true],
                            ['text' => '<div>', 'correct' => false],
                            ['text' => '<span>', 'correct' => false],
                            ['text' => '<nav>', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa breakpoint media query sebaiknya ditentukan berdasarkan titik di mana LAYOUT mulai terlihat rusak, bukan berdasarkan ukuran perangkat tertentu (misal "lebar iPhone 12")?',
                        'explanation' => 'Jumlah dan ukuran perangkat di pasar sangat beragam dan terus bertambah, sehingga breakpoint yang di-hardcode untuk satu device tertentu akan cepat usang. Breakpoint yang didasarkan pada titik layout mulai rusak (content-based) lebih tahan lama dan fleksibel.',
                        'options' => [
                            ['text' => 'Karena ukuran perangkat di pasar sangat beragam dan terus berubah', 'correct' => true],
                            ['text' => 'Karena CSS tidak mendukung breakpoint berdasarkan nama device', 'correct' => false],
                            ['text' => 'Karena breakpoint berdasarkan device selalu lebih lambat diproses browser', 'correct' => false],
                            ['text' => 'Karena breakpoint berdasarkan device hanya berlaku untuk Safari', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ================================================
            // Assignment 3: Interactive To-Do List
            // ================================================
            'Assignment 3: Interactive To-Do List' => [
                'title' => 'Quiz: JavaScript Dasar & DOM Manipulation',
                'questions' => [
                    [
                        'question' => 'Kata kunci mana yang tepat digunakan untuk mendeklarasikan variabel penghitung jumlah todo yang NILAINYA akan berubah-ubah seiring waktu?',
                        'explanation' => 'let digunakan untuk variabel yang nilainya akan diubah/reassign, sedangkan const untuk nilai yang tetap sejak awal dideklarasikan — jumlah todo yang terus bertambah/berkurang cocok pakai let.',
                        'options' => [
                            ['text' => 'let', 'correct' => true],
                            ['text' => 'const', 'correct' => false],
                            ['text' => 'var', 'correct' => false],
                            ['text' => 'final', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Method mana yang digunakan untuk mencari SATU elemen HTML pertama yang cocok dengan selector CSS tertentu?',
                        'explanation' => 'document.querySelector() mengembalikan elemen pertama yang cocok dengan selector CSS yang diberikan (misal "#daftarTodo" atau ".todo-item") — kalau butuh SEMUA elemen yang cocok, dipakai querySelectorAll().',
                        'options' => [
                            ['text' => 'document.querySelector()', 'correct' => true],
                            ['text' => 'document.createElement()', 'correct' => false],
                            ['text' => 'document.append()', 'correct' => false],
                            ['text' => 'document.getStyle()', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Fungsi apa yang digunakan untuk menjalankan sebuah function setiap kali tombol "Tambah Todo" diklik pengguna?',
                        'explanation' => 'addEventListener("click", fungsi) mendaftarkan fungsi yang akan dijalankan setiap kali event click terjadi pada elemen tersebut, tanpa perlu reload halaman.',
                        'options' => [
                            ['text' => 'tombol.addEventListener("click", fungsi)', 'correct' => true],
                            ['text' => 'tombol.onLoad(fungsi)', 'correct' => false],
                            ['text' => 'tombol.runClick(fungsi)', 'correct' => false],
                            ['text' => 'tombol.watch("click", fungsi)', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Setelah membuat elemen baru dengan document.createElement("li"), langkah apa yang WAJIB dilakukan agar elemen itu benar-benar muncul di halaman?',
                        'explanation' => 'createElement() hanya membuat elemen di memori, belum ditampilkan di halaman. Elemen itu harus ditambahkan ke DOM menggunakan method seperti appendChild() atau append() pada elemen induk yang sudah ada di halaman.',
                        'options' => [
                            ['text' => 'Menambahkannya ke DOM menggunakan appendChild() atau append()', 'correct' => true],
                            ['text' => 'Memberi nama variabel pada elemen tersebut', 'correct' => false],
                            ['text' => 'Menjalankan console.log() pada elemen tersebut', 'correct' => false],
                            ['text' => 'Tidak perlu langkah tambahan, otomatis muncul begitu dibuat', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Struktur kondisional mana yang tepat digunakan untuk menampilkan pesan berbeda tergantung apakah input judul todo kosong atau tidak?',
                        'explanation' => 'if...else memungkinkan program mengambil salah satu dari dua jalur eksekusi (kosong vs tidak kosong) berdasarkan kondisi tertentu — pas untuk validasi input sebelum todo ditambahkan.',
                        'options' => [
                            ['text' => 'if...else', 'correct' => true],
                            ['text' => 'for...of', 'correct' => false],
                            ['text' => 'try...catch', 'correct' => false],
                            ['text' => 'switch...break saja tanpa case', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa fungsi dari event.preventDefault() ketika dipanggil di dalam handler submit sebuah <form> tambah todo?',
                        'explanation' => 'preventDefault() mencegah perilaku bawaan browser (yaitu reload halaman) saat form di-submit, sehingga penambahan todo bisa ditangani sepenuhnya lewat JavaScript tanpa kehilangan state halaman.',
                        'options' => [
                            ['text' => 'Mencegah halaman reload otomatis saat form disubmit', 'correct' => true],
                            ['text' => 'Menghapus semua todo yang sudah ada', 'correct' => false],
                            ['text' => 'Membatalkan event click pada tombol lain', 'correct' => false],
                            ['text' => 'Mempercepat proses render ulang halaman', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Method array mana yang tepat digunakan untuk MENGHAPUS satu todo dari array berdasarkan posisi index-nya?',
                        'explanation' => 'splice(index, 1) menghapus 1 elemen pada posisi index tertentu dari array, cocok untuk menghapus satu todo spesifik tanpa mengubah urutan todo lain yang tersisa.',
                        'options' => [
                            ['text' => 'array.splice(index, 1)', 'correct' => true],
                            ['text' => 'array.push(index)', 'correct' => false],
                            ['text' => 'array.concat(index)', 'correct' => false],
                            ['text' => 'array.join(index)', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Cara penulisan string mana yang memungkinkan kamu menyisipkan variabel langsung di dalam teks, misalnya `Todo: ${namaTodo}`, tanpa perlu operator penggabungan (+)?',
                        'explanation' => 'Template literal (ditulis dengan backtick `...`) mendukung interpolasi variabel langsung menggunakan sintaks ${namaVariabel}, lebih ringkas dibanding menggabungkan string dengan operator + berulang-ulang.',
                        'options' => [
                            ['text' => 'Template literal: `Todo: ${namaTodo}`', 'correct' => true],
                            ['text' => 'String biasa: "Todo: " + namaTodo + "!"', 'correct' => false],
                            ['text' => 'Regex: /Todo: namaTodo/', 'correct' => false],
                            ['text' => 'JSON.stringify(namaTodo)', 'correct' => false],
                        ],
                    ],
                ],
            ],
 
    // ================================================================
    // ====================== MODUL 2: Modern JavaScript & ES6+ ========
    // ================================================================
 
    'Assignment 1: Async Weather App' => [
        'title' => 'Quiz: Fetch API, Async/Await & Error Handling',
        'questions' => [
            [
                'question' => 'Sebuah function yang dideklarasikan dengan kata kunci async SELALU mengembalikan apa, apa pun isi return-nya?',
                'explanation' => 'Function async selalu membungkus nilai kembaliannya ke dalam Promise, meskipun kamu menulis return angka atau string biasa — itu sebabnya hasilnya bisa di-await atau di-.then() oleh pemanggilnya.',
                'options' => [
                    ['text' => 'Promise', 'correct' => true],
                    ['text' => 'undefined', 'correct' => false],
                    ['text' => 'Callback', 'correct' => false],
                    ['text' => 'String JSON', 'correct' => false],
                ],
            ],
            [
                'question' => 'Struktur mana yang tepat untuk menangkap error saat fetch() gagal (misalnya tidak ada koneksi internet) di dalam async function?',
                'explanation' => 'try...catch membungkus kode yang memakai await, sehingga kalau Promise dari fetch() reject (gagal), eksekusi langsung lompat ke blok catch tanpa membuat aplikasi crash.',
                'options' => [
                    ['text' => 'try { await fetch(...) } catch (err) { ... }', 'correct' => true],
                    ['text' => 'if (fetch(...)) { ... } else { ... }', 'correct' => false],
                    ['text' => 'switch (fetch(...)) { ... }', 'correct' => false],
                    ['text' => 'for (fetch(...)) { ... }', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kata kunci await hanya boleh digunakan langsung di dalam?',
                'explanation' => 'await menghentikan eksekusi sejenak sampai Promise selesai, dan ini hanya valid di dalam function yang ditandai async (atau di top-level module modern) — memakainya di function biasa akan menyebabkan SyntaxError.',
                'options' => [
                    ['text' => 'async function', 'correct' => true],
                    ['text' => 'function biasa apa saja', 'correct' => false],
                    ['text' => 'arrow function tanpa async', 'correct' => false],
                    ['text' => 'Blok if saja', 'correct' => false],
                ],
            ],
            [
                'question' => 'Setelah fetch() berhasil terkirim, properti apa pada objek response yang perlu dicek untuk memastikan server benar-benar merespons sukses (bukan error 404/500)?',
                'explanation' => 'fetch() tidak otomatis melempar error untuk status HTTP seperti 404 atau 500 — response.ok bernilai false untuk status di luar rentang 200-299, jadi harus dicek manual sebelum melanjutkan proses data.',
                'options' => [
                    ['text' => 'response.ok', 'correct' => true],
                    ['text' => 'response.body', 'correct' => false],
                    ['text' => 'response.type', 'correct' => false],
                    ['text' => 'response.url', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kenapa hasil fetch() perlu dipanggil lagi dengan response.json() sebelum datanya bisa dipakai sebagai object JavaScript?',
                'explanation' => 'response.json() adalah method asynchronous yang membaca body response (masih berbentuk stream/teks mentah) dan mengubahnya menjadi object/array JavaScript yang bisa langsung diakses propertinya.',
                'options' => [
                    ['text' => 'Karena body response awalnya masih berupa stream, belum jadi object JS', 'correct' => true],
                    ['text' => 'Karena fetch() tidak bisa mengambil data dari API cuaca', 'correct' => false],
                    ['text' => 'Karena response.json() berfungsi mengirim ulang request ke server', 'correct' => false],
                    ['text' => 'Karena tanpa itu, browser akan menolak menampilkan hasilnya', 'correct' => false],
                ],
            ],
            [
                'question' => 'Apa yang idealnya ditampilkan ke pengguna SELAMA proses fetch data cuaca sedang berlangsung (sebelum data datang atau error muncul)?',
                'explanation' => 'Memberi indikator loading (misalnya teks "Memuat..." atau spinner) penting supaya pengguna tahu aplikasi sedang bekerja, bukan macet — ini bagian dari pengalaman pengguna yang baik saat menangani operasi asynchronous.',
                'options' => [
                    ['text' => 'Indikator loading/status sedang memuat', 'correct' => true],
                    ['text' => 'Halaman dibiarkan kosong tanpa keterangan apa pun', 'correct' => false],
                    ['text' => 'Pesan error langsung, sebelum request selesai', 'correct' => false],
                    ['text' => 'Data cuaca kota lain sebagai pengganti sementara', 'correct' => false],
                ],
            ],
        ],
    ],
 
    'Assignment 2: API Data Fetcher' => [
        'title' => 'Quiz: Array Methods & ES Modules',
        'questions' => [
            [
                'question' => 'Method array mana yang tepat digunakan untuk MENGUBAH bentuk tiap item dalam array hasil fetch, misalnya mengambil hanya field tertentu dari tiap object?',
                'explanation' => 'map() menghasilkan array baru dengan jumlah elemen yang sama, di mana tiap elemen sudah ditransformasi sesuai function yang diberikan — cocok untuk mengekstrak/mengubah bentuk data API.',
                'options' => [
                    ['text' => 'array.map()', 'correct' => true],
                    ['text' => 'array.forEach()', 'correct' => false],
                    ['text' => 'array.push()', 'correct' => false],
                    ['text' => 'array.sort()', 'correct' => false],
                ],
            ],
            [
                'question' => 'Method array mana yang dipakai untuk MENYARING data, misalnya hanya menampilkan produk dengan stok lebih dari 0?',
                'explanation' => 'filter() mengembalikan array baru berisi hanya elemen yang lolos kondisi (callback mengembalikan true), berguna untuk menyaring hasil fetch sebelum ditampilkan.',
                'options' => [
                    ['text' => 'array.filter()', 'correct' => true],
                    ['text' => 'array.map()', 'correct' => false],
                    ['text' => 'array.find()', 'correct' => false],
                    ['text' => 'array.includes()', 'correct' => false],
                ],
            ],
            [
                'question' => 'Method array mana yang paling tepat untuk menjumlahkan total harga dari seluruh item dalam array hasil fetch menjadi satu angka?',
                'explanation' => 'reduce() "meringkas" seluruh elemen array menjadi satu nilai akumulasi (misalnya total harga), berbeda dengan map/filter yang selalu mengembalikan array.',
                'options' => [
                    ['text' => 'array.reduce()', 'correct' => true],
                    ['text' => 'array.map()', 'correct' => false],
                    ['text' => 'array.slice()', 'correct' => false],
                    ['text' => 'array.concat()', 'correct' => false],
                ],
            ],
            [
                'question' => 'Sintaks mana yang benar untuk meng-export sebuah function bernama fetchData sebagai named export dari sebuah module ES?',
                'explanation' => 'export function fetchData() {...} atau export { fetchData } adalah cara membuat named export, yang harus di-import menggunakan nama yang sama persis (di dalam kurung kurawal) pada file lain.',
                'options' => [
                    ['text' => 'export function fetchData() { ... }', 'correct' => true],
                    ['text' => 'module.exports.fetchData = function', 'correct' => false],
                    ['text' => 'return function fetchData()', 'correct' => false],
                    ['text' => 'public function fetchData()', 'correct' => false],
                ],
            ],
            [
                'question' => 'Bagaimana cara yang benar untuk meng-import default export bernama "App" dari file App.js ke dalam file lain?',
                'explanation' => 'Default export di-import TANPA kurung kurawal dan boleh diberi nama apa saja saat import, berbeda dengan named export yang harus memakai nama identik di dalam kurung kurawal.',
                'options' => [
                    ['text' => "import App from './App.js'", 'correct' => true],
                    ['text' => "import { App } from './App.js'", 'correct' => false],
                    ['text' => "require('App.js').default()", 'correct' => false],
                    ['text' => "include './App.js' as App", 'correct' => false],
                ],
            ],
            [
                'question' => 'Kalau sebuah array hasil fetch API perlu diolah dengan filter() lalu dilanjutkan map(), urutan yang lebih efisien umumnya adalah?',
                'explanation' => 'Menyaring (filter) dulu sebelum mentransformasi (map) berarti operasi map hanya dijalankan pada data yang benar-benar relevan, sehingga sedikit lebih efisien dibanding map dulu baru filter belakangan pada data yang jumlahnya sama besar.',
                'options' => [
                    ['text' => 'filter() dulu, baru map()', 'correct' => true],
                    ['text' => 'map() dulu, baru filter(), urutan tidak berpengaruh sama sekali', 'correct' => false],
                    ['text' => 'Harus pakai forEach() sebelum keduanya', 'correct' => false],
                    ['text' => 'Harus dipanggil di dalam try...catch supaya urutan benar', 'correct' => false],
                ],
            ],
        ],
    ],
 
    'Assignment 3: Module Refactor Exercise' => [
        'title' => 'Quiz: ES Modules, Arrow Function & Destructuring',
        'questions' => [
            [
                'question' => 'Apa keuntungan utama memecah kode JavaScript monolitik menjadi beberapa module terpisah (import/export)?',
                'explanation' => 'Modularisasi membuat tiap file punya tanggung jawab yang jelas dan lebih mudah ditelusuri, diuji, serta dipakai ulang (reusable) di bagian lain proyek, dibanding satu file raksasa yang mencampur semua logic.',
                'options' => [
                    ['text' => 'Kode lebih terorganisir, mudah ditelusuri, dan bisa dipakai ulang', 'correct' => true],
                    ['text' => 'Kode otomatis berjalan lebih cepat di semua browser', 'correct' => false],
                    ['text' => 'Tidak perlu lagi menulis function sama sekali', 'correct' => false],
                    ['text' => 'File JavaScript jadi wajib berekstensi .mjs', 'correct' => false],
                ],
            ],
            [
                'question' => 'Perbedaan utama arrow function dengan function biasa terkait nilai this di dalamnya adalah?',
                'explanation' => 'Arrow function tidak memiliki this miliknya sendiri — this di dalam arrow function mengikuti this dari lingkup (scope) di luarnya, berbeda dengan function biasa yang this-nya bergantung pada bagaimana ia dipanggil.',
                'options' => [
                    ['text' => 'Arrow function mewarisi this dari scope sekitarnya, tidak membuat this sendiri', 'correct' => true],
                    ['text' => 'Arrow function selalu membuat this baru yang menunjuk ke window', 'correct' => false],
                    ['text' => 'Arrow function tidak bisa menerima parameter sama sekali', 'correct' => false],
                    ['text' => 'Tidak ada perbedaan apa pun antara keduanya', 'correct' => false],
                ],
            ],
            [
                'question' => 'Sintaks destructuring mana yang benar untuk mengambil properti nama dan umur langsung dari sebuah object user?',
                'explanation' => 'Object destructuring const { nama, umur } = user langsung membuat dua variabel baru (nama dan umur) dari properti object user dengan nama yang sama persis, tanpa perlu menulis user.nama dan user.umur berulang.',
                'options' => [
                    ['text' => 'const { nama, umur } = user;', 'correct' => true],
                    ['text' => 'const [nama, umur] = user;', 'correct' => false],
                    ['text' => 'const nama, umur = user;', 'correct' => false],
                    ['text' => 'const user.nama, user.umur;', 'correct' => false],
                ],
            ],
            [
                'question' => 'Ketika sebuah module hanya punya SATU hal utama untuk di-export (misalnya satu function utilitas), jenis export apa yang biasanya lebih sesuai?',
                'explanation' => 'Default export cocok dipakai saat sebuah module memang berpusat pada satu "hal utama" — pemakainya bebas menamai ulang saat import karena hanya ada satu default per file.',
                'options' => [
                    ['text' => 'Default export', 'correct' => true],
                    ['text' => 'Named export sebanyak-banyaknya sekaligus', 'correct' => false],
                    ['text' => 'Export sebagai variabel global window', 'correct' => false],
                    ['text' => 'Tidak perlu export apa pun', 'correct' => false],
                ],
            ],
            [
                'question' => 'Setelah merefactor kode monolitik menjadi beberapa module, cara paling andal untuk memastikan refactor tidak merusak fungsionalitas adalah?',
                'explanation' => 'Refactoring seharusnya hanya mengubah STRUKTUR kode, bukan perilakunya — cara memastikannya adalah menjalankan ulang aplikasi/test dan membandingkan hasilnya persis sama seperti sebelum direfactor.',
                'options' => [
                    ['text' => 'Menjalankan ulang aplikasi/test dan membandingkan hasilnya dengan sebelum refactor', 'correct' => true],
                    ['text' => 'Menghapus kode lama sebelum sempat mencobanya', 'correct' => false],
                    ['text' => 'Mengasumsikan pasti benar karena strukturnya lebih rapi', 'correct' => false],
                    ['text' => 'Mengganti seluruh nama variabel secara acak', 'correct' => false],
                ],
            ],
            [
                'question' => 'Sintaks rest parameter (...args) pada sebuah function digunakan untuk apa?',
                'explanation' => 'Rest parameter mengumpulkan sisa argumen yang dikirim ke function menjadi satu array, berguna saat jumlah argumen tidak diketahui pasti sebelumnya — berbeda dengan spread yang justru "membentangkan" array/object.',
                'options' => [
                    ['text' => 'Mengumpulkan sejumlah argumen yang tidak ditentukan jumlahnya menjadi satu array', 'correct' => true],
                    ['text' => 'Menghapus argumen terakhir dari sebuah function', 'correct' => false],
                    ['text' => 'Mengubah function menjadi asynchronous', 'correct' => false],
                    ['text' => 'Membatasi function hanya menerima 1 argumen', 'correct' => false],
                ],
            ],
        ],
    ],
 
    'Assignment 4: Quiz: ES6+ Concepts' => [
        'title' => 'Quiz: Rangkuman ES6+ & Asynchronous JavaScript',
        'questions' => [
            [
                'question' => 'Operator spread (...) pada array [...arr1, ...arr2] digunakan untuk apa?',
                'explanation' => 'Spread operator "membentangkan" isi sebuah array atau object, sering dipakai untuk menggabungkan dua array/object baru tanpa mengubah array/object aslinya (immutability).',
                'options' => [
                    ['text' => 'Menggabungkan isi beberapa array/object menjadi satu array/object baru', 'correct' => true],
                    ['text' => 'Menghapus seluruh isi array', 'correct' => false],
                    ['text' => 'Mengubah array menjadi string', 'correct' => false],
                    ['text' => 'Membuat array menjadi read-only permanen', 'correct' => false],
                ],
            ],
            [
                'question' => 'Sebuah Promise memiliki tiga kemungkinan state. Manakah yang BUKAN termasuk state Promise?',
                'explanation' => 'State resmi Promise adalah pending (masih berjalan), fulfilled (berhasil), dan rejected (gagal) — "completed" bukan istilah state resmi dalam spesifikasi Promise.',
                'options' => [
                    ['text' => 'completed', 'correct' => true],
                    ['text' => 'pending', 'correct' => false],
                    ['text' => 'fulfilled', 'correct' => false],
                    ['text' => 'rejected', 'correct' => false],
                ],
            ],
            [
                'question' => 'Method Promise.all() berguna untuk skenario apa?',
                'explanation' => 'Promise.all() menjalankan beberapa Promise secara paralel dan baru "selesai" setelah SEMUA Promise berhasil — cocok kalau kamu perlu menunggu beberapa fetch API sekaligus sebelum melanjutkan.',
                'options' => [
                    ['text' => 'Menjalankan beberapa Promise sekaligus dan menunggu semuanya selesai', 'correct' => true],
                    ['text' => 'Membatalkan semua Promise yang sedang berjalan', 'correct' => false],
                    ['text' => 'Mengubah Promise menjadi callback biasa', 'correct' => false],
                    ['text' => 'Menjalankan Promise satu per satu secara berurutan saja', 'correct' => false],
                ],
            ],
            [
                'question' => 'Template literal (backtick `...`) memberikan kemampuan apa yang tidak dimiliki string biasa (\'...\' atau "...")?',
                'explanation' => 'Template literal mendukung interpolasi variabel langsung dengan ${...} serta penulisan string multi-baris tanpa perlu karakter escape \\n, membuat penyusunan string dinamis lebih ringkas.',
                'options' => [
                    ['text' => 'Interpolasi variabel dengan ${...} dan string multi-baris', 'correct' => true],
                    ['text' => 'Otomatis meng-encode string ke Base64', 'correct' => false],
                    ['text' => 'Membuat string menjadi tidak bisa diubah (immutable)', 'correct' => false],
                    ['text' => 'Mengonversi string menjadi angka secara otomatis', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kenapa async/await sering dianggap lebih mudah dibaca dibanding .then().then().then() berantai untuk banyak operasi asynchronous berurutan?',
                'explanation' => 'async/await memungkinkan kode asynchronous ditulis dan dibaca hampir seperti kode synchronous baris-demi-baris, sehingga menghindari "callback pyramid" atau rantai .then() yang panjang dan sulit ditelusuri.',
                'options' => [
                    ['text' => 'Kode terbaca seperti alur synchronous biasa, menghindari rantai .then() yang panjang', 'correct' => true],
                    ['text' => 'async/await berjalan lebih cepat secara teknis dibanding Promise', 'correct' => false],
                    ['text' => '.then() sudah dihapus total dari JavaScript modern', 'correct' => false],
                    ['text' => 'async/await tidak memerlukan penanganan error sama sekali', 'correct' => false],
                ],
            ],
            [
                'question' => 'Default parameter pada function, misalnya function sapa(nama = "Tamu") { ... }, berfungsi untuk apa?',
                'explanation' => 'Default parameter memberi nilai fallback otomatis ketika argumen untuk parameter tersebut tidak dikirim sama sekali (undefined) saat function dipanggil, mengurangi kebutuhan pengecekan manual di dalam function.',
                'options' => [
                    ['text' => 'Memberi nilai bawaan jika argumen tidak dikirim saat pemanggilan function', 'correct' => true],
                    ['text' => 'Membuat parameter menjadi wajib diisi', 'correct' => false],
                    ['text' => 'Mengunci tipe data parameter menjadi string', 'correct' => false],
                    ['text' => 'Menjalankan function secara otomatis tanpa dipanggil', 'correct' => false],
                ],
            ],
            [
                'question' => 'Bagaimana cara yang tepat menangani sebuah Promise yang gagal (reject) ketika memakai gaya .then() (bukan async/await)?',
                'explanation' => 'Method .catch() dirangkai setelah .then() untuk menangkap error/rejection dari Promise sebelumnya, setara secara fungsional dengan blok catch pada try...catch versi async/await.',
                'options' => [
                    ['text' => 'Merangkai .catch() setelah .then()', 'correct' => true],
                    ['text' => 'Menambahkan blok if (promise === false)', 'correct' => false],
                    ['text' => 'Membungkusnya dengan JSON.stringify()', 'correct' => false],
                    ['text' => 'Promise gagal tidak perlu ditangani sama sekali', 'correct' => false],
                ],
            ],
            [
                'question' => 'Named export dan default export dari sebuah module ES bisa digunakan sekaligus dalam satu file yang sama — pernyataan ini?',
                'explanation' => 'Sebuah module ES boleh punya SATU default export dan SEBANYAK mungkin named export sekaligus dalam file yang sama, dan pemakainya bisa meng-import keduanya dalam satu pernyataan import.',
                'options' => [
                    ['text' => 'Benar, satu module boleh punya satu default export dan beberapa named export', 'correct' => true],
                    ['text' => 'Salah, satu module hanya boleh punya satu jenis export', 'correct' => false],
                    ['text' => 'Salah, named export harus di file terpisah dari default export', 'correct' => false],
                    ['text' => 'Benar, tapi hanya berlaku di Node.js, bukan di browser', 'correct' => false],
                ],
            ],
        ],
    ],
 
    // ================================================================
    // ========================= MODUL 3: React Essentials =============
    // ================================================================
 
    'Assignment 1: Todo App with React' => [
        'title' => 'Quiz: Event Handling & Conditional Rendering di React',
        'questions' => [
            [
                'question' => 'Cara yang benar untuk menangani event klik pada sebuah <button> di React adalah?',
                'explanation' => 'Di React, event handler ditulis dalam camelCase (onClick, bukan onclick) dan nilainya adalah REFERENSI ke function, bukan hasil pemanggilan function (bukan diakhiri tanda kurung), supaya tidak langsung terpanggil saat render.',
                'options' => [
                    ['text' => '<button onClick={handleClick}>...</button>', 'correct' => true],
                    ['text' => '<button onclick="handleClick()">...</button>', 'correct' => false],
                    ['text' => '<button onClick={handleClick()}>...</button>', 'correct' => false],
                    ['text' => '<button click={handleClick}>...</button>', 'correct' => false],
                ],
            ],
            [
                'question' => 'Untuk menampilkan pesan "Belum ada tugas" HANYA ketika array tugas kosong, teknik conditional rendering apa yang paling umum dipakai?',
                'explanation' => 'Operator && (short-circuit) cocok untuk menampilkan sesuatu HANYA jika kondisi bernilai true, misalnya {tugas.length === 0 && <p>Belum ada tugas</p>} — kalau kondisi false, React tidak merender apa pun.',
                'options' => [
                    ['text' => '{tugas.length === 0 && <p>Belum ada tugas</p>}', 'correct' => true],
                    ['text' => '<if condition={tugas.length === 0}><p>Belum ada tugas</p></if>', 'correct' => false],
                    ['text' => '{tugas.length === 0}<p>Belum ada tugas</p>', 'correct' => false],
                    ['text' => 'switch(tugas.length) { case 0: ... }', 'correct' => false],
                ],
            ],
            [
                'question' => 'Saat menampilkan daftar tugas dengan .map(), kenapa setiap elemen dalam list WAJIB diberi prop key yang unik?',
                'explanation' => 'Prop key membantu React mengidentifikasi elemen mana yang berubah, ditambah, atau dihapus antar render, sehingga React bisa memperbarui DOM secara efisien tanpa merender ulang seluruh list dari nol.',
                'options' => [
                    ['text' => 'Supaya React bisa melacak identitas tiap item dan update DOM secara efisien', 'correct' => true],
                    ['text' => 'Supaya urutan item dalam array otomatis terbalik', 'correct' => false],
                    ['text' => 'Supaya item bisa diberi warna berbeda-beda', 'correct' => false],
                    ['text' => 'key hanya kosmetik dan tidak berpengaruh ke performa', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kenapa memakai index array sebagai key pada list yang urutannya bisa berubah (misalnya tugas yang bisa dihapus di tengah) BUKAN praktik yang disarankan?',
                'explanation' => 'Kalau item di tengah list dihapus, index item-item setelahnya akan bergeser, membuat React salah mengasosiasikan state/DOM lama dengan item yang salah — sebaiknya pakai id unik yang tidak berubah, bukan index posisi.',
                'options' => [
                    ['text' => 'Index bisa berubah saat item ditambah/dihapus, sehingga key jadi tidak stabil', 'correct' => true],
                    ['text' => 'Index array selalu berupa string, sedangkan key harus angka', 'correct' => false],
                    ['text' => 'React tidak mengizinkan angka sebagai key sama sekali', 'correct' => false],
                    ['text' => 'Karena akan membuat aplikasi otomatis error saat build', 'correct' => false],
                ],
            ],
            [
                'question' => 'Untuk mengubah status "selesai/belum" satu tugas tanpa mengubah tugas lain, pendekatan yang tepat pada array state adalah?',
                'explanation' => 'State di React sebaiknya diperlakukan immutable — gunakan map() untuk membuat array BARU di mana hanya item yang cocok id-nya yang diubah, lalu simpan array baru itu ke state, bukan mengubah array asli secara langsung (mutasi).',
                'options' => [
                    ['text' => 'Membuat array baru dengan map(), ubah hanya item yang id-nya cocok', 'correct' => true],
                    ['text' => 'Mengubah langsung properti item di dalam array state (mutasi langsung)', 'correct' => false],
                    ['text' => 'Menghapus seluruh state lalu membuat ulang dari awal setiap saat', 'correct' => false],
                    ['text' => 'Memanggil location.reload() setiap kali status berubah', 'correct' => false],
                ],
            ],
            [
                'question' => 'Hook apa yang digunakan untuk menyimpan dan memperbarui state seperti daftar tugas di dalam function component?',
                'explanation' => 'useState mengembalikan pasangan [nilai, fungsiSetter] — memanggil fungsi setter inilah yang memberi tahu React bahwa state berubah dan component perlu dirender ulang.',
                'options' => [
                    ['text' => 'useState', 'correct' => true],
                    ['text' => 'useRef', 'correct' => false],
                    ['text' => 'useContext', 'correct' => false],
                    ['text' => 'useMemo', 'correct' => false],
                ],
            ],
        ],
    ],
 
    'Assignment 2: Movie Search App' => [
        'title' => 'Quiz: useEffect & Data Fetching di React',
        'questions' => [
            [
                'question' => 'Hook apa yang tepat digunakan untuk menjalankan fetch data film setiap kali kata kunci pencarian berubah?',
                'explanation' => 'useEffect menjalankan side effect (seperti fetch API) setelah render, dan bisa dikontrol agar berjalan ulang HANYA ketika nilai dalam dependency array (misalnya kata kunci) berubah.',
                'options' => [
                    ['text' => 'useEffect', 'correct' => true],
                    ['text' => 'useState', 'correct' => false],
                    ['text' => 'useMemo', 'correct' => false],
                    ['text' => 'useContext', 'correct' => false],
                ],
            ],
            [
                'question' => 'Apa yang terjadi jika useEffect(() => { fetchMovies(); }, []) ditulis dengan dependency array KOSONG?',
                'explanation' => 'Dependency array kosong [] berarti effect hanya dijalankan SEKALI, tepat setelah component pertama kali di-mount — cocok untuk fetch data awal yang tidak bergantung pada state/props yang berubah.',
                'options' => [
                    ['text' => 'Effect hanya berjalan sekali, saat component pertama kali dirender', 'correct' => true],
                    ['text' => 'Effect berjalan setiap kali component dirender ulang, tanpa henti', 'correct' => false],
                    ['text' => 'Effect tidak akan pernah berjalan sama sekali', 'correct' => false],
                    ['text' => 'Effect berjalan setiap 1 detik secara otomatis', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kalau kata kunci pencarian dimasukkan sebagai dependency, yaitu useEffect(() => {...}, [keyword]), kapan effect akan dijalankan ulang?',
                'explanation' => 'React membandingkan nilai di dependency array antar render — effect hanya dijalankan ulang ketika salah satu nilai di dalamnya (di sini keyword) benar-benar berbeda dari render sebelumnya.',
                'options' => [
                    ['text' => 'Setiap kali nilai keyword berubah dibanding render sebelumnya', 'correct' => true],
                    ['text' => 'Hanya sekali di awal, sama seperti dependency array kosong', 'correct' => false],
                    ['text' => 'Setiap kali component lain di halaman berubah', 'correct' => false],
                    ['text' => 'Tidak pernah, karena keyword adalah string bukan object', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kenapa penting menyimpan state terpisah untuk status loading (misalnya isLoading) saat melakukan fetch data film?',
                'explanation' => 'State loading memberi tahu UI kapan harus menampilkan indikator "Mencari..." dan kapan harus menampilkan hasil/pesan error — tanpa ini, pengguna tidak tahu apakah aplikasi sedang bekerja atau macet.',
                'options' => [
                    ['text' => 'Supaya UI bisa menampilkan indikator loading selama data belum datang', 'correct' => true],
                    ['text' => 'Supaya fetch API berjalan lebih cepat', 'correct' => false],
                    ['text' => 'Karena React mewajibkan setiap useEffect punya state loading', 'correct' => false],
                    ['text' => 'Supaya keyword pencarian otomatis tersimpan', 'correct' => false],
                ],
            ],
            [
                'question' => 'Bagaimana cara yang tepat menangani kasus di mana fetch API film gagal (misalnya server API down)?',
                'explanation' => 'Membungkus fetch dengan try...catch (atau .catch()) lalu menyimpan pesan error ke state khusus (misalnya errorMessage) memungkinkan UI menampilkan pesan yang jelas ke pengguna, bukan membiarkan aplikasi diam atau crash.',
                'options' => [
                    ['text' => 'Membungkus fetch dengan try...catch dan menyimpan error ke state untuk ditampilkan', 'correct' => true],
                    ['text' => 'Mengabaikannya karena error API jarang terjadi', 'correct' => false],
                    ['text' => 'Me-reload seluruh halaman otomatis setiap kali fetch gagal', 'correct' => false],
                    ['text' => 'Mengganti seluruh dependency array menjadi kosong', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kalau useEffect yang melakukan fetch TIDAK diberi dependency array sama sekali (parameter kedua dihilangkan), apa akibatnya?',
                'explanation' => 'Tanpa dependency array, effect akan dijalankan ULANG setiap kali component dirender — untuk fetch API, ini bisa menyebabkan request berulang tanpa henti setiap kali state apa pun berubah, yang biasanya bukan perilaku yang diinginkan.',
                'options' => [
                    ['text' => 'Effect berjalan ulang setiap kali component dirender ulang', 'correct' => true],
                    ['text' => 'Effect hanya berjalan sekali seperti dependency array kosong', 'correct' => false],
                    ['text' => 'React akan menampilkan error saat build', 'correct' => false],
                    ['text' => 'Effect otomatis dinonaktifkan oleh React', 'correct' => false],
                ],
            ],
        ],
    ],
 
    'Assignment 3: Multi-step Form' => [
        'title' => 'Quiz: Controlled Form & State Multi-Step',
        'questions' => [
            [
                'question' => 'Apa yang dimaksud dengan "controlled input" pada form React?',
                'explanation' => 'Controlled input adalah input yang nilainya sepenuhnya dikendalikan oleh state React (lewat value dan onChange) — sumber kebenaran data ada di state, bukan di DOM input itu sendiri.',
                'options' => [
                    ['text' => 'Input yang nilainya diatur oleh state React lewat value dan onChange', 'correct' => true],
                    ['text' => 'Input yang tidak bisa diketik oleh pengguna', 'correct' => false],
                    ['text' => 'Input yang otomatis tervalidasi oleh browser tanpa JavaScript', 'correct' => false],
                    ['text' => 'Input yang hanya bisa dipakai di dalam <form> HTML biasa', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kalau form multi-step disimpan sebagai satu object state besar (misalnya formData), kenapa saat mengubah SATU field, sebaiknya pakai pola setFormData({ ...formData, nama: value })?',
                'explanation' => 'Spread operator (...formData) menyalin semua field lain yang sudah ada, lalu hanya field nama yang di-override — ini mencegah field lain (dari step sebelumnya) hilang saat setState dipanggil, karena setState untuk object TIDAK menggabungkan otomatis seperti this.setState di class component.',
                'options' => [
                    ['text' => 'Supaya field lain di object formData tidak ikut terhapus/hilang', 'correct' => true],
                    ['text' => 'Supaya form otomatis submit ke server', 'correct' => false],
                    ['text' => 'Supaya validasi HTML5 berjalan otomatis', 'correct' => false],
                    ['text' => 'Supaya nama field wajib berupa angka', 'correct' => false],
                ],
            ],
            [
                'question' => 'Pendekatan mana yang tepat untuk menampilkan step form yang berbeda (step 1, 2, 3) berdasarkan state currentStep?',
                'explanation' => 'Conditional rendering — misalnya currentStep === 1 && <Step1 />, atau blok if/else — memungkinkan hanya satu step yang dirender pada satu waktu sesuai nilai state currentStep saat ini.',
                'options' => [
                    ['text' => 'Conditional rendering berdasarkan nilai state currentStep', 'correct' => true],
                    ['text' => 'Menampilkan seluruh step sekaligus lalu menyembunyikannya dengan display:none di CSS saja', 'correct' => false],
                    ['text' => 'Membuat 3 halaman HTML terpisah yang saling me-refresh', 'correct' => false],
                    ['text' => 'Menyimpan step di URL query string saja tanpa state React', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kapan idealnya validasi input pada step tertentu dijalankan dalam form multi-step?',
                'explanation' => 'Memvalidasi input SEBELUM pindah ke step berikutnya (misalnya saat tombol "Lanjut" diklik) mencegah pengguna melangkah dengan data yang belum lengkap/valid, dan memberi feedback lebih cepat dibanding menunggu submit akhir.',
                'options' => [
                    ['text' => 'Sebelum berpindah ke step berikutnya (saat tombol Lanjut diklik)', 'correct' => true],
                    ['text' => 'Hanya sekali di step terakhir, mengabaikan step-step sebelumnya', 'correct' => false],
                    ['text' => 'Setelah data terkirim ke server, tidak ada validasi di frontend', 'correct' => false],
                    ['text' => 'Validasi tidak diperlukan untuk form multi-step', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kalau pengguna menekan tombol "Kembali" ke step sebelumnya, apa yang seharusnya terjadi pada data yang sudah diisi di step tersebut?',
                'explanation' => 'Karena semua data form disimpan dalam satu state (bukan direset per step), data yang sudah diisi sebelumnya seharusnya tetap ada dan ditampilkan kembali saat pengguna kembali ke step itu — bukan hilang.',
                'options' => [
                    ['text' => 'Data yang sudah diisi tetap tersimpan dan ditampilkan kembali', 'correct' => true],
                    ['text' => 'Seluruh data form ikut direset ke kosong', 'correct' => false],
                    ['text' => 'Aplikasi otomatis reload halaman', 'correct' => false],
                    ['text' => 'Data dipindahkan ke step berikutnya, bukan step sebelumnya', 'correct' => false],
                ],
            ],
            [
                'question' => 'Event apa yang perlu ditangani (biasanya dengan preventDefault()) saat form akhirnya di-submit pada step terakhir?',
                'explanation' => 'onSubmit pada elemen <form> adalah event yang tepat untuk menangani proses submit; preventDefault() dipanggil agar browser tidak melakukan reload halaman bawaan saat form disubmit.',
                'options' => [
                    ['text' => 'onSubmit pada <form>, dengan event.preventDefault()', 'correct' => true],
                    ['text' => 'onClick pada seluruh halaman', 'correct' => false],
                    ['text' => 'onChange pada input step pertama saja', 'correct' => false],
                    ['text' => 'onLoad pada window', 'correct' => false],
                ],
            ],
        ],
    ],
 
    'Assignment 4: Shopping Cart' => [
        'title' => 'Quiz: State Management & Lists di React',
        'questions' => [
            [
                'question' => 'Untuk menambahkan item baru ke dalam array keranjang di state, pendekatan immutable yang tepat adalah?',
                'explanation' => 'setCart([...cart, itemBaru]) membuat array BARU berisi seluruh item lama ditambah item baru, tanpa mengubah array cart yang lama secara langsung — ini pola standar update array state di React.',
                'options' => [
                    ['text' => 'setCart([...cart, itemBaru])', 'correct' => true],
                    ['text' => 'cart.push(itemBaru)', 'correct' => false],
                    ['text' => 'cart[cart.length] = itemBaru', 'correct' => false],
                    ['text' => 'setCart(cart = itemBaru)', 'correct' => false],
                ],
            ],
            [
                'question' => 'Untuk menghapus satu item dari keranjang berdasarkan id-nya, method array mana yang paling tepat dipakai bersama setCart()?',
                'explanation' => 'filter() menghasilkan array baru yang tidak berisi item dengan id yang cocok, cocok untuk menghapus item tertentu tanpa memutasi array asli — misalnya setCart(cart.filter(item => item.id !== idYangDihapus)).',
                'options' => [
                    ['text' => 'cart.filter(item => item.id !== idYangDihapus)', 'correct' => true],
                    ['text' => 'cart.pop()', 'correct' => false],
                    ['text' => 'delete cart[index]', 'correct' => false],
                    ['text' => 'cart.reverse()', 'correct' => false],
                ],
            ],
            [
                'question' => 'Untuk mengubah jumlah (quantity) SATU item saja tanpa mengubah item lain di keranjang, method mana yang tepat?',
                'explanation' => 'map() memungkinkan membuat array baru di mana hanya item dengan id yang cocok yang diubah quantity-nya, sementara item lain dikembalikan apa adanya — pola umum untuk update sebagian item dalam array.',
                'options' => [
                    ['text' => 'cart.map(item => item.id === id ? {...item, qty: baru} : item)', 'correct' => true],
                    ['text' => 'cart.splice(0, 1)', 'correct' => false],
                    ['text' => 'cart.sort()', 'correct' => false],
                    ['text' => 'cart.join()', 'correct' => false],
                ],
            ],
            [
                'question' => 'Untuk menghitung total harga seluruh item di keranjang (harga × qty dijumlahkan), method array mana yang paling sesuai?',
                'explanation' => 'reduce() cocok untuk meringkas array item menjadi satu angka total, dengan mengalikan harga dan qty tiap item lalu mengakumulasikannya.',
                'options' => [
                    ['text' => 'cart.reduce((total, item) => total + item.harga * item.qty, 0)', 'correct' => true],
                    ['text' => 'cart.map((item) => item.harga * item.qty)', 'correct' => false],
                    ['text' => 'cart.filter((item) => item.harga)', 'correct' => false],
                    ['text' => 'cart.length * cart[0].harga', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kalau keranjang kosong (array cart.length === 0), UI apa yang sebaiknya ditampilkan sebagai pengganti daftar item?',
                'explanation' => 'Pesan seperti "Keranjang masih kosong" memberi umpan balik yang jelas ke pengguna, lebih baik daripada membiarkan area keranjang kosong tanpa keterangan apa-apa.',
                'options' => [
                    ['text' => 'Pesan khusus seperti "Keranjang masih kosong"', 'correct' => true],
                    ['text' => 'Area kosong tanpa keterangan apa pun', 'correct' => false],
                    ['text' => 'Pesan error merah bertuliskan "Error 404"', 'correct' => false],
                    ['text' => 'Item dari keranjang pengguna lain', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kenapa prop key pada tiap item di keranjang idealnya memakai id produk, bukan quantity atau harga?',
                'explanation' => 'key harus stabil dan unik untuk merepresentasikan IDENTITAS item, bukan nilai yang bisa berubah seperti quantity/harga — kalau key berubah tiap kali quantity berubah, React akan salah menganggap item sebagai item yang berbeda.',
                'options' => [
                    ['text' => 'id produk stabil dan unik, sedangkan quantity/harga bisa berubah-ubah', 'correct' => true],
                    ['text' => 'quantity dan harga tidak boleh dipakai sebagai data di React sama sekali', 'correct' => false],
                    ['text' => 'id produk selalu berupa angka, sedangkan key harus string', 'correct' => false],
                    ['text' => 'Tidak ada bedanya, key bisa memakai properti apa saja', 'correct' => false],
                ],
            ],
        ],
    ],
 
    'Assignment 5: Component Library' => [
        'title' => 'Quiz: Reusable Component & Props',
        'questions' => [
            [
                'question' => 'Apa tujuan utama membuat sebuah component React menjadi "reusable" (dapat dipakai ulang)?',
                'explanation' => 'Component reusable dirancang generik lewat props, sehingga bisa dipakai di berbagai konteks/tampilan berbeda tanpa perlu menulis ulang kode yang serupa berkali-kali.',
                'options' => [
                    ['text' => 'Bisa dipakai di banyak tempat berbeda tanpa menulis ulang kode serupa', 'correct' => true],
                    ['text' => 'Supaya component tidak bisa menerima props sama sekali', 'correct' => false],
                    ['text' => 'Supaya component hanya bisa dipakai satu kali dalam seluruh aplikasi', 'correct' => false],
                    ['text' => 'Supaya ukuran file component menjadi lebih besar', 'correct' => false],
                ],
            ],
            [
                'question' => 'Bagaimana cara memberi nilai default untuk sebuah prop, misalnya prop variant pada component Button, kalau prop itu tidak diisi saat dipakai?',
                'explanation' => 'Default parameter pada destructuring props ({ variant = "primary" }) adalah cara umum di function component modern untuk memberi nilai fallback ketika prop tidak dikirim oleh pemanggil.',
                'options' => [
                    ['text' => 'function Button({ variant = "primary" }) { ... }', 'correct' => true],
                    ['text' => 'function Button(variant) { variant = "primary"; }', 'correct' => false],
                    ['text' => 'Button.variant = "primary";', 'correct' => false],
                    ['text' => 'Props di React selalu wajib diisi, tidak bisa punya default', 'correct' => false],
                ],
            ],
            [
                'question' => 'Prop children pada sebuah component (misalnya <Card>{konten}</Card>) berfungsi untuk apa?',
                'explanation' => 'children adalah prop khusus yang berisi apa pun yang dituliskan DI ANTARA tag pembuka dan penutup component — memungkinkan component seperti Card atau Modal membungkus konten apa saja secara fleksibel.',
                'options' => [
                    ['text' => 'Menampung konten apa pun yang ditulis di antara tag pembuka-penutup component', 'correct' => true],
                    ['text' => 'Menyimpan daftar child component yang harus di-import manual', 'correct' => false],
                    ['text' => 'Mengatur urutan render seluruh aplikasi', 'correct' => false],
                    ['text' => 'Hanya bisa dipakai pada component class, bukan function component', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kalau ingin sebuah component Button menerima function yang dijalankan saat diklik, cara yang tepat adalah?',
                'explanation' => 'Function bisa dikirim sebagai prop biasa (misalnya onClick) dari parent ke child, lalu di dalam component Button, prop tersebut dipasang ke elemen <button onClick={onClick}> — pola umum untuk komunikasi child ke parent lewat callback.',
                'options' => [
                    ['text' => 'Mengirim function sebagai prop, misalnya <Button onClick={handleKlik} />', 'correct' => true],
                    ['text' => 'Menulis ulang logic klik di dalam component Button itu sendiri untuk setiap kasus', 'correct' => false],
                    ['text' => 'Memakai global variable untuk menyimpan function klik', 'correct' => false],
                    ['text' => 'Props di React tidak bisa berupa function', 'correct' => false],
                ],
            ],
            [
                'question' => 'Apa yang dimaksud dengan "konsistensi Props API" antar beberapa component dalam satu library?',
                'explanation' => 'Konsistensi Props API berarti pola penamaan dan penggunaan prop serupa (misalnya semua component memakai variant, size, disabled dengan makna yang sama) di seluruh component, sehingga developer yang memakainya tidak perlu mempelajari pola berbeda-beda untuk tiap component.',
                'options' => [
                    ['text' => 'Pola penamaan dan penggunaan prop yang serupa di semua component library', 'correct' => true],
                    ['text' => 'Semua component wajib memiliki jumlah props yang persis sama', 'correct' => false],
                    ['text' => 'Semua component harus ditulis dalam satu file yang sama', 'correct' => false],
                    ['text' => 'Props hanya boleh berupa string, tidak boleh object/function', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kenapa mendokumentasikan cara pemakaian tiap component (contoh props yang tersedia, contoh kode) penting untuk sebuah component library?',
                'explanation' => 'Dokumentasi membantu developer lain (atau diri sendiri di masa depan) memahami cara memakai component dengan benar tanpa harus membaca seluruh source code-nya satu per satu.',
                'options' => [
                    ['text' => 'Memudahkan developer lain memakai component dengan benar tanpa membaca seluruh source code', 'correct' => true],
                    ['text' => 'Supaya component otomatis lebih cepat dijalankan browser', 'correct' => false],
                    ['text' => 'Karena React mewajibkan setiap component punya file dokumentasi', 'correct' => false],
                    ['text' => 'Supaya component tidak bisa dipakai oleh tim lain', 'correct' => false],
                ],
            ],
        ],
    ],
 
    // ================================================================
    // ===================== MODUL 4: TypeScript for React =============
    // ================================================================
 
    'Assignment 1: Convert JS Project to TS' => [
        'title' => 'Quiz: Dasar TypeScript & Konfigurasi tsconfig',
        'questions' => [
            [
                'question' => 'Apa keuntungan utama TypeScript dibanding JavaScript biasa saat mengembangkan aplikasi berskala besar?',
                'explanation' => 'TypeScript menambahkan static typing di atas JavaScript, sehingga banyak kesalahan tipe data (misalnya memanggil method pada undefined) bisa terdeteksi SAAT development/compile, bukan baru ketahuan saat aplikasi berjalan di production.',
                'options' => [
                    ['text' => 'Mendeteksi kesalahan tipe data lebih awal, saat development bukan saat runtime', 'correct' => true],
                    ['text' => 'Membuat aplikasi berjalan otomatis lebih cepat di browser', 'correct' => false],
                    ['text' => 'Menghilangkan kebutuhan menulis function sama sekali', 'correct' => false],
                    ['text' => 'Mengubah JavaScript menjadi bahasa yang berbeda total dan tidak kompatibel', 'correct' => false],
                ],
            ],
            [
                'question' => 'File konfigurasi apa yang mengatur bagaimana compiler TypeScript memproses sebuah proyek (target JS, strict mode, dsb)?',
                'explanation' => 'tsconfig.json berisi opsi kompilasi seperti target versi JavaScript, folder output, dan level strictness pengecekan tipe yang berlaku untuk seluruh proyek.',
                'options' => [
                    ['text' => 'tsconfig.json', 'correct' => true],
                    ['text' => 'package.json', 'correct' => false],
                    ['text' => '.babelrc', 'correct' => false],
                    ['text' => 'webpack.config.js', 'correct' => false],
                ],
            ],
            [
                'question' => 'Interface pada TypeScript, misalnya interface User { nama: string; umur: number }, digunakan untuk apa?',
                'explanation' => 'Interface mendefinisikan "bentuk" (shape) yang harus dipenuhi sebuah object — properti apa saja yang wajib ada beserta tipe datanya, membantu memastikan konsistensi struktur data di seluruh kode.',
                'options' => [
                    ['text' => 'Mendefinisikan struktur/bentuk object beserta tipe data tiap propertinya', 'correct' => true],
                    ['text' => 'Menjalankan function secara otomatis saat aplikasi start', 'correct' => false],
                    ['text' => 'Mengganti seluruh sintaks JavaScript menjadi Python', 'correct' => false],
                    ['text' => 'Menghubungkan aplikasi ke database secara langsung', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kenapa penggunaan tipe any secara berlebihan di kode TypeScript sebaiknya dihindari?',
                'explanation' => 'Tipe any menonaktifkan pengecekan tipe pada variabel tersebut, sehingga menghilangkan manfaat utama TypeScript (deteksi error tipe data) — memakainya berlebihan membuat kode kembali "seperti JavaScript biasa" tanpa jaminan keamanan tipe.',
                'options' => [
                    ['text' => 'Karena menonaktifkan pengecekan tipe, menghilangkan manfaat utama TypeScript', 'correct' => true],
                    ['text' => 'Karena any hanya bisa dipakai satu kali per file', 'correct' => false],
                    ['text' => 'Karena any akan otomatis membuat aplikasi crash saat runtime', 'correct' => false],
                    ['text' => 'Karena any tidak didukung oleh React sama sekali', 'correct' => false],
                ],
            ],
            [
                'question' => 'Setelah mengonversi file .js menjadi .ts atau .tsx, langkah apa yang penting dilakukan untuk memastikan konversi berjalan aman?',
                'explanation' => 'Menjalankan compiler TypeScript (atau IDE) untuk melihat error tipe yang muncul, lalu memperbaikinya satu per satu, memastikan tidak ada tipe yang salah/hilang sebelum menganggap konversi selesai.',
                'options' => [
                    ['text' => 'Menjalankan compiler TypeScript dan memperbaiki error tipe yang muncul', 'correct' => true],
                    ['text' => 'Langsung menghapus seluruh anotasi tipe supaya tidak ada error', 'correct' => false],
                    ['text' => 'Mengubah semua variabel menjadi tipe any supaya tidak ada error', 'correct' => false],
                    ['text' => 'Tidak perlu langkah tambahan apa pun', 'correct' => false],
                ],
            ],
            [
                'question' => 'Ekstensi file mana yang digunakan untuk component React yang ditulis dengan TypeScript dan mengandung JSX?',
                'explanation' => '.tsx dipakai khusus untuk file TypeScript yang berisi sintaks JSX (seperti component React), sedangkan .ts dipakai untuk file TypeScript biasa tanpa JSX.',
                'options' => [
                    ['text' => '.tsx', 'correct' => true],
                    ['text' => '.jsx', 'correct' => false],
                    ['text' => '.tjs', 'correct' => false],
                    ['text' => '.type', 'correct' => false],
                ],
            ],
        ],
    ],
 
    'Assignment 2: Typed Form Component' => [
        'title' => 'Quiz: Typing Props, State & Event Handler',
        'questions' => [
            [
                'question' => 'Cara yang tepat untuk memberi tipe pada props sebuah component form di TypeScript adalah?',
                'explanation' => 'Mendefinisikan interface (misalnya interface FormProps) yang berisi nama dan tipe tiap prop, lalu menggunakannya sebagai tipe parameter props pada function component.',
                'options' => [
                    ['text' => 'Mendefinisikan interface FormProps { ... } lalu memakainya sebagai tipe props', 'correct' => true],
                    ['text' => 'Menulis props sebagai string bebas tanpa struktur', 'correct' => false],
                    ['text' => 'Memberi nama variabel props dengan awalan "typed_"', 'correct' => false],
                    ['text' => 'Props di TypeScript tidak bisa diberi tipe sama sekali', 'correct' => false],
                ],
            ],
            [
                'question' => 'Tipe apa yang tepat digunakan untuk event handler onChange pada elemen <input> di React + TypeScript?',
                'explanation' => 'React.ChangeEvent<HTMLInputElement> adalah tipe bawaan React TypeScript untuk event onChange pada elemen input, memberi akses ke event.target.value dengan tipe yang aman.',
                'options' => [
                    ['text' => 'React.ChangeEvent<HTMLInputElement>', 'correct' => true],
                    ['text' => 'React.ClickEvent<HTMLButtonElement>', 'correct' => false],
                    ['text' => 'string', 'correct' => false],
                    ['text' => 'any[]', 'correct' => false],
                ],
            ],
            [
                'question' => 'Tipe apa yang tepat digunakan untuk event handler onSubmit pada elemen <form>?',
                'explanation' => 'React.FormEvent<HTMLFormElement> adalah tipe bawaan untuk event submit form, memungkinkan pemanggilan event.preventDefault() dengan aman secara tipe.',
                'options' => [
                    ['text' => 'React.FormEvent<HTMLFormElement>', 'correct' => true],
                    ['text' => 'React.SubmitAction', 'correct' => false],
                    ['text' => 'HTMLFormElement langsung tanpa React.FormEvent', 'correct' => false],
                    ['text' => 'void', 'correct' => false],
                ],
            ],
            [
                'question' => 'Bagaimana cara memberi tipe pada state form yang berupa object dengan beberapa field, misalnya { nama: string; email: string }, saat memakai useState?',
                'explanation' => 'Generic pada useState, yaitu useState<{ nama: string; email: string }>({ nama: "", email: "" }), memastikan TypeScript tahu persis bentuk object state tersebut, sehingga typo properti langsung terdeteksi.',
                'options' => [
                    ['text' => "useState<{ nama: string; email: string }>({ nama: '', email: '' })", 'correct' => true],
                    ['text' => 'useState(nama, email)', 'correct' => false],
                    ['text' => "useState.type({ nama: '', email: '' })", 'correct' => false],
                    ['text' => 'State di TypeScript tidak boleh berupa object', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kenapa perlu memberi tipe optional (tanda ?) pada sebuah prop, misalnya errorMessage?: string, di interface props form?',
                'explanation' => 'Tanda ? menandai bahwa prop tersebut BOLEH tidak dikirim oleh pemanggil component — cocok untuk prop seperti pesan error yang mungkin tidak selalu ada (hanya muncul kalau validasi gagal).',
                'options' => [
                    ['text' => 'Menandai bahwa prop tersebut opsional, boleh tidak dikirim oleh pemanggil component', 'correct' => true],
                    ['text' => 'Menandai bahwa prop tersebut wajib berupa angka', 'correct' => false],
                    ['text' => 'Membuat prop tersebut otomatis bernilai true', 'correct' => false],
                    ['text' => 'Menghapus prop tersebut dari component', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kalau validasi form menghasilkan pesan error yang berbeda-beda untuk tiap field, struktur tipe apa yang cocok untuk menyimpannya?',
                'explanation' => 'Sebuah object/Record dengan key sesuai nama field dan value berupa string pesan error (misalnya Record<string, string> atau interface khusus) memudahkan menampilkan error yang tepat di bawah field yang sesuai.',
                'options' => [
                    ['text' => 'Object/Record dengan key nama field dan value string pesan error', 'correct' => true],
                    ['text' => 'Satu variabel string tunggal untuk semua field sekaligus', 'correct' => false],
                    ['text' => 'Array angka berisi jumlah karakter tiap field', 'correct' => false],
                    ['text' => 'Boolean tunggal true/false untuk seluruh form', 'correct' => false],
                ],
            ],
        ],
    ],
 
    'Assignment 3: Typed API Client' => [
        'title' => 'Quiz: Generics & Type Safety pada API Client',
        'questions' => [
            [
                'question' => 'Apa tujuan utama menggunakan generics, misalnya function fetchData<T>(url: string): Promise<T>, pada sebuah fungsi API client?',
                'explanation' => 'Generics memungkinkan satu function API client dipakai untuk berbagai tipe data response (misalnya User, Product, dll) tanpa perlu menulis function terpisah untuk tiap tipe, sambil tetap menjaga type safety.',
                'options' => [
                    ['text' => 'Memungkinkan satu function dipakai untuk berbagai tipe data response secara type-safe', 'correct' => true],
                    ['text' => 'Membuat function bisa dipanggil tanpa parameter sama sekali', 'correct' => false],
                    ['text' => 'Mengubah function menjadi otomatis asynchronous', 'correct' => false],
                    ['text' => 'Menghapus kebutuhan menangani error pada fetch', 'correct' => false],
                ],
            ],
            [
                'question' => 'Pada function fetchData<T>(url: string): Promise<T>, huruf T berperan sebagai apa?',
                'explanation' => 'T adalah type parameter (placeholder tipe) yang akan diisi dengan tipe konkret saat function dipanggil, misalnya fetchData<User>(url) berarti Promise yang dikembalikan berisi data bertipe User.',
                'options' => [
                    ['text' => 'Placeholder tipe generik yang diisi tipe konkret saat function dipanggil', 'correct' => true],
                    ['text' => 'Nama variabel yang wajib bernilai true/false', 'correct' => false],
                    ['text' => 'Singkatan dari "Try" dalam try...catch', 'correct' => false],
                    ['text' => 'Nama function bawaan TypeScript untuk fetch', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kalau response API punya bentuk { data: User[], total: number }, cara yang tepat mendefinisikan tipenya di TypeScript adalah?',
                'explanation' => 'interface ApiResponse<T> { data: T[]; total: number } adalah struktur generic yang bisa dipakai ulang untuk berbagai tipe data T (User, Product, dll), tanpa harus membuat interface response terpisah untuk tiap endpoint.',
                'options' => [
                    ['text' => 'interface ApiResponse<T> { data: T[]; total: number }', 'correct' => true],
                    ['text' => 'const ApiResponse = "data, total"', 'correct' => false],
                    ['text' => 'type ApiResponse = any', 'correct' => false],
                    ['text' => 'Tidak perlu didefinisikan, TypeScript akan menebaknya otomatis dengan tepat', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kenapa penting memberi tipe pada error yang ditangkap di blok catch saat memanggil API client (misalnya catch (error: unknown))?',
                'explanation' => 'Error yang dilempar bisa berasal dari mana saja dan bentuknya tidak selalu Error standar, sehingga TypeScript modern menyarankan tipe unknown lalu melakukan pengecekan (misalnya instanceof Error) sebelum mengakses propertinya, demi type safety.',
                'options' => [
                    ['text' => 'Karena bentuk error tidak selalu pasti, unknown memaksa pengecekan sebelum dipakai', 'correct' => true],
                    ['text' => 'Karena TypeScript tidak mengizinkan blok catch tanpa tipe apa pun', 'correct' => false],
                    ['text' => 'Karena error harus selalu bertipe string', 'correct' => false],
                    ['text' => 'Karena unknown otomatis menampilkan pesan error ke pengguna', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kalau sebuah API bisa mengembalikan data user ATAU data admin dengan struktur berbeda, konsep TypeScript apa yang cocok untuk merepresentasikan "salah satu dari beberapa tipe"?',
                'explanation' => 'Union type (misalnya type Result = User | Admin) merepresentasikan nilai yang bisa berupa salah satu dari beberapa tipe, cocok untuk kasus response API yang bentuknya bisa berbeda tergantung kondisi.',
                'options' => [
                    ['text' => 'Union type, misalnya type Result = User | Admin', 'correct' => true],
                    ['text' => 'Interface tunggal yang menggabungkan semua field User dan Admin jadi satu tanpa union', 'correct' => false],
                    ['text' => 'Tipe any untuk seluruh response', 'correct' => false],
                    ['text' => 'Array kosong []', 'correct' => false],
                ],
            ],
            [
                'question' => 'Apa manfaat utama type safety pada API client dibanding sekadar memakai fetch() biasa tanpa tipe di TypeScript?',
                'explanation' => 'Dengan tipe yang jelas pada response API, editor/compiler bisa mendeteksi kalau kode mencoba mengakses properti yang tidak ada pada data, atau salah memakai tipe data, jauh sebelum kode dijalankan — mengurangi bug terkait data yang tidak sesuai ekspektasi.',
                'options' => [
                    ['text' => 'Mendeteksi kesalahan akses properti/tipe data sejak saat menulis kode, sebelum dijalankan', 'correct' => true],
                    ['text' => 'Membuat request API terkirim lebih cepat ke server', 'correct' => false],
                    ['text' => 'Menghilangkan kebutuhan endpoint API di backend', 'correct' => false],
                    ['text' => 'Otomatis meng-cache seluruh response tanpa konfigurasi', 'correct' => false],
                ],
            ],
        ],
    ],
 
    // ================================================================
    // =================== MODUL 5: Advanced React Patterns ============
    // ================================================================
 
    'Assignment 1: Custom Hook Library' => [
        'title' => 'Quiz: Custom Hooks',
        'questions' => [
            [
                'question' => 'Sebuah function baru bisa disebut custom hook di React kalau namanya mengikuti konvensi apa?',
                'explanation' => 'Konvensi React mewajibkan custom hook diawali dengan kata "use" (misalnya useFetch, useLocalStorage) — ini bukan sekadar gaya penamaan, tapi dipakai linter React untuk memastikan aturan hook (rules of hooks) diterapkan dengan benar pada function tersebut.',
                'options' => [
                    ['text' => 'Nama function diawali dengan "use", misalnya useFetch', 'correct' => true],
                    ['text' => 'Nama function harus diakhiri dengan "Hook"', 'correct' => false],
                    ['text' => 'Function harus dideklarasikan di dalam file bernama hooks.js', 'correct' => false],
                    ['text' => 'Tidak ada konvensi penamaan khusus untuk custom hook', 'correct' => false],
                ],
            ],
            [
                'question' => 'Apa alasan utama membuat custom hook seperti useFetch, dibanding menulis logic fetch berulang di setiap component?',
                'explanation' => 'Custom hook memungkinkan logic yang stateful (seperti fetch + loading + error state) diekstrak dan dipakai ulang di banyak component tanpa duplikasi kode — menerapkan prinsip DRY (Don\'t Repeat Yourself).',
                'options' => [
                    ['text' => 'Menghindari duplikasi logic stateful di banyak component (prinsip DRY)', 'correct' => true],
                    ['text' => 'Supaya component tidak bisa lagi memiliki state sendiri', 'correct' => false],
                    ['text' => 'Supaya aplikasi otomatis berjalan tanpa perlu di-render', 'correct' => false],
                    ['text' => 'Karena React mewajibkan setiap fetch dibungkus custom hook', 'correct' => false],
                ],
            ],
            [
                'question' => 'Custom hook seperti useLocalStorage boleh memanggil hook bawaan React lain (misalnya useState, useEffect) di dalamnya — pernyataan ini?',
                'explanation' => 'Benar — custom hook pada dasarnya adalah function biasa yang BOLEH memanggil hook bawaan React lain di dalamnya, selama tetap mengikuti rules of hooks (dipanggil di top-level, bukan di dalam kondisional/loop).',
                'options' => [
                    ['text' => 'Benar, custom hook boleh memanggil hook bawaan lain di dalamnya', 'correct' => true],
                    ['text' => 'Salah, custom hook tidak boleh memanggil hook bawaan sama sekali', 'correct' => false],
                    ['text' => 'Benar, tapi hanya boleh memanggil useState, tidak boleh useEffect', 'correct' => false],
                    ['text' => 'Salah, hook bawaan hanya boleh dipanggil langsung di dalam component', 'correct' => false],
                ],
            ],
            [
                'question' => 'Apa yang idealnya dikembalikan oleh custom hook useFetch(url), supaya komponen pemanggilnya bisa menampilkan status loading, error, dan data?',
                'explanation' => 'Mengembalikan object atau array berisi { data, isLoading, error } memberi komponen pemanggil semua informasi yang dibutuhkan untuk menampilkan UI sesuai kondisi (sedang memuat, gagal, atau berhasil).',
                'options' => [
                    ['text' => '{ data, isLoading, error }', 'correct' => true],
                    ['text' => 'Hanya data mentah tanpa status loading/error', 'correct' => false],
                    ['text' => 'Sebuah component JSX siap tampil', 'correct' => false],
                    ['text' => 'undefined selalu, terlepas dari hasil fetch', 'correct' => false],
                ],
            ],
            [
                'question' => 'Salah satu aturan penting "rules of hooks" adalah hook tidak boleh dipanggil di dalam?',
                'explanation' => 'Hook (termasuk custom hook) harus selalu dipanggil di TOP-LEVEL function component atau custom hook lain — tidak boleh di dalam kondisional (if), loop, atau function bersarang, karena React mengandalkan URUTAN pemanggilan hook untuk menjaga state tetap konsisten antar render.',
                'options' => [
                    ['text' => 'Kondisional (if) atau loop', 'correct' => true],
                    ['text' => 'Function component itu sendiri', 'correct' => false],
                    ['text' => 'Custom hook lain', 'correct' => false],
                    ['text' => 'Baris paling atas sebuah component', 'correct' => false],
                ],
            ],
            [
                'question' => 'useDebounce adalah contoh custom hook yang berguna untuk skenario apa?',
                'explanation' => 'useDebounce menunda eksekusi suatu aksi (misalnya memanggil API pencarian) sampai pengguna berhenti mengetik selama durasi tertentu, mengurangi jumlah request yang tidak perlu saat pengguna masih mengetik.',
                'options' => [
                    ['text' => 'Menunda eksekusi suatu aksi sampai pengguna berhenti mengetik/berinteraksi sejenak', 'correct' => true],
                    ['text' => 'Menghapus data dari localStorage secara otomatis', 'correct' => false],
                    ['text' => 'Membuat komponen selalu dirender ulang tanpa henti', 'correct' => false],
                    ['text' => 'Mengganti seluruh CSS aplikasi menjadi dark mode', 'correct' => false],
                ],
            ],
        ],
    ],
 
    'Assignment 2: State Management App' => [
        'title' => 'Quiz: Context API & useReducer',
        'questions' => [
            [
                'question' => 'Masalah utama apa yang diselesaikan oleh Context API pada aplikasi React?',
                'explanation' => 'Context API menghindari "prop drilling" — kondisi di mana sebuah prop harus diteruskan lewat banyak lapisan component perantara hanya supaya sampai ke component yang benar-benar membutuhkannya di lapisan yang lebih dalam.',
                'options' => [
                    ['text' => 'Menghindari prop drilling (meneruskan prop lewat banyak lapisan component)', 'correct' => true],
                    ['text' => 'Mempercepat proses build aplikasi React', 'correct' => false],
                    ['text' => 'Mengganti seluruh kebutuhan useState di aplikasi', 'correct' => false],
                    ['text' => 'Menghubungkan aplikasi React langsung ke database', 'correct' => false],
                ],
            ],
            [
                'question' => 'Component apa yang harus membungkus bagian aplikasi yang ingin mengakses nilai dari sebuah Context?',
                'explanation' => 'Setiap Context yang dibuat dengan createContext() punya komponen Provider (misalnya <CartContext.Provider value={...}>) yang harus membungkus bagian pohon component yang membutuhkan akses ke nilai context tersebut.',
                'options' => [
                    ['text' => '<NamaContext.Provider value={...}>', 'correct' => true],
                    ['text' => '<NamaContext.Consumer.Wrapper>', 'correct' => false],
                    ['text' => '<React.GlobalState>', 'correct' => false],
                    ['text' => 'Tidak perlu component pembungkus apa pun', 'correct' => false],
                ],
            ],
            [
                'question' => 'Hook apa yang dipakai di dalam sebuah component untuk MENGAMBIL nilai dari sebuah Context?',
                'explanation' => 'useContext(NamaContext) mengembalikan nilai terkini yang disediakan oleh Provider terdekat di atas component tersebut dalam pohon component.',
                'options' => [
                    ['text' => 'useContext', 'correct' => true],
                    ['text' => 'useState', 'correct' => false],
                    ['text' => 'useRef', 'correct' => false],
                    ['text' => 'useCallback', 'correct' => false],
                ],
            ],
            [
                'question' => 'Fungsi reducer pada useReducer, misalnya function cartReducer(state, action), menerima parameter apa dan mengembalikan apa?',
                'explanation' => 'Reducer menerima state SAAT INI dan sebuah action (biasanya berisi type dan payload), lalu mengembalikan state BARU berdasarkan logic di dalamnya — mirip pola switch-case berdasarkan action.type.',
                'options' => [
                    ['text' => 'Menerima (state, action), mengembalikan state baru', 'correct' => true],
                    ['text' => 'Menerima (props, context), mengembalikan JSX', 'correct' => false],
                    ['text' => 'Menerima (event), mengembalikan boolean', 'correct' => false],
                    ['text' => 'Tidak menerima parameter apa pun', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kapan useReducer umumnya lebih disarankan dibanding beberapa useState terpisah untuk mengelola satu bagian state?',
                'explanation' => 'Ketika logic perubahan state cukup kompleks dengan banyak jenis aksi yang saling terkait (misalnya tambah/hapus/ubah quantity item keranjang), useReducer mengumpulkan seluruh logic transisi state di satu tempat (reducer), membuatnya lebih mudah dilacak dibanding banyak useState terpisah yang saling bergantung.',
                'options' => [
                    ['text' => 'Saat state punya banyak jenis aksi/transisi yang saling terkait dan kompleks', 'correct' => true],
                    ['text' => 'Saat state hanya berupa satu boolean sederhana', 'correct' => false],
                    ['text' => 'useReducer selalu lebih baik dipakai untuk semua kasus, tanpa terkecuali', 'correct' => false],
                    ['text' => 'Hanya saat aplikasi tidak memakai Context API', 'correct' => false],
                ],
            ],
            [
                'question' => 'Untuk memicu perubahan state lewat reducer dari dalam sebuah component, function apa yang dipanggil (hasil dari useReducer)?',
                'explanation' => 'const [state, dispatch] = useReducer(reducer, initialState) — dispatch(action) inilah yang dipanggil untuk mengirim action ke reducer, yang kemudian menghitung dan mengembalikan state baru.',
                'options' => [
                    ['text' => 'dispatch(action)', 'correct' => true],
                    ['text' => 'setState(action)', 'correct' => false],
                    ['text' => 'render(action)', 'correct' => false],
                    ['text' => 'trigger(action)', 'correct' => false],
                ],
            ],
        ],
    ],
 
    'Assignment 3: Performance Demo' => [
        'title' => 'Quiz: useMemo & useCallback',
        'questions' => [
            [
                'question' => 'Hook useMemo digunakan untuk menghindari perhitungan ulang yang berat pada setiap render — apa yang sebenarnya di-"memo"-kan (disimpan/di-cache)?',
                'explanation' => 'useMemo menyimpan HASIL dari sebuah perhitungan (nilai), dan hanya menghitung ulang jika salah satu nilai di dependency array berubah — kalau tidak, nilai lama yang tersimpan langsung dipakai lagi.',
                'options' => [
                    ['text' => 'Hasil (nilai) dari sebuah perhitungan/fungsi', 'correct' => true],
                    ['text' => 'Seluruh isi komponen React', 'correct' => false],
                    ['text' => 'Struktur file CSS yang dipakai komponen', 'correct' => false],
                    ['text' => 'Riwayat state dari render-render sebelumnya', 'correct' => false],
                ],
            ],
            [
                'question' => 'Perbedaan utama antara useMemo dan useCallback adalah?',
                'explanation' => 'useMemo mengembalikan NILAI hasil perhitungan yang di-cache, sedangkan useCallback mengembalikan REFERENSI FUNCTION yang sama antar render (selama dependency tidak berubah) — keduanya sama-sama menghindari kalkulasi/pembuatan ulang yang tidak perlu, tapi objeknya berbeda.',
                'options' => [
                    ['text' => 'useMemo meng-cache nilai, useCallback meng-cache referensi function', 'correct' => true],
                    ['text' => 'useMemo hanya bisa dipakai di class component, useCallback di function component', 'correct' => false],
                    ['text' => 'useCallback hanya bisa dipakai untuk fetch API', 'correct' => false],
                    ['text' => 'Tidak ada perbedaan, keduanya benar-benar identik', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kenapa mengoptimasi SETIAP function dan value dalam component dengan useMemo/useCallback secara membabi buta BUKAN praktik yang disarankan?',
                'explanation' => 'useMemo dan useCallback sendiri punya biaya (menyimpan cache, membandingkan dependency setiap render) — kalau perhitungan/function-nya sederhana dan murah, overhead optimasi ini justru bisa lebih besar daripada manfaatnya. Optimasi sebaiknya diterapkan pada kasus yang benar-benar terbukti berat.',
                'options' => [
                    ['text' => 'Karena optimasi ini juga punya biaya, dan bisa lebih mahal dari manfaatnya jika dipakai berlebihan pada kasus sederhana', 'correct' => true],
                    ['text' => 'Karena React membatasi maksimal 5 useMemo per component', 'correct' => false],
                    ['text' => 'Karena useMemo dan useCallback akan otomatis dihapus compiler', 'correct' => false],
                    ['text' => 'Karena keduanya tidak kompatibel dengan TypeScript', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kapan useCallback paling berguna diterapkan pada sebuah function di dalam component?',
                'explanation' => 'useCallback paling terasa manfaatnya saat function tersebut dikirim sebagai prop ke child component yang di-wrap React.memo — supaya child tidak ikut re-render hanya karena parent membuat function BARU (dengan referensi berbeda) di setiap render, meski isinya sama.',
                'options' => [
                    ['text' => 'Saat function dikirim sebagai prop ke child component yang di-wrap React.memo', 'correct' => true],
                    ['text' => 'Saat function tidak pernah dipakai di mana pun dalam component', 'correct' => false],
                    ['text' => 'Hanya saat memakai TypeScript, tidak untuk JavaScript biasa', 'correct' => false],
                    ['text' => 'Saat component tidak memiliki child component sama sekali', 'correct' => false],
                ],
            ],
            [
                'question' => 'Bagaimana cara paling andal untuk MEMBUKTIKAN bahwa sebuah optimasi useMemo benar-benar meningkatkan performa, bukan sekadar asumsi?',
                'explanation' => 'Mengukur dengan tools seperti React DevTools Profiler (membandingkan waktu render sebelum dan sesudah optimasi) memberi bukti konkret, dibanding hanya menduga-duga bahwa suatu optimasi pasti bermanfaat.',
                'options' => [
                    ['text' => 'Mengukur waktu render sebelum dan sesudah optimasi, misalnya dengan React DevTools Profiler', 'correct' => true],
                    ['text' => 'Menambahkan useMemo ke semua tempat lalu berasumsi pasti lebih cepat', 'correct' => false],
                    ['text' => 'Menghitung jumlah baris kode yang berkurang', 'correct' => false],
                    ['text' => 'Bertanya ke pengguna apakah aplikasi terasa lebih cepat', 'correct' => false],
                ],
            ],
            [
                'question' => 'Dependency array pada useMemo(() => hitungTotal(items), [items]) berfungsi untuk apa?',
                'explanation' => 'Dependency array menentukan kapan nilai yang di-memo perlu dihitung ULANG — di sini, hitungTotal(items) hanya dijalankan lagi kalau referensi items berubah dibanding render sebelumnya; kalau tidak, nilai lama yang tersimpan langsung dipakai.',
                'options' => [
                    ['text' => 'Menentukan kapan nilai perlu dihitung ulang (hanya saat items berubah)', 'correct' => true],
                    ['text' => 'Menentukan urutan render seluruh aplikasi', 'correct' => false],
                    ['text' => 'Menentukan warna komponen saat items kosong', 'correct' => false],
                    ['text' => 'Tidak berpengaruh apa pun terhadap kapan nilai dihitung ulang', 'correct' => false],
                ],
            ],
        ],
    ],
 
    'Assignment 4: Testing Suite' => [
        'title' => 'Quiz: React Testing Library',
        'questions' => [
            [
                'question' => 'Filosofi utama React Testing Library adalah menguji component dari sudut pandang siapa?',
                'explanation' => 'React Testing Library dirancang agar test menyerupai bagaimana PENGGUNA berinteraksi dengan aplikasi (mencari teks di layar, mengklik tombol), bukan menguji detail implementasi internal seperti state atau nama variabel di dalam component.',
                'options' => [
                    ['text' => 'Sudut pandang pengguna, bukan detail implementasi internal component', 'correct' => true],
                    ['text' => 'Sudut pandang compiler TypeScript', 'correct' => false],
                    ['text' => 'Sudut pandang server backend', 'correct' => false],
                    ['text' => 'Sudut pandang search engine (SEO)', 'correct' => false],
                ],
            ],
            [
                'question' => 'Function mana yang dipakai untuk merender sebuah component ke dalam "virtual DOM" pengujian sebelum bisa diperiksa/diinteraksi?',
                'explanation' => 'render() dari React Testing Library merender component ke dalam lingkungan pengujian (jsdom), menyediakan API seperti screen untuk mencari elemen yang dihasilkan.',
                'options' => [
                    ['text' => 'render()', 'correct' => true],
                    ['text' => 'mountComponent()', 'correct' => false],
                    ['text' => 'buildDOM()', 'correct' => false],
                    ['text' => 'compile()', 'correct' => false],
                ],
            ],
            [
                'question' => 'Untuk mensimulasikan pengguna mengklik sebuah tombol dalam test, function/utility mana yang umum dipakai?',
                'explanation' => 'fireEvent.click(element) (atau userEvent.click() pada library pendamping) mensimulasikan interaksi klik pengguna terhadap elemen yang ditemukan lewat query seperti screen.getByRole atau getByText.',
                'options' => [
                    ['text' => 'fireEvent.click(element)', 'correct' => true],
                    ['text' => 'element.simulateTouch()', 'correct' => false],
                    ['text' => 'React.click(element)', 'correct' => false],
                    ['text' => 'element.press()', 'correct' => false],
                ],
            ],
            [
                'question' => 'Query mana yang PALING disarankan untuk mencari sebuah elemen dalam test, karena paling menyerupai cara pengguna (termasuk pengguna screen reader) menemukan elemen?',
                'explanation' => 'getByRole (misalnya getByRole("button", { name: "Simpan" })) menyerupai cara teknologi aksesibilitas mengidentifikasi elemen berdasarkan peran (role) dan namanya, sehingga direkomendasikan sebagai prioritas utama dibanding query berbasis test-id atau class.',
                'options' => [
                    ['text' => 'getByRole', 'correct' => true],
                    ['text' => 'getByTestId sebagai pilihan pertama untuk semua kasus', 'correct' => false],
                    ['text' => 'getByClassName', 'correct' => false],
                    ['text' => 'document.querySelector langsung di dalam test', 'correct' => false],
                ],
            ],
            [
                'question' => 'Apa yang idealnya diperiksa (assert) setelah mensimulasikan klik pada tombol "Tambah ke Keranjang" dalam sebuah test?',
                'explanation' => 'Assertion sebaiknya memeriksa HASIL yang terlihat oleh pengguna setelah aksi tersebut, misalnya apakah teks "1 item di keranjang" muncul di layar — bukan memeriksa detail internal seperti nilai state yang tidak terlihat pengguna.',
                'options' => [
                    ['text' => 'Hasil yang terlihat pengguna setelah aksi, misalnya teks jumlah item di keranjang bertambah', 'correct' => true],
                    ['text' => 'Nilai state internal component secara langsung, tanpa melihat tampilannya', 'correct' => false],
                    ['text' => 'Nama variabel yang dipakai di dalam function handleClick', 'correct' => false],
                    ['text' => 'Jumlah baris kode component tersebut', 'correct' => false],
                ],
            ],
            [
                'question' => 'Kenapa penting menguji SKENARIO GAGAL (misalnya submit form dengan input kosong), bukan hanya skenario berhasil, dalam test suite?',
                'explanation' => 'Aplikasi nyata sering menghadapi input tidak valid atau kondisi error — menguji skenario gagal memastikan aplikasi menampilkan pesan/perilaku yang benar dalam kondisi tersebut, bukan hanya bekerja saat semuanya berjalan "normal".',
                'options' => [
                    ['text' => 'Memastikan aplikasi menangani kondisi error/tidak valid dengan benar, bukan hanya kondisi ideal', 'correct' => true],
                    ['text' => 'Karena test framework mewajibkan minimal satu skenario gagal per file', 'correct' => false],
                    ['text' => 'Karena skenario berhasil tidak pernah perlu diuji', 'correct' => false],
                    ['text' => 'Supaya jumlah test terlihat lebih banyak di laporan', 'correct' => false],
                ],
            ],
        ],
    ],

        ];
    }
}