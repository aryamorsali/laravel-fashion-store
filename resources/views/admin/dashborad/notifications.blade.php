@extends('admin.layouts.master')

@section('head-tag')
    <title>Notifications</title>
    <style>
        /* ===== Notifications Page ===== */
        .notif-page-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .notif-page-head h5 {
            font-weight: 700;
            color: #343a40;
            margin: 0;
        }

        .notif-page-head .mark-all {
            font-size: 0.8rem;
            color: #2f6dfa;
            text-decoration: none;
            font-weight: 600;
        }

        .notif-page-head .mark-all:hover {
            text-decoration: underline;
        }

        /* آیتم */
        .notif-page .page-notif {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #f0f2f5;
            text-decoration: none;
            transition: background 0.15s ease;
        }

        .notif-page .page-notif:last-of-type {
            border-bottom: none;
        }

        .notif-page .page-notif:hover {
            background: #f8f9fc;
        }

        /* آیکون — دایره ثابت، هیچ flex-grow ای نداشته باشد */
        .notif-page .notification-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1rem;
            flex: 0 0 42px;
            /* ← کلید رفع باگ: نه رشد، نه جمع‌شدگی */
            overflow: hidden;
        }

        /* ستون متن */
        .notif-page .notif-body {
            flex: 1;
            min-width: 0;
        }

        .notif-page .notif-title {
            margin: 0;
            font-size: 0.92rem;
            color: #343a40;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notif-page .notif-sub {
            margin: 0.2rem 0 0;
            font-size: 0.75rem;
            color: #adb5bd;
        }

        /* نقطه خوانده‌نشده */
        .notif-page .dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #2f6dfa;
            flex-shrink: 0;
            margin-right: 0.5rem;
        }

        .notif-page .page-notif.is-read .dot {
            visibility: hidden;
        }

        .notif-page .page-notif.is-read .notif-title {
            color: #868e96;
            font-weight: 400;
        }

        /* فوتر */
        .notif-page-foot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.25rem;
            border-top: 1px solid #f0f2f5;
            font-size: 0.78rem;
            color: #adb5bd;
        }

        .notif-page-foot .page-link {
            border: none;
            color: #6c757d;
        }

        .notif-page-foot .page-item.active .page-link {
            background: #2f6dfa;
            color: #fff;
            border-radius: 0.35rem;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid px-3">

        {{-- سربرگ --}}
        <div class="notif-page-head mt-5">
            <h3><i class="far fa-bell px-2"></i>Notifications</h3>
            <a href="#!" class="mark-all">Mark all as read</a>
        </div>

        {{-- کارت اصلی --}}
        <div class="card shadow-sm border-0 notif-page">
            <div class="card-body p-0">

                <a href="#!" class="page-notif">
                    <span class="notification-icon bg-primary"><i class="fas fa-shopping-cart"></i></span>
                    <div class="notif-body">
                        <p class="notif-title">New order <b>#1024</b> received</p>
                        <p class="notif-sub">5 min ago</p>
                    </div>
                    <span class="dot"></span>
                </a>

                <a href="#!" class="page-notif">
                    <span class="notification-icon bg-danger"><i class="fas fa-exclamation-triangle"></i></span>
                    <div class="notif-body">
                        <p class="notif-title">Payment failed for order <b>#1023</b></p>
                        <p class="notif-sub">32 min ago</p>
                    </div>
                    <span class="dot"></span>
                </a>

                <a href="#!" class="page-notif">
                    <span class="notification-icon bg-warning"><i class="fas fa-box-open"></i></span>
                    <div class="notif-body">
                        <p class="notif-title">Low stock: <b>Black T-Shirt — L</b></p>
                        <p class="notif-sub">2 hours ago</p>
                    </div>
                    <span class="dot"></span>
                </a>

                <a href="#!" class="page-notif">
                    <span class="notification-icon bg-info"><i class="fas fa-headset"></i></span>
                    <div class="notif-body">
                        <p class="notif-title">New support ticket <b>#88</b></p>
                        <p class="notif-sub">Yesterday</p>
                    </div>
                    <span class="dot"></span>
                </a>

                <a href="#!" class="page-notif is-read">
                    <span class="notification-icon bg-secondary"><i class="fas fa-user-plus"></i></span>
                    <div class="notif-body">
                        <p class="notif-title">New user registered: <b>David Beckham</b></p>
                        <p class="notif-sub">Yesterday</p>
                    </div>
                </a>

            </div>

            {{-- فوتر --}}
            <div class="notif-page-foot">
                <span>Showing 5 of 24</span>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#!">Prev</a></li>
                        <li class="page-item active"><a class="page-link" href="#!">1</a></li>
                        <li class="page-item"><a class="page-link" href="#!">2</a></li>
                        <li class="page-item"><a class="page-link" href="#!">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>

    </div>
@endsection
