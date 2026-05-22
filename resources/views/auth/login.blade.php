<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Banjar Digital Media Web Management</title>
    <meta name="description" content="Masuk ke WebHouse Manager untuk mengelola infrastruktur website klien Anda." />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
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
<body class="min-h-screen flex items-center justify-center p-4 font-sans transition-colors duration-300
             bg-gray-100 dark:bg-slate-950">

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
    <div class="w-full max-w-md rounded-md shadow-lg overflow-hidden transition-colors duration-300
                bg-white dark:bg-gray-800 animate-fade-in-up">
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
            <p class="text-center mt-1 mb-7 text-xs md:text-sm text-gray-400 dark:text-gray-400">
                Silahkan login untuk mengelola website client
            </p>


            @if ($errors->any())
            <div class="mb-4 bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 text-xs md:text-sm p-3 rounded-xl flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3a9 9 0 110 18A9 9 0 0112 3z"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Username --}}
                <div>
                    <label for="username" class="block text-xs md:text-sm font-medium mb-1.5 text-gray-700 dark:text-slate-300">
                        Username
                    </label>
                    <div class="relative">
                        <span class="absolute top-1/2 left-3 -translate-y-1/2 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            autocomplete="username"
                            placeholder="Masukkan username"
                            class="w-full pl-10 pr-4 py-2.5 border rounded text-sm outline-none transition
                                   border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400
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
                        <span class="absolute top-1/2 left-3 -translate-y-1/2 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password"
                            class="w-full pl-10 pr-10 py-2.5 border rounded text-sm outline-none transition
                                   border-gray-300 bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400
                                   @error('password') border-red-500 dark:border-red-500 @enderror"
                        />
                        <button type="button" onclick="togglePasswordVisibility('password', this)" tabindex="-1"
                            class="absolute right-2 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition rounded"
                            aria-label="Toggle password visibility">
                            <span class="eye-icon-show">
                                @include('components.icon', ['name' => 'eye', 'class' => 'w-4 h-4'])
                            </span>
                            <span class="eye-icon-hide" style="display:none;">
                                @include('components.icon', ['name' => 'eye-off', 'class' => 'w-4 h-4'])
                            </span>
                        </button>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2.5 rounded text-sm font-semibold
                           hover:bg-blue-700 transition-colors duration-200 mt-2 cursor-pointer"
                >
                    MASUK
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleDark() {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('darkMode', isDark ? '1' : '0');
            document.documentElement.style.colorScheme = isDark ? 'dark' : 'light';
        }

        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            const showIcon = btn.querySelector('.eye-icon-show');
            const hideIcon = btn.querySelector('.eye-icon-hide');
            if (showIcon) showIcon.style.display = isPassword ? 'none' : '';
            if (hideIcon) hideIcon.style.display = isPassword ? '' : 'none';
        }
    </script>
</body>
</html>
