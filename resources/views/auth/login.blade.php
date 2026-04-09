<!DOCTYPE html>
<html lang="id" class="{{ session('dark_mode') ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Banjar Digital Media Web Management</title>
    <meta name="description" content="Masuk ke WebHouse Manager untuk mengelola infrastruktur website klien Anda." />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4 font-sans transition-colors duration-300
             bg-slate-900 dark:bg-slate-950">

    {{-- Dark mode toggle (floating) --}}
    <button
        id="dark-toggle"
        onclick="toggleDark()"
        class="fixed top-4 right-4 p-2.5 rounded-full text-slate-400 hover:text-white transition z-50
               hover:bg-white/10"
        aria-label="Toggle dark mode"
    >
        <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-9h-1M4.34 12h-1m15.07-6.07-.707.707M6.34 17.66l-.707.707m12.02 0-.707-.707M6.34 6.34l-.707-.707M12 7a5 5 0 100 10A5 5 0 0012 7z"/></svg>
        <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
    </button>

    {{-- Login Card --}}
    <div class="w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transition-colors duration-300
                bg-white dark:bg-slate-800 animate-fade-in-up">
        <div class="p-6 md:p-10">

            {{-- Logo --}}
            <div class="flex justify-center mb-6">
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-2xl flex items-center justify-center">
                    <i><img src="logo_BDM.svg" alt="BDM"></i>
                </div>
            </div>

            <h1 class="text-xl md:text-2xl font-bold text-center text-gray-800 dark:text-white">
                Banjar Digital Media <br> Web Management
            </h1>
            <p class="text-center mt-1 mb-7 text-xs md:text-sm text-gray-500 dark:text-slate-400">
                Silahkan login untuk mengelola website client
            </p>

            {{-- Error: username --}}
            @error('username')
            <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 text-xs md:text-sm p-3 rounded-xl flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 110 18A9 9 0 0112 3z"/></svg>
                <span>{{ $message }}</span>
            </div>
            @enderror

            {{-- Error: password --}}
            @error('password')
            <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 text-xs md:text-sm p-3 rounded-xl flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 110 18A9 9 0 0112 3z"/></svg>
                <span>{{ $message }}</span>
            </div>
            @enderror

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-xs md:text-sm font-medium mb-1.5 text-gray-700 dark:text-slate-300">
                        Username
                    </label>
                    <div class="relative">
                        <svg class="w-5 h-5 absolute left-3 top-3 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            autocomplete="username"
                            placeholder="Masukkan username"
                            class="w-full pl-10 pr-4 py-2.5 md:py-3 border rounded-xl text-sm md:text-base outline-none transition
                                   border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400
                                   @error('username') border-red-500 dark:border-red-500 @enderror"
                        />
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs md:text-sm font-medium mb-1.5 text-gray-700 dark:text-slate-300">
                        Password
                    </label>
                    <div class="relative">
                        <svg class="w-5 h-5 absolute left-3 top-3 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password"
                            class="w-full pl-10 pr-4 py-2.5 md:py-3 border rounded-xl text-sm md:text-base outline-none transition
                                   border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   dark:bg-slate-700 dark:border-slate-600 dark:text-white dark:placeholder-slate-400
                                   @error('password') border-red-500 dark:border-red-500 @enderror"
                        />
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2.5 md:py-3 rounded-xl text-sm md:text-base font-bold
                           hover:bg-blue-700 transition-all duration-200 transform active:scale-[0.98]
                           shadow-lg shadow-blue-500/30 mt-2"
                >
                    MASUK
                </button>
            </form>
        </div>
    </div>

    <script>
        // Dark mode toggle (standalone, sebelum app.js loaded via Vite)
        function toggleDark() {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('darkMode', isDark ? '1' : '0');
        }
        // Apply saved preference
        if (localStorage.getItem('darkMode') === '1') {
            document.documentElement.classList.add('dark');
        }
    </script>
</body>
</html>
