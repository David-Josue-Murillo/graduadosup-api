<?php 

namespace App\Http\Controllers\Auth;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\PasswordResetRequest;

class PasswordResetController extends Controller
{

    /**
     * Send a password reset link to a user
     * @param \App\Http\Requests\Auth\PasswordResetRequest $request
     * @return \Illuminate\Http\JsonResponse
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

    public function reset(){
        
    }
}