@extends('layouts.app')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="mb-10 anim-up">
        <p class="t-accent text-xs font-bold uppercase tracking-widest mb-2">
            <i class="fa-solid fa-film mr-1.5"></i>{{ $query ? 'Search Results' : 'Discover' }}
        </p>
        <h1 class="font-display text-4xl font-bold t-primary mb-7">
            {{ $query ? '"'.$query.'"' : 'Film Populer' }}
        </h1>

        <form method="GET" action="{{ route('movies.index') }}" class="flex gap-3 max-w-2xl">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 t-muted text-sm"></i>
                <input type="text" name="q" value="{{ $query }}"
                       placeholder="Cari judul film..."
                       class="inp w-full pl-11 pr-4 py-3.5 rounded-xl text-sm transition-all">
            </div>
            <button type="submit" class="btn-primary text-white font-semibold px-6 py-3.5 rounded-xl text-sm flex items-center gap-2 flex-none">
                <i class="fa-solid fa-magnifying-glass"></i> Cari
            </button>
            @if($query)
            <a href="{{ route('movies.index') }}"
               class="card-glass b-subtle t-muted px-4 py-3.5 rounded-xl text-sm flex items-center hover:t-primary transition-all flex-none">
                <i class="fa-solid fa-xmark"></i>
            </a>
            @endif
        </form>
    </div>

    @if(count($movies) > 0)
        @if($query)
        <p class="t-muted text-sm mb-7">
            Ditemukan <span class="text-indigo-400 font-semibold">{{ number_format($meta['total_results']) }}</span> film
        </p>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 lg:gap-5">
            @foreach($movies as $movie)
                <x-movie-card :movie="$movie" />
            @endforeach
        </div>

        @if($meta['total_pages'] > 1)
        <div class="flex items-center justify-center gap-3 mt-16">
            @if($page > 1)
            <a href="{{ route('movies.index', ['q' => $query, 'page' => $page - 1]) }}"
               class="flex items-center gap-2 card-glass b-subtle t-muted px-5 py-2.5 rounded-full text-sm font-medium hover:t-primary hover:border-indigo-500/30 transition-all">
                <i class="fa-solid fa-chevron-left text-xs"></i> Prev
            </a>
            @endif
            <div class="px-6 py-2.5 card-glass b-subtle rounded-full text-sm">
                <span class="text-indigo-400 font-bold">{{ $page }}</span>
                <span class="t-muted mx-2">/</span>
                <span class="t-muted">{{ number_format($meta['total_pages']) }}</span>
            </div>
            @if($page < $meta['total_pages'])
            <a href="{{ route('movies.index', ['q' => $query, 'page' => $page + 1]) }}"
               class="flex items-center gap-2 card-glass b-subtle t-muted px-5 py-2.5 rounded-full text-sm font-medium hover:t-primary hover:border-indigo-500/30 transition-all">
                Next <i class="fa-solid fa-chevron-right text-xs"></i>
            </a>
            @endif
        </div>
        @endif
    @else
        <x-empty-state icon="search" title="Film tidak ditemukan" message="Coba kata kunci lain atau periksa ejaan film yang kamu cari.">
            <a href="{{ route('movies.index') }}" class="mt-6 inline-flex items-center gap-2 btn-primary text-white font-semibold px-6 py-3 rounded-full text-sm">
                <i class="fa-solid fa-rotate-left"></i> Lihat semua film
            </a>
        </x-empty-state>
    @endif

</div>
@endsection
