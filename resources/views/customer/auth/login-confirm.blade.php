@extends('customer.auth.layouts.master-simple')

@section('content')
    <div class="min-h-screen bg-gray-100 py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div
                class="absolute inset-0 bg-gradient-to-r from-cyan-400 to-sky-500 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl">
            </div>
            <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20">
                <form action="{{ route('auth.login-confirm.store', $token) }}" method="POST">
                    @csrf
                    <div class="max-w-md mx-auto">
                        <h1 class="text-2xl font-semibold mb-4">Enter the verification code:</h1>
                        <p class="text-gray-600 mb-6">
                            @if ($otp->type == 0)
                                Verification code was sent to the phone number {{ $otp->login_id }}
                            @else
                                Verification code was sent to the email {{ $otp->login_id }}
                            @endif
                        </p>

                        <div class="space-y-4">
                            <input autocomplete="off" id="otp" name="otp" type="text"
                                class="h-12 w-full border-b-2 border-gray-300 text-gray-900 px-3 focus:outline-none focus:border-cyan-500 placeholder-gray-400"
                                placeholder="Enter code" />
                            @error('otp')
                                <div class="text-danger" style="margin-top: 9px; font-size: 12px; font-weight: 400;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                            <button
                                class="h-12 w-full bg-cyan-500 text-white font-medium rounded-md hover:bg-cyan-600 transition">
                                Submit
                            </button>
                            <section id="resend-otp" class="d-none">
                                <a href="{{ route('auth.login-resend-otp', $token) }}"
                                    class="text-decoration-none text-primary">Receive verification code</a>
                            </section>
                            <section id="timer"class="text-muted small"></section>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @php
        use Carbon\Carbon;

        // زمان انقضای OTP
        $expireTime = Carbon::parse($otp->created_at)->addMinutes(5);

        // الان
        $now = Carbon::now();

        // اختلاف زمان (میلی‌ثانیه)
        $remainingTime = max($now->diffInMilliseconds($expireTime, false), 0);

    @endphp
    {{-- @dd($otp->created_at, Carbon::parse($otp->created_at)->toDateTimeString(), $expireTime, $remainingTime); --}}



    <script>
        let remainingTime = {{ $remainingTime }};
        let timer = document.getElementById('timer');
        let resendOtp = document.getElementById('resend-otp');

        let interval = setInterval(function() {

            let minutes = Math.floor(remainingTime / (1000 * 60));
            let seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

            if (minutes > 0) {
                timer.textContent = `Resend verification code in ${minutes} minutes and ${seconds} seconds`;
            } else {
                timer.textContent = `Resend verification code in ${seconds} seconds`;
            }

            remainingTime -= 1000;
            if (remainingTime <= 0) {
                clearInterval(interval);
                timer.classList.add('d-none');
                resendOtp.classList.remove('d-none');
            }

        }, 1000);
    </script>
@endsection
