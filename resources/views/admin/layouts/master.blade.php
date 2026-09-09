<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.layouts.head-tag')
    @yield('head-tag')

    <style>
        .chart-container {
            position: relative;
            height: 240px;
            width: 100%;
        }

        .chart-container canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .notif-count {
            position: absolute;
            top: 3px;
            right: 2px;
            font-size: 0.6rem;
            font-weight: 700;
            color: #fff;
            background: #e74a3b;
            border-radius: 50%;
            width: 15px;
            height: 15px;
            line-height: 15px;
            text-align: center;
        }

        .notif-menu {
            width: 320px;
            padding: 0;
            font-size: 0.875rem;
            overflow: hidden;
        }

        .notif-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            font-weight: 600;
            color: #21252b;
            border-bottom: 1px solid #eef0f3;
        }

        .notif-head .mark-read {
            font-size: 0.75rem;
            font-weight: 400;
            color: #0d6efd;
            text-decoration: none;
        }

        .notif-head .mark-read:hover {
            text-decoration: underline;
        }

        .notif-item {
            display: block;
            padding: 0.7rem 1rem;
            text-decoration: none;
            border-bottom: 1px solid #f4f5f7;
        }

        .notif-item:hover {
            background: #f8f9fa;
        }

        .notif-item .notif-title {
            margin: 0;
            color: #21252b;
            font-weight: 400;
            line-height: 1.5;
        }


        /* نقطه خوانده‌نشده */
        .notif-item .dot {
            display: inline-block;
            width: 7px;
            height: 7px;
            margin-left: 6px;
            border-radius: 50%;
            background: #0d6efd;
            vertical-align: middle;
        }

        .notif-foot {
            background: #f8f9fa;
            text-align: center;
        }

        .notif-foot a {
            display: block;
            padding: 0.6rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #0d6efd;
            text-decoration: none;
        }

        .notif-foot a:hover {
            background: #eef2f7;
        }

        .notification-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 0.8rem;
        }

        .notif-item {
            display: flex !important;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.65rem 1rem;
        }

        .notif-body {
            flex: 1;
            min-width: 0;
        }

        .notif-title {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            margin: 0;
            font-size: 0.85rem;
            color: #343a40;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notif-sub {
            margin: 0.1rem 0 0;
            font-size: 0.72rem;
            color: #adb5bd;
        }

        .notif-item .notification-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            color: #fff;
            flex-shrink: 0;
        }

        .notif-title .dot {
            width: 8px;
            height: 8px;
            background: #2f6dfa;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
            margin-left: auto;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    @include('admin.layouts.header')

    <div id="layoutSidenav">
        @include('admin.layouts.sidebar')
        <div id="layoutSidenav_content">
            <main>
                @yield('content')
            </main>
            @include('admin.layouts.footer')
        </div>
    </div>

    @include('admin.layouts.script')
    @yield('script')

    <section class="toast-wrapper flex-row-reverse">
        @include('admin.alerts.toast.success')
        @include('admin.alerts.toast.error')
    </section>


    @include('admin.alerts.sweetalert.success')
    @include('admin.alerts.sweetalert.error')

</body>

</html>
