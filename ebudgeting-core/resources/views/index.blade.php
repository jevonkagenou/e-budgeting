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
            scroll-padding-top: 75px;
        }

        body {
            font-family: 'Public Sans', sans-serif;
            background-color: #ffffff;
            overflow-x: hidden;
        }

        .navbar-landing {
            background-color: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .hero-fullscreen {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            padding-top: 80px;
            padding-bottom: 120px;
            background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
            z-index: 1;
            overflow: hidden;
        }

        .hero-blob-1 {
            position: absolute;
            top: -10%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: rgba(105, 108, 255, 0.15);
            border-radius: 50%;
            filter: blur(60px);
            z-index: 0;
            animation: pulseBlob 8s infinite alternate ease-in-out;
        }

        .hero-blob-2 {
            position: absolute;
            bottom: -10%;
            right: -5%;
            width: 500px;
            height: 500px;
            background: rgba(0, 210, 255, 0.15);
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            animation: pulseBlob 10s infinite alternate-reverse ease-in-out;
        }

        @keyframes pulseBlob {
            0% { transform: scale(1) translate(0, 0); opacity: 0.6; }
            100% { transform: scale(1.1) translate(20px, -20px); opacity: 1; }
        }

        .text-gradient {
            background: linear-gradient(135deg, #696cff 0%, #00d2ff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: inline-block;
        }

        .img-floating {
            animation: floatImage 6s ease-in-out infinite;
        }

        @keyframes floatImage {
            0% { transform: perspective(1000px) rotateY(-5deg) translateY(0px); }
            50% { transform: perspective(1000px) rotateY(-5deg) translateY(-20px); }
            100% { transform: perspective(1000px) rotateY(-5deg) translateY(0px); }
        }

        .btn-glow {
            transition: all 0.3s ease;
        }

        .btn-glow:hover {
            box-shadow: 0 8px 25px rgba(105, 108, 255, 0.5);
            transform: translateY(-3px) scale(1.02);
        }

        .content-section {
            padding: 100px 0;
            position: relative;
            display: flex;
            align-items: center;
        }

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
            fill: #FFFFFF;
        }

        .feature-card {
            transition: all 0.3s ease;
            border: 1px solid #f0f2f5;
            border-radius: 12px;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(105, 108, 255, 0.1);
            border-color: #696cff;
        }

        .step-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: #e7e7ff;
            color: #696cff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
        }

        .step-card:hover .step-circle {
            background-color: #696cff;
            color: #ffffff;
            transform: scale(1.1);
            box-shadow: 0 10px 20px rgba(105, 108, 255, 0.3);
        }

        #btnBackToTop {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            z-index: 999;
            box-shadow: 0 4px 10px rgba(105, 108, 255, 0.4);
            transition: all 0.3s ease;
            align-items: center !important;
            justify-content: center !important;
            background-color: #696cff;
            border: none;
            cursor: pointer;
        }

        #btnBackToTop i {
            font-size: 24px;
            color: white;
            margin: 0;
            padding: 0;
            line-height: 1;
        }

        #btnBackToTop:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(105, 108, 255, 0.6);
        }

        .alur-section {
            padding: 100px 0;
            position: relative;
        }

        .step-card {
            position: relative;
        }

        @media (min-width: 992px) {
            .step-card:not(:last-child)::after {
                content: '';
                position: absolute;
                top: 35px;
                right: -50%;
                width: 100%;
                height: 3px;
                background-color: #e7e7ff;
                z-index: 0;
            }
        }

        .step-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: #e7e7ff;
            color: #696cff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0 auto 20px;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .step-card:hover .step-circle {
            background-color: #696cff;
            color: #ffffff;
            transform: scale(1.1);
            box-shadow: 0 10px 20px rgba(105, 108, 255, 0.3);
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

                <span>SyncBudget<span class="text-dark"></span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto fw-medium text-dark">
                    <li class="nav-item px-2"><a class="nav-link text-dark" href="#beranda">Beranda</a></li>
                    <li class="nav-item px-2"><a class="nav-link text-dark" href="#tentang">Tentang Sistem</a></li>
                    <li class="nav-item px-2"><a class="nav-link text-dark" href="#layanan">Fitur MVP</a></li>
                    <li class="nav-item px-2"><a class="nav-link text-dark" href="#alur">Alur Prosedur</a></li>
                </ul>
                <a href="/login" class="btn btn-primary rounded-pill px-4 shadow-sm btn-glow">
                    Masuk <i class="bx bx-log-in-circle ms-1"></i>
                </a>
            </div>
        </div>
    </nav>

    <section id="beranda" class="hero-fullscreen">
        <div class="hero-blob-1"></div>
        <div class="hero-blob-2"></div>
        <div class="container-xxl relative" style="z-index: 1;">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0 text-center text-lg-start">
                    <span class="badge bg-label-primary rounded-pill px-3 py-2 mb-3">Sistem Informasi Bisnis</span>
                    <h1 class="display-4 fw-bolder text-dark mb-4" style="line-height: 1.2;">
                        Kendalikan Anggaran <br> <span class="text-gradient">Secara Akurat & Real-Time</span>
                    </h1>
                    <p class="lead text-muted mb-5 pe-lg-5">
                        Platform panel kendali manajemen keuangan terintegrasi. Dirancang untuk memastikan transparansi
                        alur dana, mencegah kebocoran anggaran, dan mempercepat proses persetujuan manajerial.
                    </p>
                    <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                        <a href="#layanan" class="btn btn-primary btn-lg rounded-pill px-5 btn-glow shadow">Eksplorasi
                            Sistem</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=800&auto=format&fit=crop"
                        alt="Dashboard Finance" class="img-fluid rounded-3 shadow-lg img-floating"
                        style="border: 8px solid white;">
                </div>
            </div>
        </div>

        <div class="custom-shape-divider-bottom">
            <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120"
                preserveAspectRatio="none">
                <path d="M0,0 C300,100 900,100 1200,0 L1200,120 L0,120 Z" class="shape-fill"></path>
            </svg>
        </div>
    </section>

    <section id="tentang" class="content-section bg-white">
        <div class="container-xxl">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-lg-6 mb-4 mb-lg-0 text-center">
                    <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?q=80&w=800&auto=format&fit=crop"
                        alt="Office Meeting" class="img-fluid rounded-3 shadow-sm">
                </div>
                <div class="col-lg-6 pe-lg-5 text-center text-lg-start">
                    <h6 class="text-primary fw-bold text-uppercase mb-2">Latar Belakang</h6>
                    <h2 class="fw-bold text-dark mb-4">Mendigitalisasi Proses Keuangan Tradisional</h2>
                    <p class="text-muted mb-4" style="text-align: justify;">
                        Sistem SyncBudget ini dikembangkan sebagai solusi atas lambatnya proses persetujuan anggaran
                        berbasis kertas. Kami membawa seluruh alur birokrasi ke dalam satu portal digital yang aman dan
                        dapat dilacak.
                    </p>
                    <p class="text-muted" style="text-align: justify;">
                        Dengan basis data terpusat, manajemen tingkat atas kini memiliki visibilitas penuh terhadap
                        kondisi finansial perusahaan, memungkinkan pengambilan keputusan strategis yang jauh lebih cepat
                        dan tepat sasaran.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="layanan" class="content-section" style="background-color: #fcfcfc;">
        <div class="container-xxl">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark">6 Fitur Inti Sistem SyncBudget</h2>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 feature-card p-4">
                        <div class="card-body">
                            <i class='bx bx-sitemap text-primary mb-3' style="font-size: 3rem;"></i>
                            <h5 class="fw-bold">Approval Berjenjang</h5>
                            <p class="text-muted mb-0">Alur persetujuan dinamis (Staff &rarr; Supervisor &rarr;
                                Manager)
                                yang tersistematis dan anti-langkau.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 feature-card p-4">
                        <div class="card-body">
                            <i class='bx bx-line-chart text-primary mb-3' style="font-size: 3rem;"></i>
                            <h5 class="fw-bold">Pemantauan Pagu</h5>
                            <p class="text-muted mb-0">Kalkulasi otomatis sisa anggaran divisi. Sistem menolak
                                pengajuan jika pagu dana tidak mencukupi.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 feature-card p-4">
                        <div class="card-body">
                            <i class='bx bxs-file-pdf text-primary mb-3' style="font-size: 3rem;"></i>
                            <h5 class="fw-bold">Ekspor Dokumen PDF</h5>
                            <p class="text-muted mb-0">Hasilkan laporan pertanggungjawaban dan rekapitulasi pengeluaran
                                bulanan dalam format PDF dengan satu klik.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 feature-card p-4">
                        <div class="card-body">
                            <i class='bx bx-user-circle text-primary mb-3' style="font-size: 3rem;"></i>
                            <h5 class="fw-bold">Manajemen Peran (RBAC)</h5>
                            <p class="text-muted mb-0">Pembatasan hak akses yang ketat. Setiap tingkat jabatan memiliki
                                tampilan dasbor dan otoritas yang berbeda.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 feature-card p-4">
                        <div class="card-body">
                            <i class='bx bx-bell text-primary mb-3' style="font-size: 3rem;"></i>
                            <h5 class="fw-bold">Notifikasi Status</h5>
                            <p class="text-muted mb-0">Pemberitahuan real-time saat pengajuan dana ditolak, direvisi,
                                atau berhasil dicairkan oleh bagian keuangan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 feature-card p-4">
                        <div class="card-body">
                            <i class='bx bx-history text-primary mb-3' style="font-size: 3rem;"></i>
                            <h5 class="fw-bold">Jejak Audit Aktivitas</h5>
                            <p class="text-muted mb-0">Setiap aksi (input, edit, hapus, validasi) dicatat beserta waktu
                                dan identitas penggunanya untuk transparansi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="alur" class="alur-section bg-white">
        <div class="container-xxl text-center">
            <h6 class="text-primary fw-bold text-uppercase mb-2">Standard Operating Procedure</h6>
            <h2 class="fw-bold text-dark mb-5">Siklus Anggaran Dalam Sistem</h2>

            <div class="row g-4 justify-content-center">
                <div class="col-sm-6 col-lg-3 step-card">
                    <div class="step-circle shadow-sm">1</div>
                    <h5 class="fw-bold mb-3">Input Pengajuan</h5>
                    <p class="text-muted small px-3">Pengguna divisi mengunggah rincian kebutuhan dana beserta dokumen pendukung (RAB).</p>
                </div>
                <div class="col-sm-6 col-lg-3 step-card">
                    <div class="step-circle shadow-sm">2</div>
                    <h5 class="fw-bold mb-3">Proses Validasi</h5>
                    <p class="text-muted small px-3">Sistem meneruskan pengajuan ke manajer terkait untuk meninjau kesesuaian nominal.</p>
                </div>
                <div class="col-sm-6 col-lg-3 step-card">
                    <div class="step-circle shadow-sm">3</div>
                    <h5 class="fw-bold mb-3">Pencairan Dana</h5>
                    <p class="text-muted small px-3">Status disetujui, bagian keuangan mencairkan dana. Sistem memotong saldo pagu instan.</p>
                </div>
                <div class="col-sm-6 col-lg-3 step-card">
                    <div class="step-circle shadow-sm">4</div>
                    <h5 class="fw-bold mb-3">Laporan (LPJ)</h5>
                    <p class="text-muted small px-3">Realisasi dana diunggah kembali beserta nota sebagai syarat penutupan siklus.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="pt-5 pb-4" style="background-color: #232333; color: #a1b0cb;">
        <div class="container-xxl">
            <div class="row align-items-center mt-2">
                <div class="col-md-6 text-center text-md-start small">
                    © 2026 SyncBudget. Hak Cipta Dilindungi.
                </div>
                <div class="col-md-6 text-center text-md-end small mt-3 mt-md-0">
                    Sistem Informasi Bisnis - Politeknik Negeri Malang
                </div>
            </div>
        </div>
    </footer>

    <button id="btnBackToTop" class="btn btn-primary rounded-circle shadow" title="Kembali ke atas">
        <i class="bx bx-up-arrow-alt text-white"></i>
    </button>

    <script src="{{ asset('sneat/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/js/bootstrap.js') }}"></script>

    <script>
        let mybutton = document.getElementById("btnBackToTop");

        window.onscroll = function() {
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                mybutton.style.display = "flex";
            } else {
                mybutton.style.display = "none";
            }
        };

        mybutton.addEventListener("click", function() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    </script>
</body>

</html>
