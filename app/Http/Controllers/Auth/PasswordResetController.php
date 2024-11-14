<?php 

namespace App\Http\Controllers\Auth;

use Password;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetRequest;

class PasswordResetController extends Controller
{
    protected $services;

    public function __construct(){
        
    }

    /**
     * Send a password reset link to a user
     * @param \App\Http\Requests\Auth\PasswordResetRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(PasswordResetRequest $request)
    {
        $status = Password::sendResetLink(
            $request->onlY('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? jsonResponse('Correo enviado exitosamente', __($status), 200)
            : jsonResponse('Error al enviar el correo', __($status), 400);
    }
}