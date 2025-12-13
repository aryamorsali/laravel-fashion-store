<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRegisterRequest;
use App\Models\Otp;
use App\Models\User;

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
            $user = User::create($newUser);
        }

        // create otp code
        $otpCode = random_int(111111, 999999);
        $token = Str::random(60);
        $otpInputs = [
            'token' => $token,
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'login_id' => $id,
            'type' => $type,
        ];
        $otp = Otp::create($otpInputs);
        // dd($otp);
        // event(new Registered($user));
    }
}
