<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Graduate extends Model
{

    /**
     * butes that can be assigned
     * @var array
     */
    protected $fillable = [
        'quantity',
        'year',
        'campus_id',
        'career_id'
    ];


    /**
     * Hidding data that should not be displayed
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function campu() {
        return $this->belongsTo(Campu::class);
    }

    public function career() {
        return $this->belongsTo(Career::class);
    }
}
