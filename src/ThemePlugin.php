<?php

namespace Yavuz\FilamentTheme;

use Closure;
use Filament\Actions\Action;
use Filament\Contracts\Plugin;
use Filament\Enums\GlobalSearchPosition;
use Filament\Enums\UserMenuPosition;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;

/**
 * The panel theme: dark sidebar headed by the brand, a top bar over the content (trail, search, quick create,
 * pending work and user menu), record counts under list headings, empty states and an optional login aside.
 *
 * The stylesheet is not loaded from here: import resources/css/theme.css into the panel's Vite theme.
 */
class ThemePlugin implements Plugin
{
    protected ?array $sidebarColor = null;

    protected bool $hasTopbar = true;

    /**
     * @var Closure(): array<int, array{label: string, icon: string, url: string}>|null
     */
    protected ?Closure $createLinks = null;

    /**
     * @var Closure(): array<int, array{count: int, text: string, url: string, color: string}>|null
     */
    protected ?Closure $pendingWork = null;

    /**
     * @var Closure(Authenticatable): ?string|null
     */
    protected ?Closure $roleLabel = null;

    /**
     * @var Closure(Authenticatable): ?string|null
     */
    protected ?Closure $organizationName = null;

    protected bool $hasRecordCounts = true;

    protected bool $hasEmptyStates = true;

    /**
     * @var array{heading: string, points?: array<string, string>, footer?: ?string}|null
     */
    protected ?array $loginAside = null;

    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * The plugin as registered on the current panel.
     */
    public static function get(): static
    {
        return Filament::getCurrentOrDefaultPanel()->getPlugin('filament-theme');
    }

    /**
     * Whether the current panel uses the theme at all.
     */
    public static function isActive(): bool
    {
        return Filament::getCurrentPanel()?->hasPlugin('filament-theme') ?? false;
    }

    public function getId(): string
    {
        return 'filament-theme';
    }

    /**
     * A palette of its own for the sidebar, e.g. Color::Sky; without one it takes the primary colour.
     *
     * @param  array<int|string, string>  $palette
     */
    public function sidebarColor(array $palette): static
    {
        $this->sidebarColor = $palette;

        return $this;
    }

    /**
     * Keep Filament's user menu at the foot of the sidebar instead of the top bar.
     */
    public function withoutTopbar(): static
    {
        $this->hasTopbar = false;

        return $this;
    }

    /**
     * Entries of the top bar's "Yeni" menu, each opening a create form; return only what the user may create.
     *
     * @param  Closure(): array<int, array{label: string, icon: string, url: string}>  $links
     */
    public function createLinks(Closure $links): static
    {
        $this->createLinks = $links;

        return $this;
    }

    /**
     * Work waiting on the user, counted behind the bell; without it there is no bell.
     *
     * @param  Closure(): array<int, array{count: int, text: string, url: string, color: string}>  $items
     */
    public function pendingWork(Closure $items): static
    {
        $this->pendingWork = $items;

        return $this;
    }

    /**
     * The line under the user's name in the user menu, such as their role.
     *
     * @param  Closure(Authenticatable): ?string  $label
     */
    public function roleLabel(Closure $label): static
    {
        $this->roleLabel = $label;

        return $this;
    }

    /**
     * The organization shown in the user menu; by default the current tenant's name.
     *
     * @param  Closure(Authenticatable): ?string  $name
     */
    public function organizationName(Closure $name): static
    {
        $this->organizationName = $name;

        return $this;
    }

    public function withoutRecordCounts(): static
    {
        $this->hasRecordCounts = false;

        return $this;
    }

    public function withoutEmptyStates(): static
    {
        $this->hasEmptyStates = false;

        return $this;
    }

    /**
     * A brand panel beside the login form on large screens.
     *
     * @param  array<string, string>  $points  icon => text
     */
    public function loginAside(string $heading, array $points = [], ?string $footer = null): static
    {
        $this->loginAside = ['heading' => $heading, 'points' => $points, 'footer' => $footer];

        return $this;
    }

    public function register(Panel $panel): void
    {
        // Filament's own top bar stays off so the sidebar keeps its full height and heads with the brand.
        $panel->topbar(false);

        if ($this->sidebarColor !== null) {
            $panel->colors(['sidebar' => $this->sidebarColor]);
        }

        $panel->renderHook(PanelsRenderHook::BODY_END, fn (): View => view('filament-theme::search-modal'));

        if ($this->hasTopbar) {
            $panel
                ->userMenu(position: UserMenuPosition::Topbar)
                ->userMenuItems(['logout' => fn (Action $action): Action => $action->color('danger')])
                ->breadcrumbs(false)
                // Our bar opens search in a modal; with Filament's top bar off this keeps its own field from showing.
                ->globalSearch(position: GlobalSearchPosition::Topbar)
                ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
                ->renderHook(PanelsRenderHook::PAGE_START, fn (): View => view('filament-theme::topbar-slot'));
        }

        if ($this->hasRecordCounts) {
            $panel->renderHook(PanelsRenderHook::PAGE_HEADER_HEADING_AFTER, fn (): View => view('filament-theme::record-count'));
        }

        if ($this->loginAside !== null) {
            $panel->renderHook(PanelsRenderHook::SIMPLE_LAYOUT_START, fn (): ?View => request()->routeIs("filament.{$panel->getId()}.auth.login") ? view('filament-theme::login-aside') : null);
        }
    }

    public function boot(Panel $panel): void {}

    public function hasEmptyStates(): bool
    {
        return $this->hasEmptyStates;
    }

    /**
     * @return array<int, array{label: string, icon: string, url: string}>
     */
    public function getCreateLinks(): array
    {
        return $this->createLinks ? ($this->createLinks)() : [];
    }

    public function hasPendingWork(): bool
    {
        return $this->pendingWork !== null;
    }

    /**
     * @return array<int, array{count: int, text: string, url: string, color: string}>
     */
    public function getPendingWork(): array
    {
        return $this->pendingWork ? ($this->pendingWork)() : [];
    }

    public function getRoleLabel(Authenticatable $user): ?string
    {
        return $this->roleLabel ? ($this->roleLabel)($user) : null;
    }

    public function getOrganizationName(Authenticatable $user): ?string
    {
        if ($this->organizationName) {
            return ($this->organizationName)($user);
        }

        $tenant = Filament::getTenant();

        return $tenant ? Filament::getTenantName($tenant) : null;
    }

    /**
     * @return array{heading: string, points?: array<string, string>, footer?: ?string}|null
     */
    public function getLoginAside(): ?array
    {
        return $this->loginAside;
    }
}
