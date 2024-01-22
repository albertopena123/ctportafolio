<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usr_privilegio extends Model
{
    use HasFactory;

    public $incrementing = false;
    
    protected $fillable = [
        'usr_rol_id', 'usr_submodulo_id', 'usr_modulo_id'
    ];   

    public function usr_rol()
    {
        return $this->belongsTo(Usr_rol::class, 'usr_rol_id');
    }

    public function usr_submodulo()
    {
        return $this->belongsTo(Usr_submodulo::class, 'usr_submodulo_id');
    }
}
