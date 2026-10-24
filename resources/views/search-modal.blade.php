@if (filament()->isGlobalSearchEnabled())
    <x-filament::modal id="global-search" width="2xl" :close-button="false" :autofocus="true" class="fi-theme-search-modal">
        {{-- The search field swallows Escape to clear its results, so it is caught first to close the whole modal. --}}
        <div x-on:keydown.escape.capture="$dispatch('close-modal', { id: 'global-search' })">
            @livewire(Filament\Livewire\GlobalSearch::class)

            <div class="fi-theme-search-modal-footer">
                <span><kbd>↑</kbd><kbd>↓</kbd>{{ __('filament-theme::theme.search_modal.navigate') }}</span>
                <span><kbd>↵</kbd>{{ __('filament-theme::theme.search_modal.open') }}</span>
                <span><kbd>esc</kbd>{{ __('filament-theme::theme.search_modal.close') }}</span>
            </div>
        </div>
    </x-filament::modal>
@endif
