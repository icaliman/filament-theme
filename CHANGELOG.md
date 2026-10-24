# Changelog

## 1.1.0

The stylesheet now comes precompiled and is linked on the panels that use the plugin, so apps need no custom theme, Vite or npm build for it. Publish it with `php artisan filament:assets`. If you imported the theme into a custom theme in 1.0, remove that `@import` and the package's `@source` lines.

## 1.0.2

The README no longer carries screenshots; the code is the same as 1.0.1.

## 1.0.1

Published on Packagist as `yavuz-dev/filament-theme`; the PHP namespace stays `Yavuz\FilamentTheme`. Update the CSS import and `@source` paths to `vendor/yavuz-dev/filament-theme`.

## 1.0.0

First release: dark full-height sidebar, top bar (trail, search modal, New menu, pending-work bell, user menu card), record counts, empty states, login aside, `statusBadge()` and `limitWithTooltip()` columns, row-number column, brand mark and activity timeline views, English and Turkish texts.
