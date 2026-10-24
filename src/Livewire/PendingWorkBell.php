<?php

namespace Yavuz\FilamentTheme\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Lazy;
use Livewire\Component;
use Yavuz\FilamentTheme\ThemePlugin;

/**
 * The bell in the top bar. Counting pending work can take many queries, so it loads after the page does
 * instead of holding it up; a plain bell stands in meanwhile.
 */
#[Lazy]
class PendingWorkBell extends Component
{
    public function render(): View
    {
        // Items may be phrased to follow their count ("3 tasks …"); here the count sits on the right, so lines start with a capital.
        $pending = array_map(fn (array $item): array => ['text' => self::capitalize($item['text'])] + $item, ThemePlugin::get()->getPendingWork());

        return view('filament-theme::pending-work-bell', [
            'pending' => $pending,
            'pendingCount' => array_sum(array_column($pending, 'count')),
        ]);
    }

    /**
     * Upper-cases the first letter the way the app's language does it: Turkish "i" becomes "İ", not "I".
     */
    private static function capitalize(string $text): string
    {
        $first = mb_substr($text, 0, 1);
        $first = in_array(app()->getLocale(), ['tr', 'az'], true) ? strtr($first, ['i' => 'İ']) : $first;

        return mb_strtoupper($first).mb_substr($text, 1);
    }

    public function placeholder(): View
    {
        return view('filament-theme::pending-work-bell-placeholder');
    }
}
