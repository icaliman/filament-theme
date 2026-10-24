# Filament Theme

A panel theme for [Filament](https://filamentphp.com) 5: a dark, full-height sidebar headed by your brand, a top bar over the content, and calmer tables, badges, cards and empty states. Accents follow your panel's primary colour, so the theme takes on each app's brand.

## Features

- **Sidebar** in a dark shade of your brand colour (or a colour of its own), with the brand at its head and the collapse control beside it.
- **Top bar** over the content area:
  - the page trail, led by the sidebar group the page sits in;
  - a search box that opens Filament's global search in a centred modal, also on <kbd>⌘K</kbd> / <kbd>Ctrl K</kbd>;
  - a **New** menu of the records the user may create;
  - a **bell** listing the work waiting on the user, loaded after the page so it never holds it up;
  - a **user menu** card with name, e-mail, role, organisation, theme switcher and sign-out.
- **Tables**: a quiet uppercase header band, lighter row lines, soft row hover, pill badges.
- **Record count** under list page headings, following search, filters and tabs.
- **Empty states** with the resource's own icon, a line of help and the page's create button; a search or filter that matches nothing says so instead.
- **Login aside**: an optional brand panel beside the login form on large screens.
- **Helpers**: `statusBadge()` and `limitWithTooltip()` columns, a `#` row-number column, tooltips on yes/no icons, a plus on create buttons, a brand mark and an activity timeline view.
- English and Turkish included.

## Requirements

- PHP 8.3+
- Filament 5 with a [custom theme](https://filamentphp.com/docs/styling/overview#creating-a-custom-theme) (Tailwind CSS 4, Vite)

## Installation

```bash
composer require yavuz-dev/filament-theme
```

If your panel has no custom theme yet, create one:

```bash
php artisan make:filament-theme
```

Import the theme into the panel's theme file, after Filament's, and let Tailwind scan the package:

```css
/* resources/css/filament/admin/theme.css */
@import '../../../../vendor/filament/filament/resources/css/theme.css';
@import '../../../../vendor/yavuz-dev/filament-theme/resources/css/theme.css';

@source '../../../../app/Filament/**/*';
@source '../../../../resources/views/filament/**/*';
@source '../../../../vendor/yavuz-dev/filament-theme/resources/views/**/*';
@source '../../../../vendor/yavuz-dev/filament-theme/src/**/*';
```

Register the plugin on the panel:

```php
use Yavuz\FilamentTheme\ThemePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->viteTheme('resources/css/filament/admin/theme.css')
        ->plugin(ThemePlugin::make());
}
```

Then build your assets with `npm run build`.

## Configuration

Every option is optional.

```php
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Yavuz\FilamentTheme\ThemePlugin;

$panel
    ->brandLogo(fn () => view('filament-theme::brand', [
        'icon' => Heroicon::OutlinedShieldCheck,
        'name' => 'Acme',
        'tagline' => 'Inspections',
    ]))
    ->brandLogoHeight('2.5rem')
    ->plugin(
        ThemePlugin::make()
            ->sidebarColor(Color::Sky)
            ->createLinks(fn (): array => [
                ['label' => 'Work order', 'icon' => 'heroicon-o-clipboard-document-list', 'url' => WorkOrderResource::getUrl('index', ['action' => 'create'])],
            ])
            ->pendingWork(fn (): array => [
                ['count' => 3, 'text' => 'tasks wait for your answer.', 'url' => WorkOrderResource::getUrl('index'), 'color' => 'warning'],
            ])
            ->roleLabel(fn (User $user): ?string => $user->role->label())
            ->loginAside('Everything from order to report, in one place.', [
                'heroicon-o-clipboard-document-list' => 'Plan work and assign your team.',
                'heroicon-o-check-badge' => 'Approve, sign and share reports.',
            ], 'For inspection bodies'),
    );
```

| Method | What it does |
| --- | --- |
| `sidebarColor(array $palette)` | Gives the sidebar a colour of its own, such as `Color::Sky`. Without it the sidebar uses a dark shade of the primary colour. |
| `createLinks(Closure)` | Entries of the **New** menu as `label`, `icon` and `url`. Return only what the current user may create; with none, the menu is hidden. |
| `pendingWork(Closure)` | Items behind the bell as `count`, `text`, `url` and a badge `color`. Without it there is no bell. Items with a count of zero are best left out. |
| `roleLabel(Closure)` | The line under the user's name in the user menu. |
| `organizationName(Closure)` | The organisation shown in the user menu; by default the current tenant's name. |
| `loginAside(string $heading, array $points = [], ?string $footer = null)` | A brand panel beside the login form; `$points` maps an icon to a line of text. |
| `withoutTopbar()` | Leaves out the top bar; the user menu stays at the foot of the sidebar. |
| `withoutRecordCounts()` | Leaves out the record count under list headings. |
| `withoutEmptyStates()` | Keeps Filament's own empty states. |

The theme turns off Filament's own top bar and breadcrumbs, and sets the global search shortcut to <kbd>⌘K</kbd> / <kbd>Ctrl K</kbd>.

## Helpers

### Status badges

A badge for a record's state, led by a dot in its colour so it reads apart from counts and versions:

```php
TextColumn::make('status')->statusBadge()->color(fn (string $state) => ...);
```

### Shortened text with a tooltip

Cuts text to the limit and shows the whole of it on hover, only when something was cut:

```php
TextColumn::make('notes')->limitWithTooltip(50);
```

### Row numbers

A `#` column numbering rows as listed, for records with no code of their own. It counts on across pages and follows sorting and filters:

```php
use Yavuz\FilamentTheme\Tables\RowNumberColumn;

$table->columns([RowNumberColumn::make(), /* ... */]);
```

### Activity timeline

A view that draws "who did what, when" from your own audit data:

```php
ViewEntry::make('activity')
    ->view('filament-theme::activity-timeline')
    ->state(fn (Model $record): array => [
        ['label' => 'approved the report', 'icon' => 'heroicon-m-check', 'color' => 'success', 'actor' => 'Jane Doe', 'at' => '12.11.2026 15:02', 'note' => null],
    ]);
```

In panels using the theme, create buttons also get a plus icon and boolean icon columns say what they mean on hover, e.g. "Active: Yes".

## Translations

English and Turkish are included and follow the app's locale. To change a text, publish the language files and edit them in `lang/vendor/filament-theme`:

```bash
php artisan vendor:publish --tag=filament-theme-translations
```

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE](LICENSE).
