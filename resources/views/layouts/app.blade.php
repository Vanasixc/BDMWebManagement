<!DOCTYPE html>
<html lang="id" class="">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Dashboard') — Banjar Digital Media</title>
    <meta name="description" content="@yield('meta_description', 'Banjar Digital Media — Web Client Management')" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    {{-- Favicon: ganti file public/favicon.png untuk mengubah icon loading & browser tab --}}
    <link rel="icon" type="png" href="favicon.ico" />
    <link rel="shortcut icon" type="png" href="favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    {{-- Dark mode: jalankan sebelum CSS untuk hindari flash --}}
    <script>
        (function() {
            var dark = localStorage.getItem('darkMode') === '1';
            document.documentElement.classList.toggle('dark', dark);
            document.documentElement.style.colorScheme = dark ? 'dark' : 'light';
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-screen overflow-hidden flex font-sans transition-colors duration-300
             bg-gray-100 text-slate-900
             dark:bg-gray-900 dark:text-slate-100">

    <div id="page-loader"
        style="position:fixed;inset:0;z-index:9999;display:flex;flex-direction:column;
                align-items:center;justify-content:center;gap:20px;
                background:var(--loader-bg, #f8fafc);
                transition:opacity 0.35s ease, visibility 0.35s ease;">

        <div style="position:relative;width:72px;height:72px;">
            <svg style="position:absolute;inset:0;width:72px;height:72px;animation:loader-spin 1s linear infinite;"
                viewBox="0 0 72 72" fill="none">
                <circle cx="36" cy="36" r="32" stroke="#e2e8f0" stroke-width="5" />
                <path d="M36 4 a32 32 0 0 1 32 32" stroke="#3b82f6" stroke-width="5"
                    stroke-linecap="round" />
            </svg>
            <img src="logo_BDM.svg"
                alt="Loading"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                style="position:absolute;inset:10px;width:52px;height:52px;
                        border-radius:10px;object-fit:contain;" />
            <div style="display:none;position:absolute;inset:10px;width:52px;height:52px;
                        border-radius:10px;background:#3b82f6;
                        align-items:center;justify-content:center;">
                <svg width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="white">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
            </div>
        </div>

        <p style="font-family:Inter,sans-serif;font-size:13px;font-weight:600;
                  color:#94a3b8;letter-spacing:0.05em;">Memuat halaman...</p>
    </div>

    <style>
        @keyframes loader-spin {
            to {
                transform: rotate(360deg);
            }
        }

        .dark #page-loader {
            --loader-bg: #0f172a;
        }
    </style>


    <div
        id="sidebar-overlay"
        onclick="closeSidebar()"
        style="display:none; position:fixed; inset:0; z-index:35; background:rgba(0,0,0,0.6); pointer-events:none;"></div>


    <aside
        id="sidebar"
        style="width: 256px;"
        class="fixed md:relative z-40 h-full flex flex-col shrink-0
               bg-white border-r border-gray-200 text-gray-700
               dark:bg-gray-900 dark:border-gray-700 dark:text-gray-300
               transition-colors duration-300">
        <a href="{{ route('dashboard') }}" class="p-5 flex justify-center items-center gap-3 min-w-[200px] hover:opacity-80 transition-opacity" title="Ke Dashboard">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white shrink-0">
                <i><img src="logo_BDM.svg" alt="BDM"></i>
            </div>
        </a>

        <nav class="flex-1 px-3 space-y-1 mt-2 overflow-y-auto min-w-[200px]">
            @php
            $menu = [
            ['id' => 'dashboard', 'label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'layout-dashboard'],
            ['id' => 'master', 'label' => 'Master', 'route' => 'master', 'icon' => 'database'],
            ['id' => 'domain', 'label' => 'Domain', 'route' => 'domain', 'icon' => 'globe'],
            ['id' => 'hosting', 'label' => 'Hosting', 'route' => 'hosting', 'icon' => 'server'],
            ['id' => 'akses', 'label' => 'Akses', 'route' => 'akses', 'icon' => 'key'],
            ['id' => 'finansial', 'label' => 'Finansial', 'route' => 'finansial', 'icon' => 'dollar-sign'],
            ['id' => 'reminder', 'label' => 'Reminder', 'route' => 'reminder', 'icon' => 'bell'],
            ];
            if (auth()->user()->isSuperAdmin()) {
            $menu[] = ['id' => 'akun', 'label' => 'Manajemen Akun', 'route' => 'akun', 'icon' => 'users'];
            }
            $currentRoute = request()->route()->getName();
            @endphp

            @foreach ($menu as $item)
            <a
                href="{{ route($item['route']) }}"
                class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg transition group text-sm font-medium
                       {{ $currentRoute === $item['id']
                          ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/30'
                          : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white' }}">
                @include('components.icon', ['name' => $item['icon'], 'class' => 'w-5 h-5 shrink-0'])
                <span class="truncate">{{ $item['label'] }}</span>
            </a>
            @endforeach
        </nav>

        <div class="p-3 border-t border-gray-200 dark:border-gray-700 min-w-[200px]">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-red-500 hover:bg-red-50 hover:text-red-600 dark:text-red-400 dark:hover:bg-red-600/20 dark:hover:text-red-300 transition text-sm font-medium cursor-pointer">
                    <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="truncate">Log Out</span>
                </button>
            </form>
        </div>

        <div
            id="sidebar-resizer"
            class="hidden md:block absolute right-0 top-0 bottom-0 transition"></div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">

        <header class="h-14 md:h-16 shrink-0 border-b flex items-center justify-between px-4 md:px-6 z-10 transition-colors
                       bg-white border-gray-200
                       dark:bg-gray-800 dark:border-gray-700">

            <div class="flex items-center gap-4">
                <button
                    onclick="toggleSidebar()"
                    id="hamburger-btn"
                    class="p-2 rounded-lg transition hover:bg-slate-100 dark:hover:bg-slate-700"
                    aria-label="Toggle Sidebar">
                    <svg id="hamburger-icon-open" class="w-5 h-5 md:w-6 md:h-6 block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg id="hamburger-icon-close" class="w-5 h-5 md:w-6 md:h-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="hidden sm:block">
                    <h1 class="text-base md:text-lg font-bold">@yield('page_title', 'Dashboard')</h1>
                    <p class="text-[10px] md:text-xs text-slate-500 dark:text-slate-400">@yield('page_subtitle', 'Kelola infrastruktur website Anda.')</p>
                </div>
            </div>

            <div class="flex items-center gap-3 md:gap-5">
                <button
                    onclick="toggleDark()"
                    class="p-1.5 rounded transition
                           bg-gray-100 text-gray-600 hover:bg-gray-200
                           dark:bg-gray-700 dark:text-yellow-400 dark:hover:bg-gray-600"
                    aria-label="Toggle dark mode">
                    <svg class="w-4 h-4 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg class="w-4 h-4 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-9h-1M4.34 12h-1m15.07-6.07-.707.707M6.34 17.66l-.707.707m12.02 0-.707-.707M6.34 6.34l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z" />
                    </svg>
                </button>

                <div class="flex items-center gap-3 border-l pl-3 md:pl-5 border-gray-200 dark:border-gray-700">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs md:text-sm font-semibold leading-none mb-0.5">{{ auth()->user()->getLabel() }}</p>
                        <p class="text-[9px] md:text-[10px] font-medium uppercase tracking-wider leading-none text-gray-500 dark:text-gray-400">
                            {{ auth()->user()->role }}
                        </p>
                    </div>
                    <img
                        src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}"
                        alt="{{ auth()->user()->name }}"
                        class="w-8 h-8 md:w-9 md:h-9 rounded-full border-2 border-purple-500 object-cover" />
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto w-full">
            <div class="p-4 md:p-8 max-w-[1600px] mx-auto w-full animate-fade-in-up">

                <div class="mb-5 md:mb-7 sm:hidden">
                    <h1 class="text-xl font-bold">@yield('page_title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">@yield('page_subtitle', 'Kelola infrastruktur website Anda.')</p>
                </div>

                @if (session('success'))
                <script>
                    window.__flashSuccess = @json(session('success'));
                </script>
                @endif
                @if (session('error'))
                <script>
                    window.__flashError = @json(session('error'));
                </script>
                @endif

                {{-- Main content slot --}}
                @yield('content')

                {{-- Footer --}}
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-center gap-2">
                    <span class="text-xs text-gray-400 dark:text-gray-500">Built with</span>
                    <a href="https://zuramai.github.io/mazer/"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-400 transition-colors duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm0 8a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zm12 0a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                        Mazer
                    </a>
                </div>
            </div>
        </div>
    </main>

    @include('components.modal-form')

    <script>
        (function() {
            function showFlash() {
                const isDark = document.documentElement.classList.contains('dark');
                const base = {
                    timer: 2500,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: false,
                    background: isDark ? '#1e293b' : '#ffffff',
                    color: isDark ? '#f1f5f9' : '#0f172a',
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border ' + (isDark ? 'border-slate-700' : 'border-gray-100'),
                    },
                };
                if (window.__flashSuccess) {
                    Swal.fire(Object.assign({}, base, {
                        icon: 'success',
                        title: 'Berhasil!',
                        text: window.__flashSuccess
                    }));
                } else if (window.__flashError) {
                    Swal.fire(Object.assign({}, base, {
                        icon: 'error',
                        title: 'Terjadi Kesalahan!',
                        text: window.__flashError,
                        timer: 4000
                    }));
                }
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', showFlash);
            } else {
                showFlash();
            }
        })();
    </script>

    <script>
        window.__chartCbs = [];
        window.onChartReady = function(fn) {
            if (window.Chart) { fn(); } else { window.__chartCbs.push(fn); }
        };
    </script>

    {{-- Page-specific scripts --}}
    @stack('scripts')

    {{-- Page-specific modals (di-inject ke body level, bebas dari transform container) --}}
    @stack('modals')
    {{-- Script dismiss loading screen --}}
    <script>
        (function() {
            var loader = document.getElementById('page-loader');

            function hideLoader() {
                if (!loader) return;
                loader.style.opacity = '0';
                loader.style.visibility = 'hidden';
                setTimeout(function() {
                    loader.remove();
                }, 380);
            }
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', hideLoader);
            } else {
                hideLoader();
            }
            setTimeout(hideLoader, 5000);
        })();
    </script>
</body>

</html>