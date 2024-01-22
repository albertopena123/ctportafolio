<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prt_facultad;
use DataTables;
use Validator;

class FacultadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function listar(Request $request)
    {
        $query = Prt_facultad::withCount(['prt_dept_academicos']); 
        return DataTables::of($query)->toJson();
    }

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [              
            'nombre' => 'required',
            'descripcion' => 'required',  
            'estado' => 'required',            
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = new Prt_facultad;
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
            'nombre' => 'required',
            'descripcion' => 'required',  
            'estado' => 'required',   
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = Prt_facultad::find($id);           

            if($item)
            {           
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
        $facultad = Prt_facultad::withCount(['prt_dept_academicos'])->find($id);
      
        if($facultad == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        if($facultad->prt_dept_academicos_count > 0)
            return response()->json(['message'=>'La facultad tiene departamentos académicos registrados'], 500);     

        try 
        {
            if($facultad->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }        
    }
}
