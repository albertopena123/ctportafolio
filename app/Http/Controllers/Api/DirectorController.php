<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prt_dept_director;
use Carbon\Carbon;
use DataTables;
use Validator;

class DirectorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function listar(Request $request)
    {
        $query = Prt_dept_director::with(['prt_dept_academico.prt_facultad','usr_persona']);
        /*
        if ($request->has('prt_facultad_id')) {
            if($request->prt_facultad_id != 0)
                $query->where('prt_facultad_id', $request->prt_facultad_id);
        }

        if ($request->has('prt_dept_academico_id')) {
            if($request->prt_dept_academico_id != 0)
                $query->where('prt_dept_academico_id', $request->prt_dept_academico_id);
        }
        */

        return DataTables::of($query)->toJson();
    }

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [  
            'prt_dept_academico_id' => 'required',           
            'usr_persona_id' => 'required',            
            'estado' => 'required',
        ]);        
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }       

        try 
        {
            $user = Auth::user(); 

            $existe = Prt_dept_director::where('prt_dept_academico_id', $request->prt_dept_academico_id)->where('usr_persona_id', $request->usr_persona_id)->count();
            if($existe > 0) {
                return response()->json(['message'=>'El personal ya esta registrado como director.'], 500); 
            }

            $item = new Prt_dept_director;
            $item->prt_dept_academico_id = $request->prt_dept_academico_id;
            $item->usr_persona_id = $request->usr_persona_id;
            $item->inicio = $request->inicio;
            $item->estado = $request->estado;
            $item->user_id = $user->id;

            if($item->save())
                return response()->json(['message'=>'Registrado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo registrar'], 500);            
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    public function estado(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
            'estado' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = Prt_dept_director::find($id);           

            if($item) { 

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
        $director = Prt_dept_director::find($id);
      
        if($director == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        try 
        {
            if($director->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }        
    }
}
