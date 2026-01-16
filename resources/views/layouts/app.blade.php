<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('root.headerMetadata')
    @stack('styles')

    {{-- React Fast Refresh --}}
    @viteReactRefresh

    @vite('resources/js/main.tsx')
</head>
<body>
    <div id="root"></div>

    @stack('scripts')
</body>
</html>
