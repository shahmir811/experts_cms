<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div id="app">
        @include('partials.navbar')

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- production version of vuejs 2.0, optimized for size and speed -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>  
    @stack('scripts') <!-- To use, add @push('scripts') ... @endpush -->
</body>
</html>
