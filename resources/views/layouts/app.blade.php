<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('root.headerMetadata')
    @stack('styles')
</head>
<body>
    @include('index.indexTop')

    <div class="container content-wrapper">
        @yield('content')
    </div>

    @include('index.indexBottom')

    @stack('scripts')
</body>
</html>
