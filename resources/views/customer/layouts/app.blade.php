<!DOCTYPE html>
<html lang="en">

<head>
    @include('customer.layouts.head-tag')
    @yield('head-tag')
    <link rel="stylesheet" type="text/css" href="{{ asset('customer-assets/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('customer-assets/css/main.css') }}">
</head>

<body class="animsition">

    @include('customer.layouts.header')


    @yield('content')


    @include('customer.layouts.footer')


    <!-- Back to top -->
    <div class="btn-back-to-top" id="myBtn">
        <span class="symbol-btn-back-to-top">
            <i class="zmdi zmdi-chevron-up"></i>
        </span>
    </div>


    @include('customer.layouts.script')
    @yield('script')
</body>

</html>
