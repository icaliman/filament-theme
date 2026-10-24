<?php

namespace Yavuz\FilamentTheme;

use Filament\Actions\CreateAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Yavuz\FilamentTheme\Livewire\PendingWorkBell;
use Yavuz\FilamentTheme\Livewire\Topbar;
use Yavuz\FilamentTheme\Tables\TableEmptyState;

class FilamentThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'filament-theme');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'filament-theme');
        $this->publishes([__DIR__.'/../resources/lang' => $this->app->langPath('vendor/filament-theme')], 'filament-theme-translations');

        Livewire::component('filament-theme.topbar', Topbar::class);
        Livewire::component('filament-theme.pending-work-bell', PendingWorkBell::class);

        // Create buttons lead with a plus, in panels using the theme.
        CreateAction::configureUsing(fn (CreateAction $action): CreateAction => ThemePlugin::isActive() ? $action->icon('heroicon-o-plus') : $action);

        // Yes/no icons say in words what they mean on hover, e.g. "Aktif: Evet".
        IconColumn::configureUsing(fn (IconColumn $column): IconColumn => ThemePlugin::isActive() ? $column->tooltip(
            fn (IconColumn $column, mixed $state): ?string => $column->isBoolean($state) && $state !== null ? $column->getLabel().': '.($state ? __('filament-theme::theme.boolean.yes') : __('filament-theme::theme.boolean.no')) : null,
        ) : $column);

        Table::configureUsing(TableEmptyState::apply(...));

        // A badge for a record's state, drawn with a leading coloured dot so it reads apart from counts and versions.
        TextColumn::macro('statusBadge', function (): TextColumn {
            /** @var TextColumn $this */
            return $this->badge()->extraAttributes(['class' => 'fi-theme-status'], merge: true);
        });

        // Cut long text to the limit, showing the whole of it in a tooltip only when something was cut.
        TextColumn::macro('limitWithTooltip', function (int $limit): TextColumn {
            /** @var TextColumn $this */
            return $this->limit($limit)->tooltip(function (mixed $state) use ($limit): ?string {
                $text = is_array($state) ? implode(', ', $state) : (string) $state;

                return mb_strlen($text) > $limit ? $text : null;
            });
        });
    }
}
