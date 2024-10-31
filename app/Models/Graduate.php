<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Graduate extends Model
{
    public function campu() {
        return $this->belongsTo(Campu::class);
    }

    public function career() {
        return $this->belongsTo(Career::class);
    }
}
