<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>TuNetic</title>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <style>
        .main-sidebar {
            background-color: #299e63 !important;
        }

        .main-header {
            background-color: #299e63 !important;
        }

        .main-sidebar .nav-sidebar>.nav-item>.nav-link.active {
            background-color: #25a05b !important;
            color: #fff !important;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto ">
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="user-image img-circle elevation-2"
                            alt="User Image">
                        
                        {{-- Cek apakah ada user login atau tidak --}}
                        <span class="d-none d-md-inline text-white">
                            @auth
                                {{-- Jika login, tampilkan nama --}}
                                {{ Auth::user()->name }}
                            @else
                                {{-- Jika tidak login (tamu), tampilkan teks lain --}}
                                Pengunjung
                            @endauth
                        </span>
                        </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

                        <li class="user-header text-white" style="background-color: #299e63;">
                            <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-3" alt="User Image">
                            
                            {{-- Cek lagi di sini untuk keamanan --}}
                            <p>
                                @auth
                                    {{ Auth::user()->name }}
                                    <small>Member Sejak {{ Auth::user()->created_at->format('M. Y') }}</small>
                                @else
                                    Selamat Datang!
                                @endauth
                            </p>
                             </li>

                        {{-- Tampilkan footer user (tombol keluar) hanya jika sedang login --}}
                        @auth
                        <li class="user-footer">
                            {{-- <a href="{{ route('masyarakat.profile.index') }}" class="btn btn-default btn-flat">Profil</a> --}}
                            <a href="#" class="btn btn-success btn-flat float-right" data-toggle="modal"
                                data-target="#modal-logout"><i class="fas fa-sign-out-alt"></i> <span>Keluar</span></a>
                        </li>
                        @endauth

                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="modal fade" id="modal-logout" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <div class="modal-body text-center">
                            <h5>Apakah anda ingin keluar ?</h5>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-sm btn-default btn-flat"
                                data-dismiss="modal">Tidak</button>
                            <a class="btn btn-sm btn-flat float-right" style="background-color: #299e63; color: #fff;"
                                href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                <span>Ya, Keluar</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <aside class="main-sidebar main-sidebar-custom sidebar-dark-info elevation-4">
            <a href="{{ url('/') }}" class="brand-link d-flex justify-content-center align-items-center">
                
                {{-- Cek juga di sini agar tidak error saat diakses tamu --}}
                @auth
                    @php
                        $user = Auth::user();
                        $roleName = $user->getRoleNames()->first(); // Ambil 1 role pertama

                        $panelText = match ($roleName) {
                            'admin_pusat' => 'ADMIN PANEL',
                            'admin_tps', 'admin_tpst' => 'ADMIN TPS/TPST',
                            'petugas' => 'PETUGAS',
                            'user' => 'WARGA',
                            default => 'USER',
                        };
                    @endphp
                    <span class="brand-text font-weight-bold text-white">{{ $panelText }}</span>
                @else
                    {{-- Tampilkan logo atau teks default jika tamu --}}
                    <span class="brand-text font-weight-bold text-white">TuNetic</span>
                @endauth
                </a>
            <div class="sidebar">
                <nav class="mt-2">
                    {{-- Sidebar hanya tampil jika user login --}}
                    @auth
                        @include('layouts.sidebar')
                    @endauth
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            @yield('content')
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0
            </div>
            <strong>&copy; {{ date('Y') }} <i>TuNetic </i> by Kelompok 3 </strong>
        </footer>
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
    @stack('js')
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
    <script>
        $(function() {
            $("#datatable-main").DataTable({
                "responsive": true,
                lengthMenu: [
                    [50, 100, 200, -1],
                    [50, 100, 200, 'All']
                ],
                pageLength: 50,
                //"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#datatable-main_wrapper .col-md-6:eq(0)');

            $('#datatable-sub').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });

        $('.confirm-button').click(function(event) {
            var form = $(this).closest("form");
            event.preventDefault();
            swal({
                    title: `Hapus data`,
                    icon: "warning",
                    buttons: {
                        confirm: {
                            text: 'Ya'
                        },
                        cancel: 'Tidak'
                    },
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
        });
    </script>

    @stack('scripts') </body>

</html>