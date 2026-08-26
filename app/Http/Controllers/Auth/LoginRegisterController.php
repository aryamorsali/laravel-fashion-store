<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginConfirmStoreRequest;
use App\Http\Requests\Auth\LoginRegisterStoreRequest;
use App\Models\Otp;
use App\Services\Authentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use Illuminate\View\View;

class LoginRegisterController extends Controller
{
    protected $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }
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
    public function loginRegisterStore(LoginRegisterStoreRequest $request)
    {
        $data = $request->validated();

        $result = $this->authentication->loginRegisterStore($data);

        return redirect()->route('auth.login-confirm.form', $result['token']);
    }



    public function loginConfirmForm($token)
    {
        $otp = Otp::where('token', $token)->first();
        if (!$otp) {
            return redirect()->route('auth.login-register.form')->withErrors(['id' => 'The address is not valid']);
        }
        return view('customer.auth.login-confirm', compact('otp', 'token'));
    }



    public function loginConfirmStore($token, LoginConfirmStoreRequest $request)
    {
        $data = $request->validated();

        $user = $this->authentication->loginConfirmStore($token, $data);

        Auth::login($user);

        return redirect('/');
    }

    public function resendOtp($token)
    {

        $result = $this->authentication->resendOtp($token);

        return redirect()->route('auth.login-confirm.form', $result['token']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // پاک کردن کامل session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
