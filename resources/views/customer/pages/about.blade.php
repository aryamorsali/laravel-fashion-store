@extends('customer.layouts.app')

@section('head-tag')
    <title>About</title>
@endsection

@section('content')
    <!-- Title page -->
    <section class="bg-img1 txt-center p-lr-15 p-tb-92"
        style="background-image: url('{{ asset('customer-assets/images/bg-01.jpg') }}');">
        <h2 class="ltext-105 cl0 txt-center">
            About
        </h2>
    </section>


    <!-- Content page -->
    <section class="bg0 p-t-75 p-b-120">
        <div class="container">
            <div class="row p-b-148">
                <div class="col-md-7 col-lg-8">
                    <div class="p-t-7 p-r-85 p-r-15-lg p-r-0-md">
                        <h3 class="mtext-111 cl2 p-b-16">
                            {{ $about->title ?? '' }}
                        </h3>

                        <p class="stext-113 cl6 p-b-26">
                            {!! $about->description !!}

                        </p>
                    </div>
                </div>

                @if ($about->image)
                    <div class="col-11 col-md-5 col-lg-4 m-lr-auto">
                        <div class="how-bor1 ">
                            <div class="hov-img0">
                                <img src="{{ asset($about->image) }}" alt="{{$about->title}}">
                            </div>
                        </div>
                    </div>
                @endif

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
