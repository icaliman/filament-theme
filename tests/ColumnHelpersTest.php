<?php

namespace Yavuz\FilamentTheme\Tests;

use Filament\Facades\Filament;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

class ColumnHelpersTest extends TestCase
{
    public function test_status_badge_is_a_badge_marked_for_the_leading_dot(): void
    {
        $column = TextColumn::make('status')->statusBadge();

        $this->assertTrue($column->isBadge());
        $this->assertStringContainsString('fi-theme-status', $column->getExtraAttributes()['class']);
    }

    public function test_shortened_text_shows_in_full_on_hover_only_when_cut(): void
    {
        $column = TextColumn::make('notes')->limitWithTooltip(10);

        $this->assertNull($column->getTooltip('Short note'));
        $this->assertSame('A note long enough to be cut', $column->getTooltip('A note long enough to be cut'));
        $this->assertSame('Brakes, Lights', $column->getTooltip(['Brakes', 'Lights']));
    }

    public function test_yes_no_icons_explain_themselves_only_in_themed_panels(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('themed'));
        app()->setLocale('en');
        $this->assertSame('Active: Yes', IconColumn::make('is_active')->label('Active')->boolean()->getTooltip(true));
        $this->assertSame('Active: No', IconColumn::make('is_active')->label('Active')->boolean()->getTooltip(false));

        Filament::setCurrentPanel(Filament::getPanel('plain'));
        $this->assertNull(IconColumn::make('is_active')->label('Active')->boolean()->getTooltip(true));
    }
}
