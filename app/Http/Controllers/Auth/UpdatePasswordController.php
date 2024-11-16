<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;

class UpdatePasswordController extends Controller
{
    public function update(UpdatePasswordRequest $request) {
        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return jsonResponse('Contraseña actualizada exitosamente', $user, 200);
    }
}
