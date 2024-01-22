<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prt_facultad extends Model
{
    use HasFactory;

    protected $table = 'prt_facultades';

    protected $fillable = [
        'codigo', 'nombre', 'abreviatura', 'descripcion', 'estado'
    ];

    public function prt_dept_academicos()
    {
        return $this->hasMany(Prt_dept_academico::class, 'prt_facultad_id');
    }
    
}
