<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prt_portafolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'prt_academ_carga_id', 'grupo', 'usr_persona_id', 'nombre', 'observaciones', 'avance', 'estado'
    ];

    public function prt_academ_carga()
    {
        return $this->belongsTo(Prt_academ_carga::class, 'prt_academ_carga_id');
    }    

    public function usr_persona()
    {
        return $this->belongsTo(Usr_persona::class, 'usr_persona_id');
    }

    public function prt_carpetas()
    {
        return $this->hasMany(Prt_carpeta::class, 'prt_portafolio_id');
    }

    public function prt_archivos()
    {
        return $this->hasMany(Prt_archivo::class, 'prt_portafolio_id');
    }

}
