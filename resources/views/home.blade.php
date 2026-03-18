@extends('layouts.app')
@section('content')

@if($featured)
<section class="relative min-h-[88vh] flex items-end overflow-hidden">
    <div class="absolute inset-0">
        @if($featured['backdrop_path'] ?? null)
            <img src="{{ $featured['backdrop_path'] }}" alt="{{ $featured['title'] }}" class="w-full h-full object-cover scale-105">
        @elseif($featured['poster_path'])
            <img src="{{ $featured['poster_path'] }}" alt="{{ $featured['title'] }}" class="w-full h-full object-cover object-top scale-105">
        @endif
        <div class="absolute inset-0" style="background:linear-gradient(to right, rgba(8,13,26,0.97) 0%, rgba(8,13,26,0.75) 50%, rgba(8,13,26,0.2) 100%)"></div>
        <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(8,13,26,1) 0%, rgba(8,13,26,0.2) 40%, transparent 100%)"></div>
    </div>

    <div class="absolute top-24 left-[38%] w-80 h-80 bg-indigo-600/8 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 w-full">
        <div class="max-w-xl anim-up">
            <div class="inline-flex items-center gap-2 bg-indigo-500/12 border border-indigo-500/25 text-indigo-300 text-xs font-bold px-4 py-2 rounded-full mb-6 uppercase tracking-widest">
                <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-pulse"></span>
                Trending Today
            </div>

            <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl font-bold text-white leading-tight mb-5">
                {{ $featured['title'] }}
            </h1>

            <div class="flex flex-wrap items-center gap-4 mb-6 anim-up-d1">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-star text-amber-400 text-xl"></i>
                    <span class="text-white font-bold text-xl">{{ number_format($featured['vote_average'], 1) }}</span>
                    <span class="text-white/40 text-sm">/10</span>
                </div>
                @if($featured['release_date'])
                <span class="text-white/55 text-sm flex items-center gap-1.5 bg-white/5 border border-white/10 px-3 py-1.5 rounded-full">
                    <i class="fa-regular fa-calendar text-indigo-300"></i>
                    {{ \Carbon\Carbon::parse($featured['release_date'])->format('Y') }}
                </span>
                @endif
            </div>

            @if($featured['overview'])
            <p class="text-white/55 text-base leading-relaxed line-clamp-3 mb-9 anim-up-d2">
                {{ $featured['overview'] }}
            </p>
            @endif

            <div class="flex items-center gap-3 flex-wrap anim-up-d3">
                <a href="{{ route('movies.show', $featured['id']) }}"
                   class="inline-flex items-center gap-2 btn-primary text-white font-semibold text-sm px-5 py-2.5 rounded-full">
                    <i class="fa-solid fa-circle-play"></i> Lihat Detail
                </a>
                <a href="{{ route('movies.index') }}"
                   class="inline-flex items-center gap-2 text-white/65 hover:text-white font-medium text-sm px-5 py-2.5 rounded-full transition-all border border-white/12 hover:border-white/25 hover:bg-white/5">
                    <i class="fa-solid fa-compass"></i> Jelajahi
                </a>
            </div>
        </div>
    </div>

    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/25 text-xs">
        <i class="fa-solid fa-chevron-down animate-bounce"></i>
    </div>
</section>
@endif

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    @if(count($trendingMovies) > 0)
    <section class="mt-20 fade-section">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="t-accent text-xs font-bold uppercase tracking-widest mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-fire-flame-curved"></i> Hot Right Now
                </p>
                <h2 class="font-display text-2xl font-bold t-primary">Trending Hari Ini</h2>
            </div>
            <a href="{{ route('movies.index') }}" class="flex items-center gap-1.5 text-sm t-muted hover:text-indigo-400 transition-colors b-subtle border px-4 py-2 rounded-full hover:border-indigo-500/30">
                Semua <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 lg:gap-5">
            @foreach($trendingMovies as $movie)
                <x-movie-card :movie="$movie" />
            @endforeach
        </div>
    </section>
    @endif

    @if(count($popularMovies) > 0)
    <section class="mt-20 mb-12 fade-section">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="t-accent text-xs font-bold uppercase tracking-widest mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-chart-line"></i> Most Watched
                </p>
                <h2 class="font-display text-2xl font-bold t-primary">Film Populer</h2>
            </div>
            <a href="{{ route('movies.index') }}" class="flex items-center gap-1.5 text-sm t-muted hover:text-indigo-400 transition-colors b-subtle border px-4 py-2 rounded-full hover:border-indigo-500/30">
                Semua <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 lg:gap-5">
            @foreach($popularMovies as $movie)
                <x-movie-card :movie="$movie" />
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection
