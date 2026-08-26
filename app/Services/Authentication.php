<?php

namespace App\Services;

use App\Http\Services\Message\Email\EmailService;
use App\Http\Services\Message\MessageService;
use App\Http\Services\Message\SMS\SmsService;
use App\Models\Otp;
use App\Models\User;
use App\Models\User\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Authentication
{
    public function loginRegisterStore($data)
    {
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
            throw ValidationException::withMessages([
                'id' => 'Your login ID is neither a mobile number nor an email.'
            ])->redirectTo(route('auth.login-register.form'));
        }

        if (!$user) {
            $newUser['password'] = Hash::make(Str::random(32));
            $newUser['activation'] = 0;                        //
            $newUser['loyalty_level'] = 'bronze';
            $newUser['registration_date'] = Carbon::now();
            DB::transaction(function () use (&$user, $newUser) {
                $user = User::create($newUser);

                $defaultRole = Role::query()->where('name', 'user')->first();

                if ($defaultRole) {
                    $user->roles()->sync([$defaultRole->id]);
                }
            });
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

        return [
            'token' => $token,
            'created_at' => $otp->created_at,
            'expires_at' => $otp->created_at->addMinutes(5),
        ];
    }


    public function loginConfirmStore($token, $data)
    {

        $otp = Otp::where('token', $token)->where('is_used', 0)->where('created_at', '>=', Carbon::now()->subMinutes(5))->lockForUpdate()->first();
        if (!$otp) {
            throw ValidationException::withMessages([
                'id' => 'The verification code is expired or invalid.'
            ])->redirectTo(route('auth.login-register.form'));
        }

        $user = $otp->user;

        DB::transaction(function () use ($otp, $data, $token, &$user) {

            // check is otp match?
            if (!Hash::check($data['otp'], $otp->otp_code)) {
                throw  ValidationException::withMessages([
                    'otp' => 'Incorrect code'
                ])->redirectTo(route('auth.login-confirm.form', $token));
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

        return $user;
    }


    public function resendOtp($token)
    {

        $otp = Otp::where('token', $token)->where('created_at', '>=', Carbon::now()->subMinutes(5))->first();
        if (!$otp) {
            throw ValidationException::withMessages([
                'id' => 'The address is not valid'
            ])->redirectTo(route('auth.login-register.form'));
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

        return [
            'token' => $token,
            'created_at' => $otp->created_at,
            'expires_at' => $otp->created_at->addMinutes(5),
        ];
    }
}
