<?php  

namespace App\Services;

use App\Http\Requests\auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Attempt to authenticate a user.
     *
     * @param LoginRequest $request
     * @return array
     * @throws ValidationException
     */
    public function login(LoginRequest $request): array 
    {
        $credentials = $request->getCredentials();

        if(!Auth::attempt($credentials)){
            throw ValidationException::withMessages([
               'login' => ['Las credenciales proporcionadas son incorrectas'] 
            ]);
        }

        $user = Auth::user();
        return [
            'user' => $user
        ];
    }

    /**
     * Logout the user from the current device.
     *
     * @param string|null $device
     * @return void
     */
    public function logout():void 
    {
       if(Auth::check()) {
            Auth::logout();
       }
    }

    /**
     * Check if user exists by email or username.
     *
     * @param string $login
     * @return bool
     */
    public function checkUserExists(string $login): bool
    {
        return User::where('email', $login)
            ->orWhere('username', $login)
            ->exists();
    }
}