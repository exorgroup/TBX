{{-- row number --}}
@php
    $column['value'] = $column['value'] ?? $rowNumber;
    $column['escaped'] = $column['escaped'] ?? true;
    $column['limit'] = $column['limit'] ?? 32;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['text'] = $column['default'] ?? '-';
    $column['cellAlign'] = $column['cellAlign'] ?? null;

    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }

    if(!empty($column['value'])) {
        $column['text'] = $column['prefix'].Str::limit(strip_tags($column['value']), $column['limit'], "…").$column['suffix'];
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