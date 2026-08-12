<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginConfirmStoreRequest;
use App\Http\Requests\Api\Auth\LoginRegisterStoreRequest;
use App\Services\Authentication;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Authentication', description: 'OTP-based login and registration')]

class LoginRegisterController extends Controller
{
    protected $authentication;

    public function __construct(Authentication $authentication)
    {
        $this->authentication = $authentication;
    }

    #[OA\Post(
        path: '/api/login-register',
        summary: 'Request OTP code',
        description: 'Send OTP to email or Iranian mobile number. Creates user if not exists.',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            required: true,

            content: new OA\JsonContent(
                required: ['id'],
                properties: [
                    new OA\Property(
                        property: 'id',
                        type: 'string',
                        description: 'Email address or Iranian mobile number (+98/98/09XXXXXXXXX)',
                        example: '09123456789 || arya@gmail.com'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'OTP sent successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'Verification code sent successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'otp_token', type: 'string', example: 'abc123xyz...'),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'expires_at', type: 'string', format: 'date-time')
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'Your login ID is neither a mobile number nor an email.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'id',
                                    type: 'array',
                                    items: new OA\Items(type: 'string'),
                                    example: ['Your login ID is neither a mobile number nor an email.']
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 429,
                description: 'Too many requests',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/429ResponseSchema'
                )
            ),
        ]
    )]

    public function loginRegisterStore(LoginRegisterStoreRequest $request)
    {
        $data = $request->validated();

        $result = $this->authentication->loginRegisterStore($data);

        return response()->json([
            'status' => 'success',
            'message' => "Verification code sent successfully.",
            'data' => [
                'otp_token' => $result['token'],
                'created_at' => $result['created_at'],
                'expires_at' => $result['expires_at'],
            ]
        ]);
    }


    #[OA\Post(
        path: '/api/login-confirm/{token}',
        summary: 'Verify OTP and login',
        description: 'Confirm OTP code. Enter Otp Token And Otp Code. Returns Sanctum Bearer token on success. OTP expires in 5 minutes.',
        tags: ['Authentication'],
        parameters: [
            new OA\Parameter(
                name: 'token',
                in: 'path',
                required: true,
                description: 'OTP session token received from /login-register',
                schema: new OA\Schema(type: 'string', example: 'abc123xyz...')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,

            content: new OA\JsonContent(
                required: ['otp'],
                properties: [
                    new OA\Property(
                        property: 'otp',
                        type: 'string',
                        description: '6-digit OTP code',
                        example: '482910'
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'user successfuly logined'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
                                new OA\Property(property: 'access_token', type: 'string', example: '1|abc123...')
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Invalid or expired OTP',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'The verification code is expired or invalid.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'id',
                                    type: 'array',
                                    items: new OA\Items(type: 'string'),
                                    example: ['The verification code is expired or invalid.']
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 429,
                description: 'Too many requests',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/429ResponseSchema'
                )
            ),
        ]
    )]

    public function loginConfirmStore($token, LoginConfirmStoreRequest $request)
    {
        $data = $request->validated();

        $user = $this->authentication->loginConfirmStore($token, $data);

        $accessToken = $user->createToken($request->header('User-Agent'))->plainTextToken;


        return response()->json([
            'status' => 'success',
            'message' => 'user successfuly logined',
            'data' => [
                'token_type' => 'Bearer',
                'access_token' => $accessToken,
            ]
        ]);
    }


    #[OA\Get(
        path: '/api/login-resend-otp/{token}',
        summary: 'Resend OTP code',
        description: 'Resend a new OTP using the existing session token. Original token must still be within 5-minute window.',
        tags: ['Authentication'],
        parameters: [
            new OA\Parameter(
                name: 'token',
                in: 'path',
                required: true,
                description: 'OTP session token from /login-register',
                schema: new OA\Schema(type: 'string', example: 'abc123xyz...')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'OTP resent successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'OTP code successfully resent.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'otp_token', type: 'string', example: 'newtoken123...'),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
                                new OA\Property(property: 'expires_at', type: 'string', format: 'date-time')
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Token expired or invalid',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'error'),
                        new OA\Property(property: 'message', type: 'string', example: 'The address is not valid'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'id',
                                    type: 'array',
                                    items: new OA\Items(type: 'string'),
                                    example: ['The address is not valid']
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 429,
                description: 'Too many requests',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/429ResponseSchema'
                )
            ),
        ]
    )]

    public function resendOtp($token)
    {

        $result = $this->authentication->resendOtp($token);

        return response()->json([
            'status' => 'success',
            'message' => 'OTP code successfully resent.',
            'data' => [
                'otp_token' => $result['token'],
                'created_at' => $result['created_at'],
                'expires_at' => $result['expires_at'],
            ]
        ]);
    }

    #[OA\Get(
        path: '/api/logout',
        security: [["sanctum" => []]],
        summary: 'Logout User',
        description: 'Logout User And Delete Access Token',
        tags: ['Authentication'],

        responses: [
            new OA\Response(
                response: 200,
                description: 'User Logout Successfuly',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'string', example: 'success'),
                        new OA\Property(property: 'message', type: 'string', example: 'user  logged out  successfuly'),
                    ]
                )
            ),
        ]
    )]

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'user  logged out  successfuly',
        ]);
    }
}
