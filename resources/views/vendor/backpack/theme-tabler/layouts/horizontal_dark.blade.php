<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}" dir="{{ backpack_theme_config('html_direction') }}">

<head>
    @include(backpack_view('inc.head'))
    <link href="{{ asset('css/backpack-custom.css') }}" rel="stylesheet">
</head>

<body class="{{ backpack_theme_config('classes.body') }}" bp-layout="horizontal-dark">

@include(backpack_view('layouts.partials.light_dark_mode_logic'))

<div class="page">
    <div class="page-wrapper">

        <div class="@if(backpack_theme_config('options.useStickyHeader')) sticky-top @endif">
            @includeWhen(backpack_theme_config('options.doubleTopBarInHorizontalLayouts'), backpack_view('layouts._horizontal_dark.header_container'))
            @include(backpack_view('layouts._horizontal_dark.menu_container'))
        </div>

        <div class="page-body">
            <main class="{{ backpack_theme_config('options.useFluidContainers') ? 'container-fluid' : 'container-xl' }}">

                @yield('before_breadcrumbs_widgets')
                @includeWhen(isset($breadcrumbs), backpack_view('inc.breadcrumbs'))
                @yield('after_breadcrumbs_widgets')
                @yield('header')

                <div class="container-fluid animated fadeIn">
                    @yield('before_content_widgets')
                    @yield('content')
                    @yield('after_content_widgets')
                </div>
            </main>
        </div>

        @include(backpack_view('inc.footer'))
    </div>
</div>

@yield('before_scripts')
@stack('before_scripts')

@include(backpack_view('inc.scripts'))
@include(backpack_view('inc.theme_scripts'))

@yield('after_scripts')
@stack('after_scripts')
<!-- Include CSRF token for AJAX requests -->
<script>
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
</body>
</html>
