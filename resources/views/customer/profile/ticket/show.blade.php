@extends('customer.layouts.app')

@section('head-tag')
    <title>Ticket Detail</title>

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



        .ticket-view-wrapper {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #edf2f7;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            padding: 30px;
        }

        /* Header Section */
        .ticket-view-header {
            padding-bottom: 24px;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 25px;
        }

        .ticket-header-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .ticket-badge-id {
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 3px 10px;
            border-radius: 6px;
        }

        .ticket-badge {
            font-size: 12px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
        }

        .ticket-badge-open {
            background-color: #ecfdf5;
            color: #059669;
        }

        .ticket-badge-closed {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .ticket-badge-priority {
            background-color: #eff6ff;
            color: #2563eb;
        }

        .ticket-badge-category {
            background-color: #f3f4f6;
            color: #4b5563;
        }

        .ticket-main-title {
            font-size: 20px;
            font-weight: 700;
            color: #222222;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .ticket-meta-info {
            font-size: 13px;
            color: #888888;
        }

        .btn-back-ticket {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            color: #555555;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.2s ease;
            text-decoration: none !important;
        }

        .btn-back-ticket:hover {
            background: #222222;
            color: #ffffff;
            border-color: #222222;
        }

        /* Conversation Flow */
        .ticket-conversation-flow {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 35px;
        }

        .chat-message-card {
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #eef2f6;
            transition: box-shadow 0.2s ease;
        }

        /* User Message Box (Minimal / Light Slate) */
        .chat-message-card.user-message {
            background: #ffffff;
            border-left: 4px solid #717fe0;
            /* Coza Primary Color */
        }

        /* Admin Message Box (Coza Themed soft accent) */
        .chat-message-card.admin-message {
            background: #fcfdfe;
            border-left: 4px solid #222222;
        }

        .chat-message-header {
            display: flex;
            align-items: center;
            margin-bottom: 14px;
        }

        .chat-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #eef2ff;
            color: #717fe0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-right: 12px;
        }

        .chat-user-avatar.admin-avatar {
            background: #222222;
            color: #ffffff;
        }

        .chat-user-details {
            display: flex;
            flex-direction: column;
        }

        .chat-username {
            font-size: 14px;
            font-weight: 700;
            color: #222222;
        }

        .chat-user-role {
            font-size: 11px;
            font-weight: 600;
            margin-top: 2px;
            width: fit-content;
        }

        .user-role-badge {
            color: #717fe0;
        }

        .admin-role-badge {
            color: #e11d48;
        }

        .chat-timestamp {
            margin-left: auto;
            font-size: 12px;
            color: #94a3b8;
        }

        .chat-message-body p {
            font-size: 14px;
            line-height: 1.7;
            color: #475569;
            margin-bottom: 0;
        }

        .chat-message-file {
            width: 30%;
            height: 30%;
            margin-top: 2rem;
        }



        /* Reply Form Box */
        .ticket-reply-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
        }

        .reply-card-title {
            font-size: 16px;
            font-weight: 700;
            color: #222222;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
        }

        .ticket-reply-textarea {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 14px;
            font-size: 14px;
            background: #ffffff;
            resize: vertical;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .ticket-reply-textarea:focus {
            border-color: #717fe0;
            box-shadow: 0 0 0 3px rgba(113, 127, 224, 0.15);
            outline: none;
        }

        .ticket-reply-hint {
            font-size: 12px;
            color: #64748b;
        }

        .ticket-submit-reply-btn {
            background: #717fe0;
            color: #ffffff;
            border: none;
            padding: 10px 24px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .ticket-submit-reply-btn:hover {
            background: #222222;
            transform: translateY(-1px);
        }

        /* Closed Ticket Box */
        .ticket-closed-notice {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .closed-notice-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #ffe4e6;
            color: #e11d48;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .closed-notice-text h5 {
            font-size: 15px;
            font-weight: 700;
            color: #9f1239;
            margin-bottom: 4px;
        }

        .closed-notice-text p {
            font-size: 13px;
            color: #be123c;
            margin-bottom: 0;
        }
    </style>
@endsection
@section('content')
    @include('admin.alerts.toast.success')
    @include('admin.alerts.toast.error')

    <section class="bg0 p-t-65 p-b-85">
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

                <a href="{{ route('customer.profile.ticket.index') }}" class="stext-109 cl8 hov-cl1 trans-04">
                    My Tickets
                </a>
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>

                <span class="stext-109 cl4">
                    Ticket #{{ $ticket->id }}
                </span>
            </div>

            <div class="row">
                {{-- Sidebar --}}
                @include('customer.layouts.partials.profile-sidebar')

                {{-- Main Ticket Detail Content --}}
                <div class="col-lg-9 col-md-8">
                    <div class="ticket-view-wrapper">

                        {{-- Ticket Header Card --}}
                        <div class="ticket-view-header">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <div class="ticket-header-badges">
                                        <span class="ticket-badge-id">#{{ $ticket->id }}</span>

                                        {{-- Status Badge --}}
                                        <span
                                            class="ticket-badge {{ $ticket->status === 0 ? 'ticket-badge-open' : 'ticket-badge-closed' }}">
                                            <i
                                                class="zmdi {{ $ticket->status === 0 ? 'zmdi-dot-circle' : 'zmdi-check-circle' }} m-r-4"></i>
                                            {{ $ticket->status === 0 ? 'Open' : 'Closed' }}
                                        </span>

                                        {{-- Priority Badge --}}
                                        @if ($ticket->priority)
                                            <span class="ticket-badge ticket-badge-priority">
                                                <i class="zmdi zmdi-flag m-r-4"></i>
                                                {{ $ticket->priority->name }}
                                            </span>
                                        @endif

                                        {{-- Category Badge --}}
                                        @if ($ticket->category)
                                            <span class="ticket-badge ticket-badge-category">
                                                <i class="zmdi zmdi-folder-outline m-r-4"></i>
                                                {{ $ticket->category->name }}
                                            </span>
                                        @endif
                                    </div>

                                    <h2 class="ticket-main-title">
                                        {{ $ticket->subject }}
                                    </h2>

                                    <div class="ticket-meta-info">
                                        <span><i class="zmdi zmdi-calendar m-r-5"></i>Created on
                                            {{ $ticket->created_at->format('M d, Y - H:i') }}</span>
                                        <span class="m-l-15"><i class="zmdi zmdi-time-restore m-r-5"></i>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="ticket-view-actions">
                                    <a href="{{ route('customer.profile.ticket.index') }}" class="btn-back-ticket">
                                        <i class="zmdi zmdi-arrow-left m-r-6"></i> Back to List
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Conversation Timeline --}}
                        <div class="ticket-conversation-flow">

                            {{-- Original Ticket Message (Customer) --}}
                            <div class="chat-message-card user-message">
                                <div class="chat-message-header">
                                    <div class="chat-user-avatar">
                                        <i class="zmdi zmdi-account"></i>
                                    </div>
                                    <div class="chat-user-details">
                                        <span class="chat-username">{{ $ticket->user->full_name ?? 'You' }}</span>
                                        <span class="chat-user-role user-role-badge">Author</span>
                                    </div>
                                    <span class="chat-timestamp">
                                        {{ $ticket->created_at->format('M d, Y - H:i') }}
                                    </span>
                                </div>
                                <div class="chat-message-body">
                                    <p>{{ $ticket->description }}</p>
                                </div>

                                @if ($ticket->file)
                                    <div>
                                        <img src="{{ asset($ticket->file->file_path) }}" class="chat-message-file" alt="{{ $ticket->subject }}">
                                    </div>
                                @endif

                            </div>

                            {{-- Ticket Replies Loop --}}
                            @if (isset($ticket->children) && $ticket->children->count() > 0)
                                @foreach ($ticket->children as $reply)
                                    @php
                                        $isAdmin = $reply->user_id !== $ticket->user_id;
                                    @endphp

                                    <div class="chat-message-card {{ $isAdmin ? 'admin-message' : 'user-message' }}">
                                        <div class="chat-message-header">

                                            <div class="chat-user-avatar {{ $isAdmin ? 'admin-avatar' : '' }}">
                                                <i class="zmdi {{ $isAdmin ? 'zmdi-headset-mic' : 'zmdi-account' }}"></i>
                                            </div>

                                            <div class="chat-user-details">
                                                <span class="chat-username">
                                                    Support Team
                                                </span>
                                                <span
                                                    class="chat-user-role {{ $isAdmin ? 'admin-role-badge' : 'user-role-badge' }}">
                                                    {{ $isAdmin ? 'Support Agent' : $ticket->user->full_name ?? 'You' }}
                                                </span>
                                            </div>

                                            <span class="chat-timestamp">
                                                {{ $reply->created_at->format('M d, Y - H:i') }}
                                            </span>

                                        </div>

                                        <div class="chat-message-body">
                                            <p>{{ $reply->description }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>

                        {{-- Reply Section / Closed State Notice --}}
                        @if ($ticket->status === 0)
                            <div class="ticket-reply-card">
                                <h4 class="reply-card-title">
                                    <i class="zmdi zmdi-comment-edit m-r-8"></i> Leave a Reply
                                </h4>

                                <form action="{{ route('customer.profile.ticket.answer', $ticket) }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <textarea name="description" rows="5" class="form-control ticket-reply-textarea"
                                            placeholder="Type your response or provide more details...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <span class="ticket-reply-hint">
                                            <i class="zmdi zmdi-info-outline"></i> Our support team will answer as soon as
                                            possible.
                                        </span>
                                        <button type="submit" class="ticket-submit-reply-btn">
                                            <i class="zmdi zmdi-mail-send m-r-6"></i> Submit Reply
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            {{-- Notice when ticket is closed --}}
                            <div class="ticket-closed-notice">
                                <div class="closed-notice-icon">
                                    <i class="zmdi zmdi-lock-outline"></i>
                                </div>
                                <div class="closed-notice-text">
                                    <h5>This ticket has been closed</h5>
                                    <p>You cannot submit new replies to this ticket. If you still need help, please submit a
                                        new ticket.</p>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
