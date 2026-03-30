# E-Budgeting API - Sistem Manajemen Reimbursement

Proyek ini adalah sistem backend berbasis RESTful API yang dirancang untuk mengelola permohonan dana operasional dan _reimbursement_ karyawan. Tujuan utama sistem ini adalah untuk menggantikan birokrasi kertas yang rentan hilang, mempercepat proses persetujuan, dan mencegah pengeluaran divisi yang melebihi batas anggaran.

## Arsitektur Teknologi

Sistem ini menggunakan arsitektur _Client-Server_:

- **Backend:** Menggunakan kerangka kerja **Laravel** untuk memproses logika bisnis, mengelola _database_ terpusat, dan menyediakan _endpoint_ API. Keamanan akses diatur menggunakan **Laravel Sanctum**.
- **Frontend:** Aplikasi seluler berbasis **Flutter** yang berfungsi sebagai _thin client_ agar staf dapat mengunggah pengajuan langsung dari lapangan.

## Fitur Inti (MVP)

- **Pre-emptive Budget Locking:** Validasi sistem yang akan memblokir pengajuan secara otomatis apabila nominal yang dimasukkan melebihi sisa anggaran divisi terkait.
- **Dukungan Multi-Tahun:** Skema _database_ yang dirancang untuk menyimpan riwayat anggaran dari berbagai tahun secara berdampingan tanpa perlu membuat sistem atau _database_ baru setiap pergantian tahun.
- **Digitalisasi Bukti Transaksi:** Mewajibkan pengguna untuk melampirkan foto struk fisik ke dalam sistem penyimpanan yang aman.
- **Alur Persetujuan Transparan:** Memungkinkan staf untuk melihat status pengajuan mereka (Pending, Approved, atau Rejected) secara langsung melalui aplikasi.
- **Jejak Audit (Audit Trail):** Sistem keamanan yang mencatat setiap aksi dari manajer atau admin saat menyetujui atau menolak dokumen, lengkap dengan alasan dan waktu eksekusi untuk akuntabilitas.
- **Laporan Otomatis (Ekspor PDF):** Fitur untuk merangkum seluruh pengeluaran yang telah disetujui menjadi dokumen PDF untuk kebutuhan Laporan Pertanggungjawaban (LPJ).

# Log Perubahan (Changelog)

### [30 Maret 2026] - Enterprise Year-End Closing, Automated Archiving & Data Freeze Protocol

**By:** @jevonkagenou

- **Automated Year-End Closing Engine:** Implementasi _Console Command_ khusus (`YearEndClosing`) yang diintegrasikan dengan Laravel _Task Scheduler_ (Cron Job) untuk mengeksekusi siklus tutup buku secara otomatis di latar belakang (_background_) setiap tanggal 31 Desember pukul 23:59 WIB.
- **Corporate-Grade PDF Archiving:** Merancang _template_ Laporan Eksekutif Kinerja & Efisiensi Anggaran menggunakan `dompdf`. Desain mengusung tema _Corporate Minimalist_ (Kop surat resmi bergaya _Serif_, blok tanda tangan, identitas dokumen dinamis) yang di-_generate_ otomatis dan diarsipkan secara fisik di _storage_ server.
- **Historical Data Freeze Protocol:** Pemasangan gembok keamanan mutlak (_Read-Only_) pada _Controller_ dan antarmuka _Blade_ untuk entitas _Budget_ dan _Fiscal Year_. Mencegah segala bentuk modifikasi (Edit/Delete) pada data historis ketika Tahun Anggaran telah berstatus Non-Aktif.
- **Mobile API Expiration Validation:** Sinkronisasi tingkat keamanan pada _Mobile API (Reimbursement Store)_ dengan menambahkan lapis validasi presisi terhadap batas waktu kedaluwarsa (_end_date_) dompet anggaran dan masa aktif tahun buku.
- **Advanced Table UX Refinement:** Restrukturisasi tata letak tabel Manajemen Anggaran Web; mencabut atribut _nowrap_ untuk mengeliminasi _horizontal scroll_, menggabungkan kolom Divisi & Kategori secara vertikal (stacking), dan mengekspos tombol aksi _inline_ demi efisiensi interaksi Admin.
- **Annual Record Management:** Pembuatan model dan migrasi terdedikasi (`annual_reports`) untuk menyimpan meta-data kalkulasi akhir tahun (Total Pagu, Realisasi, Efisiensi/Sisa Kas) beserta tautan unduhan file fisik PDF Laporan Tahunan.

