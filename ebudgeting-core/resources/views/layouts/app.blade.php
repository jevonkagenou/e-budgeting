<!doctype html>
<html lang="id" class="layout-menu-fixed layout-compact" data-assets-path="{{ asset('sneat/assets/') }}/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Dashboard | SyncBudget</title>
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
    <style>
        .swal2-container {
            z-index: 10000 !important;
        }

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
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.sidebar')
            <div class="layout-page">
                @include('layouts.navbar')
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <footer class="content-footer footer bg-footer-theme">
                        <div
                            class="container-xxl d-flex flex-wrap justify-content-between py-4 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script> SyncBudget.
                            </div>
                        </div>
                    </footer>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

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

        @if (session('error'))
            <div id="errorToast" class="toast fade p-1" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex bg-danger rounded shadow align-items-center">
                    <div class="toast-body d-flex w-100 align-items-center text-white py-3 px-3">
                        <i class="bx bx-error-circle fs-3 me-3"></i>
                        <span class="fw-medium">{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white-custom me-3" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <script src="{{ asset('sneat/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('sneat/assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('sneat/assets/js/main.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Keluar Aplikasi?',
                text: "Sesi Anda akan diakhiri.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#696cff',
                cancelButtonColor: '#ff3e1d',
                confirmButtonText: 'Ya, Keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            })
        }

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
