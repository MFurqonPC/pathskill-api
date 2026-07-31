<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MiniProject;
use Illuminate\Database\Seeder;

class MiniProjectFullStackSeeder extends Seeder
{
    /**
     * Mini project untuk seluruh assignment praktik di track Full Stack
     * Developer (Modul 1-5). "Assignment 4: Quiz: ES6+ Concepts" di Modul 2
     * SENGAJA tidak diberi mini project karena berupa quiz penilaian
     * (ditangani QuizFullStackSeeder), bukan tugas praktik.
     *
     * Ini tahap terakhir sebelum Pengumpulan. Jalankan setelah
     * LearningPathSeeder, AssignmentDetailSeeder & CodingExerciseFullStackSeeder
     * (assignment-nya harus sudah ada). Idempotent lewat updateOrCreate
     * berdasarkan assignment_id, aman dijalankan berkali-kali.
     *
     * Jalankan:
     *   php artisan db:seed --class=MiniProjectFullStackSeeder
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

        $this->command?->info("MiniProjectFullStackSeeder: {$created} mini project berhasil dibuat/diperbarui.");

        if (! empty($skipped)) {
            $this->command?->warn(
                'MiniProjectFullStackSeeder: assignment tidak ditemukan — ' . implode(', ', $skipped)
            );
        }
    }

    private function projectData(): array
    {
        return [

            // ============================================================
            // ---------- Modul 1: Frontend Fundamentals ----------
            // ============================================================
            'Assignment 1: Personal Portfolio Page' => [
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
            ],

            'Assignment 2: Responsive Landing Page' => [
                'title' => 'Tantangan Mini Project: Landing Page Produk/Layanan',
                'brief' => 'Kembangkan Latihan Coding "Section Fitur" sebelumnya jadi satu landing page utuh multi-section untuk produk atau layanan fiktif pilihanmu sendiri (misalnya aplikasi, kedai kopi, jasa desain, dsb). Fokus utama: konsistensi desain antar section dan layout yang benar-benar mobile-first, bukan sekadar "menyusut" saat layar dikecilkan.',
                'objectives' => [
                    'Susun landing page dengan minimal 4 section: Hero, Fitur/Layanan, Testimoni, dan Footer/CTA',
                    'Terapkan CSS Grid dan Flexbox secara kombinatif sesuai kebutuhan tiap section',
                    'Pastikan hierarki visual jelas — judul, subjudul, dan CTA mudah dibedakan tingkat kepentingannya',
                ],
                'acceptance_criteria' => [
                    'Layout dibangun mobile-first, ditulis dari CSS dasar layar kecil lalu diperluas pakai media query min-width',
                    'Section Fitur/Layanan memakai grid yang otomatis menyesuaikan jumlah kolom (1 kolom di HP, 3 kolom di desktop)',
                    'Semua gambar punya atribut alt dan tidak overflow di layar sempit',
                    'Konsistensi visual terjaga: warna, font, dan spacing antar section terasa satu kesatuan, bukan seperti section yang didesain terpisah-pisah',
                    'Navigasi utama tetap bisa diakses dengan nyaman di layar HP (boleh dalam bentuk hamburger menu atau susunan vertikal sederhana)',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi penjelasan produk/layanan fiktif yang dipilih',
                ],
            ],

            'Assignment 3: Interactive To-Do List' => [
                'title' => 'Tantangan Mini Project: Aplikasi To-Do List Interaktif',
                'brief' => 'Kembangkan Latihan Coding "Tambah & Hapus Todo" sebelumnya jadi aplikasi to-do list yang lebih lengkap: pengguna bisa menambah, menandai selesai, dan menghapus tugas — semuanya diproses langsung di browser dengan JavaScript, tanpa reload halaman sama sekali.',
                'objectives' => [
                    'Implementasikan fitur tambah tugas baru lewat input dan tombol/Enter',
                    'Implementasikan fitur menandai tugas sebagai selesai (misalnya klik checkbox atau teks jadi coret)',
                    'Implementasikan fitur hapus tugas per item',
                ],
                'acceptance_criteria' => [
                    'Semua interaksi (tambah, tandai selesai, hapus) berjalan tanpa reload halaman',
                    'Todo kosong (input tidak diisi) tidak bisa ditambahkan ke daftar',
                    'Tugas yang ditandai selesai terlihat jelas berbeda secara visual (misal teks dicoret atau warna berubah) dari yang belum selesai',
                    'Struktur kode JavaScript rapi — logic tambah/hapus/tandai selesai dipisah jadi function-function kecil, bukan satu blok kode panjang',
                    'Tampilan responsif dan nyaman digunakan baik di layar HP maupun desktop',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi daftar fitur yang berhasil diimplementasikan',
                ],
            ],

            // ============================================================
            // ---------- Modul 2: Modern JavaScript & ES6+ ----------
            // ============================================================
            'Assignment 1: Async Weather App' => [
                'title' => 'Tantangan Mini Project: Aplikasi Cuaca Multi-Kota',
                'brief' => 'Kembangkan Latihan Coding fetch cuaca sebelumnya jadi aplikasi cuaca yang lebih lengkap: pengguna bisa mencari cuaca beberapa kota sekaligus, dengan status loading dan error yang jelas di setiap pencarian, tanpa membuat UI freeze.',
                'objectives' => [
                    'Implementasikan pencarian cuaca berdasarkan nama kota memakai Fetch API dan async/await',
                    'Tampilkan minimal 3 kota tersimpan secara bersamaan dalam bentuk card terpisah',
                    'Tangani seluruh kemungkinan error (kota tidak ditemukan, koneksi gagal) dengan pesan yang informatif',
                ],
                'acceptance_criteria' => [
                    'Setiap card kota punya status loading sendiri, tidak saling memblokir card lain',
                    'Kota yang tidak ditemukan menampilkan pesan error yang jelas, bukan crash atau layar kosong',
                    'Seluruh pemanggilan API dibungkus try...catch dengan penanganan error yang konsisten',
                    'Kode async terstruktur rapi — tidak ada callback bersarang (callback hell)',
                    'Tampilan responsif di layar HP dan desktop',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi API cuaca yang digunakan dan cara setup API key',
                ],
            ],

            'Assignment 2: API Data Fetcher' => [
                'title' => 'Tantangan Mini Project: Dashboard Data dari API Publik',
                'brief' => 'Kembangkan Latihan Coding array methods sebelumnya jadi mini dashboard yang mengambil data dari satu API publik (misalnya data negara, produk, atau pengguna), lalu diolah dan ditampilkan dalam bentuk yang lebih berguna dari sekadar list mentah.',
                'objectives' => [
                    'Ambil data dari API publik dan olah memakai kombinasi map, filter, dan reduce (misalnya untuk statistik ringkas)',
                    'Pisahkan logic fetching data dan logic tampilan ke dalam module terpisah (ES Modules)',
                    'Sediakan minimal satu fitur filter/sortir data di sisi client',
                ],
                'acceptance_criteria' => [
                    'Data yang tampil sudah diolah (bukan sekadar ditampilkan mentah dari response API)',
                    'Struktur project memakai import/export antar file dengan jelas, tidak semua logic ditumpuk di satu file',
                    'Fitur filter/sortir berfungsi dan memperbarui tampilan tanpa reload halaman',
                    'Error dari API (gagal fetch, response kosong) ditangani dan ditampilkan ke pengguna',
                    'Destructuring dipakai secara wajar untuk mengakses properti object dari response API',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi API publik yang dipakai dan fitur olah data yang diimplementasikan',
                ],
            ],

            'Assignment 3: Module Refactor Exercise' => [
                'title' => 'Tantangan Mini Project: Refactor Aplikasi Jadi Modular',
                'brief' => 'Ambil salah satu proyek JavaScript kamu sebelumnya (boleh dari Latihan Coding modul ini atau modul lain) yang masih ditulis dalam satu file panjang, lalu refactor total jadi struktur modular memakai ES Modules, arrow function, dan destructuring — tanpa mengubah fungsionalitasnya.',
                'objectives' => [
                    'Pisahkan kode monolitik menjadi beberapa file module dengan tanggung jawab yang jelas (misal: utils, api, ui)',
                    'Ganti function declaration yang cocok dengan arrow function untuk kode yang lebih ringkas',
                    'Sederhanakan pengaksesan object/array memakai destructuring dan spread/rest operator',
                ],
                'acceptance_criteria' => [
                    'Fungsionalitas aplikasi sebelum dan sesudah refactor identik — tidak ada fitur yang rusak',
                    'Setiap module punya satu tanggung jawab yang jelas, tidak ada module yang "menampung semuanya"',
                    'Import/export antar module konsisten (default vs named export dipakai dengan tepat)',
                    'Tidak ada duplikasi kode yang seharusnya bisa digabung jadi satu function/module',
                    'Disertakan catatan singkat before/after yang menjelaskan perubahan struktur',
                ],
                'deliverables' => [
                    'Link repository GitHub (idealnya branch/commit terpisah untuk versi sebelum dan sesudah refactor)',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi ringkasan perubahan struktur yang dilakukan',
                ],
            ],

            // ============================================================
            // ---------- Modul 3: React Essentials ----------
            // ============================================================
            'Assignment 1: Todo App with React' => [
                'title' => 'Tantangan Mini Project: Aplikasi Todo React dengan Kategori',
                'brief' => 'Kembangkan Latihan Coding Todo React sebelumnya jadi aplikasi todo yang mendukung kategori/label dan filter status (semua/aktif/selesai), murni dengan React state dan tanpa reload halaman.',
                'objectives' => [
                    'Tambahkan kategori/label pada setiap tugas (misalnya Kerja, Pribadi, Kuliah)',
                    'Implementasikan filter untuk menampilkan tugas berdasarkan status dan/atau kategori',
                    'Gunakan conditional rendering untuk menampilkan pesan saat daftar tugas kosong',
                ],
                'acceptance_criteria' => [
                    'Setiap item list menggunakan key yang unik dan stabil (bukan index array)',
                    'Filter status dan kategori bisa dikombinasikan dan langsung memperbarui tampilan',
                    'State dikelola di komponen yang tepat (tidak ada duplikasi state yang saling tidak sinkron)',
                    'Struktur komponen dipecah dengan wajar (misal TodoList, TodoItem, FilterBar terpisah)',
                    'Tampilan responsif di layar HP dan desktop',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi daftar fitur filter/kategori yang diimplementasikan',
                ],
            ],

            'Assignment 2: Movie Search App' => [
                'title' => 'Tantangan Mini Project: Aplikasi Pencarian Film dengan Favorit',
                'brief' => 'Kembangkan Latihan Coding pencarian film sebelumnya jadi aplikasi yang lebih lengkap: pengguna bisa mencari film, melihat detail singkat, dan menyimpan film favorit selama sesi berjalan.',
                'objectives' => [
                    'Implementasikan pencarian film berbasis kata kunci memakai useEffect dan Fetch API',
                    'Tampilkan status loading dan error secara jelas selama proses pencarian',
                    'Sediakan fitur tandai/hapus film sebagai favorit yang tersimpan di state aplikasi',
                ],
                'acceptance_criteria' => [
                    'Dependency array useEffect diatur dengan tepat, tidak menyebabkan fetch berulang tanpa henti',
                    'Pencarian dengan kata kunci kosong atau hasil tidak ditemukan ditangani dengan pesan yang jelas',
                    'Daftar favorit terpisah dari hasil pencarian dan tetap konsisten saat berpindah antar tampilan',
                    'Key pada list film unik dan stabil',
                    'Struktur komponen dipecah dengan wajar (SearchBar, MovieList, MovieCard, FavoritesList)',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi API film yang digunakan',
                ],
            ],

            'Assignment 3: Multi-step Form' => [
                'title' => 'Tantangan Mini Project: Form Pendaftaran Multi-Langkah',
                'brief' => 'Kembangkan Latihan Coding multi-step form sebelumnya jadi form pendaftaran lengkap minimal 3 langkah (misalnya: Data Diri, Data Kontak, Konfirmasi), dengan validasi di setiap langkah sebelum lanjut ke langkah berikutnya.',
                'objectives' => [
                    'Bangun minimal 3 langkah form dengan data yang tersinkronisasi di satu state utama',
                    'Terapkan validasi wajib isi di setiap langkah sebelum tombol Lanjut bisa ditekan',
                    'Tampilkan halaman ringkasan/konfirmasi berisi seluruh data sebelum submit',
                ],
                'acceptance_criteria' => [
                    'Data yang sudah diisi tidak hilang saat pengguna kembali ke langkah sebelumnya',
                    'Validasi mencegah pengguna lanjut ke step berikutnya jika field wajib belum terisi',
                    'Halaman konfirmasi menampilkan seluruh data yang sudah diinput dengan akurat',
                    'Navigasi antar step (Lanjut/Kembali) berfungsi tanpa reload halaman',
                    'Struktur kode rapi — logic per step tidak ditumpuk dalam satu komponen raksasa',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi alur step form yang dibangun',
                ],
            ],

            'Assignment 4: Shopping Cart' => [
                'title' => 'Tantangan Mini Project: Aplikasi Keranjang Belanja',
                'brief' => 'Kembangkan Latihan Coding keranjang belanja sebelumnya jadi mini aplikasi katalog produk lengkap dengan keranjang: pengguna bisa menambah produk ke keranjang, mengubah jumlah, menghapus item, dan melihat total harga secara real-time.',
                'objectives' => [
                    'Tampilkan daftar produk dan implementasikan fitur tambah ke keranjang',
                    'Kelola state keranjang: ubah jumlah item, hapus item, dan hitung total harga otomatis',
                    'Tampilkan ringkasan keranjang (jumlah item dan total harga) yang selalu update',
                ],
                'acceptance_criteria' => [
                    'Total harga selalu akurat setelah menambah, mengubah jumlah, atau menghapus item',
                    'Key pada list produk dan item keranjang unik dan stabil',
                    'Keranjang kosong menampilkan pesan yang jelas, bukan tampilan kosong tanpa keterangan',
                    'State keranjang dikelola di level yang tepat agar konsisten antar komponen (list produk dan ringkasan keranjang)',
                    'Tampilan responsif di layar HP dan desktop',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi daftar fitur keranjang yang diimplementasikan',
                ],
            ],

            'Assignment 5: Component Library' => [
                'title' => 'Tantangan Mini Project: Membangun Component Library Sendiri',
                'brief' => 'Kembangkan Latihan Coding komponen reusable sebelumnya jadi kumpulan minimal 5 komponen UI reusable (misalnya Button, Card, Modal, Input, Badge) dengan props yang fleksibel dan konsisten, lengkap dengan halaman demo penggunaannya.',
                'objectives' => [
                    'Bangun minimal 5 komponen UI reusable dengan props yang jelas (variant, size, dsb.)',
                    'Pastikan setiap komponen konsisten dalam gaya visual dan pola penamaan props',
                    'Buat halaman demo yang menampilkan seluruh varian dari setiap komponen',
                ],
                'acceptance_criteria' => [
                    'Setiap komponen bisa dipakai ulang di konteks berbeda tanpa perlu diubah isinya',
                    'Props API antar komponen konsisten (misal semua pakai nama "variant" bukan campuran "type"/"kind")',
                    'Ada default props yang wajar sehingga komponen tetap tampil baik meski sebagian props tidak diisi',
                    'Halaman demo menampilkan seluruh kombinasi varian yang tersedia untuk setiap komponen',
                    'Dokumentasi singkat (komentar atau README) menjelaskan props yang tersedia untuk tiap komponen',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi daftar komponen dan props masing-masing',
                ],
            ],

            // ============================================================
            // ---------- Modul 4: TypeScript for React ----------
            // ============================================================
            'Assignment 1: Convert JS Project to TS' => [
                'title' => 'Tantangan Mini Project: Migrasi Proyek React ke TypeScript',
                'brief' => 'Ambil salah satu proyek React JavaScript kamu sebelumnya (boleh dari modul React Essentials) dan migrasikan sepenuhnya ke TypeScript, lengkap dengan tipe data yang tepat untuk seluruh props, state, dan fungsi.',
                'objectives' => [
                    'Konversi seluruh file .jsx/.js menjadi .tsx/.ts dengan konfigurasi tsconfig.json yang sesuai',
                    'Definisikan interface/type untuk seluruh props dan state komponen',
                    'Hilangkan penggunaan tipe "any" kecuali benar-benar tidak terhindarkan, dengan justifikasi',
                ],
                'acceptance_criteria' => [
                    'Proyek berhasil di-build tanpa error TypeScript',
                    'Seluruh props komponen memiliki interface/type yang jelas, bukan implicit any',
                    'Fungsionalitas aplikasi setelah migrasi identik dengan versi JavaScript sebelumnya',
                    'tsconfig.json dikonfigurasi dengan strict mode aktif',
                    'Penggunaan "any" (jika ada) disertai komentar alasan kenapa diperlukan',
                ],
                'deliverables' => [
                    'Link repository GitHub (idealnya branch terpisah untuk versi sebelum dan sesudah migrasi)',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi ringkasan proses migrasi dan kendala yang ditemui',
                ],
            ],

            'Assignment 2: Typed Form Component' => [
                'title' => 'Tantangan Mini Project: Sistem Form Bertipe dengan TypeScript',
                'brief' => 'Kembangkan Latihan Coding form bertipe sebelumnya jadi sistem form yang lebih lengkap (misalnya form registrasi dengan beberapa jenis input: text, select, checkbox), dengan seluruh props, state, dan event handler diberi tipe yang tepat.',
                'objectives' => [
                    'Definisikan interface untuk state form dan props tiap komponen input',
                    'Beri tipe pada seluruh event handler (React.ChangeEvent, React.FormEvent, dsb.) sesuai jenis elemennya',
                    'Implementasikan validasi form dengan pesan error yang tipenya juga terdefinisi jelas',
                ],
                'acceptance_criteria' => [
                    'Semua field form memiliki interface/type yang jelas, tidak ada implicit any pada state',
                    'Event handler diberi tipe yang sesuai dengan elemen HTML yang dipakai (input, select, textarea)',
                    'Validasi berjalan dan pesan error ditampilkan dengan tipe data yang konsisten',
                    'Komponen form reusable dan bisa dipakai untuk lebih dari satu jenis form dengan props berbeda',
                    'Proyek berhasil di-build tanpa error TypeScript',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi daftar interface/type yang didefinisikan',
                ],
            ],

            'Assignment 3: Typed API Client' => [
                'title' => 'Tantangan Mini Project: API Client Generic dengan TypeScript',
                'brief' => 'Kembangkan Latihan Coding API client sebelumnya jadi modul API client generic yang bisa dipakai untuk memanggil minimal 2 endpoint berbeda dengan bentuk response yang berbeda pula, tetap dengan keamanan tipe penuh.',
                'objectives' => [
                    'Bangun fungsi fetch generic (misal fetchData<T>) yang bisa dipakai ulang untuk berbagai endpoint',
                    'Definisikan type/interface untuk setiap bentuk response API yang dipanggil',
                    'Tangani error API dengan tipe data yang jelas (misal ApiError) alih-alih any',
                ],
                'acceptance_criteria' => [
                    'Fungsi API client generic berhasil dipakai untuk minimal 2 endpoint dengan tipe response berbeda',
                    'Tidak ada penggunaan any pada hasil response yang dipakai di komponen',
                    'Error dari API ditangani dengan tipe yang terdefinisi dan pesan yang jelas ke pengguna',
                    'Proyek berhasil di-build tanpa error TypeScript',
                    'Struktur kode memisahkan layer API client dari layer komponen UI',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi daftar endpoint yang dipakai dan tipe response-nya',
                ],
            ],

            // ============================================================
            // ---------- Modul 5: Advanced React Patterns ----------
            // ============================================================
            'Assignment 1: Custom Hook Library' => [
                'title' => 'Tantangan Mini Project: Kumpulan Custom Hook Reusable',
                'brief' => 'Kembangkan Latihan Coding custom hook sebelumnya jadi kumpulan minimal 3 custom hook reusable (misalnya useFetch, useLocalStorage, useDebounce), lengkap dengan halaman demo yang menunjukkan penggunaan nyata masing-masing hook.',
                'objectives' => [
                    'Bangun minimal 3 custom hook dengan tanggung jawab yang jelas dan terpisah satu sama lain',
                    'Pastikan setiap hook bisa dipakai ulang di komponen berbeda tanpa duplikasi logic',
                    'Buat halaman demo yang menunjukkan penggunaan nyata masing-masing hook',
                ],
                'acceptance_criteria' => [
                    'Setiap custom hook berfungsi independen dan tidak bergantung pada struktur komponen tertentu',
                    'Tidak ada duplikasi logic antara custom hook dan komponen yang memakainya',
                    'Halaman demo menunjukkan skenario penggunaan nyata (bukan sekadar console.log)',
                    'Penamaan hook mengikuti konvensi React (diawali "use")',
                    'Dokumentasi singkat menjelaskan parameter dan return value tiap hook',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi daftar hook dan cara pakainya',
                ],
            ],

            'Assignment 2: State Management App' => [
                'title' => 'Tantangan Mini Project: Aplikasi dengan Context API & useReducer',
                'brief' => 'Kembangkan Latihan Coding Context API/useReducer sebelumnya jadi aplikasi dengan state yang cukup kompleks (misalnya keranjang belanja atau manajemen tugas dengan beberapa jenis aksi), dikelola sepenuhnya tanpa prop drilling.',
                'objectives' => [
                    'Pindahkan state global aplikasi ke Context API agar bisa diakses tanpa prop drilling',
                    'Kelola perubahan state memakai useReducer dengan minimal 4 jenis action berbeda',
                    'Pisahkan reducer, actions, dan context provider ke file yang terstruktur',
                ],
                'acceptance_criteria' => [
                    'Tidak ada prop drilling untuk data yang dikelola lewat Context API',
                    'Setiap action pada reducer terdefinisi jelas dan menghasilkan perubahan state yang benar',
                    'Struktur folder memisahkan context, reducer, dan komponen UI dengan rapi',
                    'State kompleks (misal array of objects) diperbarui secara immutable, tidak memutasi state langsung',
                    'Aplikasi tetap responsif dan tidak ada re-render yang tidak perlu secara berlebihan',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi daftar action yang tersedia pada reducer',
                ],
            ],

            'Assignment 3: Performance Demo' => [
                'title' => 'Tantangan Mini Project: Studi Kasus Optimasi Performa React',
                'brief' => 'Kembangkan Latihan Coding useMemo/useCallback sebelumnya jadi studi kasus perbandingan performa: bangun satu fitur (misalnya list dengan filter/search berat) dalam dua versi — sebelum dan sesudah dioptimasi — lalu dokumentasikan perbedaannya.',
                'objectives' => [
                    'Identifikasi bagian aplikasi yang mengalami re-render atau komputasi berlebihan',
                    'Terapkan useMemo dan useCallback secara tepat sasaran, bukan di semua tempat',
                    'Ukur dan dokumentasikan perbedaan performa sebelum dan sesudah optimasi (misal lewat React DevTools Profiler)',
                ],
                'acceptance_criteria' => [
                    'Optimasi diterapkan hanya pada bagian yang benar-benar terbukti bermasalah, disertai penjelasan alasannya',
                    'Ada bukti terukur (screenshot profiler, angka render count, dsb.) yang menunjukkan perbaikan performa',
                    'Fungsionalitas fitur tetap sama antara versi sebelum dan sesudah optimasi',
                    'Kode versi optimized tetap mudah dibaca, tidak jadi rumit tanpa alasan jelas',
                    'Disertakan penjelasan kapan optimasi semacam ini sebaiknya TIDAK dipakai',
                ],
                'deliverables' => [
                    'Link repository GitHub (idealnya branch terpisah before/after optimasi)',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi hasil pengukuran performa before/after',
                ],
            ],

            'Assignment 4: Testing Suite' => [
                'title' => 'Tantangan Mini Project: Test Suite untuk Aplikasi React',
                'brief' => 'Ambil salah satu proyek React kamu sebelumnya (boleh dari modul React Essentials) dan tulis test suite yang cukup lengkap memakai React Testing Library, menguji interaksi pengguna dari sudut pandang pengguna, bukan detail implementasi internal.',
                'objectives' => [
                    'Tulis test untuk minimal 3 fitur utama aplikasi (misalnya render awal, tambah data, hapus data)',
                    'Simulasikan interaksi pengguna nyata (klik, ketik, submit) memakai React Testing Library',
                    'Sertakan pengujian untuk skenario edge case (input kosong, data tidak ditemukan, dsb.)',
                ],
                'acceptance_criteria' => [
                    'Seluruh test yang ditulis berhasil passing',
                    'Test menguji perilaku yang terlihat pengguna (teks di layar, elemen muncul/hilang), bukan detail state internal',
                    'Nama setiap test jelas dan menggambarkan skenario yang diuji',
                    'Minimal satu edge case (input kosong, data tidak ada, dsb.) diuji dan ditangani dengan benar',
                    'Struktur file test rapi dan konsisten (misal mengikuti pola *.test.tsx di samping komponennya)',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Link live demo (Netlify/Vercel) — opsional',
                    'File ZIP source code, sertakan README singkat berisi cara menjalankan test dan ringkasan cakupan test',
                ],
            ],
        ];
    }
}