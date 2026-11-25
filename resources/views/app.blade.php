<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ URL::asset('images/favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ URL::asset('images/favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ URL::asset('images/favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('images/favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ URL::asset('images/favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ URL::asset('images/favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ URL::asset('images/favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ URL::asset('images/favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('images/favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ URL::asset('images/favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ URL::asset('images/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('images/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ URL::asset('images/favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ URL::asset('images/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'BiraConsultant - Find Your Next Opportunity')</title>
    @yield('meta')
    @include('partials.adsense-script')
    <link href="{{ URL::asset('/css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/biraconsultant-theme.css') }}?v={{ filemtime(public_path('css/biraconsultant-theme.css')) }}" rel="stylesheet">
</head>
<body class="qj-app-shell">
    @yield('header')

    {{-- Global flash messages (status / success / error) --}}
    @if (session('success') || session('error') || session('status') || (auth()->check() && auth()->user()->email_verified_at === null))
    <div class="max-w-7xl mx-auto px-4 mt-3 space-y-2">
        @if (session('success'))
            <div class="flash-msg bg-green-100 border border-green-400 text-green-800 dark:bg-green-900/30 dark:border-green-700 dark:text-green-200 px-4 py-2.5 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="flash-msg bg-red-100 border border-red-400 text-red-800 dark:bg-red-900/30 dark:border-red-700 dark:text-red-200 px-4 py-2.5 rounded-lg text-sm">{{ session('error') }}</div>
        @endif
        @if (session('status'))
            <div class="flash-msg bg-yellow-100 border border-yellow-400 text-yellow-800 dark:bg-yellow-900/30 dark:border-yellow-700 dark:text-yellow-200 px-4 py-2.5 rounded-lg text-sm">{{ session('status') }}</div>
        @endif
        @if (auth()->check() && auth()->user()->email_verified_at === null)
            <div class="bg-orange-100 border border-orange-400 text-orange-800 dark:bg-orange-900/30 dark:border-orange-700 dark:text-orange-200 px-4 py-2.5 rounded-lg text-sm">
                <strong>Please verify your email address.</strong> Check your inbox for the verification link we sent when you registered.
            </div>
        @endif
    </div>
    @endif

    @yield('content')


    @yield('footer')
    
    <script src="{{ URL::asset('/js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
    @yield('js')
    @stack('scripts')
</body>
</html>
