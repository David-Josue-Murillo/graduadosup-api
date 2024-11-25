<?php 

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\PasswordResetRequest;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{

    /**
     * Send a password reset link to a user
     * @param PasswordResetRequest $request
     * @return JsonResponse
     */
    public function sendResetLinkEmail(PasswordResetRequest $request): JsonResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? jsonResponse('Correo enviado exitosamente', __($status), 200)
            : jsonResponse('Error al enviar el correo', __($status), 500);
    }

    public function reset(PasswordResetRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password)  {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? jsonResponse('Contraseña restablecida exitosamente', __($status), 200)
            : jsonResponse('Error al restablecer la contraseña', __($status), 500);
    }
}