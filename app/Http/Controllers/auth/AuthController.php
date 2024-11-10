<?php 

namespace App\Http\Controllers\Auth;

use App\Services\AuthService;

class AuthController
{
    protected $services;

    public function __construct(AuthService $services)
    {
        $this->services = $services;
    }
}