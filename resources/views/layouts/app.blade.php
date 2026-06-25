<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'FinanceOS') }} — Sistem Keuangan Perusahaan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Terapkan status sidebar (collapsed/expanded) sebelum render agar tidak "flash"
        (function () {
            if (localStorage.getItem('sidebar-collapsed') === '1') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        })();
    </script>
    <style>
        :root {
            --brand: #1B4FD8;
            --brand-dark: #1440B8;
            --brand-light: #EEF2FF;
            --surface: #F8FAFC;
            --sidebar-w: 248px;
            --sidebar-w-collapsed: 84px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--surface); }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ====== Sidebar ====== */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: #FFFFFF;
            border-right: 1px solid #EEF1F6;
            position: fixed;
            top: 0; left: 0;
            z-index: 40;
            display: flex;
            flex-direction: column;
            transition: width 0.2s ease, transform 0.25s ease;
        }
        #main-content { margin-left: var(--sidebar-w); transition: margin-left 0.2s ease; }

        /* Collapsed (desktop) */
        html.sidebar-collapsed #sidebar { width: var(--sidebar-w-collapsed); }
        html.sidebar-collapsed #main-content { margin-left: var(--sidebar-w-collapsed); }
        html.sidebar-collapsed .sidebar-label,
        html.sidebar-collapsed .sidebar-section-label,
        html.sidebar-collapsed .sidebar-user-text,
        html.sidebar-collapsed .sidebar-logo-text { display: none; }
        html.sidebar-collapsed .nav-item { justify-content: center; padding-left: 0; padding-right: 0; }
        html.sidebar-collapsed .sidebar-logo-row { justify-content: center; padding-left: 0; padding-right: 0; }
        html.sidebar-collapsed .sidebar-user-row { justify-content: center; }
        html.sidebar-collapsed .sidebar-collapse-btn svg { transform: rotate(180deg); }

        /* Mobile behaviour */
        #sidebar.hidden-mobile { transform: translateX(-100%); }
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); width: var(--sidebar-w) !important; }
            #sidebar.show-mobile { transform: translateX(0); }
            #main-content { margin-left: 0 !important; }
            html.sidebar-collapsed .sidebar-label,
            html.sidebar-collapsed .sidebar-section-label,
            html.sidebar-collapsed .sidebar-user-text,
            html.sidebar-collapsed .sidebar-logo-text { display: revert; }
            html.sidebar-collapsed .nav-item { justify-content: flex-start; padding-left: 12px; padding-right: 12px; }
            html.sidebar-collapsed .sidebar-logo-row { justify-content: flex-start; padding-left: 20px; padding-right: 20px; }
            html.sidebar-collapsed .sidebar-user-row { justify-content: flex-start; }
            #sidebar-overlay { display: block; }
        }

        .nav-item { transition: all 0.15s; color: #64748B; }
        .nav-item:hover { background: #F1F5F9; color: #1E293B; }
        .nav-item.active { background: var(--brand); color: #fff; box-shadow: 0 4px 10px rgba(27,79,216,0.25); }
        .nav-item.active:hover { background: var(--brand); }

        /* ====== Cards ====== */
        .stat-card { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04); }
        .stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); transform: translateY(-1px); transition: all 0.2s; }

        /* ====== Table ====== */
        .data-table th { background: #F8FAFC; color: #64748B; font-size: 11px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; padding: 12px 16px; }
        .data-table td { padding: 13px 16px; border-top: 1px solid #F1F5F9; font-size: 13.5px; }
        .data-table tr:hover td { background: #FAFBFC; }

        /* ====== Badge ====== */
        .badge-masuk { background: #DCFCE7; color: #16A34A; }
        .badge-keluar { background: #FEE2E2; color: #DC2626; }

        /* ====== Form ====== */
        .field-input { border: 1.5px solid #E2E8F0; border-radius: 10px; padding: 10px 14px; font-size: 14px; width: 100%; transition: border-color 0.15s; }
        .field-input:focus { border-color: var(--brand); outline: none; box-shadow: 0 0 0 3px rgba(27,79,216,0.1); }
        .btn-primary { background: var(--brand); color: white; border-radius: 10px; padding: 10px 20px; font-size: 14px; font-weight: 600; transition: all 0.15s; }
        .btn-primary:hover { background: var(--brand-dark); box-shadow: 0 4px 12px rgba(27,79,216,0.3); }

        /* ====== Scrollbar ====== */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #F1F5F9; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 3px; }

        /* ====== Topbar ====== */
        #topbar { background: white; border-bottom: 1px solid #E2E8F0; height: 64px; }
        .search-shell { background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 10px; }
        .search-shell:focus-within { border-color: var(--brand); background: #fff; box-shadow: 0 0 0 3px rgba(27,79,216,0.08); }
        .kbd-hint { font-size: 11px; color: #94A3B8; background: #fff; border: 1px solid #E2E8F0; border-radius: 6px; padding: 1px 6px; }

        /* ====== KPI trend ====== */
        .trend-up { color: #16A34A; }
        .trend-down { color: #DC2626; }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full">

{{-- Sidebar Overlay (mobile) --}}
<div id="sidebar-overlay" class="fixed inset-0 z-30 hidden" style="background:rgba(0,0,0,0.4)" onclick="closeSidebar()"></div>

{{-- SIDEBAR --}}
<aside id="sidebar">
    {{-- Logo + collapse toggle --}}
    <div class="sidebar-logo-row flex items-center justify-between gap-2 px-5 py-5 border-b border-slate-100 flex-shrink-0">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="sidebar-logo-text font-display text-slate-800 font-bold text-sm leading-tight whitespace-nowrap">FINANCE</p>
        </div>
        <button onclick="toggleSidebarCollapse()" title="Tutup / buka sidebar"
                class="sidebar-collapse-btn hidden md:flex p-1.5 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 flex-shrink-0">
            <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav class="px-3 py-4 space-y-0.5 flex-1 overflow-y-auto">
        <p class="sidebar-section-label px-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest mb-2">Menu Utama</p>

        <a href="{{ route('dashboard') }}" title="Dashboard"
           class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="sidebar-label whitespace-nowrap">Dashboard</span>
        </a>

        <a href="{{ route('transactions.index') }}" title="Transaksi"
           class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="sidebar-label whitespace-nowrap">Transaksi</span>
        </a>

        @if(auth()->user()->isAdmin())
        <div class="pt-3 pb-1">
            <p class="sidebar-section-label px-3 text-[10px] font-semibold text-slate-400 uppercase tracking-widest mb-2">Manajemen</p>
        </div>

        <a href="{{ route('reports.index') }}" title="Laporan & Analitik"
           class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span class="sidebar-label whitespace-nowrap">Laporan</span>
        </a>

        <a href="{{ route('branches.index') }}" title="Kelola Cabang"
           class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('branches.*') ? 'active' : '' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span class="sidebar-label whitespace-nowrap">Cabang</span>
        </a>

        <a href="{{ route('users.index') }}" title="Pengguna"
           class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-3.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/>
            </svg>
            <span class="sidebar-label whitespace-nowrap">Pengguna</span>
        </a>

        <a href="{{ route('categories.index') }}" title="Kategori"
           class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <svg class="w-[18px] h-[18px] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <span class="sidebar-label whitespace-nowrap">Kategori</span>
        </a>
        @endif
    </nav>

    {{-- User info at bottom --}}
    <div class="border-t border-slate-100 p-4 flex-shrink-0">
        <div class="sidebar-user-row flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="sidebar-user-text flex-1 min-w-0">
                <p class="text-sm text-slate-700 font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-400 truncate">{{ ucfirst(auth()->user()->role) }}@if(auth()->user()->branch) · {{ auth()->user()->branch->name }}@endif</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="sidebar-user-text">
                @csrf
                <button title="Logout" class="text-slate-400 hover:text-red-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- MAIN CONTENT --}}
<div id="main-content" class="min-h-screen flex flex-col">

    {{-- Topbar --}}
    <header id="topbar" class="sticky top-0 z-20 flex items-center justify-between gap-3 px-4 sm:px-6">
        <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
            {{-- Sidebar toggle (mobile: open drawer, desktop: collapse) --}}
            <button onclick="handleSidebarToggleClick()" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            {{-- Search --}}
            <div class="search-shell hidden sm:flex items-center gap-2 px-3 py-2 flex-1 max-w-sm">
                <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" placeholder="Cari sesuatu..." class="bg-transparent border-0 outline-none text-sm flex-1 text-slate-600 placeholder:text-slate-400">
                <span class="kbd-hint hidden md:inline">Ctrl + K</span>
            </div>
        </div>
        <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
            {{-- Quick action --}}
            <a href="{{ route('transactions.create') }}"
               class="btn-primary hidden sm:flex items-center gap-2 text-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Input Transaksi
            </a>
            {{-- Notification bell --}}
            <div class="relative">
                <button class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </button>
                <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white">3</span>
            </div>
            {{-- User dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 pl-1">
                    <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold text-slate-700 leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400 leading-tight">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                    <svg class="w-3.5 h-3.5 text-slate-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1.5 z-50">
                    <div class="px-3.5 py-2 border-b border-slate-100">
                        <p class="text-sm font-semibold text-slate-700 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left px-3.5 py-2 text-sm text-red-500 hover:bg-red-50 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="mx-4 sm:mx-6 mt-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm" id="flash-msg">
        <svg class="w-4 h-4 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        {{ session('success') }}
        <button onclick="this.parentElement.remove()" class="ml-auto text-green-600 hover:text-green-800">✕</button>
    </div>
    @endif
    @if(session('error'))
    <div class="mx-4 sm:mx-6 mt-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm" id="flash-msg-error">
        {{ session('error') }}
        <button onclick="this.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800">✕</button>
    </div>
    @endif
    @if($errors->any())
    <div class="mx-4 sm:mx-6 mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
        @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    {{-- Page header --}}
    <div class="px-4 sm:px-6 pt-5">
        <h1 class="font-display text-2xl font-bold text-slate-800">@yield('page-title', 'Dashboard')</h1>
        <p class="text-sm text-slate-400 mt-0.5">@yield('page-subtitle', now()->isoFormat('dddd, D MMMM Y'))</p>
    </div>

    {{-- Page content --}}
    <main class="flex-1 px-4 sm:px-6 py-5">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="px-6 py-3 border-t border-slate-100 text-xs text-slate-400 flex justify-between">
        <span>FinanceOS © {{ date('Y') }}</span>
        <span>v2.0.0</span>
    </footer>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js" defer></script>
<script>
function openSidebar() {
    document.getElementById('sidebar').classList.add('show-mobile');
    document.getElementById('sidebar-overlay').classList.remove('hidden');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('show-mobile');
    document.getElementById('sidebar-overlay').classList.add('hidden');
}
function toggleSidebarCollapse() {
    const isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
    localStorage.setItem('sidebar-collapsed', isCollapsed ? '1' : '0');
}
function handleSidebarToggleClick() {
    // Di mobile: buka/tutup drawer. Di desktop: collapse/expand sidebar.
    if (window.innerWidth < 768) {
        const sidebar = document.getElementById('sidebar');
        if (sidebar.classList.contains('show-mobile')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    } else {
        toggleSidebarCollapse();
    }
}
// Auto hide flash
setTimeout(() => { const f = document.getElementById('flash-msg'); if(f) f.style.opacity='0'; }, 4000);
</script>
@stack('scripts')
</body>
</html>
