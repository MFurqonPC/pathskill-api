<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\CodingExercise;
use Illuminate\Database\Seeder;

class CodingExerciseFullStackSeeder extends Seeder
{
    /**
     * Coding exercise untuk seluruh assignment teknis di career
     * Full Stack Developer (Modul 1-5). Ini adalah rename & lanjutan
     * dari CodingExerciseSeeder.php lama (yang hanya mencakup Modul 1:
     * Frontend Fundamentals) — mengikuti pola per-career yang sama
     * seperti QuizFullStackSeeder, QuizBackendSeeder, dst.
     *
     * "Assignment 4: Quiz: ES6+ Concepts" di Modul 2 sengaja TIDAK
     * dibuatkan coding exercise karena sifatnya kuis konsep, sudah
     * ter-cover oleh QuizFullStackSeeder.
     *
     * Jalankan setelah LearningPathSeeder, AssignmentDetailSeeder, dan
     * QuizFullStackSeeder (assignment-nya harus sudah ada). Idempotent
     * lewat updateOrCreate berdasarkan assignment_id.
     *
     * Jalankan:
     *   php artisan db:seed --class=CodingExerciseFullStackSeeder
     */
    public function run(): void
    {
        foreach ($this->exerciseData() as $assignmentTitle => $data) {
            $assignment = Assignment::where('title', $assignmentTitle)->first();

            if (! $assignment) {
                $this->command?->warn("CodingExerciseFullStackSeeder: assignment tidak ditemukan — {$assignmentTitle}");
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
            // Modul 1: Frontend Fundamentals (sudah ada sebelumnya)
            // ============================================================

            'Assignment 1: Personal Portfolio Page' => [
                'title' => 'Latihan: Section Hero Responsif',
                'description' => 'Lengkapi section hero di bawah supaya menggunakan elemen semantik yang benar dan layout-nya tetap rapi di layar kecil maupun besar. Ini latihan singkat sebelum kamu mengerjakan Mini Project portofolio secara utuh.',
                'learning_objectives' => [
                    'Menerapkan elemen HTML semantik (<header>, <nav>, <main>) pada struktur halaman',
                    'Membuat layout yang menyesuaikan lebar layar tanpa media query tambahan',
                ],
                'requirements' => [
                    'Bungkus navigasi utama dengan <nav>, bukan <div>',
                    'Gunakan CSS Grid/Flexbox agar kolom otomatis menyesuaikan lebar layar',
                    'Tambahkan atribut alt pada setiap <img>',
                ],
                'test_cases' => [
                    'Navigasi utama sudah pakai <nav>, bukan lagi <div class="hero-nav">',
                    'Section hero dibungkus <header>, bukan <div class="hero">',
                    'Layout tetap rapi kalau lebar browser diperkecil ke ukuran HP',
                    'Setiap <img> punya atribut alt yang mendeskripsikan gambarnya',
                ],
                'language' => 'html',
                'starter_code' => <<<'CODE'
<!-- Lengkapi section hero di bawah ini -->
<div class="hero">
  <div class="hero-nav">
    <a href="#about">About</a>
    <a href="#projects">Projects</a>
    <a href="#contact">Contact</a>
  </div>
  <div class="hero-content">
    <img src="profile.jpg">
    <h1>Halo, saya [Nama Kamu]</h1>
    <p>Frontend Developer</p>
  </div>
</div>
CODE,
                'hint' => 'Ganti <div class="hero-nav"> dengan <nav>, dan bungkus keseluruhan section dengan <header>. Untuk layout, coba grid-template-columns: repeat(auto-fit, minmax(...)) seperti di materi Quiz sebelumnya.',
            ],

            'Assignment 2: Responsive Landing Page' => [
                'title' => 'Latihan: Section Fitur dengan CSS Grid Responsif',
                'description' => 'Lengkapi section "Fitur" di bawah supaya kartu-kartu fiturnya tersusun dalam grid yang otomatis menyesuaikan jumlah kolom berdasarkan lebar layar — 1 kolom di HP, 2 kolom di tablet, 3 kolom di desktop — tanpa menulis media query manual untuk masing-masing breakpoint.',
                'learning_objectives' => [
                    'Menggunakan CSS Grid dengan repeat(auto-fit, minmax(...)) untuk layout kartu yang responsif',
                    'Menerapkan box-sizing: border-box agar padding tidak merusak perhitungan lebar kartu',
                ],
                'requirements' => [
                    'Setiap kartu fitur dibungkus <div class="kartu-fitur"> yang sudah tersedia di starter code',
                    'Container .fitur-grid harus pakai display: grid, bukan flexbox',
                    'Kartu otomatis pindah ke baris baru saat lebar layar tidak cukup, tanpa media query manual',
                    'Beri jarak antar kartu menggunakan properti gap, bukan margin manual di tiap kartu',
                ],
                'test_cases' => [
                    'Di layar desktop lebar, kartu tersusun jadi 3 kolom sejajar',
                    'Di layar HP sempit, kartu otomatis turun jadi 1 kolom per baris',
                    'Jarak antar kartu terlihat konsisten baik secara horizontal maupun vertikal',
                    'Tidak ada kartu yang overflow keluar dari container',
                ],
                'language' => 'html',
                'starter_code' => <<<'CODE'
<!-- Lengkapi CSS Grid untuk section fitur ini -->
<section class="fitur">
  <h2>Fitur Unggulan</h2>
  <div class="fitur-grid">
    <div class="kartu-fitur">
      <h3>Cepat</h3>
      <p>Performa tinggi di setiap request.</p>
    </div>
    <div class="kartu-fitur">
      <h3>Aman</h3>
      <p>Data terenkripsi end-to-end.</p>
    </div>
    <div class="kartu-fitur">
      <h3>Fleksibel</h3>
      <p>Bisa disesuaikan dengan kebutuhanmu.</p>
    </div>
  </div>
</section>

<style>
.fitur-grid {
  /* TODO: tambahkan display: grid dan grid-template-columns
     yang otomatis menyesuaikan lebar layar di sini */
}

.kartu-fitur {
  box-sizing: border-box;
  padding: 24px;
  border: 1px solid #ddd;
  border-radius: 8px;
}
</style>
CODE,
                'hint' => 'Coba tambahkan display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; pada .fitur-grid — nilai minmax menentukan lebar minimum tiap kartu sebelum grid memutuskan pindah baris.',
            ],

            'Assignment 3: Interactive To-Do List' => [
                'title' => 'Latihan: Tambah & Hapus Todo dengan JavaScript',
                'description' => 'Lengkapi kode JavaScript di bawah supaya pengguna bisa menambahkan todo baru lewat input dan tombol, serta menghapus todo dengan mengklik tombol "Hapus" di masing-masing item — semuanya tanpa reload halaman.',
                'learning_objectives' => [
                    'Menggunakan addEventListener untuk menangani klik tombol',
                    'Melakukan DOM manipulation: createElement, appendChild, dan remove',
                ],
                'requirements' => [
                    'Klik tombol "Tambah" menambahkan item <li> baru berisi teks dari input ke dalam <ul id="daftarTodo">',
                    'Setiap item todo baru harus punya tombol "Hapus" sendiri',
                    'Klik tombol "Hapus" pada suatu item akan menghapus HANYA item tersebut dari daftar',
                    'Input dikosongkan lagi setelah todo berhasil ditambahkan',
                ],
                'test_cases' => [
                    'Mengetik "Beli susu" lalu klik Tambah → muncul item baru "Beli susu" di daftar',
                    'Input kosong (tidak diisi apa-apa) lalu klik Tambah → tidak ada item baru yang ditambahkan',
                    'Klik "Hapus" pada salah satu item → item itu hilang, item lain tetap ada',
                    'Setelah menambah todo, kotak input kembali kosong untuk todo berikutnya',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
// HTML terkait (sudah tersedia di halaman):
// <input id="inputTodo" type="text" placeholder="Tulis todo baru...">
// <button id="tambahBtn">Tambah</button>
// <ul id="daftarTodo"></ul>

const input = document.querySelector('#inputTodo');
const tombolTambah = document.querySelector('#tambahBtn');
const daftar = document.querySelector('#daftarTodo');

tombolTambah.addEventListener('click', () => {
  // TODO 1: ambil teks dari input, jangan lanjut kalau kosong

  // TODO 2: buat elemen <li> baru berisi teks todo
  //         plus tombol "Hapus" di dalamnya

  // TODO 3: tambahkan <li> itu ke dalam #daftarTodo

  // TODO 4: kosongkan lagi input setelah todo ditambahkan
});
CODE,
                'hint' => 'Untuk tombol hapus per item, kamu bisa langsung addEventListener pada tombol tersebut saat item dibuat, lalu panggil item.remove() di dalam handler-nya — tidak perlu mencari index di array secara manual untuk latihan ini.',
            ],

            // ============================================================
            // Modul 2: Modern JavaScript & ES6+
            // (Assignment 4 "Quiz: ES6+ Concepts" sengaja di-skip)
            // ============================================================

            'Assignment 1: Async Weather App' => [
                'title' => 'Latihan: Fetch Cuaca dengan Async/Await',
                'description' => 'Lengkapi fungsi ambilCuaca di bawah supaya mengambil data cuaca dari API menggunakan async/await, dan menampilkan pesan error yang ramah kalau kota tidak ditemukan atau request gagal.',
                'learning_objectives' => [
                    'Menggunakan async/await untuk menangani Promise dari Fetch API',
                    'Menangani error dengan try...catch tanpa membuat aplikasi crash',
                ],
                'requirements' => [
                    'Gunakan async/await, bukan rangkaian .then()',
                    'Tampilkan teks "Memuat..." saat data sedang diambil',
                    'Tangkap error dengan try...catch dan tampilkan pesan "Kota tidak ditemukan" jika gagal',
                    'Tampilkan suhu dan kondisi cuaca ke dalam #hasilCuaca jika berhasil',
                ],
                'test_cases' => [
                    'Mengetik nama kota valid lalu klik Cari → data suhu & kondisi muncul',
                    'Mengetik nama kota tidak valid → muncul pesan "Kota tidak ditemukan", bukan error di console',
                    'Saat proses fetch berjalan, teks "Memuat..." sempat tampil',
                    'Tidak ada unhandled promise rejection di console',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
// HTML terkait:
// <input id="kotaInput" type="text" placeholder="Nama kota...">
// <button id="cariBtn">Cari</button>
// <div id="hasilCuaca"></div>

const input = document.querySelector('#kotaInput');
const tombolCari = document.querySelector('#cariBtn');
const hasil = document.querySelector('#hasilCuaca');

async function ambilCuaca(kota) {
  // TODO 1: fetch data dari API cuaca (misal https://api.contoh.com/cuaca?kota=...)
  //         menggunakan await, lalu parse JSON-nya

  // TODO 2: bungkus dengan try...catch — kalau gagal, tampilkan pesan
  //         "Kota tidak ditemukan" ke #hasilCuaca

  // TODO 3: kalau berhasil, render suhu & kondisi cuaca ke #hasilCuaca
}

tombolCari.addEventListener('click', () => {
  const kota = input.value.trim();
  if (!kota) return;
  hasil.textContent = 'Memuat...';
  ambilCuaca(kota);
});
CODE,
                'hint' => 'Struktur dasarnya: try { const res = await fetch(url); if (!res.ok) throw new Error(); const data = await res.json(); ...render... } catch (err) { hasil.textContent = "Kota tidak ditemukan"; }',
            ],

            'Assignment 2: API Data Fetcher' => [
                'title' => 'Latihan: Olah Data API dengan Array Methods',
                'description' => 'Lengkapi module dataFetcher.js supaya mengambil data dari API, memfilter data yang tidak valid, lalu mentransformnya menjadi bentuk yang lebih sederhana sebelum dikembalikan — semuanya menggunakan array methods, bukan for loop.',
                'learning_objectives' => [
                    'Menggunakan .filter() dan .map() untuk mengolah data hasil fetch',
                    'Memisahkan logic fetching ke module tersendiri menggunakan export/import',
                ],
                'requirements' => [
                    'Gunakan .filter() untuk membuang item yang field name-nya kosong',
                    'Gunakan .map() untuk mengubah tiap item menjadi bentuk { id, name } saja',
                    'Fungsi fetchData harus di-export dan dipanggil dari main.js lewat import',
                    'Tangani error fetch dengan try...catch, kembalikan array kosong jika gagal',
                ],
                'test_cases' => [
                    'Data dengan beberapa item name kosong → item tersebut tidak muncul di hasil akhir',
                    'Hasil akhir tiap item hanya punya properti id dan name, tidak ada field lain',
                    'main.js bisa import fetchData dari dataFetcher.js tanpa error',
                    'Kalau fetch gagal, fungsi mengembalikan array kosong, bukan throw ke luar',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
// dataFetcher.js
export async function fetchData(url) {
  try {
    const res = await fetch(url);
    const data = await res.json();

    // TODO 1: filter item yang field 'name'-nya kosong/undefined

    // TODO 2: map data jadi bentuk sederhana { id, name }

    // TODO 3: return array hasil transformasi
  } catch (err) {
    // TODO 4: kembalikan array kosong jika terjadi error
  }
}

// main.js
// import { fetchData } from './dataFetcher.js';
//
// fetchData('https://api.contoh.com/items').then((items) => {
//   console.log(items);
// });
CODE,
                'hint' => 'Urutannya: data.filter(item => item.name).map(item => ({ id: item.id, name: item.name })). Untuk TODO 4, cukup return [] di dalam blok catch.',
            ],

            'Assignment 3: Module Refactor Exercise' => [
                'title' => 'Latihan: Refactor Kode Monolitik ke Module',
                'description' => 'Kode di bawah masih menumpuk dalam satu file dan pakai function biasa. Pisahkan jadi dua module terpisah (hitung.js dan main.js), lalu sederhanakan fungsinya menggunakan arrow function dan destructuring.',
                'learning_objectives' => [
                    'Memisahkan kode menjadi beberapa module menggunakan export/import',
                    'Menyederhanakan function declaration menjadi arrow function dan menggunakan destructuring parameter',
                ],
                'requirements' => [
                    'Pindahkan fungsi hitungTotal dan hitungDiskon ke file hitung.js, lalu export keduanya',
                    'Import kedua fungsi itu di main.js menggunakan named import',
                    'Ubah function biasa menjadi arrow function',
                    'Gunakan destructuring untuk mengambil properti dari objek keranjang, bukan keranjang.harga dst.',
                ],
                'test_cases' => [
                    'hitung.js meng-export hitungTotal dan hitungDiskon dengan export bernama',
                    'main.js berhasil import dari hitung.js tanpa error dan hasil kalkulasi tetap sama seperti sebelum refactor',
                    'Kedua fungsi sudah berbentuk arrow function, bukan function keyword',
                    'Parameter fungsi menggunakan destructuring, misalnya ({ harga, jumlah }) bukan keranjang.harga',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
// Kode lama (sebelum refactor) — semua ada di satu file:

function hitungTotal(keranjang) {
  let total = 0;
  for (let i = 0; i < keranjang.length; i++) {
    total += keranjang[i].harga * keranjang[i].jumlah;
  }
  return total;
}

function hitungDiskon(keranjang, persen) {
  const total = hitungTotal(keranjang);
  return total - (total * persen / 100);
}

// TODO 1: pindahkan kedua fungsi di atas ke file hitung.js
// TODO 2: ubah jadi arrow function
// TODO 3: gunakan destructuring pada parameter item keranjang
// TODO 4: export kedua fungsi, lalu import & panggil dari main.js
CODE,
                'hint' => 'Contoh arrow function dengan destructuring: const hitungItemTotal = ({ harga, jumlah }) => harga * jumlah; lalu gunakan .reduce() menggantikan for loop di hitungTotal kalau mau lebih ringkas (opsional).',
            ],

            // ============================================================
            // Modul 3: React Essentials
            // ============================================================

            'Assignment 1: Todo App with React' => [
                'title' => 'Latihan: Toggle Status Selesai pada Todo',
                'description' => 'Lengkapi komponen TodoApp supaya pengguna bisa menandai todo sebagai selesai (dengan mengklik teksnya), dan menampilkan jumlah todo yang sudah selesai secara kondisional di bagian atas.',
                'learning_objectives' => [
                    'Mengubah state array dengan cara yang immutable saat toggle satu item',
                    'Menerapkan conditional rendering untuk menampilkan ringkasan status',
                ],
                'requirements' => [
                    'Klik teks todo mengubah status selesai-nya (true/false)',
                    'Todo yang selesai ditampilkan dengan gaya coret (misal className "selesai")',
                    'Tampilkan teks "X dari Y todo selesai" di atas daftar, hanya jika ada minimal 1 todo',
                    'Gunakan key unik (id) pada setiap item saat map()',
                ],
                'test_cases' => [
                    'Klik satu todo → status selesainya berubah, todo lain tidak ikut berubah',
                    'Todo yang selesai punya class visual berbeda dari yang belum',
                    'Ringkasan jumlah selesai muncul dan update otomatis setelah toggle',
                    'Daftar todo kosong → ringkasan status tidak ditampilkan',
                ],
                'language' => 'jsx',
                'starter_code' => <<<'CODE'
import { useState } from 'react';

const initialTodos = [
  { id: 1, teks: 'Belajar React', selesai: false },
  { id: 2, teks: 'Buat portofolio', selesai: false },
];

export default function TodoApp() {
  const [todos, setTodos] = useState(initialTodos);

  const toggleTodo = (id) => {
    // TODO 1: buat array baru (jangan mutate langsung) di mana
    //         todo dengan id yang cocok status selesainya dibalik
  };

  return (
    <div>
      {/* TODO 2: tampilkan "X dari Y todo selesai" di sini,
          hanya jika todos.length > 0 */}

      <ul>
        {todos.map((todo) => (
          <li
            key={todo.id}
            onClick={() => toggleTodo(todo.id)}
            className={todo.selesai ? 'selesai' : ''}
          >
            {todo.teks}
          </li>
        ))}
      </ul>
    </div>
  );
}
CODE,
                'hint' => 'Untuk TODO 1: setTodos(todos.map(t => t.id === id ? { ...t, selesai: !t.selesai } : t)). Untuk TODO 2, hitung dulu jumlah selesai dengan todos.filter(t => t.selesai).length sebelum di-render.',
            ],

            'Assignment 2: Movie Search App' => [
                'title' => 'Latihan: useEffect untuk Pencarian Film',
                'description' => 'Lengkapi komponen MovieSearch supaya setiap kali kata kunci berubah, aplikasi mengambil data film dari API lewat useEffect, dan menampilkan status loading/error secara kondisional.',
                'learning_objectives' => [
                    'Menggunakan useEffect dengan dependency array yang tepat agar fetch berjalan saat keyword berubah',
                    'Menerapkan conditional rendering untuk state loading, error, dan data kosong',
                ],
                'requirements' => [
                    'useEffect harus fetch ulang setiap kali state keyword berubah',
                    'Tampilkan teks "Mencari..." selama proses fetch berlangsung',
                    'Tampilkan pesan error jika request gagal, tanpa membuat aplikasi crash',
                    'Kalau hasil kosong, tampilkan "Film tidak ditemukan"',
                ],
                'test_cases' => [
                    'Mengetik keyword baru → hasil film ter-update sesuai keyword tersebut',
                    'Selama proses fetch, teks "Mencari..." sempat tampil',
                    'API gagal/di-mock error → pesan error muncul, bukan halaman blank',
                    'Keyword yang tidak menghasilkan film apapun → muncul "Film tidak ditemukan"',
                ],
                'language' => 'jsx',
                'starter_code' => <<<'CODE'
import { useState, useEffect } from 'react';

export default function MovieSearch() {
  const [keyword, setKeyword] = useState('');
  const [movies, setMovies] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  useEffect(() => {
    if (!keyword) return;

    // TODO 1: set loading true, reset error
    // TODO 2: fetch data film berdasarkan keyword (async function di dalam useEffect)
    // TODO 3: kalau berhasil, simpan ke state movies
    // TODO 4: kalau gagal, simpan pesan error ke state error
    // TODO 5: set loading false di akhir (baik berhasil maupun gagal)
  }, [keyword]);

  return (
    <div>
      <input value={keyword} onChange={(e) => setKeyword(e.target.value)} placeholder="Cari film..." />

      {/* TODO 6: tampilkan "Mencari...", pesan error, "Film tidak ditemukan",
          atau daftar movies sesuai kondisi masing-masing state */}
    </div>
  );
}
CODE,
                'hint' => 'Buat fungsi async terpisah di dalam useEffect (misal const ambilFilm = async () => {...}) lalu panggil ambilFilm(); di baris terakhir — useEffect sendiri tidak boleh async langsung.',
            ],

            'Assignment 3: Multi-step Form' => [
                'title' => 'Latihan: State Tersinkronisasi Antar Step Form',
                'description' => 'Lengkapi komponen MultiStepForm supaya data yang diisi di step 1 tetap tersimpan saat pengguna pindah ke step 2 dan kembali lagi ke step 1.',
                'learning_objectives' => [
                    'Menyimpan data form di parent component supaya tidak hilang saat pindah step',
                    'Menerapkan conditional rendering untuk menampilkan step yang aktif',
                ],
                'requirements' => [
                    'State data form (nama, email) disimpan di komponen induk, bukan di tiap step',
                    'Tombol "Lanjut" memindahkan ke step berikutnya tanpa menghapus data yang sudah diisi',
                    'Tombol "Kembali" mengembalikan ke step sebelumnya dengan data tetap terisi',
                    'Step yang tidak aktif tidak dirender sama sekali (bukan cuma disembunyikan lewat CSS)',
                ],
                'test_cases' => [
                    'Isi nama di step 1, klik Lanjut, klik Kembali → nama masih terisi seperti sebelumnya',
                    'Step 2 tidak muncul saat masih di step 1, dan sebaliknya',
                    'Data dari step 1 dan step 2 sama-sama tersedia saat submit di step terakhir',
                ],
                'language' => 'jsx',
                'starter_code' => <<<'CODE'
import { useState } from 'react';

export default function MultiStepForm() {
  const [step, setStep] = useState(1);
  const [formData, setFormData] = useState({ nama: '', email: '' });

  const updateField = (field, value) => {
    // TODO 1: update formData tanpa menghapus field lain (immutable update)
  };

  return (
    <div>
      {step === 1 && (
        <div>
          <input
            placeholder="Nama"
            value={formData.nama}
            onChange={(e) => updateField('nama', e.target.value)}
          />
          <button onClick={() => setStep(2)}>Lanjut</button>
        </div>
      )}

      {step === 2 && (
        <div>
          <input
            placeholder="Email"
            value={formData.email}
            onChange={(e) => updateField('email', e.target.value)}
          />
          {/* TODO 2: tambahkan tombol "Kembali" ke step 1 */}
          <button onClick={() => console.log(formData)}>Submit</button>
        </div>
      )}
    </div>
  );
}
CODE,
                'hint' => 'TODO 1: setFormData(prev => ({ ...prev, [field]: value })) — pola ini menjaga field lain tetap ada. TODO 2: cukup <button onClick={() => setStep(1)}>Kembali</button>.',
            ],

            'Assignment 4: Shopping Cart' => [
                'title' => 'Latihan: Kelola State Keranjang Belanja',
                'description' => 'Lengkapi komponen ShoppingCart supaya pengguna bisa menambah jumlah, mengurangi jumlah, dan menghapus item dari keranjang, serta melihat total harga yang otomatis ter-update.',
                'learning_objectives' => [
                    'Mengelola state array objek (tambah, kurang, hapus item) secara immutable',
                    'Menggunakan .reduce() untuk menghitung total harga dari seluruh item',
                ],
                'requirements' => [
                    'Tombol "+" menambah jumlah item, tombol "-" menguranginya (minimal 1, tidak boleh minus)',
                    'Tombol "Hapus" menghilangkan item tersebut sepenuhnya dari keranjang',
                    'Total harga dihitung otomatis dari harga x jumlah seluruh item',
                    'Setiap item di-render dengan key unik berdasarkan id',
                ],
                'test_cases' => [
                    'Klik "+" pada satu item → jumlahnya bertambah, total harga ikut naik',
                    'Klik "-" saat jumlah sudah 1 → jumlah tidak menjadi 0 atau minus',
                    'Klik "Hapus" → item hilang dari daftar dan tidak lagi dihitung di total',
                    'Total harga selalu sesuai dengan penjumlahan harga x jumlah semua item yang tersisa',
                ],
                'language' => 'jsx',
                'starter_code' => <<<'CODE'
import { useState } from 'react';

const initialItems = [
  { id: 1, nama: 'Kaos', harga: 100000, jumlah: 1 },
  { id: 2, nama: 'Celana', harga: 200000, jumlah: 1 },
];

export default function ShoppingCart() {
  const [items, setItems] = useState(initialItems);

  const ubahJumlah = (id, delta) => {
    // TODO 1: update jumlah item dengan id yang cocok, tidak boleh kurang dari 1
  };

  const hapusItem = (id) => {
    // TODO 2: hapus item dengan id yang cocok dari array items
  };

  // TODO 3: hitung total harga seluruh item menggunakan reduce
  const total = 0;

  return (
    <div>
      {items.map((item) => (
        <div key={item.id}>
          <span>{item.nama}</span>
          <button onClick={() => ubahJumlah(item.id, -1)}>-</button>
          <span>{item.jumlah}</span>
          <button onClick={() => ubahJumlah(item.id, 1)}>+</button>
          <button onClick={() => hapusItem(item.id)}>Hapus</button>
        </div>
      ))}
      <p>Total: Rp{total}</p>
    </div>
  );
}
CODE,
                'hint' => 'TODO 1: gunakan Math.max(1, item.jumlah + delta) di dalam map(). TODO 2: items.filter(item => item.id !== id). TODO 3: items.reduce((sum, item) => sum + item.harga * item.jumlah, 0).',
            ],

            'Assignment 5: Component Library' => [
                'title' => 'Latihan: Komponen Button Reusable dengan Props',
                'description' => 'Lengkapi komponen Button supaya fleksibel dipakai ulang dengan variant warna dan ukuran yang berbeda-beda lewat props, tanpa perlu membuat komponen baru untuk tiap kombinasi.',
                'learning_objectives' => [
                    'Merancang props API yang fleksibel namun tetap konsisten antar penggunaan',
                    'Memberi nilai default pada props agar komponen tetap aman dipakai tanpa semua props diisi',
                ],
                'requirements' => [
                    'Button menerima props variant ("primary" | "secondary" | "danger") yang mengubah warna',
                    'Button menerima props size ("small" | "medium" | "large") yang mengubah padding/font-size',
                    'Jika variant atau size tidak diisi, gunakan nilai default yang masuk akal',
                    'Button tetap menerima children sebagai isi teks tombolnya',
                ],
                'test_cases' => [
                    '<Button variant="danger">Hapus</Button> tampil dengan warna berbeda dari default',
                    '<Button size="large">Klik</Button> tampil lebih besar dari ukuran default',
                    '<Button>Simpan</Button> tanpa props tambahan tetap tampil dengan style default yang wajar',
                    'children tetap muncul sebagai teks tombol pada semua kombinasi props',
                ],
                'language' => 'jsx',
                'starter_code' => <<<'CODE'
// TODO 1: beri nilai default untuk variant ("primary") dan size ("medium")
export default function Button({ variant, size, children, onClick }) {
  // TODO 2: tentukan className berdasarkan variant, misal:
  //         primary -> 'btn-primary', secondary -> 'btn-secondary', danger -> 'btn-danger'

  // TODO 3: tentukan className tambahan berdasarkan size, misal:
  //         small -> 'btn-sm', medium -> 'btn-md', large -> 'btn-lg'

  return (
    <button className={/* TODO 4: gabungkan className variant + size di sini */} onClick={onClick}>
      {children}
    </button>
  );
}
CODE,
                'hint' => 'Untuk default props langsung di destructuring: function Button({ variant = "primary", size = "medium", children, onClick }). Untuk gabungan class, cukup template string: `btn-${variant} btn-${size}`.',
            ],

            // ============================================================
            // Modul 4: TypeScript for React
            // ============================================================

            'Assignment 1: Convert JS Project to TS' => [
                'title' => 'Latihan: Beri Tipe pada Fungsi & Interface',
                'description' => 'Kode JavaScript di bawah masih tanpa tipe. Konversikan menjadi TypeScript dengan interface untuk data produk dan tipe parameter/return pada fungsinya.',
                'learning_objectives' => [
                    'Membuat interface untuk merepresentasikan bentuk data',
                    'Memberi tipe pada parameter dan return value fungsi',
                ],
                'requirements' => [
                    'Buat interface Produk dengan properti id (number), nama (string), harga (number)',
                    'Fungsi hitungTotalHarga menerima array Produk[] dan mengembalikan number',
                    'Tidak boleh menggunakan any di manapun pada kode ini',
                ],
                'test_cases' => [
                    'Interface Produk sudah didefinisikan dengan tipe yang tepat untuk tiap properti',
                    'Memanggil hitungTotalHarga dengan array objek yang salah bentuknya (misal harga berupa string) menghasilkan type error dari TypeScript',
                    'Tidak ada penggunaan any di kode akhir',
                ],
                'language' => 'typescript',
                'starter_code' => <<<'CODE'
// Kode asli (JavaScript, tanpa tipe):
//
// function hitungTotalHarga(produkList) {
//   return produkList.reduce((total, p) => total + p.harga, 0);
// }

// TODO 1: buat interface Produk { id: number; nama: string; harga: number }

// TODO 2: beri tipe pada parameter produkList (Produk[]) dan return value (number)
function hitungTotalHarga(produkList) {
  return produkList.reduce((total, p) => total + p.harga, 0);
}
CODE,
                'hint' => 'interface Produk { id: number; nama: string; harga: number } lalu function hitungTotalHarga(produkList: Produk[]): number { ... }.',
            ],

            'Assignment 2: Typed Form Component' => [
                'title' => 'Latihan: Tipe Props & Event Handler Form',
                'description' => 'Lengkapi komponen LoginForm dengan tipe TypeScript untuk props, state, dan event handler perubahan input maupun submit form.',
                'learning_objectives' => [
                    'Membuat interface untuk props komponen form',
                    'Memberi tipe pada event handler menggunakan React.ChangeEvent dan React.FormEvent',
                ],
                'requirements' => [
                    'Buat interface LoginFormProps dengan onSubmit: (email: string, password: string) => void',
                    'Event handler perubahan input harus bertipe React.ChangeEvent<HTMLInputElement>',
                    'Event handler submit form harus bertipe React.FormEvent<HTMLFormElement>, dan memanggil event.preventDefault()',
                ],
                'test_cases' => [
                    'Komponen menolak (type error) jika dipakai tanpa prop onSubmit',
                    'handleChange menerima event dengan tipe yang benar tanpa perlu any',
                    'Submit form tidak me-reload halaman (preventDefault terpanggil)',
                ],
                'language' => 'typescript',
                'starter_code' => <<<'CODE'
import { useState } from 'react';

// TODO 1: buat interface LoginFormProps dengan onSubmit(email, password)

export default function LoginForm(props /* TODO 2: beri tipe LoginFormProps */) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  // TODO 3: beri tipe pada event handleEmailChange (React.ChangeEvent<HTMLInputElement>)
  const handleEmailChange = (e) => setEmail(e.target.value);

  // TODO 4: beri tipe pada event handleSubmit (React.FormEvent<HTMLFormElement>)
  //         jangan lupa panggil e.preventDefault()
  const handleSubmit = (e) => {
    props.onSubmit(email, password);
  };

  return (
    <form onSubmit={handleSubmit}>
      <input value={email} onChange={handleEmailChange} />
      <input type="password" value={password} onChange={(e) => setPassword(e.target.value)} />
      <button type="submit">Masuk</button>
    </form>
  );
}
CODE,
                'hint' => 'interface LoginFormProps { onSubmit: (email: string, password: string) => void }. Untuk event: (e: React.ChangeEvent<HTMLInputElement>) => ... dan (e: React.FormEvent<HTMLFormElement>) => { e.preventDefault(); ... }.',
            ],

            'Assignment 3: Typed API Client' => [
                'title' => 'Latihan: Fungsi Fetch Generic dengan TypeScript',
                'description' => 'Lengkapi fungsi getData supaya bisa dipakai untuk mengambil berbagai jenis data (user, produk, dll.) dengan tipe response yang aman menggunakan generics, alih-alih any.',
                'learning_objectives' => [
                    'Menggunakan generics pada fungsi agar fleksibel namun tetap type-safe',
                    'Menangani error fetch dengan tipe yang jelas, bukan any',
                ],
                'requirements' => [
                    'Fungsi getData<T> menerima url: string dan mengembalikan Promise<T>',
                    'Saat dipanggil, caller bisa menentukan tipe response, misal getData<Produk[]>(url)',
                    'Tidak ada penggunaan any pada fungsi ini',
                    'Error fetch ditangani dengan try...catch dan di-throw ulang dengan pesan yang jelas',
                ],
                'test_cases' => [
                    'getData<Produk[]>(url) mengembalikan tipe Produk[], bukan any, sehingga autocomplete properti produk berfungsi',
                    'Memanggil getData tanpa generic tetap bisa jalan (fallback ke unknown atau tipe default)',
                    'Kegagalan fetch (misal status bukan 200) menghasilkan error yang jelas, bukan silent fail',
                ],
                'language' => 'typescript',
                'starter_code' => <<<'CODE'
// TODO 1: ubah fungsi ini menjadi generic getData<T>(url: string): Promise<T>
async function getData(url) {
  try {
    const res = await fetch(url);
    if (!res.ok) {
      // TODO 2: throw error dengan pesan yang jelas, misal status response
    }
    return await res.json();
  } catch (err) {
    // TODO 3: throw ulang error agar caller bisa menanganinya (jangan ditelan diam-diam)
  }
}

// Contoh pemanggilan setelah generic diterapkan:
// interface Produk { id: number; nama: string; harga: number }
// const produkList = await getData<Produk[]>('/api/produk');
CODE,
                'hint' => 'Signature akhirnya: async function getData<T>(url: string): Promise<T> { ... return res.json() as Promise<T>; }. Untuk TODO 2/3, cukup throw new Error(`Request gagal: ${res.status}`) dan biarkan catch melempar ulang (throw err;).',
            ],

            // ============================================================
            // Modul 5: Advanced React Patterns
            // ============================================================

            'Assignment 1: Custom Hook Library' => [
                'title' => 'Latihan: Buat Custom Hook useLocalStorage',
                'description' => 'Lengkapi custom hook useLocalStorage supaya perilakunya persis seperti useState, tapi nilainya otomatis tersimpan dan dibaca dari localStorage.',
                'learning_objectives' => [
                    'Membangun custom hook yang membungkus useState dan useEffect',
                    'Menerapkan prinsip DRY dengan memindahkan logic localStorage keluar dari komponen',
                ],
                'requirements' => [
                    'Hook menerima key (string) dan initialValue, mengembalikan [value, setValue] seperti useState',
                    'Saat pertama kali dipanggil, hook membaca nilai dari localStorage jika ada, kalau tidak pakai initialValue',
                    'Setiap kali value berubah, hook menyimpan nilai terbaru ke localStorage secara otomatis',
                ],
                'test_cases' => [
                    'Refresh halaman setelah value diubah → value yang tersimpan tetap muncul, tidak kembali ke initialValue',
                    'Key berbeda pada localStorage tidak saling menimpa antar penggunaan hook',
                    'Hook bisa dipakai persis seperti useState di komponen manapun',
                ],
                'language' => 'jsx',
                'starter_code' => <<<'CODE'
import { useState, useEffect } from 'react';

function useLocalStorage(key, initialValue) {
  const [value, setValue] = useState(() => {
    // TODO 1: coba baca dari localStorage.getItem(key)
    //         kalau ada, parse JSON-nya; kalau tidak, pakai initialValue
  });

  useEffect(() => {
    // TODO 2: setiap kali value berubah, simpan ke localStorage
    //         dengan JSON.stringify(value)
  }, [key, value]);

  return [value, setValue];
}

export default useLocalStorage;
CODE,
                'hint' => 'TODO 1: const saved = localStorage.getItem(key); return saved ? JSON.parse(saved) : initialValue; TODO 2: localStorage.setItem(key, JSON.stringify(value));',
            ],

            'Assignment 2: State Management App' => [
                'title' => 'Latihan: useReducer untuk Keranjang Belanja',
                'description' => 'Lengkapi reducer cartReducer supaya bisa menangani tiga aksi berbeda pada keranjang belanja: menambah item, menghapus item, dan mengosongkan keranjang.',
                'learning_objectives' => [
                    'Menerapkan useReducer untuk mengelola state dengan banyak jenis aksi',
                    'Menyusun action dengan type dan payload yang konsisten',
                ],
                'requirements' => [
                    'Aksi ADD_ITEM menambahkan item baru ke array items pada state',
                    'Aksi REMOVE_ITEM menghapus item berdasarkan id dari payload',
                    'Aksi CLEAR_CART mengosongkan seluruh items menjadi array kosong',
                    'Reducer harus pure function — tidak boleh memutasi state langsung',
                ],
                'test_cases' => [
                    'dispatch ADD_ITEM dua kali dengan item berbeda → state.items berisi kedua item tersebut',
                    'dispatch REMOVE_ITEM dengan id tertentu → hanya item dengan id itu yang hilang',
                    'dispatch CLEAR_CART → state.items menjadi array kosong terlepas dari isi sebelumnya',
                    'State lama tidak dimutasi langsung (reducer selalu return objek/array baru)',
                ],
                'language' => 'jsx',
                'starter_code' => <<<'CODE'
const initialState = { items: [] };

function cartReducer(state, action) {
  switch (action.type) {
    case 'ADD_ITEM':
      // TODO 1: return state baru dengan action.payload ditambahkan ke items

    case 'REMOVE_ITEM':
      // TODO 2: return state baru tanpa item yang id-nya sama dengan action.payload

    case 'CLEAR_CART':
      // TODO 3: return state baru dengan items kosong

    default:
      return state;
  }
}

export { cartReducer, initialState };
CODE,
                'hint' => 'TODO 1: return { ...state, items: [...state.items, action.payload] }; TODO 2: return { ...state, items: state.items.filter(i => i.id !== action.payload) }; TODO 3: return { ...state, items: [] };',
            ],

            'Assignment 3: Performance Demo' => [
                'title' => 'Latihan: Optimasi Filter List dengan useMemo',
                'description' => 'Komponen ProductList di bawah melakukan filtering ulang setiap kali komponen re-render, meskipun daftar produk dan keyword tidak berubah. Optimalkan dengan useMemo agar filtering hanya dijalankan saat benar-benar diperlukan.',
                'learning_objectives' => [
                    'Menggunakan useMemo untuk menghindari kalkulasi ulang yang tidak perlu',
                    'Mengidentifikasi dependency yang tepat agar hasil memo tetap benar',
                ],
                'requirements' => [
                    'Hasil filter produk (berdasarkan keyword) dibungkus dengan useMemo',
                    'Dependency array useMemo harus mencakup products dan keyword saja',
                    'Filtering tidak boleh dijalankan ulang ketika state lain (misal darkMode) berubah tanpa mengubah products/keyword',
                ],
                'test_cases' => [
                    'Toggle state yang tidak terkait (misal darkMode) tidak memicu filtering ulang (bisa dicek lewat console.log di dalam filter)',
                    'Mengubah keyword tetap menghasilkan daftar produk yang ter-filter dengan benar',
                    'Mengubah products (misal menambah produk baru) tetap memicu filtering ulang seperti seharusnya',
                ],
                'language' => 'jsx',
                'starter_code' => <<<'CODE'
import { useState, useMemo } from 'react';

export default function ProductList({ products }) {
  const [keyword, setKeyword] = useState('');
  const [darkMode, setDarkMode] = useState(false);

  // TODO 1: bungkus filtering ini dengan useMemo, dengan dependency
  //         [products, keyword] agar tidak dihitung ulang saat darkMode berubah
  const filteredProducts = products.filter((p) => {
    console.log('filtering...'); // untuk memverifikasi apakah filter jalan ulang
    return p.nama.toLowerCase().includes(keyword.toLowerCase());
  });

  return (
    <div>
      <input value={keyword} onChange={(e) => setKeyword(e.target.value)} />
      <button onClick={() => setDarkMode(!darkMode)}>Toggle Dark Mode</button>
      <ul>
        {filteredProducts.map((p) => <li key={p.id}>{p.nama}</li>)}
      </ul>
    </div>
  );
}
CODE,
                'hint' => 'Bungkus jadi: const filteredProducts = useMemo(() => { ...logic filter... return hasil; }, [products, keyword]); — pastikan darkMode TIDAK ada di dependency array.',
            ],

            'Assignment 4: Testing Suite' => [
                'title' => 'Latihan: Unit Test Komponen Counter dengan RTL',
                'description' => 'Lengkapi test untuk komponen Counter menggunakan React Testing Library, dengan menguji interaksi klik tombol dari sudut pandang pengguna, bukan mengecek state internal secara langsung.',
                'learning_objectives' => [
                    'Menulis test menggunakan render, screen, dan fireEvent/userEvent dari React Testing Library',
                    'Menguji hasil yang terlihat pengguna (teks di layar), bukan implementasi internal komponen',
                ],
                'requirements' => [
                    'Test pertama memverifikasi angka awal counter adalah 0 saat pertama dirender',
                    'Test kedua mengklik tombol "+" dan memverifikasi angka berubah menjadi 1',
                    'Test ketiga mengklik tombol "-" saat angka 0 dan memverifikasi angka tidak menjadi negatif',
                    'Gunakan screen.getByText / screen.getByRole, bukan mengakses internal state komponen',
                ],
                'test_cases' => [
                    'Render awal Counter menampilkan teks "0"',
                    'Setelah klik tombol "+" satu kali, teks berubah menjadi "1"',
                    'Klik tombol "-" saat counter di angka 0 tidak membuat angka menjadi "-1"',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
import { render, screen, fireEvent } from '@testing-library/react';
import Counter from './Counter';

describe('Counter', () => {
  test('menampilkan angka awal 0', () => {
    render(<Counter />);
    // TODO 1: assert bahwa teks "0" muncul di layar
  });

  test('bertambah saat tombol + diklik', () => {
    render(<Counter />);
    // TODO 2: cari tombol "+" dengan screen.getByRole/getByText,
    //         klik dengan fireEvent.click, lalu assert teks berubah jadi "1"
  });

  test('tidak boleh minus saat tombol - diklik di angka 0', () => {
    render(<Counter />);
    // TODO 3: klik tombol "-" satu kali, lalu assert teks tetap "0", bukan "-1"
  });
});
CODE,
                'hint' => 'Pola umumnya: expect(screen.getByText("0")).toBeInTheDocument(); lalu fireEvent.click(screen.getByRole("button", { name: "+" })); expect(screen.getByText("1")).toBeInTheDocument();',
            ],
        ];
    }
}