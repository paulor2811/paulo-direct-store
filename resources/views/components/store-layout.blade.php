<html lang='{{ str_replace('_', '-', app()->getLocale()) }}'>

    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <meta name='csrf-token' content='{{ csrf_token() }}'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>

        <title>{{ config('app.name') }}</title>
        <link rel="icon" type="image/svg+xml" href="/favicon.svg">

        {{-- Vite CSS & JS --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class='bg-gray-50 dark:bg-gray-900 antialiased'>
        @include('layouts.navigation')

        <main class='min-h-screen'>
            {{ $slot }}
        </main>

        <footer class='bg-white rounded-lg shadow dark:bg-gray-800 m-4 mt-8'>
            <div class='w-full max-w-screen-xl mx-auto p-4 md:py-8'>
                <hr class='my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8' />
                <span class='block text-sm text-gray-500 sm:text-center dark:text-gray-400'>© 2026 <a href='#' class='hover:underline'>DirectMarketplaceBrazil™</a>. Todos os direitos reservados.</span>
            </div>
        </footer>
    </body>
</html>
