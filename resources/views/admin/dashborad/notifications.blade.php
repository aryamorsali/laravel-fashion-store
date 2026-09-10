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
            <form action="{{ route('admin.notifications.mark-as-read') }}" class="mark-all" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-link p-0 mark-read">
                    Mark all as read
                </button>
            </form>
        </div>

        {{-- کارت اصلی --}}
        <div class="card shadow-sm border-0 notif-page">
            <div class="card-body p-0">
                @foreach ($notifications as $notification)
                    {{-- @dd($notification->data) --}}
                    <a href="{{ $notification->data['url'] }}" class="page-notif">
                        <span
                            class="notification-icon  @switch($notification->data['event'])
                                         @case('new_order')
                                             bg-primary
                                             @break
                                                @case('low_stock')
                                             bg-warning
                                             @break
                                                @case('payment_failed')
                                             bg-danger
                                             @break
                                                @case('new_ticket')
                                             bg-info
                                             @break
                                                   @case('new_user')
                                             bg-secondary
                                             @break
                                                  @case('new_product_comment')
                                             bg-success
                                             @break
                                               @case('new_post_comment')
                                             bg-dark
                                             @break
                                             @default
                                     @endswitch">
                            @php
                                $icon = match ($notification->data['event'] ?? '') {
                                    'new_order' => 'shopping-cart',
                                    'low_stock' => 'box-open',
                                    'payment_failed' => 'exclamation-triangle',
                                    'new_ticket' => 'headset',
                                    'new_user' => 'user-plus',
                                    'new_post_comment' => 'comment-dots',
                                    'new_product_comment' => 'comment-alt',
                                };
                            @endphp

                            <i class="fas fa-{{ $icon }}"></i></span>
                        <div class="notif-body">
                            <p class="notif-title">{!! $notification->data['message'] !!}</p>
                            <p class="notif-sub">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if (!$notification->read_at)
                            <span class="dot"></span>
                        @endif

                    </a>
                @endforeach



            </div>

            {{-- فوتر --}}
            <div class="notif-page-foot">
                {{-- <span>Showing 5 of 24</span>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#!">Prev</a></li>
                        <li class="page-item active"><a class="page-link" href="#!">1</a></li>
                        <li class="page-item"><a class="page-link" href="#!">2</a></li>
                        <li class="page-item"><a class="page-link" href="#!">Next</a></li>
                    </ul>
                </nav> --}}

                <div class="d-flex justify-content-center mt-4">
                    {{ $notifications->onEachSide(1)->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>

    </div>
@endsection
