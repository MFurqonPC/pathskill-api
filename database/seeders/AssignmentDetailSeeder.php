<?php

namespace Database\Seeders;

use App\Models\Assignment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AssignmentDetailSeeder extends Seeder
{
    /**
     * Peta detail assignment: judul assignment (harus PERSIS sama dengan
     * kolom `title` di database, termasuk prefix "Assignment N: ...") ke
     * data learning_outcomes/skills_learned/prerequisites/tools/evaluation_rubrics.
     *
     * Seluruh entri di-generate berdasarkan isi lesson tiap modul di
     * LearningPathSeeder untuk masing-masing career — bukan placeholder
     * generik. Dikelompokkan per career untuk memudahkan maintenance.
     *
     * PENTING: entri untuk Backend Developer, Data Analyst, DevOps
     * Engineer, dan UI/UX Designer di bawah ini mengasumsikan judul
     * assignment-nya SAMA PERSIS dengan yang dibuat oleh
     * AddAssignmentsToExistingModulesSeeder — jalankan seeder itu DULU
     * sebelum seeder ini untuk 4 career tersebut.
     *
     * CATATAN evaluation_rubrics: total `weight` per assignment idealnya
     * 100, meskipun tidak divalidasi otomatis oleh seeder ini.
     */
    private array $assignmentDetails = [

        // ============================================================
        // ==================== FULL STACK DEVELOPER ==================
        // ============================================================

        // ---------- Modul 1: Frontend Fundamentals ----------
        'Assignment 1: Personal Portfolio Page' => [
            'learning_outcomes' => [
                'Membangun halaman portofolio pribadi yang responsif menggunakan HTML semantik dan CSS Grid/Flexbox',
                'Menerapkan praktik aksesibilitas dasar: atribut alt, kontras warna yang cukup, dan navigasi yang bisa diakses keyboard',
            ],
            'skills_learned' => ['HTML', 'CSS', 'Responsive Design'],
            'prerequisites' => ['HTML', 'CSS'],
            'tools' => ['VS Code', 'Chrome DevTools', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Struktur HTML', 'weight' => 20],
                ['criteria' => 'Layout Responsif', 'weight' => 30],
                ['criteria' => 'Aksesibilitas (A11y)', 'weight' => 20],
                ['criteria' => 'HTML Semantik', 'weight' => 15],
                ['criteria' => 'Presisi UI', 'weight' => 15],
            ],
        ],
        'Assignment 2: Responsive Landing Page' => [
            'learning_outcomes' => [
                'Membangun landing page multi-section yang sepenuhnya responsif dari mobile hingga desktop',
                'Menerapkan CSS Grid dan Flexbox secara kombinatif untuk layout yang lebih kompleks daripada portfolio page',
            ],
            'skills_learned' => ['HTML', 'CSS', 'Flexbox', 'CSS Grid', 'Responsive Design'],
            'prerequisites' => ['HTML', 'CSS', 'Personal Portfolio Page'],
            'tools' => ['VS Code', 'Chrome DevTools', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Struktur & Semantik HTML', 'weight' => 20],
                ['criteria' => 'Layout Responsif (Mobile-First)', 'weight' => 30],
                ['criteria' => 'Konsistensi Desain Antar Section', 'weight' => 20],
                ['criteria' => 'Aksesibilitas (A11y)', 'weight' => 15],
                ['criteria' => 'Presisi & Kerapian UI', 'weight' => 15],
            ],
        ],
        'Assignment 3: Interactive To-Do List' => [
            'learning_outcomes' => [
                'Menerapkan variabel, fungsi, dan struktur kondisional JavaScript dalam aplikasi nyata',
                'Melakukan DOM manipulation dan event handling untuk membuat halaman interaktif tanpa reload',
            ],
            'skills_learned' => ['JavaScript', 'DOM Manipulation', 'Event Handling'],
            'prerequisites' => ['HTML', 'CSS', 'JavaScript Dasar'],
            'tools' => ['VS Code', 'Chrome DevTools', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Fungsionalitas Tambah/Hapus/Selesai Tugas', 'weight' => 35],
                ['criteria' => 'Struktur Kode JavaScript', 'weight' => 25],
                ['criteria' => 'Responsivitas Tampilan', 'weight' => 20],
                ['criteria' => 'Penanganan Edge Case (input kosong, dsb.)', 'weight' => 20],
            ],
        ],

        // ---------- Modul 2: Modern JavaScript & ES6+ ----------
        'Assignment 1: Async Weather App' => [
            'learning_outcomes' => [
                'Menerapkan Fetch API dan async/await untuk mengambil data cuaca dari API eksternal',
                'Menangani error dengan try...catch agar aplikasi tetap stabil saat request gagal',
            ],
            'skills_learned' => ['JavaScript ES6+', 'Fetch API', 'Async/Await', 'Error Handling'],
            'prerequisites' => ['JavaScript Dasar', 'DOM Manipulation & Event Handling'],
            'tools' => ['VS Code', 'Chrome DevTools', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Implementasi Fetch API & Async/Await', 'weight' => 30],
                ['criteria' => 'Error Handling', 'weight' => 25],
                ['criteria' => 'Tampilan Data Cuaca', 'weight' => 25],
                ['criteria' => 'Struktur Kode ES6+', 'weight' => 20],
            ],
        ],
        'Assignment 2: API Data Fetcher' => [
            'learning_outcomes' => [
                'Menggunakan array methods (map, filter, reduce) untuk mengolah data hasil fetch dari API',
                'Menerapkan ES Modules untuk memisahkan logic fetching dari logic tampilan',
            ],
            'skills_learned' => ['Fetch API', 'Array Methods', 'ES Modules', 'Destructuring'],
            'prerequisites' => ['JavaScript Dasar', 'Arrow Function & Destructuring'],
            'tools' => ['VS Code', 'Chrome DevTools', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Pengambilan & Pengolahan Data', 'weight' => 30],
                ['criteria' => 'Penggunaan Array Methods', 'weight' => 25],
                ['criteria' => 'Struktur Modular (import/export)', 'weight' => 25],
                ['criteria' => 'Penanganan Error', 'weight' => 20],
            ],
        ],
        'Assignment 3: Module Refactor Exercise' => [
            'learning_outcomes' => [
                'Merefactor kode JavaScript monolitik menjadi beberapa module terpisah menggunakan import/export',
                'Menerapkan arrow function dan destructuring untuk menyederhanakan kode lama',
            ],
            'skills_learned' => ['ES Modules', 'Arrow Function', 'Destructuring', 'Code Refactoring'],
            'prerequisites' => ['JavaScript Dasar', 'Fungsi & Kondisional'],
            'tools' => ['VS Code', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Pemisahan Module yang Tepat', 'weight' => 35],
                ['criteria' => 'Konsistensi Import/Export', 'weight' => 25],
                ['criteria' => 'Penyederhanaan Kode (ES6+)', 'weight' => 25],
                ['criteria' => 'Fungsionalitas Tetap Berjalan', 'weight' => 15],
            ],
        ],
        'Assignment 4: Quiz: ES6+ Concepts' => [
            'learning_outcomes' => [
                'Menguji pemahaman konsep ES6+ seperti arrow function, destructuring, dan spread/rest',
                'Menguji pemahaman asynchronous JavaScript: Promise, async/await, dan error handling',
            ],
            'skills_learned' => ['ES6+', 'Promise', 'Async/Await'],
            'prerequisites' => ['Seluruh materi Modern JavaScript & ES6+'],
            'tools' => [],
            'evaluation_rubrics' => [
                ['criteria' => 'Pemahaman Konsep Dasar ES6+', 'weight' => 50],
                ['criteria' => 'Pemahaman Asynchronous JavaScript', 'weight' => 50],
            ],
        ],

        // ---------- Modul 3: React Essentials ----------
        'Assignment 1: Todo App with React' => [
            'learning_outcomes' => [
                'Menerapkan event handling dan conditional rendering untuk mengubah status tugas secara interaktif',
                'Menampilkan daftar tugas menggunakan map() dengan key yang unik dan konsisten',
            ],
            'skills_learned' => ['React', 'Event Handling', 'Conditional Rendering', 'Lists & Keys'],
            'prerequisites' => ['JavaScript ES6+', 'DOM Manipulation'],
            'tools' => ['VS Code', 'React DevTools', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Fungsionalitas CRUD Tugas', 'weight' => 30],
                ['criteria' => 'Conditional Rendering', 'weight' => 25],
                ['criteria' => 'Penggunaan Key yang Tepat', 'weight' => 20],
                ['criteria' => 'Struktur Komponen', 'weight' => 25],
            ],
        ],
        'Assignment 2: Movie Search App' => [
            'learning_outcomes' => [
                'Menggunakan useEffect untuk mengambil data film dari API berdasarkan kata kunci pencarian',
                'Menerapkan conditional rendering untuk menampilkan status loading dan error',
            ],
            'skills_learned' => ['React', 'useEffect', 'Fetch API', 'Conditional Rendering'],
            'prerequisites' => ['React Dasar', 'Fetch API'],
            'tools' => ['VS Code', 'React DevTools', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Implementasi useEffect & Dependency Array', 'weight' => 30],
                ['criteria' => 'Fitur Pencarian', 'weight' => 25],
                ['criteria' => 'Penanganan Loading/Error State', 'weight' => 25],
                ['criteria' => 'Struktur Komponen', 'weight' => 20],
            ],
        ],
        'Assignment 3: Multi-step Form' => [
            'learning_outcomes' => [
                'Membangun controlled form dengan state yang tersinkronisasi di setiap langkah',
                'Menerapkan conditional rendering untuk berpindah antar step form',
            ],
            'skills_learned' => ['React', 'Controlled Forms', 'Conditional Rendering', 'State Management'],
            'prerequisites' => ['React Dasar', 'JavaScript ES6+'],
            'tools' => ['VS Code', 'React DevTools', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Data Tidak Hilang Antar Step', 'weight' => 30],
                ['criteria' => 'Validasi Input per Step', 'weight' => 25],
                ['criteria' => 'Navigasi Antar Step', 'weight' => 25],
                ['criteria' => 'Struktur Kode', 'weight' => 20],
            ],
        ],
        'Assignment 4: Shopping Cart' => [
            'learning_outcomes' => [
                'Menampilkan daftar produk keranjang menggunakan map() dengan key yang unik dan tepat',
                'Mengelola state keranjang belanja (tambah, kurangi, hapus item)',
            ],
            'skills_learned' => ['React', 'Lists & Keys', 'State Management', 'Event Handling'],
            'prerequisites' => ['React Dasar', 'Array Methods'],
            'tools' => ['VS Code', 'React DevTools', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Fungsionalitas Keranjang', 'weight' => 35],
                ['criteria' => 'Perhitungan Total Harga', 'weight' => 25],
                ['criteria' => 'Penggunaan Key yang Tepat', 'weight' => 20],
                ['criteria' => 'Struktur Komponen', 'weight' => 20],
            ],
        ],
        'Assignment 5: Component Library' => [
            'learning_outcomes' => [
                'Membangun beberapa komponen reusable dengan props yang fleksibel',
                'Menerapkan konsistensi desain antar komponen agar mudah dipakai ulang',
            ],
            'skills_learned' => ['React', 'Component Design', 'Props', 'Reusability'],
            'prerequisites' => ['React Dasar'],
            'tools' => ['VS Code', 'React DevTools', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Reusability Komponen', 'weight' => 35],
                ['criteria' => 'Konsistensi Props API', 'weight' => 25],
                ['criteria' => 'Dokumentasi Penggunaan Komponen', 'weight' => 20],
                ['criteria' => 'Kualitas Kode', 'weight' => 20],
            ],
        ],

        // ---------- Modul 4: TypeScript for React ----------
        'Assignment 1: Convert JS Project to TS' => [
            'learning_outcomes' => [
                'Mengonversi proyek React JavaScript menjadi TypeScript dengan tipe data yang sesuai',
                'Mengonfigurasi tsconfig.json untuk proyek React',
            ],
            'skills_learned' => ['TypeScript', 'Type Annotations', 'Interface', 'tsconfig'],
            'prerequisites' => ['React Dasar', 'JavaScript ES6+'],
            'tools' => ['VS Code', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Ketepatan Tipe Data', 'weight' => 30],
                ['criteria' => 'Konfigurasi TypeScript', 'weight' => 20],
                ['criteria' => 'Fungsionalitas Tetap Berjalan', 'weight' => 30],
                ['criteria' => 'Kebersihan Kode (hindari any berlebihan)', 'weight' => 20],
            ],
        ],
        'Assignment 2: Typed Form Component' => [
            'learning_outcomes' => [
                'Memberi tipe pada props dan state komponen form menggunakan interface',
                'Menerapkan tipe pada event handler form (React.ChangeEvent, React.FormEvent)',
            ],
            'skills_learned' => ['TypeScript', 'Typing Props & State', 'Typing Event Handler'],
            'prerequisites' => ['TypeScript Dasar', 'React Forms'],
            'tools' => ['VS Code', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Ketepatan Interface Props', 'weight' => 30],
                ['criteria' => 'Typing Event Handler', 'weight' => 30],
                ['criteria' => 'Validasi Form', 'weight' => 20],
                ['criteria' => 'Kualitas Kode', 'weight' => 20],
            ],
        ],
        'Assignment 3: Typed API Client' => [
            'learning_outcomes' => [
                'Membangun fungsi API client generic yang dapat menangani berbagai tipe data response',
                'Menerapkan generics untuk menjaga keamanan tipe data',
            ],
            'skills_learned' => ['TypeScript', 'Generics', 'Fetch API', 'Type Safety'],
            'prerequisites' => ['TypeScript Dasar', 'Fetch API'],
            'tools' => ['VS Code', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Implementasi Generics', 'weight' => 35],
                ['criteria' => 'Type Safety pada Response API', 'weight' => 30],
                ['criteria' => 'Error Handling', 'weight' => 20],
                ['criteria' => 'Struktur Kode', 'weight' => 15],
            ],
        ],

        // ---------- Modul 5: Advanced React Patterns ----------
        'Assignment 1: Custom Hook Library' => [
            'learning_outcomes' => [
                'Membangun beberapa custom hook reusable (misalnya useFetch, useLocalStorage, useDebounce)',
                'Menerapkan prinsip DRY dengan memisahkan logic ke dalam custom hook',
            ],
            'skills_learned' => ['React', 'Custom Hooks', 'useState', 'useEffect'],
            'prerequisites' => ['React Essentials', 'useEffect & Component Lifecycle'],
            'tools' => ['VS Code', 'React DevTools', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Reusability Custom Hook', 'weight' => 35],
                ['criteria' => 'Ketepatan Logic per Hook', 'weight' => 30],
                ['criteria' => 'Dokumentasi Penggunaan', 'weight' => 15],
                ['criteria' => 'Kualitas Kode', 'weight' => 20],
            ],
        ],
        'Assignment 2: State Management App' => [
            'learning_outcomes' => [
                'Menggunakan Context API untuk menghindari prop drilling pada data yang dibutuhkan banyak komponen',
                'Menerapkan useReducer untuk mengelola state dengan banyak jenis aksi (misalnya keranjang belanja)',
            ],
            'skills_learned' => ['React', 'Context API', 'useReducer', 'State Management'],
            'prerequisites' => ['React Essentials', 'Custom Hooks'],
            'tools' => ['VS Code', 'React DevTools', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Implementasi Context API', 'weight' => 30],
                ['criteria' => 'Implementasi useReducer', 'weight' => 30],
                ['criteria' => 'Struktur Action & Reducer', 'weight' => 25],
                ['criteria' => 'Kualitas Kode', 'weight' => 15],
            ],
        ],
        'Assignment 3: Performance Demo' => [
            'learning_outcomes' => [
                'Membandingkan performa render sebelum dan sesudah menggunakan useMemo dan useCallback',
                'Mengidentifikasi kapan optimasi performa benar-benar diperlukan, bukan diterapkan berlebihan',
            ],
            'skills_learned' => ['React', 'useMemo', 'useCallback', 'Performance Optimization'],
            'prerequisites' => ['React Essentials', 'State Management App'],
            'tools' => ['VS Code', 'React DevTools', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Implementasi useMemo/useCallback yang Tepat', 'weight' => 35],
                ['criteria' => 'Analisis Perbandingan Performa', 'weight' => 30],
                ['criteria' => 'Penjelasan Kapan Optimasi Diperlukan', 'weight' => 20],
                ['criteria' => 'Kualitas Kode', 'weight' => 15],
            ],
        ],
        'Assignment 4: Testing Suite' => [
            'learning_outcomes' => [
                'Menulis automated test untuk komponen React menggunakan React Testing Library',
                'Menguji interaksi pengguna seperti klik dan input dari sudut pandang pengguna, bukan detail implementasi',
            ],
            'skills_learned' => ['React Testing Library', 'Jest/Vitest', 'Automated Testing'],
            'prerequisites' => ['React Essentials'],
            'tools' => ['VS Code', 'Jest/Vitest', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Cakupan Test terhadap Fitur Utama', 'weight' => 35],
                ['criteria' => 'Ketepatan Assertion', 'weight' => 30],
                ['criteria' => 'Simulasi Interaksi Pengguna', 'weight' => 20],
                ['criteria' => 'Kejelasan Test Case', 'weight' => 15],
            ],
        ],

        // ============================================================
        // ====================== BACKEND DEVELOPER ====================
        // ============================================================

        // ---------- Modul 1: Node.js Fundamentals ----------
        'Assignment 1: Static File Server with Node.js' => [
            'learning_outcomes' => [
                'Membangun HTTP server dari modul bawaan Node.js (http, fs, path) tanpa framework',
                'Memahami cara kerja non-blocking I/O saat membaca file secara asynchronous',
            ],
            'skills_learned' => ['Node.js', 'http module', 'fs module', 'path module'],
            'prerequisites' => ['JavaScript Dasar'],
            'tools' => ['VS Code', 'Node.js', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Server Berjalan Tanpa Framework', 'weight' => 30],
                ['criteria' => 'Penggunaan fs & path yang Aman', 'weight' => 30],
                ['criteria' => 'Penanganan Asynchronous I/O', 'weight' => 25],
                ['criteria' => 'Struktur Kode', 'weight' => 15],
            ],
        ],
        'Assignment 2: Async Task Queue Simulator' => [
            'learning_outcomes' => [
                'Mensimulasikan Event Loop Node.js dengan antrean tugas asynchronous menggunakan setTimeout/Promise',
                'Mengelola dependency proyek dan environment variable menggunakan package.json dan file .env',
            ],
            'skills_learned' => ['Event Loop', 'Asynchronous JavaScript', 'NPM', 'Environment Variables'],
            'prerequisites' => ['Static File Server with Node.js'],
            'tools' => ['VS Code', 'Node.js', 'dotenv', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Simulasi Urutan Eksekusi Non-blocking', 'weight' => 40],
                ['criteria' => 'Konfigurasi via .env', 'weight' => 25],
                ['criteria' => 'Struktur package.json', 'weight' => 20],
                ['criteria' => 'Kejelasan Output Log', 'weight' => 15],
            ],
        ],

        // ---------- Modul 2: Membangun REST API ----------
        'Assignment 1: Task Manager REST API' => [
            'learning_outcomes' => [
                'Merancang endpoint REST (GET, POST, PUT, DELETE) yang konsisten untuk resource tugas',
                'Menerapkan middleware Express dasar untuk parsing body dan logging request',
            ],
            'skills_learned' => ['Express.js', 'REST API', 'Routing', 'Middleware'],
            'prerequisites' => ['Node.js Fundamentals'],
            'tools' => ['VS Code', 'Express.js', 'Postman', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Kelengkapan Endpoint CRUD', 'weight' => 35],
                ['criteria' => 'Konsistensi Routing & HTTP Method', 'weight' => 25],
                ['criteria' => 'Penggunaan Middleware', 'weight' => 20],
                ['criteria' => 'Struktur Response', 'weight' => 20],
            ],
        ],
        'Assignment 2: Book Catalog API' => [
            'learning_outcomes' => [
                'Menggunakan req.params, req.query, dan req.body secara tepat sesuai kebutuhan masing-masing endpoint',
                'Menerapkan validasi input dan error handling agar API tidak menerima data yang tidak valid',
            ],
            'skills_learned' => ['Express.js', 'Request Validation', 'Error Handling'],
            'prerequisites' => ['Task Manager REST API'],
            'tools' => ['VS Code', 'Express.js', 'Postman', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Penggunaan Params/Query/Body yang Tepat', 'weight' => 30],
                ['criteria' => 'Validasi Input', 'weight' => 30],
                ['criteria' => 'Status Code & Pesan Error yang Jelas', 'weight' => 25],
                ['criteria' => 'Struktur Kode', 'weight' => 15],
            ],
        ],
        'Assignment 3: API Structure Refactor' => [
            'learning_outcomes' => [
                'Merefactor endpoint yang menumpuk dalam satu file menjadi struktur routes/controllers/services',
                'Menerapkan separation of concerns agar tiap lapisan kode punya satu tanggung jawab yang jelas',
            ],
            'skills_learned' => ['Software Architecture', 'Express.js', 'Code Organization'],
            'prerequisites' => ['Book Catalog API'],
            'tools' => ['VS Code', 'Express.js', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Pemisahan Routes/Controllers/Services', 'weight' => 40],
                ['criteria' => 'Fungsionalitas Tetap Berjalan Setelah Refactor', 'weight' => 30],
                ['criteria' => 'Konsistensi Penamaan & Struktur Folder', 'weight' => 20],
                ['criteria' => 'Kualitas Kode', 'weight' => 10],
            ],
        ],

        // ---------- Modul 3: Database SQL & NoSQL ----------
        'Assignment 1: Library Database Schema Design' => [
            'learning_outcomes' => [
                'Merancang skema tabel relasional yang ternormalisasi untuk sistem perpustakaan (buku, anggota, peminjaman)',
                'Menentukan relasi antar tabel yang tepat untuk menghindari duplikasi data',
            ],
            'skills_learned' => ['Database Design', 'Normalisasi', 'SQL'],
            'prerequisites' => ['Konsep Dasar Database Relasional'],
            'tools' => ['MySQL Workbench atau dbdiagram.io', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Ketepatan Relasi Antar Tabel', 'weight' => 35],
                ['criteria' => 'Tingkat Normalisasi Skema', 'weight' => 30],
                ['criteria' => 'Kelengkapan Kolom & Tipe Data', 'weight' => 20],
                ['criteria' => 'Dokumentasi Skema (ERD)', 'weight' => 15],
            ],
        ],
        'Assignment 2: Blog CRUD with MySQL' => [
            'learning_outcomes' => [
                'Menulis query SELECT, INSERT, UPDATE, dan DELETE untuk sistem blog sederhana',
                'Menghindari kesalahan umum seperti lupa klausa WHERE pada UPDATE/DELETE',
            ],
            'skills_learned' => ['SQL', 'MySQL', 'CRUD Operations'],
            'prerequisites' => ['Library Database Schema Design'],
            'tools' => ['MySQL', 'VS Code', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Ketepatan Query CRUD', 'weight' => 40],
                ['criteria' => 'Keamanan Query (WHERE pada Update/Delete)', 'weight' => 25],
                ['criteria' => 'Efisiensi Query', 'weight' => 20],
                ['criteria' => 'Dokumentasi Query', 'weight' => 15],
            ],
        ],
        'Assignment 3: Product Catalog with MongoDB' => [
            'learning_outcomes' => [
                'Menyimpan data produk dalam bentuk document MongoDB dengan struktur yang fleksibel',
                'Menjelaskan alasan memilih NoSQL dibandingkan SQL untuk kasus katalog produk yang atributnya bervariasi',
            ],
            'skills_learned' => ['MongoDB', 'NoSQL', 'Document Database'],
            'prerequisites' => ['Kenalan dengan NoSQL (MongoDB)'],
            'tools' => ['MongoDB Compass', 'VS Code', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Struktur Document yang Tepat', 'weight' => 35],
                ['criteria' => 'Operasi CRUD MongoDB', 'weight' => 30],
                ['criteria' => 'Justifikasi Pemilihan NoSQL', 'weight' => 20],
                ['criteria' => 'Kejelasan Dokumentasi', 'weight' => 15],
            ],
        ],

        // ---------- Modul 4: Authentication & Security ----------
        'Assignment 1: JWT Authentication System' => [
            'learning_outcomes' => [
                'Melakukan hashing password menggunakan bcrypt sebelum menyimpannya ke database',
                'Membuat dan memverifikasi JWT untuk proses login dan autentikasi request berikutnya',
            ],
            'skills_learned' => ['bcrypt', 'JWT', 'Authentication'],
            'prerequisites' => ['Membangun REST API', 'Database SQL & NoSQL'],
            'tools' => ['VS Code', 'bcrypt', 'jsonwebtoken', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Hashing Password dengan bcrypt', 'weight' => 30],
                ['criteria' => 'Pembuatan & Verifikasi JWT', 'weight' => 35],
                ['criteria' => 'Penyimpanan Secret Key via .env', 'weight' => 20],
                ['criteria' => 'Struktur Kode', 'weight' => 15],
            ],
        ],
        'Assignment 2: Secure Login API' => [
            'learning_outcomes' => [
                'Membangun middleware autentikasi untuk melindungi endpoint yang hanya boleh diakses user login',
                'Menerapkan input sanitization dasar untuk mencegah data berbahaya masuk ke sistem',
            ],
            'skills_learned' => ['Middleware Autentikasi', 'Input Sanitization', 'Express.js'],
            'prerequisites' => ['JWT Authentication System'],
            'tools' => ['VS Code', 'Express.js', 'Postman', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Middleware Proteksi Endpoint', 'weight' => 35],
                ['criteria' => 'Sanitasi Input', 'weight' => 25],
                ['criteria' => 'Response 401 yang Tepat', 'weight' => 25],
                ['criteria' => 'Struktur Kode', 'weight' => 15],
            ],
        ],
        'Assignment 3: Rate-Limited Login Endpoint' => [
            'learning_outcomes' => [
                'Menerapkan rate limiting pada endpoint login untuk mencegah serangan brute force',
                'Membandingkan trade-off antara pendekatan session-based dan token-based authentication',
            ],
            'skills_learned' => ['Rate Limiting', 'express-rate-limit', 'Security'],
            'prerequisites' => ['Secure Login API'],
            'tools' => ['VS Code', 'express-rate-limit', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Implementasi Rate Limiting', 'weight' => 40],
                ['criteria' => 'Pesan Error yang Informatif (429)', 'weight' => 25],
                ['criteria' => 'Analisis Session vs Token', 'weight' => 20],
                ['criteria' => 'Struktur Kode', 'weight' => 15],
            ],
        ],

        // ---------- Modul 5: Git & Collaboration Workflow ----------
        'Assignment 1: Team Branching Simulation' => [
            'learning_outcomes' => [
                'Membuat dan mengelola branch feature/fix sesuai konvensi penamaan tim',
                'Menyelesaikan merge conflict yang disengaja untuk melatih kepercayaan diri saat menghadapi konflik nyata',
            ],
            'skills_learned' => ['Git Branching', 'Merge Conflict Resolution'],
            'prerequisites' => ['Git Dasar (commit & history)'],
            'tools' => ['Git', 'GitHub', 'VS Code'],
            'evaluation_rubrics' => [
                ['criteria' => 'Konvensi Penamaan Branch', 'weight' => 25],
                ['criteria' => 'Riwayat Commit yang Rapi', 'weight' => 25],
                ['criteria' => 'Penyelesaian Merge Conflict', 'weight' => 35],
                ['criteria' => 'Kebersihan Repository (.gitignore)', 'weight' => 15],
            ],
        ],
        'Assignment 2: Pull Request Practice Repo' => [
            'learning_outcomes' => [
                'Membuat Pull Request yang deskriptif lengkap dengan penjelasan perubahan dan alasannya',
                'Memberikan dan menindaklanjuti review dari anggota tim sebelum melakukan merge ke branch utama',
            ],
            'skills_learned' => ['Pull Request', 'Code Review', 'Git Collaboration'],
            'prerequisites' => ['Team Branching Simulation'],
            'tools' => ['Git', 'GitHub', 'VS Code'],
            'evaluation_rubrics' => [
                ['criteria' => 'Kualitas Deskripsi Pull Request', 'weight' => 35],
                ['criteria' => 'Responsif Terhadap Review', 'weight' => 30],
                ['criteria' => 'Riwayat Commit Setelah Revisi', 'weight' => 20],
                ['criteria' => 'Proses Merge yang Benar', 'weight' => 15],
            ],
        ],

        // ---------- Modul 6: Testing & Debugging Backend ----------
        'Assignment 1: Unit Test Suite for Utility Functions' => [
            'learning_outcomes' => [
                'Menulis unit test menggunakan Jest untuk beberapa fungsi utilitas (misalnya perhitungan harga/diskon)',
                'Menerapkan pola arrange-act-assert dan mencakup skenario edge case, bukan hanya kasus normal',
            ],
            'skills_learned' => ['Jest', 'Unit Testing'],
            'prerequisites' => ['JavaScript Dasar'],
            'tools' => ['VS Code', 'Jest', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Cakupan Test Case (termasuk edge case)', 'weight' => 40],
                ['criteria' => 'Ketepatan Assertion (expect/matcher)', 'weight' => 30],
                ['criteria' => 'Kejelasan Nama Test', 'weight' => 15],
                ['criteria' => 'Semua Test Passing', 'weight' => 15],
            ],
        ],
        'Assignment 2: API Integration Test with Supertest' => [
            'learning_outcomes' => [
                'Menulis integration test untuk endpoint REST API menggunakan Supertest tanpa menyalakan server sungguhan',
                'Menerapkan logging terstruktur (misalnya dengan winston) untuk mempermudah debugging di production',
            ],
            'skills_learned' => ['Supertest', 'Integration Testing', 'Logging'],
            'prerequisites' => ['Unit Test Suite for Utility Functions', 'Membangun REST API'],
            'tools' => ['VS Code', 'Supertest', 'Jest', 'winston', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Cakupan Test Endpoint Utama', 'weight' => 35],
                ['criteria' => 'Pengujian Skenario Gagal (Validasi/404)', 'weight' => 30],
                ['criteria' => 'Implementasi Logging', 'weight' => 20],
                ['criteria' => 'Semua Test Passing', 'weight' => 15],
            ],
        ],

        // ---------- Modul 7: Server Architecture & Performance ----------
        'Assignment 1: Refactor to Layered Architecture' => [
            'learning_outcomes' => [
                'Menata ulang proyek REST API existing menjadi arsitektur berlapis (routes, controllers, services)',
                'Memastikan setiap lapisan memiliki tanggung jawab yang jelas dan dapat diuji secara terpisah',
            ],
            'skills_learned' => ['Layered Architecture', 'Software Design', 'Express.js'],
            'prerequisites' => ['API Structure Refactor'],
            'tools' => ['VS Code', 'Express.js', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Kejelasan Pemisahan Layer', 'weight' => 40],
                ['criteria' => 'Service Layer Tidak Bergantung pada HTTP', 'weight' => 30],
                ['criteria' => 'Fungsionalitas Tetap Berjalan', 'weight' => 20],
                ['criteria' => 'Dokumentasi Arsitektur', 'weight' => 10],
            ],
        ],
        'Assignment 2: Redis Caching Layer' => [
            'learning_outcomes' => [
                'Menerapkan caching dengan Redis untuk data yang jarang berubah guna mengurangi beban database',
                'Menangani strategi invalidasi cache saat data sumber berubah',
            ],
            'skills_learned' => ['Redis', 'Caching', 'Performance Optimization'],
            'prerequisites' => ['Refactor to Layered Architecture'],
            'tools' => ['VS Code', 'Redis', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Implementasi Cache Hit/Miss', 'weight' => 35],
                ['criteria' => 'Strategi Invalidasi Cache', 'weight' => 30],
                ['criteria' => 'Peningkatan Response Time Terukur', 'weight' => 20],
                ['criteria' => 'Struktur Kode', 'weight' => 15],
            ],
        ],
        'Assignment 3: Health Check & Monitoring Endpoint' => [
            'learning_outcomes' => [
                'Membuat endpoint /health yang memverifikasi status aplikasi beserta koneksi database',
                'Merancang format response API yang konsisten dan menerapkan API versioning dasar',
            ],
            'skills_learned' => ['Health Check', 'API Versioning', 'Monitoring'],
            'prerequisites' => ['Redis Caching Layer'],
            'tools' => ['VS Code', 'Express.js', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Endpoint Health Check Berfungsi', 'weight' => 35],
                ['criteria' => 'Konsistensi Format Response', 'weight' => 25],
                ['criteria' => 'Implementasi API Versioning', 'weight' => 25],
                ['criteria' => 'Dokumentasi Endpoint', 'weight' => 15],
            ],
        ],

        // ============================================================
        // ========================= DATA ANALYST ======================
        // ============================================================

        // ---------- Modul 1: Python untuk Data Analysis ----------
        'Assignment 1: Sales Data Exploration with Pandas' => [
            'learning_outcomes' => [
                'Membaca dan mengeksplorasi dataset penjualan menggunakan pandas (head, info, describe)',
                'Menggunakan Series dan DataFrame untuk menyusun data dalam struktur yang mudah dianalisis',
            ],
            'skills_learned' => ['Python', 'Pandas', 'Data Exploration'],
            'prerequisites' => ['Dasar Python'],
            'tools' => ['Jupyter Notebook / Google Colab', 'pandas'],
            'evaluation_rubrics' => [
                ['criteria' => 'Eksplorasi Data Awal (head/info/describe)', 'weight' => 30],
                ['criteria' => 'Ketepatan Struktur DataFrame', 'weight' => 30],
                ['criteria' => 'Interpretasi Hasil Eksplorasi', 'weight' => 25],
                ['criteria' => 'Kerapian Notebook', 'weight' => 15],
            ],
        ],
        'Assignment 2: Customer Data Summary Report' => [
            'learning_outcomes' => [
                'Menggunakan groupby() dan fungsi agregasi untuk meringkas data pelanggan berdasarkan kategori tertentu',
                'Menjawab pertanyaan bisnis sederhana (misal total transaksi per wilayah) langsung dari DataFrame',
            ],
            'skills_learned' => ['Pandas', 'GroupBy', 'Data Aggregation'],
            'prerequisites' => ['Sales Data Exploration with Pandas'],
            'tools' => ['Jupyter Notebook / Google Colab', 'pandas'],
            'evaluation_rubrics' => [
                ['criteria' => 'Ketepatan Penggunaan GroupBy', 'weight' => 35],
                ['criteria' => 'Ketepatan Fungsi Agregasi', 'weight' => 30],
                ['criteria' => 'Kejelasan Ringkasan yang Dihasilkan', 'weight' => 20],
                ['criteria' => 'Kerapian Notebook', 'weight' => 15],
            ],
        ],

        // ---------- Modul 2: SQL untuk Data Analyst ----------
        'Assignment 1: Sales Database Query Challenge' => [
            'learning_outcomes' => [
                'Menulis query SELECT dengan WHERE dan ORDER BY untuk menjawab pertanyaan bisnis spesifik',
                'Memahami urutan eksekusi dasar SQL sebagai satu kesatuan pernyataan, bukan baris per baris',
            ],
            'skills_learned' => ['SQL', 'SELECT', 'WHERE', 'ORDER BY'],
            'prerequisites' => ['Konsep Dasar Database'],
            'tools' => ['MySQL / PostgreSQL', 'DBeaver atau sejenisnya'],
            'evaluation_rubrics' => [
                ['criteria' => 'Ketepatan Query terhadap Pertanyaan', 'weight' => 40],
                ['criteria' => 'Penggunaan WHERE & ORDER BY', 'weight' => 30],
                ['criteria' => 'Efisiensi Query', 'weight' => 15],
                ['criteria' => 'Dokumentasi Query', 'weight' => 15],
            ],
        ],
        'Assignment 2: Multi-table Join Report' => [
            'learning_outcomes' => [
                'Menggabungkan data dari beberapa tabel menggunakan INNER JOIN dan LEFT JOIN sesuai kebutuhan',
                'Meringkas hasil join menggunakan GROUP BY dan HAVING untuk laporan agregat',
            ],
            'skills_learned' => ['SQL', 'JOIN', 'GROUP BY', 'HAVING'],
            'prerequisites' => ['Sales Database Query Challenge'],
            'tools' => ['MySQL / PostgreSQL', 'DBeaver atau sejenisnya'],
            'evaluation_rubrics' => [
                ['criteria' => 'Ketepatan Pemilihan Jenis JOIN', 'weight' => 35],
                ['criteria' => 'Ketepatan GROUP BY & HAVING', 'weight' => 30],
                ['criteria' => 'Data Tidak Terduplikasi', 'weight' => 20],
                ['criteria' => 'Dokumentasi Query', 'weight' => 15],
            ],
        ],

        // ---------- Modul 3: Data Cleaning & Preparation ----------
        'Assignment 1: Messy Dataset Cleanup' => [
            'learning_outcomes' => [
                'Mengidentifikasi dan menangani missing values dengan strategi yang sesuai konteks data',
                'Mendeteksi dan menangani data duplikat serta outlier menggunakan metode IQR',
            ],
            'skills_learned' => ['Data Cleaning', 'Pandas', 'Missing Values', 'Outlier Detection'],
            'prerequisites' => ['Python untuk Data Analysis'],
            'tools' => ['Jupyter Notebook / Google Colab', 'pandas'],
            'evaluation_rubrics' => [
                ['criteria' => 'Penanganan Missing Values', 'weight' => 30],
                ['criteria' => 'Deteksi & Penanganan Duplikat', 'weight' => 25],
                ['criteria' => 'Deteksi Outlier dengan IQR', 'weight' => 25],
                ['criteria' => 'Justifikasi Keputusan Cleaning', 'weight' => 20],
            ],
        ],
        'Assignment 2: Customer Data Standardization' => [
            'learning_outcomes' => [
                'Menyeragamkan format teks dan kategori yang tertulis berbeda namun bermakna sama',
                'Memastikan tipe data pada tiap kolom sudah sesuai sebelum data dianalisis lebih lanjut',
            ],
            'skills_learned' => ['Data Standardization', 'Pandas', 'Data Transformation'],
            'prerequisites' => ['Messy Dataset Cleanup'],
            'tools' => ['Jupyter Notebook / Google Colab', 'pandas'],
            'evaluation_rubrics' => [
                ['criteria' => 'Konsistensi Kategori Setelah Standarisasi', 'weight' => 40],
                ['criteria' => 'Ketepatan Tipe Data per Kolom', 'weight' => 30],
                ['criteria' => 'Validasi Hasil (groupby tidak terpecah)', 'weight' => 20],
                ['criteria' => 'Dokumentasi Proses', 'weight' => 10],
            ],
        ],

        // ---------- Modul 4: Data Visualization ----------
        'Assignment 1: Sales Performance Dashboard' => [
            'learning_outcomes' => [
                'Menyusun dashboard sederhana dengan metrik utama yang relevan bagi tim bisnis',
                'Menempatkan elemen dashboard mengikuti hierarki visual agar mudah dipahami dalam beberapa detik',
            ],
            'skills_learned' => ['Data Visualization', 'Dashboard Design', 'matplotlib/seaborn'],
            'prerequisites' => ['Customer Data Standardization'],
            'tools' => ['Jupyter Notebook / Google Colab', 'matplotlib', 'seaborn'],
            'evaluation_rubrics' => [
                ['criteria' => 'Relevansi Metrik Utama', 'weight' => 30],
                ['criteria' => 'Hierarki Visual Dashboard', 'weight' => 30],
                ['criteria' => 'Kejelasan Label & Skala', 'weight' => 25],
                ['criteria' => 'Kerapian Tampilan', 'weight' => 15],
            ],
        ],
        'Assignment 2: Chart Type Comparison Report' => [
            'learning_outcomes' => [
                'Memilih jenis chart yang tepat (bar, line, pie, scatter) sesuai struktur data dan tujuan komunikasi',
                'Menjelaskan alasan pemilihan tiap chart dan potensi kesalahan interpretasi jika chart yang salah dipakai',
            ],
            'skills_learned' => ['Data Visualization', 'Chart Selection', 'matplotlib'],
            'prerequisites' => ['Sales Performance Dashboard'],
            'tools' => ['Jupyter Notebook / Google Colab', 'matplotlib'],
            'evaluation_rubrics' => [
                ['criteria' => 'Ketepatan Pemilihan Jenis Chart', 'weight' => 40],
                ['criteria' => 'Kualitas Visual (skala, label, warna)', 'weight' => 30],
                ['criteria' => 'Penjelasan Alasan Pemilihan Chart', 'weight' => 20],
                ['criteria' => 'Kerapian Laporan', 'weight' => 10],
            ],
        ],

        // ---------- Modul 5: Statistik Dasar untuk Analisis ----------
        'Assignment 1: Statistical Summary Report' => [
            'learning_outcomes' => [
                'Menghitung dan menginterpretasikan mean, median, dan standar deviasi pada dataset nyata',
                'Menjelaskan kapan median lebih representatif dibandingkan mean akibat pengaruh outlier',
            ],
            'skills_learned' => ['Statistik Deskriptif', 'Python', 'pandas'],
            'prerequisites' => ['Python untuk Data Analysis'],
            'tools' => ['Jupyter Notebook / Google Colab', 'pandas'],
            'evaluation_rubrics' => [
                ['criteria' => 'Ketepatan Perhitungan Statistik', 'weight' => 35],
                ['criteria' => 'Interpretasi Mean vs Median', 'weight' => 30],
                ['criteria' => 'Analisis Standar Deviasi', 'weight' => 20],
                ['criteria' => 'Kejelasan Laporan', 'weight' => 15],
            ],
        ],
        'Assignment 2: Correlation Analysis Project' => [
            'learning_outcomes' => [
                'Menghitung korelasi antar dua variabel numerik dan menginterpretasikan kekuatan hubungannya',
                'Menjelaskan perbedaan korelasi dengan hubungan sebab-akibat agar tidak menyimpulkan secara berlebihan',
            ],
            'skills_learned' => ['Korelasi', 'Statistik', 'Data Interpretation'],
            'prerequisites' => ['Statistical Summary Report'],
            'tools' => ['Jupyter Notebook / Google Colab', 'pandas'],
            'evaluation_rubrics' => [
                ['criteria' => 'Ketepatan Perhitungan Korelasi', 'weight' => 35],
                ['criteria' => 'Interpretasi Kekuatan & Arah Korelasi', 'weight' => 30],
                ['criteria' => 'Kehati-hatian Korelasi vs Kausalitas', 'weight' => 25],
                ['criteria' => 'Kejelasan Laporan', 'weight' => 10],
            ],
        ],

        // ---------- Modul 6: R untuk Data Analysis ----------
        'Assignment 1: R Data Manipulation with dplyr' => [
            'learning_outcomes' => [
                'Menggunakan fungsi dasar dplyr (filter, select, mutate, summarise) untuk mengolah data di R',
                'Merangkai beberapa operasi data menggunakan pipe operator secara terbaca',
            ],
            'skills_learned' => ['R', 'dplyr', 'Data Manipulation'],
            'prerequisites' => ['Pengenalan R dan RStudio'],
            'tools' => ['RStudio', 'dplyr'],
            'evaluation_rubrics' => [
                ['criteria' => 'Ketepatan Fungsi dplyr', 'weight' => 40],
                ['criteria' => 'Penggunaan Pipe Operator', 'weight' => 25],
                ['criteria' => 'Hasil Ringkasan Data yang Benar', 'weight' => 25],
                ['criteria' => 'Kerapian Kode R', 'weight' => 10],
            ],
        ],
        'Assignment 2: Visualization with ggplot2' => [
            'learning_outcomes' => [
                'Membangun grafik menggunakan pendekatan grammar of graphics pada ggplot2',
                'Menambahkan label, judul, dan tema yang sesuai agar grafik siap untuk laporan formal',
            ],
            'skills_learned' => ['R', 'ggplot2', 'Data Visualization'],
            'prerequisites' => ['R Data Manipulation with dplyr'],
            'tools' => ['RStudio', 'ggplot2'],
            'evaluation_rubrics' => [
                ['criteria' => 'Ketepatan Jenis Grafik', 'weight' => 35],
                ['criteria' => 'Kelengkapan Label & Judul', 'weight' => 30],
                ['criteria' => 'Kualitas Visual', 'weight' => 20],
                ['criteria' => 'Kerapian Kode R', 'weight' => 15],
            ],
        ],

        // ---------- Modul 7: Komunikasi Insight Data ----------
        'Assignment 1: Data Story Presentation' => [
            'learning_outcomes' => [
                'Menyusun data story dengan struktur konteks-temuan-rekomendasi yang jelas',
                'Mengaitkan temuan data dengan implikasi bisnis yang relevan bagi audiens',
            ],
            'skills_learned' => ['Data Storytelling', 'Komunikasi Data'],
            'prerequisites' => ['Sales Performance Dashboard', 'Correlation Analysis Project'],
            'tools' => ['Google Slides / PowerPoint'],
            'evaluation_rubrics' => [
                ['criteria' => 'Struktur Konteks-Temuan-Rekomendasi', 'weight' => 40],
                ['criteria' => 'Relevansi dengan Konteks Bisnis', 'weight' => 30],
                ['criteria' => 'Kejelasan Visual Pendukung', 'weight' => 20],
                ['criteria' => 'Kejelasan Narasi', 'weight' => 10],
            ],
        ],
        'Assignment 2: Executive Summary Report' => [
            'learning_outcomes' => [
                'Menulis ringkasan eksekutif yang menghindari istilah teknis berlebihan untuk audiens non-teknis',
                'Menyampaikan angka dengan konteks yang jelas (perbandingan periode, bukan angka tunggal)',
            ],
            'skills_learned' => ['Komunikasi Data', 'Report Writing'],
            'prerequisites' => ['Data Story Presentation'],
            'tools' => ['Google Docs / Word'],
            'evaluation_rubrics' => [
                ['criteria' => 'Kejelasan Bahasa untuk Non-Teknis', 'weight' => 35],
                ['criteria' => 'Konteks Angka yang Disampaikan', 'weight' => 30],
                ['criteria' => 'Rekomendasi yang Actionable', 'weight' => 25],
                ['criteria' => 'Struktur Laporan', 'weight' => 10],
            ],
        ],

        // ============================================================
        // ======================= DEVOPS ENGINEER =====================
        // ============================================================

        // ---------- Modul 1: Linux Fundamentals ----------
        'Assignment 1: Linux Server Setup Exercise' => [
            'learning_outcomes' => [
                'Menjalankan perintah dasar command line untuk navigasi, manajemen file, dan permission',
                'Mengelola proses dan layanan menggunakan systemd (start, stop, enable)',
            ],
            'skills_learned' => ['Linux', 'Command Line', 'systemd', 'File Permissions'],
            'prerequisites' => ['Tidak ada (materi dasar)'],
            'tools' => ['Ubuntu/Debian VM atau WSL', 'Terminal'],
            'evaluation_rubrics' => [
                ['criteria' => 'Navigasi & Manajemen File', 'weight' => 30],
                ['criteria' => 'Ketepatan Permission (chmod/chown)', 'weight' => 30],
                ['criteria' => 'Pengelolaan Service dengan systemd', 'weight' => 25],
                ['criteria' => 'Dokumentasi Langkah', 'weight' => 15],
            ],
        ],
        'Assignment 2: Automated Backup Shell Script' => [
            'learning_outcomes' => [
                'Menulis shell script Bash untuk melakukan backup folder secara otomatis dengan penamaan berbasis tanggal',
                'Menerapkan variabel dan struktur dasar Bash untuk membuat script yang dapat dijalankan ulang secara konsisten',
            ],
            'skills_learned' => ['Bash Scripting', 'Automation', 'Linux'],
            'prerequisites' => ['Linux Server Setup Exercise'],
            'tools' => ['Terminal', 'Bash'],
            'evaluation_rubrics' => [
                ['criteria' => 'Fungsionalitas Backup Berjalan Benar', 'weight' => 40],
                ['criteria' => 'Penamaan File/Folder Berbasis Tanggal', 'weight' => 25],
                ['criteria' => 'Struktur Script yang Rapi', 'weight' => 20],
                ['criteria' => 'Penanganan Error Dasar', 'weight' => 15],
            ],
        ],

        // ---------- Modul 2: Docker & Containerization ----------
        'Assignment 1: Dockerize a Node.js App' => [
            'learning_outcomes' => [
                'Menulis Dockerfile untuk aplikasi Node.js dengan instruksi FROM, WORKDIR, COPY, RUN, dan CMD yang tepat',
                'Membangun dan menjalankan image menjadi container yang dapat diakses dari luar',
            ],
            'skills_learned' => ['Docker', 'Dockerfile', 'Containerization'],
            'prerequisites' => ['Linux Fundamentals'],
            'tools' => ['Docker', 'VS Code', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Ketepatan Instruksi Dockerfile', 'weight' => 35],
                ['criteria' => 'Container Berjalan & Dapat Diakses', 'weight' => 30],
                ['criteria' => 'Ukuran Image yang Efisien', 'weight' => 20],
                ['criteria' => 'Dokumentasi Build & Run', 'weight' => 15],
            ],
        ],
        'Assignment 2: Multi-Container App with Docker Compose' => [
            'learning_outcomes' => [
                'Mendefinisikan beberapa service (aplikasi + database) dalam satu file docker-compose.yml',
                'Menggunakan volume agar data database tetap tersimpan meskipun container dihapus',
            ],
            'skills_learned' => ['Docker Compose', 'Volume', 'Multi-Container Architecture'],
            'prerequisites' => ['Dockerize a Node.js App'],
            'tools' => ['Docker Compose', 'VS Code', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Konfigurasi docker-compose.yml', 'weight' => 35],
                ['criteria' => 'Komunikasi Antar Service', 'weight' => 25],
                ['criteria' => 'Penggunaan Volume yang Tepat', 'weight' => 25],
                ['criteria' => 'Dokumentasi Setup', 'weight' => 15],
            ],
        ],

        // ---------- Modul 3: CI/CD Pipeline ----------
        'Assignment 1: GitHub Actions CI Pipeline' => [
            'learning_outcomes' => [
                'Membuat workflow GitHub Actions yang otomatis menjalankan test setiap kali ada push atau pull request',
                'Memahami struktur job dan step dalam file YAML workflow',
            ],
            'skills_learned' => ['CI/CD', 'GitHub Actions', 'YAML'],
            'prerequisites' => ['Git & Collaboration Workflow', 'Testing dasar'],
            'tools' => ['GitHub Actions', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Workflow Berjalan Otomatis pada Push/PR', 'weight' => 40],
                ['criteria' => 'Ketepatan Struktur Job & Step', 'weight' => 30],
                ['criteria' => 'Pipeline Gagal saat Test Gagal', 'weight' => 20],
                ['criteria' => 'Dokumentasi Workflow', 'weight' => 10],
            ],
        ],
        'Assignment 2: Automated Deployment Workflow' => [
            'learning_outcomes' => [
                'Memperluas pipeline CI menjadi CD yang men-deploy otomatis setelah test berhasil',
                'Menjelaskan strategi rollback sederhana jika deployment baru menyebabkan masalah',
            ],
            'skills_learned' => ['CI/CD', 'Deployment Strategy', 'Rollback'],
            'prerequisites' => ['GitHub Actions CI Pipeline'],
            'tools' => ['GitHub Actions', 'Docker', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Pipeline CD Berjalan Otomatis', 'weight' => 40],
                ['criteria' => 'Strategi Deployment yang Dipilih', 'weight' => 25],
                ['criteria' => 'Mekanisme Rollback', 'weight' => 25],
                ['criteria' => 'Dokumentasi Pipeline', 'weight' => 10],
            ],
        ],

        // ---------- Modul 4: Kubernetes & Orchestration ----------
        'Assignment 1: Deploy App to Kubernetes Cluster' => [
            'learning_outcomes' => [
                'Menulis konfigurasi Deployment dan Service untuk menjalankan aplikasi di Kubernetes',
                'Memahami hubungan antara Pod, Deployment, dan Service dalam menjaga aplikasi tetap berjalan',
            ],
            'skills_learned' => ['Kubernetes', 'Pod', 'Deployment', 'Service'],
            'prerequisites' => ['Docker & Containerization'],
            'tools' => ['kubectl', 'Minikube atau sejenisnya', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Konfigurasi Deployment yang Benar', 'weight' => 35],
                ['criteria' => 'Service Dapat Diakses', 'weight' => 30],
                ['criteria' => 'Pemahaman Pod Lifecycle', 'weight' => 20],
                ['criteria' => 'Dokumentasi Konfigurasi', 'weight' => 15],
            ],
        ],
        'Assignment 2: Autoscaling Configuration Exercise' => [
            'learning_outcomes' => [
                'Mengonfigurasi Horizontal Pod Autoscaler berdasarkan penggunaan CPU',
                'Menyimpan konfigurasi sensitif menggunakan Secret, terpisah dari ConfigMap yang tidak rahasia',
            ],
            'skills_learned' => ['Horizontal Pod Autoscaler', 'ConfigMap', 'Secret'],
            'prerequisites' => ['Deploy App to Kubernetes Cluster'],
            'tools' => ['kubectl', 'Minikube atau sejenisnya'],
            'evaluation_rubrics' => [
                ['criteria' => 'Konfigurasi HPA yang Tepat', 'weight' => 40],
                ['criteria' => 'Pemisahan ConfigMap & Secret', 'weight' => 30],
                ['criteria' => 'Pengujian Scaling saat Beban Naik', 'weight' => 20],
                ['criteria' => 'Dokumentasi Konfigurasi', 'weight' => 10],
            ],
        ],

        // ---------- Modul 5: Monitoring & Logging ----------
        'Assignment 1: Prometheus Monitoring Setup' => [
            'learning_outcomes' => [
                'Menyediakan endpoint /metrics pada aplikasi agar dapat di-scrape oleh Prometheus',
                'Mengidentifikasi metrics penting yang perlu dipantau (response time, error rate, resource usage)',
            ],
            'skills_learned' => ['Prometheus', 'Metrics', 'Observability'],
            'prerequisites' => ['Kubernetes & Orchestration (opsional)', 'Server Architecture & Performance'],
            'tools' => ['Prometheus', 'Grafana (opsional)'],
            'evaluation_rubrics' => [
                ['criteria' => 'Endpoint /metrics Berfungsi', 'weight' => 35],
                ['criteria' => 'Relevansi Metrics yang Dipantau', 'weight' => 30],
                ['criteria' => 'Konfigurasi Prometheus Scrape', 'weight' => 25],
                ['criteria' => 'Dokumentasi Setup', 'weight' => 10],
            ],
        ],
        'Assignment 2: Centralized Logging with ELK Stack' => [
            'learning_outcomes' => [
                'Mengumpulkan log dari beberapa sumber ke satu tempat terpusat menggunakan pendekatan ELK Stack',
                'Melakukan pencarian log berdasarkan kriteria tertentu (request ID, severity, rentang waktu)',
            ],
            'skills_learned' => ['ELK Stack', 'Centralized Logging'],
            'prerequisites' => ['Prometheus Monitoring Setup'],
            'tools' => ['Elasticsearch', 'Logstash/Fluentd', 'Kibana'],
            'evaluation_rubrics' => [
                ['criteria' => 'Log Terkumpul dari Berbagai Sumber', 'weight' => 35],
                ['criteria' => 'Kemampuan Pencarian Log', 'weight' => 30],
                ['criteria' => 'Struktur Log yang Konsisten', 'weight' => 20],
                ['criteria' => 'Dokumentasi Setup', 'weight' => 15],
            ],
        ],

        // ---------- Modul 6: Git & Infrastructure Workflow ----------
        'Assignment 1: Terraform Infrastructure Setup' => [
            'learning_outcomes' => [
                'Mendefinisikan infrastruktur (misalnya instance server) sebagai kode menggunakan Terraform',
                'Menyimpan konfigurasi infrastruktur di Git agar dapat diversikan dan direview sebelum diterapkan',
            ],
            'skills_learned' => ['Terraform', 'Infrastructure as Code', 'Git'],
            'prerequisites' => ['Linux Fundamentals', 'Git & Collaboration Workflow'],
            'tools' => ['Terraform', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Konfigurasi Terraform yang Valid', 'weight' => 40],
                ['criteria' => 'Struktur Repository IaC', 'weight' => 25],
                ['criteria' => 'terraform apply Berhasil', 'weight' => 20],
                ['criteria' => 'Dokumentasi Konfigurasi', 'weight' => 15],
            ],
        ],
        'Assignment 2: GitOps Deployment with ArgoCD' => [
            'learning_outcomes' => [
                'Menerapkan alur GitOps di mana perubahan konfigurasi di Git otomatis diterapkan ke cluster',
                'Menjelaskan perbedaan pendekatan GitOps (pull-based) dengan CI/CD tradisional (push-based)',
            ],
            'skills_learned' => ['GitOps', 'ArgoCD', 'Kubernetes'],
            'prerequisites' => ['Terraform Infrastructure Setup', 'Kubernetes & Orchestration'],
            'tools' => ['ArgoCD', 'kubectl', 'Git'],
            'evaluation_rubrics' => [
                ['criteria' => 'Sinkronisasi Otomatis dari Git ke Cluster', 'weight' => 40],
                ['criteria' => 'Struktur Repository GitOps', 'weight' => 25],
                ['criteria' => 'Penjelasan GitOps vs CI/CD Tradisional', 'weight' => 20],
                ['criteria' => 'Dokumentasi Setup', 'weight' => 15],
            ],
        ],

        // ============================================================
        // ======================= UI/UX DESIGNER =======================
        // ============================================================

        // ---------- Modul 1: Design Thinking & User Research ----------
        'Assignment 1: User Research & Persona Creation' => [
            'learning_outcomes' => [
                'Menyusun pertanyaan wawancara terbuka dan melakukan sesi wawancara pengguna singkat',
                'Merangkum hasil riset menjadi user persona yang didasarkan pada data nyata, bukan asumsi',
            ],
            'skills_learned' => ['User Research', 'Wawancara Pengguna', 'User Persona'],
            'prerequisites' => ['Apa Itu Design Thinking'],
            'tools' => ['Google Forms / Notion', 'Figma (opsional)'],
            'evaluation_rubrics' => [
                ['criteria' => 'Kualitas Pertanyaan Wawancara', 'weight' => 30],
                ['criteria' => 'Ketepatan Persona Berdasar Data', 'weight' => 35],
                ['criteria' => 'Kejelasan Tujuan & Hambatan Persona', 'weight' => 20],
                ['criteria' => 'Presentasi Dokumen', 'weight' => 15],
            ],
        ],
        'Assignment 2: Customer Journey Map Project' => [
            'learning_outcomes' => [
                'Memetakan tahapan, tindakan, dan emosi pengguna sepanjang perjalanan menggunakan suatu produk',
                'Mengidentifikasi pain point dan peluang perbaikan dari journey map yang disusun',
            ],
            'skills_learned' => ['User Journey Mapping', 'UX Research'],
            'prerequisites' => ['User Research & Persona Creation'],
            'tools' => ['Figma / FigJam', 'Miro (opsional)'],
            'evaluation_rubrics' => [
                ['criteria' => 'Kelengkapan Tahapan Journey', 'weight' => 30],
                ['criteria' => 'Identifikasi Pain Point', 'weight' => 35],
                ['criteria' => 'Relevansi dengan Persona', 'weight' => 20],
                ['criteria' => 'Kejelasan Visual Journey Map', 'weight' => 15],
            ],
        ],

        // ---------- Modul 2: Wireframing & Prototyping ----------
        'Assignment 1: Low-Fidelity Wireframe Set' => [
            'learning_outcomes' => [
                'Membuat low-fidelity wireframe untuk minimal 3 halaman utama suatu aplikasi',
                'Menerapkan prinsip hierarki visual (proximity, alignment, whitespace) pada susunan wireframe',
            ],
            'skills_learned' => ['Wireframing', 'Visual Hierarchy'],
            'prerequisites' => ['Customer Journey Map Project'],
            'tools' => ['Figma'],
            'evaluation_rubrics' => [
                ['criteria' => 'Kejelasan Struktur Tiap Halaman', 'weight' => 35],
                ['criteria' => 'Penerapan Hierarki Visual', 'weight' => 30],
                ['criteria' => 'Konsistensi Antar Wireframe', 'weight' => 20],
                ['criteria' => 'Kerapian File Figma', 'weight' => 15],
            ],
        ],
        'Assignment 2: Interactive Prototype in Figma' => [
            'learning_outcomes' => [
                'Menghubungkan antar frame di Figma menjadi prototype yang dapat diklik dan dijelajahi',
                'Membangun beberapa komponen dasar (tombol, form) yang konsisten mengikuti design system sederhana',
            ],
            'skills_learned' => ['Figma Prototyping', 'Design System', 'Interactive Design'],
            'prerequisites' => ['Low-Fidelity Wireframe Set'],
            'tools' => ['Figma'],
            'evaluation_rubrics' => [
                ['criteria' => 'Alur Prototype Berfungsi (klik antar frame)', 'weight' => 40],
                ['criteria' => 'Konsistensi Komponen', 'weight' => 25],
                ['criteria' => 'Kelengkapan Alur Utama', 'weight' => 20],
                ['criteria' => 'Kerapian File Figma', 'weight' => 15],
            ],
        ],

        // ---------- Modul 3: Visual Design & Tipografi ----------
        'Assignment 1: Mobile App Visual Design' => [
            'learning_outcomes' => [
                'Menerapkan palet warna primary/secondary/semantic secara konsisten pada desain aplikasi mobile',
                'Menggunakan skala tipografi yang konsisten untuk menjaga hierarki teks di seluruh halaman',
            ],
            'skills_learned' => ['Visual Design', 'Color Theory', 'Tipografi'],
            'prerequisites' => ['Interactive Prototype in Figma'],
            'tools' => ['Figma'],
            'evaluation_rubrics' => [
                ['criteria' => 'Konsistensi Palet Warna', 'weight' => 30],
                ['criteria' => 'Konsistensi Skala Tipografi', 'weight' => 30],
                ['criteria' => 'Kontras & Keterbacaan', 'weight' => 25],
                ['criteria' => 'Kerapian Desain', 'weight' => 15],
            ],
        ],
        'Assignment 2: Design System Style Guide' => [
            'learning_outcomes' => [
                'Menyusun style guide berisi warna, tipografi, spacing, dan komponen dasar yang dapat dipakai ulang',
                'Menerapkan grid system dan spacing berbasis kelipatan tertentu (misal 8px) secara konsisten',
            ],
            'skills_learned' => ['Design System', 'Grid System', 'Component Design'],
            'prerequisites' => ['Mobile App Visual Design'],
            'tools' => ['Figma'],
            'evaluation_rubrics' => [
                ['criteria' => 'Kelengkapan Style Guide', 'weight' => 35],
                ['criteria' => 'Konsistensi Grid & Spacing', 'weight' => 30],
                ['criteria' => 'Reusability Komponen', 'weight' => 20],
                ['criteria' => 'Dokumentasi Penggunaan', 'weight' => 15],
            ],
        ],

        // ---------- Modul 4: HTML & CSS untuk Designer ----------
        'Assignment 1: Static Page from Figma Design' => [
            'learning_outcomes' => [
                'Menerjemahkan desain Figma menjadi struktur HTML dasar menggunakan elemen semantik',
                'Menerapkan CSS dasar (selector, warna, spacing) sesuai spesifikasi desain',
            ],
            'skills_learned' => ['HTML', 'CSS', 'Design-to-Code'],
            'prerequisites' => ['Design System Style Guide'],
            'tools' => ['VS Code', 'Figma', 'Chrome DevTools'],
            'evaluation_rubrics' => [
                ['criteria' => 'Kesesuaian dengan Desain Figma', 'weight' => 35],
                ['criteria' => 'Struktur HTML Semantik', 'weight' => 25],
                ['criteria' => 'Ketepatan Styling CSS', 'weight' => 25],
                ['criteria' => 'Kerapian Kode', 'weight' => 15],
            ],
        ],
        'Assignment 2: Responsive Landing Page Handoff' => [
            'learning_outcomes' => [
                'Menerapkan flexbox dasar agar halaman menyesuaikan tampilan di berbagai ukuran layar',
                'Mempertimbangkan batasan implementasi teknis saat menyiapkan desain untuk di-handoff ke developer',
            ],
            'skills_learned' => ['CSS Flexbox', 'Responsive Design', 'Design Handoff'],
            'prerequisites' => ['Static Page from Figma Design'],
            'tools' => ['VS Code', 'Figma', 'Chrome DevTools'],
            'evaluation_rubrics' => [
                ['criteria' => 'Responsivitas di Berbagai Layar', 'weight' => 35],
                ['criteria' => 'Kesesuaian dengan Desain Asli', 'weight' => 30],
                ['criteria' => 'Kesiapan untuk Handoff (spec jelas)', 'weight' => 20],
                ['criteria' => 'Kerapian Kode', 'weight' => 15],
            ],
        ],

        // ---------- Modul 5: Usability Testing ----------
        'Assignment 1: Usability Testing Session Report' => [
            'learning_outcomes' => [
                'Menyusun skenario tugas yang jelas dan menjalankan sesi usability testing terhadap prototype',
                'Mencatat temuan observasi (waktu, kesalahan, komentar) secara sistematis, bukan sekadar opini',
            ],
            'skills_learned' => ['Usability Testing', 'User Observation'],
            'prerequisites' => ['Interactive Prototype in Figma'],
            'tools' => ['Figma', 'Google Forms / Notion untuk catatan'],
            'evaluation_rubrics' => [
                ['criteria' => 'Kejelasan Skenario Tugas', 'weight' => 30],
                ['criteria' => 'Kualitas Catatan Observasi', 'weight' => 35],
                ['criteria' => 'Identifikasi Pola Masalah', 'weight' => 20],
                ['criteria' => 'Kejelasan Laporan', 'weight' => 15],
            ],
        ],
        'Assignment 2: Design Iteration Based on Feedback' => [
            'learning_outcomes' => [
                'Memprioritaskan temuan usability testing berdasarkan dampaknya terhadap pengalaman pengguna',
                'Melakukan revisi desain berdasarkan temuan dan menjelaskan perubahan yang dilakukan',
            ],
            'skills_learned' => ['Design Iteration', 'Prioritization', 'Usability Testing'],
            'prerequisites' => ['Usability Testing Session Report'],
            'tools' => ['Figma'],
            'evaluation_rubrics' => [
                ['criteria' => 'Ketepatan Prioritas Perbaikan', 'weight' => 35],
                ['criteria' => 'Kualitas Revisi Desain', 'weight' => 35],
                ['criteria' => 'Penjelasan Perubahan yang Dilakukan', 'weight' => 20],
                ['criteria' => 'Kerapian Dokumentasi', 'weight' => 10],
            ],
        ],

        // ---------- Modul 6: Design Handoff & Kolaborasi dengan Developer ----------
        'Assignment 1: Design Handoff Documentation' => [
            'learning_outcomes' => [
                'Menyiapkan dokumentasi handoff lengkap (ukuran, warna, spacing, aset) untuk satu halaman desain',
                'Memastikan seluruh komponen menggunakan nama yang konsisten dan mengikuti design system',
            ],
            'skills_learned' => ['Design Handoff', 'Documentation', 'Design System'],
            'prerequisites' => ['Design System Style Guide'],
            'tools' => ['Figma (fitur Inspect)'],
            'evaluation_rubrics' => [
                ['criteria' => 'Kelengkapan Spesifikasi Handoff', 'weight' => 40],
                ['criteria' => 'Konsistensi Penamaan Komponen', 'weight' => 25],
                ['criteria' => 'Kesiapan Aset (ikon, gambar)', 'weight' => 20],
                ['criteria' => 'Kejelasan Dokumentasi', 'weight' => 15],
            ],
        ],
        'Assignment 2: Design QA Checklist Exercise' => [
            'learning_outcomes' => [
                'Menyusun checklist Design QA untuk memverifikasi hasil implementasi sesuai desain',
                'Mendokumentasikan temuan perbedaan (warna, spacing, border radius, dll.) secara spesifik dan actionable',
            ],
            'skills_learned' => ['Design QA', 'Quality Assurance', 'Kolaborasi dengan Developer'],
            'prerequisites' => ['Design Handoff Documentation'],
            'tools' => ['Figma'],
            'evaluation_rubrics' => [
                ['criteria' => 'Kelengkapan Checklist QA', 'weight' => 35],
                ['criteria' => 'Ketepatan Temuan Perbedaan', 'weight' => 35],
                ['criteria' => 'Kejelasan Rekomendasi Perbaikan', 'weight' => 20],
                ['criteria' => 'Format Dokumentasi', 'weight' => 10],
            ],
        ],
    ];

    public function run(): void
    {
        if (empty($this->assignmentDetails)) {
            $this->command?->warn('AssignmentDetailSeeder: tidak ada data untuk di-seed.');
            return;
        }

        $updated = 0;
        $skipped = [];

        foreach ($this->assignmentDetails as $title => $detail) {
            $assignment = Assignment::where('title', $title)->first();

            if (! $assignment) {
                // Jangan hentikan seluruh seeder cuma karena satu judul
                // belum ada (mungkin AddAssignmentsToExistingModulesSeeder
                // belum dijalankan untuk career tertentu) — catat saja dan
                // lanjut ke entri berikutnya.
                $skipped[] = $title;
                continue;
            }

            $assignment->update($detail);
            $updated++;
        }

        $this->command?->info("AssignmentDetailSeeder: {$updated} assignment berhasil diperbarui.");

        if (! empty($skipped)) {
            $this->command?->warn(
                'AssignmentDetailSeeder: assignment berikut tidak ditemukan (jalankan AddAssignmentsToExistingModulesSeeder dulu untuk Backend/Data Analyst/DevOps/UI-UX): '
                . implode(', ', $skipped)
            );
            Log::warning('AssignmentDetailSeeder: assignment tidak ditemukan', ['titles' => $skipped]);
        }
    }
}