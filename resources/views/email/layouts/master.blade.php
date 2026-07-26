<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>

    @include('email.layouts.head-tag')
    @yield('head-tag')

</head>
<body>

    {{-- start header --}}
    @include('email.layouts.header')
    {{-- end header --}}

    {{-- start main one col --}}
    <main id="main-body-one-col" class="main-body">
        @yield('content')
    </main>
    {{-- end main one col --}}

    {{-- start footer --}}
    @include('email.layouts.footer')
    {{-- end footer --}}

</body>
</html>
