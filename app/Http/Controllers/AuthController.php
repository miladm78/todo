<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignUpRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(SignUpRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        $token = $user->createToken($user->name . '-api-token')->accessToken;

        return new JsonResponse(['user' => $user, 'token' => $token], Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {

            return new JsonResponse(['error' => trans('passwords.invalid')], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken($user->name . '-api-token')->plainTextToken;

        return new JsonResponse(['token' => $token], Response::HTTP_OK);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
