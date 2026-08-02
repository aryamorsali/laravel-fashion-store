<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginConfirmStoreRequest;
use App\Http\Requests\Api\Auth\LoginRegisterStoreRequest;
use App\Services\Authentication;
use Illuminate\Support\Facades\Auth;

class LoginRegisterController extends Controller
{
    protected $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    public function loginRegisterStore(LoginRegisterStoreRequest $request)
    {
        $data = $request->validated();

        $result = $this->authentication->loginRegisterStore($data);

        return response()->json([
            'message' => "Verification code sent successfully.",
            'data' => [
                'otp_token' => $result['token'],
                'created_at' => $result['created_at'],
                'expires_at' => $result['expires_at'],
            ]
        ]);
    }

    public function loginConfirmStore($token, LoginConfirmStoreRequest $request)
    {
        $data = $request->validated();

        $user = $this->authentication->loginConfirmStore($token, $data);

        $accessToken = $user->createToken($request->header('User-Agent'))->plainTextToken;


        return response()->json([
            'message' => 'user successfuly logined',
            'data' => [
                'token_type' => 'Bearer',
                'access_token' => $accessToken,
            ]
        ]);
    }


    public function resendOtp($token)
    {

        $result = $this->authentication->resendOtp($token);

        return response()->json([
            'message' => 'OTP code successfully resent.',
            'data' => [
                'otp_token' => $result['token'],
                'created_at' => $result['created_at'],
                'expires_at' => $result['expires_at'],
            ]
        ]);
    }


    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'user  logged out  successfuly',
        ]);
    }
}
