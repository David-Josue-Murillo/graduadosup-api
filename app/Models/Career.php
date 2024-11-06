<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Career extends Model
{
        /** @use HasFactory<\Database\Factories\CareerFactory> */
        use HasFactory, Notifiable;

    /**
     * Atributos que pueden ser asignados
     * @var array
     */
    protected $fillable = [
        'name',
        'faculty_id'
    ];

    /**
     * Ocultando los datos que no se deben mostrar
     * @var array 
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function graduates() {
        return $this->hasMany(NumGraduate::class);
    }

    public function faculty() {
        return $this->belongsTo(Faculty::class);
    }
}
