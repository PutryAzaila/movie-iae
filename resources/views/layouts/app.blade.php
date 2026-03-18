<!DOCTYPE html>
<html lang="id" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Moviesiae' }}</title>
    <script>
        // Prevent theme flash — run before render
        (function(){
            var t = localStorage.getItem('theme') || 'dark';
            document.documentElement.classList.remove('dark','light');
            document.documentElement.classList.add(t);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        .font-display { font-family: 'Syne', sans-serif; }
        *, *::before, *::after { box-sizing: border-box; }

        /* Smooth theme transition for everything except media */
        html { transition: background-color 0.3s ease; }
        div, section, nav, header, footer, main,
        p, h1, h2, h3, h4, span, a, button,
        input, textarea, label {
            transition: background-color 0.25s ease, border-color 0.25s ease, color 0.2s ease, box-shadow 0.2s ease;
        }
    </style>
</head>
<body>

    {{-- Fixed navbar wrapper with padding top --}}
    <div id="nav-wrap" class="fixed top-0 left-0 right-0 z-50 px-4 pt-4 pb-0 transition-all duration-300">
        @include('components.navbar')
    </div>
    <div class="h-20"></div>

    {{-- Flash --}}
    @if(session('success'))
    <div id="flash-msg" class="fixed top-24 right-5 z-50 flex items-center gap-3 card-glass b-subtle px-5 py-3.5 rounded-2xl shadow-xl text-sm font-medium anim-up">
        <span class="w-7 h-7 bg-emerald-500/20 text-emerald-400 rounded-full flex items-center justify-center flex-none text-xs">
            <i class="fa-solid fa-check"></i>
        </span>
        <span class="t-primary">{{ session('success') }}</span>
    </div>
    @endif

    <main>@yield('content')</main>

    {{-- Delete confirmation modal --}}
    <div id="delete-confirm-modal" class="confirm-modal" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="delete-confirm-title">
        <div class="confirm-modal-backdrop" data-confirm-close></div>
        <div class="confirm-modal-panel" role="document">
            <div class="confirm-modal-icon" aria-hidden="true">
                <i class="fa-solid fa-trash-can"></i>
            </div>
            <h3 id="delete-confirm-title" class="confirm-modal-title">Hapus dari Favorit?</h3>
            <p id="delete-confirm-message" class="confirm-modal-message">Aksi ini tidak bisa dibatalkan.</p>
            <div class="confirm-modal-actions">
                <button id="delete-confirm-cancel" type="button" class="confirm-btn confirm-btn-ghost">
                    Batal
                </button>
                <button id="delete-confirm-submit" type="button" class="confirm-btn confirm-btn-danger">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <footer class="mt-24 border-t b-subtle py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl btn-primary flex items-center justify-center flex-none">
                    <i class="fa-solid fa-clapperboard text-white text-sm"></i>
                </div>
                <span class="font-display font-bold text-xl t-primary">Moviesiae</span>
            </div>
            <div class="flex items-center gap-6 text-sm t-muted">
                <a href="{{ route('home') }}" class="hover:t-accent transition-colors hover:text-indigo-400">Home</a>
                <a href="{{ route('movies.index') }}" class="hover:text-indigo-400 transition-colors">Movies</a>
                <a href="{{ route('favorites.index') }}" class="hover:text-indigo-400 transition-colors">Favorites</a>
            </div>
            <p class="t-muted text-sm">Powered by <a href="https://www.themoviedb.org" target="_blank" class="text-indigo-400 hover:text-indigo-300 font-medium">TMDB API</a></p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-7 pt-5 border-t b-subtle">
            <p class="text-center text-[11px] tracking-[0.18em] uppercase t-muted">
                Created by Azaila Putri
            </p>
        </div>
    </footer>

</body>
</html>
