<!DOCTYPE html>
<html lang="en">

<head>
    @include('customer.layouts.head-tag')
    @yield('head-tag')
</head>

<body>


    @yield('content')

    @include('customer.layouts.script')
    @yield('script')
</body>

</html>
