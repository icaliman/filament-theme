<?php

namespace Yavuz\FilamentTheme\Tables;

use Filament\Actions\Action;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Yavuz\FilamentTheme\ThemePlugin;

/**
 * Default empty state for the tables of panels using the theme: the resource's own icon, a line of help and,
 * where the page can create records, the same create button. A search or filter that matches nothing gets
 * its own message instead, so it never invites the user to add a first record that already exists.
 */
class TableEmptyState
{
    public static function apply(Table $table): void
    {
        if (! ThemePlugin::isActive() || ! ThemePlugin::get()->hasEmptyStates()) {
            return;
        }

        $table
            ->emptyState(fn (HasTable $livewire) => self::isNarrowedDown($livewire) ? view('filament-theme::table-no-matches') : null)
            ->emptyStateIcon(fn (HasTable $livewire) => method_exists($livewire, 'getResource') ? $livewire::getResource()::getNavigationIcon() : null)
            ->emptyStateDescription(fn (HasTable $livewire) => self::createAction($livewire) ? __('filament-theme::theme.empty_state.create') : __('filament-theme::theme.empty_state.none'))
            ->emptyStateActions([
                Action::make('emptyStateCreate')
                    ->label(fn (HasTable $livewire) => self::createAction($livewire)?->getLabel())
                    ->icon('heroicon-o-plus')
                    ->visible(fn (HasTable $livewire) => self::createAction($livewire) !== null)
                    ->url(fn (HasTable $livewire) => self::createAction($livewire)?->getUrl())
                    ->alpineClickHandler(fn (HasTable $livewire) => self::createAction($livewire)?->getUrl() ? null : "\$wire.mountAction('create')"),
            ]);
    }

    /**
     * The page's own visible create button, if it has one.
     */
    private static function createAction(HasTable $livewire): ?Action
    {
        if (! method_exists($livewire, 'getAction')) {
            return null;
        }

        $action = $livewire->getAction('create', isMounting: false);

        return $action?->isVisible() ? $action : null;
    }

    /**
     * The table is empty only because of a search, filter or tab: the records exist without them.
     */
    private static function isNarrowedDown(HasTable $livewire): bool
    {
        // A tab narrows the table's own query, so an empty tab is checked against the whole resource.
        if (method_exists($livewire, 'getDefaultActiveTab') && filled($livewire->activeTab) && $livewire->activeTab !== (string) $livewire->getDefaultActiveTab()) {
            return $livewire::getResource()::getEloquentQuery()->exists();
        }

        return $livewire->getTable()->getQuery()?->exists() ?? false;
    }
}
