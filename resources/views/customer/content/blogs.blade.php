@extends('customer.layouts.app')

@section('head-tag')
    <title>Blogs</title>
    <style>
        .filter-tag-active {
            color: #6c7ae0 !important;
            border-color: #6c7ae0 !important;
        }
    </style>
@endsection

@section('content')
    <!-- Title page -->
    <section class="bg-img1 txt-center p-lr-15 p-tb-92"
        style="background-image: url('{{ asset('customer-assets/images/bg-02.jpg') }}');">
        <h2 class="ltext-105 cl0 txt-center">
            Blogs
        </h2>
    </section>


    <!-- Content page -->
    <section class="bg0 p-t-62 p-b-60">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-lg-9 p-b-80">
                    <div class="p-r-45 p-r-0-lg">
                        @if (!$posts->isEmpty())
                            @foreach ($posts as $post)
                                <!-- item blog -->
                                <div class="p-b-63">
                                    <a href="{{ route('customer.content.blog-detail', $post) }}"
                                        class="hov-img0 how-pos5-parent">
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
                                    </a>

                                    <div class="p-t-32">
                                        <h4 class="p-b-15">
                                            <a href="{{ route('customer.content.blog-detail', $post) }}"
                                                class="ltext-108 cl2 hov-cl1 trans-04">
                                                {{ $post->title }}
                                            </a>
                                        </h4>

                                        <p class="stext-117 cl6">
                                            {!! $post->summary !!}
                                        </p>

                                        <div class="flex-w flex-sb-m p-t-18">
                                            <span class="flex-w flex-m stext-111 cl2 p-r-30 m-tb-10">
                                                <span>
                                                    <span class="cl4">By</span> {{ $post->user->full_name ?? '-' }}
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
                                                    {{ $post->comments->count() ?? '-' }} Comments
                                                </span>
                                            </span>

                                            <a href="{{ route('customer.content.blog-detail', $post) }}"
                                                class="stext-101 cl2 hov-cl1 trans-04 m-tb-10">
                                                Continue Reading

                                                <i class="fa fa-long-arrow-right m-l-9"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <h3 class="text-danger ml-5">no posts available</h3>
                        @endif


                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $posts->onEachSide(1)->appends(request()->query())->links('vendor.pagination.custom') }}
                        </div>

                    </div>
                </div>

                <div class="col-md-4 col-lg-3 p-b-80">
                    <div class="side-menu">
                        <div class="bor17 of-hidden pos-relative">
                            <form action="{{ url()->current() }}" method="GET">

                                <input class="stext-103 cl2 plh4 size-116 p-l-28 p-r-55" type="text" name="search"
                                    value="{{ request()->search }}" placeholder="Search">

                                <button class="flex-c-m size-122 ab-t-r fs-18 cl4 hov-cl1 trans-04">
                                    <i class="zmdi zmdi-search"></i>
                                </button>
                            </form>
                        </div>


                        @php
                            $currentCategory = request()->route('category');
                        @endphp
                        <div class="p-t-55">
                            <h4 class="mtext-112 cl2 p-b-33">
                                Categories
                            </h4>

                            <ul>
                                @foreach ($categories as $category)
                                    <li class="bor18">
                                        <a href="{{ route('customer.content.blogs', $category->slug) }}"
                                            class="dis-block stext-115 cl6 hov-cl1 trans-04 p-tb-8 p-lr-4  filter-link stext-106 trans-04
                                        {{ $currentCategory?->slug === $category->slug ? 'filter-link-active' : '' }}">
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
                                                $variant = $product->variants
                                                    ->filter(
                                                        fn($v) => $v->warehouseVariants->sum('stock') >
                                                            $v->warehouseVariants->sum('reserved'),
                                                    )
                                                    ->first();
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
                                @foreach ($tags as $tag)
                                    <a href="{{ route('customer.content.blogs', ['category' => $currentCategory?->slug] + request()->except(['page', 'tag']) + ['tag' => $tag->slug]) }}"
                                        class="flex-c-m stext-107 cl6 size-301 bor7 p-lr-15 hov-tag1 trans-04 m-r-5 m-b-5 {{ request('tag') == $tag->slug ? 'filter-tag-active' : '' }}">
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
@endsection
