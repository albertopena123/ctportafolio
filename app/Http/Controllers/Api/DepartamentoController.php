<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prt_dept_academico;
use DataTables;
use Validator;

class DepartamentoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function listar(Request $request)
    { 
        $query = Prt_dept_academico::with('prt_facultad')->withCount(['prt_prof_escuelas','prt_asignaturas']); 
        if ($request->has('prt_facultad_id')) {
            if($request->prt_facultad_id != 0)
                $query->where('prt_facultad_id', $request->prt_facultad_id);
        }
        return DataTables::of($query)->toJson();
    }

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [     
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
            $item = new Prt_dept_academico;
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
            $item = Prt_dept_academico::find($id);           

            if($item)
            {           
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
        $departamento = Prt_dept_academico::withCount(['prt_prof_escuelas','prt_asignaturas'])->find($id);
      
        if($departamento == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        if($departamento->prt_prof_escuelas_count > 0)
            return response()->json(['message'=>'El departamentos académicos tiene escuelas profesiones registradas'], 500);   
        
        if($departamento->prt_asignaturas_count > 0)
            return response()->json(['message'=>'El departamentos académicos tiene asignaturas registradas'], 500);

        try 
        {
            if($departamento->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }    
        
    }
}
