<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sistem Manajemen Perpustakaan</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        main {
            flex: 1;
        }
        
        .footer {
            margin-top: auto;
        }
        
        .navbar-brand {
            font-weight: bold;
        }
        
        .dropdown-menu {
            border-radius: 0;
        }
        
        .nav-link:hover {
            background-color: rgba(0,0,0,0.05);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <i class="fas fa-book-open me-2"></i>
                    Perpustakaan
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('home') }}">Beranda</a>
                        </li>
                        @auth
                            @if(Auth::user()->role === 'anggota')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('anggota.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('anggota.katalog.index') }}">Katalog Buku</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="peminjamanDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Peminjaman
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="peminjamanDropdown">
                                        <li><a class="dropdown-item" href="{{ route('anggota.peminjaman.aktif') }}">Peminjaman Aktif</a></li>
                                        <li><a class="dropdown-item" href="{{ route('anggota.peminjaman.riwayat') }}">Riwayat Peminjaman</a></li>
                                        <li><a class="dropdown-item" href="{{ route('anggota.peminjaman.terlambat') }}">Peminjaman Terlambat</a></li>
                                        <li><a class="dropdown-item" href="{{ route('anggota.peminjaman.denda') }}">Denda</a></li>
                                    </ul>
                                </li>
                            @elseif(Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                            @elseif(Auth::user()->role === 'staff')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('staff.dashboard') }}">Dashboard</a>
                                </li>
                            @endif
                        @endauth
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Register</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->role === 'admin')
                                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                                    @elseif(Auth::user()->role === 'staff')
                                        <li><a class="dropdown-item" href="{{ route('staff.dashboard') }}">Dashboard Staff</a></li>
                                    @else
                                        <li><a class="dropdown-item" href="{{ route('anggota.dashboard') }}">Dashboard Anggota</a></li>
                                        <li><a class="dropdown-item" href="{{ route('anggota.profil') }}">Profil Saya</a></li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="py-4">
        @if(session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>

    <footer class="footer bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Sistem Manajemen Perpustakaan</h5>
                    <p>Solusi lengkap untuk pengelolaan perpustakaan modern.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; {{ date('Y') }} Perpustakaan. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>