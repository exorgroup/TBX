@php
    $isValid = $entry->verifySignature();
@endphp

<span>
    {!! $isValid ? \App\Utils\SvgIcons::check() : \App\Utils\SvgIcons::x() !!}
</span>

