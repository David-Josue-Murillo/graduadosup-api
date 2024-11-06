<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Campu extends Model
{
    /** @use HasFactory<\Database\Factories\CampuFactory> */
    use HasFactory, Notifiable;

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
        return $this->hasMany(NumGraduate::class, 'campus_id');
    }
}
