<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\MiniProject;
use Illuminate\Database\Seeder;

class MiniProjectDevOpsSeeder extends Seeder
{
    /**
     * Mini project untuk seluruh assignment praktik di track DevOps
     * Engineer (Modul 1-6). Judul assignment di sini HARUS PERSIS sama
     * dengan yang dibuat AddAssignmentsToExistingModulesSeeder (format
     * "Assignment N: {title}") — jalankan seeder itu DULU sebelum ini.
     *
     * Jalankan setelah AddAssignmentsToExistingModulesSeeder,
     * AssignmentDetailSeeder & CodingExerciseDevOpsSeeder (assignment-nya
     * harus sudah ada). Idempotent lewat updateOrCreate berdasarkan
     * assignment_id, aman dijalankan berkali-kali.
     *
     * Jalankan:
     *   php artisan db:seed --class=MiniProjectDevOpsSeeder
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

        $this->command?->info("MiniProjectDevOpsSeeder: {$created} mini project berhasil dibuat/diperbarui.");

        if (! empty($skipped)) {
            $this->command?->warn(
                'MiniProjectDevOpsSeeder: assignment tidak ditemukan (jalankan AddAssignmentsToExistingModulesSeeder dulu) — '
                . implode(', ', $skipped)
            );
        }
    }

    private function projectData(): array
    {
        return [

            // ============================================================
            // ---------- Modul 1: Linux Fundamentals ----------
            // ============================================================
            'Assignment 1: Linux Server Setup Exercise' => [
                'title' => 'Tantangan Mini Project: Setup Server Linux dari Nol',
                'brief' => 'Kembangkan Latihan Coding perintah dasar sebelumnya jadi simulasi setup server Linux end-to-end: buat struktur folder proyek, atur permission yang tepat untuk beberapa user/role, dan kelola minimal 1 service dengan systemd.',
                'objectives' => [
                    'Buat struktur folder proyek dengan permission yang sesuai untuk kebutuhan berbeda (misal folder log read-only, folder upload writable)',
                    'Konfigurasikan permission dan ownership (chmod/chown) yang tepat untuk skenario multi-user sederhana',
                    'Kelola minimal 1 service dengan systemd (start, stop, enable, cek status)',
                ],
                'acceptance_criteria' => [
                    'Struktur folder dan file mencerminkan kebutuhan akses yang berbeda-beda (baca saja vs baca-tulis)',
                    'Permission (chmod) dan kepemilikan (chown) diterapkan dengan tepat, tidak asal 777',
                    'Service yang dikelola bisa di-start, stop, dan enable saat boot dengan systemd',
                    'Setiap langkah didokumentasikan dengan perintah yang dipakai dan alasannya',
                    'Tidak ada kesalahan permission yang membuat service gagal berjalan',
                ],
                'deliverables' => [
                    'File dokumentasi (.md) berisi seluruh perintah yang dijalankan beserta penjelasan',
                    'Screenshot/log hasil eksekusi (status service, hasil ls -la, dsb.)',
                ],
            ],

            'Assignment 2: Automated Backup Shell Script' => [
                'title' => 'Tantangan Mini Project: Script Backup Otomatis dengan Rotasi',
                'brief' => 'Kembangkan Latihan Coding backup script sebelumnya jadi script Bash yang lebih lengkap: backup otomatis berbasis tanggal, plus mekanisme rotasi (hapus backup lama) agar penyimpanan tidak penuh.',
                'objectives' => [
                    'Tulis script Bash yang mem-backup folder target dengan penamaan file berbasis tanggal/waktu',
                    'Implementasikan rotasi backup — hapus otomatis backup yang lebih tua dari N hari',
                    'Tangani error dasar (folder sumber tidak ada, gagal menulis ke folder tujuan)',
                ],
                'acceptance_criteria' => [
                    'Script berhasil membuat backup dengan nama file yang mencerminkan tanggal/waktu eksekusi',
                    'Backup lama yang melebihi batas retensi terhapus otomatis tanpa menghapus backup yang masih dalam masa retensi',
                    'Script memberi pesan error yang jelas saat folder sumber/tujuan bermasalah, tidak diam saja',
                    'Variabel penting (folder sumber, tujuan, jumlah hari retensi) mudah diubah di bagian atas script',
                    'Script bisa dijalankan berulang kali tanpa menyebabkan error atau file rusak',
                ],
                'deliverables' => [
                    'File script (.sh) beserta contoh hasil eksekusi',
                    'File dokumentasi (.md) berisi cara pakai dan cara mengatur jadwal otomatis (cron)',
                ],
            ],

            // ============================================================
            // ---------- Modul 2: Docker & Containerization ----------
            // ============================================================
            'Assignment 1: Dockerize a Node.js App' => [
                'title' => 'Tantangan Mini Project: Dockerize Aplikasi Node.js Siap Produksi',
                'brief' => 'Kembangkan Latihan Coding Dockerfile sebelumnya jadi image Docker yang lebih efisien untuk aplikasi Node.js: pakai multi-stage build agar ukuran image lebih kecil, dan pastikan container bisa diakses dan dites dari luar.',
                'objectives' => [
                    'Tulis Dockerfile dengan instruksi FROM, WORKDIR, COPY, RUN, dan CMD yang tepat',
                    'Terapkan multi-stage build untuk mengecilkan ukuran image final',
                    'Bangun dan jalankan image menjadi container yang bisa diakses dan diuji dari luar (curl/browser)',
                ],
                'acceptance_criteria' => [
                    'Image berhasil dibangun tanpa error dan ukurannya lebih kecil dibanding tanpa multi-stage build',
                    'Container berjalan dan aplikasi bisa diakses dari luar sesuai port yang di-expose',
                    'Dockerfile mengikuti best practice dasar (.dockerignore dipakai, tidak menyalin node_modules dari host)',
                    'Environment variable penting dikelola lewat docker run -e atau .env, tidak di-hardcode',
                    'Dokumentasi menjelaskan cara build dan run image dengan jelas',
                ],
                'deliverables' => [
                    'Link repository GitHub berisi Dockerfile dan source code aplikasi',
                    'File dokumentasi (.md) berisi perintah build/run dan perbandingan ukuran image before/after optimasi',
                ],
            ],

            'Assignment 2: Multi-Container App with Docker Compose' => [
                'title' => 'Tantangan Mini Project: Aplikasi Multi-Container dengan Docker Compose',
                'brief' => 'Kembangkan Latihan Coding Docker Compose sebelumnya jadi aplikasi yang terdiri dari minimal 3 service (misalnya app, database, dan reverse proxy/cache), dengan volume dan komunikasi antar service yang benar.',
                'objectives' => [
                    'Definisikan minimal 3 service (app, database, dan satu service tambahan) dalam satu docker-compose.yml',
                    'Gunakan volume agar data database tetap tersimpan meskipun container dihapus/dibuat ulang',
                    'Pastikan seluruh service bisa saling berkomunikasi lewat network internal Docker Compose',
                ],
                'acceptance_criteria' => [
                    'Seluruh service berhasil naik bersamaan dengan satu perintah docker compose up',
                    'Data database tetap ada setelah container database dihapus dan dijalankan ulang (volume berfungsi)',
                    'Service app bisa terhubung ke database menggunakan nama service, bukan hardcode IP',
                    'Environment variable (kredensial database, dsb.) dikelola lewat .env, tidak di-hardcode di YAML',
                    'Dokumentasi menjelaskan arsitektur service dan cara menjalankannya',
                ],
                'deliverables' => [
                    'Link repository GitHub berisi docker-compose.yml dan source code',
                    'File dokumentasi (.md) berisi diagram/penjelasan arsitektur service dan cara setup',
                ],
            ],

            // ============================================================
            // ---------- Modul 3: CI/CD Pipeline ----------
            // ============================================================
            'Assignment 1: GitHub Actions CI Pipeline' => [
                'title' => 'Tantangan Mini Project: Pipeline CI Lengkap dengan GitHub Actions',
                'brief' => 'Kembangkan Latihan Coding workflow GitHub Actions sebelumnya jadi pipeline CI yang lebih lengkap: jalankan lint dan test secara otomatis di setiap push/PR, dan pastikan pipeline gagal (fail) saat ada masalah.',
                'objectives' => [
                    'Buat workflow GitHub Actions yang otomatis berjalan pada setiap push dan pull request',
                    'Sertakan minimal 2 job/step berbeda (misal lint dan test) dalam satu pipeline',
                    'Pastikan pipeline ditandai gagal saat salah satu step (lint/test) gagal',
                ],
                'acceptance_criteria' => [
                    'Workflow berjalan otomatis setiap ada push atau pull request ke branch yang ditentukan',
                    'Minimal 2 job/step berbeda dijalankan dan hasilnya terlihat jelas di tab Actions GitHub',
                    'Pipeline berstatus failed saat salah satu step gagal, tidak tetap hijau meski ada error',
                    'File YAML workflow terstruktur rapi dan mudah dibaca',
                    'Dokumentasi menjelaskan kapan dan bagaimana pipeline ini dijalankan',
                ],
                'deliverables' => [
                    'Link repository GitHub berisi file workflow (.github/workflows)',
                    'Screenshot hasil run pipeline (sukses dan gagal) di tab Actions',
                ],
            ],

            'Assignment 2: Automated Deployment Workflow' => [
                'title' => 'Tantangan Mini Project: Pipeline CI/CD dengan Strategi Rollback',
                'brief' => 'Kembangkan Latihan Coding CI/CD sebelumnya jadi pipeline penuh yang otomatis men-deploy aplikasi setelah test berhasil, lengkap dengan strategi rollback sederhana jika deployment baru bermasalah.',
                'objectives' => [
                    'Perluas pipeline CI menjadi CD yang men-deploy otomatis setelah seluruh test lolos',
                    'Rancang strategi rollback sederhana (misal deploy versi image sebelumnya) jika deployment baru gagal',
                    'Dokumentasikan alur deployment dari commit hingga live, termasuk kondisi rollback',
                ],
                'acceptance_criteria' => [
                    'Deployment berjalan otomatis hanya setelah seluruh test pada pipeline CI berhasil',
                    'Deployment yang gagal tidak membuat aplikasi lama ikut down (ada mekanisme aman)',
                    'Strategi rollback dijelaskan dengan jelas dan bisa dijalankan (manual atau otomatis)',
                    'Pipeline mencatat log/riwayat setiap deployment yang dilakukan',
                    'Dokumentasi menjelaskan seluruh alur dari commit hingga live dan skenario rollback',
                ],
                'deliverables' => [
                    'Link repository GitHub berisi file workflow CI/CD',
                    'File dokumentasi (.md) berisi diagram alur deployment dan langkah rollback',
                ],
            ],

            // ============================================================
            // ---------- Modul 4: Kubernetes & Orchestration ----------
            // ============================================================
            'Assignment 1: Deploy App to Kubernetes Cluster' => [
                'title' => 'Tantangan Mini Project: Deploy Aplikasi Multi-Replica ke Kubernetes',
                'brief' => 'Kembangkan Latihan Coding Deployment/Service sebelumnya jadi konfigurasi Kubernetes yang lebih lengkap: aplikasi berjalan dengan minimal 2 replica dan bisa diakses secara stabil meski salah satu pod di-restart.',
                'objectives' => [
                    'Tulis konfigurasi Deployment dengan minimal 2 replica untuk aplikasi target',
                    'Tulis konfigurasi Service yang mengekspos aplikasi secara stabil ke luar cluster',
                    'Uji ketahanan aplikasi dengan menghapus salah satu pod secara manual dan amati pemulihannya',
                ],
                'acceptance_criteria' => [
                    'Aplikasi berjalan dengan jumlah replica sesuai konfigurasi dan tetap terlihat di kubectl get pods',
                    'Service berhasil mengarahkan trafik ke pod yang sehat, aplikasi tetap bisa diakses',
                    'Saat satu pod dihapus manual, Kubernetes secara otomatis membuat pod pengganti (self-healing)',
                    'Konfigurasi YAML (Deployment & Service) tertata rapi dan didokumentasikan',
                    'Pengujian ketahanan didokumentasikan dengan langkah dan hasil yang jelas',
                ],
                'deliverables' => [
                    'File YAML konfigurasi Deployment dan Service',
                    'File dokumentasi (.md) berisi hasil pengujian self-healing (sebelum/sesudah pod dihapus)',
                ],
            ],

            'Assignment 2: Autoscaling Configuration Exercise' => [
                'title' => 'Tantangan Mini Project: Autoscaling dengan Pemisahan Config & Secret',
                'brief' => 'Kembangkan Latihan Coding HPA sebelumnya jadi konfigurasi lengkap: aplikasi yang bisa auto-scale berdasarkan CPU, dengan konfigurasi non-rahasia disimpan di ConfigMap dan data sensitif disimpan terpisah di Secret.',
                'objectives' => [
                    'Konfigurasikan Horizontal Pod Autoscaler (HPA) berdasarkan penggunaan CPU untuk aplikasi target',
                    'Pisahkan konfigurasi non-rahasia (misal feature flag) ke ConfigMap dan data sensitif (misal API key) ke Secret',
                    'Uji perilaku scaling dengan memberi beban CPU tinggi pada aplikasi',
                ],
                'acceptance_criteria' => [
                    'HPA berhasil menambah jumlah replica saat penggunaan CPU melewati threshold yang ditentukan',
                    'Jumlah replica kembali turun setelah beban CPU kembali normal',
                    'Data sensitif tidak pernah tersimpan di ConfigMap, seluruhnya ada di Secret',
                    'Aplikasi tetap berfungsi normal saat membaca konfigurasi dari ConfigMap maupun Secret',
                    'Hasil pengujian scaling (before/after beban) didokumentasikan dengan jelas',
                ],
                'deliverables' => [
                    'File YAML konfigurasi HPA, ConfigMap, dan Secret',
                    'File dokumentasi (.md) berisi hasil pengujian autoscaling',
                ],
            ],

            // ============================================================
            // ---------- Modul 5: Monitoring & Logging ----------
            // ============================================================
            'Assignment 1: Prometheus Monitoring Setup' => [
                'title' => 'Tantangan Mini Project: Setup Monitoring Aplikasi dengan Prometheus',
                'brief' => 'Kembangkan Latihan Coding endpoint /metrics sebelumnya jadi setup monitoring yang lebih lengkap: expose minimal 3 metrics penting dari aplikasi dan konfigurasikan Prometheus untuk melakukan scrape secara berkala.',
                'objectives' => [
                    'Sediakan endpoint /metrics pada aplikasi yang mengekspos minimal 3 metrics penting (response time, error rate, resource usage)',
                    'Konfigurasikan Prometheus untuk melakukan scrape endpoint tersebut secara berkala',
                    'Verifikasi metrics yang di-scrape muncul dan bisa di-query lewat Prometheus',
                ],
                'acceptance_criteria' => [
                    'Endpoint /metrics mengembalikan data dalam format yang bisa dibaca Prometheus',
                    'Minimal 3 metrics yang relevan (bukan sekadar uptime) berhasil di-scrape dan terlihat di Prometheus',
                    'Konfigurasi scrape (scrape_configs) di Prometheus benar dan berjalan sesuai interval yang ditentukan',
                    'Metrics yang dipilih relevan untuk memantau kesehatan/performa aplikasi',
                    'Dokumentasi menjelaskan metrics apa saja yang dipantau dan alasannya',
                ],
                'deliverables' => [
                    'File konfigurasi Prometheus (prometheus.yml)',
                    'File dokumentasi (.md) berisi daftar metrics yang di-expose dan screenshot hasil scrape',
                ],
            ],

            'Assignment 2: Centralized Logging with ELK Stack' => [
                'title' => 'Tantangan Mini Project: Centralized Logging dari Beberapa Sumber',
                'brief' => 'Kembangkan Latihan Coding ELK Stack sebelumnya jadi sistem logging terpusat yang mengumpulkan log dari minimal 2 sumber berbeda (misal 2 service/container berbeda), dengan struktur log yang konsisten dan bisa dicari.',
                'objectives' => [
                    'Kumpulkan log dari minimal 2 sumber berbeda ke satu tempat terpusat menggunakan pendekatan ELK Stack',
                    'Pastikan struktur log konsisten antar sumber (format timestamp, severity, request ID)',
                    'Lakukan pencarian log berdasarkan kriteria tertentu (misal berdasarkan severity atau rentang waktu) lewat Kibana',
                ],
                'acceptance_criteria' => [
                    'Log dari kedua sumber berhasil masuk dan terlihat di dashboard Kibana',
                    'Struktur log konsisten (field timestamp, severity, request ID/source ada di semua log)',
                    'Pencarian/filter log berdasarkan kriteria tertentu menghasilkan hasil yang tepat',
                    'Log yang error/severity tinggi bisa dibedakan dengan mudah dari log biasa',
                    'Dokumentasi menjelaskan arsitektur pipeline logging (sumber → Logstash/Fluentd → Elasticsearch → Kibana)',
                ],
                'deliverables' => [
                    'File konfigurasi Logstash/Fluentd dan docker-compose untuk ELK Stack',
                    'File dokumentasi (.md) berisi diagram arsitektur logging dan screenshot hasil pencarian di Kibana',
                ],
            ],

            // ============================================================
            // ---------- Modul 6: Git & Infrastructure Workflow ----------
            // ============================================================
            'Assignment 1: Terraform Infrastructure Setup' => [
                'title' => 'Tantangan Mini Project: Infrastructure as Code dengan Terraform',
                'brief' => 'Kembangkan Latihan Coding Terraform sebelumnya jadi konfigurasi infrastruktur yang lebih lengkap: definisikan minimal 2 resource terkait (misal instance server dan storage/network-nya), disimpan rapi di Git untuk direview sebelum diterapkan.',
                'objectives' => [
                    'Definisikan minimal 2 resource infrastruktur yang saling terkait sebagai kode Terraform',
                    'Susun struktur file Terraform yang rapi (variables, main, outputs terpisah)',
                    'Simpan seluruh konfigurasi di Git dengan riwayat commit yang bisa direview sebelum di-apply',
                ],
                'acceptance_criteria' => [
                    'terraform plan dan terraform apply berjalan tanpa error dan menghasilkan resource yang sesuai',
                    'Resource yang saling terkait (misal server dan network-nya) terhubung dengan benar',
                    'Struktur file (variables.tf, main.tf, outputs.tf) terpisah dan konsisten',
                    'Konfigurasi sensitif (kredensial cloud) tidak ikut ter-commit ke Git',
                    'Dokumentasi menjelaskan resource yang dibuat dan cara menjalankan Terraform',
                ],
                'deliverables' => [
                    'Link repository GitHub berisi file Terraform (.tf)',
                    'File dokumentasi (.md) berisi output terraform plan/apply dan penjelasan resource',
                ],
            ],

            'Assignment 2: GitOps Deployment with ArgoCD' => [
                'title' => 'Tantangan Mini Project: Alur GitOps dengan ArgoCD',
                'brief' => 'Kembangkan Latihan Coding ArgoCD sebelumnya jadi alur GitOps yang lebih lengkap: perubahan konfigurasi Kubernetes di repository Git otomatis tersinkron ke cluster, lengkap dengan analisis perbandingan terhadap CI/CD tradisional.',
                'objectives' => [
                    'Hubungkan ArgoCD dengan repository Git berisi konfigurasi Kubernetes aplikasi target',
                    'Buktikan perubahan konfigurasi di Git otomatis diterapkan ke cluster tanpa perintah manual kubectl apply',
                    'Tulis analisis singkat perbedaan pendekatan GitOps (pull-based) dengan CI/CD tradisional (push-based)',
                ],
                'acceptance_criteria' => [
                    'Perubahan yang di-commit ke repository Git tersinkron otomatis ke cluster lewat ArgoCD',
                    'Status sinkronisasi (Synced/OutOfSync) di ArgoCD terlihat jelas dan sesuai kondisi sebenarnya',
                    'Struktur repository GitOps (manifest Kubernetes) tertata rapi dan mudah ditelusuri',
                    'Analisis GitOps vs CI/CD tradisional mencakup minimal 2 perbedaan konkret (arah sinkronisasi, kontrol akses, dsb.)',
                    'Dokumentasi menjelaskan cara mereproduksi setup ArgoCD ini dari awal',
                ],
                'deliverables' => [
                    'Link repository GitHub berisi manifest Kubernetes yang dikelola ArgoCD',
                    'File dokumentasi (.md) berisi hasil analisis GitOps vs CI/CD tradisional dan screenshot status sync ArgoCD',
                ],
            ],
        ];
    }
}