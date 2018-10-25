<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="<?= csrf_token() ?>">

        <title>@yield('title', config('app.name'))</title>
    </head>

    <body>
        <div class="container">
            @yield('content')
        </div>

        <link media="all" type="text/css" rel="stylesheet" href="{{ mix('/assets/styles/app.css') }}">

        <script src="{{ mix('/assets/js/main.js') }}"></script>

        @if (config('app.env') == 'local' && !request()->isSecure())
            <script src="http://localhost:35729/livereload.js"></script>
        @endif
    </body>
</html>
