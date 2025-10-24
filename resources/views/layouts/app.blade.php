<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <x-alert />
</head>

<body class="font-sans antialiased bg-sky-50">
    <div class="flex h-screen">
        <div id="overlay" class="fixed inset-0 z-40 hidden bg-black bg-opacity-50 lg:hidden"></div>
        <x-sidebar />

        <main class="flex flex-1 flex-col min-w-0">
            <x-header />
            <div class="flex-1 overflow-y-auto p-2">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>
