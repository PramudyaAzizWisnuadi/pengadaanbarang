<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Dashboard') - Sistem Pengadaan Barang</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
            rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Custom CSS -->
        <style>
            :root {
                --sidebar-width: 260px;
                --sidebar-bg: #1e2139;
                --sidebar-hover: #282a47;
                --sidebar-active: #3b82f6;
                --sidebar-text: #9ca3af;
                --sidebar-text-active: #ffffff;
            }

            body {
                background-color: #f8fafc;
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            }

            .sidebar {
                background: var(--sidebar-bg);
                min-height: 100vh;
                width: var(--sidebar-width);
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1000;
                transition: all 0.3s ease;
                box-shadow: 2px 0 20px rgba(0, 0, 0, 0.1);
            }

            .sidebar-header {
                padding: 1.5rem 1rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            }

            .sidebar-nav {
                padding: 1rem 0;
            }

            .nav-item {
                margin: 0.25rem 0.75rem;
            }

            .nav-link {
                display: flex;
                align-items: center;
                padding: 0.75rem 1rem;
                color: var(--sidebar-text);
                text-decoration: none;
                border-radius: 0.5rem;
                transition: all 0.2s ease;
                font-weight: 500;
                position: relative;
                overflow: hidden;
            }

            .nav-link:hover {
                background: var(--sidebar-hover);
                color: var(--sidebar-text-active);
                transform: translateX(2px);
            }

            .nav-link.active {
                background: var(--sidebar-active);
                color: var(--sidebar-text-active);
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            }

            .nav-link i {
                width: 20px;
                text-align: center;
                margin-right: 0.75rem;
                font-size: 1.1rem;
            }

            .sidebar-divider {
                height: 1px;
                background: rgba(255, 255, 255, 0.1);
                margin: 1rem 0.75rem;
            }

            .sidebar-heading {
                color: var(--sidebar-text);
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                padding: 0 1.75rem;
                margin: 1rem 0 0.5rem 0;
            }

            .sidebar-footer {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                padding: 1rem;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                background: rgba(0, 0, 0, 0.1);
            }

            .user-profile {
                background: rgba(255, 255, 255, 0.05);
                border-radius: 0.75rem;
                padding: 1rem;
                backdrop-filter: blur(10px);
            }

            .user-avatar {
                width: 40px;
                height: 40px;
                background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: 600;
                margin-right: 0.75rem;
            }

            .main-content {
                margin-left: var(--sidebar-width);
                min-height: 100vh;
                padding: 2rem;
                transition: margin-left 0.3s ease;
            }

            .card {
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                border: none;
                border-radius: 0.75rem;
            }

            .btn-primary {
                background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
                border: none;
                border-radius: 0.5rem;
                font-weight: 500;
                transition: all 0.2s ease;
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            }

            .status-badge {
                padding: 0.35em 0.8em;
                font-size: 0.85em;
                border-radius: 50px;
                font-weight: 500;
            }

            .status-draft {
                background-color: #6b7280;
                color: white;
            }

            .status-submitted {
                background-color: #06b6d4;
                color: white;
            }

            .status-approved {
                background-color: #10b981;
                color: white;
            }

            .status-rejected {
                background-color: #ef4444;
                color: white;
            }

            .status-completed {
                background-color: #3b82f6;
                color: white;
            }

            /* Mobile responsiveness */
            @media (max-width: 768px) {
                .sidebar {
                    transform: translateX(-100%);
                }

                .sidebar.show {
                    transform: translateX(0);
                }

                .main-content {
                    margin-left: 0;
                    padding: 1rem;
                }
            }

            /* Custom scrollbar */
            .sidebar::-webkit-scrollbar {
                width: 4px;
            }

            .sidebar::-webkit-scrollbar-track {
                background: transparent;
            }

            .sidebar::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.2);
                border-radius: 2px;
            }

            .sidebar::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.3);
            }
        </style>

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

        @stack('styles')
    </head>

    <body>
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <div class="text-center">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <i class="bi bi-laptop text-white me-2" style="font-size: 1.5rem;"></i>
                        <div>
                            <h6 class="text-white mb-0 fw-bold">Sistem Pengadaan</h6>
                            <small class="text-white opacity-75">Perangkat Komputer</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <div class="sidebar-nav">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pengadaan.index') ? 'active' : '' }}"
                            href="{{ route('pengadaan.index') }}">
                            <i class="bi bi-grid-3x3-gap"></i>
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pengadaan.create') ? 'active' : '' }}"
                            href="{{ route('pengadaan.create') }}">
                            <i class="bi bi-plus-circle"></i>
                            Pengadaan Baru
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pengadaan.show') || request()->routeIs('pengadaan.edit') ? 'active' : '' }}"
                            href="{{ route('pengadaan.index') }}">
                            <i class="bi bi-list-ul"></i>
                            Daftar Pengadaan
                        </a>
                    </li>
                    <div class="sidebar-divider"></div>
                    <div class="sidebar-heading">Master Data</div>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}"
                            href="{{ route('kategori.index') }}">
                            <i class="bi bi-tags"></i>
                            Kategori Barang
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                            href="{{ route('users.index') }}">
                            <i class="bi bi-people"></i>
                            Manajemen User
                        </a>
                    </li>

                    <div class="sidebar-divider"></div>
                    <div class="sidebar-heading">Laporan</div>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pengadaan.statistik') ? 'active' : '' }}"
                            href="{{ route('pengadaan.statistik') }}">
                            <i class="bi bi-graph-up"></i>
                            Statistik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('pengadaan.laporan') ? 'active' : '' }}"
                            href="{{ route('pengadaan.laporan') }}">
                            <i class="bi bi-file-earmark-text"></i>
                            Laporan
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Sidebar Footer -->
            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="d-flex align-items-center mb-3">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-white fw-semibold small">
                                {{ Str::limit(Auth::user()->name ?? 'Guest', 15) }}</div>
                            <div class="text-white-50 small">{{ Auth::user()->jabatan ?? '' }}</div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-white-50 d-block">{{ Auth::user()->email ?? '' }}</small>
                        <small class="text-white-50 d-block">{{ Auth::user()->departemen ?? '' }}</small>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Mobile Header -->
            <div class="d-md-none mb-3">
                <button class="btn btn-outline-primary" type="button" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
            </div>

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0 fw-bold text-dark">@yield('title', 'Dashboard')</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 small">
                            <li class="breadcrumb-item"><a href="{{ route('pengadaan.index') }}"
                                    class="text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@yield('title', 'Dashboard')</li>
                        </ol>
                    </nav>
                </div>
                <div class="text-muted small">
                    <i class="bi bi-calendar-event me-1"></i>
                    {{ now()->format('d F Y') }}
                </div>
            </div>

            <!-- Content -->
            <div class="content-wrapper">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Mobile Sidebar Overlay -->
        <div class="sidebar-overlay d-md-none" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Custom JS -->
        <script>
            // Mobile sidebar toggle
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');

                if (sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                    overlay.style.display = 'none';
                } else {
                    sidebar.classList.add('show');
                    overlay.style.display = 'block';
                }
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('DOMContentLoaded', function() {
                const overlay = document.getElementById('sidebarOverlay');
                if (overlay) {
                    overlay.addEventListener('click', toggleSidebar);
                }

                // Add active states
                const navLinks = document.querySelectorAll('.nav-link');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        // Remove active from all links
                        navLinks.forEach(l => l.classList.remove('active'));
                        // Add active to clicked link
                        this.classList.add('active');
                    });
                });

                // Auto-hide mobile sidebar when window is resized
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) {
                        const sidebar = document.getElementById('sidebar');
                        const overlay = document.getElementById('sidebarOverlay');
                        sidebar.classList.remove('show');
                        overlay.style.display = 'none';
                    }
                });
            });

            // Smooth scrolling for better UX
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        </script>

        <!-- Additional styles for mobile overlay -->
        <style>
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
                display: none;
            }

            @media (max-width: 768px) {
                .sidebar.show {
                    transform: translateX(0);
                }
            }

            /* Improve loading animation */
            .nav-link {
                position: relative;
                overflow: hidden;
            }

            .nav-link::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
                transition: left 0.5s;
            }

            .nav-link:hover::before {
                left: 100%;
            }

            /* Loading states */
            .loading {
                opacity: 0.6;
                pointer-events: none;
            }

            /* Custom tooltip styles */
            .tooltip {
                font-size: 0.875rem;
            }
        </style>

        @stack('scripts')
    </body>

</html>
