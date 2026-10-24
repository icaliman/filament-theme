@php
    $page = \Livewire\Livewire::current();
@endphp

@if ($page instanceof \Filament\Resources\Pages\ListRecords)
    @php
        $count = $page->getAllTableRecordsCount();
    @endphp

    {{-- Follows the table's search and filters, so it always matches what the list shows. --}}
    <p class="fi-theme-record-count">{{ trans_choice('filament-theme::theme.record_count', $count, ['count' => \Illuminate\Support\Number::format($count, locale: app()->getLocale())]) }}</p>
@endif
