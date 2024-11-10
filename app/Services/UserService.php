<?php 

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class UserService
{
    public function formatData(User $user): array {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ];
    }

    public function formatAllData(Collection $users):Collection {
        return $users->map(function($user){
            return $this->formatData($user);
        });
    }
}