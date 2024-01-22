<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prt_asignatura extends Model
{
    use HasFactory;

    protected $fillable = [
        'prt_dept_academico_id', 'prt_facultad_id', 'codigo', 'nombre', 'descripcion', 'estado'
    ];

    public function prt_dept_academico()
    {
        return $this->belongsTo(Prt_dept_academico::class, 'prt_dept_academico_id');
    }

    public function prt_academ_cargas()
    {
        return $this->hasMany(Prt_academ_carga::class, 'prt_asignatura_id');
    }
    
}
