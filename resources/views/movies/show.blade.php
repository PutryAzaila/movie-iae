@extends('layouts.app')
@section('content')

<div class="relative">

    @if($movie['backdrop_path'] ?? null)
    <div class="absolute inset-0 pointer-events-none" style="height:62vh;overflow:hidden">
        <img src="{{ $movie['backdrop_path'] }}" alt=""
             class="w-full h-full object-cover object-center">
        <div class="detail-backdrop-overlay absolute inset-0"></div>
    </div>
    @else
    <div class="absolute inset-0 pointer-events-none" style="height:32vh"
         :class="dark ? 'bg-[#0d1526]' : 'bg-slate-100'">
    </div>
    @endif

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14">
        <div class="flex flex-col md:flex-row gap-10">

            <div class="flex-none w-52 md:w-60 mx-auto md:mx-0 anim-up">
                <div class="rounded-2xl overflow-hidden shadow-2xl ring-1 b-subtle" style="aspect-ratio:2/3">
                    @if($movie['poster_path'])
                        <img src="{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-surface2 flex items-center justify-center">
                            <i class="fa-solid fa-film text-5xl" style="color:rgba(99,102,241,0.2)"></i>
                        </div>
                    @endif
                </div>

                @if($isFav)
                <form method="POST" action="{{ route('favorites.destroy', $favRecord->id) }}" class="mt-4" data-confirm-delete data-item-name="{{ $movie['title'] }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full flex items-center justify-center gap-2 text-sm font-medium py-3 px-4 rounded-xl transition-all"
                            style="background:rgba(239,68,68,0.10);border:1px solid rgba(239,68,68,0.20);color:#f87171">
                        <i class="fa-solid fa-heart-crack"></i> Hapus dari Favorit
                    </button>
                </form>
                @else
                <form method="POST" action="{{ route('favorites.store') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="tmdb_id"      value="{{ $movie['id'] }}">
                    <input type="hidden" name="title"         value="{{ $movie['title'] }}">
                    <input type="hidden" name="overview"      value="{{ $movie['overview'] }}">
                    <input type="hidden" name="poster_path"   value="{{ $movie['poster_path'] }}">
                    <input type="hidden" name="release_date"  value="{{ $movie['release_date'] }}">
                    <input type="hidden" name="vote_average"  value="{{ $movie['vote_average'] }}">
                    <button type="submit" class="w-full flex items-center justify-center gap-2 btn-primary text-white font-semibold py-3 px-4 rounded-xl text-sm">
                        <i class="fa-solid fa-heart"></i> Simpan ke Favorit
                    </button>
                </form>
                @endif

                <a href="{{ url()->previous() }}"
                   class="mt-3 w-full flex items-center justify-center gap-2 card-glass b-subtle t-muted font-medium py-2.5 px-4 rounded-xl text-sm hover:t-primary transition-all">
                    <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
                </a>
            </div>

            <div class="flex-1 min-w-0 pb-10 anim-up-d1">

                @if(!empty($movie['genres']))
                <div class="flex flex-wrap gap-2 mb-5">
                    @foreach($movie['genres'] as $genre)
                        <span class="text-xs font-medium px-3.5 py-1.5 rounded-full"
                              style="background:rgba(99,102,241,0.12);border:1px solid rgba(99,102,241,0.22);color:#a5b4fc">
                            {{ $genre }}
                        </span>
                    @endforeach
                </div>
                @endif

                <h1 class="font-display text-4xl sm:text-5xl font-bold text-hero leading-tight mb-3">
                    {{ $movie['title'] }}
                </h1>

                @if($movie['tagline'] ?? null)
                <p class="t-muted italic text-base mb-6">
                    <i class="fa-solid fa-quote-left mr-1" style="color:rgba(99,102,241,0.3)"></i>{{ $movie['tagline'] }}
                </p>
                @endif

                <div class="flex flex-wrap gap-3 mb-8">
                    <div class="flex items-center gap-2 px-4 py-2 rounded-full" style="background:rgba(245,158,11,0.12);border:1px solid rgba(245,158,11,0.22)">
                        <i class="fa-solid fa-star text-amber-400"></i>
                        <span class="t-primary font-bold">{{ number_format($movie['vote_average'], 1) }}</span>
                        <span class="t-muted text-xs">/10</span>
                    </div>
                    @if($movie['release_date'])
                    <div class="flex items-center gap-2 card-glass b-subtle px-4 py-2 rounded-full text-sm t-primary">
                        <i class="fa-regular fa-calendar t-accent"></i>
                        {{ \Carbon\Carbon::parse($movie['release_date'])->format('d M Y') }}
                    </div>
                    @endif
                    @if($movie['runtime'] ?? null)
                    <div class="flex items-center gap-2 card-glass b-subtle px-4 py-2 rounded-full text-sm t-primary">
                        <i class="fa-regular fa-clock t-accent"></i>
                        {{ floor($movie['runtime'] / 60) }}j {{ $movie['runtime'] % 60 }}m
                    </div>
                    @endif
                    @if($movie['vote_count'] ?? null)
                    <div class="flex items-center gap-2 card-glass b-subtle px-4 py-2 rounded-full text-sm t-primary">
                        <i class="fa-regular fa-thumbs-up t-accent"></i>
                        {{ number_format($movie['vote_count']) }} votes
                    </div>
                    @endif
                </div>

                @if($movie['overview'])
                <div class="card-glass b-subtle rounded-2xl p-6 mb-8">
                    <h3 class="text-xs font-bold t-accent uppercase tracking-widest mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-align-left"></i> Sinopsis
                    </h3>
                    <p class="t-muted leading-relaxed text-sm">{{ $movie['overview'] }}</p>
                </div>
                @endif

                @if(!empty($movie['cast']))
                <h3 class="text-xs font-bold t-accent uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-users"></i> Pemeran Utama
                </h3>
                <div class="flex flex-wrap gap-2.5">
                    @foreach($movie['cast'] as $actor)
                    <div class="flex items-center gap-2.5 card-glass b-subtle rounded-xl px-3 py-2 transition-colors" style="transition:border-color 0.2s ease">
                        <div class="w-8 h-8 rounded-full overflow-hidden flex-none ring-1 ring-indigo-500/20">
                            @if($actor['profile'])
                                <img src="{{ $actor['profile'] }}" alt="{{ $actor['name'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background:rgba(99,102,241,0.12)">
                                    <i class="fa-solid fa-user text-xs" style="color:rgba(99,102,241,0.4)"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <p class="t-primary text-xs font-semibold leading-tight">{{ $actor['name'] }}</p>
                            <p class="t-muted text-xs leading-tight">{{ $actor['character'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-14 mb-14 fade-section">
    <h2 class="font-display text-2xl font-bold t-primary mb-6 flex items-center gap-3">
        <div class="w-1 h-7 btn-primary rounded-full"></div>
        <i class="fa-brands fa-youtube" style="color:#ef4444"></i> Trailer
    </h2>

    @if($movie['trailer_url'] ?? null)
        @php preg_match('/[?&]v=([^&]+)/', $movie['trailer_url'], $m); $yt = $m[1] ?? null; @endphp
        @if($yt)
        <div class="relative rounded-2xl overflow-hidden shadow-2xl b-subtle ring-1" style="padding-top:42%">
            <iframe class="absolute inset-0 w-full h-full"
                src="https://www.youtube.com/embed/{{ $yt }}?rel=0&modestbranding=1"
                title="Trailer {{ $movie['title'] }}"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
        @endif
    @else
        <div class="rounded-2xl flex flex-col items-center justify-center py-16 gap-4"
             style="border:2px dashed rgba(99,102,241,0.15)">
            <div class="w-14 h-14 card-glass b-subtle rounded-2xl flex items-center justify-center">
                <i class="fa-solid fa-video-slash text-2xl t-muted"></i>
            </div>
            <div class="text-center">
                <p class="t-primary font-medium text-sm">Trailer tidak tersedia</p>
                <p class="t-muted text-xs mt-1">Belum ada trailer untuk film ini</p>
            </div>
        </div>
    @endif
</div>

@endsection
