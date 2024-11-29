<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    protected $services;

    public function __construct(UserService $services)
    {
        $this->services = $services;
    }

    /**
     * Display a listing of the resource.
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = UserResource::collection(User::all());
        return jsonResponse('ok', $users, 200);
    }


    /**
     * Store a newly created resource in storage.
     * 
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        $newUser = User::create($request->validated());
        $token = JWTAuth::fromUser($newUser);
        $data = [
            'user' => $newUser,
            'token' => $token,
            'expires_in' => auth('')->factory()->getTTL() * 60
        ];

        return jsonResponse('Usuario creado exitosamente', $data, 201);
    }

    /**
     *  Display the specified resource.
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user =  UserResource::make(User::findOrFail($id));
        return jsonResponse('Usuario encontreado', $user, 200);
    }

    /**
     *  Update the specified resource in storage.
     * 
     * @param UserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UserRequest $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->update($request->validated());
        return jsonResponse('Usuario actualizado exitosamente', $user, 200);
    }

    /**
     *  Remove the specified resource from storage.
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();
        return jsonResponse('Usuario eliminado exitosamente', [], 204);
    }
}