---

### [25 Maret 2026] - Enterprise API Foundation, Secure Identity Management & UX Refinement

**By:** @jevonkagenou

- **Mobile API Engine (Sanctum):** Implementasi _RESTful API_ komprehensif menggunakan Laravel Sanctum untuk mendukung aplikasi _mobile_, mencakup otentikasi (Login/Logout) yang aman dan _Token Management_.
- **Financial Core API & Data Aggregation:** Pengembangan _endpoint_ pengajuan dana (_multipart/form-data_) dan penyatuan data Dasbor terpadu (metrik saldo, profil, riwayat) dalam satu _response_ untuk meminimalisir _latency_ pada _mobile client_.
- **Race Condition Protection (API):** Sinkronisasi tingkat keamanan _Enterprise_ pada rute persetujuan (Approve) API menggunakan `DB::transaction()` dan `lockForUpdate()`, memastikan integritas pemotongan saldo divisi terhindar dari _double-spending_.
- **Cross-Platform Compliance Validation:** Penyelarasan _Business Logic_ secara mutlak antara _Web Controller_ dan _API Controller_, mewajibkan lampiran struk fisik (gambar) dan deskripsi untuk setiap pengajuan demi kepatuhan _audit trail_ perusahaan.
- **Secure Identity Management:** Pembuatan antarmuka dan logika pengelolaan Profil Web dengan sistem verifikasi berlapis, mewajibkan otentikasi kata sandi lama (_Current Password validation_) sebelum mengizinkan pembaruan kredensial.
- **Navigational Architecture Refactor:** Restrukturisasi hierarki UI dengan memusatkan menu personal ke _Dropdown Navbar_ (menggunakan _Flexbox Alignment_ untuk presisi visual), serta membersihkan _Sidebar_ agar eksklusif untuk modul operasional bisnis.
- **Seamless Data Presentation:** Pembaruan antarmuka tabel Dasbor dengan mengintegrasikan _Bootstrap Modal_ (menggantikan elemen _Collapse/SweetAlert_) untuk merender teks penolakan yang panjang secara terisolasi tanpa merusak struktur _grid_ tabel.
- **Event Handling Resolution:** Resolusi anomali _JavaScript event handler_ (`undefined event`) pada eksekusi _Logout_ Web, memastikan penahan _SweetAlert2_ beroperasi sempurna sebelum melakukan destruksi sesi secara _server-side_.

---

### [23 Maret 2026] - Enterprise Architecture, Soft Deletes, & Advanced UI Integration

**By:** @jevonkagenou

- **Database Normalization & Multi-Year Support:** Pemisahan tabel `fiscal_years` dan `budget_categories` untuk mendukung arsitektur _enterprise_ sehingga mencegah fragmentasi data lintas tahun anggaran.
- **Master Data Management Expansion:** Penambahan antarmuka, kontroler, rute, dan menu Sidebar untuk mengelola data master _Tahun Anggaran_ dan _Kategori Anggaran_ secara penuh menggunakan sistem _Modal_ Bootstrap.
- **Pre-emptive Budget Locking:** Implementasi pengamanan tingkat lanjut menggunakan `DB::transaction()` dan `lockForUpdate()` pada proses _approval_ untuk mengunci baris data di PostgreSQL, memastikan saldo divisi aman dari kebocoran akibat _race condition_.
- **Automated LPJ PDF Generation:** Integrasi `barryvdh/laravel-dompdf` untuk mencetak Laporan Pertanggungjawaban. Dilengkapi dengan desain _Modern Corporate_ khusus ukuran A4 Landscape dan kapabilitas filter berdasarkan rentang tanggal persetujuan.
- **Context-Aware UI/UX:** Optimalisasi antarmuka pengguna dengan menyembunyikan tombol aksi ke dalam menu _dropdown_ pada tabel yang padat data (mencegah _horizontal scroll_), namun mempertahankan tombol langsung pada tabel master untuk efisiensi klik.
- **Seeder Synchronization:** Restrukturisasi total pada `BudgetSeeder` dan `ReimbursementSeeder` agar sepenuhnya kompatibel dengan skema UUID relasional yang baru dan terintegrasi mulus dengan _Master Data_.
- **Multi-Division Manager Architecture:** Implementasi relasi _Many-to-Many_ melalui tabel pivot `manager_divisions` (UUID-based), memungkinkan satu akun level Manajer untuk mengawasi dan menyetujui anggaran dari banyak divisi sekaligus.
- **Enterprise-Grade Form UI:** Integrasi _library_ Select2 untuk pencarian _dropdown_ yang intuitif (_Single_ & _Multi-select_) di dalam Modal Bootstrap, serta penerapan _Inline Truncation_ dengan Tooltip untuk merapikan tampilan _badge_ data yang panjang.
- **Data Integrity & Soft Deletes Implementation:** Mengamankan seluruh data historis dan laporan (LPJ) dari kerusakan akibat penghapusan data master (_Cascade Delete_) dengan menerapkan `SoftDeletes` secara global dan mengamankan relasi menggunakan `withTrashed()`.
- **Query Optimization & Bug Fixes:** Resolusi _N+1 query problem_ via _eager loading_, perbaikan filter tanggal, penyelesaian konflik _namespace facade_ PDF, pembersihan migrasi ganda, dan penyesuaian aturan `Unique Index` PostgreSQL agar mendukung _Soft Deletes_.

