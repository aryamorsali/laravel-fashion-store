@extends('customer.layouts.app')

@section('head-tag')
    <title>Contact</title>
@endsection

@section('content')
    <!-- Title page -->
    <section class="bg-img1 txt-center p-lr-15 p-tb-92"
        style="background-image: url('{{ asset('customer-assets/images/bg-01.jpg') }}');">
        <h2 class="ltext-105 cl0 txt-center">
            Contact
        </h2>
    </section>
    @include('admin.alerts.toast.success')
    <!-- Content page -->
    <section class="bg0 p-t-104 p-b-116">
        <div class="container">
            <div class="flex-w flex-tr">
                <div class="size-210 bor10 p-lr-70 p-t-55 p-b-70 p-lr-15-lg w-full-md">
                    <form action="{{ route('customer.content.contact.store') }}" method="POST">
                        @csrf
                        <h4 class="mtext-105 cl2 txt-center p-b-30">
                            Send Us A Message
                        </h4>

                        <div class="bor8 m-b-20 how-pos4-parent">
                            <input class="stext-111 cl2 plh3 size-116 p-l-62 p-r-30" type="text" name="email"
                                placeholder="Your Email Address">

                            <img class="how-pos4 pointer-none"
                                src="{{ asset('customer-assets/images/icons/icon-email.png') }}" alt="ICON">
                            @error('email')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="bor8 m-b-30">
                            <textarea class="stext-111 cl2 plh3 size-120 p-lr-28 p-tb-25" name="body" placeholder="How Can We Help?"></textarea>
                            @error('body')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <button type="submit"
                            class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer">
                            Submit
                        </button>
                    </form>
                </div>
                <div class="size-210 bor10 flex-w flex-col-m p-lr-93 p-tb-30 p-lr-15-lg w-full-md">
                    @if (isset($settings['site_address']))
                        <div class="flex-w w-full p-b-42">
                            <span class="fs-19 cl5 txt-center size-211">
                                <i class="fa fa-map-marker"></i>
                            </span>

                            <div class="size-212 p-t-2">
                                <span class="mtext-110 cl2">
                                    Address
                                </span>

                                <p class="stext-115 cl6 size-213 p-t-18">
                                    {{ $settings['site_address'] }}
                                </p>
                            </div>
                        </div>
                    @endif

                    @if (isset($settings['site_phone']))
                        <div class="flex-w w-full p-b-42">
                            <span class="fs-20 cl5 txt-center size-211">
                                <i class="fa fa-phone"></i>

                            </span>

                            <div class="size-212 p-t-2">
                                <span class="mtext-110 cl2">
                                    Lets Talk
                                </span>

                                <p class="stext-115 cl1 size-213 p-t-18">
                                    {{ $settings['site_phone'] }}
                                </p>
                            </div>
                        </div>
                    @endif

                    @if (isset($settings['site_email']))
                        <div class="flex-w w-full">
                            <span class="fs-16 cl5 txt-center size-211">
                                <i class="fa fa-envelope"></i>

                            </span>

                            <div class="size-212 p-t-2">
                                <span class="mtext-110 cl2">
                                    Sale Support
                                </span>

                                <p class="stext-115 cl1 size-213 p-t-18">
                                    {{ $settings['site_email'] }}
                                </p>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>


    <!-- Map -->
    <div class="map">
        <div class="size-303" id="google_map" data-map-x="40.691446" data-map-y="-73.886787"
            data-pin="{{ asset('customer-assets/images/icons/pin.png') }}" data-scrollwhell="0" data-draggable="1"
            data-zoom="11"></div>
    </div>
@endsection


@section('script')
    <script src="{{ asset('customer-assets/vendor/MagnificPopup/jquery.magnific-popup.min.js') }}"></script>
    <!--===============================================================================================-->
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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKFWBqlKAGCeS1rMVoaNlwyayu0e0YRes"></script>
    <script src="{{ asset('customer-assets/js/map-custom.js') }}"></script>
@endsection
