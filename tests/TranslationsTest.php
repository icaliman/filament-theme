<?php

namespace Yavuz\FilamentTheme\Tests;

use Illuminate\Support\Arr;

class TranslationsTest extends TestCase
{
    public function test_every_language_has_every_text(): void
    {
        $english = array_keys(Arr::dot(require __DIR__.'/../resources/lang/en/theme.php'));

        foreach (glob(__DIR__.'/../resources/lang/*/theme.php') as $file) {
            $this->assertEqualsCanonicalizing($english, array_keys(Arr::dot(require $file)), basename(dirname($file)).' is missing or has extra texts.');
        }
    }

    public function test_texts_follow_the_app_language(): void
    {
        app()->setLocale('tr');
        $this->assertSame('Bekleyen işlerim', __('filament-theme::theme.pending_work.label'));
        $this->assertSame('5 kayıt', trans_choice('filament-theme::theme.record_count', 5, ['count' => 5]));

        app()->setLocale('en');
        $this->assertSame('1 record', trans_choice('filament-theme::theme.record_count', 1, ['count' => 1]));
        $this->assertSame('5 records', trans_choice('filament-theme::theme.record_count', 5, ['count' => 5]));
    }
}
