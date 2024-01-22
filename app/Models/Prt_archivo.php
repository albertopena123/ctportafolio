<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prt_archivo extends Model
{
    use HasFactory;

    protected $appends = [ 'format_size', 'ruta_publica', 'ruta_storage' ];

    protected $fillable = [
        'prt_seccion_id', 'prt_portafolio_id', 'prt_carpeta_id', 'codigo', 'nombre', 'formato', 'size', 'ruta', 'nombre_real', 'descripcion', 'estado', 'user_id'
    ];

    public function getFormatSizeAttribute()
    {  
        if ($this->size >= 1073741824)
        {
            return number_format($this->size / 1073741824, 2) . ' GB';
        }
        elseif ($this->size >= 1048576)
        {
            return number_format($this->size / 1048576, 2) . ' MB';
        }
        elseif ($this->size >= 1024)
        {
            return number_format($this->size / 1024, 2) . ' KB';
        }
        elseif ($this->size > 1)
        {
            return $this->size.' bytes';
        }
        elseif ($this->size == 1)
        {
            return $this->size.' byte';
        }
        else
        {
            return '0 bytes';
        }
    }

    public function getRutaPublicaAttribute()
    {
        return 'storage/'.$this->ruta;
    }

    public function getRutaStorageAttribute()
    {
        return 'app/public/'.$this->ruta;
    }

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
    
}
