<!DOCTYPE html>
<html lang="id" class="light-style layout-wide">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistem Informasi Bisnis | E-Budgeting K1</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/theme-default.css') }}" />

    <style>
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 75px;
        }
        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #ffffff;
        }

        /* Navbar Styling */
        .navbar-landing {
            background-color: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        /* Hero Section dengan Gradient Mewah */
        .hero-section {
            position: relative;
            padding: 140px 0 160px; /* Padding bawah dilebarkan untuk wave */
            background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
            z-index: 1;
        }

        /* SVG Curve Divider (Efek Lengkungan Premium) */
        .custom-shape-divider-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            z-index: 2;
        }
        .custom-shape-divider-bottom svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 90px;
        }
        .custom-shape-divider-bottom .shape-fill {
            fill: #FFFFFF; /* Warna sama dengan section di bawahnya */
        }

        /* Hover Cards */
        .feature-card {
            transition: all 0.3s ease;
            border: 1px solid #f0f2f5;
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(105, 108, 255, 0.1);
            border-color: #696cff;
        }

        /* Step/Alur Kerja styling */
        .step-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background-color: #e7e7ff;
            color: #696cff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 15px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-landing fixed-top py-3">
        <div class="container-xxl">
            <a class="navbar-brand fw-bold text-primary fs-4" href="#beranda">
                <i class="bx bx-wallet-alt me-1"></i> IMAJIKU<span class="text-dark">.FIN</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto fw-medium text-dark">
                    <li class="nav-item px-2"><a class="nav-link text-dark" href="#beranda">Beranda</a></li>
                    <li class="nav-item px-2"><a class="nav-link text-dark" href="#tentang">Tentang Kami</a></li>
                    <li class="nav-item px-2"><a class="nav-link text-dark" href="#layanan">Layanan</a></li>
                    <li class="nav-item px-2"><a class="nav-link text-dark" href="#alur">Alur Kerja</a></li>
                    <li class="nav-item px-2"><a class="nav-link text-dark" href="#kontak">Kontak</a></li>
                </ul>
                <a href="/login" class="btn btn-primary rounded-pill px-4 shadow-sm">
                    Client Login <i class="bx bx-right-arrow-alt ms-1"></i>
                </a>
            </div>
        </div>
    </nav>

    <section id="beranda" class="hero-section">
        <div class="container-xxl relative">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 text-center text-lg-start">
                    <h1 class="display-4 fw-bolder text-dark mb-4" style="line-height: 1.2;">
                        Membangun Ekosistem <br> <span class="text-primary">Anggaran Profesional</span>
                    </h1>
                    <p class="lead text-muted mb-5 pe-lg-5" style="font-size: 1.1rem;">
                        Sistem manajemen keuangan terintegrasi untuk bisnis modern. Kami memastikan setiap alur dana perusahaan Anda tercatat, transparan, dan mudah dianalisis.
                    </p>
                    <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                        <a href="#layanan" class="btn btn-primary btn-lg rounded-pill px-5 shadow">Eksplorasi</a>
                        <a href="#kontak" class="btn btn-outline-dark btn-lg rounded-pill px-4">Hubungi Kami</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800&auto=format&fit=crop"
                         alt="Dashboard Finance" class="img-fluid rounded-3 shadow-lg" style="border: 8px solid white; transform: perspective(1000px) rotateY(-5deg);">
                </div>
            </div>
        </div>

        <div class="custom-shape-divider-bottom">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,0 C300,100 900,100 1200,0 L1200,120 L0,120 Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <section id="tentang" class="py-5 bg-white">
        <div class="container-xxl py-5">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?q=80&w=800&auto=format&fit=crop"
                         alt="Office Meeting" class="img-fluid rounded-3 shadow-sm">
                </div>
                <div class="col-lg-6 pe-lg-5">
                    <h6 class="text-primary fw-bold text-uppercase mb-2">Tentang Perusahaan</h6>
                    <h2 class="fw-bold text-dark mb-4">Solusi Cerdas untuk Sistem Informasi Bisnis</h2>
                    <p class="text-muted mb-4" style="text-align: justify;">
                        Di era digital, pencatatan anggaran secara manual bukan lagi pilihan. Kami merancang arsitektur panel kendali terpusat yang menggabungkan prinsip akuntansi dengan teknologi komputasi awan.
                    </p>
                    <p class="text-muted" style="text-align: justify;">
                        Fokus utama kami adalah menyederhanakan birokrasi internal perusahaan, memberikan hak akses yang aman bagi setiap divisi, dan mencegah kebocoran anggaran melalui pemantauan berbasis data.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="layanan" class="py-5" style="background-color: #fcfcfc;">
        <div class="container-xxl py-5">
            <div class="text-center mb-5">
                <h6 class="text-primary fw-bold text-uppercase mb-2">Layanan Kami</h6>
                <h2 class="fw-bold text-dark">Modul Keuangan Terintegrasi</h2>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 feature-card p-4">
                        <div class="card-body">
                            <i class='bx bx-check-shield text-primary mb-3' style="font-size: 3rem;"></i>
                            <h5 class="fw-bold">Approval Berjenjang</h5>
                            <p class="text-muted mb-0">Sistem validasi pengajuan dana yang aman, melibatkan verifikasi dari staf, supervisor, hingga manajer keuangan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 feature-card p-4">
                        <div class="card-body">
                            <i class='bx bx-pie-chart-alt text-primary mb-3' style="font-size: 3rem;"></i>
                            <h5 class="fw-bold">Monitoring Real-Time</h5>
                            <p class="text-muted mb-0">Lacak sisa pagu anggaran setiap divisi secara presisi detik demi detik melalui dasbor analitik interaktif.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 feature-card p-4">
                        <div class="card-body">
                            <i class='bx bx-file-find text-primary mb-3' style="font-size: 3rem;"></i>
                            <h5 class="fw-bold">Jejak Audit & PDF</h5>
                            <p class="text-muted mb-0">Riwayat transaksi tersimpan permanen sebagai jejak audit. Ekspor laporan harian, bulanan, atau tahunan dalam sekali klik.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="alur" class="py-5 bg-white">
        <div class="container-xxl py-5 text-center">
            <h6 class="text-primary fw-bold text-uppercase mb-2">Prosedur</h6>
            <h2 class="fw-bold text-dark mb-5">Alur Kerja Sistem</h2>

            <div class="row g-4 justify-content-center">
                <div class="col-6 col-md-3">
                    <div class="step-circle shadow-sm">1</div>
                    <h6 class="fw-bold">Pengajuan Divisi</h6>
                    <p class="text-muted small">Input rencana anggaran dan melampirkan dokumen RAB.</p>
                </div>
                <div class="col-6 col-md-3">
                    <div class="step-circle shadow-sm">2</div>
                    <h6 class="fw-bold">Validasi Manajer</h6>
                    <p class="text-muted small">Pengecekan kesesuaian data dan persetujuan pencairan.</p>
                </div>
                <div class="col-6 col-md-3">
                    <div class="step-circle shadow-sm">3</div>
                    <h6 class="fw-bold">Distribusi Dana</h6>
                    <p class="text-muted small">Sistem mencatat pengeluaran dan memotong sisa pagu.</p>
                </div>
                <div class="col-6 col-md-3">
                    <div class="step-circle shadow-sm">4</div>
                    <h6 class="fw-bold">Pelaporan Selesai</h6>
                    <p class="text-muted small">Bukti transaksi diunggah sebagai laporan pertanggungjawaban.</p>
                </div>
            </div>
        </div>
    </section>

    <footer id="kontak" class="pt-5 pb-4" style="background-color: #232333; color: #a1b0cb;">
        <div class="container-xxl">
            <div class="row mb-5">
                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <h5 class="fw-bold text-white mb-4"><i class="bx bx-wallet-alt me-2 text-primary"></i> E-Budgeting K1</h5>
                    <p class="small pe-lg-4">
                        Kami menyediakan ekosistem pelacakan anggaran yang modern, aman, dan mudah digunakan untuk memaksimalkan efisiensi finansial perusahaan Anda.
                    </p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="text-white"><i class='bx bxl-facebook-circle fs-3'></i></a>
                        <a href="#" class="text-white"><i class='bx bxl-linkedin-square fs-3'></i></a>
                        <a href="#" class="text-white"><i class='bx bxl-github fs-3'></i></a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                    <h5 class="fw-bold text-white mb-4">Tautan Cepat</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#beranda" class="text-decoration-none text-muted">Beranda</a></li>
                        <li class="mb-2"><a href="#tentang" class="text-decoration-none text-muted">Tentang Kami</a></li>
                        <li class="mb-2"><a href="#layanan" class="text-decoration-none text-muted">Layanan Bisnis</a></li>
                        <li class="mb-2"><a href="#alur" class="text-decoration-none text-muted">Alur Persetujuan</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12">
                    <h5 class="fw-bold text-white mb-4">Hubungi Kami</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-3 d-flex align-items-start">
                            <i class='bx bx-map fs-5 me-2 text-primary'></i>
                            <span>Gedung Sistem Informasi, Politeknik Negeri Malang, Jawa Timur</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <i class='bx bx-envelope fs-5 me-2 text-primary'></i>
                            <span>hello@ebudgeting-k1.com</span>
                        </li>
                        <li class="d-flex align-items-center">
                            <i class='bx bx-phone fs-5 me-2 text-primary'></i>
                            <span>+62 811-2345-6789</span>
                        </li>
                    </ul>
                </div>
            </div>

            <hr style="border-color: rgba(255,255,255,0.1);">

            <div class="row align-items-center mt-4">
                <div class="col-md-6 text-center text-md-start small">
                    © 2026 E-Budgeting Kelompok 1. Hak Cipta Dilindungi.
                </div>
                <div class="col-md-6 text-center text-md-end small mt-3 mt-md-0">
                    Dibuat menggunakan <a href="https://themeselection.com" target="_blank" class="text-primary text-decoration-none fw-medium">Sneat Template</a> & Laravel.
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('sneat/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/js/bootstrap.js') }}"></script>
</body>
</html>
