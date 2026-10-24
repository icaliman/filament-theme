@php
    $events = $getState() ?? [];
@endphp

@if (empty($events))
    <p class="fi-theme-activity-empty">{{ __('filament-theme::theme.activity.empty') }}</p>
@else
    <ol class="fi-theme-activity">
        @foreach ($events as $event)
            <li class="fi-theme-activity-item">
                <span @class(['fi-theme-activity-icon', 'fi-color-'.$event['color']])>
                    <x-filament::icon :icon="$event['icon']" class="size-4" />
                </span>
                <div class="fi-theme-activity-body">
                    <p class="fi-theme-activity-title">
                        <span class="fi-theme-activity-actor">{{ $event['actor'] }}</span>
                        {{ $event['label'] }}
                    </p>
                    <p class="fi-theme-activity-time">{{ $event['at'] }}</p>
                    @if ($event['note'])
                        <blockquote class="fi-theme-activity-note">{{ $event['note'] }}</blockquote>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
@endif
