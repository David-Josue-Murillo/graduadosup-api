<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{

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
