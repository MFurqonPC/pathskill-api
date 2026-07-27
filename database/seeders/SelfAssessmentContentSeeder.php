<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\ExperienceChecklistItem;
use App\Models\ScenarioConfidenceItem;
use App\Models\VerificationQuizQuestion;
use Illuminate\Database\Seeder;

class SelfAssessmentContentSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan pengecekan foreign key
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        // Kosongkan seluruh data lama
        ExperienceChecklistItem::truncate();
        ScenarioConfidenceItem::truncate();
        VerificationQuizQuestion::truncate();

        // Aktifkan kembali foreign key
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        foreach ($this->data() as $careerName => $content) {
            $career = Career::where('name', $careerName)->first();
            if (! $career) {
                continue; // jalankan CareerSeeder dulu
            }

            foreach ($content['checklist'] as $i => $statement) {
                ExperienceChecklistItem::create([
                    'career_id' => $career->id,
                    'statement' => $statement,
                    'order' => $i + 1,
                ]);
            }

            // Lookup skill_name → id untuk career ini, dipakai untuk resolve
// key 'skill' di tiap item scenario/quiz menjadi skill_id.
$skillMap = \App\Models\CareerSkill::where('career_id', $career->id)
    ->pluck('id', 'skill_name');

foreach ($content['scenarios'] as $i => $scenario) {
    // Dukung dua format: string biasa (belum ditag skill) atau
    // array ['text' => ..., 'skill' => 'CSS'] (sudah ditag).
    $text = is_array($scenario) ? $scenario['text'] : $scenario;
    $skillName = is_array($scenario) ? ($scenario['skill'] ?? null) : null;

    ScenarioConfidenceItem::create([
        'career_id' => $career->id,
        'skill_id' => $skillName ? $skillMap->get($skillName) : null,
        'scenario_text' => $text,
        'order' => $i + 1,
    ]);
}

$warmup = $content['warmup'];
VerificationQuizQuestion::create([
    'career_id' => $career->id,
    'skill_id' => isset($warmup['skill']) ? $skillMap->get($warmup['skill']) : null,
    'question_text' => $warmup['question_text'],
    'code_snippet' => $warmup['code_snippet'] ?? null,
    'options' => $warmup['options'],
    'correct_option_index' => $warmup['correct_option_index'],
    'explanation' => $warmup['explanation'],
    'is_warmup' => true,
    'order' => 0,
]);

foreach ($content['quiz'] as $i => $q) {
    VerificationQuizQuestion::create([
        'career_id' => $career->id,
        'skill_id' => isset($q['skill']) ? $skillMap->get($q['skill']) : null,
        'question_text' => $q['question_text'],
        'code_snippet' => $q['code_snippet'] ?? null,
        'options' => $q['options'],
        'correct_option_index' => $q['correct_option_index'],
        'explanation' => $q['explanation'],
        'is_warmup' => false,
        'order' => $i + 1,
    ]);
}
        }
    }

    /**
     * @return array<string, array{checklist: string[], scenarios: string[], warmup: array, quiz: array}>
     */

