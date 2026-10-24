<?php

namespace Yavuz\FilamentTheme\Tests;

use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Foundation\Auth\User;
use Livewire\Livewire;
use Yavuz\FilamentTheme\Livewire\Topbar;

class TopbarTest extends TestCase
{
    public function test_the_top_bar_keeps_what_the_app_hooks_into_filaments_own(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('themed'));
        $this->actingAs((new User)->forceFill(['name' => 'Jane Doe', 'email' => 'jane@example.com']));

        FilamentView::registerRenderHook(PanelsRenderHook::TOPBAR_START, fn (): string => 'hooked-start');
        FilamentView::registerRenderHook(PanelsRenderHook::GLOBAL_SEARCH_BEFORE, fn (): string => 'hooked-before-search');
        FilamentView::registerRenderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, fn (): string => 'hooked-after-search');
        FilamentView::registerRenderHook(PanelsRenderHook::TOPBAR_END, fn (): string => 'hooked-end');

        Livewire::test(Topbar::class)
            ->assertSeeInOrder(['hooked-start', 'hooked-before-search', 'hooked-after-search', 'Jane Doe', 'hooked-end']);
    }

    public function test_the_trail_names_a_sidebar_group_as_the_sidebar_does(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('themed'));

        $this->assertSame('Work orders', Topbar::navigationGroupLabel('service'));
        $this->assertSame('Customers', Topbar::navigationGroupLabel('Customers'));
        $this->assertSame('Reports', Topbar::navigationGroupLabel('Reports'));
    }
}
