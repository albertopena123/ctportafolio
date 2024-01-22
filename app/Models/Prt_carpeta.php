<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prt_carpeta extends Model
{
    use HasFactory;

    protected $fillable = [
        'prt_seccion_id', 'prt_portafolio_id', 'prt_carpeta_id', 'codigo', 'nombre', 'descripcion', 'ubicacion', 'estado', 'user_id'
    ];

    public function prt_seccion_padre()
    {
        return $this->belongsTo(Prt_seccion::class, 'prt_seccion_id');
    }

    public function prt_portafolio()
    {
        return $this->belongsTo(Prt_portafolio::class, 'prt_portafolio_id');
    }

    public function prt_carpeta_padre()
    {
        return $this->belongsTo(Prt_carpeta::class, 'prt_carpeta_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prt_carpeta_hijos()
    {
        return $this->hasMany(Prt_carpeta::class, 'prt_carpeta_id');
    }

    public function prt_archivos()
    {
        return $this->hasMany(Prt_archivo::class, 'prt_carpeta_id');
    }

}
