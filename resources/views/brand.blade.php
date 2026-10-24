@props([
    'icon' => \Filament\Support\Icons\Heroicon::OutlinedSquares2x2,
    'name',
    'tagline' => null,
])

{{-- A square mark in the primary colour beside the product name; turns light inside the dark sidebar. --}}
<div class="fi-theme-brand">
    <span class="fi-theme-brand-mark">
        <x-filament::icon :icon="$icon" class="size-5" />
    </span>
    <span class="fi-theme-brand-text">
        <span class="fi-theme-brand-name">{{ $name }}</span>
        @if (filled($tagline))
            <span class="fi-theme-brand-tagline">{{ $tagline }}</span>
        @endif
    </span>
</div>
