<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prt_academ_carga;
use App\Models\Prt_portafolio;
use Validator;


class PortafolioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function listar(Request $request)
    { 
        $query = Prt_academ_carga::with(['prt_prof_escuela','prt_asignatura','prt_portafolios.usr_persona'])
                ->where('estado',1);

        if ($request->has('year')) {
            if($request->year != 0)
                $query->where('year', $request->year);
        }

        if ($request->has('semestre')) {
            if($request->semestre != 0)
                $query->where('semestre', $request->semestre);
        }
        
        if ($request->has('estado')) {
            if($request->estado != 0) {
                if($request->estado == 2){
                    $query->has('prt_portafolios');
                } elseif ($request->estado == 1) {
                    $query->doesntHave('prt_portafolios');
                }
            }
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

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [  
            'prt_academ_carga_id' => 'required',
            'grupo' => 'required',    
            'usr_persona_id' => 'required',
            'estado' => 'required',            
        ]); 
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $existe = Prt_portafolio::where('prt_academ_carga_id', $request->prt_academ_carga_id)
                    ->where('usr_persona_id', $request->usr_persona_id)
                    ->count();

            if($existe > 0) {
                return response()->json(['message'=>'El docente ya esta asignado a la carga académica.'], 500);         
            }


            $item = new Prt_portafolio;
            $item->prt_academ_carga_id = $request->prt_academ_carga_id;
            $item->grupo = $request->grupo;
            $item->usr_persona_id = $request->usr_persona_id;
            //$item->nombre = '';
            $item->observaciones = $request->observaciones;
            $item->estado = $request->estado;

            if($item->save())
                return response()->json(['message'=>'Registrado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo registrar'], 500);            
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    public function modificar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [            
            'grupo' => 'required',    
            'usr_persona_id' => 'required',
            'estado' => 'required',     
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = Prt_portafolio::find($id);           

            if($item)
            {                
                $item->grupo = $request->grupo;
                $item->usr_persona_id = $request->usr_persona_id;
                //$item->nombre = '';
                $item->observaciones = $request->observaciones;
                $item->estado = $request->estado;

                if($item->save())
                    return response()->json(['message'=>'Actualizado correctamente'], 200);
                else 
                    return response()->json(['message'=>'No se pudo actualizar'], 500);                
            }
            else
                return response()->json(['message'=>'No se pudo encontrar'], 500);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    public function eliminar(Request $request, $id)
    {
        $portafolio = Prt_portafolio::withCount(['prt_carpetas','prt_archivos'])->find($id);
      
        if($portafolio == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        if($portafolio->prt_carpetas_count > 0)
            return response()->json(['message'=>'El portafolio tiene carpetas registradas'], 500);     

        if($portafolio->prt_archivos_count > 0)
            return response()->json(['message'=>'El portafolio tiene archivos registrados'], 500);

        try 
        {
            if($portafolio->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }        
    }
}
