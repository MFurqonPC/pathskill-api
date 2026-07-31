<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

/**
 * Quiz untuk seluruh assignment career "DevOps Engineer"
 * (Modul 1-6: Linux Fundamentals, Docker & Containerization,
 * CI/CD Pipeline, Kubernetes & Orchestration, Monitoring & Logging,
 * Git & Infrastructure Workflow).
 *
 * Jalankan setelah LearningPathSeeder & AddAssignmentsToExistingModulesSeeder
 * (assignment-nya harus sudah ada). Idempotent: quiz di-updateOrCreate per
 * assignment, dan soal lama dihapus dulu sebelum di-recreate.
 */
class QuizDevOpsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->quizData() as $assignmentTitle => $data) {
            $assignment = Assignment::where('title', $assignmentTitle)->first();

            if (! $assignment) {
                $this->command?->warn("QuizDevOpsSeeder: assignment tidak ditemukan — {$assignmentTitle}");
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
            // MODUL 1: Linux Fundamentals
            // ============================================================
            'Assignment 1: Linux Server Setup Exercise' => [
                'title' => 'Quiz: Command Line, File Permission & systemd',
                'questions' => [
                    [
                        'question' => 'Kenapa penguasaan command line dianggap kebutuhan dasar, bukan sekadar preferensi, bagi seorang DevOps Engineer?',
                        'explanation' => 'Hampir seluruh proses otomasi, deployment, dan pengelolaan server dilakukan melalui terminal, baik secara langsung maupun lewat script — bukan lewat antarmuka grafis seperti pada sistem operasi desktop.',
                        'options' => [
                            ['text' => 'Hampir seluruh otomasi, deployment, dan pengelolaan server dilakukan lewat terminal', 'correct' => true],
                            ['text' => 'Server Linux tidak mendukung antarmuka grafis sama sekali', 'correct' => false],
                            ['text' => 'Command line hanya dipakai untuk tampilan yang lebih estetik', 'correct' => false],
                            ['text' => 'Distribusi Linux modern sudah tidak menyediakan terminal', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Perintah chmod 755 deploy.sh memberikan izin apa pada file tersebut?',
                        'explanation' => 'chmod 755 memberi izin read-write-execute (rwx) untuk owner, dan read-execute (r-x) untuk group serta others — pola umum untuk file script yang perlu dijalankan.',
                        'options' => [
                            ['text' => 'rwx untuk owner, r-x untuk group dan others', 'correct' => true],
                            ['text' => 'Hanya owner yang bisa membaca file, tidak ada yang bisa menjalankannya', 'correct' => false],
                            ['text' => 'Semua pengguna mendapat akses penuh tanpa batasan', 'correct' => false],
                            ['text' => 'File menjadi otomatis terenkripsi', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa kesalahan pengaturan permission dianggap penyebab umum masalah keamanan maupun kegagalan aplikasi di server?',
                        'explanation' => 'Permission yang terlalu longgar bisa membuka celah keamanan, sementara permission yang terlalu ketat bisa membuat proses tertentu (misalnya web server) gagal mengakses file yang dibutuhkannya.',
                        'options' => [
                            ['text' => 'Permission yang salah bisa membuka celah keamanan atau membuat proses gagal mengakses file', 'correct' => true],
                            ['text' => 'Permission tidak berpengaruh sama sekali terhadap keamanan sistem', 'correct' => false],
                            ['text' => 'Permission hanya relevan untuk file yang berukuran besar', 'correct' => false],
                            ['text' => 'Linux otomatis memperbaiki permission yang salah', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa fungsi systemd dalam mengelola layanan di server Linux modern?',
                        'explanation' => 'systemd adalah sistem inisialisasi yang mengatur bagaimana layanan dijalankan, dihentikan, atau di-restart secara otomatis, sehingga layanan seperti web server bisa dikonfigurasi untuk berjalan otomatis setiap kali server dinyalakan.',
                        'options' => [
                            ['text' => 'Mengatur bagaimana layanan dijalankan, dihentikan, atau di-restart secara otomatis', 'correct' => true],
                            ['text' => 'Menyimpan seluruh file konfigurasi aplikasi secara terenkripsi', 'correct' => false],
                            ['text' => 'Mengganti kebutuhan command line sepenuhnya dengan antarmuka grafis', 'correct' => false],
                            ['text' => 'Hanya dipakai untuk memantau penggunaan jaringan', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Perintah systemctl enable nginx berfungsi untuk apa?',
                        'explanation' => 'systemctl enable membuat sebuah layanan otomatis berjalan setiap kali server restart, berbeda dengan systemctl start yang hanya menjalankan layanan saat itu juga tanpa memastikan ia aktif lagi setelah reboot.',
                        'options' => [
                            ['text' => 'Membuat layanan otomatis jalan setiap kali server di-restart', 'correct' => true],
                            ['text' => 'Menghapus layanan nginx secara permanen dari server', 'correct' => false],
                            ['text' => 'Mengganti port yang dipakai nginx', 'correct' => false],
                            ['text' => 'Menampilkan log error dari nginx', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Automated Backup Shell Script' => [
                'title' => 'Quiz: Shell Scripting & Otomasi Dasar',
                'questions' => [
                    [
                        'question' => 'Apa prinsip utama DevOps yang mendasari kenapa shell scripting penting dipelajari?',
                        'explanation' => 'Salah satu prinsip utama DevOps adalah otomasi, yaitu mengganti pekerjaan manual yang berulang dengan script yang bisa dijalankan kapan saja secara konsisten — shell scripting adalah cara paling dasar buat mencapai ini di lingkungan Linux.',
                        'options' => [
                            ['text' => 'Otomasi — mengganti pekerjaan manual berulang dengan script yang konsisten', 'correct' => true],
                            ['text' => 'Menghindari penggunaan command line sepenuhnya', 'correct' => false],
                            ['text' => 'Membuat setiap server punya konfigurasi yang berbeda-beda', 'correct' => false],
                            ['text' => 'Mengurangi jumlah developer yang dibutuhkan tim', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang dimaksud dengan sebuah shell script pada dasarnya?',
                        'explanation' => 'Shell script adalah kumpulan perintah command line yang ditulis berurutan dalam satu file, sehingga bisa dijalankan sekaligus tanpa perlu mengetik satu per satu, dan bisa memakai variabel, kondisi, serta perulangan.',
                        'options' => [
                            ['text' => 'Kumpulan perintah command line yang ditulis berurutan dalam satu file', 'correct' => true],
                            ['text' => 'File konfigurasi yang hanya bisa dibaca, tidak bisa dijalankan', 'correct' => false],
                            ['text' => 'Bahasa pemrograman terpisah yang tidak berhubungan dengan Linux', 'correct' => false],
                            ['text' => 'Format khusus untuk menyimpan data biner', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Pada script backup.sh, kenapa nama folder backup sering menyertakan tanggal (misalnya /backup/2026-07-20)?',
                        'explanation' => 'Penamaan folder berbasis tanggal membuat setiap backup punya lokasi yang unik dan mudah ditelusuri, sehingga backup lama tidak tertimpa oleh backup baru dan riwayatnya bisa dilacak berdasarkan waktu.',
                        'options' => [
                            ['text' => 'Agar setiap backup punya lokasi unik dan mudah ditelusuri berdasarkan waktu', 'correct' => true],
                            ['text' => 'Karena Linux mewajibkan nama folder mengandung tanggal', 'correct' => false],
                            ['text' => 'Supaya ukuran folder backup menjadi lebih kecil', 'correct' => false],
                            ['text' => 'Tanggal pada nama folder tidak punya fungsi praktis apapun', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Tugas backup, deployment, atau pembersihan log lama sering diotomasi menggunakan apa di lingkungan Linux?',
                        'explanation' => 'Shell script (biasanya menggunakan Bash) sering dipakai untuk tugas-tugas berulang seperti backup otomatis, deployment aplikasi, pembersihan file log lama, atau pengecekan status server secara berkala.',
                        'options' => [
                            ['text' => 'Shell script (Bash)', 'correct' => true],
                            ['text' => 'File gambar', 'correct' => false],
                            ['text' => 'Dokumen presentasi', 'correct' => false],
                            ['text' => 'File audio', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa script kecil sering disebut sebagai "lem" yang menghubungkan berbagai tool DevOps yang lebih besar?',
                        'explanation' => 'Script sederhana sering dipakai untuk menjalankan proses build, memindahkan artifact hasil build, atau melakukan pengecekan sebelum deployment dilakukan — menghubungkan berbagai tool otomasi yang lebih kompleks menjadi satu alur kerja.',
                        'options' => [
                            ['text' => 'Sering dipakai untuk menghubungkan proses build, pemindahan artifact, dan pengecekan sebelum deployment', 'correct' => true],
                            ['text' => 'Karena script menggantikan seluruh tool CI/CD yang ada', 'correct' => false],
                            ['text' => 'Karena script tidak bisa dijalankan tanpa tool tambahan lain', 'correct' => false],
                            ['text' => 'Karena script hanya bisa dipakai sekali lalu harus dihapus', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 2: Docker & Containerization
            // ============================================================
            'Assignment 1: Dockerize a Node.js App' => [
                'title' => 'Quiz: Containerization & Dockerfile',
                'questions' => [
                    [
                        'question' => 'Apa masalah klasik yang coba diselesaikan oleh containerization?',
                        'explanation' => 'Containerization menyelesaikan masalah "aplikasi berjalan normal di laptop saya, tapi error di server" akibat perbedaan environment antara komputer developer, server testing, dan server production.',
                        'options' => [
                            ['text' => 'Perbedaan environment antara laptop developer, server testing, dan server production', 'correct' => true],
                            ['text' => 'Kurangnya jumlah developer dalam sebuah tim', 'correct' => false],
                            ['text' => 'Lambatnya kecepatan internet di kantor', 'correct' => false],
                            ['text' => 'Tingginya biaya lisensi sistem operasi', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa perbedaan mendasar antara container dan Virtual Machine (VM)?',
                        'explanation' => 'VM menjalankan sistem operasi lengkap secara terpisah di atas hypervisor sehingga cukup berat, sementara container berbagi kernel sistem operasi host dan hanya mengisolasi proses aplikasinya, sehingga jauh lebih ringan dan cepat.',
                        'options' => [
                            ['text' => 'VM menjalankan OS lengkap terpisah, container berbagi kernel host dan lebih ringan', 'correct' => true],
                            ['text' => 'Container dan VM sebenarnya teknologi yang identik', 'correct' => false],
                            ['text' => 'VM selalu lebih ringan dan cepat dibanding container', 'correct' => false],
                            ['text' => 'Container hanya bisa dipakai untuk aplikasi berbasis Windows', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Instruksi Dockerfile mana yang menentukan direktori kerja di dalam container?',
                        'explanation' => 'WORKDIR dipakai untuk menentukan direktori kerja di dalam image/container, sehingga instruksi berikutnya seperti COPY atau RUN dijalankan relatif terhadap direktori tersebut.',
                        'options' => [
                            ['text' => 'WORKDIR', 'correct' => true],
                            ['text' => 'FROM', 'correct' => false],
                            ['text' => 'CMD', 'correct' => false],
                            ['text' => 'EXPOSE', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa fungsi instruksi CMD pada Dockerfile?',
                        'explanation' => 'CMD menentukan perintah yang akan dijalankan saat container mulai berjalan, misalnya CMD ["node", "server.js"] untuk menjalankan aplikasi Node.js.',
                        'options' => [
                            ['text' => 'Menentukan perintah yang dijalankan saat container mulai berjalan', 'correct' => true],
                            ['text' => 'Menyalin file dari komputer lokal ke dalam image', 'correct' => false],
                            ['text' => 'Menentukan image dasar yang dipakai', 'correct' => false],
                            ['text' => 'Menghapus dependensi yang tidak terpakai', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa pertanyaan mengenai perbedaan container dan VM cukup sering muncul di wawancara kerja DevOps?',
                        'explanation' => 'Pertanyaan ini menunjukkan apakah kandidat memahami konsep dasar di balik teknologi yang mereka gunakan, bukan sekadar menghafal perintah Docker tanpa memahami alasan kenapa container lebih efisien dibanding VM.',
                        'options' => [
                            ['text' => 'Menunjukkan apakah kandidat memahami konsep dasar, bukan sekadar hafal perintah', 'correct' => true],
                            ['text' => 'Karena VM sudah tidak dipakai sama sekali di industri', 'correct' => false],
                            ['text' => 'Karena pertanyaan ini wajib ditanyakan menurut standar hukum', 'correct' => false],
                            ['text' => 'Karena container dan VM tidak ada hubungannya dengan pekerjaan DevOps', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Multi-Container App with Docker Compose' => [
                'title' => 'Quiz: Docker Compose & Volume',
                'questions' => [
                    [
                        'question' => 'Kenapa Docker Compose dibutuhkan ketika aplikasi terdiri dari beberapa komponen seperti backend, database, dan cache?',
                        'explanation' => 'Docker Compose memungkinkan pendefinisian dan menjalankan beberapa container sekaligus menggunakan satu file konfigurasi, sehingga mengelola container-container yang saling berhubungan tidak perlu dilakukan manual satu per satu.',
                        'options' => [
                            ['text' => 'Memungkinkan pendefinisian dan menjalankan beberapa container sekaligus dari satu file konfigurasi', 'correct' => true],
                            ['text' => 'Docker Compose menggantikan kebutuhan Dockerfile sepenuhnya', 'correct' => false],
                            ['text' => 'Docker Compose hanya bisa dipakai untuk satu container saja', 'correct' => false],
                            ['text' => 'Docker Compose wajib dipakai di lingkungan production skala besar', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Bagaimana cara container-container dalam satu docker-compose.yml saling berkomunikasi?',
                        'explanation' => 'Docker Compose membuat jaringan internal yang memungkinkan container-container tersebut saling berkomunikasi menggunakan nama service (misalnya "database") sebagai hostname, bukan alamat IP statis.',
                        'options' => [
                            ['text' => 'Menggunakan nama service sebagai hostname lewat jaringan internal yang dibuat Compose', 'correct' => true],
                            ['text' => 'Setiap container harus dikonfigurasi manual dengan alamat IP tetap', 'correct' => false],
                            ['text' => 'Container tidak bisa saling berkomunikasi dalam satu Compose file', 'correct' => false],
                            ['text' => 'Komunikasi hanya bisa lewat internet publik', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa container bersifat ephemeral menjadi masalah untuk aplikasi seperti database?',
                        'explanation' => 'Sifat ephemeral berarti semua data yang disimpan di dalam container akan hilang begitu container tersebut dihapus — ini menjadi masalah untuk aplikasi yang perlu menyimpan data secara permanen seperti database.',
                        'options' => [
                            ['text' => 'Semua data di dalam container akan hilang begitu container dihapus', 'correct' => true],
                            ['text' => 'Container tidak bisa menjalankan aplikasi database sama sekali', 'correct' => false],
                            ['text' => 'Container ephemeral berarti containernya berjalan lebih lambat', 'correct' => false],
                            ['text' => 'Data di container justru menjadi permanen selamanya', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa fungsi Docker volume?',
                        'explanation' => 'Docker volume adalah mekanisme untuk menyimpan data di luar lifecycle container, sehingga data tetap ada meskipun container dihapus atau diganti dengan versi baru.',
                        'options' => [
                            ['text' => 'Menyimpan data di luar lifecycle container, agar tetap ada meski container dihapus', 'correct' => true],
                            ['text' => 'Mempercepat proses build image Docker', 'correct' => false],
                            ['text' => 'Mengenkripsi seluruh isi container secara otomatis', 'correct' => false],
                            ['text' => 'Menggabungkan beberapa image menjadi satu image tunggal', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa kesalahan umum yang sering terjadi pada DevOps pemula terkait database di dalam container?',
                        'explanation' => 'Kesalahan umum adalah menjalankan database di dalam container tanpa volume, sehingga seluruh data hilang saat container tersebut restart atau dihapus.',
                        'options' => [
                            ['text' => 'Menjalankan database tanpa volume, sehingga data hilang saat container restart/dihapus', 'correct' => true],
                            ['text' => 'Menyertakan volume pada setiap container database yang dibuat', 'correct' => false],
                            ['text' => 'Menggunakan bind mount saat proses development', 'correct' => false],
                            ['text' => 'Menentukan port yang tepat untuk database', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 3: CI/CD Pipeline
            // ============================================================
            'Assignment 1: GitHub Actions CI Pipeline' => [
                'title' => 'Quiz: Konsep CI/CD & GitHub Actions',
                'questions' => [
                    [
                        'question' => 'Apa tujuan utama Continuous Integration (CI)?',
                        'explanation' => 'CI adalah praktik di mana setiap perubahan kode yang dikirim developer secara otomatis diuji dan diverifikasi (biasanya lewat build dan automated test), bertujuan mendeteksi masalah sedini mungkin sebelum kode digabungkan ke branch utama.',
                        'options' => [
                            ['text' => 'Mendeteksi masalah sedini mungkin lewat pengujian otomatis sebelum kode digabungkan', 'correct' => true],
                            ['text' => 'Menghapus seluruh riwayat commit yang sudah lama', 'correct' => false],
                            ['text' => 'Mengganti kebutuhan developer menulis test manual sepenuhnya', 'correct' => false],
                            ['text' => 'Mempercepat proses instalasi sistem operasi di server', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa perbedaan Continuous Delivery dan Continuous Deployment?',
                        'explanation' => 'Continuous Delivery berarti kode yang lolos pengujian siap di-deploy kapan saja namun masih memerlukan persetujuan manual, sedangkan Continuous Deployment berarti proses deployment berjalan sepenuhnya otomatis tanpa intervensi manual.',
                        'options' => [
                            ['text' => 'Continuous Delivery butuh persetujuan manual, Continuous Deployment sepenuhnya otomatis', 'correct' => true],
                            ['text' => 'Keduanya adalah istilah yang benar-benar identik tanpa perbedaan', 'correct' => false],
                            ['text' => 'Continuous Deployment hanya berlaku untuk environment staging', 'correct' => false],
                            ['text' => 'Continuous Delivery berarti kode tidak pernah di-deploy sama sekali', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Di mana file konfigurasi workflow GitHub Actions biasanya ditempatkan pada sebuah repository?',
                        'explanation' => 'Workflow GitHub Actions didefinisikan menggunakan file YAML yang ditempatkan di dalam folder .github/workflows pada repository.',
                        'options' => [
                            ['text' => '.github/workflows', 'correct' => true],
                            ['text' => 'src/config', 'correct' => false],
                            ['text' => 'node_modules/actions', 'correct' => false],
                            ['text' => 'public/pipeline', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Event apa yang umum dipakai untuk memicu sebuah workflow GitHub Actions agar berjalan otomatis?',
                        'explanation' => 'Workflow dapat dikonfigurasi untuk berjalan otomatis berdasarkan event tertentu, misalnya setiap kali ada push ke branch main, atau setiap kali dibuat pull request.',
                        'options' => [
                            ['text' => 'Push ke branch tertentu atau pembuatan pull request', 'correct' => true],
                            ['text' => 'Hanya bisa dipicu manual lewat command line setiap saat', 'correct' => false],
                            ['text' => 'Hanya berjalan sekali seumur hidup repository', 'correct' => false],
                            ['text' => 'Dipicu setiap kali file README diubah saja', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa hampir seluruh perusahaan teknologi menerapkan CI/CD dalam bentuk tertentu?',
                        'explanation' => 'CI/CD memungkinkan tim merilis perubahan kode secara lebih sering dengan risiko yang lebih terkendali, karena pengujian dan deployment dilakukan secara konsisten dan otomatis, bukan manual satu per satu.',
                        'options' => [
                            ['text' => 'Memungkinkan rilis kode lebih sering dengan risiko yang lebih terkendali', 'correct' => true],
                            ['text' => 'CI/CD diwajibkan oleh regulasi pemerintah di semua negara', 'correct' => false],
                            ['text' => 'CI/CD menghapus kebutuhan menulis kode aplikasi', 'correct' => false],
                            ['text' => 'CI/CD hanya relevan untuk perusahaan yang sangat kecil', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Automated Deployment Workflow' => [
                'title' => 'Quiz: Deployment Strategy & Rollback',
                'questions' => [
                    [
                        'question' => 'Apa yang terjadi pada strategi rolling update saat versi baru aplikasi di-deploy?',
                        'explanation' => 'Pada rolling update, instance aplikasi lama diganti secara bertahap dengan instance baru (bukan sekaligus), sehingga selalu ada instance yang tetap melayani traffic selama proses update berlangsung.',
                        'options' => [
                            ['text' => 'Instance lama diganti secara bertahap dengan instance baru, traffic tetap terlayani', 'correct' => true],
                            ['text' => 'Seluruh instance lama dimatikan sekaligus sebelum versi baru dinyalakan', 'correct' => false],
                            ['text' => 'Aplikasi harus offline total selama proses deployment berlangsung', 'correct' => false],
                            ['text' => 'Hanya satu instance yang boleh berjalan sepanjang waktu', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Bagaimana cara kerja blue-green deployment?',
                        'explanation' => 'Blue-green deployment menyiapkan dua environment identik (blue yang sedang berjalan, green versi baru) — setelah green siap dan terverifikasi, traffic dialihkan sepenuhnya dari blue ke green, dan blue tetap siaga untuk rollback cepat.',
                        'options' => [
                            ['text' => 'Menyiapkan dua environment identik, lalu mengalihkan traffic dari versi lama ke versi baru', 'correct' => true],
                            ['text' => 'Mengganti instance lama satu per satu secara bertahap tanpa environment cadangan', 'correct' => false],
                            ['text' => 'Hanya berlaku untuk aplikasi yang tidak memiliki traffic sama sekali', 'correct' => false],
                            ['text' => 'Menghapus environment lama sebelum environment baru selesai disiapkan', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa kemampuan melakukan rollback dengan cepat dianggap sama pentingnya dengan kemampuan melakukan deployment itu sendiri?',
                        'explanation' => 'Semakin cepat tim dapat kembali ke versi yang stabil setelah deployment bermasalah, semakin kecil dampak yang dirasakan pengguna aplikasi — ini bagian penting dari incident response.',
                        'options' => [
                            ['text' => 'Semakin cepat kembali ke versi stabil, semakin kecil dampak yang dirasakan pengguna', 'correct' => true],
                            ['text' => 'Rollback selalu lebih sulit dilakukan dibanding deployment awal', 'correct' => false],
                            ['text' => 'Rollback tidak pernah dibutuhkan kalau pipeline CI/CD sudah bagus', 'correct' => false],
                            ['text' => 'Rollback hanya relevan untuk aplikasi berskala kecil', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa itu automated rollback?',
                        'explanation' => 'Automated rollback adalah kondisi di mana sistem monitoring mendeteksi anomali (misalnya lonjakan error rate) setelah deployment baru dilakukan, dan secara otomatis mengembalikan aplikasi ke versi sebelumnya tanpa menunggu intervensi manusia.',
                        'options' => [
                            ['text' => 'Sistem otomatis mengembalikan ke versi sebelumnya saat mendeteksi anomali pasca-deployment', 'correct' => true],
                            ['text' => 'Proses rollback yang harus dilakukan manual oleh developer setiap saat', 'correct' => false],
                            ['text' => 'Penghapusan seluruh riwayat versi aplikasi secara permanen', 'correct' => false],
                            ['text' => 'Fitur yang hanya tersedia pada Continuous Delivery, bukan Continuous Deployment', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa pipeline CI/CD yang matang perlu dirancang dengan mempertimbangkan skenario kegagalan sejak awal?',
                        'explanation' => 'Pipeline yang hanya berfokus pada skenario ketika semuanya berjalan lancar tidak siap menghadapi kondisi nyata di mana deployment bisa gagal — mempertimbangkan skenario kegagalan memastikan tim punya cara menangani masalah dengan cepat dan terukur.',
                        'options' => [
                            ['text' => 'Agar tim siap menangani kegagalan deployment dengan cepat dan terukur, bukan hanya skenario sukses', 'correct' => true],
                            ['text' => 'Karena skenario kegagalan tidak pernah terjadi pada sistem yang sudah pakai CI/CD', 'correct' => false],
                            ['text' => 'Karena regulasi hukum mewajibkan pipeline mempertimbangkan kegagalan', 'correct' => false],
                            ['text' => 'Karena skenario sukses tidak perlu dipertimbangkan sama sekali', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 4: Kubernetes & Orchestration
            // ============================================================
            'Assignment 1: Deploy App to Kubernetes Cluster' => [
                'title' => 'Quiz: Pod, Deployment & Service',
                'questions' => [
                    [
                        'question' => 'Kenapa orkestrasi container dibutuhkan ketika aplikasi harus berjalan di puluhan atau ratusan server sekaligus?',
                        'explanation' => 'Pada skala besar, kebutuhan seperti penyeimbangan beban, pemulihan otomatis saat container gagal, dan penambahan kapasitas secara dinamis membuat pengelolaan manual menjadi tidak praktis — orkestrasi mengotomatiskan seluruh proses ini.',
                        'options' => [
                            ['text' => 'Mengotomatiskan penempatan, load balancing, dan pemulihan container dalam skala besar', 'correct' => true],
                            ['text' => 'Karena container tidak bisa berjalan tanpa orkestrasi sama sekali', 'correct' => false],
                            ['text' => 'Karena orkestrasi menggantikan kebutuhan menulis Dockerfile', 'correct' => false],
                            ['text' => 'Orkestrasi hanya relevan untuk aplikasi dengan satu pengguna saja', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa itu Pod dalam Kubernetes?',
                        'explanation' => 'Pod adalah unit terkecil yang dapat di-deploy di Kubernetes, biasanya berisi satu container aplikasi, dan bersifat sementara — jika sebuah Pod bermasalah, Kubernetes menggantinya dengan Pod baru, bukan memperbaiki Pod yang sama.',
                        'options' => [
                            ['text' => 'Unit terkecil yang dapat di-deploy di Kubernetes, biasanya berisi satu container', 'correct' => true],
                            ['text' => 'Nama lain untuk seluruh cluster Kubernetes', 'correct' => false],
                            ['text' => 'File konfigurasi YAML utama Kubernetes', 'correct' => false],
                            ['text' => 'Jenis database khusus yang dipakai Kubernetes', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang dilakukan Deployment ketika salah satu Pod yang dikelolanya gagal?',
                        'explanation' => 'Deployment mengelola sekumpulan Pod yang identik dan memastikan jumlah Pod yang berjalan selalu sesuai konfigurasi (replica) — jika ada Pod yang gagal, Deployment akan otomatis membuat Pod baru sebagai penggantinya.',
                        'options' => [
                            ['text' => 'Deployment otomatis membuat Pod baru sebagai penggantinya', 'correct' => true],
                            ['text' => 'Deployment akan menghentikan seluruh aplikasi sampai diperbaiki manual', 'correct' => false],
                            ['text' => 'Deployment mengirim notifikasi tapi tidak melakukan tindakan apapun', 'correct' => false],
                            ['text' => 'Deployment menghapus konfigurasi cluster secara keseluruhan', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa fungsi utama Service pada Kubernetes?',
                        'explanation' => 'Service menyediakan alamat jaringan yang stabil untuk mengakses sekumpulan Pod, meskipun Pod-Pod tersebut dapat berganti seiring waktu — tanpa Service, aplikasi lain akan kesulitan menemukan alamat Pod yang terus berubah.',
                        'options' => [
                            ['text' => 'Menyediakan alamat jaringan yang stabil untuk mengakses sekumpulan Pod', 'correct' => true],
                            ['text' => 'Menyimpan data konfigurasi rahasia seperti password', 'correct' => false],
                            ['text' => 'Menjalankan proses build image Docker', 'correct' => false],
                            ['text' => 'Menampilkan log dari seluruh Pod dalam bentuk dashboard', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Perintah kubectl apply -f deployment.yaml dipakai untuk apa?',
                        'explanation' => 'Perintah ini dipakai untuk menerapkan konfigurasi yang ada di file YAML (misalnya Deployment) ke cluster Kubernetes, sehingga Kubernetes akan membuat/menyesuaikan resource sesuai definisi tersebut.',
                        'options' => [
                            ['text' => 'Menerapkan konfigurasi pada file YAML ke cluster Kubernetes', 'correct' => true],
                            ['text' => 'Menghapus seluruh Pod yang sedang berjalan', 'correct' => false],
                            ['text' => 'Membuat image Docker baru dari Dockerfile', 'correct' => false],
                            ['text' => 'Mengecek koneksi jaringan ke server eksternal', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Autoscaling Configuration Exercise' => [
                'title' => 'Quiz: Horizontal Pod Autoscaler, ConfigMap & Secret',
                'questions' => [
                    [
                        'question' => 'Apa fungsi Horizontal Pod Autoscaler (HPA) di Kubernetes?',
                        'explanation' => 'HPA menyesuaikan jumlah Pod yang berjalan secara otomatis berdasarkan metrik tertentu, misalnya penggunaan CPU, sehingga aplikasi bisa menyesuaikan kapasitas secara dinamis sesuai kebutuhan traffic.',
                        'options' => [
                            ['text' => 'Menyesuaikan jumlah Pod secara otomatis berdasarkan metrik tertentu seperti CPU', 'correct' => true],
                            ['text' => 'Menyimpan data konfigurasi rahasia seperti password database', 'correct' => false],
                            ['text' => 'Membuat image Docker menjadi lebih kecil ukurannya', 'correct' => false],
                            ['text' => 'Menghubungkan Kubernetes dengan repository Git', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Bagaimana Service mendistribusikan traffic ketika ada banyak Pod yang menjalankan aplikasi yang sama?',
                        'explanation' => 'Ketika terdapat banyak Pod yang identik, Service akan mendistribusikan traffic yang masuk secara merata ke seluruh Pod yang tersedia secara otomatis, tanpa perlu konfigurasi load balancing tambahan dari developer.',
                        'options' => [
                            ['text' => 'Secara otomatis mendistribusikan traffic merata ke seluruh Pod tanpa konfigurasi tambahan', 'correct' => true],
                            ['text' => 'Traffic hanya dikirim ke satu Pod tertentu yang dipilih manual', 'correct' => false],
                            ['text' => 'Setiap Pod harus mengatur load balancing masing-masing secara terpisah', 'correct' => false],
                            ['text' => 'Service tidak bisa mendistribusikan traffic ke lebih dari satu Pod', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa perbedaan utama antara ConfigMap dan Secret di Kubernetes?',
                        'explanation' => 'ConfigMap dipakai untuk menyimpan data konfigurasi yang tidak bersifat rahasia (misalnya URL API), sedangkan Secret khusus dipakai untuk data sensitif seperti password atau API key, dengan mekanisme akses yang lebih dibatasi.',
                        'options' => [
                            ['text' => 'ConfigMap untuk data konfigurasi biasa, Secret untuk data sensitif seperti password', 'correct' => true],
                            ['text' => 'ConfigMap dan Secret sebenarnya fungsinya identik dan bisa saling menggantikan', 'correct' => false],
                            ['text' => 'Secret hanya bisa dipakai untuk menyimpan file gambar', 'correct' => false],
                            ['text' => 'ConfigMap hanya bisa dipakai sekali lalu otomatis terhapus', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa menyimpan konfigurasi terpisah dari image Docker (lewat ConfigMap/Secret) dianggap praktik yang baik?',
                        'explanation' => 'Dengan ConfigMap dan Secret, konfigurasi bisa diubah tanpa perlu membangun ulang image, dan data sensitif bisa dikelola dengan kontrol akses yang lebih ketat, dibanding menaruh konfigurasi langsung di dalam image.',
                        'options' => [
                            ['text' => 'Konfigurasi bisa diubah tanpa membangun ulang image, dan data sensitif lebih terkontrol', 'correct' => true],
                            ['text' => 'Karena Docker image tidak mendukung environment variable sama sekali', 'correct' => false],
                            ['text' => 'Karena ConfigMap dan Secret membuat image berjalan lebih cepat', 'correct' => false],
                            ['text' => 'Karena tanpa ConfigMap, Kubernetes tidak bisa menjalankan Pod', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa dampak konfigurasi scaling yang terlalu konservatif pada suatu aplikasi?',
                        'explanation' => 'Konfigurasi scaling yang terlalu konservatif dapat menyebabkan aplikasi kewalahan saat traffic tinggi, karena jumlah Pod tidak bertambah cukup cepat untuk menangani lonjakan permintaan.',
                        'options' => [
                            ['text' => 'Aplikasi bisa kewalahan saat traffic tinggi karena Pod tidak bertambah cukup cepat', 'correct' => true],
                            ['text' => 'Aplikasi akan otomatis menggunakan resource yang lebih sedikit dari kebutuhan sebenarnya', 'correct' => false],
                            ['text' => 'Tidak ada dampak apapun terhadap performa aplikasi', 'correct' => false],
                            ['text' => 'Biaya infrastruktur akan meningkat drastis tanpa alasan jelas', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 5: Monitoring & Logging
            // ============================================================
            'Assignment 1: Prometheus Monitoring Setup' => [
                'title' => 'Quiz: Observability & Prometheus',
                'questions' => [
                    [
                        'question' => 'Apa yang dimaksud dengan observability pada sebuah sistem?',
                        'explanation' => 'Observability adalah kemampuan memahami kondisi internal suatu sistem hanya dengan mengamati output yang dihasilkannya, tanpa perlu masuk secara langsung ke dalam sistem tersebut.',
                        'options' => [
                            ['text' => 'Kemampuan memahami kondisi internal sistem hanya dari output yang dihasilkannya', 'correct' => true],
                            ['text' => 'Proses menghapus log yang sudah lama secara otomatis', 'correct' => false],
                            ['text' => 'Kemampuan sistem untuk mengenkripsi seluruh datanya sendiri', 'correct' => false],
                            ['text' => 'Fitur untuk membuat backup database secara berkala', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa peran masing-masing dari tiga pilar observability: metrics, logs, dan traces?',
                        'explanation' => 'Metrics memberi tahu bahwa ada masalah (data numerik kondisi sistem), logs memberi detail mengenai apa yang terjadi, dan traces membantu menemukan di komponen mana masalah tersebut berasal, terutama pada arsitektur microservices.',
                        'options' => [
                            ['text' => 'Metrics menandai adanya masalah, logs memberi detail, traces melacak lokasi masalah', 'correct' => true],
                            ['text' => 'Ketiganya adalah istilah berbeda untuk hal yang benar-benar sama', 'correct' => false],
                            ['text' => 'Metrics hanya dipakai untuk visual dashboard, tidak punya fungsi analitis', 'correct' => false],
                            ['text' => 'Traces menggantikan kebutuhan metrics dan logs sepenuhnya', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Bagaimana cara kerja dasar Prometheus dalam mengumpulkan data metrics?',
                        'explanation' => 'Prometheus bekerja dengan cara secara berkala "menarik" (scrape) data metrics dari endpoint tertentu (biasanya /metrics) yang disediakan oleh aplikasi yang dipantau.',
                        'options' => [
                            ['text' => 'Secara berkala menarik (scrape) data dari endpoint /metrics yang disediakan aplikasi', 'correct' => true],
                            ['text' => 'Aplikasi harus mengirim data secara manual lewat email ke Prometheus', 'correct' => false],
                            ['text' => 'Prometheus hanya bisa membaca data dari file log statis', 'correct' => false],
                            ['text' => 'Prometheus menyimpan data langsung di dalam kode aplikasi', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Tool apa yang umum dipakai untuk memvisualisasikan data yang dikumpulkan Prometheus dalam bentuk dashboard interaktif?',
                        'explanation' => 'Grafana sering dipakai bersama Prometheus untuk memvisualisasikan data metrics dalam bentuk dashboard interaktif yang bisa dipantau secara real-time.',
                        'options' => [
                            ['text' => 'Grafana', 'correct' => true],
                            ['text' => 'Terraform', 'correct' => false],
                            ['text' => 'ArgoCD', 'correct' => false],
                            ['text' => 'Docker Compose', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa penting memahami metrics apa saja yang benar-benar perlu dipantau, bukan sekadar mengumpulkan semua data yang tersedia?',
                        'explanation' => 'Terlalu banyak metrics tanpa prioritas yang jelas justru dapat mempersulit proses analisis saat terjadi masalah, karena tim harus menyaring informasi yang relevan dari data yang berlebihan.',
                        'options' => [
                            ['text' => 'Terlalu banyak metrics tanpa prioritas justru mempersulit analisis saat terjadi masalah', 'correct' => true],
                            ['text' => 'Prometheus membatasi jumlah metrics maksimal yang bisa dikumpulkan', 'correct' => false],
                            ['text' => 'Metrics yang lebih banyak selalu membuat sistem berjalan lebih cepat', 'correct' => false],
                            ['text' => 'Tidak ada pengaruh apapun terhadap proses analisis masalah', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: Centralized Logging with ELK Stack' => [
                'title' => 'Quiz: Centralized Logging, Alerting & Incident Response',
                'questions' => [
                    [
                        'question' => 'Kenapa centralized logging dibutuhkan ketika aplikasi berjalan di banyak server atau container?',
                        'explanation' => 'Mengecek log satu per satu di setiap server ketika terjadi masalah sangat tidak efisien — centralized logging mengumpulkan seluruh log dari berbagai sumber ke satu tempat terpusat sehingga bisa dicari dan dianalisis dari satu lokasi.',
                        'options' => [
                            ['text' => 'Mengumpulkan log dari berbagai sumber ke satu tempat agar bisa dicari dan dianalisis lebih efisien', 'correct' => true],
                            ['text' => 'Karena log dari server yang berbeda tidak bisa disimpan sama sekali', 'correct' => false],
                            ['text' => 'Karena logging terpusat menghapus kebutuhan monitoring metrics', 'correct' => false],
                            ['text' => 'Hanya relevan untuk aplikasi yang berjalan di satu server saja', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa fungsi masing-masing komponen dalam ELK Stack?',
                        'explanation' => 'Elasticsearch menyimpan dan mencari log, Logstash/Fluentd mengumpulkan dan memproses log dari berbagai sumber, dan Kibana dipakai untuk visualisasi serta pencarian log melalui antarmuka web.',
                        'options' => [
                            ['text' => 'Elasticsearch menyimpan/mencari log, Logstash/Fluentd mengumpulkan, Kibana memvisualisasikan', 'correct' => true],
                            ['text' => 'Ketiganya adalah nama lain untuk komponen yang sama persis', 'correct' => false],
                            ['text' => 'Kibana dipakai untuk menyimpan log, Elasticsearch untuk visualisasi', 'correct' => false],
                            ['text' => 'ELK Stack hanya terdiri dari satu komponen tunggal', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa tujuan utama alerting pada sistem monitoring?',
                        'explanation' => 'Alerting secara otomatis mengirimkan notifikasi kepada tim ketika suatu kondisi tertentu terpenuhi (misalnya error rate melebihi ambang batas), sehingga tim bisa merespons masalah sebelum berdampak luas ke pengguna.',
                        'options' => [
                            ['text' => 'Mengirim notifikasi otomatis saat kondisi tertentu terpenuhi, agar masalah bisa direspons cepat', 'correct' => true],
                            ['text' => 'Menghapus log lama secara otomatis dari sistem penyimpanan', 'correct' => false],
                            ['text' => 'Membuat backup database secara berkala tanpa notifikasi apapun', 'correct' => false],
                            ['text' => 'Mengganti versi aplikasi secara otomatis tanpa pengujian', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa yang dimaksud dengan alert fatigue, dan kenapa perlu dihindari?',
                        'explanation' => 'Alert fatigue terjadi ketika alert dirancang terlalu sensitif sehingga menghasilkan terlalu banyak notifikasi yang tidak relevan, membuat tim cenderung mengabaikannya — sehingga alert yang benar-benar penting berisiko ikut terlewat.',
                        'options' => [
                            ['text' => 'Kondisi di mana terlalu banyak notifikasi tidak relevan membuat tim jadi mengabaikan alert', 'correct' => true],
                            ['text' => 'Kondisi di mana sistem monitoring berhenti berfungsi total', 'correct' => false],
                            ['text' => 'Fitur bawaan Prometheus untuk mempercepat proses scraping', 'correct' => false],
                            ['text' => 'Istilah lain untuk proses rollback otomatis', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa fokus utama dari sebuah dokumen post-mortem setelah insiden ditangani?',
                        'explanation' => 'Post-mortem yang baik berfokus pada perbaikan sistem dan proses (apa yang terjadi, kenapa bisa terjadi, dan langkah pencegahan ke depan), bukan pada mencari pihak yang harus disalahkan.',
                        'options' => [
                            ['text' => 'Perbaikan sistem dan proses ke depan, bukan mencari pihak yang harus disalahkan', 'correct' => true],
                            ['text' => 'Menentukan siapa anggota tim yang harus dikenai sanksi', 'correct' => false],
                            ['text' => 'Menghitung total biaya kerugian akibat insiden secara detail', 'correct' => false],
                            ['text' => 'Menghapus seluruh log yang berkaitan dengan insiden tersebut', 'correct' => false],
                        ],
                    ],
                ],
            ],

            // ============================================================
            // MODUL 6: Git & Infrastructure Workflow
            // ============================================================
            'Assignment 1: Terraform Infrastructure Setup' => [
                'title' => 'Quiz: Infrastructure as Code & Version Control',
                'questions' => [
                    [
                        'question' => 'Apa itu Infrastructure as Code (IaC)?',
                        'explanation' => 'IaC adalah pendekatan yang mendefinisikan konfigurasi infrastruktur dalam bentuk file kode, bukan dilakukan secara manual, sehingga konfigurasi bisa disimpan, diversikan dengan Git, dan diterapkan ulang secara konsisten.',
                        'options' => [
                            ['text' => 'Mendefinisikan konfigurasi infrastruktur dalam bentuk file kode, bukan dilakukan manual', 'correct' => true],
                            ['text' => 'Proses mengubah kode aplikasi menjadi dokumentasi otomatis', 'correct' => false],
                            ['text' => 'Sistem operasi khusus yang dipakai untuk server production', 'correct' => false],
                            ['text' => 'Fitur bawaan Kubernetes untuk mengelola Secret', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa perbedaan fokus utama antara Terraform dan Ansible dalam konteks IaC?',
                        'explanation' => 'Terraform berfokus pada provisioning infrastruktur seperti server dan jaringan, sedangkan Ansible berfokus pada konfigurasi software di dalam server yang sudah ada — meski keduanya berbagi prinsip dasar yang sama.',
                        'options' => [
                            ['text' => 'Terraform untuk provisioning infrastruktur, Ansible untuk konfigurasi software di server', 'correct' => true],
                            ['text' => 'Keduanya adalah tool yang fungsinya benar-benar identik', 'correct' => false],
                            ['text' => 'Terraform hanya bisa dipakai untuk konfigurasi jaringan lokal', 'correct' => false],
                            ['text' => 'Ansible hanya bisa dipakai pada sistem operasi Windows', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa menyimpan konfigurasi infrastruktur di Git dianggap penting?',
                        'explanation' => 'Menyimpan konfigurasi di Git memungkinkan setiap perubahan tercatat riwayatnya (siapa yang mengubah, kapan, dan kenapa), serta memungkinkan rollback ke versi konfigurasi sebelumnya kalau suatu perubahan menyebabkan masalah.',
                        'options' => [
                            ['text' => 'Setiap perubahan tercatat riwayatnya dan bisa di-rollback kalau menyebabkan masalah', 'correct' => true],
                            ['text' => 'Git wajib dipakai secara hukum untuk semua konfigurasi server', 'correct' => false],
                            ['text' => 'Menyimpan di Git membuat konfigurasi otomatis diterapkan tanpa proses apapun', 'correct' => false],
                            ['text' => 'Tidak ada manfaat praktis dibanding menyimpan di dokumen terpisah', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa manfaat melakukan proses review (mirip pull request) sebelum perubahan konfigurasi infrastruktur diterapkan?',
                        'explanation' => 'Proses review memungkinkan perubahan pada infrastruktur, seperti menambah server baru atau mengubah aturan firewall, diperiksa dulu oleh anggota tim lain sebelum benar-benar diterapkan ke environment production.',
                        'options' => [
                            ['text' => 'Perubahan infrastruktur bisa diperiksa anggota tim lain sebelum diterapkan ke production', 'correct' => true],
                            ['text' => 'Review membuat proses penerapan konfigurasi menjadi lebih lambat tanpa manfaat lain', 'correct' => false],
                            ['text' => 'Review hanya diperlukan untuk perubahan kode aplikasi, bukan infrastruktur', 'correct' => false],
                            ['text' => 'Review menggantikan kebutuhan pengujian konfigurasi di staging', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa perusahaan yang mengelola infrastruktur skala menengah hingga besar umumnya beralih ke pendekatan berbasis kode (IaC) dibanding konfigurasi manual?',
                        'explanation' => 'Konfigurasi manual rentan terhadap kesalahan manusia dan sulit direplikasi secara konsisten, terutama saat jumlah server yang dikelola semakin banyak — IaC memastikan konfigurasi bisa diterapkan berulang kali dengan hasil yang konsisten.',
                        'options' => [
                            ['text' => 'Konfigurasi manual rentan kesalahan manusia dan sulit direplikasi konsisten pada skala besar', 'correct' => true],
                            ['text' => 'Karena konfigurasi manual sudah tidak didukung oleh server modern manapun', 'correct' => false],
                            ['text' => 'Karena IaC menghapus kebutuhan version control sepenuhnya', 'correct' => false],
                            ['text' => 'Karena konfigurasi manual selalu lebih cepat diterapkan dibanding IaC', 'correct' => false],
                        ],
                    ],
                ],
            ],

            'Assignment 2: GitOps Deployment with ArgoCD' => [
                'title' => 'Quiz: GitOps & Kolaborasi Tim Infrastruktur',
                'questions' => [
                    [
                        'question' => 'Apa prinsip utama GitOps?',
                        'explanation' => 'GitOps menjadikan Git sebagai satu-satunya sumber kebenaran (single source of truth) untuk kondisi infrastruktur dan aplikasi yang seharusnya berjalan, dan sebuah sistem otomatis memastikan kondisi aktual selalu sesuai dengan yang didefinisikan di Git.',
                        'options' => [
                            ['text' => 'Git menjadi satu-satunya sumber kebenaran untuk kondisi infrastruktur yang seharusnya berjalan', 'correct' => true],
                            ['text' => 'GitOps berarti seluruh deployment harus dilakukan manual lewat command line', 'correct' => false],
                            ['text' => 'GitOps menghapus kebutuhan menggunakan Kubernetes sama sekali', 'correct' => false],
                            ['text' => 'GitOps hanya bisa dipakai untuk aplikasi yang tidak memakai container', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Apa perbedaan utama GitOps dengan pendekatan CI/CD tradisional?',
                        'explanation' => 'Pada CI/CD tradisional, pipeline biasanya "mendorong" (push) perubahan ke server, sedangkan pada GitOps, sistem secara aktif "menarik" (pull) konfigurasi dari Git dan menyesuaikan kondisi infrastruktur secara otomatis.',
                        'options' => [
                            ['text' => 'CI/CD tradisional mendorong (push) perubahan, GitOps menarik (pull) konfigurasi dari Git', 'correct' => true],
                            ['text' => 'Keduanya adalah pendekatan yang benar-benar sama tanpa perbedaan berarti', 'correct' => false],
                            ['text' => 'GitOps tidak melibatkan Git sama sekali dalam prosesnya', 'correct' => false],
                            ['text' => 'CI/CD tradisional hanya bisa dipakai untuk aplikasi berbasis Kubernetes', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Tool GitOps seperti ArgoCD atau Flux umum digunakan pada ekosistem apa?',
                        'explanation' => 'ArgoCD dan Flux umum digunakan pada Kubernetes, di mana perubahan konfigurasi di repository Git dideteksi secara otomatis oleh tool tersebut dan diterapkan ke cluster.',
                        'options' => [
                            ['text' => 'Kubernetes', 'correct' => true],
                            ['text' => 'Sistem operasi desktop biasa', 'correct' => false],
                            ['text' => 'Aplikasi mobile native', 'correct' => false],
                            ['text' => 'Database relasional tradisional', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa GitOps memberikan auditability yang lebih baik dibanding pendekatan deployment tradisional?',
                        'explanation' => 'Karena seluruh perubahan tercatat di Git, tim dapat dengan mudah melihat riwayat perubahan infrastruktur dan aplikasi dalam satu tempat yang sama, sehingga proses audit menjadi lebih mudah dilakukan.',
                        'options' => [
                            ['text' => 'Seluruh perubahan infrastruktur dan aplikasi tercatat di satu tempat (Git) yang sama', 'correct' => true],
                            ['text' => 'Karena GitOps menghapus kebutuhan mencatat perubahan sama sekali', 'correct' => false],
                            ['text' => 'Karena GitOps hanya bisa dipakai oleh satu orang dalam tim', 'correct' => false],
                            ['text' => 'Karena GitOps otomatis mengenkripsi seluruh riwayat perubahan', 'correct' => false],
                        ],
                    ],
                    [
                        'question' => 'Kenapa code review untuk perubahan infrastruktur dianggap sama pentingnya dengan review kode aplikasi?',
                        'explanation' => 'Perubahan pada konfigurasi infrastruktur, terutama yang berkaitan dengan production, sebaiknya selalu melalui proses review karena dampaknya dapat memengaruhi keseluruhan sistem, bukan hanya satu bagian kecil aplikasi.',
                        'options' => [
                            ['text' => 'Dampak perubahan infrastruktur bisa memengaruhi keseluruhan sistem, bukan hanya satu bagian kecil', 'correct' => true],
                            ['text' => 'Karena perubahan infrastruktur tidak pernah berdampak pada aplikasi yang berjalan', 'correct' => false],
                            ['text' => 'Karena review infrastruktur diwajibkan oleh Git secara teknis', 'correct' => false],
                            ['text' => 'Karena perubahan infrastruktur selalu lebih mudah diperbaiki dibanding kode aplikasi', 'correct' => false],
                        ],
                    ],
                ],
            ],
        ];
    }
}