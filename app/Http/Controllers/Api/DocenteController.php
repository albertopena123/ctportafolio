<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Prt_portafolio;
use App\Models\Prt_carpeta;
use App\Models\Prt_archivo;
use App\Models\Prt_seccion;
use DataTables;
use Validator;
use stdClass;

class DocenteController extends Controller
{
    protected $disco;

    public function __construct()
    {
        $this->middleware('auth');
        $this->disco = config('app.almacenamiento');
    }
   
    //Listar portafolios del usuario (docente)
    public function listar(Request $request)
    { 
        $user = Auth::user();

        $query = Prt_portafolio::with(['prt_academ_carga' => function ($query) {
            $query->with(['prt_prof_escuela','prt_asignatura']);
        }])
        ->whereHas('prt_academ_carga', function ($query) use ($request) {
            $query->where('year', '=', $request->year)->where('semestre', $request->semestre);
        })
        ->where('usr_persona_id', $user->usr_persona_id)
        ->where('estado', 1);        

        return DataTables::of($query)->toJson();
    }

    /**
     * LISTAR CARPETAS Y ARCHIVOS
     */
    public function archivos(Request $request)
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
            $user = Auth::user();
            //validamos portafolio pertenece a usuario
            $existe =  Prt_portafolio::where('usr_persona_id', $user->usr_persona_id)->where('id', $request->prt_portafolio_id)->count();
            if($existe == 0) {
                return response()->json(['message'=>'El portafolio no pertenece al docente.'], 500); 
            }

            //obtenemos actual
            if($request->prt_carpeta_id != 0) //se selecciona una carpeta
            {
                $carpeta_actual = Prt_carpeta::where('prt_portafolio_id',$request->prt_portafolio_id)->where('prt_seccion_id',$request->prt_seccion_id)->where('id',$request->prt_carpeta_id)->first();
                if($carpeta_actual == null) {
                    return response()->json(['message'=>'No se econtro la carpeta.'], 500); 
                }

                $actual = new stdClass();
                $actual->tipo = 1;//1:carpeta
                $actual->nombre = $carpeta_actual->nombre;  

                if($carpeta_actual->prt_carpeta_id == null) {//esta en raiz                    
                    $actual->anterior_id = 0;//carpeta 0
                } else {//esta dentro de otra carpeta
                    $actual->anterior_tipo = 1;
                    $actual->anterior_id = $carpeta_actual->prt_carpeta_id;
                }
            } 
            else //se selecciona una seccion
            {
                $seccion_actual = Prt_seccion::find($request->prt_seccion_id);
                if($seccion_actual == null) {
                    return response()->json(['message'=>'No se econtro la seccion.'], 500); 
                }

                $actual = new stdClass();
                $actual->tipo = 2;//2:seccion
                $actual->nombre = $seccion_actual->nombre;               
                $actual->anterior_id = null;//carpeta 0                
            }

            //obtenemos carpetas
            $query_carpetas = Prt_carpeta::where('prt_portafolio_id', $request->prt_portafolio_id)->where('prt_seccion_id', $request->prt_seccion_id);
            if($request->prt_carpeta_id != 0 && $request->prt_carpeta_id != null) {
                $query_carpetas->where('prt_carpeta_id', $request->prt_carpeta_id);
            } else {
                $query_carpetas->whereNull('prt_carpeta_id');
            }
            $carpetas = $query_carpetas->get();

            //obtenemos archivos
            $query_archivos = Prt_archivo::where('prt_portafolio_id', $request->prt_portafolio_id)->where('prt_seccion_id', $request->prt_seccion_id);
            if($request->prt_carpeta_id != 0 && $request->prt_carpeta_id != null) {
                $query_archivos->where('prt_carpeta_id', $request->prt_carpeta_id);
            } else {
                $query_archivos->whereNull('prt_carpeta_id');
            }
            $archivos = $query_archivos->get();

