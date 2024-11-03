<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campu extends Model
{
    /**
     * Attributes that can be assigned
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Hiding data that should not be displayed
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function graduates() {
        return $this->hasMany(Graduate::class);
    }
}
