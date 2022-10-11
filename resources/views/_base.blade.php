<!DOCTYPE html>
<html lang="ru" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @yield('meta')

    <title>Остатки былой роскоши</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png"/>
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png"/>
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png"/>
    <link rel="manifest" href="/site.webmanifest"/>

    <style>
        body {
            min-height: 100%;
        }

        :root {
            --bs-font-sans-serif: Arial, sans-serif;
            --bs-body-font-size: 14px;
        }

        .table-sm > :not(caption) > * > * {
            padding: .1rem .25rem;
        }

        td.asset_type_1 {
            background-color: #6FA8DC !important;
        }

        td.asset_type_2 {
            background-color: #6AA84F !important;
        }

        td.asset_type_3 {
            background-color: #C4B2E6 !important;
        }

        td.asset_type_4 {
            background-color: #FFD966 !important;
        }

        td.editable {
            cursor: cell;
        }

        td.editable:hover {
            background-color: #fff8d6;
        }

    </style>
</head>

<body class="d-flex flex-column">

<main class="flex-grow-1 bg-light">
    <div class="container py-4">
        @yield('content')
    </div>
</main>

<footer class="mt-auto bg-dark text-muted text-center py-4">
    &copy; {{ date('Y') }}
    <a href="https://twitter.com/yuraigle" class="text-reset text-decoration-none" target="_blank">yuraigle</a>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa"
        crossorigin="anonymous"></script>

@yield('inline_scripts')

</body>

</html>
