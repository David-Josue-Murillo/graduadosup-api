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
            return jsonResponse('Error de autenticación', null, 401, 'Unauthorized');
        }

        return jsonResponse('Token creado exitosamente', [
            'token' => $token,
            'expires_in' => auth('')->factory()->getTTL() * 60
        ], 200);
    }

}