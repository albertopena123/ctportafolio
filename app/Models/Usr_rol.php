<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usr_privilegio;

class Usr_rol extends Model
{
    use HasFactory;

    protected $table = 'usr_roles';

    protected $fillable = [
        'nombre', 'descripcion', 'estado'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'usr_rol_id');
    }

    public function usr_privilegios()
    {
        return $this->hasMany(Usr_privilegio::class, 'usr_rol_id');
    }

    public function submodulos()
    {
        $privilegios = Usr_privilegio::where('usr_rol_id', $this->id)->with('usr_submodulo.usr_modulo')->get();

        //obtenemos solo los módulos en texto
        $mod_array = array();
        foreach ($privilegios as $privilegio) {
            $mod_text = $privilegio->usr_submodulo->usr_modulo->nombre;
            if(!in_array($mod_text, $mod_array))
                $mod_array[] = $mod_text;
        }
        //obtenemos los submodulos para cada modulo
        $modulos = array();
        foreach ($mod_array as $modulo) {
            $submodulos = array();
            foreach ($privilegios as $privilegio) {
                if($privilegio->usr_submodulo->usr_modulo->nombre == $modulo)
                    $submodulos[] = $privilegio->usr_submodulo->nombre;
            }
            $modulos[$modulo] = $submodulos;
        }
        return $modulos;//["MODULOS"=>["SUBMODULO1","SUBMODULO2"]]
    }
}
