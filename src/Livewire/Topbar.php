<?php

namespace Yavuz\FilamentTheme\Livewire;

use Filament\Livewire\Topbar as FilamentTopbar;
use Illuminate\Contracts\View\View;
use Yavuz\FilamentTheme\ThemePlugin;

/**
 * Bar across the top of the content area, beside the full-height sidebar: where the user is, search,
 * quick create, the work waiting on them (loaded after the page) and their user menu. Extends Filament's
 * topbar for the user menu.
 */
class Topbar extends FilamentTopbar
{
    /**
     * Where the user is, outermost first; entries without a url are not links.
     *
     * @var array<int, array{url: ?string, label: string}>
     */
    public array $breadcrumbs = [];

    public function render(): View
    {
        $plugin = ThemePlugin::get();
        $user = auth()->user();

        return view('filament-theme::topbar', [
            'createLinks' => $plugin->getCreateLinks(),
            'hasPendingWork' => $plugin->hasPendingWork(),
            'user' => $user,
            'roleLabel' => $plugin->getRoleLabel($user),
            'organizationName' => $plugin->getOrganizationName($user),
        ]);
    }
}
