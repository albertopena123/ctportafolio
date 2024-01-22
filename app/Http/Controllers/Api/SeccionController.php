<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Prt_seccion;
use Validator;
use stdClass;

class SeccionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   
    public function listar(Request $request)
    { 
        $secciones = Prt_seccion::withCount(['prt_carpetas','prt_archivos'])->orderBy('numero', 'asc')->get(); 
        $secciones_ordenado = $this->ordenar($secciones, null, "");
        
        return response()->json($secciones_ordenado, 200);
    }

    protected function ordenar($desordando, $padre_id, $acumulado) 
    {
        $ordenado = collect(); 
        foreach ($desordando as $seccion) {
            if($seccion->prt_seccion_id == $padre_id){
                $elemento = new stdClass();
                $elemento->id = $seccion->id;
                $elemento->prt_seccion_id = $seccion->prt_seccion_id;
                $elemento->numero = $seccion->numero;
                $elemento->numero_acumulado = $acumulado.$seccion->numero.".";
                $elemento->nombre = $seccion->nombre;
                $elemento->descripcion = $seccion->descripcion;
                $elemento->estado = $seccion->estado;
                $elemento->prt_carpetas_count = $seccion->prt_carpetas_count;
                $elemento->prt_archivos_count = $seccion->prt_archivos_count;
                $elemento->sub_seccion = $this->ordenar($desordando, $seccion->id, $acumulado.$seccion->numero.".");
                $ordenado->push($elemento);
            }
        }
       
        return $ordenado;
    }

    public function nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [     
            'numero' => 'required',       
            'nombre' => 'required', 
            'estado' => 'required',            
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = new Prt_seccion;
            $item->prt_seccion_id = $request->prt_seccion_id;
            $item->numero = $request->numero;
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
            'numero' => 'required',       
            'nombre' => 'required', 
            'estado' => 'required', 
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = Prt_seccion::find($id);           

            if($item)
            {           
                $item->numero = $request->numero;
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

    public function mover(Request $request, $id)
    {       
        try 
        {
            $item = Prt_seccion::find($id);           

            if($item)
            {
                //el destino no es el mismo y no esta dentro de si mismo
                if($request->prt_seccion_id != null) {

                    if($id == $request->prt_seccion_id) {
                        return response()->json(['message'=>'El destino no puede ser el mismo elemento'], 500);
                    }

                    $secciones = Prt_seccion::withCount(['prt_carpetas','prt_archivos'])->orderBy('numero', 'asc')->get(); 

                    $items = array();
                    $this->obtener_hijos($secciones, $items, $id);                    
                    $existe = false;
                    foreach ($items as $key => $value) {                        
                        if($request->prt_seccion_id == $value) {
                            $existe = true;
                        }
                    }

                    if($existe) {
                        return response()->json(['message'=>'El destino no puede estar incluido dentro del mismo elemento'], 500);
                    }                    
                }

                $item->prt_seccion_id = $request->prt_seccion_id;                

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

    
    protected function obtener_hijos($secciones, &$items, $padre_id) 
    {        
        foreach ($secciones as $seccion) {
            if($seccion->prt_seccion_id == $padre_id){
                $items[] = $seccion->id;               
                $this->obtener_hijos($secciones, $items, $seccion->id);
            }
        }       
    }
    

    public function eliminar(Request $request, $id)
    {
        $seccion = Prt_seccion::withCount(['prt_carpetas','prt_archivos','prt_seccion_hijos'])->find($id);
      
        if($seccion == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        if($seccion->prt_carpetas_count > 0)
            return response()->json(['message'=>'La sección tiene carpetas registradas'], 500);   
        
        if($seccion->prt_archivos_count > 0)
            return response()->json(['message'=>'La sección tiene archivos registrados'], 500);

        if($seccion->prt_seccion_hijos_count > 0)
            return response()->json(['message'=>'La sección tiene sub secciones registrados'], 500);

        try 
        {
            if($seccion->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }        
    }
}
