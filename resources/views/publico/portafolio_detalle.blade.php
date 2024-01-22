@extends('layouts.publico')
@section('titulo', 'Sistema de gestión de personal')

@section('js')
<script src="{{ asset('lib/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script>
    const elPortafolio = {{ $portafolio->id }};
</script>
<script src="{{ asset('js/publico/portafolio_detalle.js?v='.config('app.version')) }}" type="text/javascript"></script>
@endsection

@section('contenido')
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <div class="mb-0">
                    <ol class="breadcrumb" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Portafolio</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Portafolio de docente
                </h2>
            </div>  
            <div class="col-md-auto pt-2 pt-md-0">
                <div class="btn-list">                   
                    <a href="{{ url('/') }}" class="btn btn-white" >
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                        Regresar                                        
                    </a>    
                </div>
            </div>          
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Detalle de portafolio</h3>
                      </div>
                    <div class="card-body">
                        <dl class="mb-2">
                            <dt>Facultad</dt>
                            <dd>{{ $portafolio->prt_academ_carga->prt_prof_escuela->prt_dept_academico->prt_facultad->nombre }}</dd>
                            <dt>Departamento académico</dt>
                            <dd>{{ $portafolio->prt_academ_carga->prt_prof_escuela->prt_dept_academico->nombre }}</dd>
                            <dt>Escuela profesional</dt>
                            <dd>{{ $portafolio->prt_academ_carga->prt_prof_escuela->nombre }}</dd>
                        </dl>
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="mb-2">                                    
                                    <dt>Semestre</dt>
                                    <dd>{{ $portafolio->prt_academ_carga->year }} - {{ $portafolio->prt_academ_carga->semestre_text }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="mb-2">                                    
                                    <dt>Ciclo</dt>
                                    <dd>{{ $portafolio->prt_academ_carga->ciclo_text }}</dd>
                                </dl>
                            </div>
                        </div>
                        <dl class="mb-2">
                            <dt>Asignatura</dt>
                            <dd>{{ $portafolio->prt_academ_carga->prt_asignatura->nombre }}</dd>
                        </dl>
                        <div class="row">
                            <div class="col-md-6">
                                <dl class="mb-2">                                    
                                    <dt>Código asginatura</dt>
                                    <dd>{{ $portafolio->prt_academ_carga->prt_asignatura->codigo }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="mb-2">                                    
                                    <dt>Grupo</dt>
                                    <dd>{{ $portafolio->grupo }}</dd>
                                </dl>
                            </div>
                        </div>
                        <dl class="mb-2">
                            <dt>Docente</dt>
                            <dd>{{ $portafolio->usr_persona->nombre }} {{ $portafolio->usr_persona->apaterno }} {{ $portafolio->usr_persona->amaterno }}</dd>
                        </dl>
                        
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header">
                      <h3 class="card-title">Registro de eventos</h3>
                    </div>
                    <div class="list-group list-group-flush list-group-hoverable">
                        <div class="list-group-item py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-muted">Carga acádemica</div>
                                <div class="text-muted">{{ $portafolio->prt_academ_carga->created_at->format('d/m/Y')}}</div>
                            </div>
                        </div>
                        <div class="list-group-item py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-muted">Creación portafolio</div>
                                <div class="text-muted">{{ $portafolio->created_at->format('d/m/Y')}}</div>
                            </div>
                        </div>
                        <div class="list-group-item py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="text-muted">Última actualización</div>
                                <div class="text-muted">{{ $portafolio->updated_at->format('d/m/Y')}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8"> 
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Archivos</h3>
                    </div>
                    <div class="card-body p-3" style="background: #f7f7f7;">
                        <ol id="lista_rutas" class="breadcrumb" aria-label="breadcrumbs">
                            <li class="breadcrumb-item active" aria-current="page"><a href="#">INICIO</a></li>
                        </ol>
                    </div>
                    <div id="lista_elementos" class="list-group list-group-flush list-group-hoverable">
                        <div class="p-3">
                            <div class="card" style="background: #ebebeb;">
                                <div class="card-body px-3 py-2 text-center text-muted">No se encontraron registros</div>
                            </div>
                        </div>                      
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection