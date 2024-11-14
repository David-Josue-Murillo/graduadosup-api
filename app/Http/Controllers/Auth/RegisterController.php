<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /**
     * Register a new user and return a token JWT.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $newUser = User::create($request->validated());
        $token = auth()->login($newUser);
        $data = [
            'user' => $newUser,
            'token' => $token,
        ];

        return jsonResponse('Usuario creado exitosamente', $data, 201);
    }
}
