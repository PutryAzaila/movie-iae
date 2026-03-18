@props(['title' => 'Tidak ada data', 'message' => 'Belum ada data.', 'icon' => 'film'])

<div class="flex flex-col items-center justify-center py-28 text-center">
    <div class="w-20 h-20 rounded-3xl card-glass b-subtle flex items-center justify-center mb-6">
        @if($icon === 'search')
            <i class="fa-solid fa-magnifying-glass text-3xl" style="color:rgba(99,102,241,0.35)"></i>
        @elseif($icon === 'heart')
            <i class="fa-solid fa-heart text-3xl" style="color:rgba(99,102,241,0.35)"></i>
        @else
            <i class="fa-solid fa-film text-3xl" style="color:rgba(99,102,241,0.35)"></i>
        @endif
    </div>
    <h3 class="text-lg font-semibold t-primary mb-2">{{ $title }}</h3>
    <p class="t-muted text-sm max-w-xs leading-relaxed">{{ $message }}</p>
    {{ $slot }}
</div>
