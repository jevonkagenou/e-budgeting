<!DOCTYPE html>
<html lang="id" class="light-style layout-wide">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sistem Informasi Bisnis | SyncBudget</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('sneat/assets/css/demo.css') }}" />

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
            color: #3a3b45;
        }

        section {
            scroll-margin-top: 90px;
        }

        .navbar-landing {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .hero-section {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 100px;
            padding-bottom: 80px;
            background-color: #ffffff;
            background-image: radial-gradient(rgba(105, 108, 255, 0.05) 2px, transparent 2px);
            background-size: 30px 30px;
            z-index: 1;
            overflow: hidden;
        }

        .hero-glow,
        .hero-glow-2 {
            position: absolute;
            z-index: -1;
            border-radius: 50%;
        }

        .hero-glow {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(105, 108, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
            top: -10%;
            left: -10%;
        }

        .hero-glow-2 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(0, 210, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
            bottom: -10%;
            right: -5%;
        }

        .text-gradient {
            background: linear-gradient(135deg, #696cff 0%, #00d2ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .browser-mockup {
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.05);
            transform: perspective(1000px) rotateY(-5deg) rotateX(2deg);
            transition: transform 0.5s ease;
        }

        .browser-mockup:hover {
            transform: perspective(1000px) rotateY(0deg) rotateX(0deg);
        }

        .browser-header {
            background: #f1f1f1;
            padding: 10px 15px;
            display: flex;
            gap: 6px;
            border-bottom: 1px solid #e1e1e1;
        }

        .browser-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .dot-red {
            background: #ff5f56;
        }

        .dot-yellow {
            background: #ffbd2e;
        }

        .dot-green {
            background: #27c93f;
        }

        .btn-glow {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-glow:hover {
            box-shadow: 0 8px 25px -5px rgba(105, 108, 255, 0.5);
            transform: translateY(-2px);
        }

        .section-padding {
            padding: 100px 0;
        }

        .card-premium {
            background: #ffffff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.05);
            transition: all 0.4s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .card-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #696cff, #00d2ff);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .card-premium:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px -10px rgba(105, 108, 255, 0.15);
        }

        .card-premium:hover::before {
            transform: scaleX(1);
        }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background: rgba(105, 108, 255, 0.1);
            color: #696cff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 24px;
            transition: all 0.3s ease;
        }

        .card-premium:hover .icon-box {
            background: #696cff;
            color: #ffffff;
            transform: scale(1.1) rotate(5deg);
        }

        .bg-corporate-dark {
            background-color: #1a233a;
            color: #ffffff;
            position: relative;
        }

        .bg-corporate-dark::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: radial-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.5;
        }

        .step-wrapper {
            position: relative;
            z-index: 2;
        }

        .step-number {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #696cff, #00d2ff);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            box-shadow: 0 8px 15px rgba(105, 108, 255, 0.3);
        }

        .step-line {
            position: absolute;
            top: 25px;
            left: 50px;
            width: calc(100% - 30px);
            height: 2px;
            background: rgba(255, 255, 255, 0.1);
            z-index: -1;
        }

        @media (max-width: 991px) {
            .step-line {
                display: none;
            }
        }

        /* Timeline CSS */
        .timeline-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .custom-timeline {
            border-left: 2px solid #e7e7ff;
            padding-left: 30px;
            margin-left: 15px;
            position: relative;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 40px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-indicator {
            position: absolute;
            left: -39px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #696cff;
            border: 3px solid #fff;
            box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.2);
        }

        .timeline-date {
            display: inline-block;
            padding: 4px 12px;
            background: rgba(105, 108, 255, 0.1);
            color: #696cff;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .timeline-content {
            background: #fff;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .timeline-content:hover {
            box-shadow: 0 8px 25px rgba(105, 108, 255, 0.1);
            transform: translateX(5px);
        }

        .timeline-list {
            padding-left: 1rem;
            margin-bottom: 0;
        }

        .timeline-list li {
            margin-bottom: 8px;
            color: #6c757d;
        }

        .timeline-list li strong {
            color: #3a3b45;
        }

        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s cubic-bezier(0.5, 0, 0, 1);
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        #btnBackToTop {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            z-index: 999;
            box-shadow: 0 4px 15px rgba(105, 108, 255, 0.4);
            transition: all 0.3s ease;
            align-items: center;
            justify-content: center;
            background-color: #696cff;
            border: none;
            cursor: pointer;
        }

        #btnBackToTop:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(105, 108, 255, 0.6);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-landing fixed-top py-3">
        <div class="container-xxl">
            <a class="navbar-brand fw-bold text-primary fs-4 d-flex align-items-center gap-2" href="#beranda">
                <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                        <path
                            d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                            id="path-1"></path>
                        <path
                            d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z"
                            id="path-3"></path>
                        <path
                            d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z"
                            id="path-4"></path>
                        <path
                            d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z"
                            id="path-5"></path>
                    </defs>
                    <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                            <g id="Icon" transform="translate(27.000000, 15.000000)">
                                <g id="Mask" transform="translate(0.000000, 8.000000)">
                                    <mask id="mask-2" fill="white">
                                        <use xlink:href="#path-1"></use>
                                    </mask>
                                    <use fill="currentColor" xlink:href="#path-1"></use>
                                    <g id="Path-3" mask="url(#mask-2)">
                                        <use fill="currentColor" xlink:href="#path-3"></use>
                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                    </g>
                                    <g id="Path-4" mask="url(#mask-2)">
                                        <use fill="currentColor" xlink:href="#path-4"></use>
                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                    </g>
                                </g>
                                <g id="Triangle"
                                    transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000) ">
                                    <use fill="currentColor" xlink:href="#path-5"></use>
                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                </g>
                            </g>
                        </g>
                    </g>
                </svg>
                <span>SyncBudget</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <i class="bx bx-menu fs-3"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto fw-medium text-dark">
                    <li class="nav-item px-2"><a class="nav-link text-dark" href="#beranda">Beranda</a></li>
                    <li class="nav-item px-2"><a class="nav-link text-dark" href="#arsitektur">Arsitektur</a></li>
                    <li class="nav-item px-2"><a class="nav-link text-dark" href="#fitur">Modul Sistem</a></li>
                    <li class="nav-item px-2"><a class="nav-link text-dark" href="#alur">Alur Operasional</a></li>
                    <li class="nav-item px-2"><a class="nav-link text-dark" href="#pembaruan">Jejak Sistem</a></li>
                </ul>
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="btn btn-primary rounded-pill px-4 shadow-sm btn-glow fw-medium">
                        Ke Dasbor <i class="bx bx-home-alt ms-1"></i>
                    </a>
                @else
                    <a href="{{ url('/login') }}" class="btn btn-primary rounded-pill px-4 shadow-sm btn-glow fw-medium">
                        Masuk Panel <i class="bx bx-right-arrow-alt ms-1"></i>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <section id="beranda" class="hero-section">
        <div class="hero-glow"></div>
        <div class="hero-glow-2"></div>
        <div class="container-xxl">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 text-center text-lg-start reveal active">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill bg-label-primary mb-4">
                        <span class="badge bg-primary rounded-pill">Baru</span>
                        <span class="small fw-semibold">Enterprise E-Budgeting v2.0</span>
                    </div>
                    <h1 class="display-4 fw-bolder text-dark mb-4" style="line-height: 1.15; letter-spacing: -1px;">
                        Kendalikan Anggaran <br> Dengan Presisi & <span class="text-gradient">Keamanan Finansial</span>
                    </h1>
                    <p class="fs-5 text-muted mb-5 pe-lg-5" style="line-height: 1.6;">
                        Arsitektur E-Budgeting modern yang meredefinisi transparansi operasional. Dilengkapi dengan
                        kontrol persetujuan dinamis, proteksi kebocoran saldo secara real-time, dan integrasi API
                        menyeluruh.
                    </p>
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                        <a href="#arsitektur" class="btn btn-primary btn-lg rounded-pill px-5 btn-glow">Pelajari
                            Arsitektur</a>
                        <a href="#fitur" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Eksplorasi
                            Fitur</a>
                    </div>
                </div>
                <div class="col-lg-6 reveal active" style="transition-delay: 0.2s;">
                    <div class="browser-mockup">
                        <div class="browser-header">
                            <div class="browser-dot dot-red"></div>
                            <div class="browser-dot dot-yellow"></div>
                            <div class="browser-dot dot-green"></div>
                        </div>
                        <img src="https://images.unsplash.com/photo-1642104704074-907c0698cbd9?q=80&w=1000&auto=format&fit=crop"
                            class="w-100 d-block" style="object-fit: cover; height: 100%;"
                            alt="Enterprise Data Tech">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="arsitektur" class="section-padding bg-white">
        <div class="container-xxl">
            <div class="text-center mb-5 reveal">
                <span class="text-primary fw-bold tracking-wide text-uppercase small">System Design & Integrity</span>
                <h2 class="fw-bolder text-dark mt-2 mb-3">Arsitektur Skala Enterprise</h2>
                <p class="text-muted mx-auto fs-6" style="max-width: 600px;">
                    Dibangun di atas fondasi teknologi yang berfokus pada keandalan data historis, keamanan transaksi
                    multi-user, dan konektivitas lintas platform.
                </p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 reveal" style="transition-delay: 0.1s;">
                    <div class="card-premium p-4 p-xl-5">
                        <div class="icon-box"><i class="bx bx-lock-alt"></i></div>
                        <h4 class="fw-bold mb-3">Keamanan Transaksional</h4>
                        <p class="text-muted mb-0" style="line-height: 1.6;">
                            Menerapkan skema <strong>Row-Level Locking</strong> pada tingkat database PostgreSQL.
                            Mencegah secara absolut terjadinya kebocoran saldo akibat eksekusi persetujuan ganda dalam
                            hitungan milidetik.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 reveal" style="transition-delay: 0.2s;">
                    <div class="card-premium p-4 p-xl-5">
                        <div class="icon-box"><i class="bx bx-history"></i></div>
                        <h4 class="fw-bold mb-3">Integritas Data Historis</h4>
                        <p class="text-muted mb-0" style="line-height: 1.6;">
                            Arsitektur diamankan dengan <strong>Global Soft Deletes</strong>. Penghapusan data master
                            tidak akan memicu Cascade Delete, menjamin seluruh rekam jejak audit keuangan abadi untuk
                            keperluan LPJ.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 reveal" style="transition-delay: 0.3s;">
                    <div class="card-premium p-4 p-xl-5">
                        <div class="icon-box"><i class="bx bx-code-alt"></i></div>
                        <h4 class="fw-bold mb-3">RESTful API Terenkripsi</h4>
                        <p class="text-muted mb-0" style="line-height: 1.6;">
                            Dilengkapi dengan endpoint API mutakhir yang diotentikasi melalui <strong>Laravel
                                Sanctum</strong>. Memungkinkan interaksi sistem secara real-time langsung dari perangkat
                            mobile para staf operasional.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="section-padding" style="background-color: #f8f9fa;">
        <div class="container-xxl">
            <div class="text-center mb-5 reveal">
                <span class="text-primary fw-bold tracking-wide text-uppercase small">Modul Operasional</span>
                <h2 class="fw-bolder text-dark mt-2 mb-3">Fitur Unggulan SyncBudget</h2>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="card-premium p-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class='bx bx-git-merge text-primary fs-2 me-3'></i>
                            <h5 class="fw-bold mb-0">Manajemen Multi-Divisi</h5>
                        </div>
                        <p class="text-muted mb-0">Implementasi relasi dinamis yang mengizinkan satu akun Manajer untuk
                            mengawasi dan menyetujui anggaran lintas departemen secara bersamaan.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" style="transition-delay: 0.1s;">
                    <div class="card-premium p-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class='bx bx-pie-chart-alt-2 text-primary fs-2 me-3'></i>
                            <h5 class="fw-bold mb-0">Dasbor Metrik Multi-Role</h5>
                        </div>
                        <p class="text-muted mb-0">Visualisasi data interaktif menggunakan ApexCharts, disesuaikan
                            secara hierarkis berdasarkan tingkat otoritas pengguna (RBAC).</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" style="transition-delay: 0.2s;">
                    <div class="card-premium p-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class='bx bxs-file-pdf text-primary fs-2 me-3'></i>
                            <h5 class="fw-bold mb-0">Otomatisasi Laporan (LPJ)</h5>
                        </div>
                        <p class="text-muted mb-0">Mesin render PDF dengan manajemen memori optimal (chunking) untuk
                            mengekspor rekapitulasi pengeluaran masif tanpa membebani server.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal">
                    <div class="card-premium p-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class='bx bx-calendar-check text-primary fs-2 me-3'></i>
                            <h5 class="fw-bold mb-0">Fiscal Year Guard</h5>
                        </div>
                        <p class="text-muted mb-0">Sistem pintar yang mendeteksi dan mencegah tumpang tindih aktivasi
                            Tahun Anggaran guna memastikan akurasi kalkulasi saldo global.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" style="transition-delay: 0.1s;">
                    <div class="card-premium p-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class='bx bx-trash text-primary fs-2 me-3'></i>
                            <h5 class="fw-bold mb-0">Automated Storage Cleanup</h5>
                        </div>
                        <p class="text-muted mb-0">Logika pembersihan storage otomatis yang menghancurkan file struk
                            fisik usang hanya saat pengajuan benar-benar dihapus permanen.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 reveal" style="transition-delay: 0.2s;">
                    <div class="card-premium p-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class='bx bx-shield-quarter text-primary fs-2 me-3'></i>
                            <h5 class="fw-bold mb-0">Audit Aktivitas Pengguna</h5>
                        </div>
                        <p class="text-muted mb-0">Pencatatan rekam jejak sistematis untuk setiap aksi krusial sistem
                            guna menunjang transparansi tata kelola korporat yang akuntabel.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="alur" class="section-padding bg-corporate-dark">
        <div class="container-xxl text-center">
            <span class="text-primary fw-bold tracking-wide text-uppercase small">Standard Operating Procedure</span>
            <h2 class="fw-bolder text-white mt-2 mb-5">Siklus Anggaran Digital Terintegrasi</h2>

            <div class="row g-0 justify-content-center pt-4">
                <div class="col-md-6 col-lg-3 step-wrapper reveal">
                    <div class="step-line"></div>
                    <div class="d-flex flex-column align-items-center px-4">
                        <div class="step-number">1</div>
                        <h5 class="fw-bold text-white mb-2">Integrasi Mobile</h5>
                        <p class="text-light small" style="opacity: 0.8;">Staf mengunggah rincian kebutuhan dana
                            beserta foto struk secara real-time via aplikasi ponsel.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 step-wrapper reveal" style="transition-delay: 0.1s;">
                    <div class="step-line"></div>
                    <div class="d-flex flex-column align-items-center px-4">
                        <div class="step-number">2</div>
                        <h5 class="fw-bold text-white mb-2">Validasi Hierarkis</h5>
                        <p class="text-light small" style="opacity: 0.8;">Manajer Multi-Divisi meninjau notifikasi dan
                            memverifikasi kelayakan berdasarkan sisa pagu.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 step-wrapper reveal" style="transition-delay: 0.2s;">
                    <div class="step-line"></div>
                    <div class="d-flex flex-column align-items-center px-4">
                        <div class="step-number">3</div>
                        <h5 class="fw-bold text-white mb-2">Pencairan Sistematis</h5>
                        <p class="text-light small" style="opacity: 0.8;">Sistem memotong saldo secara atomik saat
                            disetujui, menjamin keseimbangan buku besar tanpa selisih.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 step-wrapper reveal" style="transition-delay: 0.3s;">
                    <div class="d-flex flex-column align-items-center px-4">
                        <div class="step-number">4</div>
                        <h5 class="fw-bold text-white mb-2">Pelaporan Otomatis</h5>
                        <p class="text-light small" style="opacity: 0.8;">Admin mengekspor Laporan Pertanggungjawaban
                            (LPJ) PDF yang merangkum seluruh rekam jejak audit.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="pembaruan" class="section-padding bg-white">
        <div class="container-xxl">
            <div class="text-center mb-5 reveal">
                <span class="text-primary fw-bold tracking-wide text-uppercase small">Development Journey</span>
                <h2 class="fw-bolder text-dark mt-2 mb-3">Log Pembaruan Sistem</h2>
                <p class="text-muted mx-auto fs-6" style="max-width: 600px;">
                    Riwayat pengembangan dan optimasi arsitektur sistem SyncBudget untuk mencapai standar fungsionalitas
                    tingkat korporat.
                </p>
            </div>

            <div class="timeline-container reveal">
                <div class="custom-timeline">
                    <div class="timeline-item">
                        <div class="timeline-indicator"></div>
                        <div class="timeline-content">
                            <span class="timeline-date">23 Maret 2026</span>
                            <h5 class="fw-bold mb-3">Enterprise Architecture, Soft Deletes & UI Integration</h5>
                            <ul class="timeline-list">
                                <li><strong>Pre-emptive Budget Locking:</strong> Implementasi
                                    <code>DB::transaction()</code> dan <code>lockForUpdate()</code> pada persetujuan
                                    dana untuk mencegah kebocoran saldo akibat <i>race condition</i>.
                                </li>
                                <li><strong>Multi-Division Manager:</strong> Skema pengawasan Many-to-Many via tabel
                                    pivot berbasis UUID.</li>
                                <li><strong>Global Soft Deletes:</strong> Proteksi data historis dan laporan dari
                                    kerusakan akibat <i>Cascade Delete</i>.</li>
                                <li><strong>Automated LPJ PDF:</strong> Integrasi <code>laravel-dompdf</code> dengan
                                    desain Modern Corporate dan <i>Memory Chunking</i>.</li>
                                <li><strong>Database Normalization:</strong> Pemisahan master data Kategori dan Tahun
                                    Anggaran secara komprehensif.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-indicator"></div>
                        <div class="timeline-content">
                            <span class="timeline-date">21 Maret 2026</span>
                            <h5 class="fw-bold mb-3">Master Budgeting & Core Reimbursement Engine</h5>
                            <ul class="timeline-list">
                                <li><strong>Core Reimbursement Engine:</strong> Pembuatan sistem Pengajuan Dana dengan
                                    Role-Based Access Control mutlak.</li>
                                <li><strong>Financial Automation:</strong> Penerapan logika bisnis untuk kalkulasi dan
                                    pemotongan saldo pagu (<code>used_amount</code>) secara dinamis.</li>
                                <li><strong>Global Localization:</strong> Konfigurasi zona waktu
                                    <code>Asia/Jakarta</code> dan translasi validasi ke bahasa Indonesia baku.
                                </li>
                                <li><strong>Advanced Data Filtering:</strong> Fitur pencarian dan filter multi-kolom
                                    untuk pemantauan audit log dan status pengajuan.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="timeline-item">
                        <div class="timeline-indicator"></div>
                        <div class="timeline-content">
                            <span class="timeline-date">20 Maret 2026</span>
                            <h5 class="fw-bold mb-3">Initial Backend, Auth & Core Foundation</h5>
                            <ul class="timeline-list">
                                <li><strong>Security Layer:</strong> Setup Spatie Roles & Permissions (Admin, Manager,
                                    Staff) dan <i>Role-based redirection</i>.</li>
                                <li><strong>Secure Data Import:</strong> Fitur import pengguna massal dilengkapi
                                    sanitasi anti-CSV Injection.</li>
                                <li><strong>Audit Trail Foundation:</strong> Integrasi Spatie Activitylog untuk
                                    perekaman diam-diam atas semua jejak perubahan data.</li>
                                <li><strong>Database Setup:</strong> Migrasi infrastruktur PostgreSQL dengan struktur
                                    relasional awal dan data <i>seeding</i> otomatis.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-4 bg-white border-top">
        <div class="container-xxl">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start small text-muted">
                    &copy; 2026 SyncBudget Enterprise. Hak Cipta Dilindungi.
                </div>
                <div class="col-md-6 text-center text-md-end small mt-2 mt-md-0 fw-semibold text-dark">
                    Sistem Informasi Bisnis - Politeknik Negeri Malang
                </div>
            </div>
        </div>
    </footer>

    <button id="btnBackToTop" title="Kembali ke atas">
        <i class="bx bx-up-arrow-alt fs-3"></i>
    </button>

    <script src="{{ asset('sneat/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/js/bootstrap.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar-landing');
            const mybutton = document.getElementById("btnBackToTop");
            const reveals = document.querySelectorAll(".reveal");

            function reveal() {
                for (let i = 0; i < reveals.length; i++) {
                    let windowHeight = window.innerHeight;
                    let elementTop = reveals[i].getBoundingClientRect().top;
                    let elementVisible = 50;

                    if (elementTop < windowHeight - elementVisible) {
                        reveals[i].classList.add("active");
                    }
                }
            }

            window.addEventListener("scroll", function() {
                reveal();

                if (window.scrollY > 50) {
                    navbar.style.padding = "10px 0";
                    navbar.style.boxShadow = "0 4px 20px rgba(0,0,0,0.08)";
                } else {
                    navbar.style.padding = "16px 0";
                    navbar.style.boxShadow = "none";
                }

                if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                    mybutton.style.display = "flex";
                } else {
                    mybutton.style.display = "none";
                }
            });

            mybutton.addEventListener("click", function() {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });

            reveal();
        });
    </script>
</body>

</html>
