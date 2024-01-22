<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prt_seccion extends Model
{
    use HasFactory;

    protected $table = 'prt_secciones';

    protected $fillable = [
        'prt_seccion_id', 'numero', 'nombre', 'descripcion', 'estado'
    ];

    public function prt_seccion_padre()
    {
        return $this->belongsTo(Prt_seccion::class, 'prt_seccion_id');
    }

    public function prt_seccion_hijos()
    {
        return $this->hasMany(Prt_seccion::class, 'prt_seccion_id');
    }

    public function prt_carpetas()
    {
        return $this->hasMany(Prt_carpeta::class, 'prt_seccion_id');
    }

    public function prt_carpetas_principales()
    {
        return $this->hasMany(Prt_carpeta::class, 'prt_seccion_id')->whereNull('prt_carpeta_id');
    }

    public function prt_archivos()
    {
        return $this->hasMany(Prt_archivo::class, 'prt_seccion_id');
    }

    public function prt_archivos_principales()
    {
        return $this->hasMany(Prt_archivo::class, 'prt_seccion_id')->whereNull('prt_carpeta_id');
    }

}
