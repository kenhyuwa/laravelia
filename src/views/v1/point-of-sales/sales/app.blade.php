<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include(__v().'.layouts.partials.head')
    <body class="hold-transition modern-{{ auth()->user()->my_color ?? 'purple' }}-skin fixed sidebar-mini sidebar-collapse">
        <div id="app" class="wrapper">
            @include(__v().'.layouts.partials.header')
            <div class="content-wrapper">
                @yield('content')
            </div>
            @include(__v().'.layouts.partials.footer')
            @include(__v().'.layouts.partials.rightbar')
        </div>
        @stack('lang')
        <script src="{{ mix(__v().'/js/app.js') }}"></script>
        @yield('js')
    </body>
</html>