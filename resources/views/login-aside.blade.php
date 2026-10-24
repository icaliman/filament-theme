@php
    $aside = \Yavuz\FilamentTheme\ThemePlugin::get()->getLoginAside();
@endphp

{{-- Brand panel beside the login form; hidden on small screens, where the form stands alone. --}}
<aside class="fi-theme-login-aside">
    <x-filament-panels::logo />

    <div class="fi-theme-login-aside-body">
        <h2 class="fi-theme-login-aside-heading">{{ $aside['heading'] }}</h2>

        @if (filled($aside['points'] ?? []))
            <ul class="fi-theme-login-aside-points">
                @foreach ($aside['points'] as $icon => $text)
                    <li>
                        <x-filament::icon :icon="$icon" class="fi-theme-login-aside-point-icon" />
                        {{ $text }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    @if (filled($aside['footer'] ?? null))
        <p class="fi-theme-login-aside-footer">{{ $aside['footer'] }}</p>
    @endif
</aside>
