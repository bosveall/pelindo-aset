<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Pemeliharaan Aset') - {{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/icons/pelindo-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            color-scheme: light;
        }
    </style>
</head>

<body class="min-h-screen text-slate-800">
    @auth
        <div class="flex min-h-screen">
            {{-- Sidebar --}}
            <aside id="app-sidebar"
                class="app-sidebar fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col overflow-hidden border-r border-slate-800 text-white transition-transform duration-200 lg:translate-x-0">
                <div class="relative px-6 py-6">
                    <div
                        class="flex flex-col items-center rounded-xl border border-white/10 bg-white/5 px-3 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <span class="h-11 w-11 shrink-0">
                                <img src="{{ asset('assets/icons/pelindo-logo.png') }}" alt="Logo Pelindo"
                                    class="h-full w-full object-contain">
                            </span>
                            <div class="flex flex-col items-start gap-1">
                                <h1 class="text-xl font-bold uppercase leading-none tracking-[0.12em] text-[#58b5e0]">Pelindo</h1>
                                <p class="text-[10px] font-medium uppercase leading-none tracking-[0.2em] text-slate-300">Regional 1 Dumai</p>
                            </div>
                        </div>
                    </div>
                </div>

                <nav aria-label="Navigasi utama" class="relative flex-1 space-y-1 overflow-y-auto px-3">
                    <p class="px-3 pb-1 pt-2 text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-500">Menu</p>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition-colors {{ request()->routeIs('dashboard') ? 'bg-teal-700 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>

                    <a href="{{ route('asets.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition-colors {{ request()->routeIs('asets.index', 'asets.create', 'asets.show', 'asets.edit') ? 'bg-teal-700 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="text-sm font-medium">Data Aset</span>
                    </a>

                    <a href="{{ route('asets.scan') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition-colors {{ request()->routeIs('asets.scan') ? 'bg-teal-700 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 7V5a1 1 0 011-1h2m7 0h2a1 1 0 011 1v2m0 10v2a1 1 0 01-1 1h-2m-7 0H5a1 1 0 01-1-1v-2M7 8v8m3-8v8m3-8v8m3-8v8" />
                        </svg>
                        <span class="text-sm font-medium">Scan Barcode</span>
                    </a>

                    <a href="{{ route('ruangan.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition-colors {{ request()->routeIs('ruangan.index') ? 'bg-teal-700 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h7v7H3V3zm11 0h7v7h-7V3zM3 14h7v7H3v-7zm11 0h7v7h-7v-7z" />
                        </svg>
                        <span class="text-sm font-medium">QR Ruangan</span>
                    </a>

                    <a href="{{ route('pemeliharaans.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition-colors {{ request()->routeIs('pemeliharaans.*') ? 'bg-teal-700 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span class="text-sm font-medium">Pemeliharaan</span>
                    </a>

                    <a href="{{ route('laporan.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition-colors {{ request()->routeIs('laporan.*') ? 'bg-teal-700 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="text-sm font-medium">Laporan</span>
                    </a>

                    @if(Auth::user()->isAdmin())
                        <p class="px-3 pb-1 pt-5 text-[10px] font-semibold uppercase tracking-[0.3em] text-slate-500">Manajemen
                        </p>
                        <a href="{{ route('petugas.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition-colors {{ request()->routeIs('petugas.*') ? 'bg-teal-700 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="text-sm font-medium">Admin / Petugas</span>
                        </a>
                        <a href="{{ route('hakakses.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 transition-colors {{ request()->routeIs('hakakses.*') ? 'bg-teal-700 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span class="text-sm font-medium">Hak Akses</span>
                        </a>
                    @endif
                </nav>

                <div class="relative border-t border-white/10 p-4">
                    <div
                        class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 px-3 py-3">
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-700 text-sm font-bold uppercase">
                            {{ Str::substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-xs capitalize text-slate-400">{{ Auth::user()->role }}</p>
                        </div>
                        <a href="{{ route('logout') }}" title="Logout"
                            class="rounded-xl p-2 text-slate-400 transition-colors hover:bg-slate-800 hover:text-red-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </a>
                    </div>
                    <p class="mt-3 px-1 text-[10px] tracking-wide text-slate-500">Regional 1 Dumai · Pengelolaan aset</p>
                </div>
            </aside>
            <button id="sidebar-overlay" type="button" aria-label="Tutup menu" aria-hidden="true"
                class="fixed inset-0 z-30 hidden bg-slate-950/50 backdrop-blur-sm lg:hidden"></button>

            <div class="ml-0 flex-1 lg:ml-64">
                <header class="app-topbar sticky top-0 z-20 px-4 py-3 sm:px-6 sm:py-4 lg:px-8">
                    <div
                        class="glass-panel flex items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 sm:px-5 sm:py-4">
                        <button id="sidebar-toggle" type="button" aria-label="Buka menu" aria-controls="app-sidebar"
                            aria-expanded="false"
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:border-teal-300 hover:text-teal-700 lg:hidden">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-teal-800">Pelindo · Regional 1 Dumai</p>
                            <h2 class="truncate text-base font-semibold text-slate-800 sm:text-lg">
                                @yield('title', 'Dashboard')
                            </h2>
                        </div>
                        <div class="flex items-center gap-3">
                            <span
                                class="hidden items-center gap-2 rounded-full border border-teal-100 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 sm:inline-flex">
                                <span class="h-1.5 w-1.5 rounded-full bg-teal-600"></span>
                                @php echo ucfirst(Auth::user()->role); @endphp
                            </span>
                        </div>
                    </div>
                </header>

                <main class="p-4 sm:p-6 lg:p-8">
                    <div class="app-content-shell">
                        @if(session('success'))
                            <div
                                class="mb-6 flex items-center gap-2 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div
                                class="mb-6 flex items-center gap-2 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ session('error') }}
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                                <ul class="list-disc space-y-0.5 pl-5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    @else
        @yield('auth-content')
    @endauth
    @auth
        <script>
            const sidebar = document.getElementById('app-sidebar');
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebarOverlay = document.getElementById('sidebar-overlay');

            const setSidebarState = (isOpen) => {
                sidebar.classList.toggle('-translate-x-full', !isOpen);
                sidebarOverlay.classList.toggle('hidden', !isOpen);
                sidebarOverlay.setAttribute('aria-hidden', String(!isOpen));
                sidebarToggle.setAttribute('aria-expanded', String(isOpen));
            };

            sidebarToggle.addEventListener('click', () => setSidebarState(true));
            sidebarOverlay.addEventListener('click', () => setSidebarState(false));
            sidebar.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', () => setSidebarState(false));
            });
        </script>
    @endauth
    @stack('scripts')
</body>

</html>