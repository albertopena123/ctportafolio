<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prt_carpeta;
use App\Models\Prt_seccion;
use App\Models\Prt_archivo;
use App\Models\Prt_portafolio;
use App\Models\Prt_academ_carga;
use Validator;
use stdClass;

class PublicoController extends Controller
{
    //
    public function navegar(Request $request)
    {
        $validator = Validator::make($request->all(), [     
            'prt_portafolio_id' => 'required',       
            'prt_seccion_id' => 'required',
            'prt_carpeta_id' => 'required'     
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            //Comrpobamos el protafolio
            $existe =  Prt_portafolio::where('id', $request->prt_portafolio_id)->where('estado', 1)->count();
            if($existe == 0) {
                return response()->json(['message'=>'El portafolio no se ecuentra activo'], 500); 
            }

            //obtemos las sub secciones
            if($request->prt_carpeta_id == 0) {
                $query_secciones = Prt_seccion::withCount(['prt_seccion_hijos','prt_carpetas_principales','prt_archivos_principales'])->orderBy('numero', 'asc')->where('estado', 1);
                if($request->prt_seccion_id != 0) {
                    $query_secciones->where('prt_seccion_id', $request->prt_seccion_id);
                } else {
                    $query_secciones->whereNull('prt_seccion_id');
                }
                $secciones = $query_secciones->get();
            } else {
                $secciones = [];
            }

            //obtenemos las carpetas
            if($request->prt_seccion_id != 0) {
                $query_carpetas = Prt_carpeta::withCount(['prt_carpeta_hijos','prt_archivos'])->where('prt_portafolio_id', $request->prt_portafolio_id)->where('prt_seccion_id', $request->prt_seccion_id);
                if($request->prt_carpeta_id != 0) {
                    $query_carpetas->where('prt_carpeta_id', $request->prt_carpeta_id);
                } else {
                    $query_carpetas->whereNull('prt_carpeta_id');
                }
                $carpetas = $query_carpetas->get();
            } else {
                $carpetas = [];
            }

            //obtenemos archivos
            if($request->prt_seccion_id != 0) {
                $query_archivos = Prt_archivo::where('prt_portafolio_id', $request->prt_portafolio_id)->where('prt_seccion_id', $request->prt_seccion_id);
                if($request->prt_carpeta_id != 0) {
                    $query_archivos->where('prt_carpeta_id', $request->prt_carpeta_id);
                } else {
                    $query_archivos->whereNull('prt_carpeta_id');
                }
                $archivos = $query_archivos->get();
            } else {
                $archivos = [];
            }
            
            /**
             * Obtenemos los elementos
             */

            $elementos = collect(); 

            //secciones
            foreach ($secciones as $seccion) 
            {
                $elemento = new stdClass();
                $elemento->tipo = 0;//0:seccion
                $elemento->prt_seccion_id = $seccion->id;
                $elemento->prt_carpeta_id = 0;                
                $elemento->nombre = $seccion->nombre;
                $elemento->codigo = null;
                $elemento->carpetas_count = $seccion->prt_seccion_hijos_count + $seccion->prt_carpetas_principales_count;
                $elemento->archivos_count = $seccion->prt_archivos_principales_count;
                $elemento->formato = null;
                $elemento->format_size = null;
                $elemento->created_at = $seccion->created_at;
                $elementos->push($elemento);
            }

            //carpetas
            foreach ($carpetas as $carpeta)
            {
                $elemento = new stdClass();
                $elemento->tipo = 1;//1:carpeta
                $elemento->prt_seccion_id = $carpeta->prt_seccion_id;
                $elemento->prt_carpeta_id = $carpeta->id;
                $elemento->nombre = $carpeta->nombre;
                $elemento->codigo = null;
                $elemento->carpetas_count = $carpeta->prt_carpeta_hijos_count;
                $elemento->archivos_count = $carpeta->prt_archivos_count;
                $elemento->formato = null;
                $elemento->format_size = null;
                $elemento->created_at = $carpeta->created_at;
                $elementos->push($elemento);
            }

            //archivos
            foreach ($archivos as $archivo) 
            {
                $elemento = new stdClass();
                $elemento->tipo = 2;//2:archivo
                $elemento->prt_seccion_id = $archivo->prt_seccion_id;
                $elemento->prt_carpeta_id = ($archivo->prt_carpeta_id ? $archivo->prt_carpeta_id : 0);
                $elemento->nombre = $archivo->nombre;
                $elemento->codigo = $archivo->codigo;
                $elemento->carpetas_count = 0;
                $elemento->archivos_count = 0;
                $elemento->formato = $archivo->formato;
                $elemento->format_size = $archivo->format_size;
                $elemento->created_at = $archivo->created_at;
                $elementos->push($elemento);
            }

            /**
             * Obtenemos la ruta
             */
            $rutas = collect(); 

            if($request->prt_seccion_id != 0){
                if($request->prt_carpeta_id != 0) {
                    //carpetas
                    $todas_carpetas = Prt_carpeta::where('prt_portafolio_id', $request->prt_portafolio_id)->where('prt_seccion_id', $request->prt_seccion_id)->get();
                    $this->rutas_carpetas($todas_carpetas, $rutas, $request->prt_carpeta_id);
                }
                //secciones
                $todas_secciones = Prt_seccion::where('estado', 1)->orderBy('numero', 'asc')->get();
                $this->rutas_secciones($todas_secciones, $rutas, $request->prt_seccion_id);                
            }

            return response()->json(['rutas' => $rutas, 'elementos' => $elementos ], 200);             
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    protected function rutas_secciones($secciones, &$rutas, $seccion_id)
    {
        foreach ($secciones as $seccion) {
            if($seccion->id == $seccion_id) {
                $elemento = new stdClass();
                $elemento->prt_seccion_id = $seccion->id;
                $elemento->prt_carpeta_id = 0;
                $elemento->nombre = $seccion->nombre;
                $rutas->push($elemento);
                $this->rutas_secciones($secciones, $rutas, $seccion->prt_seccion_id);
            }
        }
    }

    protected function rutas_carpetas($carpetas, &$rutas, $carpeta_id)
    {
        foreach ($carpetas as $carpeta) {
            if($carpeta->id == $carpeta_id) {
                $elemento = new stdClass();
                $elemento->prt_seccion_id = $carpeta->prt_seccion_id;
                $elemento->prt_carpeta_id = $carpeta->id;
                $elemento->nombre = $carpeta->nombre;
                $rutas->push($elemento);
                $this->rutas_carpetas($carpetas, $rutas, $carpeta->prt_carpeta_id);
            }
        }
    }

    public function portafolios(Request $request)
    { 
        $query = Prt_academ_carga::with(['prt_prof_escuela','prt_asignatura','prt_portafolios' => function ($query) {
                $query->with('usr_persona')->where('estado', 1);
            }])->where('estado',1);

        if ($request->has('year')) {
            if($request->year != 0)
                $query->where('year', $request->year);
        }

        if ($request->has('semestre')) {
            if($request->semestre != 0)
                $query->where('semestre', $request->semestre);
        }        
        
        if ($request->has('prt_prof_escuela_id')) {
            if($request->prt_prof_escuela_id != 0)
                $query->where('prt_prof_escuela_id', $request->prt_prof_escuela_id);
            else
                $query->where('prt_prof_escuela_id', 0);
        }

        if ($request->has('texto')) {
            if($request->texto != ''){
                $query->whereHas('prt_asignatura', function ($query) use ($request) {
                    $query->where('codigo', 'like', '%'.$request->texto.'%')
                        ->orWhere('nombre','like', '%'.$request->texto.'%');
                });  
            }
        }

        return $query->paginate(50);
    }


}
