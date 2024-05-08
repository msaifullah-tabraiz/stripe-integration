<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/checkout.css')}}" />
    <title>Stripe</title>
</head>

<body>
    @yield('content')
    <script src="//js.stripe.com/v3/"></script>
    <script src="//cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/checkout.js') }}" defer></script>
</body>

</html>