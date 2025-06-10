<!doctype html>
<html lang="en" class="color-sidebar sidebarcolor1">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ url('') }}/assets/images/favicon-32x32.png" type="image/png" />
    <!--plugins-->
    <link href="{{ url('') }}/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="{{ url('') }}/assets/plugins/fullcalendar/css/main.min.css" rel="stylesheet" />
    <link href="{{ url('') }}/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="{{ url('') }}/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="{{ url('') }}/assets/css/pace.min.css" rel="stylesheet" />
    <script src="{{ url('') }}/assets/js/pace.min.js"></script>
    <link href="{{ url('') }}/assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="{{ url('') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ url('') }}/assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ url('') }}/assets/css/app.css" rel="stylesheet">
    <link href="{{ url('') }}/assets/css/icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ url('') }}/assets/css/dark-theme.css" />
    <link rel="stylesheet" href="{{ url('') }}/assets/css/semi-dark.css" />
    <link rel="stylesheet" href="{{ url('') }}/assets/css/header-colors.css" />
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="{{ url('') }}/assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h5 class="logo-text" style="font-size: 15px !important">CV. Mugi Jaya</h5>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
                </div>
            </div>
            <ul class="metismenu" id="menu">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <div class="parent-icon"><i class='bx bx-home-circle'></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>
                @if (Auth::user()->role == 'karyawan')
                    <li>
                        <a href="{{ route('karyawan.riwayat-gaji') }}">
                            <div class="parent-icon"><i class='bx bx-book-bookmark'></i>
                            </div>
                            <div class="menu-title">Riwayat Gaji</div>

                        </a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('absensi') }}">
                            <div class="parent-icon"><i class='bx bx-list-check'></i>
                            </div>
                            <div class="menu-title">Absensi</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('potongan') }}">
                            <div class="parent-icon"><i class='bx bx-cut'></i>
                            </div>
                            <div class="menu-title">Potongan</div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('gaji.index') }}">
                            <div class="parent-icon"><i class='bx bx-book-bookmark'></i>
                            </div>
                            <div class="menu-title">Rekap Gaji</div>

                        </a>
                    </li>

                    <li>
                        <a href="javascript:void(0)" class="has-arrow">
                            <div class="parent-icon"><i class="bx bx-group"></i>
                            </div>
                            <div class="menu-title">Karyawan</div>
                        </a>
                        <ul class="mm-collapse">
                            <li> <a href="{{ route('users.jabatan') }}"><i
                                        class="bx bx-right-arrow-alt"></i>Jabatan</a>
                            </li>
                            <li> <a href="{{ route('users.karyawan') }}"><i
                                        class="bx bx-right-arrow-alt"></i>Karyawan</a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                    </div>
                    <div class="search-bar flex-grow-1" style="display: none">
                        <div class="position-relative search-bar-box">
                            <input type="text" class="form-control search-control" placeholder="Type to search...">
                            <span class="position-absolute top-50 search-show translate-middle-y"><i
                                    class='bx bx-search'></i></span>
                            <span class="position-absolute top-50 search-close translate-middle-y"><i
                                    class='bx bx-x'></i></span>
                        </div>
                    </div>
                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item mobile-search-icon">
                                <a class="nav-link" href="#"> <i class='bx bx-search'></i>
                                </a>
                            </li>
                            <li class="nav-item dropdown dropdown-large" style="display: none">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false"> <i
                                        class='bx bx-category'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="row row-cols-3 g-3 p-3">
                                        <div class="col text-center">
                                            <div class="app-box mx-auto bg-gradient-cosmic text-white"><i
                                                    class='bx bx-group'></i>
                                            </div>
                                            <div class="app-title">Teams</div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown dropdown-large" style="display: none">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="alert-count">7</span>
                                    <i class='bx bx-bell'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Notifications</p>
                                            <p class="msg-header-clear ms-auto">Marks all as read</p>
                                        </div>
                                    </a>
                                    <div class="header-notifications-list">
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-primary text-primary"><i
                                                        class="bx bx-group"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Customers<span
                                                            class="msg-time float-end">14 Sec
                                                            ago</span></h6>
                                                    <p class="msg-info">5 new user registered</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <a href="javascript:;">
                                        <div class="text-center msg-footer">View All Notifications</div>
                                    </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown dropdown-large" style="display: none">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="alert-count">8</span>
                                    <i class='bx bx-comment'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Messages</p>
                                            <p class="msg-header-clear ms-auto">Marks all as read</p>
                                        </div>
                                    </a>
                                    <div class="header-message-list">
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="assets/images/avatars/avatar-1.png" class="msg-avatar"
                                                        alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Daisy Anderson <span
                                                            class="msg-time float-end">5 sec
                                                            ago</span></h6>
                                                    <p class="msg-info">The standard chunk of lorem</p>
                                                </div>
                                            </div>
                                        </a>

                                    </div>
                                    <a href="javascript:;">
                                        <div class="text-center msg-footer">View All Messages</div>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if (Auth::user()->role == 'karyawan')
                                <img src="{{ asset('assets/images/avatars/icons8-employee-96.png') }}"
                                    class="user-img" alt="user avatar">
                            @elseif(Auth::user()->role == 'admin')
                                <img src="{{ asset('assets/images/avatars/icons8-admin-96.png') }}" class="user-img"
                                    alt="user avatar">
                            @elseif(Auth::user()->role == 'owner')
                                <img src="{{ asset('assets/images/avatars/icons8-owner-64.png') }}" class="user-img"
                                    alt="user avatar">
                            @endif
                            <div class="user-info ps-3">
                                <p class="user-name mb-0">{{ Auth::user()->name }}</p>
                                <p class="designattion mb-0" style="text-transform:capitalize">
                                    {{ Auth::user()->username }}</p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:;" data-bs-toggle="modal"
                                    data-bs-target="#gantiPasswordModal"><i class="bx bx-user"></i><span>Ganti
                                        Password</span></a>
                            </li>
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"
                                        style="border:none; background:none; padding:0; cursor:pointer;">
                                        <i class='bx bx-log-out-circle'></i> <span>Logout</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <div class="page-wrapper">
            <div class="page-content">
                {{ $slot }}
            </div>
        </div>
        <div class="overlay toggle-icon"></div>
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <footer class="page-footer">
            <p class="mb-0">Copyright © 2021. All right reserved.</p>
        </footer>
    </div>
    <div class="modal fade" id="gantiPasswordModal" tabindex="-1" aria-labelledby="gantiPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="/ganti-password" method="POST" id="formGantiPassword">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="gantiPasswordModalLabel">Ganti Password</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Lama</label>
                            <input type="password" class="form-control" id="current_password"
                                name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="new_password" name="new_password"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ url('') }}/assets/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('') }}/assets/js/jquery.min.js"></script>
    <script src="{{ url('') }}/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="{{ url('') }}/assets/plugins/fullcalendar/js/main.min.js"></script>
    <script src="{{ url('') }}/assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="{{ url('') }}/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="{{ url('') }}/assets/plugins/datatable/js/jquery.dataTables.min.js"></script>
    <script src="{{ url('') }}/assets/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    @if (session('error') || session('success') || $errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('gantiPasswordModal'));
                modal.show();
            });
        </script>
    @endif
    @stack('scripts')
    <script src="{{ url('') }}/assets/js/app.js"></script>
</body>

</html>
