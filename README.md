# E-Budgeting API - Sistem Manajemen Reimbursement

Proyek ini adalah sistem backend berbasis RESTful API yang dirancang untuk mengelola permohonan dana operasional dan *reimbursement* karyawan. Tujuan utama sistem ini adalah untuk menggantikan birokrasi kertas yang rentan hilang, mempercepat proses persetujuan, dan mencegah pengeluaran divisi yang melebihi batas anggaran.

## Arsitektur Teknologi
Sistem ini menggunakan arsitektur *Client-Server*:
* **Backend:** Menggunakan kerangka kerja **Laravel** untuk memproses logika bisnis, mengelola *database* terpusat, dan menyediakan *endpoint* API. Keamanan akses diatur menggunakan **Laravel Sanctum**.
* **Frontend:** Aplikasi seluler berbasis **Flutter** yang berfungsi sebagai *thin client* agar staf dapat mengunggah pengajuan langsung dari lapangan.

## Fitur Inti (MVP)
* **Pre-emptive Budget Locking:** Validasi sistem yang akan memblokir pengajuan secara otomatis apabila nominal yang dimasukkan melebihi sisa anggaran divisi terkait.
* **Dukungan Multi-Tahun:** Skema *database* yang dirancang untuk menyimpan riwayat anggaran dari berbagai tahun secara berdampingan tanpa perlu membuat sistem atau *database* baru setiap pergantian tahun.
* **Digitalisasi Bukti Transaksi:** Mewajibkan pengguna untuk melampirkan foto struk fisik ke dalam sistem penyimpanan yang aman.
* **Alur Persetujuan Transparan:** Memungkinkan staf untuk melihat status pengajuan mereka (Pending, Approved, atau Rejected) secara langsung melalui aplikasi.
* **Jejak Audit (Audit Trail):** Sistem keamanan yang mencatat setiap aksi dari manajer atau admin saat menyetujui atau menolak dokumen, lengkap dengan alasan dan waktu eksekusi untuk akuntabilitas.
* **Laporan Otomatis (Ekspor PDF):** Fitur untuk merangkum seluruh pengeluaran yang telah disetujui menjadi dokumen PDF untuk kebutuhan Laporan Pertanggungjawaban (LPJ).

## Log Perubahan (Changelog)

### [25 Maret 2026] - Enterprise API Foundation, Secure Identity Management & UX Refinement
**By:** @jevonkagenou

* **Mobile API Engine (Sanctum):** Implementasi *RESTful API* komprehensif menggunakan Laravel Sanctum untuk mendukung aplikasi *mobile*, mencakup otentikasi (Login/Logout) yang aman dan *Token Management*.
* **Financial Core API & Data Aggregation:** Pengembangan *endpoint* pengajuan dana (*multipart/form-data*) dan penyatuan data Dasbor terpadu (metrik saldo, profil, riwayat) dalam satu *response* untuk meminimalisir *latency* pada *mobile client*.
* **Race Condition Protection (API):** Sinkronisasi tingkat keamanan *Enterprise* pada rute persetujuan (Approve) API menggunakan `DB::transaction()` dan `lockForUpdate()`, memastikan integritas pemotongan saldo divisi terhindar dari *double-spending*.
* **Cross-Platform Compliance Validation:** Penyelarasan *Business Logic* secara mutlak antara *Web Controller* dan *API Controller*, mewajibkan lampiran struk fisik (gambar) dan deskripsi untuk setiap pengajuan demi kepatuhan *audit trail* perusahaan.
* **Secure Identity Management:** Pembuatan antarmuka dan logika pengelolaan Profil Web dengan sistem verifikasi berlapis, mewajibkan otentikasi kata sandi lama (*Current Password validation*) sebelum mengizinkan pembaruan kredensial.
* **Navigational Architecture Refactor:** Restrukturisasi hierarki UI dengan memusatkan menu personal ke *Dropdown Navbar* (menggunakan *Flexbox Alignment* untuk presisi visual), serta membersihkan *Sidebar* agar eksklusif untuk modul operasional bisnis.
* **Seamless Data Presentation:** Pembaruan antarmuka tabel Dasbor dengan mengintegrasikan *Bootstrap Modal* (menggantikan elemen *Collapse/SweetAlert*) untuk merender teks penolakan yang panjang secara terisolasi tanpa merusak struktur *grid* tabel.
* **Event Handling Resolution:** Resolusi anomali *JavaScript event handler* (`undefined event`) pada eksekusi *Logout* Web, memastikan penahan *SweetAlert2* beroperasi sempurna sebelum melakukan destruksi sesi secara *server-side*.

---

### [23 Maret 2026] - Enterprise Architecture, Soft Deletes, & Advanced UI Integration
**By:** @jevonkagenou

* **Database Normalization & Multi-Year Support:** Pemisahan tabel `fiscal_years` dan `budget_categories` untuk mendukung arsitektur *enterprise* sehingga mencegah fragmentasi data lintas tahun anggaran.
* **Master Data Management Expansion:** Penambahan antarmuka, kontroler, rute, dan menu Sidebar untuk mengelola data master *Tahun Anggaran* dan *Kategori Anggaran* secara penuh menggunakan sistem *Modal* Bootstrap.
* **Pre-emptive Budget Locking:** Implementasi pengamanan tingkat lanjut menggunakan `DB::transaction()` dan `lockForUpdate()` pada proses *approval* untuk mengunci baris data di PostgreSQL, memastikan saldo divisi aman dari kebocoran akibat *race condition*.
* **Automated LPJ PDF Generation:** Integrasi `barryvdh/laravel-dompdf` untuk mencetak Laporan Pertanggungjawaban. Dilengkapi dengan desain *Modern Corporate* khusus ukuran A4 Landscape dan kapabilitas filter berdasarkan rentang tanggal persetujuan.
* **Context-Aware UI/UX:** Optimalisasi antarmuka pengguna dengan menyembunyikan tombol aksi ke dalam menu *dropdown* pada tabel yang padat data (mencegah *horizontal scroll*), namun mempertahankan tombol langsung pada tabel master untuk efisiensi klik.
* **Seeder Synchronization:** Restrukturisasi total pada `BudgetSeeder` dan `ReimbursementSeeder` agar sepenuhnya kompatibel dengan skema UUID relasional yang baru dan terintegrasi mulus dengan *Master Data*.
* **Multi-Division Manager Architecture:** Implementasi relasi *Many-to-Many* melalui tabel pivot `manager_divisions` (UUID-based), memungkinkan satu akun level Manajer untuk mengawasi dan menyetujui anggaran dari banyak divisi sekaligus.
* **Enterprise-Grade Form UI:** Integrasi *library* Select2 untuk pencarian *dropdown* yang intuitif (*Single* & *Multi-select*) di dalam Modal Bootstrap, serta penerapan *Inline Truncation* dengan Tooltip untuk merapikan tampilan *badge* data yang panjang.
* **Data Integrity & Soft Deletes Implementation:** Mengamankan seluruh data historis dan laporan (LPJ) dari kerusakan akibat penghapusan data master (*Cascade Delete*) dengan menerapkan `SoftDeletes` secara global dan mengamankan relasi menggunakan `withTrashed()`.
* **Query Optimization & Bug Fixes:** Resolusi *N+1 query problem* via *eager loading*, perbaikan filter tanggal, penyelesaian konflik *namespace facade* PDF, pembersihan migrasi ganda, dan penyesuaian aturan `Unique Index` PostgreSQL agar mendukung *Soft Deletes*.

---

### [21 Maret 2026] - Master Budgeting, Reimbursement Engine & Global Localization
**By:** @jevonkagenou

* **Master Budget Management:** Pembuatan antarmuka dan logika CRUD untuk Pagu Anggaran Divisi, dilengkapi dengan *Progress Bar* visual yang dinamis (indikator warna berubah berdasarkan persentase dana terpakai).
* **Core Reimbursement Engine:** Implementasi sistem Pengajuan Dana (Reimbursement) dari nol hingga *production-ready* dengan *Role-Based Access Control* (Staff mengajukan, Manager/Admin menyetujui/menolak).
* **Financial Automation:** Penerapan *Business Logic* untuk memotong saldo anggaran (`used_amount`) secara otomatis saat pengajuan disetujui, lengkap dengan validasi proteksi saldo tidak cukup.
* **RESTful Routing Refactor:** Restrukturisasi *URL prefix* dari *role-based* menjadi *resource-based* (fungsional murni) agar lebih profesional dan sesuai standar *Enterprise*.
* **Global Localization & Timezone:** Konfigurasi zona waktu sistem ke `Asia/Jakarta` (WIB) dan translasi penuh file `validation.php` bawaan Laravel ke Bahasa Indonesia yang baku.
* **UI/UX Polishing:** Perbaikan struktur tabel responsif (menghilangkan *horizontal scroll*), perbaikan *rendering* Modal Bootstrap, dan implementasi *SweetAlert2* untuk konfirmasi tindakan finansial yang krusial.
* **Advanced Data Filtering:** Penambahan fitur *Search* (pencarian multi-kolom) dan *Filter* berdasarkan Status pada modul *Activity Log* dan *Reimbursement*.
* **Data Seeding & Audit Trail:** Pembuatan `BudgetSeeder` dan `ReimbursementSeeder` berskenario lengkap, serta memastikan setiap transaksi finansial terekam otomatis oleh Spatie Activitylog dengan format bahasa Indonesia.

---

### [20 Maret 2026] - Initial Backend, Auth & Core Features Foundation
**By:** @jevonkagenou

* **Database Setup:** Migrasi ke PostgreSQL dan implementasi skema tabel inti.
* **Security Layer:** Implementasi Spatie Roles & Permissions (Admin, Manager, Staff).
* **Authentication:** Sistem Login/Logout dengan proteksi Middleware dan logika *Role-based redirection*.
* **UI Restructuring:** Refaktor Master Layout Sneat menggunakan Blade Components (`@include` & `@extends`).
* **User Feedback:** Implementasi notifikasi Toast (Bootstrap 5) dan SweetAlert2 untuk UX yang lebih modern.
* **Master Data Management:** Pembuatan antarmuka dan logika CRUD lengkap untuk mengelola data Divisi.
* **Secure Data Import:** Fitur import data pengguna massal via Excel/CSV yang dilengkapi sanitasi anti-CSV Injection dan *auto-create* divisi.
* **Audit Trail (Activity Log):** Integrasi `spatie/laravel-activitylog` (dengan kustomisasi UUID) untuk merekam seluruh jejak perubahan data secara diam-diam dan otomatis.
* **Data Integrity:** Penerapan `SoftDeletes` pada model User untuk mengamankan riwayat transaksi dari penghapusan permanen (mencegah *orphan records*).
* **Data Seeding:** Pembuatan akun *tester* otomatis dan `DivisionSeeder` yang sudah saling berelasi utuh untuk mempermudah fase pengembangan.
