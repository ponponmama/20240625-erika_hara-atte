<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Atte</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link href="{{ asset('css/common.css') }}" rel="stylesheet">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <h2 class="top_logo">Atte</h2>
            <div class="header_nav">
                @yield('header_nav')
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>
    <footer class="footer">
        ©Atte,inc
    </footer>
</body>
</html>