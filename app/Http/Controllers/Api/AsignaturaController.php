<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prt_asignatura;
use DataTables;
use Validator;

class AsignaturaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function listar(Request $request)
    {
        $query = Prt_asignatura::with('prt_dept_academico')->withCount(['prt_academ_cargas']);

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
            'codigo' => 'required',
            'nombre' => 'required',
            'estado' => 'required',            
        ]);        
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = new Prt_asignatura;
            $item->prt_dept_academico_id = $request->prt_dept_academico_id;
            $item->prt_facultad_id = $request->prt_facultad_id;
            $item->codigo = $request->codigo;
            $item->nombre = $request->nombre;
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
            'codigo' => 'required',
            'nombre' => 'required',
            'estado' => 'required',     
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = Prt_asignatura::find($id);           

            if($item)
            {           
                $item->prt_dept_academico_id = $request->prt_dept_academico_id;
                $item->prt_facultad_id = $request->prt_facultad_id;
                $item->codigo = $request->codigo;
                $item->nombre = $request->nombre;
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
        $asignatura = Prt_asignatura::withCount(['prt_academ_cargas'])->find($id);
      
        if($asignatura == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        if($asignatura->prt_academ_cargas_count > 0)
            return response()->json(['message'=>'La asignatura tiene carga académica registrada'], 500);     

        try 
        {
            if($asignatura->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }        
    }

    public function buscar(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'term' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $result = Prt_asignatura::where('estado', 1)
        ->where(function ($query) use ($request) {
            $query->where('codigo','like', '%'.$request->input('term').'%')
                ->orWhere('nombre','like', '%'.$request->input('term').'%');
        })->get();                              
        return response()->json($result, 200);
    }
}
