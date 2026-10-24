<?php

namespace Yavuz\FilamentTheme\Tests;

use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Symfony\Component\Process\Process;

class StylesheetTest extends TestCase
{
    public function test_the_precompiled_stylesheet_is_linked_on_themed_panels(): void
    {
        $panel = Filament::getPanel('themed');
        Filament::setCurrentPanel($panel);
        $panel->boot();

        $this->assertStringContainsString('css/yavuz-dev/filament-theme/filament-theme.css', FilamentView::renderHook(PanelsRenderHook::STYLES_AFTER)->toHtml());
    }

    public function test_panels_without_the_theme_get_no_stylesheet(): void
    {
        $panel = Filament::getPanel('plain');
        Filament::setCurrentPanel($panel);
        $panel->boot();

        $this->assertStringNotContainsString('filament-theme.css', FilamentView::renderHook(PanelsRenderHook::STYLES_AFTER)->toHtml());
    }

    public function test_the_shipped_stylesheet_is_built_from_the_current_sources(): void
    {
        $shipped = __DIR__.'/../resources/dist/filament-theme.css';
        $this->assertFileExists($shipped);

        if (! is_file(__DIR__.'/../node_modules/.bin/tailwindcss')) {
            $this->markTestSkipped('Run `npm install` to check the stylesheet against its sources.');
        }

        $fresh = tempnam(sys_get_temp_dir(), 'filament-theme').'.css';
        (new Process(['node_modules/.bin/tailwindcss', '--input', 'resources/css/compiled.css', '--output', $fresh, '--minify'], __DIR__.'/..'))->mustRun();

        $this->assertFileEquals($fresh, $shipped, 'resources/dist is out of date: run `npm run build`.');
    }
}