private function data(): array
{
    return [
        'Full Stack Developer' => [
            'checklist' => [
                'Pernah membuat proyek kecil menggunakan HTML & CSS',
                'Pernah bekerja dengan framework JavaScript (React, Vue, atau Angular)',
                'Pernah menulis JavaScript modern (ES6+) untuk logika aplikasi',
                'Pernah membuat tampilan responsif untuk mobile dan desktop',
                'Pernah berkolaborasi dalam proyek tim menggunakan Git',
            ],
            'scenarios' => [
                ['text' => 'Debugging masalah saat form gagal ter-submit', 'skill' => 'Testing & Debugging'],
                ['text' => 'Membuat layout responsif dari desain Figma', 'skill' => 'CSS'],
                ['text' => 'Mengoptimalkan performa halaman yang lambat dimuat', 'skill' => 'Problem Solving'],
            ],
            'warmup' => [
                'question_text' => 'Perbaiki kode CSS berikut supaya elemen berada tepat di tengah (center).',
                'skill' => 'CSS',
                'code_snippet' => ".container {\n  display: ????;\n  justify-content: center;\n  align-items: center;\n}",
                'options' => ['block', 'flex', 'inline'],
                'correct_option_index' => 1,
                'explanation' => 'display: flex mengaktifkan flexbox, sehingga justify-content dan align-items bisa dipakai untuk menengahkan elemen.',
            ],
            'quiz' => [
                [
                    'question_text' => 'Apa output dari kode berikut?',
                    'skill' => 'JavaScript',
                    'code_snippet' => "for (var i = 0; i < 3; i++) {\n  setTimeout(() => console.log(i), 0);\n}",
                    'options' => ['0 1 2', 'undefined undefined undefined', '3 3 3', '0 0 0'],
                    'correct_option_index' => 2,
                    'explanation' => 'var bersifat function-scoped, bukan block-scoped, sehingga ketiga callback berbagi variabel i yang sama dan nilainya sudah 3 saat setTimeout dieksekusi. Jika pakai let, hasilnya akan 0 1 2 karena let membuat binding baru tiap iterasi.',
                ],
                [
                    'question_text' => 'Kenapa memakai index array sebagai key pada elemen list yang bisa berubah urutan (reorder/insert/delete) di React dianggap anti-pattern?',
                    'skill' => 'JavaScript',
                    'options' => [
                        'React bisa salah mencocokkan elemen lama dan baru saat reorder, sehingga state internal seperti input yang sedang diketik bisa tertukar antar item',
                        'React akan langsung melempar exception dan menghentikan seluruh proses render begitu mendeteksi key berbasis index dipakai pada sebuah list yang bisa berubah urutan',
                        'Key berbasis index membuat proses reconciliation React berjalan jauh lebih lambat dibanding key berbasis string acak seperti UUID, terutama pada list yang besar',
                        'React versi terbaru tidak lagi mengizinkan angka index dipakai sebagai key sama sekali, sehingga kode semacam itu tidak akan pernah bisa dikompilasi',
                    ],
                    'correct_option_index' => 0,
                    'explanation' => 'Key dipakai React untuk mencocokkan elemen antar render. Kalau urutan list berubah tapi key-nya berbasis index, React bisa salah mengira item lama = item baru pada index yang sama, sehingga state (misal input yang sedang diketik) bisa nyasar ke item yang salah.',
                ],
                [
                    'question_text' => 'Diberikan dua aturan CSS berikut yang menargetkan elemen yang sama, warna teks final elemen tersebut adalah apa?',
                    'skill' => 'CSS',
                    'code_snippet' => "#judul { color: blue; }\n.title { color: red !important; }\n\n<h1 id=\"judul\" class=\"title\">Teks</h1>",
                    'options' => [
                        'Biru, karena ID selector (#judul) selalu punya spesifisitas lebih tinggi daripada class selector apapun, termasuk yang memakai !important',
                        'Warna akan jatuh ke default browser (biasanya hitam) karena browser tidak bisa menyelesaikan konflik antara dua aturan yang saling bertentangan',
                        'Hasilnya tergantung urutan file CSS di-load di dalam HTML, siapa yang dimuat terakhir itu yang menang terlepas dari !important',
                        'Merah, karena !important menang atas aturan normal berapa pun spesifisitas selector-nya, selama tidak dilawan !important lain yang lebih spesifik',
                    ],
                    'correct_option_index' => 3,
                    'explanation' => '!important menang atas aturan normal berapa pun spesifisitasnya, kecuali dilawan !important lain dengan spesifisitas lebih tinggi. Di sini hanya satu aturan yang punya !important, jadi itu yang menang.',
                ],
                [
                    'question_text' => 'Apa masalah utama pada kode berikut?',
                    'skill' => 'JavaScript',
                    'code_snippet' => "async function ambilData() {\n  const res1 = await fetch('/api/a');\n  const res2 = await fetch('/api/b');\n  return [res1, res2];\n}",
                    'options' => [
                        'Kode ini tidak valid secara sintaks karena await tidak boleh dipanggil dua kali berturut-turut dalam satu fungsi async',
                        'Kedua request dijalankan berurutan (sequential) padahal tidak saling bergantung satu sama lain, sehingga total waktu tunggu lebih lama dari yang seharusnya',
                        'fetch tidak bisa dipanggil lebih dari sekali di dalam fungsi async yang sama tanpa membungkusnya dalam try-catch terlebih dahulu',
                        'Data yang dikembalikan pasti berupa array kosong karena res1 dan res2 belum ter-resolve sepenuhnya saat fungsi return',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'Karena res2 tidak bergantung pada res1, menunggunya secara berurutan membuang waktu. Idealnya pakai Promise.all([fetch(\'/api/a\'), fetch(\'/api/b\')]) agar kedua request berjalan paralel.',
                ],
                [
                    'question_text' => 'Sebuah elemen punya width: 200px, padding: 20px, dan border: 5px solid, dengan box-sizing: content-box (default). Berapa lebar total elemen yang sebenarnya dirender di layar?',
                    'skill' => 'CSS',
                    'options' => ['250px', '200px', '220px', '245px'],
                    'correct_option_index' => 0,
                    'explanation' => 'Dengan content-box, width hanya berlaku untuk area konten. Lebar total = 200 (konten) + 20+20 (padding kiri-kanan) + 5+5 (border kiri-kanan) = 250px. Ini alasan box-sizing: border-box sering dipakai supaya perhitungan lebih intuitif.',
                ],
                [
                    'question_text' => 'Kapan useEffect berikut akan dijalankan ulang?',
                    'skill' => 'JavaScript',
                    'code_snippet' => "useEffect(() => {\n  console.log('efek jalan');\n}, [userId]);",
                    'options' => [
                        'Hanya sekali saat komponen pertama kali mount, dan tidak akan pernah berjalan lagi walau userId berubah di kemudian hari',
                        'Setiap kali komponen melakukan render ulang, apapun penyebabnya, tanpa memedulikan isi dependency array sama sekali',
                        'Tidak akan pernah berjalan sama sekali karena dependency array tidak diperbolehkan berisi variabel yang berasal dari props atau state',
                        'Setiap kali komponen pertama kali mount, dan setiap kali nilai userId berubah dibandingkan dengan render sebelumnya',
                    ],
                    'correct_option_index' => 3,
                    'explanation' => 'Dependency array [userId] membuat React membandingkan nilai userId antar render; efek hanya dijalankan ulang kalau nilainya berbeda dari render sebelumnya, plus sekali di awal saat mount.',
                ],
                [
                    'question_text' => 'Kamu perlu membuat layout galeri foto dengan kolom dan baris yang sama-sama harus diatur presisi (misal grid 3x3 dengan ukuran sel konsisten). Teknik CSS mana yang paling tepat dan kenapa?',
                    'skill' => 'CSS',
                    'options' => [
                        'Position: absolute pada setiap item galeri, karena ini memberi kontrol piksel paling presisi tanpa perlu mempelajari sistem layout baru sama sekali',
                        'CSS Grid, karena dirancang khusus untuk mengatur baris dan kolom sekaligus dalam satu sistem layout dua dimensi, cocok untuk grid presisi seperti galeri foto',
                        'Float, karena kompatibilitasnya paling luas mencakup hampir semua versi browser termasuk yang sudah usang sekalipun',
                        'Flexbox, karena secara native sudah didesain untuk mengatur dua dimensi baris dan kolom dengan presisi yang sama seperti Grid',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'Flexbox pada dasarnya satu dimensi (baris ATAU kolom); mengatur dua dimensi sekaligus dengan Flexbox butuh trik tambahan. Grid didesain khusus untuk kasus dua dimensi seperti ini.',
                ],
                [
                    'question_text' => 'Apa perbedaan antara event bubbling dan event capturing, dan mode mana yang menjadi default saat addEventListener dipanggil tanpa parameter ketiga?',
                    'skill' => 'JavaScript',
                    'options' => [
                        'Capturing menyebar dari elemen induk menuju elemen target dan merupakan mode default saat addEventListener dipanggil tanpa parameter ketiga',
                        'Bubbling hanya berlaku untuk elemen form seperti input dan button, sedangkan elemen lain seperti div selalu memakai capturing',
                        'Bubbling menyebar dari elemen target ke elemen induknya dan menjadi mode default, sedangkan capturing berjalan sebaliknya dan harus diaktifkan eksplisit',
                        'Keduanya pada dasarnya identik dan hanya berbeda penamaan historis tanpa perbedaan perilaku nyata di browser modern',
                    ],
                    'correct_option_index' => 2,
                    'explanation' => 'Secara default (addEventListener(event, handler)), event berjalan dalam fase bubbling: dari elemen paling spesifik (target) menyebar ke atas ke elemen induknya. Capturing adalah arah sebaliknya dan harus diaktifkan eksplisit lewat parameter ketiga { capture: true }.',
                ],
                [
                    'question_text' => 'User mengetik cepat di kotak pencarian, dan setiap ketikan memicu pemanggilan API. Teknik apa yang paling tepat untuk mengurangi jumlah request tanpa mengorbankan responsivitas input?',
                    'skill' => 'JavaScript',
                    'options' => [
                        'Debounce — tunda pemanggilan API sampai user berhenti mengetik selama jeda waktu tertentu, sehingga request hanya dikirim sekali per jeda ketikan',
                        'Melepas (remove) event listener pada input setelah karakter pertama diketik, lalu memasangnya kembali secara manual setiap kali dibutuhkan',
                        'Mengubah input menjadi read-only setelah karakter pertama diketik supaya user tidak bisa menambah ketikan sampai API selesai merespons',
                        'Memanggil API secara synchronous supaya browser menunggu proses selesai sebelum mengizinkan user mengetik karakter berikutnya',
                    ],
                    'correct_option_index' => 0,
                    'explanation' => 'Debounce menunda eksekusi hingga user berhenti mengetik sejenak, sehingga API hanya dipanggil sekali per "jeda" ketikan, bukan di setiap keystroke. Throttle berbeda — itu membatasi eksekusi maksimal sekali per interval waktu tertentu, cocok untuk kasus seperti event scroll.',
                ],
                [
                    'question_text' => 'Kenapa properti CSS position: sticky bisa "berhenti menempel" (tidak lagi sticky) meskipun sudah discroll melewati posisinya?',
                    'skill' => 'CSS',
                    'options' => [
                        'position: sticky memang hanya pernah didukung penuh di Internet Explorer lama dan sudah dihapus dari browser modern lainnya',
                        'sticky tidak pernah bisa dipakai bersamaan dengan flexbox pada elemen induk mana pun, apapun konfigurasinya',
                        'Elemen dengan position: sticky wajib diberi width: 100% agar browser bisa menghitung area menempelnya dengan benar',
                        'Elemen induk (parent) punya overflow: hidden/scroll/auto, atau tingginya sama persis dengan elemen sticky sehingga tidak ada ruang untuk "menempel"',
                    ],
                    'correct_option_index' => 3,
                    'explanation' => 'sticky bekerja relatif terhadap parent scroll container-nya. Kalau parent punya overflow yang memotong, atau tinggi parent sama dengan elemen sticky itu sendiri, elemen tidak punya "ruang scroll" untuk terlihat menempel.',
                ],
                [
                    'question_text' => 'Kenapa memakai elemen semantic seperti <button> lebih direkomendasikan dibanding <div onclick="..."> untuk membuat tombol yang bisa diklik?',
                    'skill' => 'HTML',
                    'options' => [
                        '<button> secara bawaan sudah bisa difokus dan diaktifkan lewat keyboard (Tab + Enter/Space), sedangkan <div> butuh atribut tambahan agar perilakunya setara',
                        'Tidak ada perbedaan fungsional apapun antara <button> dan <div onclick>, keduanya akan selalu berperilaku identik persis di semua jenis browser',
                        '<div onclick> akan selalu menghasilkan error validasi HTML yang fatal, sehingga halaman tidak akan pernah bisa dirender oleh browser manapun',
                        '<button> hanya boleh dipakai di dalam elemen <form>, sehingga tidak bisa dipakai untuk aksi lain seperti toggle menu atau modal',
                    ],
                    'correct_option_index' => 0,
                    'explanation' => '<button> membawa semantik dan perilaku bawaan (focusable, bisa diaktifkan keyboard, diumumkan sebagai "button" oleh screen reader) tanpa perlu kode tambahan. <div onclick> kelihatan sama secara visual tapi kehilangan semua aksesibilitas itu kecuali ditambah tabindex dan event handler keyboard secara manual.',
                ],
                [
                    'question_text' => 'Apa fungsi utama atribut alt pada elemen <img>, dan apa akibatnya kalau atribut ini dihilangkan pada gambar yang bersifat informatif (bukan dekoratif)?',
                    'skill' => 'HTML',
                    'options' => [
                        'alt hanya berfungsi sebagai tooltip yang muncul saat kursor mouse dihover di atas gambar, tidak ada fungsi lain selain itu',
                        'alt menyediakan teks alternatif yang dibacakan oleh screen reader dan ditampilkan kalau gambar gagal dimuat; tanpa itu, pengguna screen reader kehilangan konteks penting',
                        'Menghilangkan alt akan membuat gambar tersebut otomatis tidak ter-render sama sekali oleh browser manapun, apapun format gambarnya',
                        'alt hanya relevan untuk kebutuhan SEO semata dan tidak memiliki dampak apapun terhadap aksesibilitas pengguna screen reader',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'alt memberi deskripsi teks yang dibacakan screen reader dan ditampilkan sebagai fallback kalau gambar gagal dimuat. Untuk gambar informatif, menghilangkan alt berarti pengguna screen reader tidak mendapat informasi apapun tentang gambar itu — sebuah pelanggaran aksesibilitas dasar.',
                ],
                [
                    'question_text' => 'Kode berikut membandingkan dua nilai: var_dump(0 == "abc"). Sebelum PHP 8, hasilnya true karena "abc" dikonversi jadi 0 saat dibandingkan dengan ==. Kenapa === lebih aman dipakai untuk validasi seperti mengecek ID atau token?',
                    'skill' => 'PHP',
                    'code_snippet' => "\$input = \"abc\";\nif (\$input == 0) {\n    // tereksekusi di PHP versi lama akibat type juggling\n}",
                    'options' => [
                        '== dan === pada dasarnya selalu menghasilkan hasil yang identik pada PHP versi apapun, sehingga keduanya bisa dipakai bergantian tanpa risiko sama sekali',
                        '=== memaksa PHP membandingkan tipe data sekaligus nilainya (strict comparison), sehingga menghindari konversi tipe otomatis yang bisa menghasilkan hasil tak terduga seperti pada ==',
                        '=== hanya bisa dipakai untuk membandingkan angka semata, dan tidak valid dipakai untuk membandingkan string dengan angka',
                        'Menggunakan === akan membuat kode berjalan lebih lambat secara signifikan dibanding ==, sehingga == selalu lebih disarankan demi performa',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => '== melakukan type juggling (konversi tipe otomatis) sebelum membandingkan, yang bisa menghasilkan hasil tak terduga (misal string non-numerik dianggap 0 di versi PHP lama). === membandingkan tipe DAN nilai tanpa konversi, sehingga lebih aman dan predictable untuk validasi input.',
                ],
                [
                    'question_text' => 'Apa perbedaan mendasar antara require dan include di PHP saat file yang dipanggil tidak ditemukan?',
                    'skill' => 'PHP',
                    'options' => [
                        'Keduanya akan selalu menghentikan eksekusi script secara permanen dengan cara yang identik, tanpa ada perbedaan penanganan error apapun',
                        'require menganggap file yang dipanggil krusial — kalau tidak ditemukan, PHP melempar fatal error dan skrip berhenti total, sedangkan include hanya mengeluarkan warning',
                        'include selalu menghentikan seluruh proses PHP-FPM atau server secara total, sedangkan require hanya memengaruhi request yang sedang berjalan saja',
                        'Tidak ada perbedaan apapun antara require dan include, kedua nama tersebut hanya alias historis untuk fungsi yang persis sama',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'require menganggap file yang dipanggil krusial — kalau tidak ditemukan, PHP melempar fatal error dan skrip berhenti total. include lebih permisif — hanya mengeluarkan warning dan skrip tetap lanjut jalan meski file gagal dimuat. Untuk dependency penting (misal file konfigurasi/koneksi database), require lebih aman dipakai.',
                ],
                [
                    'question_text' => 'Apa perbedaan fungsi klausa WHERE dan HAVING dalam query SQL yang menggunakan GROUP BY?',
                    'skill' => 'SQL',
                    'code_snippet' => "SELECT department, COUNT(*) as total\nFROM employees\nWHERE status = 'active'\nGROUP BY department\nHAVING COUNT(*) > 5;",
                    'options' => [
                        'WHERE dan HAVING pada dasarnya berfungsi identik dan bisa saling dipertukarkan posisinya dalam query apapun tanpa mengubah hasil sama sekali',
                        'WHERE memfilter baris sebelum data dikelompokkan, sedangkan HAVING memfilter hasil setelah agregasi dilakukan — sehingga HAVING bisa memfilter berdasarkan hasil fungsi seperti COUNT()',
                        'HAVING hanya bisa dipakai pada tabel yang tidak memiliki primary key sama sekali, sedangkan WHERE selalu mengharuskan tabel punya primary key',
                        'WHERE hanya berfungsi untuk data bertipe angka semata, sedangkan HAVING hanya berfungsi khusus untuk data bertipe teks atau string',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'WHERE menyaring baris individual sebelum pengelompokan terjadi, sehingga tidak bisa mengacu ke hasil agregat (misal COUNT()). HAVING bekerja setelah GROUP BY, memfilter grup berdasarkan hasil agregasinya — pada contoh di atas, HAVING COUNT(*) > 5 hanya menampilkan department dengan lebih dari 5 karyawan aktif.',
                ],
                [
                    'question_text' => 'Kenapa transaction (BEGIN...COMMIT/ROLLBACK) penting saat menjalankan beberapa query INSERT/UPDATE yang saling bergantung, misal mencatat pesanan sekaligus mengurangi stok produk?',
                    'skill' => 'SQL',
                    'options' => [
                        'Transaction hanya berguna untuk mempercepat eksekusi query semata, dan tidak memiliki hubungan apapun dengan konsistensi atau integritas data',
                        'Transaction memastikan seluruh rangkaian query dieksekusi sebagai satu unit utuh — kalau salah satu gagal, seluruh perubahan bisa di-ROLLBACK sehingga data tidak setengah tersimpan',
                        'Transaction wajib digunakan pada setiap query SELECT sekalipun, termasuk yang hanya membaca data tanpa mengubah apapun di database',
                        'Menggunakan transaction akan otomatis membuat query berjalan jauh lebih lambat tanpa ada manfaat lain yang benar-benar sepadan',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'Tanpa transaction, kalau server crash atau salah satu query gagal di tengah rangkaian operasi yang saling bergantung, database bisa berakhir dalam state yang tidak konsisten (misal stok sudah berkurang tapi order gagal tersimpan). Transaction menjamin sifat "semua berhasil atau semua dibatalkan" (atomicity), menjaga integritas data.',
                ],
                [
                    'question_text' => 'Apa perbedaan mendasar antara git merge dan git rebase saat menggabungkan perubahan dari branch feature ke branch main?',
                    'skill' => 'Git & Version Control',
                    'options' => [
                        'git merge dan git rebase pada dasarnya menghasilkan riwayat commit yang identik persis secara internal, hanya berbeda dalam nama perintah dan sintaks yang dipakai',
                        'git merge membuat commit baru yang menggabungkan dua riwayat dan mempertahankan histori branch apa adanya, sedangkan git rebase memindahkan commit feature ke atas commit terbaru main sehingga riwayat jadi linear namun hash commit berubah',
                        'git rebase hanya bisa dijalankan dari branch main menuju branch feature, dan tidak pernah bisa dipakai sebaliknya dari branch feature manapun',
                        'git merge akan selalu menghapus seluruh riwayat commit pada branch feature setelah proses penggabungan selesai, sehingga riwayat lama tidak bisa ditelusuri lagi',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'merge menggabungkan dua riwayat dengan membuat merge commit baru, mempertahankan histori percabangan apa adanya (termasuk kompleksitasnya). rebase "menulis ulang" histori dengan memindahkan commit feature agar seolah dibuat setelah commit terbaru main, menghasilkan riwayat linear yang lebih bersih — tapi karena mengubah commit hash, rebase pada branch yang sudah di-push dan dipakai orang lain bisa berbahaya.',
                ],
                [
                    'question_text' => 'Saat git pull menghasilkan conflict pada sebuah file, apa yang sebenarnya terjadi dan langkah apa yang harus dilakukan sebelum bisa commit kembali?',
                    'skill' => 'Git & Version Control',
                    'options' => [
                        'Git secara otomatis akan memilih salah satu versi file secara acak lalu langsung menyelesaikan proses commit tanpa membutuhkan campur tangan apapun dari pengguna',
                        'Conflict artinya Git tidak bisa otomatis menggabungkan perubahan karena baris yang sama diubah berbeda di kedua sisi; pengguna harus membuka file tersebut, memutuskan versi yang benar secara manual, lalu menandainya selesai dengan git add sebelum commit',
                        'Conflict berarti repository menjadi rusak secara permanen, dan satu-satunya solusi yang tersedia adalah menghapus seluruh riwayat lalu membuat repository baru dari awal',
                        'Conflict hanya bisa terjadi pada file bertipe gambar atau biner, dan tidak akan pernah terjadi pada file teks seperti kode program apapun',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'Conflict muncul saat Git tidak bisa otomatis menyatukan perubahan pada baris yang sama dari dua sumber berbeda. Git menandai bagian yang konflik langsung di dalam file (dengan marker <<<<<<<, =======, >>>>>>>), dan pengguna harus menghapus marker itu setelah memutuskan versi final, lalu git add file tersebut sebelum bisa menyelesaikan commit/merge.',
                ],
            ],
        ],

        'Backend Developer' => [
            'checklist' => [
                'Pernah membuat REST API menggunakan Node.js/Express atau framework backend lain',
                'Pernah merancang skema database (SQL atau NoSQL)',
                'Pernah mengimplementasikan autentikasi (login/JWT/session) di backend',
                'Pernah menangani error handling dan validasi input di API',
                'Pernah melakukan query database yang kompleks (JOIN, aggregation)',
            ],
            'scenarios' => [
                ['text' => 'Debugging API yang mengembalikan response sangat lambat', 'skill' => 'Testing & Debugging'],
                ['text' => 'Merancang skema database untuk fitur baru dari nol', 'skill' => 'Databases (SQL/NoSQL)'],
                ['text' => 'Menangani celah keamanan (SQL injection) pada endpoint publik', 'skill' => 'Authentication & Security'],
            ],
            'warmup' => [
                'question_text' => 'Perbaiki kode Express.js berikut supaya endpoint mengembalikan status 404 saat data tidak ditemukan.',
                'skill' => 'REST APIs',
                'code_snippet' => "app.get('/users/:id', (req, res) => {\n  const user = findUser(req.params.id);\n  if (!user) {\n    return res.status(????).json({ error: 'User not found' });\n  }\n  res.json(user);\n});",
                'options' => ['200', '500', '404'],
                'correct_option_index' => 2,
                'explanation' => 'Status 404 Not Found dipakai ketika resource yang diminta tidak ditemukan di server.',
            ],
            'quiz' => [
                [
                    'question_text' => 'Method HTTP PUT dianggap idempotent, artinya mengirim request yang sama berkali-kali menghasilkan efek akhir yang sama. Apakah POST juga idempotent?',
                    'skill' => 'REST APIs',
                    'options' => [
                        'Ya, POST selalu bersifat idempotent persis seperti PUT, karena keduanya sama-sama mengirim data ke server melalui body request',
                        'Tidak — POST umumnya dipakai untuk membuat resource baru, sehingga mengirim request yang sama berkali-kali bisa membuat banyak resource duplikat',
                        'Tidak, tapi alasannya karena POST hanya bisa dipakai sekali saja sepanjang umur satu koneksi HTTP yang sama',
                        'Konsep idempotency sebenarnya hanya berlaku untuk method GET dan tidak relevan sama sekali untuk method lainnya',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'PUT mengganti/menetapkan state resource pada URL tertentu, jadi diulang berkali-kali hasil akhirnya sama. POST biasanya membuat resource baru setiap kali dipanggil (misal beberapa baris database baru), sehingga tidak idempotent.',
                ],
                [
                    'question_text' => 'Kode berikut mengambil daftar order lalu, untuk tiap order, melakukan query terpisah ke tabel users. Apa nama masalah performa ini dan bagaimana solusi umumnya?',
                    'skill' => 'Databases (SQL/NoSQL)',
                    'code_snippet' => "orders = Order.all()\nfor order in orders:\n    user = User.find(order.user_id)  # query terpisah tiap loop",
                    'options' => [
                        'Deadlock — dua proses saling menunggu lock satu sama lain, solusinya menambah index tambahan pada tabel users agar query lebih cepat',
                        'SQL Injection — input user_id yang tidak divalidasi bisa disisipi query berbahaya, solusinya sanitize setiap nilai user_id sebelum dipakai',
                        'Race condition — dua query berjalan bersamaan dan saling menimpa hasil, solusinya menambahkan transaction lock di level database',
                        'N+1 query problem — solusinya pakai eager loading/JOIN agar data user diambil sekaligus dalam satu query, bukan satu per satu di dalam loop',
                    ],
                    'correct_option_index' => 3,
                    'explanation' => 'Ini disebut N+1 query: 1 query untuk ambil semua order, ditambah N query (satu per order) untuk ambil user-nya. Solusinya eager loading (misal .with(\'user\') di Laravel, atau JOIN manual) sehingga cukup 1-2 query total.',
                ],
                [
                    'question_text' => 'Dari sisi keamanan, apa risiko utama menyimpan JWT di localStorage dibanding menyimpannya di httpOnly cookie?',
                    'skill' => 'Authentication & Security',
                    'options' => [
                        'localStorage bisa diakses lewat JavaScript apapun yang berjalan di halaman, sehingga rentan dicuri lewat serangan XSS dibanding httpOnly cookie',
                        'localStorage otomatis terhapus setiap kali ada request baru dikirim ke server, sehingga token JWT yang tersimpan di sana selalu menjadi invalid',
                        'httpOnly cookie tidak pernah bisa ikut terkirim ke server sama sekali, sehingga tidak berguna untuk menyimpan token autentikasi',
                        'Tidak ada perbedaan risiko keamanan berarti antara localStorage dan httpOnly cookie selama koneksi memakai HTTPS',
                    ],
                    'correct_option_index' => 0,
                    'explanation' => 'localStorage bisa dibaca oleh script apapun yang berjalan di halaman, termasuk script berbahaya hasil serangan XSS. httpOnly cookie tidak bisa diakses lewat JavaScript sama sekali, sehingga lebih tahan terhadap pencurian token via XSS (meski tetap rentan terhadap CSRF kalau tidak dimitigasi).',
                ],
                [
                    'question_text' => 'Dua request datang bersamaan untuk mengurangi stok produk yang sama dari 5 menjadi masing-masing -1, tanpa mekanisme locking. Masalah apa yang berpotensi terjadi, dan istilah teknisnya apa?',
                    'skill' => 'Databases (SQL/NoSQL)',
                    'options' => [
                        'SQL Injection — karena dua request dikirim ke server secara bersamaan tanpa jeda waktu yang cukup antar keduanya',
                        'Memory leak — karena menjalankan dua request paralel pada endpoint yang sama membuat memori server tidak pernah dibebaskan',
                        'Race condition — kedua request bisa membaca stok "5" secara bersamaan sebelum salah satu selesai menulis, sehingga stok akhir salah hitung',
                        'CORS error — karena kedua request berasal dari origin/domain yang berbeda saat mengakses endpoint pengurangan stok yang sama',
                    ],
                    'correct_option_index' => 2,
                    'explanation' => 'Tanpa locking/transaction yang tepat, kedua proses bisa membaca nilai stok yang sama sebelum salah satu menulis hasil pengurangannya, menyebabkan stok akhir tidak akurat (lost update). Solusi umum: database transaction dengan row locking, atau optimistic locking.',
                ],
                [
                    'question_text' => 'Menambahkan index baru ke kolom yang sering di-query pasti mempercepat SELECT. Apa trade-off (kerugian) yang perlu dipertimbangkan?',
                    'skill' => 'Databases (SQL/NoSQL)',
                    'options' => [
                        'Index membuat data yang tersimpan di dalam tabel menjadi tidak konsisten dan berisiko hilang setiap kali proses update dijalankan',
                        'Operasi INSERT/UPDATE/DELETE menjadi lebih lambat karena index juga harus diperbarui, dan index menambah ukuran penyimpanan yang dipakai',
                        'Index hanya bisa diterapkan pada kolom bertipe angka (integer), sehingga tidak bisa dipakai untuk kolom teks atau tanggal',
                        'Tidak ada trade-off apapun yang perlu dipikirkan, menambah index selalu murni menguntungkan tanpa efek samping apapun',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'Setiap kali baris ditulis/diubah/dihapus, seluruh index yang relevan juga harus disesuaikan, sehingga menambah beban di operasi tulis. Index yang terlalu banyak atau tidak perlu justru memperlambat write-heavy workload dan memakan storage.',
                ],
                [
                    'question_text' => 'Middleware A memvalidasi token, middleware B melakukan logging request. Kalau urutan pendaftarannya app.use(loggingMiddleware); app.use(authMiddleware);, apa konsekuensinya?',
                    'skill' => 'Node.js',
                    'options' => [
                        'Request yang tidak punya token valid tetap akan tercatat di log lebih dulu sebelum akhirnya ditolak oleh authMiddleware',
                        'authMiddleware tidak akan pernah dijalankan sama sekali karena posisinya diletakkan setelah loggingMiddleware pada kode',
                        'Urutan pendaftaran middleware tidak berpengaruh apapun terhadap urutan eksekusinya saat request masuk ke server',
                        'loggingMiddleware otomatis akan dieksekusi setelah authMiddleware meskipun didaftarkan lebih dulu di dalam kode aplikasi',
                    ],
                    'correct_option_index' => 0,
                    'explanation' => 'Middleware dieksekusi sesuai urutan pendaftarannya. Karena logging didaftarkan lebih dulu, semua request (termasuk yang nantinya ditolak karena token invalid) akan tetap tercatat lebih dulu di log sebelum authMiddleware sempat menolaknya.',
                ],
                [
                    'question_text' => 'Kapan denormalisasi database (sengaja menyimpan data duplikat) bisa menjadi keputusan yang tepat, meskipun melanggar prinsip normalisasi?',
                    'skill' => 'Databases (SQL/NoSQL)',
                    'options' => [
                        'Selalu menjadi pilihan yang tepat, karena normalisasi database sebenarnya tidak lagi diperlukan pada arsitektur aplikasi modern',
                        'Hanya menjadi opsi yang valid untuk database NoSQL, dan tidak pernah bisa diterapkan pada database relasional (SQL)',
                        'Tidak pernah ada alasan yang benar-benar valid untuk melakukan denormalisasi pada sistem produksi mana pun',
                        'Ketika kecepatan baca (read) jauh lebih kritis daripada kompleksitas menjaga konsistensi data saat menulis, misal untuk laporan/dashboard yang sering diakses',
                    ],
                    'correct_option_index' => 3,
                    'explanation' => 'Denormalisasi mengurangi kebutuhan JOIN sehingga query baca jadi lebih cepat, dengan trade-off risiko inkonsistensi data dan penambahan kompleksitas saat menulis. Ini keputusan sadar berdasarkan pola akses data (read-heavy vs write-heavy), bukan aturan mutlak.',
                ],
                [
                    'question_text' => 'Apa perbedaan mendasar antara autentikasi (authentication) dan otorisasi (authorization) dalam konteks keamanan API?',
                    'skill' => 'Authentication & Security',
                    'options' => [
                        'Keduanya adalah istilah berbeda yang sebenarnya merujuk pada satu proses teknis yang identik dalam sistem keamanan API',
                        'Otorisasi selalu dilakukan lebih dulu sebelum sistem sempat memverifikasi identitas pengguna lewat proses autentikasi',
                        'Autentikasi memverifikasi SIAPA pengguna itu, sedangkan otorisasi menentukan APA yang boleh dilakukan oleh pengguna tersebut',
                        'Autentikasi hanya relevan untuk API yang bersifat publik, sedangkan otorisasi hanya dipakai pada API internal perusahaan',
                    ],
                    'correct_option_index' => 2,
                    'explanation' => 'Autentikasi menjawab "apakah kamu benar-benar user X?" (misal lewat login), sedangkan otorisasi menjawab "apakah user X ini punya izin melakukan aksi Y?" (misal role admin vs biasa). Sebuah sistem bisa saja mengautentikasi user dengan benar tapi tetap menolak aksinya karena tidak diotorisasi.',
                ],
                [
                    'question_text' => 'Rate limiting biasanya diterapkan pada endpoint login. Apa tujuan utamanya, dan kenapa ini beda dari validasi input?',
                    'skill' => 'Authentication & Security',
                    'options' => [
                        'Rate limiting dan validasi input pada dasarnya adalah dua istilah berbeda untuk satu mekanisme keamanan yang sama persis',
                        'Mencegah serangan brute force dengan membatasi jumlah percobaan dalam periode waktu tertentu, sementara validasi input hanya memastikan format data yang dikirim benar',
                        'Tujuan utama rate limiting adalah mempercepat response time server dengan mengurangi jumlah data yang perlu diproses setiap saat',
                        'Validasi input yang cukup ketat sudah otomatis menggantikan seluruh kebutuhan akan penerapan rate limiting pada endpoint manapun',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'Validasi input mengecek APA yang dikirim (misal format email benar), sedangkan rate limiting mengecek SEBERAPA SERING sesuatu dikirim dalam rentang waktu tertentu — keduanya lapisan pertahanan berbeda dan saling melengkapi, bukan saling menggantikan.',
                ],
                [
                    'question_text' => 'Sebuah proses memindahkan uang dari akun A ke akun B melibatkan dua operasi: kurangi saldo A, tambah saldo B. Kalau server crash tepat di antara dua operasi itu tanpa database transaction, apa yang bisa terjadi, dan properti ACID mana yang relevan?',
                    'skill' => 'Databases (SQL/NoSQL)',
                    'options' => [
                        'Saldo A sudah berkurang tapi saldo B belum bertambah sehingga data tidak konsisten — ini dicegah oleh properti Atomicity yang menjamin operasi berjalan semua atau tidak sama sekali',
                        'Tidak akan terjadi masalah apapun karena database secara otomatis melakukan rollback sendiri tanpa perlu ada transaction eksplisit sama sekali',
                        'Ini murni masalah Isolation, bukan Atomicity, karena menyangkut dua operasi yang dijalankan pada baris data yang berbeda satu sama lain',
                        'Prinsip ACID hanya relevan dan berlaku untuk database NoSQL semata, dan sama sekali tidak berlaku untuk database relasional seperti pada kasus ini',
                    ],
                    'correct_option_index' => 0,
                    'explanation' => 'Tanpa transaction, dua operasi terpisah bisa "terpotong" di tengah jalan sehingga hasilnya tidak konsisten (uang hilang). Atomicity dalam ACID menjamin serangkaian operasi dieksekusi sebagai satu unit utuh — kalau gagal di tengah, seluruh perubahan di-rollback.',
                ],
                [
                    'question_text' => 'Apa perbedaan mendasar antara git merge dan git rebase saat menggabungkan perubahan dari branch feature ke branch main?',
                    'skill' => 'Git & Version Control',
                    'options' => [
                        'git merge dan git rebase pada dasarnya menghasilkan riwayat commit yang identik persis secara internal, hanya berbeda dalam nama perintah dan sintaks yang dipakai',
                        'git merge membuat commit baru yang menggabungkan dua riwayat dan mempertahankan histori branch apa adanya, sedangkan git rebase memindahkan commit feature ke atas commit terbaru main sehingga riwayat jadi linear namun hash commit berubah',
                        'git rebase hanya bisa dijalankan dari branch main menuju branch feature, dan tidak pernah bisa dipakai sebaliknya dari branch feature manapun',
                        'git merge akan selalu menghapus seluruh riwayat commit pada branch feature setelah proses penggabungan selesai, sehingga riwayat lama tidak bisa ditelusuri lagi',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'merge menggabungkan dua riwayat dengan membuat merge commit baru, mempertahankan histori percabangan apa adanya (termasuk kompleksitasnya). rebase "menulis ulang" histori dengan memindahkan commit feature agar seolah dibuat setelah commit terbaru main, menghasilkan riwayat linear yang lebih bersih — tapi karena mengubah commit hash, rebase pada branch yang sudah di-push dan dipakai orang lain bisa berbahaya.',
                ],
                [
                    'question_text' => 'Saat git pull menghasilkan conflict pada sebuah file, apa yang sebenarnya terjadi dan langkah apa yang harus dilakukan sebelum bisa commit kembali?',
                    'skill' => 'Git & Version Control',
                    'options' => [
                        'Git secara otomatis akan memilih salah satu versi file secara acak lalu langsung menyelesaikan proses commit tanpa membutuhkan campur tangan apapun dari pengguna',
                        'Conflict artinya Git tidak bisa otomatis menggabungkan perubahan karena baris yang sama diubah berbeda di kedua sisi; pengguna harus membuka file tersebut, memutuskan versi yang benar secara manual, lalu menandainya selesai dengan git add sebelum commit',
                        'Conflict berarti repository menjadi rusak secara permanen, dan satu-satunya solusi yang tersedia adalah menghapus seluruh riwayat lalu membuat repository baru dari awal',
                        'Conflict hanya bisa terjadi pada file bertipe gambar atau biner, dan tidak akan pernah terjadi pada file teks seperti kode program apapun',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'Conflict muncul saat Git tidak bisa otomatis menyatukan perubahan pada baris yang sama dari dua sumber berbeda. Git menandai bagian yang konflik langsung di dalam file (dengan marker <<<<<<<, =======, >>>>>>>), dan pengguna harus menghapus marker itu setelah memutuskan versi final, lalu git add file tersebut sebelum bisa menyelesaikan commit/merge.',
                ],
            ],
        ],

        'UI/UX Designer' => [
            'checklist' => [
                'Pernah membuat wireframe atau mockup menggunakan Figma/Sketch/Adobe XD',
                'Pernah melakukan user research (wawancara/survey) sebelum mendesain',
                'Pernah membuat design system atau komponen UI yang reusable',
                'Pernah melakukan usability testing terhadap desain',
                'Pernah berkolaborasi dengan developer untuk implementasi desain',
            ],
            'scenarios' => [
                ['text' => 'Mendesain ulang halaman checkout yang tingkat drop-off-nya tinggi', 'skill' => 'Problem Solving'],
                ['text' => 'Melakukan riset pengguna untuk fitur yang belum pernah dibuat sebelumnya', 'skill' => 'User Research'],
                ['text' => 'Menjelaskan keputusan desain ke stakeholder non-teknis', 'skill' => 'Communication'],
            ],
            'warmup' => [
                'question_text' => 'Pilih prinsip desain yang paling tepat untuk meningkatkan keterbacaan teks pada latar belakang gelap.',
                'skill' => 'CSS',
                'code_snippet' => null,
                'options' => ['Kontras warna teks-background tinggi', 'Ukuran font sekecil mungkin', 'Warna teks sama dengan background'],
                'correct_option_index' => 0,
                'explanation' => 'Kontras warna yang tinggi antara teks dan background penting untuk keterbacaan, sesuai standar aksesibilitas WCAG.',
            ],
            'quiz' => [
                [
                    'question_text' => 'Fitts\'s Law menyatakan bahwa waktu untuk mencapai target dipengaruhi oleh jarak dan ukuran target tersebut. Berdasarkan prinsip ini, kenapa tombol "Hapus Akun" yang berbahaya biasanya dibuat kecil dan jauh dari tombol utama, bukan besar dan mudah dijangkau?',
                    'skill' => 'Wireframing & Prototyping',
                    'options' => [
                        'Untuk memastikan tombol tersebut terlihat lebih estetik dan seimbang secara visual, terlepas dari tingkat risiko aksi yang diwakilinya',
                        'Karena Fitts\'s Law sebenarnya tidak berlaku sama sekali untuk tombol-tombol yang berpotensi berbahaya seperti penghapusan akun',
                        'Supaya keseluruhan halaman terlihat lebih minimalis dan rapi, tanpa mempertimbangkan risiko aksi yang diwakili tombol tersebut',
                        'Untuk sengaja memperbesar effort dan waktu yang dibutuhkan mencapainya, sehingga mengurangi risiko ter-klik tidak sengaja',
                    ],
                    'correct_option_index' => 3,
                    'explanation' => 'Fitts\'s Law dipakai dua arah: untuk aksi yang ingin dipermudah, target dibuat besar & dekat; untuk aksi berisiko yang ingin "dijaga jaraknya" dari klik tidak sengaja, target sengaja dibuat kecil dan/atau jauh dari alur klik utama.',
                ],
                [
                    'question_text' => 'Sebuah teks abu-abu (#999999) di atas latar putih (#FFFFFF) diukur memiliki contrast ratio 2.85:1. Apakah ini memenuhi standar WCAG AA untuk teks normal (bukan teks besar)?',
                    'skill' => 'CSS',
                    'options' => [
                        'Ya, karena rasio kontras di atas 2:1 sudah dianggap cukup memadai untuk semua ukuran dan ketebalan teks',
                        'Tidak — WCAG AA mensyaratkan minimal 4.5:1 untuk teks normal, jadi kombinasi ini gagal dan perlu diperbaiki',
                        'Ya, karena warna abu-abu pada dasarnya selalu dianggap sebagai warna netral yang aman digunakan di mana saja',
                        'Pertanyaan ini tidak relevan karena WCAG hanya mengatur ukuran font, bukan kombinasi warna teks dan latar',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'WCAG 2.1 level AA mensyaratkan rasio kontras minimal 4.5:1 untuk teks normal (3:1 untuk teks besar/bold). Rasio 2.85:1 jelas di bawah ambang batas ini dan akan sulit dibaca oleh sebagian pengguna, termasuk yang low vision.',
                ],
                [
                    'question_text' => 'Apa perbedaan utama antara card sorting dan tree testing sebagai metode riset information architecture?',
                    'skill' => 'User Research',
                    'options' => [
                        'Keduanya adalah nama lain untuk satu metode riset information architecture yang persis sama tanpa perbedaan signifikan apapun',
                        'Card sorting hanya cocok diterapkan pada aplikasi mobile semata, sedangkan tree testing hanya cocok dipakai untuk platform berbasis web',
                        'Card sorting dipakai untuk merancang struktur kategori dari nol lewat pengguna mengelompokkan kartu, sedangkan tree testing dipakai untuk memvalidasi struktur yang sudah ada',
                        'Tree testing selalu harus dilakukan lebih dulu sebelum tim bisa melanjutkan ke sesi card sorting pada proyek apapun',
                    ],
                    'correct_option_index' => 2,
                    'explanation' => 'Card sorting bersifat generatif — membantu tim MEMBENTUK struktur kategori berdasarkan bagaimana pengguna secara alami mengelompokkan konten. Tree testing bersifat evaluatif — menguji apakah struktur yang SUDAH dirancang bisa dinavigasi dengan mudah oleh pengguna untuk menemukan item tertentu.',
                ],
                [
                    'question_text' => 'Progressive disclosure adalah teknik menyembunyikan informasi/opsi kompleks di balik interaksi tambahan (misal "Advanced Settings"), dan hanya menampilkan yang esensial di awal. Apa risiko utama kalau teknik ini diterapkan secara berlebihan?',
                    'skill' => 'Wireframing & Prototyping',
                    'options' => [
                        'Fitur penting bisa jadi tersembunyi terlalu dalam sehingga pengguna tidak menemukannya sama sekali (discoverability rendah)',
                        'Halaman utama menjadi terlalu penuh dengan seluruh informasi dan opsi yang sebenarnya ingin disembunyikan sejak awal',
                        'Progressive disclosure selalu meningkatkan cognitive load pengguna secara signifikan, tanpa pengecualian pada kondisi apapun',
                        'Teknik progressive disclosure pada dasarnya hanya bisa diterapkan secara efektif pada aplikasi berbasis mobile saja',
                    ],
                    'correct_option_index' => 0,
                    'explanation' => 'Progressive disclosure mengurangi cognitive load di awal, tapi kalau berlebihan, fitur yang sebenarnya sering dibutuhkan pengguna bisa jadi tersembunyi terlalu dalam sehingga mereka tidak sadar fitur itu ada — trade-off antara kesederhanaan tampilan dan discoverability.',
                ],
                [
                    'question_text' => 'Apa perbedaan mendasar antara user persona dan empathy map sebagai artefak riset UX?',
                    'skill' => 'User Research',
                    'options' => [
                        'Persona dan empathy map adalah dua dokumen yang identik, hanya berbeda dalam format visual penyajiannya saja tanpa perbedaan isi',
                        'Empathy map hanya relevan dipakai dalam konteks riset yang bersifat kuantitatif semata, bukan riset kualitatif pengguna',
                        'Persona hanya relevan digunakan untuk produk berjenis B2B semata, sedangkan empathy map hanya relevan dipakai untuk produk B2C',
                        'Persona merangkum siapa pengguna secara umum sebagai profil, sedangkan empathy map memetakan apa yang dipikirkan dan dirasakan pengguna dalam momen tertentu',
                    ],
                    'correct_option_index' => 3,
                    'explanation' => 'Persona adalah representasi identitas pengguna secara umum (siapa mereka), sedangkan empathy map lebih fokus pada state internal pengguna dalam suatu konteks spesifik (apa yang mereka pikirkan, rasakan, lihat, katakan pada momen tertentu) — keduanya saling melengkapi, bukan pengganti satu sama lain.',
                ],
                [
                    'question_text' => 'WCAG punya tiga level kepatuhan: A, AA, dan AAA. Level mana yang paling umum dijadikan standar minimum wajib oleh regulasi/perusahaan kebanyakan, dan kenapa AAA jarang dijadikan target penuh?',
                    'skill' => 'CSS',
                    'options' => [
                        'AAA — karena level ini secara otomatis menjadi standar wajib yang diterapkan oleh regulasi hukum di hampir semua negara',
                        'AA — karena AAA memiliki kriteria yang sangat ketat dan seringkali tidak realistis dipenuhi penuh tanpa mengorbankan aspek desain lain',
                        'A — karena level paling dasar ini pada dasarnya sudah dianggap paling lengkap dan mencakup seluruh kebutuhan aksesibilitas pengguna',
                        'Ketiga level WCAG tersebut sebenarnya tidak memiliki keterkaitan apapun dengan regulasi hukum aksesibilitas di negara manapun',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'AA adalah level yang paling umum dijadikan target compliance (termasuk oleh banyak regulasi seperti ADA di AS), karena AAA memuat kriteria yang sangat ketat (misal rasio kontras 7:1) yang sulit dipenuhi konsisten di seluruh konten tanpa mengorbankan aspek desain lain.',
                ],
                [
                    'question_text' => 'Apa perbedaan user flow dan user journey map, dan kapan masing-masing lebih tepat dipakai?',
                    'skill' => 'Wireframing & Prototyping',
                    'options' => [
                        'User flow fokus pada langkah teknis navigasi untuk menyelesaikan satu task spesifik, sedangkan journey map mencakup pengalaman lebih luas termasuk emosi pengguna',
                        'Keduanya merupakan istilah berbeda yang pada dasarnya merujuk pada satu jenis dokumen desain yang persis sama',
                        'User flow hanya relevan dipakai pada proses desain aplikasi mobile semata, dan tidak berlaku untuk desain aplikasi berbasis web',
                        'Journey map hanya boleh dibuat setelah produk sepenuhnya selesai dikembangkan, dan tidak pernah dibuat pada tahap awal proyek',
                    ],
                    'correct_option_index' => 0,
                    'explanation' => 'User flow biasanya berupa diagram alur layar/langkah untuk task tertentu (misal proses checkout). Journey map lebih luas, mencakup keseluruhan pengalaman pengguna termasuk emosi, pain point, dan titik kontak sebelum/sesudah menggunakan produk — bukan hanya di dalam aplikasi.',
                ],
                [
                    'question_text' => 'Sebuah tim mengklaim hasil A/B test mereka valid karena Varian B punya conversion rate lebih tinggi, padahal sample size hanya 40 pengguna per varian dan test dijalankan cuma 6 jam. Apa masalah utama pada klaim ini?',
                    'skill' => 'User Research',
                    'options' => [
                        'Tidak ada masalah apapun, karena A/B test akan selalu valid berapa pun jumlah sample dan durasi pengujiannya dijalankan',
                        'Masalah utamanya terletak pada pemilihan hari pengujian yang jatuh di hari kerja, bukan pada akhir pekan yang lebih ramai',
                        'Sample size terlalu kecil dan durasi terlalu singkat untuk mencapai signifikansi statistik, hasilnya kemungkinan besar hanya kebetulan, bukan efek nyata',
                        'A/B test sebenarnya tidak pernah membutuhkan perhitungan atau pertimbangan statistik apapun agar bisa dijalankan dengan benar',
                    ],
                    'correct_option_index' => 2,
                    'explanation' => 'A/B test butuh sample size dan durasi yang cukup untuk mencapai signifikansi statistik dan menangkap variasi perilaku pengguna dari waktu ke waktu (misal beda perilaku siang vs malam). Sample kecil dan durasi singkat sangat rentan terhadap hasil yang kebetulan, bukan efek desain yang sebenarnya.',
                ],
                [
                    'question_text' => 'Kapan skip-link ("Lompat ke konten utama") menjadi elemen aksesibilitas yang penting pada sebuah halaman web?',
                    'skill' => 'HTML',
                    'options' => [
                        'Hanya diperlukan pada halaman yang sama sekali tidak memiliki navigasi atau menu apapun di bagian atasnya',
                        'Skip-link hanya benar-benar relevan digunakan oleh pengguna yang mengakses halaman lewat perangkat mouse, bukan lewat keyboard',
                        'Skip-link pada dasarnya hanyalah elemen dekoratif tambahan tanpa fungsi aksesibilitas nyata bagi pengguna manapun yang mengaksesnya',
                        'Ketika halaman punya navigasi panjang di bagian atas, sehingga pengguna keyboard atau screen reader tidak perlu melewati semua item menu berulang kali',
                    ],
                    'correct_option_index' => 3,
                    'explanation' => 'Skip-link membantu pengguna yang bernavigasi dengan keyboard (tab) atau screen reader untuk langsung "melompat" ke konten utama tanpa harus menekan tab berkali-kali melewati seluruh item menu di setiap halaman yang dikunjungi.',
                ],
                [
                    'question_text' => 'Dalam heuristic evaluation Nielsen, prinsip "Visibility of system status" mengharuskan sistem selalu memberi feedback kepada pengguna. Contoh pelanggaran paling umum dari prinsip ini di produk digital adalah?',
                    'skill' => 'Wireframing & Prototyping',
                    'options' => [
                        'Warna tombol yang dipilih terlalu mencolok dibandingkan elemen-elemen lain yang ada di sekitar halaman tersebut',
                        'Tombol submit yang tidak menampilkan loading indicator apapun setelah diklik, membuat pengguna tidak tahu apakah aksinya berhasil terkirim atau tidak',
                        'Terlalu banyak animasi transisi yang ditampilkan setiap kali pengguna berpindah dari satu halaman ke halaman lain',
                        'Ukuran font pada judul halaman yang dibuat jauh lebih besar dibanding ukuran font pada bagian isi konten',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'Tanpa indikator loading/status setelah aksi (submit, upload, dll), pengguna tidak tahu apakah sistem sedang memproses, sudah selesai, atau gagal — ini pelanggaran langsung terhadap prinsip "sistem harus selalu menginformasikan kondisinya kepada pengguna melalui feedback yang wajar".',
                ],
                [
                    'question_text' => 'Apa perbedaan mendasar antara git merge dan git rebase saat menggabungkan perubahan dari branch feature ke branch main?',
                    'skill' => 'Git & Version Control',
                    'options' => [
                        'git merge dan git rebase pada dasarnya menghasilkan riwayat commit yang identik persis secara internal, hanya berbeda dalam nama perintah dan sintaks yang dipakai',
                        'git merge membuat commit baru yang menggabungkan dua riwayat dan mempertahankan histori branch apa adanya, sedangkan git rebase memindahkan commit feature ke atas commit terbaru main sehingga riwayat jadi linear namun hash commit berubah',
                        'git rebase hanya bisa dijalankan dari branch main menuju branch feature, dan tidak pernah bisa dipakai sebaliknya dari branch feature manapun',
                        'git merge akan selalu menghapus seluruh riwayat commit pada branch feature setelah proses penggabungan selesai, sehingga riwayat lama tidak bisa ditelusuri lagi',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'merge menggabungkan dua riwayat dengan membuat merge commit baru, mempertahankan histori percabangan apa adanya (termasuk kompleksitasnya). rebase "menulis ulang" histori dengan memindahkan commit feature agar seolah dibuat setelah commit terbaru main, menghasilkan riwayat linear yang lebih bersih — tapi karena mengubah commit hash, rebase pada branch yang sudah di-push dan dipakai orang lain bisa berbahaya.',
                ],
                [
                    'question_text' => 'Saat git pull menghasilkan conflict pada sebuah file, apa yang sebenarnya terjadi dan langkah apa yang harus dilakukan sebelum bisa commit kembali?',
                    'skill' => 'Git & Version Control',
                    'options' => [
                        'Git secara otomatis akan memilih salah satu versi file secara acak lalu langsung menyelesaikan proses commit tanpa membutuhkan campur tangan apapun dari pengguna',
                        'Conflict artinya Git tidak bisa otomatis menggabungkan perubahan karena baris yang sama diubah berbeda di kedua sisi; pengguna harus membuka file tersebut, memutuskan versi yang benar secara manual, lalu menandainya selesai dengan git add sebelum commit',
                        'Conflict berarti repository menjadi rusak secara permanen, dan satu-satunya solusi yang tersedia adalah menghapus seluruh riwayat lalu membuat repository baru dari awal',
                        'Conflict hanya bisa terjadi pada file bertipe gambar atau biner, dan tidak akan pernah terjadi pada file teks seperti kode program apapun',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'Conflict muncul saat Git tidak bisa otomatis menyatukan perubahan pada baris yang sama dari dua sumber berbeda. Git menandai bagian yang konflik langsung di dalam file (dengan marker <<<<<<<, =======, >>>>>>>), dan pengguna harus menghapus marker itu setelah memutuskan versi final, lalu git add file tersebut sebelum bisa menyelesaikan commit/merge.',
                ],
            ],
        ],

        'DevOps Engineer' => [
            'checklist' => [
                'Pernah membuat Dockerfile untuk containerize aplikasi',
                'Pernah deploy aplikasi ke Kubernetes cluster',
                'Pernah membuat pipeline CI/CD (GitHub Actions/GitLab CI/Jenkins)',
                'Pernah mengelola server Linux (SSH, permission, systemd)',
                'Pernah setup monitoring/logging untuk aplikasi production',
            ],
            'scenarios' => [
                ['text' => 'Container aplikasi tiba-tiba crash di production', 'skill' => 'Docker'],
                ['text' => 'Pipeline CI/CD gagal setelah push ke branch main', 'skill' => 'CI/CD'],
                ['text' => 'Mengoptimalkan resource usage cluster Kubernetes yang overload', 'skill' => 'Kubernetes'],
            ],
            'warmup' => [
                'question_text' => 'Lengkapi Dockerfile berikut supaya direktori kerja container ter-set dengan benar.',
                'skill' => 'Docker',
                'code_snippet' => "FROM node:20\nWORKDIR ????\nCOPY package.json .\nRUN npm install",
                'options' => ['npm', 'docker', '/app'],
                'correct_option_index' => 2,
                'explanation' => 'WORKDIR menentukan direktori kerja di dalam container, biasanya diisi path seperti /app.',
            ],
            'quiz' => [
                [
                    'question_text' => 'Apa perbedaan mendasar antara Liveness Probe dan Readiness Probe di Kubernetes, dan apa akibatnya kalau keduanya dikonfigurasi mengarah ke endpoint yang sama dengan logic identik?',
                    'skill' => 'Kubernetes',
                    'options' => [
                        'Liveness gagal membuat Pod di-restart, sedangkan readiness gagal hanya mengeluarkan Pod dari traffic sementara — kalau endpoint keduanya sama, Pod bisa direstart padahal cukup dikeluarkan dari traffic',
                        'Keduanya pada dasarnya memberikan efek yang identik terhadap Pod, sehingga tidak masalah dikonfigurasi dengan endpoint dan logic yang sama persis',
                        'Readiness probe hanya bisa diterapkan secara valid pada Pod yang menjalankan database, dan sama sekali tidak berlaku untuk aplikasi web biasa',
                        'Liveness probe sebenarnya tidak pernah memengaruhi status maupun siklus hidup Pod sama sekali di dalam cluster Kubernetes manapun',
                    ],
                    'correct_option_index' => 0,
                    'explanation' => 'Menyamakan kedua probe adalah kesalahan konfigurasi umum: kalau aplikasi sedang overload sementara (bukan benar-benar mati), readiness probe yang gagal cukup untuk sementara berhenti menerima traffic, tapi kalau liveness probe juga gagal dengan logic sama, Kubernetes akan me-restart Pod yang sebenarnya masih bisa pulih sendiri — memperparah situasi.',
                ],
                [
                    'question_text' => 'Apa perbedaan utama strategi deployment Blue-Green dan Canary?',
                    'skill' => 'CI/CD',
                    'options' => [
                        'Keduanya adalah strategi deployment yang identik, hanya menggunakan penamaan berbeda tanpa ada perbedaan teknis apapun di antaranya',
                        'Blue-Green hanya bisa diterapkan secara eksklusif pada Kubernetes semata, sedangkan Canary hanya bisa diterapkan pada Docker Swarm',
                        'Blue-Green mengalihkan seluruh traffic sekaligus ke versi baru setelah siap, sedangkan Canary mengalihkan traffic bertahap untuk memvalidasi versi baru dengan risiko lebih kecil',
                        'Strategi Canary pada dasarnya akan selalu lebih cepat dieksekusi secara keseluruhan dibandingkan strategi Blue-Green pada kondisi apapun',
                    ],
                    'correct_option_index' => 2,
                    'explanation' => 'Blue-Green switch penuh dan instan (mudah rollback tapi risiko dampak penuh kalau ada bug), sedangkan Canary rilis bertahap ke sebagian kecil pengguna dulu, memungkinkan deteksi masalah dengan dampak terbatas sebelum rollout penuh.',
                ],
                [
                    'question_text' => 'Docker memanfaatkan layer caching saat build image. Kenapa urutan instruksi berikut dianggap kurang optimal untuk kecepatan build berulang?',
                    'skill' => 'Docker',
                    'code_snippet' => "FROM node:20\nWORKDIR /app\nCOPY . .\nRUN npm install\nCMD [\"node\", \"server.js\"]",
                    'options' => [
                        'Urutan instruksi pada Dockerfile tersebut sebenarnya sudah paling optimal dan tidak ada bagian yang perlu diubah lagi sama sekali',
                        'COPY . . menyalin seluruh kode sebelum npm install, sehingga perubahan kecil pada kode tetap membatalkan cache layer install dan memaksa install ulang semua dependency',
                        'Instruksi CMD seharusnya selalu diletakkan pada baris paling pertama di dalam Dockerfile, bahkan sebelum instruksi FROM dijalankan',
                        'RUN npm install sebenarnya tidak diperbolehkan dipakai berdampingan dengan instruksi COPY dalam satu Dockerfile yang sama persis',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'Docker meng-cache tiap layer berdasarkan instruksi sebelumnya. Idealnya COPY package.json lebih dulu, RUN npm install, BARU COPY . . — supaya perubahan pada source code saja (tanpa ubah dependency) tidak memicu install ulang seluruh package, mempercepat build berulang secara signifikan.',
                ],
                [
                    'question_text' => 'Kapan sebaiknya memakai Kubernetes StatefulSet dibanding Deployment biasa?',
                    'skill' => 'Kubernetes',
                    'options' => [
                        'StatefulSet akan selalu ter-deploy lebih cepat dibandingkan Deployment biasa untuk semua jenis beban kerja aplikasi apapun',
                        'Deployment sama sekali tidak bisa dipakai untuk menjalankan aplikasi web pada cluster Kubernetes dalam kondisi apapun sekalipun',
                        'StatefulSet dan Deployment pada dasarnya memiliki fungsi yang identik dan hanya berbeda dari sisi penamaan resource-nya saja',
                        'Ketika aplikasi butuh identitas jaringan stabil dan storage persisten per instance, seperti database cluster, bukan aplikasi stateless yang instance-nya bisa saling dipertukarkan',
                    ],
                    'correct_option_index' => 3,
                    'explanation' => 'Deployment cocok untuk aplikasi stateless dimana tiap Pod identik dan bisa saling menggantikan bebas. StatefulSet dipakai saat tiap Pod butuh identitas unik yang stabil (nama, storage) yang tetap sama meski Pod di-restart — penting untuk database terdistribusi seperti Cassandra atau Kafka.',
                ],
                [
                    'question_text' => 'Prinsip immutable infrastructure menyatakan server/container yang sudah di-deploy tidak boleh diubah langsung (misal SSH masuk lalu edit config manual). Kenapa prinsip ini penting dalam praktik DevOps modern?',
                    'skill' => 'Linux',
                    'options' => [
                        'Perubahan manual langsung di server membuat konfigurasi antar environment tidak konsisten dan sulit direplikasi; perubahan seharusnya lewat rebuild image atau redeploy dari kode',
                        'Immutable infrastructure pada dasarnya berarti server tidak boleh pernah dilakukan restart dalam kondisi apapun sekalipun sedang crash',
                        'Prinsip immutable infrastructure ini hanya relevan diterapkan pada database semata, dan tidak relevan untuk komponen aplikasi lainnya',
                        'Menerapkan immutable infrastructure akan membuat proses deployment menjadi jauh lebih lambat tanpa ada manfaat tambahan lainnya',
                    ],
                    'correct_option_index' => 0,
                    'explanation' => 'Kalau server sering "ditambal" manual, lama-lama konfigurasinya menyimpang dari definisi aslinya (configuration drift) dan sulit direproduksi ulang secara konsisten. Immutable infrastructure memaksa semua perubahan lewat proses yang terdokumentasi (rebuild image, IaC) sehingga environment selalu bisa direplikasi dengan predictable.',
                ],
                [
                    'question_text' => 'Menjalankan terraform apply dua kali berturut-turut pada konfigurasi yang sama seharusnya tidak menghasilkan perubahan tambahan pada infrastruktur di kali kedua. Properti apa yang dijamin oleh perilaku ini?',
                    'skill' => 'CI/CD',
                    'options' => [
                        'Immutability — karena prinsip ini menjamin bahwa infrastruktur yang sudah dibuat tidak akan pernah bisa diubah sama sekali',
                        'Concurrency — karena beberapa proses apply dapat berjalan secara bersamaan tanpa saling mengganggu satu sama lain',
                        'Idempotency — menjalankan operasi yang sama berkali-kali menghasilkan state akhir yang sama, tidak menduplikasi resource',
                        'Properti idempotency sebenarnya tidak relevan sama sekali dengan konsep dan praktik Infrastructure as Code modern',
                    ],
                    'correct_option_index' => 2,
                    'explanation' => 'Tool IaC seperti Terraform membandingkan state yang diinginkan (kode) dengan state aktual di infrastruktur, dan hanya menerapkan perbedaannya. Kalau tidak ada perbedaan, apply kedua tidak melakukan apa-apa — inilah sifat idempotent yang membuat IaC aman dijalankan berulang.',
                ],
                [
                    'question_text' => 'Apa risiko utama menyimpan secret (API key, password database) langsung di environment variable dalam file docker-compose.yml yang ikut di-commit ke Git?',
                    'skill' => 'Docker',
                    'options' => [
                        'File docker-compose.yml sebenarnya tidak mendukung deklarasi environment variable sama sekali di dalam strukturnya sejak awal',
                        'Tidak ada risiko keamanan berarti karena environment variable akan selalu terenkripsi secara otomatis oleh Docker itu sendiri',
                        'Risiko semacam ini hanya berlaku jika repository bersifat publik, dan sama sekali tidak berlaku pada repository privat tim internal',
                        'Secret tersebut menjadi bagian dari riwayat commit Git secara permanen dan bisa diakses siapapun yang punya akses repository, bahkan setelah dihapus dari commit terbaru',
                    ],
                    'correct_option_index' => 3,
                    'explanation' => 'Sekali secret masuk ke riwayat commit Git, ia tetap tersimpan di histori meskipun dihapus dari file terbaru (kecuali riwayatnya dibersihkan khusus). Praktik yang benar: pakai secret manager (Vault, AWS Secrets Manager) atau minimal file .env yang di-gitignore, bukan hardcode di file yang ikut version control.',
                ],
                [
                    'question_text' => 'Rolling update mengganti instance lama dengan yang baru secara bertahap sambil tetap melayani traffic. Dalam kondisi apa strategi "Recreate" (matikan semua instance lama dulu, baru nyalakan yang baru) justru lebih masuk akal dipakai?',
                    'skill' => 'Kubernetes',
                    'options' => [
                        'Strategi Recreate akan selalu lebih unggul dibandingkan rolling update pada kondisi deployment apapun tanpa pengecualian sama sekali',
                        'Ketika aplikasi tidak boleh menjalankan dua versi berbeda secara bersamaan, misal karena perubahan skema database yang tidak backward-compatible',
                        'Strategi Recreate hanya bisa diterapkan secara valid pada aplikasi stateless yang sangat sederhana tanpa dependency eksternal apapun',
                        'Sebenarnya tidak ada skenario produksi yang benar-benar membenarkan penggunaan strategi Recreate dalam praktik DevOps manapun',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'Rolling update menyebabkan periode dimana versi lama dan baru berjalan bersamaan. Kalau ada perubahan yang membuat kedua versi tidak bisa hidup berdampingan (misal migrasi skema database yang breaking), Recreate — meski menyebabkan downtime singkat — jadi pilihan yang lebih aman.',
                ],
                [
                    'question_text' => 'Sebuah tim mengalami masalah: response time API tiba-tiba naik drastis, tapi tidak ada error di log dan CPU/memory server terlihat normal. Pilar observability mana yang paling membantu menemukan DI KOMPONEN mana request menghabiskan waktu paling lama?',
                    'skill' => 'Monitoring & Logging',
                    'options' => [
                        'Distributed tracing — memetakan perjalanan satu request melalui berbagai service/komponen untuk melihat di titik mana waktu paling banyak terpakai',
                        'Logs — karena catatan log dari tiap service akan selalu otomatis menunjukkan durasi pemrosesan tiap komponen secara rinci',
                        'Metrics CPU/memory — karena masalah penurunan performa aplikasi selalu berasal dari resource server yang mulai habis',
                        'Sebenarnya tidak tersedia cara yang memadai untuk mendiagnosis jenis masalah performa semacam ini pada sistem terdistribusi',
                    ],
                    'correct_option_index' => 0,
                    'explanation' => 'Metrics resource yang normal berarti bottleneck-nya bukan soal CPU/memory, dan logs individual per service tidak menunjukkan hubungan waktu ANTAR service. Distributed tracing secara spesifik memetakan alur satu request melewati banyak service dan menunjukkan komponen mana yang paling memakan waktu (misal call database yang lambat).',
                ],
                [
                    'question_text' => 'Horizontal scaling (menambah jumlah instance) sering dianggap lebih fleksibel dibanding vertical scaling (menambah spek satu server). Namun ada aplikasi yang sulit di-horizontal scale tanpa perubahan arsitektur. Kenapa?',
                    'skill' => 'Kubernetes',
                    'options' => [
                        'Horizontal scaling pada dasarnya hanya bisa diterapkan secara valid pada database semata, dan tidak berlaku untuk aplikasi web',
                        'Semua jenis aplikasi pada dasarnya selalu bisa di-horizontal scale dengan mudah tanpa syarat teknis tambahan apapun sama sekali',
                        'Aplikasi yang menyimpan state penting secara lokal di memory instance tidak otomatis konsisten kalau request user diarahkan ke instance berbeda oleh load balancer',
                        'Vertical scaling akan selalu jauh lebih murah dibandingkan horizontal scaling untuk seluruh jenis kasus penggunaan aplikasi apapun',
                    ],
                    'correct_option_index' => 2,
                    'explanation' => 'Kalau aplikasi menyimpan state (misal session login) hanya di memory satu instance, request berikutnya yang diarahkan ke instance lain oleh load balancer tidak akan menemukan state tersebut. Aplikasi perlu dibuat stateless (state dipindah ke shared store seperti Redis) agar bisa di-scale horizontal dengan aman.',
                ],
                [
                    'question_text' => 'Apa perbedaan mendasar antara git merge dan git rebase saat menggabungkan perubahan dari branch feature ke branch main?',
                    'skill' => 'Git & Version Control',
                    'options' => [
                        'git merge dan git rebase pada dasarnya menghasilkan riwayat commit yang identik persis secara internal, hanya berbeda dalam nama perintah dan sintaks yang dipakai',
                        'git merge membuat commit baru yang menggabungkan dua riwayat dan mempertahankan histori branch apa adanya, sedangkan git rebase memindahkan commit feature ke atas commit terbaru main sehingga riwayat jadi linear namun hash commit berubah',
                        'git rebase hanya bisa dijalankan dari branch main menuju branch feature, dan tidak pernah bisa dipakai sebaliknya dari branch feature manapun',
                        'git merge akan selalu menghapus seluruh riwayat commit pada branch feature setelah proses penggabungan selesai, sehingga riwayat lama tidak bisa ditelusuri lagi',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'merge menggabungkan dua riwayat dengan membuat merge commit baru, mempertahankan histori percabangan apa adanya (termasuk kompleksitasnya). rebase "menulis ulang" histori dengan memindahkan commit feature agar seolah dibuat setelah commit terbaru main, menghasilkan riwayat linear yang lebih bersih — tapi karena mengubah commit hash, rebase pada branch yang sudah di-push dan dipakai orang lain bisa berbahaya.',
                ],
                [
                    'question_text' => 'Saat git pull menghasilkan conflict pada sebuah file, apa yang sebenarnya terjadi dan langkah apa yang harus dilakukan sebelum bisa commit kembali?',
                    'skill' => 'Git & Version Control',
                    'options' => [
                        'Git secara otomatis akan memilih salah satu versi file secara acak lalu langsung menyelesaikan proses commit tanpa membutuhkan campur tangan apapun dari pengguna',
                        'Conflict artinya Git tidak bisa otomatis menggabungkan perubahan karena baris yang sama diubah berbeda di kedua sisi; pengguna harus membuka file tersebut, memutuskan versi yang benar secara manual, lalu menandainya selesai dengan git add sebelum commit',
                        'Conflict berarti repository menjadi rusak secara permanen, dan satu-satunya solusi yang tersedia adalah menghapus seluruh riwayat lalu membuat repository baru dari awal',
                        'Conflict hanya bisa terjadi pada file bertipe gambar atau biner, dan tidak akan pernah terjadi pada file teks seperti kode program apapun',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'Conflict muncul saat Git tidak bisa otomatis menyatukan perubahan pada baris yang sama dari dua sumber berbeda. Git menandai bagian yang konflik langsung di dalam file (dengan marker <<<<<<<, =======, >>>>>>>), dan pengguna harus menghapus marker itu setelah memutuskan versi final, lalu git add file tersebut sebelum bisa menyelesaikan commit/merge.',
                ],
            ],
        ],

        'Data Analyst' => [
            'checklist' => [
                'Pernah membersihkan dan mengolah data mentah (data cleaning) menggunakan Python/R',
                'Pernah membuat visualisasi data (chart/dashboard) untuk komunikasi insight',
                'Pernah menulis query SQL untuk analisis data dari database',
                'Pernah melakukan analisis statistik dasar (rata-rata, korelasi, distribusi)',
                'Pernah membuat laporan atau presentasi berbasis data untuk stakeholder',
            ],
            'scenarios' => [
                ['text' => 'Data yang diterima punya banyak missing values dan duplikat', 'skill' => 'Python'],
                ['text' => 'Stakeholder minta insight cepat dari dataset yang sangat besar', 'skill' => 'Problem Solving'],
                ['text' => 'Hasil analisis menunjukkan korelasi yang tidak sesuai ekspektasi bisnis', 'skill' => 'Problem Solving'],
            ],
            'warmup' => [
                'question_text' => 'Lengkapi kode Python (pandas) berikut untuk menghapus baris duplikat.',
                'skill' => 'Python',
                'code_snippet' => "import pandas as pd\ndf = pd.read_csv('data.csv')\ndf = df.????()",
                'options' => ['remove_duplicates', 'drop_duplicates', 'delete_duplicates'],
                'correct_option_index' => 1,
                'explanation' => 'pandas menyediakan method drop_duplicates() untuk menghapus baris yang datanya duplikat.',
            ],
            'quiz' => [
                [
                    'question_text' => 'Data menunjukkan bahwa penjualan es krim dan jumlah kasus tenggelam sama-sama naik setiap bulan Juni-Agustus, dengan korelasi 0.85. Kesimpulan mana yang paling tepat?',
                    'skill' => 'Problem Solving',
                    'options' => [
                        'Membeli es krim dalam jumlah banyak secara langsung menjadi penyebab meningkatnya jumlah kasus tenggelam di kolam maupun pantai',
                        'Data semacam ini pasti keliru dicatat karena nilai korelasi setinggi 0.85 secara statistik tidak mungkin muncul secara kebetulan',
                        'Korelasi tinggi tidak membuktikan sebab-akibat langsung — kemungkinan besar ada variabel ketiga seperti cuaca panas yang memengaruhi keduanya secara terpisah',
                        'Meningkatnya jumlah kasus tenggelam justru menjadi penyebab utama naiknya angka penjualan es krim pada periode yang sama',
                    ],
                    'correct_option_index' => 2,
                    'explanation' => 'Ini contoh klasik "correlation does not imply causation". Kenaikan suhu di musim panas mendorong lebih banyak orang membeli es krim SEKALIGUS lebih banyak orang berenang (sehingga kasus tenggelam naik) — cuaca adalah confounding variable yang menjelaskan korelasi tanpa hubungan sebab-akibat langsung antara keduanya.',
                ],
                [
                    'question_text' => 'Tabel customers punya 100 baris, tabel orders punya 150 baris (beberapa customer belum pernah order). Query "SELECT * FROM customers LEFT JOIN orders ON customers.id = orders.customer_id" menghasilkan berapa baris minimal, dan kenapa?',
                    'skill' => 'SQL',
                    'options' => [
                        'Minimal 100 baris — LEFT JOIN mempertahankan semua baris dari tabel kiri meskipun tidak ada order yang cocok, dengan kolom order diisi NULL',
                        'Tepat 150 baris, karena hasil LEFT JOIN akan selalu mengikuti jumlah baris yang ada pada tabel di sisi kanan',
                        'Tepat 100 baris, karena LEFT JOIN tidak pernah bisa menghasilkan jumlah baris lebih banyak dari tabel di sisi kirinya',
                        'Query tersebut akan menghasilkan error karena jumlah baris pada kedua tabel yang di-JOIN tidak sama persis satu sama lain',
                    ],
                    'correct_option_index' => 0,
                    'explanation' => 'LEFT JOIN menjamin semua baris dari tabel kiri (customers) tetap muncul di hasil, walau tidak ada order yang cocok (kolom order jadi NULL). Kalau ada customer dengan lebih dari satu order, hasilnya bisa lebih dari 100 baris (karena baris customer tersebut terduplikasi per order) — beda dengan INNER JOIN yang hanya menyertakan baris yang benar-benar match di kedua tabel.',
                ],
                [
                    'question_text' => 'Sebuah A/B test menghasilkan p-value 0.03 untuk perbedaan conversion rate antara Varian A dan B. Interpretasi mana yang PALING TEPAT?',
                    'skill' => 'Problem Solving',
                    'options' => [
                        'p-value sebesar 0.03 berarti Varian B dapat dipastikan memiliki performa 3% lebih baik dibandingkan Varian A secara nyata',
                        'Ada 3% kemungkinan perbedaan sebesar ini terjadi murni kebetulan jika sebenarnya tidak ada perbedaan nyata — bukan berarti 97% pasti benar atau mengukur dampak bisnis',
                        'Nilai p-value pada dasarnya tidak memiliki keterkaitan apapun dengan konsep signifikansi statistik dalam pengujian hipotesis manapun',
                        'Hasil pengujian ini pasti tidak dapat dipercaya karena nilai p-value yang diperoleh dianggap terlalu kecil untuk masuk akal',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'p-value mengukur probabilitas mengamati hasil seekstrem ini (atau lebih) di bawah asumsi H0 (tidak ada perbedaan sebenarnya) — bukan probabilitas hipotesis benar, dan bukan ukuran besarnya efek. p-value kecil (biasanya <0.05) menunjukkan hasil "signifikan secara statistik", tapi signifikansi statistik tidak selalu sama dengan signifikansi praktis/bisnis.',
                ],
                [
                    'question_text' => 'Kenapa standar deviasi lebih sering dipakai daripada varians untuk mendeskripsikan sebaran data kepada stakeholder non-teknis?',
                    'skill' => 'Communication',
                    'options' => [
                        'Rumus untuk menghitung varians dan standar deviasi sebenarnya identik persis, sehingga tidak ada perbedaan berarti di antara keduanya',
                        'Nilai standar deviasi akan selalu lebih akurat dibandingkan nilai varians dalam merepresentasikan sebaran data apapun jenisnya',
                        'Varians hanya bisa dihitung untuk jenis data kategorikal semata, dan tidak bisa diterapkan pada data numerik seperti gaji',
                        'Standar deviasi memiliki satuan yang sama dengan data aslinya, sedangkan varians adalah satuan kuadrat yang sulit diinterpretasikan secara intuitif',
                    ],
                    'correct_option_index' => 3,
                    'explanation' => 'Standar deviasi adalah akar kuadrat dari varians, sehingga satuannya kembali sama dengan data asli — memudahkan interpretasi ("rata-rata penyimpangan sekitar Rp50.000") dibanding varians yang satuannya kuadrat dan kurang intuitif dikomunikasikan.',
                ],
                [
                    'question_text' => 'Simpson\'s Paradox adalah fenomena di mana suatu tren muncul pada beberapa kelompok data terpisah, tapi hilang atau berbalik arah ketika data digabung. Kenapa fenomena ini berbahaya bagi analis yang terburu-buru mengambil kesimpulan?',
                    'skill' => 'Problem Solving',
                    'options' => [
                        'Fenomena Simpson\'s Paradox ini pada dasarnya hanya dapat terjadi pada dataset yang mengandung banyak sekali kesalahan pencatatan',
                        'Simpson\'s Paradox pada kenyataannya tidak pernah benar-benar ditemukan terjadi pada dataset bisnis di dunia nyata manapun',
                        'Kesimpulan di level agregat bisa menyesatkan kalau ada variabel pembaur yang berbeda proporsinya antar subgrup, sehingga penting memeriksa data per segmen',
                        'Munculnya Simpson\'s Paradox pada suatu dataset berarti sebagian data tersebut harus segera dihapus sebelum dianalisis lebih lanjut',
                    ],
                    'correct_option_index' => 2,
                    'explanation' => 'Contoh klasik: sebuah obat bisa terlihat kurang efektif secara keseluruhan, tapi ternyata lebih efektif di SETIAP subgrup pasien (pria dan wanita terpisah) — karena proporsi pasien parah berbeda antar grup. Ini menunjukkan pentingnya segmentasi data, bukan hanya melihat angka agregat.',
                ],
                [
                    'question_text' => 'Apa perbedaan window function (misal RANK() OVER (PARTITION BY...)) dengan GROUP BY biasa dalam SQL?',
                    'skill' => 'SQL',
                    'options' => [
                        'GROUP BY meringkas banyak baris jadi satu baris per grup, sedangkan window function tetap mempertahankan setiap baris asli sambil menambah kolom hasil perhitungan',
                        'Window function dan GROUP BY pada dasarnya akan selalu menghasilkan output akhir yang persis identik satu sama lain',
                        'Window function hanya tersedia dan bisa dipakai pada database NoSQL semata, dan tidak tersedia pada database SQL relasional',
                        'GROUP BY akan selalu diproses lebih cepat dibandingkan window function untuk semua jenis kasus query yang ada',
                    ],
                    'correct_option_index' => 0,
                    'explanation' => 'GROUP BY mengagregasi baris jadi satu ringkasan per grup (misal total penjualan per kota). Window function menghitung nilai berbasis grup (misal ranking penjualan dalam kota) TANPA mengurangi jumlah baris asli — berguna saat butuh detail per baris sekaligus konteks agregatnya, misal "ranking tiap transaksi dalam bulannya".',
                ],
                [
                    'question_text' => 'Sebuah studi tentang "kesuksesan startup" hanya menganalisis startup yang masih beroperasi hari ini, tanpa memasukkan startup yang sudah gagal/tutup. Bias apa yang terjadi di sini?',
                    'skill' => 'Problem Solving',
                    'options' => [
                        'Selection bias sebenarnya tidak relevan sama sekali untuk dipertimbangkan dalam kasus studi kesuksesan startup semacam ini',
                        'Survivorship bias — kesimpulan hanya didasarkan pada yang bertahan, sehingga bisa melebih-lebihkan faktor kesuksesan dan mengabaikan pola yang juga dimiliki startup gagal',
                        'Ini lebih tepat disebut sebagai confirmation bias, bukan survivorship bias, karena tim hanya melihat data yang mendukung hipotesisnya',
                        'Tidak ada bias metodologis apapun dalam studi ini karena seluruh data yang dianalisis berasal dari startup yang benar-benar nyata',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'Survivorship bias terjadi ketika analisis hanya melibatkan subjek yang "lolos" suatu proses seleksi (di sini: startup yang masih hidup), mengabaikan yang gagal. Akibatnya, faktor yang dianggap "kunci sukses" bisa saja sebenarnya juga dimiliki banyak startup yang gagal, tapi tidak pernah dianalisis karena datanya tidak ada.',
                ],
                [
                    'question_text' => 'Saat membangun model prediksi, seorang analis melakukan normalisasi (scaling) data menggunakan mean dan standar deviasi dari SELURUH dataset (termasuk data test) sebelum membagi menjadi train/test set. Apa masalah dari pendekatan ini?',
                    'skill' => 'Python',
                    'options' => [
                        'Sebenarnya tidak ada masalah apapun, karena proses normalisasi selalu diperbolehkan dilakukan sebelum data dibagi menjadi train dan test',
                        'Masalah yang muncul di sini murni terkait kecepatan komputasi saja, dan tidak memengaruhi validitas hasil evaluasi model apapun',
                        'Fenomena data leakage semacam ini hanya relevan dan hanya bisa terjadi pada data time series, tidak pada jenis data lainnya',
                        'Data leakage — informasi dari test set bocor ke proses training lewat statistik yang dipakai untuk scaling, membuat evaluasi model terlihat lebih baik dari kenyataan',
                    ],
                    'correct_option_index' => 3,
                    'explanation' => 'Statistik yang dipakai untuk transformasi data (scaling, imputasi, dll) seharusnya dihitung HANYA dari training set, lalu diterapkan ke test set — bukan sebaliknya. Kalau dihitung dari gabungan semua data, test set secara tidak sengaja "membocorkan" informasinya ke proses training, membuat evaluasi model jadi terlalu optimis dan tidak merepresentasikan performa nyata di data yang benar-benar baru.',
                ],
                [
                    'question_text' => 'Sebuah data penjualan bulanan menunjukkan pola naik-turun yang berulang tiap tahun (misal selalu naik di bulan Desember), ditambah tren kenaikan jangka panjang secara keseluruhan. Istilah apa untuk masing-masing pola ini dalam analisis time series?',
                    'skill' => 'Python',
                    'options' => [
                        'Kedua pola tersebut sebenarnya disebut dengan istilah yang persis sama di dalam analisis time series, yaitu trend semata',
                        'Pola berulang dan kenaikan jangka panjang semacam ini secara umum disebut sebagai outlier, bukan bagian normal dari data time series',
                        'Pola berulang tahunan disebut seasonality, sedangkan kenaikan jangka panjang disebut trend — keduanya komponen berbeda yang biasanya dipisahkan sebelum dianalisis',
                        'Sebuah data time series sebenarnya tidak mungkin memiliki lebih dari satu jenis pola yang muncul secara bersamaan',
                    ],
                    'correct_option_index' => 2,
                    'explanation' => 'Trend adalah arah pergerakan jangka panjang data (naik/turun/stabil), sedangkan seasonality adalah pola berulang dalam interval waktu tetap (misal tiap Desember). Time series decomposition memisahkan keduanya (plus komponen noise/residual) agar masing-masing bisa dianalisis dan diprediksi secara terpisah.',
                ],
                [
                    'question_text' => 'Dataset gaji karyawan memiliki mean Rp8.000.000 tapi median hanya Rp6.000.000. Apa yang paling mungkin menjelaskan perbedaan besar ini?',
                    'skill' => 'Problem Solving',
                    'options' => [
                        'Distribusi data kemungkinan skewed ke kanan — ada segelintir gaji sangat tinggi yang menarik mean naik jauh di atas median',
                        'Data tersebut pasti mengalami kesalahan perhitungan karena nilai mean dan median seharusnya selalu identik pada dataset apapun',
                        'Nilai median pada dasarnya akan selalu lebih besar dibandingkan nilai mean pada distribusi data jenis apapun tanpa terkecuali',
                        'Perbedaan yang cukup besar ini justru menjadi indikasi bahwa tidak terdapat outlier sama sekali di dalam dataset gaji tersebut',
                    ],
                    'correct_option_index' => 0,
                    'explanation' => 'Mean sensitif terhadap nilai ekstrem (outlier), sedangkan median tidak. Selisih besar seperti ini adalah indikasi kuat distribusi data condong (skewed), biasanya karena sejumlah kecil nilai yang jauh lebih tinggi dari mayoritas data — dalam kasus gaji, median sering jadi ukuran yang lebih representatif untuk "gaji orang kebanyakan".',
                ],
                [
                    'question_text' => 'Apa perbedaan mendasar antara git merge dan git rebase saat menggabungkan perubahan dari branch feature ke branch main?',
                    'skill' => 'Git & Version Control',
                    'options' => [
                        'git merge dan git rebase pada dasarnya menghasilkan riwayat commit yang identik persis secara internal, hanya berbeda dalam nama perintah dan sintaks yang dipakai',
                        'git merge membuat commit baru yang menggabungkan dua riwayat dan mempertahankan histori branch apa adanya, sedangkan git rebase memindahkan commit feature ke atas commit terbaru main sehingga riwayat jadi linear namun hash commit berubah',
                        'git rebase hanya bisa dijalankan dari branch main menuju branch feature, dan tidak pernah bisa dipakai sebaliknya dari branch feature manapun',
                        'git merge akan selalu menghapus seluruh riwayat commit pada branch feature setelah proses penggabungan selesai, sehingga riwayat lama tidak bisa ditelusuri lagi',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'merge menggabungkan dua riwayat dengan membuat merge commit baru, mempertahankan histori percabangan apa adanya (termasuk kompleksitasnya). rebase "menulis ulang" histori dengan memindahkan commit feature agar seolah dibuat setelah commit terbaru main, menghasilkan riwayat linear yang lebih bersih — tapi karena mengubah commit hash, rebase pada branch yang sudah di-push dan dipakai orang lain bisa berbahaya.',
                ],
                [
                    'question_text' => 'Saat git pull menghasilkan conflict pada sebuah file, apa yang sebenarnya terjadi dan langkah apa yang harus dilakukan sebelum bisa commit kembali?',
                    'skill' => 'Git & Version Control',
                    'options' => [
                        'Git secara otomatis akan memilih salah satu versi file secara acak lalu langsung menyelesaikan proses commit tanpa membutuhkan campur tangan apapun dari pengguna',
                        'Conflict artinya Git tidak bisa otomatis menggabungkan perubahan karena baris yang sama diubah berbeda di kedua sisi; pengguna harus membuka file tersebut, memutuskan versi yang benar secara manual, lalu menandainya selesai dengan git add sebelum commit',
                        'Conflict berarti repository menjadi rusak secara permanen, dan satu-satunya solusi yang tersedia adalah menghapus seluruh riwayat lalu membuat repository baru dari awal',
                        'Conflict hanya bisa terjadi pada file bertipe gambar atau biner, dan tidak akan pernah terjadi pada file teks seperti kode program apapun',
                    ],
                    'correct_option_index' => 1,
                    'explanation' => 'Conflict muncul saat Git tidak bisa otomatis menyatukan perubahan pada baris yang sama dari dua sumber berbeda. Git menandai bagian yang konflik langsung di dalam file (dengan marker <<<<<<<, =======, >>>>>>>), dan pengguna harus menghapus marker itu setelah memutuskan versi final, lalu git add file tersebut sebelum bisa menyelesaikan commit/merge.',
                ],
            ],
        ],
    ];
}
}
