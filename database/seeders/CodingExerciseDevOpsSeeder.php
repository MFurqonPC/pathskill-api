<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\CodingExercise;
use Illuminate\Database\Seeder;

class CodingExerciseDevOpsSeeder extends Seeder
{
    /**
     * Coding exercise untuk seluruh assignment di career DevOps Engineer,
     * mengikuti pola per-career yang sama seperti
     * CodingExerciseFullStackSeeder / CodingExerciseBackendSeeder /
     * CodingExerciseDataAnalystSeeder.
     *
     * Semua assignment di career ini bersifat teknis (Bash, Dockerfile,
     * YAML, Terraform), jadi tidak ada yang di-skip.
     *
     * Jalankan setelah LearningPathSeeder dan AssignmentDetailSeeder
     * (assignment-nya harus sudah ada). Idempotent lewat updateOrCreate
     * berdasarkan assignment_id.
     *
     * Jalankan:
     *   php artisan db:seed --class=CodingExerciseDevOpsSeeder
     */
    public function run(): void
    {
        foreach ($this->exerciseData() as $assignmentTitle => $data) {
            $assignment = Assignment::where('title', $assignmentTitle)->first();

            if (! $assignment) {
                $this->command?->warn("CodingExerciseDevOpsSeeder: assignment tidak ditemukan — {$assignmentTitle}");
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
            // Modul 1: Linux Fundamentals
            // ============================================================

            'Assignment 1: Linux Server Setup Exercise' => [
                'title' => 'Latihan: Permission & Service Management',
                'description' => 'Lengkapi perintah di bawah untuk mengatur permission file yang benar dan mengelola service menggunakan systemd, dua hal dasar yang sering dibutuhkan saat menyiapkan server.',
                'learning_objectives' => [
                    'Menerapkan chmod/chown untuk mengatur permission file dengan tepat',
                    'Mengelola service dengan systemd (start, enable, status)',
                ],
                'requirements' => [
                    'File deploy.sh harus bisa dieksekusi oleh owner saja (permission 700)',
                    'Kepemilikan folder /var/www/app diubah menjadi user "deploy" dan group "www-data"',
                    'Service nginx dijalankan dan diaktifkan agar otomatis start saat boot',
                    'Status service nginx dicek untuk memastikan berjalan dengan benar',
                ],
                'test_cases' => [
                    'ls -l deploy.sh menunjukkan permission -rwx------ (700)',
                    'ls -l /var/www/app menunjukkan owner deploy dan group www-data',
                    'systemctl status nginx menunjukkan status "active (running)"',
                    'nginx tetap otomatis berjalan setelah simulasi reboot (systemctl is-enabled nginx menunjukkan "enabled")',
                ],
                'language' => 'bash',
                'starter_code' => <<<'CODE'
# TODO 1: beri permission 700 (rwx untuk owner saja) pada deploy.sh


# TODO 2: ubah kepemilikan folder /var/www/app menjadi user 'deploy'
#         dan group 'www-data'


# TODO 3: jalankan service nginx


# TODO 4: aktifkan nginx agar otomatis start saat boot


# TODO 5: cek status nginx untuk memastikan sudah berjalan
CODE,
                'hint' => 'chmod 700 deploy.sh | chown deploy:www-data /var/www/app | systemctl start nginx | systemctl enable nginx | systemctl status nginx',
            ],

            'Assignment 2: Automated Backup Shell Script' => [
                'title' => 'Latihan: Script Backup Otomatis Berbasis Tanggal',
                'description' => 'Lengkapi shell script di bawah supaya melakukan backup folder /var/www/app menjadi file .tar.gz dengan nama yang menyertakan tanggal saat itu, dan menyimpan hasilnya di folder backup.',
                'learning_objectives' => [
                    'Menggunakan variabel Bash untuk membuat penamaan file yang dinamis',
                    'Menerapkan struktur script dasar (variabel, kondisi, perintah) agar bisa dijalankan ulang secara konsisten',
                ],
                'requirements' => [
                    'Nama file backup harus menyertakan tanggal saat ini, format backup_YYYY-MM-DD.tar.gz',
                    'Script membuat folder tujuan backup jika belum ada (mkdir -p)',
                    'Script memberi pesan sukses/gagal setelah proses backup selesai, berdasarkan exit code tar',
                ],
                'test_cases' => [
                    'Menjalankan script menghasilkan file dengan nama sesuai format tanggal hari itu',
                    'Menjalankan script dua kali di hari yang sama tidak error walau folder tujuan sudah ada',
                    'Jika proses tar gagal (misal folder sumber tidak ada), script mencetak pesan gagal, bukan diam saja',
                ],
                'language' => 'bash',
                'starter_code' => <<<'CODE'
#!/bin/bash

SUMBER="/var/www/app"
TUJUAN="/backup"

# TODO 1: buat variabel TANGGAL berisi tanggal hari ini format YYYY-MM-DD

# TODO 2: pastikan folder TUJUAN ada (buat jika belum ada)

# TODO 3: jalankan tar untuk mem-backup SUMBER menjadi
#         $TUJUAN/backup_$TANGGAL.tar.gz

# TODO 4: cek exit code dari tar ($?), cetak pesan sukses/gagal
CODE,
                'hint' => 'TANGGAL=$(date +%F) | mkdir -p "$TUJUAN" | tar -czf "$TUJUAN/backup_$TANGGAL.tar.gz" "$SUMBER" | if [ $? -eq 0 ]; then echo "Backup berhasil"; else echo "Backup gagal"; fi',
            ],

            // ============================================================
            // Modul 2: Docker & Containerization
            // ============================================================

            'Assignment 1: Dockerize a Node.js App' => [
                'title' => 'Latihan: Tulis Dockerfile untuk Aplikasi Node.js',
                'description' => 'Lengkapi Dockerfile di bawah supaya aplikasi Node.js bisa di-build menjadi image yang efisien dan berjalan sebagai container yang bisa diakses dari luar.',
                'learning_objectives' => [
                    'Menulis instruksi Dockerfile (FROM, WORKDIR, COPY, RUN, CMD) dengan urutan yang tepat',
                    'Memahami cara memanfaatkan Docker layer caching agar build lebih cepat',
                ],
                'requirements' => [
                    'Gunakan base image node dengan versi tertentu (bukan "latest") untuk konsistensi',
                    'Copy package.json dan install dependency SEBELUM copy seluruh source code, agar caching layer lebih efisien',
                    'Expose port yang digunakan aplikasi (misal 3000)',
                    'CMD menjalankan aplikasi dengan node index.js atau npm start',
                ],
                'test_cases' => [
                    'docker build berhasil tanpa error',
                    'Mengubah source code (tanpa mengubah package.json) tidak memicu instalasi ulang dependency saat rebuild',
                    'Container yang dijalankan bisa diakses dari host melalui port yang di-expose',
                ],
                'language' => 'dockerfile',
                'starter_code' => <<<'CODE'
# TODO 1: gunakan base image node dengan versi spesifik, misal node:20-alpine
FROM ___

WORKDIR /app

# TODO 2: copy package.json (dan package-lock.json) dulu, install dependency
#         SEBELUM copy seluruh source code

# TODO 3: copy seluruh source code aplikasi

# TODO 4: expose port yang dipakai aplikasi (misal 3000)

# TODO 5: jalankan aplikasi dengan CMD
CODE,
                'hint' => 'FROM node:20-alpine → COPY package*.json ./ → RUN npm install → COPY . . → EXPOSE 3000 → CMD ["node", "index.js"] — urutan COPY package.json sebelum COPY . . adalah kunci caching yang efisien.',
            ],

            'Assignment 2: Multi-Container App with Docker Compose' => [
                'title' => 'Latihan: docker-compose.yml untuk App + Database',
                'description' => 'Lengkapi file docker-compose.yml di bawah supaya menjalankan service aplikasi dan database dalam satu perintah, dengan volume agar data database tidak hilang saat container dihapus.',
                'learning_objectives' => [
                    'Mendefinisikan beberapa service dalam satu file docker-compose.yml',
                    'Menggunakan volume agar data persist meskipun container dihapus dan dibuat ulang',
                ],
                'requirements' => [
                    'Service app di-build dari Dockerfile lokal, service db menggunakan image postgres resmi',
                    'Service app harus depends_on service db agar urutan start benar',
                    'Volume digunakan untuk menyimpan data postgres agar tidak hilang saat docker compose down',
                    'Environment variable database (user, password, nama db) didefinisikan lewat environment, bukan hardcode di image',
                ],
                'test_cases' => [
                    'docker compose up berhasil menjalankan kedua service tanpa error',
                    'Setelah docker compose down lalu up lagi, data yang sebelumnya disimpan di database masih ada',
                    'Service app bisa terhubung ke service db menggunakan nama service sebagai hostname',
                ],
                'language' => 'yaml',
                'starter_code' => <<<'CODE'
version: '3.8'

services:
  app:
    build: .
    ports:
      - "3000:3000"
    # TODO 1: tambahkan depends_on agar app menunggu db siap
    environment:
      - DATABASE_HOST=db

  db:
    image: postgres:16
    # TODO 2: tambahkan environment untuk POSTGRES_USER, POSTGRES_PASSWORD,
    #         dan POSTGRES_DB

    # TODO 3: tambahkan volume agar data postgres tidak hilang
    #         saat container dihapus

# TODO 4: definisikan named volume di bagian top-level 'volumes'
CODE,
                'hint' => 'depends_on: [db] pada app. Untuk db: environment: [POSTGRES_USER=admin, POSTGRES_PASSWORD=secret, POSTGRES_DB=appdb] dan volumes: ["db_data:/var/lib/postgresql/data"], lalu di top-level tambahkan volumes: { db_data: {} }.',
            ],

            // ============================================================
            // Modul 3: CI/CD Pipeline
            // ============================================================

            'Assignment 1: GitHub Actions CI Pipeline' => [
                'title' => 'Latihan: Workflow CI yang Menjalankan Test Otomatis',
                'description' => 'Lengkapi file workflow GitHub Actions di bawah supaya otomatis menjalankan test setiap kali ada push atau pull request ke branch main.',
                'learning_objectives' => [
                    'Menulis struktur job dan step dalam file YAML workflow GitHub Actions',
                    'Memicu workflow otomatis berdasarkan event push dan pull_request',
                ],
                'requirements' => [
                    'Workflow terpicu pada event push dan pull_request ke branch main',
                    'Job menggunakan runner ubuntu-latest',
                    'Step meng-checkout kode, setup Node.js, install dependency, lalu menjalankan test',
                    'Pipeline harus gagal (job merah) jika test gagal, bukan tetap hijau',
                ],
                'test_cases' => [
                    'Push ke branch main memicu workflow berjalan otomatis',
                    'Pull request ke branch main juga memicu workflow yang sama',
                    'Jika salah satu test gagal, keseluruhan job berstatus failed, bukan success',
                ],
                'language' => 'yaml',
                'starter_code' => <<<'CODE'
name: CI Pipeline

# TODO 1: definisikan trigger 'on' untuk push dan pull_request ke branch main

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      # TODO 2: checkout kode menggunakan actions/checkout

      # TODO 3: setup Node.js menggunakan actions/setup-node

      # TODO 4: install dependency (npm install / npm ci)

      # TODO 5: jalankan test (npm test)
CODE,
                'hint' => 'on: { push: { branches: [main] }, pull_request: { branches: [main] } } — steps: uses actions/checkout@v4, actions/setup-node@v4 (with node-version), lalu run: npm ci dan run: npm test.',
            ],

            'Assignment 2: Automated Deployment Workflow' => [
                'title' => 'Latihan: Perluas CI Menjadi CD dengan Rollback Sederhana',
                'description' => 'Lengkapi workflow di bawah supaya setelah test berhasil, aplikasi otomatis di-deploy, dan tambahkan langkah sederhana yang memungkinkan rollback jika deployment gagal.',
                'learning_objectives' => [
                    'Memperluas pipeline CI menjadi CD yang deploy otomatis setelah test berhasil',
                    'Menerapkan strategi rollback dasar ketika deployment baru bermasalah',
                ],
                'requirements' => [
                    'Job deploy hanya berjalan jika job test berhasil (needs: test)',
                    'Step deploy men-tag image dengan versi/commit SHA agar bisa dilacak dan di-rollback',
                    'Sediakan step/dokumentasi rollback yang menjelaskan cara kembali ke image versi sebelumnya',
                ],
                'test_cases' => [
                    'Job deploy tidak berjalan sama sekali jika job test gagal',
                    'Image yang di-deploy diberi tag yang unik (bukan selalu "latest") sehingga versi sebelumnya masih bisa diakses',
                    'Ada langkah/dokumentasi yang jelas untuk rollback ke image versi sebelumnya',
                ],
                'language' => 'yaml',
                'starter_code' => <<<'CODE'
name: CI/CD Pipeline

on:
  push:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - run: npm ci
      - run: npm test

  deploy:
    runs-on: ubuntu-latest
    # TODO 1: pastikan job ini hanya berjalan kalau job 'test' sukses
    steps:
      - uses: actions/checkout@v4

      # TODO 2: build & tag image docker menggunakan commit SHA
      #         (misal docker build -t myapp:${{ github.sha }} .)

      # TODO 3: push image ke registry

      # TODO 4: deploy image yang baru di-tag ke server/cluster

      # TODO 5 (dokumentasi): tambahkan komentar cara rollback,
      #         misal deploy ulang image dengan tag SHA sebelumnya
CODE,
                'hint' => 'needs: test pada job deploy. Tag image: docker build -t myapp:${{ github.sha }} . — rollback cukup dijelaskan sebagai: jalankan ulang step deploy dengan tag SHA commit sebelumnya yang diketahui stabil.',
            ],

            // ============================================================
            // Modul 4: Kubernetes & Orchestration
            // ============================================================

            'Assignment 1: Deploy App to Kubernetes Cluster' => [
                'title' => 'Latihan: Deployment & Service Kubernetes',
                'description' => 'Lengkapi manifest Kubernetes di bawah supaya aplikasi berjalan sebagai Deployment dengan beberapa replica, dan bisa diakses dari luar cluster lewat Service.',
                'learning_objectives' => [
                    'Menulis konfigurasi Deployment dengan jumlah replica dan container image yang tepat',
                    'Menghubungkan Deployment dengan Service agar Pod bisa diakses',
                ],
                'requirements' => [
                    'Deployment menjalankan minimal 2 replica Pod',
                    'Container di dalam Pod menggunakan image aplikasi dan expose port yang sesuai (containerPort)',
                    'Service bertipe yang bisa diakses dari luar (misal NodePort atau LoadBalancer)',
                    'Selector di Service harus cocok dengan label yang didefinisikan di Deployment/Pod',
                ],
                'test_cases' => [
                    'kubectl get pods menunjukkan minimal 2 Pod berjalan dengan status Running',
                    'kubectl get svc menunjukkan Service dengan selector yang cocok dengan label Pod',
                    'Aplikasi bisa diakses melalui Service (curl ke NodePort/LoadBalancer berhasil)',
                ],
                'language' => 'yaml',
                'starter_code' => <<<'CODE'
apiVersion: apps/v1
kind: Deployment
metadata:
  name: myapp-deployment
spec:
  # TODO 1: set jumlah replica minimal 2
  replicas: ___
  selector:
    matchLabels:
      app: myapp
  template:
    metadata:
      labels:
        app: myapp
    spec:
      containers:
        - name: myapp
          image: myapp:latest
          # TODO 2: tambahkan containerPort sesuai port aplikasi (misal 3000)
---
apiVersion: v1
kind: Service
metadata:
  name: myapp-service
spec:
  # TODO 3: gunakan selector yang cocok dengan label Pod (app: myapp)
  selector:
    app: ___
  ports:
    - port: 80
      targetPort: 3000
  # TODO 4: set type Service agar bisa diakses dari luar cluster
  type: ___
CODE,
                'hint' => 'replicas: 2. containerPort: 3000 di bawah ports:. selector.app: myapp di Service. type: NodePort (atau LoadBalancer kalau di cloud provider yang mendukung).',
            ],

            'Assignment 2: Autoscaling Configuration Exercise' => [
                'title' => 'Latihan: HPA, ConfigMap, dan Secret',
                'description' => 'Lengkapi konfigurasi di bawah untuk mengaktifkan Horizontal Pod Autoscaler berdasarkan CPU, sekaligus memisahkan konfigurasi biasa (ConfigMap) dari data sensitif (Secret).',
                'learning_objectives' => [
                    'Mengonfigurasi Horizontal Pod Autoscaler berbasis pemakaian CPU',
                    'Memisahkan konfigurasi non-rahasia (ConfigMap) dari data sensitif (Secret)',
                ],
                'requirements' => [
                    'HPA mengatur minReplicas dan maxReplicas yang wajar (misal 2-10)',
                    'HPA melakukan scale berdasarkan target rata-rata CPU utilization (misal 70%)',
                    'ConfigMap menyimpan konfigurasi non-rahasia (misal APP_ENV, LOG_LEVEL)',
                    'Secret menyimpan data sensitif (misal DB_PASSWORD), bukan ditaruh di ConfigMap',
                ],
                'test_cases' => [
                    'kubectl get hpa menunjukkan target CPU dan rentang replica sesuai konfigurasi',
                    'Saat beban CPU naik melebihi target, jumlah replica bertambah otomatis (hingga maxReplicas)',
                    'Data sensitif seperti password TIDAK muncul di dalam ConfigMap',
                ],
                'language' => 'yaml',
                'starter_code' => <<<'CODE'
apiVersion: autoscaling/v2
kind: HorizontalPodAutoscaler
metadata:
  name: myapp-hpa
spec:
  scaleTargetRef:
    apiVersion: apps/v1
    kind: Deployment
    name: myapp-deployment
  # TODO 1: set minReplicas dan maxReplicas
  minReplicas: ___
  maxReplicas: ___
  metrics:
    - type: Resource
      resource:
        name: cpu
        target:
          type: Utilization
          # TODO 2: set target rata-rata CPU utilization, misal 70
          averageUtilization: ___
---
apiVersion: v1
kind: ConfigMap
metadata:
  name: myapp-config
data:
  # TODO 3: tambahkan konfigurasi non-rahasia, misal APP_ENV dan LOG_LEVEL
---
apiVersion: v1
kind: Secret
metadata:
  name: myapp-secret
type: Opaque
data:
  # TODO 4: tambahkan data sensitif (misal DB_PASSWORD) dalam bentuk base64
CODE,
                'hint' => 'minReplicas: 2, maxReplicas: 10, averageUtilization: 70. ConfigMap data: { APP_ENV: "production", LOG_LEVEL: "info" }. Secret data harus base64-encoded, misal DB_PASSWORD: <hasil base64 dari password>.',
            ],

            // ============================================================
            // Modul 5: Monitoring & Logging
            // ============================================================

            'Assignment 1: Prometheus Monitoring Setup' => [
                'title' => 'Latihan: Endpoint /metrics untuk Prometheus',
                'description' => 'Lengkapi endpoint /metrics di aplikasi Express di bawah supaya bisa di-scrape oleh Prometheus, menampilkan metrik dasar seperti jumlah request dan response time.',
                'learning_objectives' => [
                    'Menyediakan endpoint /metrics dalam format yang bisa dibaca Prometheus',
                    'Mengidentifikasi metrik penting untuk dipantau (request count, response time)',
                ],
                'requirements' => [
                    'Endpoint GET /metrics mengembalikan data dalam format teks yang bisa di-scrape Prometheus',
                    'Metrik mencakup minimal jumlah total request yang diterima aplikasi',
                    'Metrik response time dicatat per request (histogram/summary), bukan hanya request terakhir',
                    'Middleware pencatat metrik dipasang di seluruh route, bukan hanya satu endpoint tertentu',
                ],
                'test_cases' => [
                    'GET /metrics mengembalikan status 200 dengan format teks Prometheus (bukan JSON biasa)',
                    'Setelah beberapa request ke endpoint lain, angka request count di /metrics bertambah',
                    'Prometheus dapat melakukan scrape endpoint ini tanpa error format',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
const express = require('express');
const client = require('prom-client');

const app = express();

// TODO 1: buat Counter untuk menghitung total request
const requestCounter = null;

// TODO 2: buat Histogram untuk mencatat response time per request
const responseTimeHistogram = null;

app.use((req, res, next) => {
  const end = responseTimeHistogram ? responseTimeHistogram.startTimer() : null;
  res.on('finish', () => {
    // TODO 3: increment requestCounter setiap request selesai
    // TODO 4: catat waktu response menggunakan end() dari histogram
  });
  next();
});

app.get('/metrics', async (req, res) => {
  // TODO 5: kembalikan metrics dalam format Prometheus
  //         (client.register.metrics(), dengan Content-Type yang sesuai)
});

module.exports = app;
CODE,
                'hint' => 'requestCounter = new client.Counter({ name: "http_requests_total", help: "Total HTTP requests" }); responseTimeHistogram = new client.Histogram({ name: "http_response_time_seconds", help: "Response time" }); di /metrics: res.set("Content-Type", client.register.contentType); res.end(await client.register.metrics());',
            ],

            'Assignment 2: Centralized Logging with ELK Stack' => [
                'title' => 'Latihan: Konfigurasi Filebeat untuk Kirim Log ke Elasticsearch',
                'description' => 'Lengkapi konfigurasi Filebeat di bawah supaya log aplikasi dari beberapa file dikumpulkan dan dikirim ke Elasticsearch secara terpusat, dengan format yang konsisten agar mudah dicari di Kibana.',
                'learning_objectives' => [
                    'Mengonfigurasi Filebeat untuk mengumpulkan log dari beberapa sumber',
                    'Memastikan log terkirim ke Elasticsearch dengan struktur yang konsisten',
                ],
                'requirements' => [
                    'Filebeat memantau file log dari path aplikasi (misal /var/log/myapp/*.log)',
                    'Output Filebeat diarahkan ke Elasticsearch, bukan langsung ke Kibana',
                    'Tambahkan field tambahan (misal service name) agar log dari berbagai service bisa dibedakan saat dicari',
                ],
                'test_cases' => [
                    'Filebeat berhasil membaca file log dari path yang dikonfigurasi',
                    'Log yang terkirim ke Elasticsearch bisa dicari berdasarkan field service name yang ditambahkan',
                    'Konfigurasi output.elasticsearch mengarah ke host Elasticsearch yang benar',
                ],
                'language' => 'yaml',
                'starter_code' => <<<'CODE'
filebeat.inputs:
  - type: log
    enabled: true
    # TODO 1: tentukan path log yang akan dipantau, misal /var/log/myapp/*.log
    paths:
      - ___

    # TODO 2: tambahkan field tambahan untuk membedakan service, misal service: myapp
    fields:

# TODO 3: arahkan output ke Elasticsearch (host & port sesuai instalasi)
output.elasticsearch:
  hosts: ["___"]
CODE,
                'hint' => 'paths: ["/var/log/myapp/*.log"]. fields: { service: "myapp" } (tambahkan fields_under_root: true kalau mau field ini jadi top-level). output.elasticsearch.hosts: ["localhost:9200"].',
            ],

            // ============================================================
            // Modul 6: Git & Infrastructure Workflow
            // ============================================================

            'Assignment 1: Terraform Infrastructure Setup' => [
                'title' => 'Latihan: Definisikan Instance Server dengan Terraform',
                'description' => 'Lengkapi konfigurasi Terraform di bawah untuk mendefinisikan sebuah instance server (misal AWS EC2) sebagai kode, lengkap dengan variabel yang bisa disesuaikan tanpa mengubah kode utama.',
                'learning_objectives' => [
                    'Mendefinisikan infrastruktur sebagai kode menggunakan resource block Terraform',
                    'Menggunakan variable agar konfigurasi bisa disesuaikan tanpa mengubah kode inti',
                ],
                'requirements' => [
                    'Resource instance menggunakan variable untuk instance_type, bukan nilai hardcode',
                    'Variable instance_type punya default value yang wajar (misal "t2.micro")',
                    'Tag Name pada instance menyertakan nama project agar mudah diidentifikasi',
                    'terraform plan bisa dijalankan tanpa error sebelum apply',
                ],
                'test_cases' => [
                    'terraform validate tidak menunjukkan error konfigurasi',
                    'Mengubah nilai variable instance_type mengubah tipe instance yang akan dibuat, tanpa menyentuh resource block',
                    'Instance yang direncanakan memiliki tag Name yang jelas',
                ],
                'language' => 'hcl',
                'starter_code' => <<<'CODE'
provider "aws" {
  region = "ap-southeast-1"
}

# TODO 1: definisikan variable "instance_type" dengan default value "t2.micro"

resource "aws_instance" "app_server" {
  ami           = "ami-0123456789abcdef0"
  # TODO 2: gunakan var.instance_type di sini, bukan hardcode

  tags = {
    # TODO 3: tambahkan tag Name yang menyertakan nama project, misal "myapp-server"
  }
}
CODE,
                'hint' => 'variable "instance_type" { default = "t2.micro" } lalu instance_type = var.instance_type dan tags = { Name = "myapp-server" }.',
            ],

            'Assignment 2: GitOps Deployment with ArgoCD' => [
                'title' => 'Latihan: Application Manifest untuk ArgoCD',
                'description' => 'Lengkapi manifest Application ArgoCD di bawah supaya cluster otomatis melakukan sync setiap kali ada perubahan konfigurasi di repository Git, mengikuti alur GitOps (pull-based).',
                'learning_objectives' => [
                    'Mendefinisikan Application ArgoCD yang menunjuk ke repository Git sebagai sumber kebenaran',
                    'Mengaktifkan automated sync agar perubahan di Git otomatis diterapkan ke cluster',
                ],
                'requirements' => [
                    'source.repoURL menunjuk ke repository Git yang berisi manifest Kubernetes',
                    'destination.server dan destination.namespace menentukan ke cluster/namespace mana deploy dilakukan',
                    'syncPolicy.automated diaktifkan agar ArgoCD otomatis sync tanpa perlu klik manual',
                    'Tambahkan opsi selfHeal agar ArgoCD otomatis memperbaiki drift jika ada perubahan manual di cluster',
                ],
                'test_cases' => [
                    'ArgoCD menunjukkan status "Synced" dan "Healthy" setelah manifest diterapkan',
                    'Perubahan baru di repository Git (misal ganti jumlah replica) otomatis ter-sync ke cluster tanpa intervensi manual',
                    'Perubahan manual langsung di cluster (yang menyimpang dari Git) otomatis dikembalikan sesuai konfigurasi Git (self-heal)',
                ],
                'language' => 'yaml',
                'starter_code' => <<<'CODE'
apiVersion: argoproj.io/v1alpha1
kind: Application
metadata:
  name: myapp
  namespace: argocd
spec:
  project: default
  source:
    # TODO 1: isi repoURL menuju repository Git berisi manifest Kubernetes
    repoURL: ___
    targetRevision: main
    path: manifests
  destination:
    # TODO 2: tentukan server (cluster) dan namespace tujuan deploy
    server: ___
    namespace: ___
  syncPolicy:
    automated:
      # TODO 3: aktifkan selfHeal agar drift manual otomatis diperbaiki
      selfHeal: ___
      prune: true
CODE,
                'hint' => 'repoURL: "https://github.com/namaorg/myapp-manifests.git", server: "https://kubernetes.default.svc", namespace: "production", selfHeal: true.',
            ],
        ];
    }
}