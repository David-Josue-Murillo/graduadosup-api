<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use Hash;

class UpdatePasswordController extends Controller
{
    public function update(UpdatePasswordRequest $request) {
        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return jsonResponse('Contraseña actualizada exitosamente', $user, 200);
    }
}
