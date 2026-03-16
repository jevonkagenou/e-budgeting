# Sistem Manajemen Reimbursement dan Pengajuan Dana Operasional (E-Budgeting)

[cite_start]Sistem E-Budgeting berbasis API ini dirancang untuk mendigitalisasi tata kelola keuangan internal, khususnya dalam proses *reimbursement* dan pengajuan dana operasional[cite: 2, 45]. [cite_start]Proyek ini dikembangkan untuk menggantikan birokrasi manual yang rentan terhadap kehilangan bukti fisik, proses persetujuan yang lambat, serta mencegah terjadinya *over-budgeting*[cite: 6, 8, 12].

## Arsitektur Sistem
[cite_start]Sistem ini mengadopsi pola arsitektur *Client-Server* terdistribusi[cite: 14, 48]:
* [cite_start]**Backend (Web Panel & API):** Dibangun menggunakan framework **Laravel** sebagai pusat pengolah data, manajemen master data oleh Admin, serta penyedia layanan RESTful API yang diamankan dengan Laravel Sanctum[cite: 27, 49, 56, 57].
* [cite_start]**Frontend (Mobile Thin Client):** Dibangun menggunakan **Flutter** yang berfokus menyajikan antarmuka pengguna interaktif bagi staf operasional untuk melakukan pengajuan data dengan cepat di lapangan[cite: 50, 64, 67].

## Fitur Utama (MVP)
1.  [cite_start]**Pre-emptive Budget Locking:** Mekanisme validasi otomatis yang menolak pengajuan dana jika nominal melebihi sisa saldo divisi secara *real-time*[cite: 28, 78].
2.  [cite_start]**Multi-Year Support:** Basis data terpusat yang mampu mengakomodasi pengelolaan anggaran lintas tahun tanpa perlu memisahkan portal aplikasi[cite: 22, 74].
3.  [cite_start]**Digital Reimbursement Form:** Modul pengajuan yang mewajibkan unggahan foto struk fisik untuk digitalisasi bukti transaksi yang aman[cite: 80, 81, 84].
4.  [cite_start]**Approval Workflow & Tracking:** Alur persetujuan terintegrasi yang memungkinkan staf memantau status pengajuan (Pending, Approved, Rejected) secara transparan[cite: 24, 86].
5.  [cite_start]**Audit Trail:** Pencatatan jejak digital secara permanen yang merekam aktor validasi, alasan, dan waktu persis eksekusi sebagai pengganti tanda tangan basah[cite: 88, 89].
6.  [cite_start]**Export to PDF:** Generator laporan otomatis untuk merangkum transaksi yang telah disetujui guna mempermudah pembuatan Laporan Pertanggungjawaban (LPJ)[cite: 91, 93].