@extends('customer.layouts.app')

@section('head-tag')
    <title>Blog</title>
    <style>
        .custom-toast {
            position: fixed;
            top: 110px;
            right: 20px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff;
            padding: 14px 18px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            z-index: 9999;
            animation: toastIn .4s ease;
        }

        .custom-toast .close-btn {
            margin-left: 10px;
            cursor: pointer;
            font-size: 18px;
            opacity: .8;
        }

        .custom-toast .close-btn:hover {
            opacity: 1;
        }

        @keyframes toastIn {
            from {
                transform: translateX(40px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast-link {
            color: #fff;
            font-weight: 600;
            text-decoration: underline;
        }

        .toast-link:hover {
            color: #d1fae5;
        }
    </style>
@endsection

@section('content')
    @include('admin.alerts.toast.success')
    @include('admin.alerts.toast.error')

    <!-- breadcrumb -->
    <div class="container">
        <div class="bread-crumb flex-w p-l-25 p-r-15 p-t-30 p-lr-0-lg">
            <a href="{{ route('customer.home') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Home
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <a href="{{ route('customer.content.blogs') }}" class="stext-109 cl8 hov-cl1 trans-04">
                Blog
                <i class="fa fa-angle-right m-l-9 m-r-10" aria-hidden="true"></i>
            </a>

            <span class="stext-109 cl4">
                {{ $post->title }}
            </span>
        </div>
    </div>


    <!-- Content page -->
    <section class="bg0 p-t-52 p-b-20">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-9 p-b-80">
                    <div class="p-r-45 p-r-0-lg">
                        <!--  -->
                        <div class="wrap-pic-w how-pos5-parent">
                            <img src="{{ asset($post->image['blogArray'][$post->image['currentImage']]) }}"
                                alt="{{ $post->title }}">

                            <div class="flex-col-c-m size-123 bg9 how-pos5">
                                <span class="ltext-107 cl2 txt-center">
                                    {{ $post->created_at->format('d') }}
                                </span>

                                <span class="stext-109 cl3 txt-center">
                                    {{ $post->created_at->format('M Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="p-t-32">
                            <span class="flex-w flex-m stext-111 cl2 p-b-19">
                                <span>
                                    <span class="cl4">By</span> {{ $post->user->full_name ?? '-' }}
                                    <span class="cl12 m-l-4 m-r-6">|</span>
                                </span>

                                <span>
                                    {{ $post->created_at->format('d M Y') }}
                                    <span class="cl12 m-l-4 m-r-6">|</span>
                                </span>
                                @if ($post->tags && $post->tags->count() > 0)
                                    <span>
                                        @foreach ($post->tags as $tag)
                                            {{ $tag->name }},
                                        @endforeach

                                        <span class="cl12 m-l-4 m-r-6">|</span>
                                    </span>
                                @endif

                                <span>
                                    {{ $post->comments->count() }} Comments
                                </span>
                            </span>

                            <h4 class="ltext-109 cl2 p-b-28">
                                {{ $post->title }}
                            </h4>

                            <p class="stext-117 cl6 p-b-26">
                                {!! $post->body !!}
                            </p>

                        </div>

                        @if ($post->tags && $post->tags->count() > 0)
                            <div class="flex-w flex-t p-t-16">

                                <span class="size-216 stext-116 cl8 p-t-4">
                                    Tags
                                </span>


                                <div class="flex-w size-217">
                                    @foreach ($post->tags as $tag)
                                        <a href="{{ route('customer.market.shop', ['tag' => $tag->name]) }}"
                                            class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                            {{ $tag->name }}
                                        </a>
                                    @endforeach

                                </div>
                            </div>
                        @endif


                        <div id="comments-container">
                            @foreach ($approvedComments as $comment)
                                <div class="flex-w flex-t p-b-40 p-t-50">

                                    {{-- Parent Avatar --}}
                                    <div class="wrap-pic-s size-109 bor0 of-hidden m-r-18 m-t-6">
                                        <img src="{{ asset($comment->user->profile_photo_path ?? 'images/users/default-avatar.png') }}"
                                            alt="avatar">
                                    </div>

                                    {{-- Parent Content --}}
                                    <div class="size-207">

                                        {{-- Name + Rating --}}
                                        <div class="flex-w flex-sb-m p-b-17">
                                            <span class="mtext-107 cl2 p-r-20">
                                                {{ $comment->user->full_name ?? 'ناشناس' }}
                                            </span>

                                        </div>

                                        {{-- Comment Body --}}
                                        <p class="stext-102 cl6">
                                            {{ $comment->body }}
                                        </p>


                                        {{-- Replies --}}
                                        @foreach ($comment->children as $childComment)
                                            @if ($childComment->approved)
                                                <div class="flex-w flex-t p-t-30" style="padding-left: 50px;">

                                                    {{-- Reply Avatar --}}
                                                    <div class="wrap-pic-s size-108 bor0 of-hidden m-r-18 m-t-6">
                                                        <img src="{{ asset($childComment->user->profile_photo_path ?? 'images/users/default-avatar.png') }}"
                                                            alt="avatar">
                                                    </div>

                                                    {{-- Reply Content --}}
                                                    <div class="size-207">
                                                        <span class="mtext-107 cl2 p-r-20">
                                                            {{ $childComment->user->full_name ?? 'ناشناس' }}
                                                        </span>

                                                        <p class="stext-102 cl6 p-t-10">
                                                            {{ $childComment->body }}
                                                        </p>
                                                    </div>

                                                </div>
                                            @endif
                                        @endforeach

                                    </div>

                                </div>
                            @endforeach
                        </div>
                        @if ($approvedComments->hasMorePages())
                            <div class="text-center p-b-30">
                                <button id="load-more-comments" data-next-page="{{ $approvedComments->currentPage() + 1 }}"
                                    class="btn btn-primary py-2">
                                    see more commets
                                </button>
                            </div>
                        @endif

                        <!--  -->
                        <div class="p-t-40">
                            <h5 class="mtext-113 cl2 p-b-12">
                                Leave a Comment
                            </h5>

                            <p class="stext-107 cl6 p-b-40">
                                Your email address will not be published. Required fields are marked *
                            </p>

                            <form action="{{ route('customer.content.blog-detail.add-comment', $post) }}" method="POST">
                                @csrf
                                <div class="bor19 m-b-20">
                                    <textarea class="stext-111 cl2 plh3 size-124 p-lr-18 p-tb-15" name="body" placeholder="Comment...">{{ old('body') }}</textarea>

                                </div>
                                @error('body')
                                    <div class="text-danger" style="margin-top: 5px; font-size: 12px; font-weight: 400;">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                                <button type="submit"
                                    class="flex-c-m stext-101 cl0 size-125 bg3 bor2 hov-btn3 p-lr-15 trans-04 mt-3">
                                    Post Comment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-lg-3 p-b-80">
                    <div class="side-menu">
                        <div class="bor17 of-hidden pos-relative">
                            <input class="stext-103 cl2 plh4 size-116 p-l-28 p-r-55" type="text" name="search"
                                placeholder="Search">

                            <button class="flex-c-m size-122 ab-t-r fs-18 cl4 hov-cl1 trans-04">
                                <i class="zmdi zmdi-search"></i>
                            </button>
                        </div>

                        <div class="p-t-55">
                            <h4 class="mtext-112 cl2 p-b-33">
                                Categories
                            </h4>

                            <ul>
                                @foreach ($categories as $category)
                                    <li class="bor18">
                                        <a href="{{ route('customer.content.blogs', $category->slug) }}"
                                            class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4">
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="p-t-65">
                            <h4 class="mtext-112 cl2 p-b-33">
                                Featured Products
                            </h4>

                            <ul>
                                @foreach ($featuredProducts as $product)
                                    <li class="flex-w flex-t p-b-30">
                                        <a href="{{ route('customer.market.product', $product->slug) }}"
                                            class="wrao-pic-w size-214 hov-ovelay1 m-r-20">
                                            <img class="w-75" src="{{ asset($product->image['indexArray']['small']) }}"
                                                alt="{{ $product->name }}">
                                        </a>

                                        <div class="size-215 flex-col-t p-t-8">
                                            <a href="{{ route('customer.market.product', $product->slug) }}"
                                                class="stext-116 cl8 hov-cl1 trans-04">
                                                {{ $product->name }}
                                            </a>
                                            @php
                                                $variant = $product->variants->filter(
                                                    fn($v) => $v->warehouseVariants->sum('stock') >
                                                        $v->warehouseVariants->sum('reserved'),
                                                )->first();
                                            @endphp
                                            <span class="stext-116 cl6 p-t-20">
                                                ${{ $variant->price }}

                                            </span>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>


                        <div class="p-t-50">
                            <h4 class="mtext-112 cl2 p-b-27">
                                Tags
                            </h4>

                            <div class="flex-w m-r--5">
                                @foreach ($post->tags as $tag)
                                    <a href="{{ route('customer.market.shop', ['tag' => $tag->name]) }}"
                                        class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script src="{{ asset('customer-assets/vendor/MagnificPopup/jquery.magnific-popup.min.js') }}"></script>
    <script>
        $('.js-pscroll').each(function() {
            $(this).css('position', 'relative');
            $(this).css('overflow', 'hidden');
            var ps = new PerfectScrollbar(this, {
                wheelSpeed: 1,
                scrollingThreshold: 1000,
                wheelPropagation: false,
            });

            $(window).on('resize', function() {
                ps.update();
            })
        });
    </script>

    {{-- دکمه مشاهده بیشتر کامنت ها --}}
    <script>
        var btn = document.getElementById('load-more-comments');

        if (btn) {

            btn.addEventListener('click', function() {

                var page = btn.getAttribute('data-next-page');

                var url = window.location.pathname + '?page=' + page;
                fetch(url)
                    .then(function(response) {
                        return response.text();
                    })
                    .then(function(html) {

                        var parser = new DOMParser();
                        var doc = parser.parseFromString(html, 'text/html');

                        var newComments = doc.querySelector('#comments-container').innerHTML;

                        document
                            .getElementById('comments-container')
                            .insertAdjacentHTML('beforeend', newComments);

                        btn.setAttribute('data-next-page', Number(page) + 1);

                        if (!doc.querySelector('#load-more-comments')) {
                            btn.remove();
                        }

                    });

            });

        }
    </script>
@endsection
