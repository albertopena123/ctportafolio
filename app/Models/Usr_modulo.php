<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usr_modulo extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'nombre', 'titulo', 'descripcion', 'ruta', 'estado'
    ];

    public function usr_submodulos()
    {
        return $this->hasMany(Usr_submodulo::class, 'usr_modulo_id');
    }
}
