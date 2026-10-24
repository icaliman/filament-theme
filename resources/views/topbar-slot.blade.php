@php
    $page = \Livewire\Livewire::current();
    $label = fn ($value): string => strip_tags((string) ($value instanceof \UnitEnum ? $value->name : $value));

    // Filament's trail for the page, without the blank entry single-page resources leave in it.
    $breadcrumbs = collect(method_exists($page, 'getBreadcrumbs') ? $page->getBreadcrumbs() : [])
        ->filter(fn ($text): bool => filled($text))
        ->map(fn ($text, $url): array => ['url' => is_string($url) ? $url : null, 'label' => $label($text)])
        ->values();

    // Pages outside a resource (dashboard, calendars) have no trail; their title stands in for it.
    if ($breadcrumbs->isEmpty() && method_exists($page, 'getTitle')) {
        $breadcrumbs->push(['url' => null, 'label' => $label($page->getTitle())]);
    }

    // Lead with the sidebar group the page sits in, as the sidebar shows it.
    $navigation = method_exists($page, 'getResource') ? $page::getResource() : $page::class;
    if (filled($group = $navigation::getNavigationGroup())) {
        $breadcrumbs->prepend(['url' => null, 'label' => $label($group)]);
    }

    // A page named like its sidebar group ("Müşteriler › Müşteriler") reads as one step.
    $breadcrumbs = $breadcrumbs->reject(fn (array $crumb, int $index): bool => $index > 0 && $crumb['label'] === $breadcrumbs[$index - 1]['label'])->values();
@endphp

@livewire(\Yavuz\FilamentTheme\Livewire\Topbar::class, ['breadcrumbs' => $breadcrumbs->all()], key('filament-theme-topbar'))
