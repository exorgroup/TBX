{{-- localized datetime using carbon --}}
@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    $column['escaped'] = $column['escaped'] ?? true;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['format'] = $column['format'] ?? backpack_theme_config('default_datetime_format');
    $column['text'] = $column['default'] ?? '-';
    $column['cellAlign'] = $column['cellAlign'] ?? null;

    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }

    if(!empty($column['value'])) {
        $date = \Carbon\Carbon::parse($column['value'])
            ->locale(App::getLocale())
            ->isoFormat($column['format']);

        $column['text'] = $column['prefix'].$date.$column['suffix'];
    }
    
    // Generate inline style for cell alignment
    $cellStyle = '';
    if ($column['cellAlign']) {
        $cellStyle = 'style="text-align: ' . $column['cellAlign'] . ';"';
    }
@endphp

<span data-order="{{ $column['value'] ?? '' }}" {!! $cellStyle !!}>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
        @if($column['escaped'])
            {{ $column['text'] }}
        @else
            {!! $column['text'] !!}
        @endif
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
</span>