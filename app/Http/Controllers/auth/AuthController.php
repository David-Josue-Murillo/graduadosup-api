<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Requests\auth\LoginRequest;
use App\Services\AuthService;

class AuthController
{
    protected $services;

    public function __construct(AuthService $services)
    {
        $this->services = $services;
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            //'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}