<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <link rel="shortcut icon" href="/images/favicon.png" type="image/png">
    <title>@yield('title', config('app.name'))</title>
</head>

<body>
@yield('content')

<link media="all" type="text/css" rel="stylesheet" href="{{ mix('/assets/styles/app.css') }}">

<script src="{{ mix('/assets/js/main.js') }}"></script>

</body>
</html>
