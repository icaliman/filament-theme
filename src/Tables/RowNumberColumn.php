<?php

namespace Yavuz\FilamentTheme\Tables;

use Filament\Tables\Columns\TextColumn;

/**
 * A "#" column numbering rows as listed, for tables whose records carry no code or number of their own.
 * It counts on across pages and follows sorting and filters, so it is a position in the list, not an identifier.
 */
class RowNumberColumn
{
    public static function make(): TextColumn
    {
        return TextColumn::make('row_number')->label('#')->rowIndex()->color('gray')->width('3rem');
    }
}
