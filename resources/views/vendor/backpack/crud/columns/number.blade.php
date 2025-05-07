{{-- number column regular object attribute --}}
@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    $column['escaped'] = $column['escaped'] ?? true;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['decimals'] = $column['decimals'] ?? 0;
    $column['dec_point'] = $column['dec_point'] ?? '.';
    $column['thousands_sep'] = $column['thousands_sep'] ?? ',';
    $column['text'] = $column['default'] ?? '-';
    $column['cellAlign'] = $column['cellAlign'] ?? null;

    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }
    
    if (!is_null($column['value'])) {
        $column['value'] = number_format($column['value'], $column['decimals'], $column['dec_point'], $column['thousands_sep']);
        $column['text'] = $column['prefix'].$column['value'].$column['suffix'];
    }
    
    // Generate inline style for cell alignment
    $cellStyle = '';
    if ($column['cellAlign']) {
        $cellStyle = 'style="text-align: ' . $column['cellAlign'] . ';"';
    }
@endphp

<span {!! $cellStyle !!}>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
        @if($column['escaped'])
            {{ $column['text'] }}
        @else
            {!! $column['text'] !!}
        @endif
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
</span>