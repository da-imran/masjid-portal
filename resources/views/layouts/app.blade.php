<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('root.headerMetadata')
    @stack('styles')

    {{-- React Fast Refresh Preamble --}}
    <script type="module">
        import RefreshRuntime from 'http://localhost:5173/@react-refresh'
        RefreshRuntime.injectIntoGlobalHook(window)
        window.$RefreshReg$ = () => {}
        window.$RefreshSig$ = () => (type) => type
        window.__vite_plugin_react_preamble_installed__ = true
    </script>

    @vite('resources/js/main.tsx')
</head>
<body>
    <div id="root"></div>

    @stack('scripts')
</body>
</html>
