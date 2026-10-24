@php
    use Filament\Support\Enums\Platform;

    $shortcut = Platform::detect() === Platform::Mac ? '⌘K' : 'Ctrl K';
    $keyBindings = collect(filament()->getGlobalSearchKeyBindings())->map(fn (string $keyBinding): string => str_replace('+', '-', $keyBinding))->implode('.');
@endphp

<header class="fi-theme-topbar">
    <nav aria-label="{{ __('filament-theme::theme.topbar.breadcrumbs') }}" class="fi-theme-topbar-crumbs">
        @foreach ($breadcrumbs as $crumb)
            @if (! $loop->first)
                <x-filament::icon icon="heroicon-m-chevron-right" class="fi-theme-topbar-crumb-separator" />
            @endif

            @if (filled($crumb['url']) && ! $loop->last)
                <a href="{{ $crumb['url'] }}" class="fi-theme-topbar-crumb">{{ $crumb['label'] }}</a>
            @else
                <span @class(['fi-theme-topbar-crumb', 'fi-theme-topbar-crumb-current' => $loop->last]) @if ($loop->last) aria-current="page" @endif>{{ $crumb['label'] }}</span>
            @endif
        @endforeach
    </nav>

    @if (filament()->isGlobalSearchEnabled())
        <button
            type="button"
            x-data="{}"
            x-on:click="$dispatch('open-modal', { id: 'global-search' })"
            x-mousetrap.global.{{ $keyBindings }}="$dispatch('open-modal', { id: 'global-search' })"
            class="fi-theme-topbar-search"
        >
            <x-filament::icon icon="heroicon-m-magnifying-glass" class="fi-theme-topbar-search-icon" />
            <span class="fi-theme-topbar-search-label">{{ __('filament-theme::theme.topbar.search') }}</span>
            <kbd class="fi-theme-topbar-search-shortcut">{{ $shortcut }}</kbd>
        </button>
    @endif

    <div class="fi-theme-topbar-end">
        @if ($createLinks)
            <x-filament::dropdown placement="bottom-end" teleport>
                <x-slot name="trigger">
                    <x-filament::button icon="heroicon-m-plus" size="sm">{{ __('filament-theme::theme.topbar.create') }}</x-filament::button>
                </x-slot>

                <x-filament::dropdown.list>
                    @foreach ($createLinks as $link)
                        <x-filament::dropdown.list.item tag="a" :href="$link['url']" :icon="$link['icon']">
                            {{ $link['label'] }}
                        </x-filament::dropdown.list.item>
                    @endforeach
                </x-filament::dropdown.list>
            </x-filament::dropdown>
        @endif

        @if ($hasPendingWork)
            @livewire(\Yavuz\FilamentTheme\Livewire\PendingWorkBell::class, key('filament-theme-pending-work'))
        @endif

        @if (filament()->hasUserMenu())
            @php
                $menuItems = collect($this->getUserMenuItems())->except('profile');
            @endphp

            <x-filament::dropdown placement="bottom-end" teleport width="xs" class="fi-theme-user-menu">
                <x-slot name="trigger">
                    <button type="button" aria-label="{{ __('filament-theme::theme.user_menu.label') }}" class="fi-theme-user-menu-trigger">
                        <x-filament-panels::avatar.user :user="$user" loading="lazy" />
                        <span class="fi-theme-user-menu-trigger-text">
                            <span class="fi-theme-user-menu-name">{{ filament()->getUserName($user) }}</span>
                            @if ($roleLabel)
                                <span class="fi-theme-user-menu-role">{{ $roleLabel }}</span>
                            @endif
                        </span>
                        <x-filament::icon icon="heroicon-m-chevron-down" class="fi-theme-user-menu-chevron" />
                    </button>
                </x-slot>

                <div class="fi-theme-user-menu-header">
                    <x-filament-panels::avatar.user :user="$user" loading="lazy" />
                    <div class="fi-theme-user-menu-identity">
                        <p class="fi-theme-user-menu-name">{{ filament()->getUserName($user) }}</p>
                        <p class="fi-theme-user-menu-email">{{ $user->email }}</p>
                        @if ($roleLabel)
                            <x-filament::badge color="primary" size="sm" class="fi-theme-user-menu-role-badge">{{ $roleLabel }}</x-filament::badge>
                        @endif
                    </div>
                </div>

                @if ($organizationName)
                    <div class="fi-theme-user-menu-membership">
                        <x-filament::icon icon="heroicon-m-building-office" class="size-4 shrink-0" />
                        <span class="fi-theme-user-menu-organization">{{ $organizationName }}</span>
                    </div>
                @endif

                @if (filament()->hasDarkMode() && (! filament()->hasDarkModeForced()) && filament()->hasThemeSwitcher())
                    <div class="fi-theme-user-menu-theme">
                        <span class="fi-theme-user-menu-section-label">{{ __('filament-theme::theme.user_menu.appearance') }}</span>
                        <x-filament-panels::theme-switcher />
                    </div>
                @endif

                @if ($menuItems->isNotEmpty())
                    <x-filament::dropdown.list class="fi-theme-user-menu-actions">
                        @foreach ($menuItems as $item)
                            {{ $item }}
                        @endforeach
                    </x-filament::dropdown.list>
                @endif
            </x-filament::dropdown>
        @endif
    </div>
</header>
