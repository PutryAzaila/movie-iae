@props(['movie'])

<a href="{{ route('movies.show', $movie['id']) }}" class="group block movie-card">

    <div class="poster-wrap bg-surface">

        @if($movie['poster_path'])
            <img src="{{ $movie['poster_path'] }}"
                 alt="{{ $movie['title'] }}"
                 loading="lazy">
        @else
            <div class="w-full h-full flex flex-col items-center justify-center gap-3 bg-surface2">
                <i class="fa-solid fa-film text-4xl" style="color:rgba(99,102,241,0.2)"></i>
            </div>
        @endif

        <div class="poster-overlay"></div>

        <div style="position:absolute;top:10px;right:10px;z-index:10">
            <div style="display:flex;align-items:center;gap:4px;background:rgba(0,0,0,0.58);backdrop-filter:blur(6px);padding:4px 10px;border-radius:9999px">
                <i class="fa-solid fa-star" style="color:#fbbf24;font-size:11px"></i>
                <span style="color:#fff;font-size:12px;font-weight:600">{{ number_format($movie['vote_average'], 1) }}</span>
            </div>
        </div>

        <div class="poster-cta">
            <div class="btn-primary flex items-center justify-center gap-1.5 text-white text-[11px] font-semibold py-2 rounded-lg">
                <i class="fa-solid fa-eye text-xs"></i> Lihat Detail
            </div>
        </div>

    </div>

    <div style="margin-top:10px;padding:0 2px">
        <h3 style="font-size:13px;font-weight:600;line-clamp:2;line-height:1.4;transition:color 0.2s ease"
            class="t-primary group-hover:text-indigo-400 line-clamp-2">
            {{ $movie['title'] }}
        </h3>
        <p style="font-size:11px;margin-top:3px;display:flex;align-items:center;gap:4px" class="t-muted">
            <i class="fa-regular fa-calendar" style="font-size:10px"></i>
            {{ $movie['release_date'] ? \Carbon\Carbon::parse($movie['release_date'])->format('Y') : '—' }}
        </p>
    </div>

</a>
