<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w3-margin-top">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    @if($errors->any())
                        @foreach($errors->all() as $error)
                            <div class="p-6 w3-pale-red w3-text-red border-b border-gray-200">
                                <div>{{ $error }}</div>
                            </div>
                        @endforeach
                    @endif
                    @if(isset($messages[0]['message']))
                        @foreach($messages as $message)
                            <div class="p-6 w3-pale-{{ $message['status'] == 'error' ? 'red' : 'yellow' }} w3-left-bar w3-text-{{ $message['status'] == 'error' ? 'red' : 'yellow' }} border-b border-gray-200">
                                <div>{{ $message['message'] }}</div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Page Content -->
            <main>
                @yield('slot')
            </main>
        </div>
    </body>
</html>
