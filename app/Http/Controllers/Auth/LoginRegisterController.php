<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRegisterRequest;
use App\Http\Services\Message\Email\EmailService;
use App\Http\Services\Message\MessageService;
use App\Http\Services\Message\SMS\SmsService;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\View\View;

class LoginRegisterController extends Controller
{
    /**
     * Display the registration view.
     */
    public function loginRegisterForm(): View
    {
        return view('customer.auth.login-register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function loginRegisterStore(LoginRegisterRequest $request)
    {
        $data = $request->validated();
        $id = trim($data['id']);
        $newUser = [];

        // check is email?
        if (filter_var($id, FILTER_VALIDATE_EMAIL)) {
            $type = 1;       // email
            $user = User::where('email', $data['id'])->first();
            if (empty($user)) {
                $newUser['email'] = $id;
            }
        }
        // check is phone number?
        elseif (preg_match('/^(\+98|98|0)9\d{9}$/', $id)) {
            $type = 0;       //phone
            // save phone number with one format
            $id = preg_replace('/^(?:\+98|0|98)/', '', $id);

            $user = User::where('mobile', $id)->first();
            if (empty($user)) {
                $newUser['mobile'] = $id;
            }
        } else {
            return redirect()->back()->withErrors(['id' => 'Your login ID is neither a mobile number nor an email.']);
        }

        if (!$user) {
            $newUser['password'] = Hash::make(Str::random(32));
            $newUser['activation'] = 0;                        //
            $newUser['loyalty_level'] = 'bronze';
            $newUser['registration_date'] = Carbon::now();
            $user = User::create($newUser);
        }

        // create otp code
        $otpCode = random_int(111111, 999999);
        $token = Str::random(60);
        $otpInputs = [
            'token' => $token,
            'user_id' => $user->id,
            'otp_code' => Hash::make($otpCode),
            'login_id' => $id,
            'type' => $type,
        ];
        $otp = Otp::create($otpInputs);

        // send email or sms
        if ($type == 0) {
            // send sms
            $smsService = new SmsService();
            $smsService->setFrom(Config::get('sms.otp_from'));
            $smsService->setTo(['0' . $user->mobile]);
            $smsService->setText("Coza Shop \n Verification code : $otpCode");
            $smsService->setIsFlash(true);

            $messagesService = new MessageService($smsService);
        } elseif ($type == 1) {
            // send email
            $emailService = new EmailService();
            $details = [
                'title' => 'Activation Email',
                'body' => "Your activation code : $otpCode"
            ];
            $emailService->setDetails($details);
            $emailService->setFrom('noreply@shop.com', 'CozaShop');
            $emailService->setSubject('Authentication code');
            $emailService->setTo($id);
            $messagesService = new MessageService($emailService);
        }

        $messagesService->send();

        return redirect()->route('auth.login-confirm.form', $token);
    }


    public function loginConfirmForm($token)
    {
        $otp = Otp::where('token', $token)->first();
        if (!$otp) {
            return redirect()->route('auth.login-register.form')->withErrors(['id' => 'The address is not valid']);
        }
        return view('customer.auth.login-confirm', compact('otp', 'token'));
    }

    public function loginConfirmStore($token, LoginRegisterRequest $request)
    {
        $data = $request->validated();

        $otp = Otp::where('token', $token)->where('is_used', 0)->where('created_at', '>=', Carbon::now()->subMinutes(5))->first();
        if (!$otp) {
            return redirect()->route('auth.login-register.form')->withErrors(['id' => 'error']);
        }

        $user = $otp->user;

        DB::transaction(function () use ($otp, $data, $token, $user) {

            // check is otp match?
            if (!Hash::check($data['otp'], $otp->otp_code)) {

                return redirect()->route('auth.login-confirm.form', $token)->withErrors(['otp' => 'Incorrect code']);
            }
            // if everything is ok :
            $otp->update(['is_used' => 1]);


            if ($otp->type == 0 && empty($user->mobile_verified_at)) {
                $user->update(['mobile_verified_at' => Carbon::now()]);
            } elseif ($otp->type == 1 && empty($user->email_verified_at)) {
                $user->update(['email_verified_at' => Carbon::now()]);
            }
            $user->update(['activation' => 1]);
        });

        Auth::login($user);
        return redirect('/');
    }

    public function resendOtp($token)
    {

        $otp = Otp::where('token', $token)->where('created_at', '<=', Carbon::now()->subMinutes(5))->first();
        if (!$otp) {
            return redirect()->route('auth.login-register.form')->withErrors(['id' => 'The address is not valid']);
        }

        $user = $otp->user;

        // create otp code
        $otpCode = random_int(111111, 999999);
        $token = Str::random(60);
        $otpInputs = [
            'token' => $token,
            'user_id' => $user->id,
            'otp_code' => Hash::make($otpCode),
            'login_id' => $otp->login_id,
            'type' => $otp->type,
        ];
        $otp = Otp::create($otpInputs);

        // send email or sms
        if ($otp->type == 0) {
            // send sms
            $smsService = new SmsService();
            $smsService->setFrom(Config::get('sms.otp_from'));
            $smsService->setTo(['0' . $user->mobile]);
            $smsService->setText("Coza Shop \n Verification code : $otpCode");
            $smsService->setIsFlash(true);

            $messagesService = new MessageService($smsService);
        } elseif ($otp->type == 1) {
            // send email
            $emailService = new EmailService();
            $details = [
                'title' => 'Activation Email',
                'body' => "Your activation code : $otpCode"
            ];
            $emailService->setDetails($details);
            $emailService->setFrom('noreply@shop.com', 'CozaShop');
            $emailService->setSubject('Authentication code');
            $emailService->setTo($otp->login_id);
            $messagesService = new MessageService($emailService);
        }

        $messagesService->send();

        return redirect()->route('auth.login-confirm.form', $token);
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
            return redirect('/');
        } else {
            abort(404);
        }
    }
}
