<?php

namespace App\Http\Controllers;

use App\Constants\MessagesAlert;
use App\Http\Requests\JwtAuth\JWTAuthLoginRequest;
use App\Http\Requests\JwtAuth\JWTAuthRegisterRequest;
use App\Repositories\UserRepository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTAuthController extends Controller
{
    public function __construct(
        protected UserRepository $userRepository,
    ) {}


    public function register(JWTAuthRegisterRequest $request)
    {
        $user = $this->userRepository->register([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }


    public function login(JWTAuthLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales incorrectas'], 401);
            }

            $user = auth()->user();

            $token = JWTAuth::fromUser($user);

            return response()->json(compact('token', "user"));
        } catch (JWTException $e) {
            return response()->json([
                'code' => 500,
                'message' => MessagesAlert::API_ERROR
            ], 500);
        }
    }
}
