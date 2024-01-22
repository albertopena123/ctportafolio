<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usr_persona extends Model
{
    use HasFactory;

    protected $fillable = [
        'persona_tipo',
        'usr_documento_tipo_id',
        'nro_documento',
        'nombre',
        'apaterno',
        'amaterno',
        'usr_pais_id',
        'usr_departamento_id',
        'usr_provincia_id',
        'usr_distrito_id',
        'ciudad',
        'direccion',
        'sexo',
        'telefono',
        'correo',
        'nacimiento',
        'estado_civil',
        'usr_acad_grado_id',
        'usr_prof_titulo_id',
        'titulo_profesional',
        'estado'
    ];
    
    public function usr_documento_tipo()
    {
        return $this->belongsTo(Usr_documento_tipo::class, 'usr_documento_tipo_id');
    }

    /*
    public function usr_pais()
    {
        return $this->belongsTo(Usr_pais::class, 'usr_pais_id');
    }

    public function usr_departamento()
    {
        return $this->belongsTo(Usr_departamento::class, 'usr_departamento_id');
    }

    public function usr_provincia()
    {
        return $this->belongsTo(Usr_provincia::class, 'usr_provincia_id');
    }

    public function usr_distrito()
    {
        return $this->belongsTo(Usr_distrito::class, 'usr_distrito_id');
    }

    public function usr_academico_grado()
    {
        return $this->belongsTo(Usr_academico_grado::class, 'usr_acad_grado_id');
    }

    public function usr_profesional_titulo()
    {
        return $this->belongsTo(Usr_profesional_titulo::class, 'usr_prof_titulo_id');
    }
    */
    
    public function users()
    {
        return $this->hasMany(User::class, 'usr_persona_id');
    }

    public function prt_docentes()
    {
        return $this->hasMany(Prt_docente::class, 'usr_persona_id');
    }
}
