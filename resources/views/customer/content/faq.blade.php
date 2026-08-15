@extends('customer.layouts.app')

@section('head-tag')
    <title>FAQ</title>

    <style>
        .faq-item {
            border: 1px solid #e6e6e6;
            margin-bottom: 15px;
            background: #fff;
        }

        .faq-question {
            width: 100%;
            border: 0;
            outline: none;
            background: #fff;
            cursor: pointer;
            text-align: left;
            padding: 22px 28px;
            position: relative;
            color: #333;
            font-size: 16px;
            font-weight: 500;
            transition: all .3s ease;
        }

        .faq-question:hover {
            color: #717fe0;
        }

        .faq-question::after {
            content: '+';
            position: absolute;
            right: 28px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 23px;
            font-weight: 300;
            color: #888;
            transition: all .3s ease;
        }

        .faq-item.active .faq-question {
            color: #717fe0;
        }

        .faq-item.active .faq-question::after {
            content: '−';
            color: #717fe0;
        }

        .faq-answer {
            display: none;
            padding: 0 28px 24px;
            color: #777;
            line-height: 1.9;
            font-size: 14px;
        }

        .faq-answer p {
            margin: 0;
        }

        .faq-category-title {
            font-size: 20px;
            color: #333;
            margin-bottom: 25px;
        }

        .faq-sidebar {
            border: 1px solid #e6e6e6;
            padding: 35px 30px;
            height: 100%;
        }

        .faq-sidebar ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .faq-sidebar li {
            border-bottom: 1px solid #eeeeee;
        }

        .faq-sidebar li:last-child {
            border-bottom: none;
        }

        .faq-sidebar a {
            display: block;
            padding: 15px 0;
            color: #666;
            font-size: 14px;
            transition: all .3s ease;
        }

        .faq-sidebar a:hover,
        .faq-sidebar a.active {
            color: #717fe0;
            padding-left: 5px;
        }

        .faq-support-box {
            background: #f7f7f7;
            padding: 35px 30px;
            text-align: center;
        }

        .faq-support-box h4 {
            margin-bottom: 12px;
        }

        .faq-support-box p {
            color: #777;
            margin-bottom: 22px;
        }

        @media (max-width: 991px) {
            .faq-sidebar {
                margin-bottom: 35px;
                height: auto;
            }
        }
    </style>
@endsection

@section('content')
    {{-- Page title --}}
    <section class="bg-img1 txt-center p-lr-15 p-tb-92"
        style="background-image: url('{{ asset('customer-assets/images/bg-01.jpg') }}');">
        <h2 class="ltext-105 cl0 txt-center">
            Frequently Asked Questions
        </h2>
    </section>

    {{-- FAQ content --}}
    <section class="bg0 p-t-95 p-b-100">
        <div class="container">

            <div class="row">


                {{-- Questions --}}
                <div class="col-md-8 col-lg-11">

                    {{-- General --}}
                    <div id="general" class="faq-section m-b-45">
                        <h3 class="mtext-108 cl2 p-b-20">
                            Frequently Asked Questions
                        </h3>

                        @foreach ($faqs as $faq)
                            <div class="faq-item">
                                <button type="button" class="faq-question">
                                    {{ $faq->question ?? '-' }}
                                </button>

                                <div class="faq-answer">
                                    <p>
                                        {!! $faq->answer ?? '-' !!}

                                    </p>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $faqs->onEachSide(1)->appends(request()->query())->links('vendor.pagination.custom') }}
                </div>
            </div>

            {{-- Contact support box --}}
            <div class="row p-t-80">
                <div class="col-12">
                    <div class="faq-support-box">

                        <h4 class="mtext-105 cl2">
                            Still have questions?
                        </h4>

                        <p class="stext-115">
                            Our support team is ready to help you.
                        </p>

                        <a href="{{ route('customer.content.contact') }}"
                            class="flex-c-m stext-101 cl0 size-121 bg3 bor1 hov-btn3 p-lr-15 trans-04 pointer m-auto"
                            style="max-width: 220px;">
                            Contact Us
                        </a>

                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('.faq-question').on('click', function() {
                const item = $(this).closest('.faq-item');
                const answer = item.find('.faq-answer');

                $('.faq-item')
                    .not(item)
                    .removeClass('active')
                    .find('.faq-answer')
                    .stop(true, true)
                    .slideUp(250);

                item.toggleClass('active');

                answer.stop(true, true).slideToggle(250);
            });

            $('.faq-category-link').on('click', function(event) {
                event.preventDefault();

                const target = $(this).attr('href');

                $('.faq-category-link').removeClass('active');
                $(this).addClass('active');

                $('html, body').animate({
                    scrollTop: $(target).offset().top - 100
                }, 500);
            });

        });
    </script>
@endsection
