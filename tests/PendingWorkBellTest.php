<?php

namespace Yavuz\FilamentTheme\Tests;

use Filament\Facades\Filament;
use Livewire\Livewire;
use Yavuz\FilamentTheme\Livewire\PendingWorkBell;

class PendingWorkBellTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Filament::setCurrentPanel(Filament::getPanel('themed'));
    }

    public function test_bell_totals_the_work_and_capitalises_each_line_in_the_apps_language(): void
    {
        // Turkish upper-cases "i" to "İ"; English to "I".
        app()->setLocale('tr');
        Livewire::withoutLazyLoading()->test(PendingWorkBell::class)
            ->assertSee('İş onayınızı bekliyor.')
            ->assertSee('Bekleyen işlerim')
            ->assertViewHas('pendingCount', 5);

        app()->setLocale('en');
        Livewire::withoutLazyLoading()->test(PendingWorkBell::class)
            ->assertSee('Inspections need review.')
            ->assertSee('Pending work');
    }

    public function test_bell_waits_for_the_page_with_a_placeholder(): void
    {
        app()->setLocale('en');

        Livewire::test(PendingWorkBell::class)->assertSee('Loading pending work')->assertDontSee('need review');
    }
}
