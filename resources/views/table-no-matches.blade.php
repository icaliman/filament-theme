<div class="fi-ta-empty-state" role="status">
    <div class="fi-ta-empty-state-content">
        <div class="fi-ta-empty-state-icon-bg">
            {{ \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::OutlinedMagnifyingGlass, size: \Filament\Support\Enums\IconSize::Large) }}
        </div>

        <h4 class="fi-ta-empty-state-heading">{{ __('filament-theme::theme.no_matches.heading') }}</h4>

        <p class="fi-ta-empty-state-description">{{ __('filament-theme::theme.no_matches.description') }}</p>
    </div>
</div>
