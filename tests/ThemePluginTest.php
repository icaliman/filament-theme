<?php

namespace Yavuz\FilamentTheme\Tests;

use Filament\Enums\UserMenuPosition;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Illuminate\Foundation\Auth\User;
use Yavuz\FilamentTheme\ThemePlugin;

class ThemePluginTest extends TestCase
{
    public function test_the_theme_lays_the_panel_out_as_sidebar_and_top_bar(): void
    {
        $panel = Filament::getPanel('themed');

        $this->assertFalse($panel->hasTopbar(), "Filament's own top bar gives way to ours.");
        $this->assertSame(UserMenuPosition::Topbar, $panel->getUserMenuPosition());
        $this->assertFalse($panel->hasBreadcrumbs(), 'The trail moves into the top bar.');
        $this->assertSame(['command+k', 'ctrl+k'], $panel->getGlobalSearchKeyBindings());
        $this->assertSame(Color::Sky, $panel->getColors()['sidebar']);
    }

    public function test_without_the_top_bar_the_user_menu_stays_in_the_sidebar(): void
    {
        $panel = Filament::getPanel('sidebar-only');

        $this->assertFalse($panel->hasTopbar());
        $this->assertSame(UserMenuPosition::Sidebar, $panel->getUserMenuPosition());
        $this->assertTrue($panel->hasBreadcrumbs());
        $this->assertArrayNotHasKey('sidebar', $panel->getColors(), 'Without a sidebar colour the sidebar follows the primary.');
    }

    public function test_the_app_supplies_links_pending_work_and_who_the_user_is(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('themed'));
        $plugin = ThemePlugin::get();
        $user = new User;

        $this->assertTrue(ThemePlugin::isActive());
        $this->assertSame('Order', $plugin->getCreateLinks()[0]['label']);
        $this->assertTrue($plugin->hasPendingWork());
        $this->assertCount(2, $plugin->getPendingWork());
        $this->assertSame('Administrator', $plugin->getRoleLabel($user));
        $this->assertSame('Acme Inspections', $plugin->getOrganizationName($user));
        $this->assertSame('Everything in one place.', $plugin->getLoginAside()['heading']);
    }

    public function test_a_panel_without_the_theme_is_left_alone(): void
    {
        $panel = Filament::getPanel('plain');
        Filament::setCurrentPanel($panel);

        $this->assertFalse(ThemePlugin::isActive());
        $this->assertTrue($panel->hasTopbar());
    }
}
