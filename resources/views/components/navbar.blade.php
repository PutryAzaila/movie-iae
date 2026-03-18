@php
    $favCount = \App\Models\Favorite::where('session_id', session()->getId())->count();
@endphp

<nav class="navbar-pill flex items-center justify-between px-3 py-2 max-w-5xl mx-auto gap-3">

    <a href="{{ route('home') }}" class="flex items-center gap-2.5 flex-none group">
        <div class="w-8 h-8 rounded-xl btn-primary flex items-center justify-center shadow-md">
            <i class="fa-solid fa-clapperboard text-white text-xs"></i>
        </div>
        <span class="font-display font-bold text-base t-primary hidden sm:block">Moviesiae</span>
    </a>

    <div class="flex items-center">
        <a href="{{ route('home') }}"
           class="nav-item flex items-center gap-1.5 px-3.5 py-2 rounded-full text-sm font-medium transition-all
           {{ request()->routeIs('home') ? 'nav-item-active' : 't-muted' }}">
            <i class="fa-solid fa-house text-xs"></i>
            <span class="hidden md:inline">Home</span>
        </a>

        <a href="{{ route('movies.index') }}"
           class="nav-item flex items-center gap-1.5 px-3.5 py-2 rounded-full text-sm font-medium transition-all
           {{ request()->routeIs('movies.*') ? 'nav-item-active' : 't-muted' }}">
            <i class="fa-solid fa-film text-xs"></i>
            <span class="hidden md:inline">Movies</span>
        </a>

        <a href="{{ route('movies.index') }}"
           class="nav-item hidden lg:flex items-center gap-1.5 px-3.5 py-2 rounded-full text-sm font-medium transition-all t-muted">
            <i class="fa-solid fa-fire text-xs"></i>
            <span>Popular</span>
        </a>

        <a href="{{ route('favorites.index') }}"
           class="nav-item flex items-center gap-1.5 px-3.5 py-2 rounded-full text-sm font-medium transition-all
           {{ request()->routeIs('favorites.*') ? 'nav-item-active' : 't-muted' }}">
            <i class="fa-solid fa-heart text-xs"></i>
            <span class="hidden md:inline">Favorites</span>
            @if($favCount > 0)
                <span class="bg-indigo-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full leading-none ml-0.5">{{ $favCount }}</span>
            @endif
        </a>
    </div>

    <div class="flex items-center gap-1.5 flex-none">

        <a href="{{ route('movies.index') }}"
           class="w-9 h-9 flex items-center justify-center rounded-full t-muted hover:t-primary transition-all nav-item">
            <i class="fa-solid fa-magnifying-glass text-sm"></i>
        </a>

        <button id="theme-toggle"
                class="w-9 h-9 flex items-center justify-center rounded-full theme-btn nav-item transition-all"
                title="Ganti tema">
            <i id="theme-icon" class="fa-solid fa-sun text-sm"></i>
        </button>

        <a href="{{ route('favorites.index') }}"
           class="hidden sm:flex items-center gap-2 btn-primary text-white font-semibold text-sm px-4 py-2 rounded-full">
            <i class="fa-solid fa-heart text-xs"></i>
            <span class="hidden md:inline">Favorit</span>
            @if($favCount > 0)
                <span class="bg-white/25 text-white text-xs font-bold px-1.5 py-0.5 rounded-full leading-none">{{ $favCount }}</span>
            @endif
        </a>

    </div>
</nav>
