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

                foreach ($content['scenarios'] as $i => $text) {
                    ScenarioConfidenceItem::create([
                        'career_id' => $career->id,
                        'scenario_text' => $text,
                        'order' => $i + 1,
                    ]);
                }

                $warmup = $content['warmup'];
                VerificationQuizQuestion::create([
                    'career_id' => $career->id,
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
                        'Debugging masalah saat form gagal ter-submit',
                        'Membuat layout responsif dari desain Figma',
                        'Mengoptimalkan performa halaman yang lambat dimuat',
                    ],
                    'warmup' => [
                        'question_text' => 'Perbaiki kode CSS berikut supaya elemen berada tepat di tengah (center).',
                        'code_snippet' => ".container {\n  display: ????;\n  justify-content: center;\n  align-items: center;\n}",
                        'options' => ['flex', 'block', 'inline'],
                        'correct_option_index' => 0,
                        'explanation' => 'display: flex mengaktifkan flexbox, sehingga justify-content dan align-items bisa dipakai untuk menengahkan elemen.',
                    ],
                    'quiz' => [
                        ['question_text' => 'Apa fungsi display:flex?', 'options' => ['Membuat elemen menjadi flexible container untuk layout', 'Menyembunyikan elemen dari tampilan', 'Membuat elemen menjadi gambar', 'Mengubah warna elemen'], 'correct_option_index' => 0, 'explanation' => 'display:flex mengaktifkan Flexbox, memudahkan pengaturan layout item di dalam container.'],
                        ['question_text' => 'Tag HTML apa yang digunakan untuk membuat link?', 'options' => ['<link>', '<a>', '<href>', '<url>'], 'correct_option_index' => 1, 'explanation' => 'Tag <a> (anchor) dipakai untuk membuat hyperlink, dengan atribut href untuk tujuannya.'],
                        ['question_text' => 'Properti CSS apa yang mengatur jarak DI DALAM elemen (antara konten dan border)?', 'options' => ['margin', 'padding', 'gap', 'spacing'], 'correct_option_index' => 1, 'explanation' => 'padding mengatur jarak di dalam elemen, sedangkan margin mengatur jarak di luar elemen.'],
                        ['question_text' => 'Bagaimana cara mendeklarasikan variabel yang nilainya tidak boleh diubah di JavaScript modern?', 'options' => ['var', 'let', 'const', 'static'], 'correct_option_index' => 2, 'explanation' => 'const mendeklarasikan variabel dengan referensi yang tidak bisa di-assign ulang.'],
                        ['question_text' => 'Apa fungsi properti justify-content pada Flexbox?', 'options' => ['Mengatur alignment horizontal item dalam flex container', 'Mengatur ukuran font', 'Mengatur warna background', 'Mengatur border radius'], 'correct_option_index' => 0, 'explanation' => 'justify-content mengatur posisi item sepanjang main axis (biasanya horizontal) dalam flex container.'],
                        ['question_text' => 'Method apa yang dipakai untuk memilih elemen HTML berdasarkan CSS selector di JavaScript?', 'options' => ['getElementById', 'querySelector', 'getAttribute', 'setAttribute'], 'correct_option_index' => 1, 'explanation' => 'querySelector menerima CSS selector (mis. ".class" atau "#id") dan mengembalikan elemen pertama yang cocok.'],
                        ['question_text' => 'Apa perbedaan utama CSS Grid dibanding Flexbox?', 'options' => ['Grid untuk layout 2 dimensi (baris & kolom), Flexbox untuk 1 dimensi', 'Grid lebih cepat dari Flexbox', 'Grid hanya bisa dipakai di mobile', 'Tidak ada bedanya sama sekali'], 'correct_option_index' => 0, 'explanation' => 'CSS Grid dirancang untuk layout dua dimensi, sedangkan Flexbox lebih cocok untuk satu dimensi.'],
                        ['question_text' => 'Apa itu event bubbling di JavaScript?', 'options' => ['Event yang menyebar dari elemen anak ke elemen induk', 'Event yang hanya bisa terjadi sekali', 'Event yang otomatis terhapus setelah dipakai', 'Event khusus untuk animasi CSS'], 'correct_option_index' => 0, 'explanation' => 'Event bubbling adalah mekanisme di mana event pada elemen anak akan "menggelembung" ke elemen induknya.'],
                        ['question_text' => 'Media query CSS digunakan untuk apa?', 'options' => ['Membuat desain responsif berdasarkan ukuran layar', 'Memutar video di halaman', 'Menjalankan query ke database', 'Membuat animasi otomatis'], 'correct_option_index' => 0, 'explanation' => 'Media query memungkinkan CSS diterapkan secara kondisional berdasarkan karakteristik perangkat.'],
                        ['question_text' => 'Apa perbedaan operator == dan === di JavaScript?', 'options' => ['=== membandingkan nilai DAN tipe data, == hanya membandingkan nilai', 'Tidak ada perbedaan sama sekali', '== lebih cepat dieksekusi', '=== hanya bisa dipakai untuk angka'], 'correct_option_index' => 0, 'explanation' => '=== (strict equality) memeriksa nilai dan tipe data, sedangkan == melakukan type coercion terlebih dahulu.'],
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
                        'Debugging API yang mengembalikan response sangat lambat',
                        'Merancang skema database untuk fitur baru dari nol',
                        'Menangani celah keamanan (SQL injection) pada endpoint publik',
                    ],
                    'warmup' => [
                        'question_text' => 'Perbaiki kode Express.js berikut supaya endpoint mengembalikan status 404 saat data tidak ditemukan.',
                        'code_snippet' => "app.get('/users/:id', (req, res) => {\n  const user = findUser(req.params.id);\n  if (!user) {\n    return res.status(????).json({ error: 'User not found' });\n  }\n  res.json(user);\n});",
                        'options' => ['404', '200', '500'],
                        'correct_option_index' => 0,
                        'explanation' => 'Status 404 Not Found dipakai ketika resource yang diminta tidak ditemukan di server.',
                    ],
                    'quiz' => [
                        ['question_text' => 'Apa fungsi middleware di Express.js?', 'options' => ['Menjalankan fungsi di antara request masuk dan response dikirim, misalnya logging atau autentikasi', 'Mengganti database', 'Membuat tampilan HTML', 'Menghapus route'], 'correct_option_index' => 0, 'explanation' => 'Middleware adalah fungsi yang dieksekusi di tengah siklus request-response, umum dipakai untuk logging, auth, atau validasi.'],
                        ['question_text' => 'Apa perbedaan utama SQL dan NoSQL database?', 'options' => ['SQL pakai skema tabel relasional, NoSQL lebih fleksibel (document/key-value)', 'SQL selalu lebih cepat dari NoSQL', 'NoSQL tidak bisa menyimpan banyak data', 'Tidak ada perbedaan'], 'correct_option_index' => 0, 'explanation' => 'Database SQL terstruktur dalam tabel dengan skema tetap, sedangkan NoSQL lebih fleksibel untuk data tidak terstruktur.'],
                        ['question_text' => 'Apa itu REST API?', 'options' => ['Arsitektur API yang memakai HTTP methods (GET/POST/PUT/DELETE) untuk operasi CRUD', 'Bahasa pemrograman backend', 'Jenis database', 'Framework frontend'], 'correct_option_index' => 0, 'explanation' => 'REST adalah gaya arsitektur API yang memanfaatkan method HTTP standar untuk operasi data.'],
                        ['question_text' => 'Apa fungsi JWT (JSON Web Token)?', 'options' => ['Menyimpan informasi user terenkripsi untuk autentikasi tanpa session di server', 'Memformat response JSON', 'Mengenkripsi database', 'Membuat query SQL'], 'correct_option_index' => 0, 'explanation' => 'JWT dipakai untuk autentikasi stateless — informasi user disimpan dalam token, bukan session server.'],
                        ['question_text' => 'Apa itu SQL injection?', 'options' => ['Serangan yang menyisipkan kode SQL berbahaya lewat input user yang tidak divalidasi', 'Cara mempercepat query SQL', 'Fitur bawaan MySQL', 'Teknik backup database'], 'correct_option_index' => 0, 'explanation' => 'SQL injection adalah celah keamanan akibat input user yang tidak di-sanitize, memungkinkan penyerang menjalankan query berbahaya.'],
                        ['question_text' => 'Apa fungsi index pada database?', 'options' => ['Mempercepat pencarian data dengan struktur data tambahan', 'Menghapus data duplikat otomatis', 'Mengenkripsi data', 'Membuat backup otomatis'], 'correct_option_index' => 0, 'explanation' => 'Index membantu database menemukan baris data lebih cepat tanpa harus scan seluruh tabel.'],
                        ['question_text' => 'Status code HTTP 401 berarti apa?', 'options' => ['Unauthorized — user belum terautentikasi', 'Server error', 'Request berhasil', 'Data tidak ditemukan'], 'correct_option_index' => 0, 'explanation' => '401 Unauthorized menandakan request butuh autentikasi yang valid.'],
                        ['question_text' => 'Kenapa environment variable penting untuk backend?', 'options' => ['Menyimpan konfigurasi sensitif (API key, password) di luar kode, supaya tidak ter-hardcode', 'Membuat kode berjalan lebih cepat', 'Wajib untuk semua bahasa pemrograman', 'Mengganti fungsi database'], 'correct_option_index' => 0, 'explanation' => 'Environment variable mencegah data sensitif ter-commit ke repository dan memudahkan konfigurasi per environment.'],
                        ['question_text' => 'Apa fungsi ORM (Object-Relational Mapping)?', 'options' => ['Memetakan objek di kode ke tabel database, sehingga query bisa ditulis dengan kode bukan SQL mentah', 'Mengoptimalkan gambar', 'Membuat API otomatis', 'Mengatur routing'], 'correct_option_index' => 0, 'explanation' => 'ORM seperti Eloquent atau Prisma memudahkan interaksi database lewat objek/kode, tanpa menulis SQL manual.'],
                        ['question_text' => 'Apa perbedaan HTTP method PUT dan PATCH?', 'options' => ['PUT mengganti seluruh resource, PATCH mengubah sebagian field saja', 'Tidak ada perbedaan', 'PUT hanya untuk membaca data', 'PATCH hanya untuk menghapus data'], 'correct_option_index' => 0, 'explanation' => 'PUT idealnya mengirim representasi lengkap resource, PATCH hanya mengirim field yang berubah.'],
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
                        'Mendesain ulang halaman checkout yang tingkat drop-off-nya tinggi',
                        'Melakukan riset pengguna untuk fitur yang belum pernah dibuat sebelumnya',
                        'Menjelaskan keputusan desain ke stakeholder non-teknis',
                    ],
                    'warmup' => [
                        'question_text' => 'Pilih prinsip desain yang paling tepat untuk meningkatkan keterbacaan teks pada latar belakang gelap.',
                        'code_snippet' => null,
                        'options' => ['Kontras warna teks-background tinggi', 'Ukuran font sekecil mungkin', 'Warna teks sama dengan background'],
                        'correct_option_index' => 0,
                        'explanation' => 'Kontras warna yang tinggi antara teks dan background penting untuk keterbacaan, sesuai standar aksesibilitas WCAG.',
                    ],
                    'quiz' => [
                        ['question_text' => 'Apa perbedaan UX dan UI?', 'options' => ['UX fokus pada pengalaman & alur pengguna, UI fokus pada tampilan visual antarmuka', 'UX dan UI adalah hal yang sama persis', 'UI mencakup riset pengguna, UX tidak', 'UX hanya soal warna'], 'correct_option_index' => 0, 'explanation' => 'UX (User Experience) mencakup keseluruhan pengalaman pengguna, sedangkan UI (User Interface) fokus pada elemen visual yang dilihat/disentuh.'],
                        ['question_text' => 'Apa itu wireframe?', 'options' => ['Sketsa/kerangka dasar layout halaman tanpa detail visual', 'Warna final sebuah desain', 'Kode HTML sebuah halaman', 'Logo aplikasi'], 'correct_option_index' => 0, 'explanation' => 'Wireframe adalah representasi struktural sederhana dari sebuah halaman, biasanya hitam-putih tanpa detail visual.'],
                        ['question_text' => 'Apa fungsi user persona dalam UX research?', 'options' => ['Merepresentasikan karakteristik target pengguna untuk memandu keputusan desain', 'Nama produk yang akan dirilis', 'Jenis font yang dipakai', 'Warna brand utama'], 'correct_option_index' => 0, 'explanation' => 'Persona membantu tim desain berempati dan mengambil keputusan berdasarkan kebutuhan pengguna nyata.'],
                        ['question_text' => 'Apa itu design system?', 'options' => ['Kumpulan komponen, aturan, dan pedoman desain yang konsisten dipakai di seluruh produk', 'Software untuk membuat wireframe', 'Warna tunggal yang dipakai di logo', 'Jenis file desain'], 'correct_option_index' => 0, 'explanation' => 'Design system memastikan konsistensi visual dan fungsional di seluruh produk, mempercepat kerja tim desain-developer.'],
                        ['question_text' => 'Apa fungsi usability testing?', 'options' => ['Menguji apakah pengguna nyata bisa menggunakan produk dengan mudah', 'Mengecek kecepatan loading server', 'Menghitung jumlah pengguna aktif', 'Membuat laporan keuangan'], 'correct_option_index' => 0, 'explanation' => 'Usability testing mengamati pengguna nyata berinteraksi dengan produk untuk menemukan masalah UX sebelum rilis.'],
                        ['question_text' => 'Apa itu accessibility (a11y) dalam desain?', 'options' => ['Memastikan produk bisa digunakan pengguna dengan berbagai keterbatasan, termasuk disabilitas', 'Membuat produk hanya untuk pengguna teknis', 'Fitur premium berbayar', 'Desain yang hanya berlaku di mobile'], 'correct_option_index' => 0, 'explanation' => 'Accessibility memastikan inklusivitas, termasuk untuk pengguna dengan gangguan penglihatan, pendengaran, atau motorik.'],
                        ['question_text' => 'Apa fungsi color contrast ratio?', 'options' => ['Mengukur keterbacaan teks terhadap background sesuai standar WCAG', 'Menentukan jumlah warna dalam palette', 'Mengatur kecepatan animasi', 'Mengukur resolusi layar'], 'correct_option_index' => 0, 'explanation' => 'Contrast ratio yang cukup tinggi wajib dipenuhi supaya teks tetap terbaca oleh semua pengguna, termasuk yang low vision.'],
                        ['question_text' => 'Apa itu information architecture?', 'options' => ['Struktur pengorganisasian dan pelabelan konten agar mudah dinavigasi', 'Server tempat aplikasi di-hosting', 'Bahasa pemrograman backend', 'Jenis font untuk heading'], 'correct_option_index' => 0, 'explanation' => 'Information architecture menentukan bagaimana konten disusun dan diberi label supaya pengguna mudah menemukan yang dicari.'],
                        ['question_text' => 'Apa perbedaan low-fidelity dan high-fidelity prototype?', 'options' => ['Low-fidelity sketsa kasar tanpa detail, high-fidelity mendekati tampilan final', 'Low-fidelity dibuat di kode, high-fidelity di kertas', 'Tidak ada perbedaan', 'High-fidelity selalu lebih cepat dibuat'], 'correct_option_index' => 0, 'explanation' => 'Low-fidelity dipakai untuk validasi ide cepat, high-fidelity untuk menguji tampilan dan interaksi mendekati produk final.'],
                        ['question_text' => 'Apa fungsi A/B testing dalam UX?', 'options' => ['Membandingkan dua versi desain untuk melihat mana yang performanya lebih baik', 'Menguji kecepatan server', 'Mengecek bug di kode', 'Membuat dua akun testing'], 'correct_option_index' => 0, 'explanation' => 'A/B testing membandingkan dua varian desain ke pengguna nyata untuk mengambil keputusan berbasis data.'],
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
                        'Container aplikasi tiba-tiba crash di production',
                        'Pipeline CI/CD gagal setelah push ke branch main',
                        'Mengoptimalkan resource usage cluster Kubernetes yang overload',
                    ],
                    'warmup' => [
                        'question_text' => 'Lengkapi Dockerfile berikut supaya direktori kerja container ter-set dengan benar.',
                        'code_snippet' => "FROM node:20\nWORKDIR ????\nCOPY package.json .\nRUN npm install",
                        'options' => ['/app', 'npm', 'docker'],
                        'correct_option_index' => 0,
                        'explanation' => 'WORKDIR menentukan direktori kerja di dalam container, biasanya diisi path seperti /app.',
                    ],
                    'quiz' => [
                        ['question_text' => 'Apa fungsi Docker container?', 'options' => ['Mengemas aplikasi beserta dependensinya agar konsisten berjalan di lingkungan manapun', 'Membuat backup database', 'Mengganti sistem operasi server', 'Mempercepat koneksi internet'], 'correct_option_index' => 0, 'explanation' => 'Container membungkus aplikasi dan dependensinya sehingga bisa berjalan konsisten di berbagai environment.'],
                        ['question_text' => 'Apa perbedaan Docker image dan container?', 'options' => ['Image adalah blueprint/template, container adalah instance yang berjalan dari image tersebut', 'Image dan container adalah hal yang sama', 'Container dipakai untuk membuat image', 'Image hanya bisa dipakai sekali'], 'correct_option_index' => 0, 'explanation' => 'Image bersifat statis (template), sedangkan container adalah proses berjalan yang dibuat dari image tersebut.'],
                        ['question_text' => 'Apa fungsi Pod di Kubernetes?', 'options' => ['Unit terkecil deployment di Kubernetes, berisi satu atau lebih container', 'Server fisik tempat Kubernetes berjalan', 'Nama lain dari Docker image', 'Command line tool Kubernetes'], 'correct_option_index' => 0, 'explanation' => 'Pod adalah unit deployment terkecil di Kubernetes yang membungkus satu atau lebih container yang saling terkait.'],
                        ['question_text' => 'Apa itu CI/CD?', 'options' => ['Continuous Integration/Continuous Deployment — otomasi build, test, dan deploy kode', 'Jenis database khusus DevOps', 'Command line tool untuk Docker', 'Protokol keamanan server'], 'correct_option_index' => 0, 'explanation' => 'CI/CD mengotomasi proses integrasi kode, testing, hingga deployment agar rilis lebih cepat dan konsisten.'],
                        ['question_text' => 'Apa fungsi Kubernetes Service?', 'options' => ['Menyediakan endpoint jaringan stabil untuk mengakses sekumpulan Pod', 'Menghapus Pod yang error otomatis', 'Membuat Dockerfile otomatis', 'Mengatur billing cloud'], 'correct_option_index' => 0, 'explanation' => 'Service memberikan alamat jaringan tetap meskipun Pod di baliknya berganti-ganti (scaling, restart, dll).'],
                        ['question_text' => 'Kenapa environment variable penting di Docker?', 'options' => ['Mengatur konfigurasi container tanpa mengubah image, seperti API key atau mode aplikasi', 'Mempercepat build image', 'Wajib ada di setiap Dockerfile', 'Mengganti fungsi volume'], 'correct_option_index' => 0, 'explanation' => 'Environment variable memungkinkan konfigurasi berbeda (dev/staging/production) tanpa membangun ulang image.'],
                        ['question_text' => 'Apa fungsi Horizontal Pod Autoscaler (HPA)?', 'options' => ['Otomatis menambah/mengurangi jumlah Pod berdasarkan beban', 'Mengatur ukuran disk Pod', 'Membuat Pod baru secara manual', 'Menghapus namespace yang tidak dipakai'], 'correct_option_index' => 0, 'explanation' => 'HPA menyesuaikan jumlah replika Pod secara otomatis mengikuti metrik seperti CPU atau memory usage.'],
                        ['question_text' => 'Apa fungsi volume di Docker?', 'options' => ['Menyimpan data secara persisten di luar lifecycle container', 'Mempercepat startup container', 'Mengatur jaringan antar container', 'Membatasi CPU container'], 'correct_option_index' => 0, 'explanation' => 'Volume memastikan data tetap ada meskipun container dihapus atau diganti dengan yang baru.'],
                        ['question_text' => 'Apa itu Infrastructure as Code (IaC)?', 'options' => ['Mengelola infrastruktur server lewat kode/konfigurasi, bukan setup manual', 'Menulis dokumentasi server', 'Jenis bahasa pemrograman backend', 'Command khusus Kubernetes'], 'correct_option_index' => 0, 'explanation' => 'IaC (mis. Terraform) memungkinkan infrastruktur didefinisikan sebagai kode, sehingga mudah direplikasi dan di-versioning.'],
                        ['question_text' => 'Apa fungsi load balancer?', 'options' => ['Mendistribusikan traffic ke beberapa server/instance agar beban merata', 'Mengurangi ukuran file gambar', 'Meng-compile kode aplikasi', 'Menghapus log lama otomatis'], 'correct_option_index' => 0, 'explanation' => 'Load balancer mencegah satu server kelebihan beban dengan mendistribusikan traffic secara merata.'],
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
                        'Data yang diterima punya banyak missing values dan duplikat',
                        'Stakeholder minta insight cepat dari dataset yang sangat besar',
                        'Hasil analisis menunjukkan korelasi yang tidak sesuai ekspektasi bisnis',
                    ],
                    'warmup' => [
                        'question_text' => 'Lengkapi kode Python (pandas) berikut untuk menghapus baris duplikat.',
                        'code_snippet' => "import pandas as pd\ndf = pd.read_csv('data.csv')\ndf = df.????()",
                        'options' => ['drop_duplicates', 'remove_duplicates', 'delete_duplicates'],
                        'correct_option_index' => 0,
                        'explanation' => 'pandas menyediakan method drop_duplicates() untuk menghapus baris yang datanya duplikat.',
                    ],
                    'quiz' => [
                        ['question_text' => 'Apa fungsi library pandas di Python?', 'options' => ['Mengolah dan menganalisis data terstruktur (tabel) dengan mudah', 'Membuat website', 'Mengirim email otomatis', 'Compile kode C++'], 'correct_option_index' => 0, 'explanation' => 'pandas adalah library Python paling umum untuk manipulasi dan analisis data tabular (DataFrame).'],
                        ['question_text' => 'Apa fungsi GROUP BY di SQL?', 'options' => ['Mengelompokkan baris data berdasarkan kolom tertentu untuk agregasi (SUM, COUNT, dll)', 'Mengurutkan data secara alfabetis', 'Menghapus baris duplikat', 'Membuat tabel baru'], 'correct_option_index' => 0, 'explanation' => 'GROUP BY mengelompokkan baris dengan nilai sama pada kolom tertentu, biasa dipakai bersama fungsi agregasi.'],
                        ['question_text' => 'Apa itu korelasi dalam statistik?', 'options' => ['Ukuran seberapa kuat hubungan antara dua variabel', 'Rata-rata dari sekumpulan data', 'Jumlah total data dalam dataset', 'Nilai maksimum dalam data'], 'correct_option_index' => 0, 'explanation' => 'Korelasi mengukur kekuatan dan arah hubungan linear antara dua variabel, biasanya bernilai -1 sampai 1.'],
                        ['question_text' => 'Apa fungsi JOIN di SQL?', 'options' => ['Menggabungkan data dari dua tabel atau lebih berdasarkan kolom terkait', 'Menghapus tabel', 'Membuat index otomatis', 'Mengubah tipe data kolom'], 'correct_option_index' => 0, 'explanation' => 'JOIN menggabungkan baris dari beberapa tabel berdasarkan relasi kolom yang cocok (mis. foreign key).'],
                        ['question_text' => 'Apa itu outlier dalam data?', 'options' => ['Nilai data yang jauh berbeda dari sebagian besar data lainnya', 'Data yang hilang/kosong', 'Data yang paling sering muncul', 'Rata-rata dari dataset'], 'correct_option_index' => 0, 'explanation' => 'Outlier adalah data ekstrem yang berbeda jauh dari pola umum, bisa jadi kesalahan input atau kejadian nyata yang penting.'],
                        ['question_text' => 'Apa fungsi library matplotlib/seaborn di Python?', 'options' => ['Membuat visualisasi data seperti grafik dan chart', 'Mengelola database', 'Membuat API', 'Mengirim notifikasi'], 'correct_option_index' => 0, 'explanation' => 'matplotlib dan seaborn adalah library visualisasi data paling umum dipakai di ekosistem Python.'],
                        ['question_text' => 'Apa perbedaan mean, median, dan mode?', 'options' => ['Mean = rata-rata, median = nilai tengah, mode = nilai yang paling sering muncul', 'Ketiganya sama saja', 'Mean = nilai tengah, median = rata-rata', 'Mode = nilai maksimum'], 'correct_option_index' => 0, 'explanation' => 'Ketiganya adalah ukuran tendensi sentral yang berbeda cara hitungnya dan cocok untuk situasi data yang berbeda.'],
                        ['question_text' => 'Apa fungsi normalisasi data?', 'options' => ['Mengubah skala data agar berada dalam rentang yang sama untuk analisis yang adil', 'Menghapus semua data kosong', 'Mengubah data jadi teks', 'Menggandakan jumlah data'], 'correct_option_index' => 0, 'explanation' => 'Normalisasi penting terutama untuk model machine learning, supaya fitur dengan skala besar tidak mendominasi.'],
                        ['question_text' => 'Kenapa missing value perlu ditangani sebelum analisis?', 'options' => ['Karena bisa mengganggu keakuratan hasil analisis dan statistik', 'Karena selalu bikin program error', 'Karena tidak berpengaruh sama sekali', 'Karena harus dihapus semua tanpa terkecuali'], 'correct_option_index' => 0, 'explanation' => 'Missing value yang tidak ditangani dengan tepat bisa membuat hasil analisis bias atau tidak akurat.'],
                        ['question_text' => 'Apa fungsi pivot table?', 'options' => ['Meringkas dan mengagregasi data dalam bentuk tabel silang untuk analisis lebih mudah', 'Mengubah data jadi grafik 3D', 'Menghapus kolom yang tidak dipakai', 'Membuat koneksi ke API eksternal'], 'correct_option_index' => 0, 'explanation' => 'Pivot table merangkum data mentah menjadi tabel ringkasan yang mudah dibaca, umum dipakai di Excel maupun pandas.'],
                    ],
                ],
            ];
        }
    }