@extends('customer.layouts.app')

@section('head-tag')
    <title>Ticket</title>

    <style>
        .profile-sidebar-menu {
            padding-top: 12px;
        }

        .profile-menu-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 13px 5px;
            border: 0;
            border-bottom: 1px solid #f0f0f0;
            background: transparent;
            color: #555;
            font-size: 14px;
            text-align: left;
            cursor: pointer;
            transition: all .2s ease;
        }

        .profile-menu-item:hover,
        .profile-menu-item.active {
            color: #717fe0;
            padding-left: 10px;
        }

        .profile-menu-item.active {
            font-weight: 600;
        }

        .profile-logout:hover {
            color: #d9534f;
        }

        ///////////////////////////////////////////

        .ticket-page-card {
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 3px 18px rgba(0, 0, 0, .035);
        }

        .ticket-page-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            padding: 25px 30px;
            border-bottom: 1px solid #efefef;
        }

        .ticket-page-title {
            font-size: 22px;
            font-weight: 700;
            color: #222;
            margin: 0 0 7px;
        }

        .ticket-page-description {
            margin: 0;
            color: #888;
            font-size: 14px;
        }

        .ticket-header-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f7ff;
            border-radius: 50%;
            color: #5779df;
            font-size: 23px;
        }

        .ticket-create-btn {
            height: 36px;
            min-width: 136px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 10px;
            background: #717fe0;
            color: #fff !important;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 600;
            transition: all .25s ease;
        }

        .ticket-create-btn:hover {
            background: #222;
            transform: translateY(-1px);
        }

        .ticket-list-head,
        .ticket-row {
            display: grid;
            grid-template-columns: 65px minmax(190px, 2fr) minmax(120px, 1fr) 120px 115px 105px;
            gap: 15px;
            align-items: center;
        }

        .ticket-list-head {
            padding: 16px 30px;
            background: #fafafa;
            border-bottom: 1px solid #ededed;
            color: #777;
            font-size: 13px;
            font-weight: 700;
        }

        .ticket-row {
            padding: 19px 30px;
            border-bottom: 1px solid #f0f0f0;
            transition: background-color .2s ease;
        }

        .ticket-row:last-child {
            border-bottom: 0;
        }

        .ticket-row:hover {
            background: #fcfcff;
        }

        .ticket-number {
            color: #888;
            font-weight: 700;
            font-size: 14px;
        }

        .ticket-subject {
            display: block;
            color: #333;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 5px;
            transition: color .2s ease;
        }

        .ticket-subject:hover {
            color: #717fe0;
        }

        .ticket-preview {
            display: block;
            max-width: 270px;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
            color: #999;
            font-size: 13px;
        }

        .ticket-category {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #666;
            font-size: 13px;
        }

        .ticket-category i {
            color: #8d97a8;
            font-size: 15px;
        }

        .ticket-date {
            color: #777;
            font-size: 13px;
            white-space: nowrap;
        }

        .ticket-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 78px;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .ticket-status-0 {
            color: #198754;
            background: #e8f8ef;
        }

        .ticket-status-1 {
            color: #dc3545;
            background: #f0f2f5;
        }

        .ticket-details-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 36px;
            border-radius: 5px;
            color: #fff !important;
            background: #20a8d8;
            font-size: 17px;
            transition: all .25s ease;
        }

        .ticket-details-btn:hover {
            background: #1688b3;
            transform: translateY(-1px);
        }

        .ticket-status-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 36px;
            border-radius: 5px;
            color: #fff !important;
            font-size: 17px;
            transition: all .25s ease;
        }

        .ticket-status-btn:hover {
            transform: translateY(-1px);
        }

        .tickets-empty-state {
            padding: 70px 25px;
        }

        .tickets-empty-icon {
            width: 75px;
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border-radius: 50%;
            background: #f3f5fa;
            color: #a8b0bf;
            font-size: 34px;
        }

        @media (max-width: 991px) {
            .ticket-list-head {
                display: none;
            }

            .ticket-row {
                grid-template-columns: 42px 1fr auto;
                gap: 13px;
                padding: 18px 20px;
            }

            .ticket-subject-cell {
                grid-column: 2 / 3;
            }

            .ticket-category-cell,
            .ticket-date-cell {
                grid-column: 2 / 3;
            }

            .ticket-status-cell {
                grid-column: 2 / 3;
            }

            .ticket-priority-cell {
                grid-column: 2 / 3;
            }

            .ticket-action-cell {
                grid-column: 3 / 4;
                grid-row: 1 / 6;
                align-self: center;
            }

            .ticket-category-cell::before {
                content: 'Category: ';
                color: #999;
                font-size: 13px;
            }

            .ticket-date-cell::before {
                content: 'Created: ';
                color: #999;
                font-size: 13px;
            }

            .ticket-status-cell::before {
                content: 'Status: ';
                color: #999;
                font-size: 13px;
                margin-right: 5px;
            }

            .ticket-priority-cell::before {
                content: 'Priority: ';
                color: #999;
                font-size: 13px;
                margin-right: 5px;
            }
        }

        @media (max-width: 575px) {
            .ticket-page-head {
                align-items: flex-start;
                flex-wrap: wrap;
                padding: 20px;
            }

            .ticket-page-title {
                font-size: 19px;
            }

            .ticket-header-icon {
                display: none;
            }

            .ticket-create-btn {
                width: 100%;
            }

            .ticket-row {
                padding: 17px 15px;
            }
        }
    </style>
