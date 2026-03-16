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