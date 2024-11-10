<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $services;

    public function __construct(UserService $services)
    {
        $this->services = $services;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->services->formatAllData(User::all());
        return $this->jsonResponse('ok', $users, 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $newUser = User::create($request->validated());
        return $this->jsonResponse('Usuario creado exitosamente', $newUser, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
