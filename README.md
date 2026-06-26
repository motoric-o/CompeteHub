# CompeteHub

CompeteHub adalah aplikasi web berbasis Laravel untuk membantu proses pengelolaan kompetisi secara end-to-end, mulai dari publikasi kompetisi, pendaftaran peserta, manajemen tim, verifikasi administrasi, penilaian oleh juri, hingga leaderboard. Aplikasi ini dibuat sebagai project perkuliahan dengan fokus pada penerapan arsitektur Laravel, role-based access, workflow registrasi, dan beberapa design pattern.

## Anggota Kelompok

| Nama | NRP |
|---|---:|
| Valentino Hose | 2472026 |
| Felicia Ivanna Widian | 2472030 |
| Jeryko Farelin Heliandra | 2472032 |
| Rico Dharmawan | 2472041 |

## Fitur Utama

### 1. Autentikasi dan Role-Based Access

Aplikasi memiliki sistem login, register, profile, dan pembagian akses berdasarkan role:

| Role | Hak Akses Utama |
|---|---|
| Committee | Mengelola kompetisi, pendaftaran, dokumen, pembayaran, ronde, bracket, juri, scoring criteria, notification log, dan command center |
| Judge | Melihat submisi yang ditugaskan, membuka antrean penilaian, dan memberikan skor |
| Participant | Melihat kompetisi, membuat atau bergabung ke tim, mendaftar lomba, upload dokumen/pembayaran, submit karya, dan melihat status |

### 2. Manajemen Kompetisi

Committee dapat membuat, mengubah, melihat, dan menghapus data kompetisi. Setiap kompetisi memiliki data seperti nama, deskripsi, jenis kompetisi, kategori, kuota, biaya registrasi, tanggal registrasi, tanggal lomba, status, serta tipe penilaian.

Kategori yang digunakan di aplikasi antara lain:

- Web Development
- Competitive Programming
- UI/UX Design
- Capture The Flag
- Other

### 3. Dynamic Registration Form

Committee dapat membuat template form registrasi sesuai kebutuhan setiap kompetisi. Form dapat berisi field dinamis, lalu peserta mengisi form tersebut ketika mendaftar. Sistem juga memiliki fitur preview agar committee dapat melihat bentuk form sebelum digunakan peserta.

### 4. Registration Validation Workflow

Sistem mendukung workflow validasi pendaftaran, termasuk:

- pengecekan kelengkapan data peserta,
- upload dokumen registrasi,
- upload bukti pembayaran,
- verifikasi dokumen oleh committee,
- verifikasi pembayaran,
- approval atau rejection pendaftaran,
- reupload dokumen atau pembayaran jika ada data yang kurang sesuai.

Pada Week 4, aplikasi juga memiliki pre-check service yang membantu mendeteksi masalah sebelum peserta submit final, misalnya field wajib kosong, dokumen belum diunggah, format file tidak sesuai, atau bukti pembayaran belum tersedia.

### 5. Competition Command Center

Command Center membantu committee memantau kondisi kompetisi dalam satu halaman. Informasi yang ditampilkan mencakup ringkasan status registrasi, pembayaran pending, dokumen pending, rejected registration, readiness score, dan bottleneck workflow yang perlu ditangani.

Fitur ini berguna agar committee tidak perlu mengecek data satu per satu dari banyak halaman.

### 6. One-Click Review Action

Committee dapat melakukan aksi review secara cepat, seperti:

- approve registrasi,
- reject registrasi dengan alasan,
- mengirim reminder ke peserta,
- bulk validate untuk beberapa registrasi sekaligus.

Setiap aksi penting dicatat ke audit log agar proses review lebih mudah ditelusuri.

### 7. Notification Log dan Broadcast Email

Aplikasi menyimpan riwayat notifikasi yang pernah dikirim ke peserta, termasuk reminder dan broadcast. Committee dapat melihat status notifikasi, penerima, channel, subject, dan waktu pengiriman.

### 8. Manajemen Tim

Participant dapat membuat tim, bergabung ke tim menggunakan join code, keluar dari tim, mengeluarkan anggota, serta membuat ulang join code. Fitur ini digunakan untuk kompetisi bertipe team.

### 9. Round, Bracket, dan Winner

Committee dapat mengelola ronde kompetisi dan bracket pertandingan. Sistem mendukung pembuatan bracket otomatis, input bracket manual, penghapusan bracket, serta penentuan pemenang pada bracket tertentu.