            return response()->json(['actual' => $actual, 'carpetas' => $carpetas, 'archivos' => $archivos ], 200);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    /**
     * EDITAR CARPETAS
     */
    public function carpeta_nuevo(Request $request)
    {
        $validator = Validator::make($request->all(), [              
            'prt_portafolio_id' => 'required',
            'prt_seccion_id' => 'required',
            'prt_carpeta_id' => 'required',
            'nombre' => 'required',  
            'estado' => 'required',            
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $user = Auth::user();
            $item = new Prt_carpeta;
            $item->prt_portafolio_id = $request->prt_portafolio_id;
            $item->prt_seccion_id = $request->prt_seccion_id;
            $item->prt_carpeta_id = ($request->prt_carpeta_id != 0 ? $request->prt_carpeta_id : null);
            $item->nombre = $request->nombre;
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

    public function carpeta_modificar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
            'prt_portafolio_id' => 'required',
            'nombre' => 'required',
            'estado' => 'required',   
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        try 
        {
            $item = Prt_carpeta::where('prt_portafolio_id', $request->prt_portafolio_id)->where('id', $id)->first();

            if($item)
            {           
                $item->nombre = $request->nombre;
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

    public function carpeta_eliminar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
            'prt_portafolio_id' => 'required',  
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $carpeta = Prt_carpeta::withCount(['prt_carpeta_hijos','prt_archivos'])->where('prt_portafolio_id', $request->prt_portafolio_id)->where('id', $id)->first();
      
        if($carpeta == null)
            return response()->json(['message'=>'No se pudo encontrar'], 500);

        if($carpeta->prt_carpeta_hijos_count > 0)
            return response()->json(['message'=>'La carpeta tiene sub carpetas registradas'], 500);   
        
        if($carpeta->prt_archivos_count > 0)
            return response()->json(['message'=>'La carpeta tiene archivos registrados'], 500);   

        try 
        {
            if($carpeta->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }        
    }

    public function carpetas(Request $request, $id) {

        $validator = Validator::make($request->all(), [     
            'prt_portafolio_id' => 'required'   
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $user = Auth::user();
        //validamos portafolio pertenece a usuario
        $existe =  Prt_portafolio::where('usr_persona_id', $user->usr_persona_id)->where('id', $request->prt_portafolio_id)->count();
        if($existe == 0) {
            return response()->json(['message'=>'El portafolio no pertenece al docente.'], 500); 
        }

        $seccion = Prt_seccion::find($id);
        if($seccion == null) {
            return response()->json(['message'=>'No se ecnontro la seccion.'], 500); 
        }

        $carpetas = Prt_carpeta::where('prt_portafolio_id',$request->prt_portafolio_id)->where('prt_seccion_id',$id)->get();
        $carpetas_ordenado = $this->ordenar_carpetas($carpetas, null, "");
        return response()->json([ "seccion" => $seccion, "carpetas" => $carpetas_ordenado], 200);        
    }

    protected function ordenar_carpetas($carpetas, $padre_id) 
    {
        $ordenado = collect(); 
        foreach ($carpetas as $carpeta) {
            if($carpeta->prt_carpeta_id == $padre_id){
                $elemento = new stdClass();
                $elemento->id = $carpeta->id;
                $elemento->prt_seccion_id = $carpeta->prt_seccion_id;
                $elemento->prt_carpeta_id = $carpeta->prt_carpeta_id;
                $elemento->nombre = $carpeta->nombre;
                $elemento->sub_carpeta = $this->ordenar_carpetas($carpetas, $carpeta->id);
                $ordenado->push($elemento);
            }
        }       
        return $ordenado;
    }

    /**
     * EDITAR ARCHIVO
     */
    public function archivo_nuevo(Request $request)
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
            $portafolio = Prt_portafolio::find($request->prt_portafolio_id);
            if ($portafolio == null) {
                return response()->json(['message'=>'No se encontro el portafolio'], 500);
            }

            $user = Auth::user(); 

            if($request->hasFile('archivos')) 
            {
                $archivos = $request->file('archivos');

                foreach ($archivos as $archivo_nuevo) {
                    //obtenemos la extension
                    if($archivo_nuevo->getClientOriginalExtension()!="")
                        $extension = strtolower($archivo_nuevo->getClientOriginalExtension());
                    else
                        $extension = strtolower($archivo_nuevo->extension());

                    //obtenemos el nombre
                    $nombre = $archivo_nuevo->getClientOriginalName();

                    //obtenemos el tamaño
                    $size = $archivo_nuevo->getSize();
                    //subimos el archivo y obtenemos la ruta
                    $ruta = Storage::disk($this->disco)->putFile('archivos', $archivo_nuevo);   
                    
                    $archivo = new Prt_archivo;
                    $archivo->prt_portafolio_id = $request->prt_portafolio_id;
                    $archivo->prt_seccion_id = $request->prt_seccion_id;
                    $archivo->prt_carpeta_id = ($request->prt_carpeta_id != 0 ? $request->prt_carpeta_id : null);
                    $archivo->nombre = $nombre;
                    $archivo->formato = $extension;
                    $archivo->size = $size;
                    $archivo->ruta = $ruta;
                    $archivo->nombre_real = basename($ruta);
                    $archivo->estado = 1;
                    $archivo->user_id = $user->id; 

                    if($archivo->save())
                    {
                        $archivo->codigo = $this->codigo_alpha($archivo->id + 1234);
                        $archivo->save();
                    }
                }

                if($portafolio->avance == 0) {
                    $portafolio->avance = 1; 
                    $portafolio->save();                
                } else {
                    $portafolio->touch();
                }
                
                return response()->json(['message'=>'Cargado correctamente'], 200);                
            }
            else
                return response()->json(['message'=>'No se encontro el Archivo'], 500);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }

    public function archivo_eliminar(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [ 
            'prt_portafolio_id' => 'required',  
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $archivo = Prt_archivo::where('prt_portafolio_id', $request->prt_portafolio_id)->where('id', $id)->first();
      
        if($archivo == null)
            return response()->json(['message'=>'No se pudo encontrar el archivo'], 500);        
        
        try 
        {
            Storage::disk($this->disco)->delete($archivo->ruta);
            
            if($archivo->delete())
                return response()->json(['message'=>'Eliminado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo eliminar'], 500); 
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }    
    }

    public function codigo_alpha($numero)
    {
        return str_pad($this->generar($numero), 12, "0", STR_PAD_LEFT);
    }
    
    public function generar($numero)//iterativo
    {
        $alphabet = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";//32
        $largo = strlen($alphabet);

        if($numero <= $largo) {
            $codigo = substr($alphabet,($numero-1),1);
            return $codigo;
        }
        else {
            $factor = floor($numero / $largo);
            $sobrante = $numero - ($largo * $factor);            
            $codigo = substr($alphabet,($sobrante-1),1);
            if($sobrante == 0) { $factor--; }
            return $this->generar($factor).$codigo;
        }        
    }

    public function archivo_mover(Request $request, $id)
    {     
        $validator = Validator::make($request->all(), [ 
            'prt_seccion_id' => 'required',  
            'prt_carpeta_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $archivo = Prt_archivo::where('prt_seccion_id', $request->prt_seccion_id)->where('id', $id)->first();

        if($archivo == null) {
            return response()->json(['message'=>'No se pudo encontrar el archivo'], 500);  
        }   
        
        if($archivo->prt_carpeta_id == $request->prt_carpeta_id) {
            return response()->json(['message'=>'El archivo ya se encuentra en la carpeta'], 500);
        }
        
        try 
        {            
            $archivo->prt_carpeta_id = ($request->prt_carpeta_id != 0 ? $request->prt_carpeta_id : null);                
            
            if($archivo->save())
                return response()->json(['message'=>'Actualizado correctamente'], 200);
            else 
                return response()->json(['message'=>'No se pudo actualizar'], 500);   
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage()], 500);
        }        
    }
}
