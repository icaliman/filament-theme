<?php

namespace Yavuz\FilamentTheme\Livewire;

use BackedEnum;
use Filament\Livewire\Topbar as FilamentTopbar;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\View\View;
use UnitEnum;
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

    /**
     * The name of a sidebar group as the sidebar shows it: the label the panel registered for its key,
     * else the enum's label, else the group itself.
     */
    public static function navigationGroupLabel(string|UnitEnum $group): string
    {
        $key = match (true) {
            $group instanceof BackedEnum => $group->value,
            $group instanceof UnitEnum => $group->name,
            default => $group,
        };
        $registered = filament()->getCurrentOrDefaultPanel()->getNavigationGroups()[$key] ?? null;

        $label = match (true) {
            $registered instanceof NavigationGroup => $registered->getLabel() ?? $key,
            is_string($registered) => $registered,
            $group instanceof HasLabel => $group->getLabel(),
            default => $key,
        };

        return strip_tags((string) $label);
    }

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
