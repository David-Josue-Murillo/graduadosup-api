<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class NumGraduate extends Model
{
    /** @use HasFactory<\Database\Factories\NumGraduateFactory> */
    use HasFactory, Notifiable;

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

    public function campus() {
        return $this->belongsTo(Campu::class, 'campus_id');
    }

    public function career() {
        return $this->belongsTo(Career::class, 'career_id');
    }

    public function faculty() {
        return $this->hasOneThrough(Faculty::class, Career::class, 'id', 'id', 'career_id', 'faculty_id');
    }

}