### 10. Scoring dan Leaderboard

Judge dapat memberikan skor pada submisi peserta berdasarkan ronde dan kriteria penilaian. Leaderboard menampilkan peringkat peserta atau tim berdasarkan hasil penilaian dan dapat diakses melalui halaman leaderboard.

### 11. Submission Peserta

Participant dapat mengirim submisi untuk ronde tertentu. Submisi dapat berupa link atau file sesuai kebutuhan kompetisi. Data submisi kemudian dapat dinilai oleh judge yang ditugaskan.

### 12. Sertifikat

Peserta yang sudah memenuhi kondisi tertentu dapat mengunduh sertifikat dalam bentuk PDF.

## Fokus Pengembangan Week 4

Fokus utama Week 4 adalah memperkuat workflow intelligence dan proses validasi operasional. Beberapa bagian yang ditambahkan atau diperkuat adalah:

- Command Center untuk memantau bottleneck kompetisi.
- Registration Pre-Check untuk validasi awal sebelum peserta submit data.
- Template Quality Analyzer untuk mengecek kualitas form template.
- One-Click Review Action untuk approve, reject, reminder, dan bulk validation.
- Notification Log untuk mencatat riwayat pengiriman notifikasi.
- Audit Log untuk mencatat aksi penting committee.
- Registration State Resolver untuk menentukan status dan next action peserta.

## Tech Stack

| Bagian | Teknologi |
|---|---|
| Backend | Laravel |
| Bahasa | PHP |
| Frontend | Blade, Tailwind CSS, Alpine.js |
| Build Tool | Vite |
| Database | PostgreSQL sesuai `.env.example` |
| PDF | barryvdh/laravel-dompdf |
| Authentication | Laravel Breeze |
| Queue | Laravel Queue |
| Package Manager | Composer dan NPM |

## Struktur Folder Penting

```text
CompeteHub-week4/
├── app/
│   ├── Core/                 # Core competition dan scoring logic
│   ├── Factories/            # Factory pattern untuk pembuatan competition
│   ├── Http/Controllers/     # Controller committee, judge, participant, auth
│   ├── Models/               # Model Eloquent
│   ├── Patterns/             # Implementasi design pattern
│   ├── Services/             # Business logic aplikasi
│   └── States/               # Resolver status registrasi dan next action
├── database/
│   ├── migrations/           # Struktur tabel database
│   └── seeders/              # Data awal untuk demo
├── resources/
│   ├── css/                  # Styling utama
│   ├── js/                   # JavaScript frontend
│   └── views/                # Blade views
├── routes/
│   └── web.php               # Routing aplikasi
└── tests/                    # Feature dan unit test
```

## Design Pattern yang Digunakan

| Pattern | Lokasi | Fungsi |
|---|---|---|
| Factory | `app/Factories/CompetitionFactory.php`, `app/Services/Competition/CompetitionFactory.php` | Membuat object kompetisi berdasarkan tipe individual atau team |
| Strategy | `app/Core/Scoring/` | Memisahkan strategi penilaian, misalnya judge-based scoring dan time-based scoring |
| Observer | `app/Patterns/Observer/` | Menjalankan aksi lanjutan ketika scoring berubah, seperti update leaderboard atau notifikasi |
| Proxy | `app/Patterns/Proxy/RoleAccessProxy.php`, `app/Services/Scoring/ScoreProxy.php` | Membatasi akses berdasarkan role atau aturan penilaian |
| Iterator | `app/Patterns/Iterator/SubmissionQueueIterator.php` | Mengatur antrean submisi yang perlu dinilai judge |
| Facade | `app/Services/Facade/` | Menyederhanakan akses ke layanan notifikasi, email, storage, dan PDF |
| Chain of Responsibility | `app/Services/Validation/` | Memvalidasi registrasi secara bertahap, seperti status akun, dokumen, dan pembayaran |
| Command | `app/Services/Review/ReviewCommandExecutor.php` | Menangani aksi review seperti approve, reject, dan reminder |
| State | `app/States/RegistrationStateResolver.php` | Menentukan status registrasi dan tindakan berikutnya yang perlu dilakukan |

## Cara Instalasi

Pastikan perangkat sudah memiliki:

- PHP sesuai kebutuhan Laravel project
- Composer
- Node.js dan NPM
- PostgreSQL

Clone repository:

```bash
git clone <url-repository>
cd CompeteHub-week4
```

Install dependency PHP:

```bash
composer install
```

Install dependency frontend:

```bash
npm install
```

Buat file environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Atur konfigurasi database di file `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=competehub
DB_USERNAME=postgres
DB_PASSWORD=
```

Jalankan migration dan seeder:

```bash
php artisan migrate:fresh --seed
```

Buat storage link untuk file upload:

```bash
php artisan storage:link
```

Jalankan server Laravel:

```bash
php artisan serve
```

Jalankan Vite di terminal lain:

```bash
npm run dev
```

Jika ingin menjalankan server, queue, dan Vite sekaligus, gunakan:

```bash
composer run dev
```

Aplikasi dapat dibuka melalui:

```text
http://127.0.0.1:8000
```

## Akun Demo

Setelah menjalankan seeder, beberapa akun demo yang dapat digunakan adalah:

| Role | Email | Password |
|---|---|---|
| Committee | valentino@competehub.com | password |
| Committee | felicia@competehub.com | password |
| Judge | jeryko@competehub.com | password |
| Judge | rico@competehub.com | password |
| Participant | budi@gmail.com | password |
| Participant | siti@gmail.com | password |
| Participant | andi@gmail.com | password |

## Alur Penggunaan Singkat

### Committee

1. Login sebagai committee.
2. Masuk ke dashboard committee.
3. Buat atau kelola kompetisi.
4. Buat form template registrasi.
5. Cek pendaftaran peserta.
6. Verifikasi dokumen dan pembayaran.
7. Gunakan Command Center untuk melihat bottleneck.
8. Approve, reject, atau kirim reminder ke peserta.
9. Atur ronde, bracket, scoring criteria, dan assignment juri.
10. Pantau leaderboard dan notification log.

### Participant

1. Register atau login sebagai participant.
2. Lihat daftar kompetisi yang tersedia.
3. Buat tim atau gabung ke tim jika kompetisi bertipe team.
4. Isi form registrasi.
5. Upload dokumen dan bukti pembayaran jika diperlukan.
6. Pantau status registrasi.
7. Reupload dokumen atau pembayaran jika diminta.
8. Submit karya pada ronde kompetisi.
9. Lihat leaderboard atau unduh sertifikat jika tersedia.

### Judge

1. Login sebagai judge.
2. Lihat daftar submisi atau kompetisi yang ditugaskan.
3. Buka antrean penilaian.
4. Berikan skor sesuai kriteria.
5. Sistem akan memperbarui data penilaian dan leaderboard.

## Route Penting

| Halaman | URL |
|---|---|
| Home | `/` |
| Login | `/login` |
| Dashboard | `/dashboard` |
| Team Management | `/teams` |
| Committee Competitions | `/committee/competitions` |
| Committee Management Competitions | `/committee/management/competitions` |
| Judge Submissions | `/judge/submissions` |
| Participant Competitions | `/participant/competitions` |
| Participant Registrations | `/participant/registrations` |
| Leaderboards | `/leaderboards` |

## Testing

Jalankan seluruh test:

```bash
php artisan test
```

Jalankan test khusus workflow intelligence:

```bash
php artisan test --filter=WorkflowIntelligenceTest
```

Test yang tersedia mencakup beberapa bagian penting, seperti:

- template quality analyzer,
- registration pre-check service,
- command center service,
- review command executor,
- AJAX pre-check endpoint,
- command center view,
- notification log view.

## Troubleshooting

### 1. Halaman tidak memiliki style

Pastikan Vite sudah berjalan:

```bash
npm run dev
```

Jika masih bermasalah, coba build ulang asset:

```bash
npm run build
```

### 2. Error database connection

Pastikan PostgreSQL aktif dan konfigurasi `.env` sudah sesuai:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=competehub
DB_USERNAME=postgres
DB_PASSWORD=
```

Setelah mengubah `.env`, jalankan:

```bash
php artisan config:clear
php artisan cache:clear
```

### 3. Data demo belum muncul

Jalankan ulang migration dan seeder:

```bash
php artisan migrate:fresh --seed
```

### 4. Notifikasi atau queue tidak berjalan

Jalankan queue listener:

```bash
php artisan queue:listen
```

Atau gunakan:

```bash
composer run dev
```

## Status Project

Project ini merupakan aplikasi akademik untuk simulasi platform kompetisi. Fitur utama sudah mencakup alur utama dari sisi committee, judge, dan participant, serta dilengkapi data dummy untuk kebutuhan demo dan pengujian.
