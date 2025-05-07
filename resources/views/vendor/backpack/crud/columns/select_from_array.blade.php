{{-- select_from_array column --}}
@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    $column['escaped'] = $column['escaped'] ?? true;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['cellAlign'] = $column['cellAlign'] ?? null;

    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }

    $list = [];
    if ($column['value'] !== null) {
        if (is_array($column['value'])) {
            foreach ($column['value'] as $key => $value) {
                if (! is_null($value)) {
                    $list[$key] = $column['options'][$value] ?? $value;
                }
            }
        } else {
            $list[$column['value']] = $column['options'][$column['value']] ?? $column['value'];
        }
    }
    
    // Generate inline style for cell alignment
    $cellStyle = '';
    if ($column['cellAlign']) {
        $cellStyle = 'style="text-align: ' . $column['cellAlign'] . ';"';
    }
@endphp

<span {!! $cellStyle !!}>
    @if(!empty($list))
        {{ $column['prefix'] }}
        @foreach($list as $key => $text)
            @php
                $related_key = $key;
            @endphp

            <span class="d-inline-flex">
                @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
                    @if($column['escaped'])
                        {{ $text }}
                    @else
                        {!! $text !!}
                    @endif
                @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')

                @if(!$loop->last), @endif
            </span>
        @endforeach
        {{ $column['suffix'] }}
    @else
        {{ $column['default'] ?? '-' }}
    @endif
</span>