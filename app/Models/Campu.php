<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campu extends Model
{
    //
    public function graduates() {
        return $this->hasMany(Graduate::class);
    }
}