---

### [21 Maret 2026] - Master Budgeting, Reimbursement Engine & Global Localization

**By:** @jevonkagenou

- **Master Budget Management:** Pembuatan antarmuka dan logika CRUD untuk Pagu Anggaran Divisi, dilengkapi dengan _Progress Bar_ visual yang dinamis (indikator warna berubah berdasarkan persentase dana terpakai).
- **Core Reimbursement Engine:** Implementasi sistem Pengajuan Dana (Reimbursement) dari nol hingga _production-ready_ dengan _Role-Based Access Control_ (Staff mengajukan, Manager/Admin menyetujui/menolak).
- **Financial Automation:** Penerapan _Business Logic_ untuk memotong saldo anggaran (`used_amount`) secara otomatis saat pengajuan disetujui, lengkap dengan validasi proteksi saldo tidak cukup.
- **RESTful Routing Refactor:** Restrukturisasi _URL prefix_ dari _role-based_ menjadi _resource-based_ (fungsional murni) agar lebih profesional dan sesuai standar _Enterprise_.
- **Global Localization & Timezone:** Konfigurasi zona waktu sistem ke `Asia/Jakarta` (WIB) dan translasi penuh file `validation.php` bawaan Laravel ke Bahasa Indonesia yang baku.
- **UI/UX Polishing:** Perbaikan struktur tabel responsif (menghilangkan _horizontal scroll_), perbaikan _rendering_ Modal Bootstrap, dan implementasi _SweetAlert2_ untuk konfirmasi tindakan finansial yang krusial.
- **Advanced Data Filtering:** Penambahan fitur _Search_ (pencarian multi-kolom) dan _Filter_ berdasarkan Status pada modul _Activity Log_ dan _Reimbursement_.
- **Data Seeding & Audit Trail:** Pembuatan `BudgetSeeder` dan `ReimbursementSeeder` berskenario lengkap, serta memastikan setiap transaksi finansial terekam otomatis oleh Spatie Activitylog dengan format bahasa Indonesia.

---

### [20 Maret 2026] - Initial Backend, Auth & Core Features Foundation

**By:** @jevonkagenou

- **Database Setup:** Migrasi ke PostgreSQL dan implementasi skema tabel inti.
- **Security Layer:** Implementasi Spatie Roles & Permissions (Admin, Manager, Staff).
- **Authentication:** Sistem Login/Logout dengan proteksi Middleware dan logika _Role-based redirection_.
- **UI Restructuring:** Refaktor Master Layout Sneat menggunakan Blade Components (`@include` & `@extends`).
- **User Feedback:** Implementasi notifikasi Toast (Bootstrap 5) dan SweetAlert2 untuk UX yang lebih modern.
- **Master Data Management:** Pembuatan antarmuka dan logika CRUD lengkap untuk mengelola data Divisi.
- **Secure Data Import:** Fitur import data pengguna massal via Excel/CSV yang dilengkapi sanitasi anti-CSV Injection dan _auto-create_ divisi.
- **Audit Trail (Activity Log):** Integrasi `spatie/laravel-activitylog` (dengan kustomisasi UUID) untuk merekam seluruh jejak perubahan data secara diam-diam dan otomatis.
- **Data Integrity:** Penerapan `SoftDeletes` pada model User untuk mengamankan riwayat transaksi dari penghapusan permanen (mencegah _orphan records_).
- **Data Seeding:** Pembuatan akun _tester_ otomatis dan `DivisionSeeder` yang sudah saling berelasi utuh untuk mempermudah fase pengembangan.
