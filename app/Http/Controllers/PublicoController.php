<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Prt_archivo;
use App\Models\Prt_portafolio;
use App\Models\Prt_facultad;
use Carbon\Carbon;

class PublicoController extends Controller
{
    public function index()
    {
        $ahora = Carbon::now();
        $facultades = Prt_facultad::with('prt_dept_academicos.prt_prof_escuelas')->where('estado',1)->get();
        return view('inicio',compact('ahora','facultades'));
    }
    
    public function portafolio(Request $request, $id)
    {
        $portafolio = Prt_portafolio::with(['usr_persona','prt_academ_carga' => function ($query) {
                        $query->with(['prt_prof_escuela.prt_dept_academico.prt_facultad','prt_asignatura']);
                    }])->where('estado', 1)->where('id', $id)->firstOrFail();                    
        return view('publico.portafolio_detalle',compact('portafolio')); 
    }

    public function descargar(Request $request, $codigo)
    {
        $archivo = Prt_archivo::where('codigo', $codigo)->firstOrFail();
        $disco = config('app.almacenamiento');      
        $headers = array();        

        $ruta = Storage::disk($disco)->path($archivo->ruta);

        if(!file_exists($ruta)){
            rabort(404);
        }

        return response()->download($ruta, $archivo->nombre, $headers);
    }
}
