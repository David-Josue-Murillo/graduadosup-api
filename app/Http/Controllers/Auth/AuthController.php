<?php 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Auth;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
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
    public function login(LoginRequest $request): JsonResponse
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

    public function logout() 
    {
        Auth::logout();
        return jsonResponse('Sessión cerrada');
    }
}