<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Usr_documento_tipo;
use App\Models\Prt_facultad;
use App\Models\Prt_dept_academico;
use App\Models\Prt_prof_escuela;
use App\Models\Prt_portafolio;
use App\Models\Prt_seccion;
use App\Models\Prt_dept_director;
use Carbon\Carbon;
use stdClass;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $disco = config('app.disco', 'D:');
        $total_disk = disk_total_space($disco);
        $free_disk = disk_free_space($disco);
        $used_disk = $total_disk - $free_disk;
        $almacenamiento = array(
            'disco' => $disco, 
            'total_disk' => $total_disk, 
            'free_disk' => $free_disk, 
            'used_disk' => $used_disk, 
            'total_disk_text' => $this->bytes_format($total_disk),
            'free_disk_text' => $this->bytes_format($free_disk),
            'used_disk_text' => $this->bytes_format($used_disk),
            'porcentaje' => round($used_disk * 100 / $total_disk)
        );

        //sistema operativo
        $s_operativo = php_uname();

        return view('admin.index', compact('almacenamiento','s_operativo'));
    }

    protected function bytes_format($size)
    {
        if ($size >= 1073741824)
        {
            return number_format($size / 1073741824, 2) . ' GB';
        }
        elseif ($size >= 1048576)
        {
            return number_format($size / 1048576, 2) . ' MB';
        }
        elseif ($size >= 1024)
        {
            return number_format($size / 1024, 2) . ' KB';
        }
        elseif ($size > 1)
        {
            return $size.' bytes';
        }
        elseif ($size == 1)
        {
            return $size.' byte';
        }
        else
        {
            return '0 bytes';
        }
    }

    /**
     * MANTENIMIENTO
     */
    public function facultades(Request $request) {
        return view('admin.facultades');
    }

    public function departamentos(Request $request) {
        $facultades = Prt_facultad::where('estado',1)->get();
        return view('admin.departamentos', compact('facultades'));
    }

    public function escuelas(Request $request) {
        $facultades = Prt_facultad::where('estado',1)->get();
        $departamentos = Prt_dept_academico::where('estado',1)->get();
        return view('admin.escuelas', compact('facultades','departamentos'));
    }

    public function asignaturas(Request $request) {
        $facultades = Prt_facultad::where('estado',1)->get();
        $departamentos = Prt_dept_academico::where('estado',1)->get();
        return view('admin.asignaturas', compact('facultades','departamentos'));
    }

    public function directores(Request $request) {
        $facultades = Prt_facultad::with('prt_dept_academicos')->where('estado',1)->get();
        return view('admin.directores', compact('facultades'));
    }

    public function secciones(Request $request) {
        return view('admin.secciones');
    }

    /**
     * CARGA ACADEMICA
     */
    public function carga_academica(Request $request) {
        $ahora = Carbon::now();
        $facultades = Prt_facultad::where('estado',1)->get();
        $departamentos = Prt_dept_academico::where('estado',1)->get();
        $escuelas = Prt_prof_escuela::where('estado',1)->get();
        return view('admin.carga',compact('ahora','facultades','departamentos','escuelas'));
    }


    /**
     * PORTAFOLIOS
     */
    public function portafolios(Request $request) {
        $ahora = Carbon::now();
        $user = Auth::user();
        $asignados = Prt_dept_director::with('prt_dept_academico.prt_prof_escuelas')->where('usr_persona_id', $user->usr_persona_id)->where('estado',1)->get();       
        return view('admin.portafolios',compact('ahora','asignados'));
    }


    /**
     * MI PORTAFOLIO
     */
    public function mis_portafolios(Request $request) {
        $ahora = Carbon::now();
        $user = User::with('usr_persona')->find(Auth::id());
        return view('admin.mis_portafolios',compact('ahora','user'));
    }

    public function mi_portafolio(Request $request, $id) {
        $user = Auth::user();
        $portafolio = Prt_portafolio::with(['prt_academ_carga' => function ($query) {
                        $query->with(['prt_prof_escuela','prt_asignatura']);
                    }])->where('usr_persona_id', $user->usr_persona_id)->where('id', $id)->firstOrFail();      
        $secciones = Prt_seccion::orderBy('numero', 'asc')->get(); 
        $secciones_ordenado = $this->ordenar($secciones, null, "");
        return view('admin.mi_portafolio',compact('portafolio','user','secciones_ordenado'));
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
                $elemento->sub_seccion = $this->ordenar($desordando, $seccion->id, $acumulado.$seccion->numero.".");
                $ordenado->push($elemento);
            }
        }

        return $ordenado;
    }


}
