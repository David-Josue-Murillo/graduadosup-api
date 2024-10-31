<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    public function graduates() {
        return $this->hasMany(Graduate::class);
    }

    public function faculty() {
        return $this->belongsTo(Faculty::class);
    }
}
