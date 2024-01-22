<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prt_prof_escuela;
use DataTables;
use Validator;

class EscuelaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function listar(Request $request)
    {
        $query = Prt_prof_escuela::with('prt_dept_academico')->withCount(['prt_academ_cargas']);

        if ($request->has('prt_facultad_id')) {
            if($request->prt_facultad_id != 0)
                $query->where('prt_facultad_id', $request->prt_facultad_id);
        }

        if ($request->has('prt_dept_academico_id')) {
            if($request->prt_dept_academico_id != 0)
                $query->where('prt_dept_academico_id', $request->prt_dept_academico_id);
        }

        return DataTables::of($query)->toJson();
    }

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [  
            'prt_dept_academico_id' => 'required',
            'prt_facultad_id' => 'required',            
            'nombre' => 'required',
            'descripcion' => 'required',  
            'estado' => 'required',            
        ]);        
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = new Prt_prof_escuela;
            $item->prt_dept_academico_id = $request->prt_dept_academico_id;
            $item->prt_facultad_id = $request->prt_facultad_id;
            $item->codigo = $request->codigo;
            $item->nombre = $request->nombre;
            $item->abreviatura = $request->abreviatura;
            $item->descripcion = $request->descripcion;
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
            'prt_dept_academico_id' => 'required',
            'prt_facultad_id' => 'required',            
            'nombre' => 'required',
            'descripcion' => 'required',  
            'estado' => 'required',    
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = Prt_prof_escuela::find($id);           

            if($item)
            {           
                $item->prt_dept_academico_id = $request->prt_dept_academico_id;
                $item->prt_facultad_id = $request->prt_facultad_id;
                $item->codigo = $request->codigo;
                $item->nombre = $request->nombre;
                $item->abreviatura = $request->abreviatura;
                $item->descripcion = $request->descripcion;
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
        $escuela = Prt_prof_escuela::withCount(['prt_academ_cargas'])->find($id);
      
        if($escuela == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        if($escuela->prt_academ_cargas_count > 0)
            return response()->json(['message'=>'La escuela profesional tiene carga académica registrada'], 500);     

        try 
        {
            if($escuela->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }        
    }
}