@endsection

@section('content')
    @include('admin.alerts.toast.success')
    @include('admin.alerts.toast.error')

    <section class="bg0 p-t-85 p-b-85">
        <div class="container">

            {{-- Breadcrumb --}}
            <div class="flex-w flex-r-m p-b-35">
                <a href="{{ route('customer.home') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    Home
                </a>

                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>

                <a href="{{ route('customer.profile.profile') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    My Account
                </a>

                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>

                <span class="stext-109 cl4">
                    My Tickets
                </span>
            </div>

            <div class="row">
                {{-- Sidebar --}}
                @include('customer.layouts.partials.profile-sidebar')

                {{-- Main Content --}}
                <div class="col-lg-9 col-md-8">
                    <div class="ticket-page-card">

                        {{-- Header --}}
                        <div class="ticket-page-head">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h1 class="ticket-page-title">
                                        My Tickets
                                    </h1>

                                    <p class="ticket-page-description">
                                        View your support requests and follow their status.
                                    </p>
                                </div>
                            </div>

                            <a href="{{ route('customer.profile.ticket.create') }}" class="ticket-create-btn">
                                <i class="fa fa-plus"></i>
                                Submit New Ticket
                            </a>
                        </div>

                        <div class="ticket-list-head">
                            <div>#</div>
                            <div>Ticket</div>
                            <div>Category</div>
                            <div>Status</div>
                            <div>Priority</div>
                            <div class="text-center">View</div>
                        </div>

                        {{-- Tickets --}}
                        @forelse ($tickets as $ticket)
                            <div class="ticket-row">
                                {{-- Number --}}
                                <div class="ticket-number">
                                    #{{ $ticket->id }}
                                </div>

                                {{-- Subject + Message preview --}}
                                <div class="ticket-subject-cell">
                                    <a href="{{ route('customer.profile.ticket.show', $ticket) }}" class="ticket-subject">
                                        {{ $ticket->subject }}
                                    </a>

                                    <span class="ticket-preview">
                                        {{ Str::limit($ticket->description, 65) }}
                                    </span>
                                </div>

                                {{-- Category --}}
                                <div class="ticket-category-cell">
                                    <span class="ticket-category">
                                        <i class="zmdi zmdi-label"></i>
                                        {{ $ticket->category->name ?? 'Uncategorized' }}
                                    </span>
                                </div>

                                {{-- Status --}}
                                <div class="ticket-status-cell">
                                    <span class="ticket-badge ticket-status-{{ $ticket->status }}">
                                        @switch($ticket->status)
                                            @case(0)
                                                Open
                                            @break

                                            @case(1)
                                                Closed
                                            @break
                                        @endswitch
                                    </span>
                                </div>

                                {{-- Priority --}}
                                @if ($ticket->priority)
                                    <div class="ticket-priority-cell">
                                        <span class="ticket-badge">
                                            {{ $ticket->priority->name }}
                                        </span>
                                    </div>
                                @else
                                    <span class="ticket-no-priority">-</span>
                                @endif

                                <div class="ticket-action-cell text-center">
                                    {{-- Details --}}

                                    <a href="{{ route('customer.profile.ticket.show', $ticket) }}"
                                        class="ticket-details-btn" title="View Ticket">
                                        <i class="zmdi zmdi-eye"></i>
                                    </a>
                                    {{-- change status --}}
                                    <a href="{{ route('customer.profile.ticket.change', $ticket) }}"
                                        class="ticket-status-btn {{ $ticket->status === 0 ? 'bg-danger' : 'bg-success' }}"
                                        title="{{ $ticket->status === 0 ? 'Close' : 'Open' }} Ticket">
                                        <i class="zmdi zmdi-{{ $ticket->status === 0 ? 'lock' : 'lock-open' }}"></i>
                                    </a>
                                </div>
                            </div>
                            @empty
                                <div class="tickets-empty-state text-center">
                                    <div class="tickets-empty-icon">
                                        <i class="zmdi zmdi-help-outline"></i>
                                    </div>

                                    <h4 class="mtext-105 cl2 p-t-20 p-b-10">
                                        No tickets found
                                    </h4>

                                    <p class="stext-107 cl6 p-b-30">
                                        If you need help, submit a support ticket and our team will get back to you.
                                    </p>

                                    <a href="{{ route('customer.profile.ticket.create') }}" class="ticket-create-btn">
                                        <i class="fa fa-plus"></i>
                                        Submit New Ticket
                                    </a>
                                </div>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-4">
                            {{ $tickets->onEachSide(1)->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection
