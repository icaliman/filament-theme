<?php

namespace Yavuz\FilamentTheme\Tests\Fixtures;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Yavuz\FilamentTheme\ThemePlugin;

/**
 * Three panels: one with the full theme, one without our top bar and one without the theme at all.
 */
class TestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel->default()->id('themed')->path('themed')
            ->navigationGroups(['service' => NavigationGroup::make()->label(fn (): string => 'Work orders'), 'Customers'])
            ->plugin(
                ThemePlugin::make()
                    ->sidebarColor(Color::Sky)
                    ->createLinks(fn (): array => [['label' => 'Order', 'icon' => 'heroicon-o-plus', 'url' => '/orders/create']])
                    ->pendingWork(fn (): array => [
                        ['count' => 3, 'text' => 'iş onayınızı bekliyor.', 'url' => '/a', 'color' => 'warning'],
                        ['count' => 2, 'text' => 'inspections need review.', 'url' => '/b', 'color' => 'danger'],
                    ])
                    ->roleLabel(fn (): string => 'Administrator')
                    ->organizationName(fn (): string => 'Acme Inspections')
                    ->loginAside('Everything in one place.', ['heroicon-o-check' => 'Plan work.'], 'For teams'),
            );
    }

    public function register(): void
    {
        parent::register();

        Filament::registerPanel(fn (): Panel => Panel::make()->id('sidebar-only')->path('sidebar-only')->plugin(ThemePlugin::make()->withoutTopbar()->withoutRecordCounts()));
        Filament::registerPanel(fn (): Panel => Panel::make()->id('plain')->path('plain'));
    }
}
