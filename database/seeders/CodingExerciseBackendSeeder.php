<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\CodingExercise;
use Illuminate\Database\Seeder;

class CodingExerciseBackendSeeder extends Seeder
{
    /**
     * Coding exercise untuk seluruh assignment teknis di career
     * Backend Developer, mengikuti pola per-career yang sama seperti
     * CodingExerciseFullStackSeeder.
     *
     * Modul 5 (Git & Collaboration Workflow: "Team Branching Simulation"
     * dan "Pull Request Practice Repo") sengaja TIDAK dibuatkan coding
     * exercise — keduanya latihan alur kerja Git/PR, bukan menulis kode
     * yang cocok untuk format "lengkapi starter code".
     *
     * Jalankan setelah LearningPathSeeder, AddAssignmentsToExistingModulesSeeder,
     * dan AssignmentDetailSeeder (assignment-nya harus sudah ada). Idempotent
     * lewat updateOrCreate berdasarkan assignment_id.
     *
     * Jalankan:
     *   php artisan db:seed --class=CodingExerciseBackendSeeder
     */
    public function run(): void
    {
        foreach ($this->exerciseData() as $assignmentTitle => $data) {
            $assignment = Assignment::where('title', $assignmentTitle)->first();

            if (! $assignment) {
                $this->command?->warn("CodingExerciseBackendSeeder: assignment tidak ditemukan — {$assignmentTitle}");
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
            // Modul 1: Node.js Fundamentals
            // ============================================================

            'Assignment 1: Static File Server with Node.js' => [
                'title' => 'Latihan: Serve File Statis Tanpa Framework',
                'description' => 'Lengkapi server HTTP di bawah supaya bisa membaca dan mengirim isi file statis (misal index.html) menggunakan modul bawaan Node.js, lengkap dengan penanganan file yang tidak ditemukan.',
                'learning_objectives' => [
                    'Menggunakan modul http untuk membuat server tanpa framework',
                    'Melakukan pembacaan file secara asynchronous dengan fs dan menangani error ENOENT',
                ],
                'requirements' => [
                    'Gunakan fs.readFile (asynchronous), bukan fs.readFileSync',
                    'Kalau file ditemukan, kirim isinya dengan status 200 dan Content-Type yang sesuai',
                    'Kalau file tidak ditemukan, kirim response 404 dengan pesan yang jelas',
                    'Gunakan path.join untuk menggabungkan path folder public dengan nama file yang diminta',
                ],
                'test_cases' => [
                    'Request ke file yang ada (misal /index.html) mengembalikan status 200 dan isi file',
                    'Request ke file yang tidak ada mengembalikan status 404, bukan server crash',
                    'Server tidak throw unhandled error ketika path file salah',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
const http = require('http');
const fs = require('fs');
const path = require('path');

const server = http.createServer((req, res) => {
  const filePath = path.join(__dirname, 'public', req.url === '/' ? 'index.html' : req.url);

  // TODO 1: baca file di filePath secara asynchronous dengan fs.readFile

  // TODO 2: kalau ada error (file tidak ditemukan), kirim response 404

  // TODO 3: kalau berhasil, kirim isi file dengan status 200
});

server.listen(3000, () => console.log('Server berjalan di port 3000'));
CODE,
                'hint' => 'Pola dasar: fs.readFile(filePath, (err, data) => { if (err) { res.writeHead(404); res.end("File tidak ditemukan"); return; } res.writeHead(200); res.end(data); });',
            ],

            'Assignment 2: Async Task Queue Simulator' => [
                'title' => 'Latihan: Simulasi Urutan Eksekusi Event Loop',
                'description' => 'Lengkapi kode di bawah supaya urutan log yang tercetak sesuai dengan cara kerja Event Loop Node.js: kode synchronous dulu, baru microtask (Promise), baru macrotask (setTimeout).',
                'learning_objectives' => [
                    'Memahami urutan eksekusi synchronous code, microtask, dan macrotask',
                    'Menggunakan setTimeout dan Promise untuk mensimulasikan antrean tugas asynchronous',
                ],
                'requirements' => [
                    'Log "Mulai" dan "Selesai" harus tercetak duluan karena synchronous',
                    'Log dari Promise.resolve().then() harus tercetak sebelum log dari setTimeout, meskipun setTimeout delay-nya 0ms',
                    'Tidak boleh menggunakan async/await untuk latihan ini — gunakan .then() langsung agar urutan microtask/macrotask terlihat jelas',
                ],
                'test_cases' => [
                    'Urutan output akhir: Mulai, Selesai, Microtask (Promise), Macrotask (setTimeout)',
                    'Mengubah delay setTimeout menjadi 100d tetap tidak membuat macrotask tercetak sebelum microtask',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
console.log('Mulai');

// TODO 1: tambahkan setTimeout dengan delay 0ms yang mencetak
//         "Macrotask (setTimeout)"

// TODO 2: tambahkan Promise.resolve().then() yang mencetak
//         "Microtask (Promise)"

console.log('Selesai');

// Output yang diharapkan:
// Mulai
// Selesai
// Microtask (Promise)
// Macrotask (setTimeout)
CODE,
                'hint' => 'setTimeout(() => console.log("Macrotask (setTimeout)"), 0); dan Promise.resolve().then(() => console.log("Microtask (Promise)")); — microtask queue selalu dikosongkan dulu sebelum macrotask berikutnya diproses.',
            ],

            // ============================================================
            // Modul 2: Membangun REST API
            // ============================================================

            'Assignment 1: Task Manager REST API' => [
                'title' => 'Latihan: Lengkapi Endpoint CRUD Task',
                'description' => 'Lengkapi endpoint Express di bawah supaya mendukung operasi CRUD penuh (GET, POST, PUT, DELETE) untuk resource task, dengan status code yang sesuai konvensi REST.',
                'learning_objectives' => [
                    'Merancang route REST yang konsisten untuk satu resource',
                    'Menggunakan status code HTTP yang tepat (200, 201, 404) sesuai hasil operasi',
                ],
                'requirements' => [
                    'GET /tasks mengembalikan seluruh task dengan status 200',
                    'POST /tasks menambahkan task baru dan mengembalikan status 201 beserta data yang dibuat',
                    'PUT /tasks/:id mengupdate task, mengembalikan 404 jika id tidak ditemukan',
                    'DELETE /tasks/:id menghapus task, mengembalikan 404 jika id tidak ditemukan',
                ],
                'test_cases' => [
                    'GET /tasks pada awal mula mengembalikan array (boleh kosong) dengan status 200',
                    'POST /tasks dengan body valid → task baru muncul di GET /tasks berikutnya, status 201',
                    'PUT /tasks/999 (id tidak ada) → status 404, bukan 200',
                    'DELETE /tasks/:id yang valid → task tersebut tidak lagi muncul di GET /tasks',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
const express = require('express');
const app = express();
app.use(express.json());

let tasks = [];
let nextId = 1;

app.get('/tasks', (req, res) => {
  // TODO 1: kembalikan seluruh tasks dengan status 200
});

app.post('/tasks', (req, res) => {
  // TODO 2: buat task baru dari req.body, simpan ke array tasks,
  //         kembalikan task yang dibuat dengan status 201
});

app.put('/tasks/:id', (req, res) => {
  // TODO 3: cari task berdasarkan id dari req.params
  //         kalau tidak ditemukan, kembalikan status 404
  //         kalau ditemukan, update datanya dan kembalikan status 200
});

app.delete('/tasks/:id', (req, res) => {
  // TODO 4: hapus task berdasarkan id, kalau tidak ditemukan kembalikan 404
});

module.exports = app;
CODE,
                'hint' => 'Untuk cari index: tasks.findIndex(t => t.id === Number(req.params.id)). Kalau -1, res.status(404).json({ message: "Task tidak ditemukan" }); return;',
            ],

            'Assignment 2: Book Catalog API' => [
                'title' => 'Latihan: Validasi Input pada Endpoint Buku',
                'description' => 'Lengkapi endpoint POST /books di bawah supaya menolak data yang tidak valid (judul kosong atau tahun bukan angka) dengan pesan error yang jelas, sebelum data disimpan.',
                'learning_objectives' => [
                    'Menggunakan req.body untuk validasi input sebelum diproses',
                    'Mengembalikan status code dan pesan error yang informatif untuk request yang tidak valid',
                ],
                'requirements' => [
                    'Judul buku wajib diisi (tidak boleh string kosong)',
                    'Tahun terbit wajib berupa angka, bukan string',
                    'Jika validasi gagal, kembalikan status 400 dengan pesan yang menjelaskan field mana yang salah',
                    'Jika valid, simpan buku dan kembalikan status 201',
                ],
                'test_cases' => [
                    'POST tanpa judul → status 400 dengan pesan menyebutkan judul wajib diisi',
                    'POST dengan tahun berupa string "duaribu" → status 400',
                    'POST dengan data lengkap dan valid → status 201, buku tersimpan',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
const express = require('express');
const app = express();
app.use(express.json());

let books = [];

app.post('/books', (req, res) => {
  const { judul, tahun } = req.body;

  // TODO 1: validasi judul — tolak jika kosong/undefined, status 400

  // TODO 2: validasi tahun — tolak jika bukan angka, status 400

  // TODO 3: kalau valid, simpan buku ke array books, kembalikan status 201
});

module.exports = app;
CODE,
                'hint' => 'if (!judul || judul.trim() === "") return res.status(400).json({ message: "Judul wajib diisi" }); if (typeof tahun !== "number") return res.status(400).json({ message: "Tahun harus berupa angka" });',
            ],

            'Assignment 3: API Structure Refactor' => [
                'title' => 'Latihan: Pisahkan Route dari Business Logic',
                'description' => 'Kode di bawah masih menaruh seluruh logic (query data, validasi, response) langsung di dalam route handler. Pisahkan menjadi controller dan service agar tiap lapisan punya satu tanggung jawab.',
                'learning_objectives' => [
                    'Memisahkan route (Express) dari controller dan service',
                    'Menjaga service tidak bergantung pada req/res dari Express',
                ],
                'requirements' => [
                    'Buat productService.js yang berisi fungsi getAllProducts() — tidak boleh mengakses req/res sama sekali',
                    'Buat productController.js yang memanggil service, lalu menyusun response HTTP',
                    'productRoutes.js hanya berisi definisi route yang mengarah ke controller, tanpa logic apapun',
                ],
                'test_cases' => [
                    'Endpoint GET /products tetap mengembalikan data yang sama seperti sebelum refactor',
                    'productService.js tidak mengimpor apapun dari express',
                    'productRoutes.js tidak memiliki logic pengolahan data, hanya pemanggilan controller',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
// Kode lama (semua logic menumpuk di satu file route):
//
// app.get('/products', (req, res) => {
//   const products = db.query('SELECT * FROM products');
//   const hasil = products.map(p => ({ id: p.id, nama: p.nama, harga: p.harga }));
//   res.status(200).json(hasil);
// });

// TODO 1: buat productService.js dengan fungsi getAllProducts()
//         yang melakukan query & transform data, TANPA req/res

// TODO 2: buat productController.js dengan fungsi getProducts(req, res)
//         yang memanggil productService.getAllProducts() lalu res.json(...)

// TODO 3: buat productRoutes.js yang cuma berisi:
//         router.get('/products', productController.getProducts);
CODE,
                'hint' => 'Service: exports.getAllProducts = () => { const products = db.query(...); return products.map(...); }; Controller cukup memanggil service dan membungkus hasilnya jadi response HTTP.',
            ],

            // ============================================================
            // Modul 3: Database SQL & NoSQL
            // ============================================================

            'Assignment 1: Library Database Schema Design' => [
                'title' => 'Latihan: Tulis DDL untuk Skema Perpustakaan',
                'description' => 'Lengkapi statement CREATE TABLE di bawah untuk membuat skema perpustakaan (buku, anggota, peminjaman) dengan relasi foreign key yang benar dan ternormalisasi.',
                'learning_objectives' => [
                    'Menulis DDL (CREATE TABLE) dengan tipe data dan constraint yang sesuai',
                    'Menentukan foreign key yang tepat agar relasi antar tabel konsisten',
                ],
                'requirements' => [
                    'Tabel books, members, dan loans masing-masing punya primary key sendiri',
                    'Tabel loans punya foreign key ke books dan members (bukan menyalin data buku/anggota langsung)',
                    'Kolom yang wajib diisi (misal judul buku, nama anggota) diberi constraint NOT NULL',
                ],
                'test_cases' => [
                    'CREATE TABLE loans gagal dijalankan jika foreign key mengarah ke tabel yang belum ada — urutan pembuatan tabel harus benar',
                    'Insert data ke loans dengan book_id/member_id yang tidak ada di tabel induk akan gagal karena foreign key constraint',
                    'Insert buku tanpa judul (NULL) gagal karena constraint NOT NULL',
                ],
                'language' => 'sql',
                'starter_code' => <<<'CODE'
-- TODO 1: lengkapi tabel books dengan primary key id dan kolom judul (NOT NULL)
CREATE TABLE books (
  id INT AUTO_INCREMENT,
  -- tambahkan kolom judul, penulis, stok di sini
  PRIMARY KEY (id)
);

-- TODO 2: lengkapi tabel members dengan primary key id dan kolom nama (NOT NULL)
CREATE TABLE members (
  id INT AUTO_INCREMENT,
  -- tambahkan kolom nama, email di sini
  PRIMARY KEY (id)
);

-- TODO 3: lengkapi tabel loans dengan foreign key ke books dan members
CREATE TABLE loans (
  id INT AUTO_INCREMENT,
  book_id INT NOT NULL,
  member_id INT NOT NULL,
  tanggal_pinjam DATE NOT NULL,
  PRIMARY KEY (id)
  -- tambahkan FOREIGN KEY (book_id) REFERENCES books(id) di sini
  -- tambahkan FOREIGN KEY (member_id) REFERENCES members(id) di sini
);
CODE,
                'hint' => 'Tambahkan di akhir CREATE TABLE loans: FOREIGN KEY (book_id) REFERENCES books(id), FOREIGN KEY (member_id) REFERENCES members(id). Urutan pembuatan tabel harus books & members dulu, baru loans.',
            ],

            'Assignment 2: Blog CRUD with MySQL' => [
                'title' => 'Latihan: Query CRUD Aman untuk Blog',
                'description' => 'Lengkapi query SQL di bawah untuk operasi CRUD pada tabel posts, dan pastikan query UPDATE/DELETE selalu punya klausa WHERE agar tidak mengubah seluruh baris tabel secara tidak sengaja.',
                'learning_objectives' => [
                    'Menulis query INSERT, SELECT, UPDATE, DELETE yang tepat',
                    'Menghindari kesalahan umum: lupa WHERE pada UPDATE/DELETE',
                ],
                'requirements' => [
                    'INSERT menambahkan post baru dengan judul dan isi',
                    'SELECT mengambil post berdasarkan id tertentu, bukan seluruh tabel',
                    'UPDATE mengubah judul post HANYA untuk id tertentu (wajib ada WHERE id = ...)',
                    'DELETE menghapus post HANYA untuk id tertentu (wajib ada WHERE id = ...)',
                ],
                'test_cases' => [
                    'Setelah INSERT, SELECT dengan id yang baru dibuat mengembalikan data yang sesuai',
                    'UPDATE dengan WHERE id = 5 hanya mengubah baris dengan id 5, baris lain tetap sama',
                    'DELETE tanpa WHERE dianggap SALAH — query yang dibuat wajib menyertakan WHERE id = ...',
                ],
                'language' => 'sql',
                'starter_code' => <<<'CODE'
-- TODO 1: insert post baru dengan judul 'Halo Dunia' dan isi 'Post pertama saya'
INSERT INTO posts (judul, isi) VALUES (/* lengkapi di sini */);

-- TODO 2: ambil satu post berdasarkan id = 1
SELECT * FROM posts WHERE /* lengkapi kondisi di sini */;

-- TODO 3: update judul post dengan id = 1 menjadi 'Judul Baru'
--         JANGAN LUPA klausa WHERE, atau seluruh baris akan ikut berubah
UPDATE posts SET judul = 'Judul Baru' /* tambahkan WHERE di sini */;

-- TODO 4: hapus post dengan id = 1
--         JANGAN LUPA klausa WHERE, atau seluruh tabel akan ikut terhapus
DELETE FROM posts /* tambahkan WHERE di sini */;
CODE,
                'hint' => 'Pola aman untuk UPDATE/DELETE: selalu akhiri dengan WHERE id = <nilai_spesifik>. Lupa WHERE pada UPDATE/DELETE adalah salah satu kesalahan paling umum dan berbahaya di SQL.',
            ],

            'Assignment 3: Product Catalog with MongoDB' => [
                'title' => 'Latihan: CRUD Document Produk dengan MongoDB',
                'description' => 'Lengkapi operasi CRUD di bawah menggunakan MongoDB driver, dengan struktur document yang fleksibel untuk produk yang atributnya bisa berbeda-beda antar kategori.',
                'learning_objectives' => [
                    'Melakukan insertOne, find, updateOne, dan deleteOne pada koleksi MongoDB',
                    'Merancang struktur document yang fleksibel untuk atribut produk yang bervariasi',
                ],
                'requirements' => [
                    'insertOne menyimpan produk dengan field nama, harga, dan atribut tambahan sesuai kategori (misal warna untuk baju, RAM untuk laptop)',
                    'find mengambil produk berdasarkan kategori tertentu',
                    'updateOne mengubah harga produk berdasarkan _id, menggunakan operator $set',
                    'deleteOne menghapus produk berdasarkan _id',
                ],
                'test_cases' => [
                    'Produk kategori berbeda bisa punya field atribut yang berbeda tanpa error skema',
                    'find({ kategori: "baju" }) hanya mengembalikan produk dengan kategori itu',
                    'updateOne mengubah harga tanpa menghapus field lain pada document yang sama',
                    'deleteOne hanya menghapus satu document yang cocok dengan _id',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
async function tambahProduk(db) {
  // TODO 1: insertOne ke koleksi 'products' dengan document
  //         { nama: 'Kaos Polos', harga: 100000, kategori: 'baju', warna: 'putih' }
}

async function cariProdukByKategori(db, kategori) {
  // TODO 2: gunakan find({ kategori }) lalu toArray() untuk mengembalikan hasilnya
}

async function updateHargaProduk(db, produkId, hargaBaru) {
  // TODO 3: gunakan updateOne dengan filter _id dan operator $set untuk harga
}

async function hapusProduk(db, produkId) {
  // TODO 4: gunakan deleteOne dengan filter _id
}

module.exports = { tambahProduk, cariProdukByKategori, updateHargaProduk, hapusProduk };
CODE,
                'hint' => 'updateOne({ _id: produkId }, { $set: { harga: hargaBaru } }) — operator $set penting supaya field lain di document tidak ikut terhapus/tertimpa.',
            ],

            // ============================================================
            // Modul 4: Authentication & Security
            // ============================================================

            'Assignment 1: JWT Authentication System' => [
                'title' => 'Latihan: Hash Password & Buat JWT saat Login',
                'description' => 'Lengkapi fungsi register dan login di bawah supaya password di-hash dengan bcrypt sebelum disimpan, dan token JWT dibuat serta diverifikasi dengan benar saat login.',
                'learning_objectives' => [
                    'Melakukan hashing password dengan bcrypt sebelum menyimpannya',
                    'Membuat dan memverifikasi JWT menggunakan secret key dari environment variable',
                ],
                'requirements' => [
                    'Password di-hash dengan bcrypt.hash sebelum disimpan ke "database"',
                    'Saat login, password yang diinput dibandingkan dengan hash menggunakan bcrypt.compare, bukan perbandingan string biasa',
                    'JWT dibuat dengan jwt.sign, menyertakan userId di payload dan expiresIn yang wajar (misal 1h)',
                    'Secret key JWT diambil dari process.env, bukan ditulis langsung di kode',
                ],
                'test_cases' => [
                    'Password yang disimpan di "database" bukan plain text, melainkan hash bcrypt',
                    'Login dengan password yang benar menghasilkan token JWT',
                    'Login dengan password yang salah ditolak (bcrypt.compare mengembalikan false)',
                    'Token JWT yang dihasilkan bisa diverifikasi ulang dengan jwt.verify tanpa error',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');

let users = []; // { id, email, passwordHash }

async function register(email, password) {
  // TODO 1: hash password dengan bcrypt.hash (saltRounds = 10)
  //         lalu simpan user baru ke array users
}

async function login(email, password) {
  const user = users.find(u => u.email === email);
  if (!user) return null;

  // TODO 2: bandingkan password dengan user.passwordHash menggunakan bcrypt.compare
  //         kalau tidak cocok, return null

  // TODO 3: kalau cocok, buat JWT dengan payload { userId: user.id }
  //         menggunakan process.env.JWT_SECRET, expiresIn '1h'
  //         lalu return token-nya
}

module.exports = { register, login };
CODE,
                'hint' => 'const passwordHash = await bcrypt.hash(password, 10); ... const cocok = await bcrypt.compare(password, user.passwordHash); ... jwt.sign({ userId: user.id }, process.env.JWT_SECRET, { expiresIn: "1h" });',
            ],

            'Assignment 2: Secure Login API' => [
                'title' => 'Latihan: Middleware Proteksi Endpoint',
                'description' => 'Lengkapi middleware authMiddleware supaya hanya request dengan token JWT valid yang bisa mengakses endpoint yang dilindungi, dan request tanpa token/invalid ditolak dengan status 401.',
                'learning_objectives' => [
                    'Membangun middleware Express untuk memverifikasi JWT dari header Authorization',
                    'Mengembalikan response 401 yang konsisten untuk request yang tidak terautentikasi',
                ],
                'requirements' => [
                    'Middleware membaca token dari header Authorization (format "Bearer <token>")',
                    'Kalau token tidak ada, kembalikan status 401 dengan pesan yang jelas',
                    'Kalau token ada tapi tidak valid/expired, kembalikan status 401, bukan 500',
                    'Kalau token valid, simpan payload user ke req.user dan lanjutkan ke handler berikutnya (next())',
                ],
                'test_cases' => [
                    'Request tanpa header Authorization → status 401',
                    'Request dengan token yang salah/expired → status 401, bukan server error',
                    'Request dengan token valid → req.user terisi dan handler berikutnya terpanggil',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
const jwt = require('jsonwebtoken');

function authMiddleware(req, res, next) {
  const authHeader = req.headers['authorization'];

  // TODO 1: kalau tidak ada header authorization, kembalikan status 401

  // TODO 2: ambil token dari format "Bearer <token>"

  // TODO 3: verifikasi token dengan jwt.verify, bungkus dalam try...catch
  //         kalau gagal (invalid/expired), kembalikan status 401

  // TODO 4: kalau valid, simpan payload ke req.user, panggil next()
}

module.exports = authMiddleware;
CODE,
                'hint' => 'const token = authHeader && authHeader.split(" ")[1]; try { req.user = jwt.verify(token, process.env.JWT_SECRET); next(); } catch (err) { res.status(401).json({ message: "Token tidak valid" }); }',
            ],

            'Assignment 3: Rate-Limited Login Endpoint' => [
                'title' => 'Latihan: Batasi Percobaan Login dengan Rate Limiting',
                'description' => 'Lengkapi konfigurasi rate limiter di bawah supaya endpoint /login hanya menerima maksimal 5 percobaan dalam 15 menit per IP, dengan pesan error yang informatif saat limit tercapai.',
                'learning_objectives' => [
                    'Menerapkan express-rate-limit untuk melindungi endpoint dari brute force',
                    'Mengembalikan status code dan pesan yang sesuai (429) saat limit tercapai',
                ],
                'requirements' => [
                    'Maksimal 5 request per 15 menit per IP untuk endpoint /login',
                    'Saat limit tercapai, response harus berstatus 429 dengan pesan yang menyebutkan waktu tunggu',
                    'Rate limiter hanya diterapkan pada endpoint /login, bukan seluruh aplikasi',
                ],
                'test_cases' => [
                    'Request ke /login sebanyak 5 kali dalam window waktu masih diproses normal',
                    'Request ke-6 dalam window yang sama menghasilkan status 429',
                    'Endpoint lain (misal /register) tidak terkena limit yang sama',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
const rateLimit = require('express-rate-limit');
const express = require('express');
const app = express();

// TODO 1: buat rate limiter dengan windowMs 15 menit dan max 5 request
//         serta pesan error yang jelas saat limit tercapai
const loginLimiter = rateLimit({
  // lengkapi konfigurasi di sini
});

app.post('/login', loginLimiter, (req, res) => {
  // logic login di sini (di luar scope latihan ini)
  res.json({ message: 'Login diproses' });
});

module.exports = app;
CODE,
                'hint' => 'rateLimit({ windowMs: 15 * 60 * 1000, max: 5, message: { message: "Terlalu banyak percobaan login, coba lagi dalam 15 menit" } }) — jangan pasang app.use(loginLimiter) secara global, cukup di route /login saja.',
            ],

            // ============================================================
            // Modul 6: Testing & Debugging Backend
            // ============================================================

            'Assignment 1: Unit Test Suite for Utility Functions' => [
                'title' => 'Latihan: Unit Test Fungsi Diskon dengan Jest',
                'description' => 'Lengkapi test untuk fungsi hitungHargaSetelahDiskon menggunakan Jest, termasuk skenario edge case seperti diskon 0% dan diskon 100%.',
                'learning_objectives' => [
                    'Menulis unit test dengan pola arrange-act-assert menggunakan Jest',
                    'Mencakup edge case, bukan hanya kasus normal',
                ],
                'requirements' => [
                    'Test kasus normal: diskon 10% dari harga 100000 menghasilkan 90000',
                    'Test edge case: diskon 0% mengembalikan harga yang sama persis',
                    'Test edge case: diskon 100% mengembalikan harga 0',
                    'Semua test menggunakan expect().toBe(), bukan console.log manual',
                ],
                'test_cases' => [
                    'hitungHargaSetelahDiskon(100000, 10) === 90000',
                    'hitungHargaSetelahDiskon(100000, 0) === 100000',
                    'hitungHargaSetelahDiskon(100000, 100) === 0',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
function hitungHargaSetelahDiskon(harga, persenDiskon) {
  return harga - (harga * persenDiskon / 100);
}

describe('hitungHargaSetelahDiskon', () => {
  test('diskon 10% dari 100000 menghasilkan 90000', () => {
    // TODO 1: arrange nilai input, act panggil fungsi, assert hasilnya
  });

  test('diskon 0% mengembalikan harga yang sama', () => {
    // TODO 2: lengkapi test edge case ini
  });

  test('diskon 100% mengembalikan harga 0', () => {
    // TODO 3: lengkapi test edge case ini
  });
});

module.exports = hitungHargaSetelahDiskon;
CODE,
                'hint' => 'Pola: const hasil = hitungHargaSetelahDiskon(100000, 10); expect(hasil).toBe(90000); — ulangi pola yang sama untuk kedua edge case dengan nilai diskon yang berbeda.',
            ],

            'Assignment 2: API Integration Test with Supertest' => [
                'title' => 'Latihan: Integration Test Endpoint dengan Supertest',
                'description' => 'Lengkapi integration test untuk endpoint GET /tasks dan POST /tasks menggunakan Supertest, termasuk skenario validasi gagal, tanpa perlu menyalakan server sungguhan.',
                'learning_objectives' => [
                    'Menulis integration test untuk endpoint Express menggunakan Supertest',
                    'Menguji skenario sukses maupun skenario gagal (validasi/404)',
                ],
                'requirements' => [
                    'Test GET /tasks memverifikasi status 200 dan response berupa array',
                    'Test POST /tasks dengan body valid memverifikasi status 201',
                    'Test POST /tasks dengan body tidak lengkap (misal tanpa judul) memverifikasi status 400',
                    'Test menggunakan request(app), bukan menyalakan app.listen() sungguhan',
                ],
                'test_cases' => [
                    'GET /tasks mengembalikan status 200 dengan body berupa array',
                    'POST /tasks dengan judul valid mengembalikan status 201',
                    'POST /tasks tanpa judul mengembalikan status 400',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
const request = require('supertest');
const app = require('./app'); // Express app, tanpa app.listen()

describe('Task API', () => {
  test('GET /tasks mengembalikan array dengan status 200', async () => {
    // TODO 1: request(app).get('/tasks') lalu assert status dan tipe body
  });

  test('POST /tasks dengan data valid mengembalikan status 201', async () => {
    // TODO 2: request(app).post('/tasks').send({ judul: 'Belajar Testing' })
    //         lalu assert status 201
  });

  test('POST /tasks tanpa judul mengembalikan status 400', async () => {
    // TODO 3: kirim body kosong/tanpa judul, assert status 400
  });
});
CODE,
                'hint' => 'Pola dasar: const res = await request(app).get("/tasks"); expect(res.status).toBe(200); expect(Array.isArray(res.body)).toBe(true);',
            ],

            // ============================================================
            // Modul 7: Server Architecture & Performance
            // ============================================================

            'Assignment 1: Refactor to Layered Architecture' => [
                'title' => 'Latihan: Service Layer Tanpa Ketergantungan HTTP',
                'description' => 'Fungsi di bawah masih mencampur logic bisnis dengan req/res Express. Pisahkan logic bisnisnya ke dalam service layer yang bisa diuji tanpa perlu mock req/res sama sekali.',
                'learning_objectives' => [
                    'Memisahkan business logic dari detail HTTP (req/res)',
                    'Menulis service layer yang bisa dites secara terpisah dari Express',
                ],
                'requirements' => [
                    'Fungsi service hanya menerima data biasa (bukan req/res) dan mengembalikan hasil biasa (bukan res.json)',
                    'Controller yang memanggil service bertugas menerjemahkan hasil service menjadi response HTTP',
                    'Error dari service (misal "user tidak ditemukan") dilempar sebagai Error biasa, bukan langsung res.status(...)',
                ],
                'test_cases' => [
                    'Fungsi service bisa dipanggil dan diuji tanpa membuat mock req/res',
                    'Controller tetap mengembalikan response HTTP yang sama seperti sebelum refactor',
                    'Error dari service tertangkap di controller dan diterjemahkan ke status code yang sesuai',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
// Kode lama (logic bisnis bercampur dengan req/res):
//
// app.get('/users/:id', (req, res) => {
//   const user = users.find(u => u.id === Number(req.params.id));
//   if (!user) return res.status(404).json({ message: 'User tidak ditemukan' });
//   res.status(200).json(user);
// });

// TODO 1: buat userService.js dengan fungsi getUserById(id)
//         yang throw new Error('User tidak ditemukan') kalau tidak ada,
//         atau return user kalau ada — TANPA req/res sama sekali

// TODO 2: buat controller yang memanggil userService.getUserById,
//         menangkap error dengan try...catch, lalu mengubahnya
//         jadi res.status(404).json({ message: err.message })
CODE,
                'hint' => 'Service: function getUserById(id) { const user = users.find(u => u.id === id); if (!user) throw new Error("User tidak ditemukan"); return user; } — Controller membungkusnya dengan try/catch untuk menerjemahkan ke response HTTP.',
            ],

            'Assignment 2: Redis Caching Layer' => [
                'title' => 'Latihan: Cache-Aside Pattern dengan Redis',
                'description' => 'Lengkapi fungsi getProdukById di bawah supaya menerapkan pola cache-aside: cek Redis dulu, kalau tidak ada baru query ke "database" lalu simpan hasilnya ke cache.',
                'learning_objectives' => [
                    'Menerapkan pola cache-aside (cek cache dulu, fallback ke database)',
                    'Menentukan strategi invalidasi cache sederhana saat data berubah',
                ],
                'requirements' => [
                    'Cek Redis terlebih dahulu menggunakan key yang unik per produk (misal produk:{id})',
                    'Kalau cache hit, kembalikan data dari cache tanpa query ke database',
                    'Kalau cache miss, query ke database, simpan hasilnya ke Redis dengan TTL (misal 300 detik), lalu kembalikan datanya',
                    'Saat produk diupdate, cache untuk produk tersebut harus dihapus (invalidasi)',
                ],
                'test_cases' => [
                    'Pemanggilan pertama untuk suatu produk melakukan query database (cache miss)',
                    'Pemanggilan kedua untuk produk yang sama TIDAK melakukan query database lagi (cache hit)',
                    'Setelah produk diupdate, pemanggilan berikutnya kembali melakukan query database (cache sudah diinvalidasi)',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
async function getProdukById(redisClient, db, id) {
  const cacheKey = `produk:${id}`;

  // TODO 1: cek Redis dengan cacheKey, kalau ada (cache hit) langsung
  //         return data yang di-parse dari cache

  // TODO 2: kalau tidak ada (cache miss), query produk dari db

  // TODO 3: simpan hasil query ke Redis dengan TTL 300 detik (gunakan setEx)

  // TODO 4: return hasil query
}

async function updateProduk(redisClient, db, id, dataBaru) {
  // update ke database (di luar scope latihan ini)

  // TODO 5: hapus cache produk:{id} dari Redis setelah update
  //         supaya pemanggilan berikutnya mengambil data terbaru
}

module.exports = { getProdukById, updateProduk };
CODE,
                'hint' => 'const cached = await redisClient.get(cacheKey); if (cached) return JSON.parse(cached); ... await redisClient.setEx(cacheKey, 300, JSON.stringify(produk)); — untuk invalidasi cukup await redisClient.del(`produk:${id}`).',
            ],

            'Assignment 3: Health Check & Monitoring Endpoint' => [
                'title' => 'Latihan: Endpoint /health dengan Cek Koneksi Database',
                'description' => 'Lengkapi endpoint GET /health di bawah supaya memverifikasi status aplikasi sekaligus koneksi database, dengan format response yang konsisten.',
                'learning_objectives' => [
                    'Membuat endpoint monitoring yang memverifikasi dependency eksternal (database)',
                    'Merancang format response API yang konsisten untuk status sukses maupun gagal',
                ],
                'requirements' => [
                    'Endpoint mengembalikan status 200 dengan { status: "ok" } jika database bisa diakses',
                    'Endpoint mengembalikan status 503 dengan { status: "error", message: ... } jika koneksi database gagal',
                    'Pengecekan database dilakukan dengan query ringan (misal SELECT 1), bukan query berat',
                ],
                'test_cases' => [
                    'Saat database sehat, GET /health mengembalikan status 200 dan { status: "ok" }',
                    'Saat koneksi database gagal (disimulasikan), GET /health mengembalikan status 503, bukan 200',
                    'Response selalu berupa JSON dengan struktur yang konsisten pada kedua skenario',
                ],
                'language' => 'javascript',
                'starter_code' => <<<'CODE'
const express = require('express');
const app = express();

app.get('/health', async (req, res) => {
  try {
    // TODO 1: jalankan query ringan ke database, misal await db.query('SELECT 1')

    // TODO 2: kalau berhasil, kembalikan status 200 dengan { status: 'ok' }
  } catch (err) {
    // TODO 3: kalau gagal, kembalikan status 503 dengan
    //         { status: 'error', message: 'Database tidak dapat diakses' }
  }
});

module.exports = app;
CODE,
                'hint' => 'try { await db.query("SELECT 1"); res.status(200).json({ status: "ok" }); } catch (err) { res.status(503).json({ status: "error", message: "Database tidak dapat diakses" }); }',
            ],
        ];
    }
}