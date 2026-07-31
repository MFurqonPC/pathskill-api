<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MiniProject;
use Illuminate\Database\Seeder;

class MiniProjectBackendSeeder extends Seeder
{
    /**
     * Mini project untuk seluruh assignment praktik di track Backend
     * Developer (Modul 1-7). Judul assignment di sini HARUS PERSIS sama
     * dengan yang dibuat AddAssignmentsToExistingModulesSeeder (format
     * "Assignment N: {title}") — jalankan seeder itu DULU sebelum ini.
     *
     * Jalankan setelah AddAssignmentsToExistingModulesSeeder,
     * AssignmentDetailSeeder & CodingExerciseBackendSeeder (assignment-nya
     * harus sudah ada). Idempotent lewat updateOrCreate berdasarkan
     * assignment_id, aman dijalankan berkali-kali.
     *
     * Jalankan:
     *   php artisan db:seed --class=MiniProjectBackendSeeder
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

        $this->command?->info("MiniProjectBackendSeeder: {$created} mini project berhasil dibuat/diperbarui.");

        if (! empty($skipped)) {
            $this->command?->warn(
                'MiniProjectBackendSeeder: assignment tidak ditemukan (jalankan AddAssignmentsToExistingModulesSeeder dulu) — '
                . implode(', ', $skipped)
            );
        }
    }

    private function projectData(): array
    {
        return [

            // ============================================================
            // ---------- Modul 1: Node.js Fundamentals ----------
            // ============================================================
            'Assignment 1: Static File Server with Node.js' => [
                'title' => 'Tantangan Mini Project: Static File Server Multi-Tipe',
                'brief' => 'Kembangkan Latihan Coding static server sebelumnya jadi HTTP server yang lebih lengkap: mampu menyajikan berbagai tipe file (HTML, CSS, JS, gambar) dengan MIME type yang benar, plus halaman 404 kustom, tanpa memakai framework apa pun.',
                'objectives' => [
                    'Sajikan minimal 4 jenis file berbeda (HTML, CSS, JS, gambar) dengan Content-Type yang sesuai',
                    'Tangani request ke file yang tidak ada dengan halaman 404 kustom, bukan crash server',
                    'Gunakan fs dan path secara aman untuk mencegah directory traversal (akses file di luar folder publik)',
                ],
                'acceptance_criteria' => [
                    'Header Content-Type sesuai dengan ekstensi file yang diminta',
                    'Request ke file yang tidak ada mengembalikan status 404 dengan halaman kustom, bukan error mentah Node.js',
                    'Server tidak bisa diakses untuk membaca file di luar folder publik (misal lewat "../../")',
                    'Pembacaan file dilakukan secara asynchronous, tidak memblokir request lain',
                    'Struktur kode terpisah dengan jelas antara logic routing dan logic pembacaan file',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'File ZIP source code, sertakan README singkat berisi cara menjalankan server dan daftar tipe file yang didukung',
                ],
            ],

            'Assignment 2: Async Task Queue Simulator' => [
                'title' => 'Tantangan Mini Project: Simulator Antrean Tugas Asynchronous',
                'brief' => 'Kembangkan Latihan Coding simulasi Event Loop sebelumnya jadi task queue simulator yang lebih realistis: mendukung prioritas tugas dan batas jumlah tugas yang berjalan bersamaan (concurrency limit), dengan log eksekusi yang jelas urutannya.',
                'objectives' => [
                    'Implementasikan antrean tugas dengan prioritas (misal high/normal/low) menggunakan Promise/setTimeout',
                    'Terapkan batas concurrency — maksimal N tugas berjalan bersamaan, sisanya menunggu di antrean',
                    'Konfigurasikan parameter simulasi (jumlah tugas, concurrency limit, dsb.) lewat file .env',
                ],
                'acceptance_criteria' => [
                    'Tugas dengan prioritas lebih tinggi dieksekusi lebih dulu dibanding prioritas rendah pada antrean yang sama',
                    'Jumlah tugas yang berjalan bersamaan tidak pernah melebihi batas concurrency yang ditentukan',
                    'Log output menunjukkan urutan mulai/selesai tiap tugas dengan jelas dan mudah ditelusuri',
                    'Parameter simulasi bisa diubah lewat .env tanpa mengubah kode program',
                    'package.json dan struktur folder tertata rapi',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'File ZIP source code, sertakan README singkat berisi cara mengubah parameter simulasi lewat .env',
                ],
            ],

            // ============================================================
            // ---------- Modul 2: Membangun REST API ----------
            // ============================================================
            'Assignment 1: Task Manager REST API' => [
                'title' => 'Tantangan Mini Project: Task Manager REST API Lengkap',
                'brief' => 'Kembangkan Latihan Coding REST API dasar sebelumnya jadi Task Manager API yang lebih lengkap: mendukung kategori tugas, due date, dan filter berdasarkan status, dengan middleware logging dan parsing body yang konsisten.',
                'objectives' => [
                    'Sediakan endpoint CRUD lengkap (GET, POST, PUT, DELETE) untuk resource tugas dengan kategori dan due date',
                    'Implementasikan filter dan pagination sederhana pada endpoint GET list tugas',
                    'Terapkan middleware untuk logging tiap request (method, path, waktu proses)',
                ],
                'acceptance_criteria' => [
                    'Seluruh endpoint CRUD berfungsi dan mengembalikan status code yang sesuai (200, 201, 404, dsb.)',
                    'Filter berdasarkan status/kategori dan pagination bekerja dengan benar pada endpoint list',
                    'Middleware logging mencatat setiap request tanpa mengganggu response utama',
                    'Struktur response konsisten di seluruh endpoint (format sukses maupun error sama)',
                    'Validasi dasar mencegah data tugas tanpa judul tersimpan ke database',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Koleksi Postman/Insomnia berisi seluruh endpoint',
                    'File ZIP source code, sertakan README singkat berisi daftar endpoint dan contoh request/response',
                ],
            ],

            'Assignment 2: Book Catalog API' => [
                'title' => 'Tantangan Mini Project: Book Catalog API dengan Validasi',
                'brief' => 'Kembangkan Latihan Coding penggunaan params/query/body sebelumnya jadi Book Catalog API yang lebih lengkap: mendukung pencarian judul lewat query, validasi input yang ketat, dan pesan error yang jelas untuk setiap kasus gagal.',
                'objectives' => [
                    'Sediakan endpoint pencarian buku berdasarkan judul/penulis lewat query parameter',
                    'Terapkan validasi input (judul wajib, tahun terbit harus angka, dsb.) sebelum data disimpan',
                    'Tangani error dengan status code dan pesan yang informatif untuk setiap kasus (400, 404, 422)',
                ],
                'acceptance_criteria' => [
                    'Pencarian buku via query parameter mengembalikan hasil yang sesuai kata kunci',
                    'Data yang tidak valid (judul kosong, tahun bukan angka) ditolak dengan pesan error yang jelas',
                    'req.params, req.query, dan req.body dipakai sesuai kebutuhan masing-masing endpoint, tidak tertukar',
                    'Buku yang tidak ditemukan mengembalikan 404 dengan pesan yang jelas, bukan array kosong tanpa keterangan',
                    'Struktur kode rapi dan konsisten di seluruh endpoint',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Koleksi Postman/Insomnia berisi seluruh endpoint termasuk skenario gagal',
                    'File ZIP source code, sertakan README singkat berisi aturan validasi yang diterapkan',
                ],
            ],

            'Assignment 3: API Structure Refactor' => [
                'title' => 'Tantangan Mini Project: Refactor API ke Struktur Routes/Controllers/Services',
                'brief' => 'Ambil salah satu API kamu sebelumnya (Task Manager atau Book Catalog) yang masih menumpuk semua logic dalam satu file, lalu refactor total jadi struktur routes/controllers/services dengan separation of concerns yang jelas.',
                'objectives' => [
                    'Pisahkan endpoint yang menumpuk jadi layer routes, controllers, dan services yang jelas tanggung jawabnya',
                    'Pastikan layer service tidak bergantung pada objek request/response Express (murni logic bisnis)',
                    'Dokumentasikan struktur folder baru agar mudah dipahami anggota tim lain',
                ],
                'acceptance_criteria' => [
                    'Routes hanya menangani pemetaan endpoint, controllers menangani request/response, services menangani logic bisnis',
                    'Fungsionalitas API sebelum dan sesudah refactor identik — tidak ada endpoint yang rusak',
                    'Layer service bisa dipanggil/diuji tanpa perlu menjalankan server HTTP',
                    'Penamaan file dan folder konsisten mengikuti satu konvensi',
                    'README menjelaskan struktur folder baru dan tanggung jawab tiap layer',
                ],
                'deliverables' => [
                    'Link repository GitHub (idealnya branch/commit terpisah sebelum dan sesudah refactor)',
                    'File ZIP source code, sertakan README singkat berisi penjelasan struktur folder baru',
                ],
            ],

            // ============================================================
            // ---------- Modul 3: Database SQL & NoSQL ----------
            // ============================================================
            'Assignment 1: Library Database Schema Design' => [
                'title' => 'Tantangan Mini Project: Rancangan Skema Database Perpustakaan',
                'brief' => 'Kembangkan Latihan Coding rancangan skema sebelumnya jadi skema database perpustakaan yang lebih lengkap: mencakup buku, anggota, peminjaman, dan denda keterlambatan, dengan relasi yang ternormalisasi dengan baik.',
                'objectives' => [
                    'Rancang minimal 4 tabel (buku, anggota, peminjaman, denda) dengan relasi yang tepat',
                    'Terapkan normalisasi untuk menghindari duplikasi data (misal data anggota tidak diulang di setiap peminjaman)',
                    'Buat ERD yang menggambarkan seluruh relasi antar tabel secara visual',
                ],
                'acceptance_criteria' => [
                    'Setiap tabel punya primary key dan foreign key yang tepat untuk merepresentasikan relasinya',
                    'Skema minimal memenuhi normalisasi tingkat 3NF pada kasus-kasus umum (tidak ada data yang berulang tanpa alasan)',
                    'Tipe data tiap kolom sesuai dengan jenis datanya (misal tanggal pakai DATE, bukan VARCHAR)',
                    'ERD didokumentasikan dengan jelas dan bisa dibaca tanpa penjelasan tambahan',
                    'Skema mempertimbangkan skenario nyata: satu buku bisa dipinjam banyak anggota di waktu berbeda',
                ],
                'deliverables' => [
                    'File ERD (gambar/export dari dbdiagram.io atau MySQL Workbench)',
                    'File SQL berisi statement CREATE TABLE lengkap',
                    'File ZIP/README singkat berisi penjelasan keputusan desain skema',
                ],
            ],

            'Assignment 2: Blog CRUD with MySQL' => [
                'title' => 'Tantangan Mini Project: Blog CRUD dengan Kategori dan Komentar',
                'brief' => 'Kembangkan Latihan Coding CRUD blog sebelumnya jadi sistem blog yang lebih lengkap: mendukung kategori artikel dan komentar pembaca, dengan query SQL yang aman dan efisien.',
                'objectives' => [
                    'Rancang dan implementasikan tabel artikel, kategori, dan komentar dengan relasi yang tepat',
                    'Tulis query untuk operasi CRUD lengkap pada artikel, termasuk join dengan kategori dan komentar',
                    'Terapkan query yang aman — selalu ada klausa WHERE yang jelas pada UPDATE/DELETE',
                ],
                'acceptance_criteria' => [
                    'Query CRUD artikel, kategori, dan komentar berjalan dengan benar tanpa merusak data lain',
                    'Setiap UPDATE/DELETE memiliki klausa WHERE yang spesifik (tidak ada risiko menimpa seluruh tabel)',
                    'Query untuk menampilkan artikel beserta kategorinya menggunakan JOIN, bukan query terpisah berulang',
                    'Query terdokumentasi dengan komentar yang menjelaskan tujuannya',
                    'Tidak ada duplikasi data kategori akibat desain tabel yang kurang tepat',
                ],
                'deliverables' => [
                    'File SQL berisi struktur tabel dan seluruh query CRUD',
                    'Link repository GitHub (jika diimplementasikan dengan script/aplikasi)',
                    'File ZIP/README singkat berisi penjelasan skema dan contoh query',
                ],
            ],

            'Assignment 3: Product Catalog with MongoDB' => [
                'title' => 'Tantangan Mini Project: Katalog Produk Fleksibel dengan MongoDB',
                'brief' => 'Kembangkan Latihan Coding MongoDB sebelumnya jadi katalog produk dengan atribut yang bervariasi antar kategori (misalnya baju punya ukuran/warna, elektronik punya spesifikasi teknis), memanfaatkan fleksibilitas struktur document.',
                'objectives' => [
                    'Rancang struktur document yang mendukung atribut berbeda-beda antar kategori produk',
                    'Implementasikan operasi CRUD lengkap untuk produk memakai MongoDB',
                    'Sediakan query pencarian/filter produk berdasarkan kategori dan salah satu atributnya',
                ],
                'acceptance_criteria' => [
                    'Document produk bisa menyimpan atribut berbeda antar kategori tanpa kolom kosong yang tidak perlu (khas SQL)',
                    'Operasi CRUD produk berjalan dengan benar melalui MongoDB Compass atau driver Node.js',
                    'Query filter berdasarkan kategori dan atribut spesifik mengembalikan hasil yang tepat',
                    'Struktur document didesain dengan mempertimbangkan pola akses data yang umum (bukan asal simpan)',
                    'Disertakan penjelasan singkat kenapa NoSQL lebih cocok untuk kasus ini dibanding SQL',
                ],
                'deliverables' => [
                    'File export koleksi MongoDB (JSON) berisi contoh data produk',
                    'Link repository GitHub (jika diimplementasikan dengan script/aplikasi)',
                    'File ZIP/README singkat berisi struktur document dan justifikasi pemilihan NoSQL',
                ],
            ],

            // ============================================================
            // ---------- Modul 4: Authentication & Security ----------
            // ============================================================
            'Assignment 1: JWT Authentication System' => [
                'title' => 'Tantangan Mini Project: Sistem Autentikasi JWT Lengkap',
                'brief' => 'Kembangkan Latihan Coding JWT sebelumnya jadi sistem autentikasi lengkap: register, login, dan endpoint yang dilindungi token, dengan password di-hash menggunakan bcrypt dan secret key dikelola lewat .env.',
                'objectives' => [
                    'Implementasikan endpoint register dengan hashing password memakai bcrypt sebelum disimpan',
                    'Implementasikan endpoint login yang menghasilkan JWT saat kredensial valid',
                    'Sediakan minimal satu endpoint terproteksi yang hanya bisa diakses dengan token valid',
                ],
                'acceptance_criteria' => [
                    'Password tidak pernah disimpan dalam bentuk plain text di database',
                    'Login dengan kredensial salah mengembalikan pesan error yang jelas, bukan token',
                    'Endpoint terproteksi menolak akses tanpa token atau dengan token tidak valid/kadaluarsa',
                    'Secret key JWT disimpan di .env, tidak di-hardcode di kode program',
                    'Struktur kode memisahkan logic autentikasi dari logic bisnis lainnya',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Koleksi Postman/Insomnia berisi skenario register, login, dan akses endpoint terproteksi',
                    'File ZIP source code, sertakan README singkat berisi cara setup .env',
                ],
            ],

            'Assignment 2: Secure Login API' => [
                'title' => 'Tantangan Mini Project: API dengan Middleware Autentikasi',
                'brief' => 'Kembangkan Latihan Coding middleware autentikasi sebelumnya jadi API dengan beberapa endpoint yang levelnya berbeda (publik, hanya login, khusus admin), lengkap dengan sanitasi input dasar.',
                'objectives' => [
                    'Bangun middleware autentikasi yang melindungi endpoint sesuai levelnya (publik/login/admin)',
                    'Terapkan sanitasi input dasar untuk mencegah data berbahaya (misal karakter script) masuk ke sistem',
                    'Kembalikan status 401/403 yang tepat sesuai jenis pelanggaran akses',
                ],
                'acceptance_criteria' => [
                    'Endpoint publik bisa diakses tanpa login, endpoint lain menolak akses tanpa token valid',
                    'Endpoint khusus admin menolak user biasa meski sudah login (403), bukan hanya mengecek token ada/tidak',
                    'Input yang mengandung karakter mencurigakan disaring/divalidasi sebelum diproses',
                    'Middleware autentikasi reusable dan bisa dipasang di banyak route tanpa duplikasi kode',
                    'Struktur kode rapi dan konsisten',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Koleksi Postman/Insomnia berisi skenario akses untuk tiap level user',
                    'File ZIP source code, sertakan README singkat berisi daftar endpoint dan level akses masing-masing',
                ],
            ],

            'Assignment 3: Rate-Limited Login Endpoint' => [
                'title' => 'Tantangan Mini Project: Login API dengan Proteksi Brute Force',
                'brief' => 'Kembangkan Latihan Coding rate limiting sebelumnya jadi endpoint login yang tahan terhadap percobaan brute force, lengkap dengan analisis singkat perbandingan pendekatan session-based vs token-based authentication.',
                'objectives' => [
                    'Terapkan rate limiting pada endpoint login (misal maksimal 5 percobaan per menit per IP)',
                    'Kembalikan pesan error yang informatif (status 429) saat batas percobaan terlampaui',
                    'Tulis analisis singkat trade-off session-based vs token-based authentication untuk kasus API ini',
                ],
                'acceptance_criteria' => [
                    'Percobaan login yang melebihi batas ditolak dengan status 429 dan pesan yang jelas',
                    'Rate limit tidak memblokir pengguna lain yang login dari IP berbeda',
                    'Batas dan durasi rate limit bisa dikonfigurasi (misal lewat .env), tidak hardcoded ketat',
                    'Analisis session vs token mencakup minimal 2 pertimbangan konkret (skalabilitas, revocation, dsb.)',
                    'Fungsionalitas login normal (di bawah batas) tetap berjalan seperti biasa',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'File ZIP source code, sertakan README singkat berisi hasil analisis session vs token',
                ],
            ],

            // ============================================================
            // ---------- Modul 5: Git & Collaboration Workflow ----------
            // ============================================================
            'Assignment 1: Team Branching Simulation' => [
                'title' => 'Tantangan Mini Project: Simulasi Alur Kerja Tim dengan Git',
                'brief' => 'Kembangkan Latihan Coding branching sebelumnya jadi simulasi alur kerja tim yang lebih realistis: minimal 3 branch fitur berbeda, salah satunya sengaja dibuat konflik dengan branch lain, lalu diselesaikan dengan benar.',
                'objectives' => [
                    'Buat minimal 3 branch fitur/fix dengan konvensi penamaan yang konsisten',
                    'Ciptakan dan selesaikan minimal 1 merge conflict yang disengaja di antara branch tersebut',
                    'Jaga riwayat commit tetap rapi dan deskriptif di sepanjang proses',
                ],
                'acceptance_criteria' => [
                    'Nama branch mengikuti konvensi yang jelas (misal feature/, fix/) dan konsisten',
                    'Merge conflict yang dibuat benar-benar terjadi dan diselesaikan dengan hasil akhir yang benar (bukan asal pilih salah satu sisi)',
                    'Riwayat commit menunjukkan proses kerja yang masuk akal (bukan satu commit besar "final")',
                    '.gitignore digunakan dengan tepat sehingga file yang tidak perlu tidak ikut ter-commit',
                    'Branch akhirnya digabung ke branch utama dengan riwayat yang bisa ditelusuri',
                ],
                'deliverables' => [
                    'Link repository GitHub (riwayat branch dan commit harus terlihat, jangan di-squash semua)',
                    'File ZIP/README singkat berisi ringkasan konflik yang dibuat dan cara menyelesaikannya',
                ],
            ],

            'Assignment 2: Pull Request Practice Repo' => [
                'title' => 'Tantangan Mini Project: Alur Kerja Pull Request Lengkap',
                'brief' => 'Kembangkan Latihan Coding Pull Request sebelumnya jadi repository latihan dengan alur PR yang lengkap: deskripsi PR yang jelas, proses review (boleh self-review dengan akun/branch kedua), hingga revisi dan merge.',
                'objectives' => [
                    'Buat minimal 2 Pull Request dengan deskripsi yang menjelaskan perubahan dan alasannya',
                    'Simulasikan proses review dengan minimal 1 komentar perbaikan yang ditindaklanjuti',
                    'Selesaikan proses dengan merge yang bersih setelah revisi selesai',
                ],
                'acceptance_criteria' => [
                    'Setiap PR memiliki deskripsi yang jelas: apa yang berubah dan kenapa perubahan itu dilakukan',
                    'Ada minimal satu review comment yang direspons dengan commit revisi tambahan, bukan diabaikan',
                    'Riwayat commit setelah revisi menunjukkan perbaikan yang relevan dengan komentar review',
                    'Proses merge dilakukan dengan benar (tidak ada conflict yang belum terselesaikan)',
                    'Branch fitur dihapus/dibersihkan setelah PR selesai di-merge',
                ],
                'deliverables' => [
                    'Link repository GitHub (Pull Request dan review comment harus terlihat di riwayat)',
                    'File ZIP/README singkat berisi ringkasan proses review dan revisi yang dilakukan',
                ],
            ],

            // ============================================================
            // ---------- Modul 6: Testing & Debugging Backend ----------
            // ============================================================
            'Assignment 1: Unit Test Suite for Utility Functions' => [
                'title' => 'Tantangan Mini Project: Unit Test Suite Lengkap untuk Modul Utilitas',
                'brief' => 'Kembangkan Latihan Coding unit test sebelumnya jadi test suite yang lebih lengkap untuk modul utilitas (misalnya perhitungan harga, diskon, validasi format), mencakup skenario normal maupun edge case.',
                'objectives' => [
                    'Tulis unit test untuk minimal 4 fungsi utilitas berbeda menggunakan Jest',
                    'Terapkan pola arrange-act-assert secara konsisten di setiap test',
                    'Cakup skenario edge case (input kosong, angka negatif, nilai batas, dsb.), tidak hanya kasus normal',
                ],
                'acceptance_criteria' => [
                    'Seluruh test yang ditulis berhasil passing',
                    'Setiap fungsi diuji dengan minimal 1 kasus normal dan 1 edge case',
                    'Nama test jelas menggambarkan skenario yang diuji (bukan "test1", "test2")',
                    'Assertion yang dipakai tepat sesuai jenis nilai yang diuji (misal toBeCloseTo untuk angka desimal)',
                    'Struktur file test rapi dan konsisten (misal *.test.js di samping file utilitasnya)',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'File ZIP source code, sertakan README singkat berisi cara menjalankan test dan ringkasan cakupan test',
                ],
            ],

            'Assignment 2: API Integration Test with Supertest' => [
                'title' => 'Tantangan Mini Project: Integration Test API dengan Logging',
                'brief' => 'Kembangkan Latihan Coding Supertest sebelumnya jadi integration test suite yang lebih lengkap untuk salah satu REST API kamu sebelumnya, ditambah logging terstruktur untuk mempermudah debugging.',
                'objectives' => [
                    'Tulis integration test untuk minimal 3 endpoint utama (termasuk skenario sukses dan gagal) memakai Supertest',
                    'Implementasikan logging terstruktur (misal dengan winston) untuk request dan error penting',
                    'Pastikan test berjalan tanpa perlu menyalakan server sungguhan (in-memory/test instance)',
                ],
                'acceptance_criteria' => [
                    'Seluruh integration test berhasil passing tanpa perlu server aktif secara manual',
                    'Setiap endpoint diuji minimal untuk skenario sukses dan satu skenario gagal (validasi/404)',
                    'Log terstruktur mencatat informasi yang berguna untuk debugging (bukan sekadar console.log biasa)',
                    'Test tidak saling bergantung satu sama lain (bisa dijalankan dalam urutan apa pun)',
                    'Struktur file test terpisah jelas dari kode aplikasi utama',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'File ZIP source code, sertakan README singkat berisi cara menjalankan test dan contoh output log',
                ],
            ],

            // ============================================================
            // ---------- Modul 7: Server Architecture & Performance ----------
            // ============================================================
            'Assignment 1: Refactor to Layered Architecture' => [
                'title' => 'Tantangan Mini Project: Refactor API ke Arsitektur Berlapis',
                'brief' => 'Ambil salah satu REST API kamu sebelumnya dan tata ulang total menjadi arsitektur berlapis (routes, controllers, services) yang jelas tanggung jawab tiap lapisannya, tanpa mengubah fungsionalitas.',
                'objectives' => [
                    'Pisahkan API menjadi layer routes, controllers, dan services dengan tanggung jawab yang jelas',
                    'Pastikan service layer tidak bergantung pada objek request/response Express',
                    'Dokumentasikan arsitektur baru agar mudah dipahami anggota tim',
                ],
                'acceptance_criteria' => [
                    'Setiap layer (routes/controllers/services) punya tanggung jawab yang jelas dan tidak tumpang tindih',
                    'Service layer bisa dipanggil/diuji secara terpisah tanpa perlu menjalankan HTTP server',
                    'Fungsionalitas API tetap berjalan identik setelah refactor',
                    'Penamaan file dan folder konsisten mengikuti satu konvensi di seluruh proyek',
                    'Dokumentasi arsitektur menjelaskan alur request dari routes hingga service',
                ],
                'deliverables' => [
                    'Link repository GitHub (idealnya branch terpisah sebelum/sesudah refactor)',
                    'File ZIP source code, sertakan README singkat berisi diagram/penjelasan arsitektur baru',
                ],
            ],

            'Assignment 2: Redis Caching Layer' => [
                'title' => 'Tantangan Mini Project: Implementasi Caching Redis pada API',
                'brief' => 'Kembangkan Latihan Coding Redis sebelumnya jadi implementasi caching yang lebih lengkap pada salah satu API kamu: cache untuk data yang jarang berubah, lengkap dengan strategi invalidasi saat data sumber diperbarui.',
                'objectives' => [
                    'Terapkan caching Redis pada minimal 1 endpoint GET yang datanya jarang berubah',
                    'Implementasikan strategi invalidasi cache saat data terkait diubah lewat endpoint lain (POST/PUT/DELETE)',
                    'Ukur dan dokumentasikan perbedaan response time sebelum dan sesudah caching',
                ],
                'acceptance_criteria' => [
                    'Endpoint yang di-cache mengembalikan data yang sama dengan sumber aslinya (tidak stale tanpa alasan)',
                    'Cache ter-invalidasi otomatis saat data sumber berubah, tidak menampilkan data basi',
                    'Ada bukti terukur (angka response time before/after) yang menunjukkan cache bekerja',
                    'Penanganan cache miss (data belum ada di cache) berjalan dengan benar, fallback ke database',
                    'Struktur kode caching terpisah rapi dari logic bisnis utama',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'File ZIP source code, sertakan README singkat berisi hasil pengukuran response time before/after caching',
                ],
            ],

            'Assignment 3: Health Check & Monitoring Endpoint' => [
                'title' => 'Tantangan Mini Project: Endpoint Health Check dengan API Versioning',
                'brief' => 'Kembangkan Latihan Coding health check sebelumnya jadi endpoint /health yang lebih lengkap: memverifikasi koneksi database, ditambah penerapan API versioning dasar (misal /api/v1) pada seluruh endpoint yang ada.',
                'objectives' => [
                    'Bangun endpoint /health yang mengecek status aplikasi dan koneksi database secara real-time',
                    'Terapkan API versioning dasar (misal prefix /api/v1) pada seluruh endpoint yang sudah dibuat sebelumnya',
                    'Pastikan format response konsisten di seluruh endpoint (termasuk endpoint health check)',
                ],
                'acceptance_criteria' => [
                    'Endpoint /health mengembalikan status berbeda saat koneksi database berhasil vs gagal',
                    'Seluruh endpoint utama diakses melalui path yang mengandung versi API (misal /api/v1/...)',
                    'Format response (sukses maupun error) konsisten mengikuti satu struktur di seluruh endpoint',
                    'Dokumentasi menjelaskan endpoint yang tersedia beserta versinya',
                    'Endpoint health check tidak membocorkan informasi sensitif (misal detail koneksi database)',
                ],
                'deliverables' => [
                    'Link repository GitHub',
                    'Koleksi Postman/Insomnia berisi seluruh endpoint dengan versioning',
                    'File ZIP source code, sertakan README singkat berisi daftar endpoint dan versinya',
                ],
            ],
        ];
    }
}