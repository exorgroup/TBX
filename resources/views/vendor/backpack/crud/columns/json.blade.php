{{-- json --}}
@php
    $column['value'] = $column['value'] ?? $entry->{$column['name']};
    $column['escaped'] = $column['escaped'] ?? true;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['wrapper']['element'] = $column['wrapper']['element'] ?? 'pre';
    $column['text'] = $column['default'] ?? '-';
    $column['cellAlign'] = $column['cellAlign'] ?? null;

    if(is_string($column['value'])) {
        $column['value'] = json_decode($column['value'], true);
    }

    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }

    if(!empty($column['value'])) {
        $column['text'] = $column['prefix'].json_encode($column['value'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).$column['suffix'];
    }
    
    // Generate inline style for cell alignment
    $wrapperStyle = '';
    if ($column['cellAlign']) {
        $wrapperStyle = ' style="text-align: ' . $column['cellAlign'] . ';"';
    }
@endphp

<div{!! $wrapperStyle !!}>
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
    @if($column['escaped'])
        {{ $column['text'] }}
    @else
        {!! $column['text'] !!}
    @endif
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
</div>