<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prt_dept_academico extends Model
{
    use HasFactory;

    protected $fillable = [
        'prt_facultad_id', 'codigo', 'nombre', 'abreviatura', 'descripcion', 'estado'
    ];

    public function prt_facultad()
    {
        return $this->belongsTo(Prt_facultad::class, 'prt_facultad_id');
    }

    public function prt_prof_escuelas()
    {
        return $this->hasMany(Prt_prof_escuela::class, 'prt_dept_academico_id');
    }

    public function prt_asignaturas()
    {
        return $this->hasMany(Prt_asignatura::class, 'prt_dept_academico_id');
    }

}
