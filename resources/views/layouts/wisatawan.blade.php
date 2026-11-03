<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Jelajah Nusa' }} — Jelajah Nusantara Bersama Kami</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-full flex flex-col text-slate-800 bg-slate-50">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-40 bg-white border-b border-slate-200/80 shadow-xs" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-18 sm:h-20">
                
                <!-- Logo Brand -->
                <a href="{{ route('paket.index') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-full bg-teal-700 flex items-center justify-center text-white shadow-sm group-hover:bg-teal-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                            <polygon points="12,7 15,15 12,13 9,15" fill="currentColor"/>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold text-slate-900 tracking-tight">Jelajah Nusa</span>
                    </div>
                </a>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center gap-2 text-sm font-semibold text-slate-600">
                    <a href="{{ route('paket.index') }}" 
                       class="px-4 py-2 rounded-xl transition {{ request()->routeIs('paket.index') && !request()->query('cari') && !request()->query('destinasi') ? 'text-teal-700 font-bold' : 'hover:text-slate-900' }}">
                        Beranda
                    </a>
                    <a href="{{ route('paket.index') }}" 
                       class="px-4 py-2 rounded-xl transition {{ request()->routeIs('paket.*') ? 'text-teal-700 font-bold' : 'hover:text-slate-900' }}">
                        Paket Wisata
                    </a>
                    <a href="{{ route('custom-trip.index') }}" 
                       class="px-4 py-2 rounded-xl transition {{ request()->routeIs('custom-trip.*') ? 'text-teal-700 font-bold' : 'hover:text-slate-900' }}">
                        Custom Trip
                    </a>
                    @auth
                        <a href="{{ route('pesanan-saya.index') }}" 
                           class="px-4 py-2 rounded-xl transition {{ request()->routeIs('pesanan-saya.*') ? 'text-teal-700 font-bold' : 'hover:text-slate-900' }}">
                            Pesanan Saya
                        </a>
                    @endauth
                    <a href="{{ route('tiket.verifikasi.kode') }}" 
                       class="px-4 py-2 rounded-xl transition {{ request()->routeIs('tiket.verifikasi*') ? 'text-teal-700 font-bold' : 'hover:text-slate-900' }}">
                        Cek Keaslian Tiket
                    </a>
                </nav>

                <!-- Auth / User Action -->
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition shadow-xs">
                                Panel Admin
                            </a>
                        @endif

                        <div class="relative" x-data="{ userMenu: false }">
                            <button @click="userMenu = !userMenu" @click.away="userMenu = false" class="flex items-center gap-2.5 p-1.5 rounded-xl hover:bg-slate-100 transition">
                                <span class="text-xs font-bold text-slate-800">{{ auth()->user()->nama_lengkap }}</span>
                                <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center text-xs font-bold uppercase border border-teal-200">
                                    {{ substr(auth()->user()->nama_lengkap ?? 'U', 0, 2) }}
                                </div>
                            </button>

                            <!-- User Dropdown -->
                            <div x-show="userMenu" x-transition class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 text-xs font-medium" style="display: none;">
                                <div class="px-4 py-2 border-b border-slate-100 text-slate-500">
                                    {{ auth()->user()->email }}
                                </div>
                                <a href="{{ route('pesanan-saya.index') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-slate-50">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    Pesanan Saya
                                </a>
                                <a href="{{ route('custom-trip.index') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-slate-50">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Pengajuan Custom Trip
                                </a>
                                <a href="{{ route('profil.show') }}" class="flex items-center gap-2 px-4 py-2 text-slate-700 hover:bg-slate-50">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Profil &amp; Password
                                </a>
                                <div class="border-t border-slate-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-rose-600 hover:bg-rose-50 text-left">
                                        <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-teal-700 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-teal-700 hover:bg-teal-800 text-white text-sm font-bold rounded-xl shadow-xs transition">
                            Daftar
                        </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Dropdown Menu -->
            <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-slate-200 py-4 space-y-2 text-sm font-semibold" style="display: none;">
                <a href="{{ route('paket.index') }}" class="block px-4 py-2 rounded-xl text-slate-700 hover:bg-slate-100">Paket Wisata</a>
                <a href="{{ route('custom-trip.index') }}" class="block px-4 py-2 rounded-xl text-slate-700 hover:bg-slate-100">Custom Trip</a>
                <a href="{{ route('tiket.verifikasi.kode') }}" class="block px-4 py-2 rounded-xl text-slate-700 hover:bg-slate-100">Cek Keaslian Tiket</a>
                @auth
                    <a href="{{ route('pesanan-saya.index') }}" class="block px-4 py-2 rounded-xl text-slate-700 hover:bg-slate-100">Pesanan Saya</a>
                    <a href="{{ route('profil.show') }}" class="block px-4 py-2 rounded-xl text-slate-700 hover:bg-slate-100">Profil Saya</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded-xl text-teal-700 font-bold bg-teal-50">Panel Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50 rounded-xl">Keluar (Logout)</button>
                    </form>
                @else
                    <div class="pt-3 border-t border-slate-100 flex flex-col gap-2 px-4">
                        <a href="{{ route('login') }}" class="w-full py-2.5 text-center text-slate-700 border border-slate-200 rounded-xl font-semibold">Masuk</a>
                        <a href="{{ route('register') }}" class="w-full py-2.5 text-center bg-teal-700 text-white rounded-xl font-bold shadow-xs">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Global Alerts / Toast -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
        @if(session('sukses'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 flex items-center justify-between p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm text-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="font-medium">{{ session('sukses') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-600 hover:text-emerald-900 text-lg font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" class="mb-4 flex items-center justify-between p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-sm text-sm">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-600 hover:text-rose-900 text-lg font-bold">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div x-data="{ show: true }" x-show="show" class="mb-4 p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl shadow-sm text-sm">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-bold">Perhatian:</span>
                    <button @click="show = false" class="text-amber-600 hover:text-amber-900 text-lg font-bold">&times;</button>
                </div>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Page Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer matching Figma Mockup -->
    <footer class="bg-[#101d2d] text-slate-400 text-sm mt-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center text-white font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><polygon points="12,7 15,15 12,13 9,15" fill="currentColor"/></svg>
                    </div>
                    <span class="text-lg font-bold text-white">Jelajah Nusa</span>
                </div>
                <p class="text-xs text-slate-400">
                    Sistem Informasi Travel dan Agen Wisata · Surakarta, Jawa Tengah
                </p>
                <div class="pt-4 border-t border-slate-800/80 text-xs text-slate-500">
                    &copy; {{ date('Y') }} Kelompok 3 — Universitas Duta Bangsa. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
