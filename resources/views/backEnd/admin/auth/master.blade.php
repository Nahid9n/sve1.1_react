<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Sign in - {{ config('app.name') }}</title>
    <!-- CSS files -->
    <link href="{{ asset('backEnd/assets/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backEnd/assets/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backEnd/assets/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backEnd/assets/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('backEnd/assets/css/demo.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('backEnd/assets/css/toastr.min.css') }}">
</head>

<body class=" border-top-wide border-primary d-flex flex-column">
    <div class="page page-center">
        <div class="container-tight py-4">
            @yield('content')
        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="{{ asset('backEnd/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('backEnd/assets/js/tabler.min.js') }}" defer></script>
    <script src="{{ asset('backEnd/assets/js/demo.min.js') }}" defer></script>
    <script src="{{ asset('backEnd/assets/js/toastr.min.js') }}"></script>


</body>

</html>
