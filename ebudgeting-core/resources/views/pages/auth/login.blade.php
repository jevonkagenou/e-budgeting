<!doctype html>
<html lang="id" class="layout-wide customizer-hide" data-assets-path="{{ asset('sneat/assets/') }}/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Masuk | SyncBudget - Sistem Informasi Bisnis</title>
    <meta name="description" content="Halaman Login Sistem SyncBudget" />
    <link rel="icon" type="image/x-icon" href="{{ asset('sneat/assets/img/favicon/favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/fonts/iconify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('sneat/assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('sneat/assets/vendor/css/pages/page-auth.css') }}" />
    <style>
        .custom-toast-z {
            z-index: 999999 !important;
        }

        .btn-close-white-custom {
            filter: invert(1) grayscale(1) brightness(10);
            opacity: 1;
        }
    </style>
    <script src="{{ asset('sneat/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('sneat/assets/js/config.js') }}"></script>
</head>

<body>
    <div class="toast-container position-fixed top-0 end-0 custom-toast-z">
        @if (session('success'))
            <div id="successToast" class="toast fade p-1" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex bg-success rounded shadow align-items-center">
                    <div class="toast-body d-flex w-100 align-items-center text-white py-3 px-3">
                        <i class="bx bx-check-circle fs-3 me-3"></i>
                        <span class="fw-medium">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white-custom me-3" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if ($errors->has('email') && request()->isMethod('post'))
            <div id="errorToast" class="toast fade p-1" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex bg-danger rounded shadow align-items-center">
                    <div class="toast-body d-flex w-100 align-items-center text-white py-3 px-3">
                        <i class="bx bx-error-circle fs-3 me-3"></i>
                        <span class="fw-medium">Kredensial tidak valid. Periksa kembali email dan kata sandi
                            Anda.</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white-custom me-3" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <div class="app-brand justify-content-center">
                            <a href="/" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <span class="text-primary">
                                        <svg width="25" viewBox="0 0 25 42" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <defs>
                                                <path
                                                    d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z"
                                                    id="path-1"></path>
                                            </defs>
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-27.000000, -15.000000)">
                                                    <g transform="translate(27.000000, 15.000000)">
                                                        <g transform="translate(0.000000, 8.000000)">
                                                            <mask id="mask-2" fill="white">
                                                                <use href="#path-1"></use>
                                                            </mask>
                                                            <use fill="currentColor" href="#path-1"></use>
                                                        </g>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                </span>
                                <span class="app-brand-text demo text-heading fw-bold">SyncBudget</span>
                            </a>
                        </div>

                        <h4 class="mb-1">Selamat Datang di SyncBudget!</h4>
                        <p class="mb-6">Silakan masuk ke akun Anda untuk mengakses sistem</p>

                        <form id="formAuthentication" class="mb-6" action="{{ route('login.post') }}" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="Masukkan email Anda" autofocus
                                    value="{{ old('email') }}" required />
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-6 form-password-toggle">
                                <label class="form-label" for="password">Kata Sandi</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        required />
                                    <span class="input-group-text cursor-pointer"><i
                                            class="icon-base bx bx-hide"></i></span>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-6">
                                <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
                            </div>
                        </form>

                        <p class="text-center">
                            <a href="/">
                                <span>Kembali ke Beranda</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('sneat/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('sneat/assets/js/main.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const successToastEl = document.getElementById('successToast');
            if (successToastEl) {
                const toast = new bootstrap.Toast(successToastEl, {
                    delay: 3500
                });
                toast.show();
            }

            const errorToastEl = document.getElementById('errorToast');
            if (errorToastEl) {
                const toast = new bootstrap.Toast(errorToastEl, {
                    delay: 3500
                });
                toast.show();
            }
        });
    </script>
</body>

</html>
