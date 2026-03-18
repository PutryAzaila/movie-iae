@extends('layouts.app')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="mb-10 anim-up">
        <p class="t-accent text-xs font-bold uppercase tracking-widest mb-2 flex items-center gap-2">
            <i class="fa-solid fa-database"></i> Database Lokal
        </p>
        <h1 class="font-display text-4xl font-bold t-primary flex items-center gap-3 mb-1">
            <i class="fa-solid fa-heart text-indigo-500"></i> Favorit Saya
        </h1>
        <p class="t-muted text-sm mt-1">{{ $favorites->count() }} film tersimpan</p>
    </div>

    @if($favorites->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 lg:gap-5">
            @foreach($favorites as $fav)
            <div class="group relative">
                <a href="{{ route('movies.show', $fav->tmdb_id) }}" class="block card-hover">
                    <div class="relative overflow-hidden rounded-2xl bg-surface" style="aspect-ratio:2/3">

                        @if($fav->poster_path)
                            <img src="{{ $fav->poster_path }}" alt="{{ $fav->title }}"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                 loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-surface2">
                                <i class="fa-solid fa-film text-4xl" style="color:rgba(99,102,241,0.2)"></i>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-50 group-hover:opacity-80 transition-opacity duration-300"></div>

                        <div class="absolute inset-0 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center rounded-2xl" style="background:rgba(8,13,26,0.88)">
                            <form method="POST" action="{{ route('favorites.destroy', $fav->id) }}" data-confirm-delete data-item-name="{{ $fav->title }}">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="flex items-center gap-2 text-xs font-semibold px-4 py-2.5 rounded-xl transition-all"
                                        style="background:rgba(239,68,68,0.18);border:1px solid rgba(239,68,68,0.28);color:#f87171">
                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                </button>
                            </form>
                        </div>

                        @if($fav->vote_average)
                        <div class="absolute top-2.5 right-2.5">
                            <div class="flex items-center gap-1 bg-black/55 backdrop-blur-sm px-2.5 py-1 rounded-full">
                                <i class="fa-solid fa-star text-amber-400 text-xs"></i>
                                <span class="text-white text-xs font-semibold">{{ number_format($fav->vote_average, 1) }}</span>
                            </div>
                        </div>
                        @endif

                        <div class="absolute top-2.5 left-2.5">
                            <div class="w-7 h-7 btn-primary rounded-xl flex items-center justify-center shadow-md">
                                <i class="fa-solid fa-heart text-white text-xs"></i>
                            </div>
                        </div>

                    </div>

                    <div class="mt-3 px-0.5">
                        <h3 class="text-sm font-semibold t-primary group-hover:text-indigo-400 transition-colors line-clamp-2 leading-snug">
                            {{ $fav->title }}
                        </h3>
                        <p class="text-xs t-muted mt-1 flex items-center gap-1">
                            <i class="fa-regular fa-calendar text-xs"></i>
                            {{ $fav->release_date ? \Carbon\Carbon::parse($fav->release_date)->format('Y') : '—' }}
                        </p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    @else
        <x-empty-state icon="heart" title="Belum ada favorit"
            message="Kamu belum menyimpan film apapun. Jelajahi film dan klik Simpan ke Favorit!">
            <a href="{{ route('movies.index') }}"
               class="mt-6 inline-flex items-center gap-2 btn-primary text-white font-semibold px-6 py-3 rounded-full text-sm">
                <i class="fa-solid fa-compass"></i> Jelajahi Film
            </a>
        </x-empty-state>
    @endif

</div>
@endsection
