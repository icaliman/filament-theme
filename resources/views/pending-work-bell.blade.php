<div>
    <x-filament::dropdown placement="bottom-end" teleport width="md">
        <x-slot name="trigger">
            <x-filament::icon-button
                icon="heroicon-o-bell"
                color="gray"
                icon-size="lg"
                :label="__('filament-theme::theme.pending_work.label')"
                :badge="$pendingCount > 0 ? ($pendingCount > 99 ? '99+' : $pendingCount) : null"
                badge-color="danger"
                class="fi-theme-topbar-bell"
            />
        </x-slot>

        <div class="fi-theme-topbar-pending-heading">{{ __('filament-theme::theme.pending_work.label') }}</div>

        @if ($pending)
            <x-filament::dropdown.list>
                @foreach ($pending as $item)
                    <x-filament::dropdown.list.item tag="a" :href="$item['url']" :badge="$item['count']" :badge-color="$item['color']">
                        {{ $item['text'] }}
                    </x-filament::dropdown.list.item>
                @endforeach
            </x-filament::dropdown.list>
        @else
            <p class="fi-theme-topbar-pending-empty">{{ __('filament-theme::theme.pending_work.empty') }}</p>
        @endif
    </x-filament::dropdown>
</div>
