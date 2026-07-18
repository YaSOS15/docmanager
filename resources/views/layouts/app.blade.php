<!DOCTYPE html>
<html lang="fr" class="{{ auth()->check() && auth()->user()->theme === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DocManager')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">
    <x-toast />
    <div class="flex-1 flex flex-col">
        @yield('content')
    </div>
    <x-footer />
</body>
</html>