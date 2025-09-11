<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('adminasset/assets/') }}" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'Dashboard - Admin Panel')</title>
    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('foto/logo.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('adminasset/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('adminasset/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('adminasset/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('adminasset/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('adminasset/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('adminasset/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->
    @yield('styles')

    <!-- Helpers -->
    <script src="{{ asset('adminasset/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('adminasset/assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="{{ url('/') }}" class="app-brand-link">
                        <span class="app-brand-text demo menu-text fw-bolder text-uppercase ms-2"> POS</span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
                        <a href="{{ url('dashboard') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>

                    @if(Auth::user()->role == 'Admin')
                    <!-- Data Management -->
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Data Management</span></li>
                    <li class="menu-item {{ request()->is('barang*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-box"></i>
                            <div data-i18n="Kamar">Kelola Barang</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('tambahbarang') ? 'active' : '' }}">
                            <a href="{{ url('tambahbarang') }}" class="menu-link">
                                <div data-i18n="Tambah Barang">Tambah Barang</div>
                            </a>
                            </li>
                            <li class="menu-item {{ request()->is('barang') ? 'active' : '' }}">
                            <a href="{{ url('barang') }}" class="menu-link">
                                <div data-i18n="Daftar Barang">Daftar Barang</div>
                            </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item {{ request()->is('stok*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-gift"></i>
                            <div data-i18n="stok">Kelola Stok</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('tambahstok') ? 'active' : '' }}">
                                <a href="{{ url('tambahstok') }}" class="menu-link">
                                    <div data-i18n="Tambah Stok">Tambah Stok</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('stok') ? 'active' : '' }}">
                                <a href="{{ url('stok') }}" class="menu-link">
                                    <div data-i18n="Daftar Stok">Daftar Stok</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                      <li class="menu-item {{ request()->is('tamu*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-book"></i>
                            <div data-i18n="Tamu">Kelola Penjualan</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('tambahtamu') ? 'active' : '' }}">
                                <a href="{{ url('tambahtamu') }}" class="menu-link">
                                    <div data-i18n="Tambah Tamu">Tambah Penjualan</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('tamu') ? 'active' : '' }}">
                                <a href="{{ url('tamu') }}" class="menu-link">
                                    <div data-i18n="Daftar tamu">Daftar Pelanggan</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item {{ request()->is('booking*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-book-alt"></i>
                            <div data-i18n="Pemesanan">Kelola Pembelian</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('bookingtambah') ? 'active' : '' }}">
                                <a href="{{ url('bookingtambah') }}" class="menu-link">
                                    <div data-i18n="Tambah Pemesanan">Tambah Pembelian</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('booking') ? 'active' : '' }}">
                                <a href="{{ url('booking') }}" class="menu-link">
                                    <div data-i18n="Daftar Pemesanan">Daftar Pembelian</div>
                                </a>
                            </li>
                        </ul>
                    </li>


                    <li class="menu-item {{ request()->is('tamu*') ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-user"></i>
                            <div data-i18n="Tamu">Pelanggan</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item {{ request()->is('tambahtamu') ? 'active' : '' }}">
                                <a href="{{ url('tambahtamu') }}" class="menu-link">
                                    <div data-i18n="Tambah Tamu">Tambah Pelanggan</div>
                                </a>
                            </li>
                            <li class="menu-item {{ request()->is('tamu') ? 'active' : '' }}">
                                <a href="{{ url('tamu') }}" class="menu-link">
                                    <div data-i18n="Daftar tamu">Daftar Pelanggan</div>
                                </a>
                            </li>
                        </ul>
                    </li>
{{-- 

                    <li class="menu-item {{ request()->is('laporantamu') ? 'active' : '' }}">
                        <a href="{{ url('laporantamu') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-file"></i>
                            <div data-i18n="Laporan Tamu">Laporan Tamu</div>
                        </a>
                    </li> --}}
                    <li class="menu-item {{ request()->is('laporankunjungan') ? 'active' : '' }}">
                        <a href="{{ url('laporankunjungan') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-line-chart"></i>
                            <div data-i18n="Laporan Kunjungan">Laporan</div>
                        </a>
                    </li>


                    @endif

                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('adminasset/assets/img/avatars/profile.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ asset('adminasset/assets/img/avatars/profile.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                                    <small class="text-muted">{{ Auth::user()->role }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider"></div></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ url('profile') }}">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">Profil Saya</span>
                                        </a>
                                    </li>
                                    <li><div class="dropdown-divider"></div></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Keluar</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                © <script>document.write(new Date().getFullYear());</script>, Point Of Sale
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="{{ asset('adminasset/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('adminasset/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('adminasset/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('adminasset/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('adminasset/assets/vendor/js/menu.js') }}"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('adminasset/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('adminasset/assets/js/main.js') }}"></script>

    <!-- Page JS -->
    @yield('script')
</body>
</html>