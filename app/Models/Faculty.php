<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    //
    use HasFactory;

    /**
     * Atributos que pueden ser asignados
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Ocultando los datos que no se deben mostrar
     * @var array 
     */
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function careers() {
        return $this->hasMany(Career::class);
    }
}
