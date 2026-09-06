<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inventory App') - Web App Inventory & Penjualan</title>

    <!-- Tailwind & App CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Inter Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-background text-textPrimary antialiased flex flex-col min-h-screen overflow-x-hidden" x-data="{ sidebarOpen: false }">

    <div class="flex flex-1 min-h-screen overflow-hidden">
        <!-- Sidebar Desktop & Mobile -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-sidebar text-white transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static flex flex-col justify-between"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div>
                <div class="h-16 flex items-center justify-between px-6 bg-slate-900/50 border-b border-slate-700/50">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center font-bold text-white shadow-xs">
                            INV
                        </div>
                        <span class="font-bold text-lg tracking-wide text-white">InvManager</span>
                    </div>
                    <button @click="sidebarOpen = false" aria-label="Tutup menu navigasi"
                        class="md:hidden min-h-11 min-w-11 inline-flex items-center justify-center text-slate-400 hover:text-white focus:outline-none focus:ring-2 focus:ring-white/50 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="mt-6 px-4 space-y-1.5">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('dashboard') ? 'bg-primary text-white shadow-xs' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="font-medium text-sm">Dashboard</span>
                    </a>

                    <a href="{{ route('inventory.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('inventory.*') ? 'bg-primary text-white shadow-xs' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="font-medium text-sm">Inventaris Produk</span>
                    </a>

                    <a href="{{ route('sales.index') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg font-medium transition {{ request()->routeIs('sales.*') ? 'bg-primary text-white shadow-xs' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium text-sm">Data Penjualan</span>
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-700/50 bg-slate-900/30 text-xs text-slate-400">
                <p class="font-semibold text-slate-300">Inventory App Laravel</p>
                <p class="mt-0.5">Versi 1.0.0</p>
            </div>
        </aside>

        <!-- Overlay backdrop for mobile sidebar -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs md:hidden"
            style="display: none;"></div>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 bg-background overflow-y-auto">
            <!-- Top Navbar -->
            <header
                class="bg-surface border-b border-border h-16 flex items-center justify-between px-4 md:px-8 sticky top-0 z-30 shadow-xs">
                <div class="flex items-center space-x-3">
                    <button @click="sidebarOpen = true" aria-label="Buka menu navigasi"
                        class="md:hidden min-h-11 min-w-11 inline-flex items-center justify-center text-textSecondary hover:text-textPrimary p-2 rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-primary/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="min-w-0 truncate text-lg md:text-xl font-bold text-textPrimary">@yield('header_title', 'Inventory System')</h1>
                </div>

                <div class="flex items-center space-x-3">
                    <span class="text-xs md:text-sm text-textSecondary hidden sm:inline">Admin User</span>
                    <div
                        class="w-8 h-8 rounded-full bg-slate-200 border border-border flex items-center justify-center font-bold text-slate-700 text-xs">
                        AD
                    </div>
                </div>
            </header>

            <!-- Main Content Container -->
            <main class="flex-1 p-4 md:p-8">
                <!-- Global Flash Notifications -->
                @if (session('success') || session('error'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
                        class="mb-6 rounded-lg p-4 shadow-xs border flex items-center justify-between {{ session('success') ? 'bg-emerald-50 border-success/30 text-success' : 'bg-red-50 border-danger/30 text-danger' }}">
                        <div class="flex items-center space-x-3">
                            <span
                                class="text-sm font-medium">{{ session('success') ?? session('error') }}</span>
                        </div>
                        <button @click="show = false" aria-label="Tutup notifikasi" class="min-h-11 min-w-11 inline-flex items-center justify-center text-slate-400 hover:text-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/30">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
