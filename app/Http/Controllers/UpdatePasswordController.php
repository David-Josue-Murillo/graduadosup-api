<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use Auth;
use Hash;
use Illuminate\Http\Request;

class UpdatePasswordController extends Controller
{
    public function update(UpdatePasswordRequest $request) {
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        return jsonResponse('Contraseña actualizada exitosamente', $user, 200);
    }
}
