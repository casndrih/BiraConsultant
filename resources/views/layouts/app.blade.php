<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BiraConsultant - @yield('title', 'Authentication')</title>
    <link href="{{ URL::asset('/css/app.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/css/biraconsultant-theme.css') }}?v=20261103.1" rel="stylesheet">
</head>
<body class="bc-app-shell bc-auth-screen">
    <div class="bc-auth-card">
        @yield('content')
    </div>
    <script src="{{ URL::asset('/js/app.js') }}"></script>
</body>
</html>
