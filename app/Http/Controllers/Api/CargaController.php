<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prt_academ_carga;
use DataTables;
use Validator;

class CargaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function listar(Request $request)
    { 
        $query = Prt_academ_carga::with(['prt_prof_escuela','prt_asignatura'])->withCount(['prt_portafolios']);

        if ($request->has('year')) {
            if($request->year != 0)
                $query->where('year', $request->year);
        }

        if ($request->has('semestre')) {
            if($request->semestre != 0)
                $query->where('semestre', $request->semestre);
        }

        if ($request->has('prt_facultad_id')) {
            if($request->prt_facultad_id != 0)
                $query->where('prt_facultad_id', $request->prt_facultad_id);
        }        

        if ($request->has('prt_prof_escuela_id')) {
            if($request->prt_prof_escuela_id != 0)
                $query->where('prt_prof_escuela_id', $request->prt_prof_escuela_id);
        }

        return DataTables::of($query)->toJson();
    }

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [  
            'year' => 'required',
            'semestre' => 'required',    
            'prt_prof_escuela_id' => 'required',
            'prt_dept_academico_id' => 'required',        
            'prt_facultad_id' => 'required',
            'ciclo' => 'required',  
            'prt_asignatura_id' => 'required', 
            'estado' => 'required',            
        ]); 
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $existe = Prt_academ_carga::where('year', $request->year)
                    ->where('semestre', $request->semestre)
                    ->where('prt_prof_escuela_id', $request->prt_prof_escuela_id)
                    ->where('ciclo', $request->ciclo)
                    ->where('prt_asignatura_id', $request->prt_asignatura_id)
                    ->count();

            if($existe > 0) {
                return response()->json(['message'=>'Ya existe una carga académica para AÑO/SEMESTRE/ESCUELA/CICLO/ASIGNATURA'], 500);         
            }


            $item = new Prt_academ_carga;
            $item->year = $request->year;
            $item->semestre = $request->semestre;
            $item->prt_prof_escuela_id = $request->prt_prof_escuela_id;
            $item->prt_dept_academico_id = $request->prt_dept_academico_id;
            $item->prt_facultad_id = $request->prt_facultad_id;
            $item->ciclo = $request->ciclo;
            $item->prt_asignatura_id = $request->prt_asignatura_id;
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
            'year' => 'required',
            'semestre' => 'required',    
            'prt_prof_escuela_id' => 'required',
            'prt_dept_academico_id' => 'required',        
            'prt_facultad_id' => 'required',
            'ciclo' => 'required',  
            'prt_asignatura_id' => 'required', 
            'estado' => 'required',    
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = Prt_academ_carga::find($id);           

            if($item)
            {           
                $item->year = $request->year;
                $item->semestre = $request->semestre;
                $item->prt_prof_escuela_id = $request->prt_prof_escuela_id;
                $item->prt_dept_academico_id = $request->prt_dept_academico_id;
                $item->prt_facultad_id = $request->prt_facultad_id;
                $item->ciclo = $request->ciclo;
                $item->prt_asignatura_id = $request->prt_asignatura_id;
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
        $carga = Prt_academ_carga::withCount(['prt_portafolios'])->find($id);
      
        if($carga == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        if($carga->prt_portafolios_count > 0)
            return response()->json(['message'=>'La carga académica tiene portafolios registrados'], 500);     

        try 
        {
            if($carga->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }        
    }
}
