<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usr_submodulo extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id', 'usr_modulo_id', 'nombre', 'titulo', 'descripcion', 'estado'
    ];

    public function usr_modulo()
    {
        return $this->belongsTo(Usr_modulo::class, 'usr_modulo_id');
    }
}
