<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

abstract class TestCase extends BaseTestCase
{
    protected function apiAs(User $user, string $method, string $url, array $data = [])
    {
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '. JWTAuth::fromUser($user)
        ];

        return $this->json($method, $url, $data, $headers);
    }
}
