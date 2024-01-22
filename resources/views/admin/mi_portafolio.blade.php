@extends('layouts.admin')
@section('titulo', 'Editar portafolio')

@section('css')
<link href="{{ asset('css/mi_portafolio.css?v='.config('app.version')) }}" rel="stylesheet"/>   
@endsection

@section('js')
<script>
    const secciones = {!! $secciones_ordenado !!};
    const elPortafolio = {{ $portafolio->id }};
</script>
<script src="{{ asset('js/mi_portafolio.js?v='.config('app.version')) }}" type="text/javascript"></script>
@endsection
@section('contenido')
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <div class="mb-0">
                    <ol class="breadcrumb" aria-label="breadcrumbs">
                        <li class="breadcrumb-item"><a href="{{ url('admin') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('admin/docente/portafolios') }}">Portafolio</a></li>                        
                        <li class="breadcrumb-item active" aria-current="page">Editar</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Editar portafolio
                </h2>
            </div>
            <div class="col-md-auto pt-2 pt-md-0">
                <div class="btn-list">                   
                    <a href="{{ url('admin/docente/portafolios') }}" class="btn btn-white" >
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
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-3">
                    <div class="card-body pb-2">
                        <div class="row">
                            <div class="col-md-1">
                                <dl class="mb-2">
                                    <dt>SEMESTRE</dt>
                                    <dd>{{ $portafolio->prt_academ_carga->year." - ".$portafolio->prt_academ_carga->semestre_text }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-4">
                                <dl class="mb-2">
                                    <dt>ESCUELA PROFESIONAL</dt>
                                    <dd>{{ $portafolio->prt_academ_carga->prt_prof_escuela->nombre }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-1">
                                <dl class="mb-2">
                                    <dt>CICLO</dt>
                                    <dd>{{ $portafolio->prt_academ_carga->ciclo_text }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-5">
                                <dl class="mb-2">
                                    <dt>ASIGNATURA</dt>
                                    <dd>{{ $portafolio->prt_academ_carga->prt_asignatura->codigo." - ".$portafolio->prt_academ_carga->prt_asignatura->nombre }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-1">
                                <dl class="mb-2">
                                    <dt>GRUPO</dt>
                                    <dd>{{ $portafolio->grupo }}</dd>
                                </dl>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-cards">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title">Secciones</h4>       
                    </div>
                    <div class="card-body">
                        <div id="lista_secciones"></div>
                    </div>
                </div>
            </div>            
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">                        
                        <div style="margin: -.5rem 0 -.5rem -.5rem;">
                            <button id="accion_back" onclick="retroceder();" class="btn btn-secondary btn-icon" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                            </button>                            
                        </div>      
                        <div id="titulo_icono" class="ps-3 pe-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                        </div>                  
                        <div class="flex-fill ps-1 pe-2">
                            <div id="titulo_texto" class="card-title lh-1">Seleccione un sección...</div>    
                        </div>                          
                        <div style="margin: -.5rem -.5rem -.5rem 0;">
                            <button class="btn btn-icon" onclick="actualizar();">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                            </button>
                        </div>    
                    </div>
                    <div class="card-body border-bottom py-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-fill">
                                <h2 class="m-0">Carpetas</h2>
                            </div>     
                            <div>
                                <button class="btn btn-success" onclick="nuevo_carpeta();">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                    Agregar
                                </button>
                            </div>                            
                        </div>
                        <div class="mb-3">
                            <div id="lista_carpetas" class="row">
                                <div class="col-12">
                                    <div class="card" style="background: #ebebeb;">
                                        <div class="card-body px-3 py-2 text-center text-muted">
                                            Seleccione una sección...
                                        </div>    
                                    </div>    
                                </div>                                 
                            </div>                            
                        </div>     
                        <div class="d-flex align-items-center">
                            <div class="flex-fill">
                                <h2 class="m-0">Archivos</h2>
                                <input id="input_subir" name="archivos[]" type="file" class="oculto" multiple="multiple" onchange="seleccion_archivos(this);">                                
                            </div>     
                            <div>
                                <button class="btn btn-success" onclick="nuevo_archivo();">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                    Agregar
                                </button>
                            </div>                            
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table card-table table-vcenter datatable">
                            <thead>
                                <tr>                                   
                                    <th>NOMBRE</th>
                                    <th class="w-1">FECHA</th>
                                    <th class="w-1">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody id="lista_archivos">                               
                                <tr>
                                    <td colspan="3" class="text-center text-muted">
                                       Seleccione una sección...
                                    </td>                                                                    
                                </tr>                                                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<!-- MODAL CARPETA -->
<div id="editar_carpeta" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="titulo_editar_carpeta" class="modal-title">Nuevo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="form_editar_carpeta" class="modal-body">                  
                <div class="form-group form-required mb-3">
                    <label class="form-label">Nombre</label>
                    <input id="nombre_carpeta" type="text" class="form-control mayuscula" placeholder="" maxlength="190">
                </div>
            </div>   
            <div class="modal-footer">
                <div>
                    <label class="form-check form-switch mb-0">
                        <input id="estado_carpeta" class="form-check-input" type="checkbox" checked="">
                        <span class="form-check-label">Visible</span>
                    </label>
                </div>
                <button type="button" class="btn ms-auto" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar_carpeta()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2"></path>
                        <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                        <path d="M14 4l0 4l-6 0l0 -4"></path>
                    </svg>
                    Guardar
                </button>
            </div>       
        </div>
    </div>
</div>

<!-- MODAL MOVER -->
<div id="mover" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mover archivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-2" style="position: relative;">  
                <ul id="lista_mover" class="lista_mover"></ul>                
                <div id="cargando_mover" class="cargando">
                    <div class="text-center pt-4">
                      <div class="spinner-border text-blue align-middle" role="status"></div> <b class="align-middle ps-1">Cargando...</b>
                    </div>
                </div> 
            </div>   
            <div class="modal-footer">
                <button type="button" class="btn ms-auto" data-bs-dismiss="modal">Cancelar</button>                
            </div>       
        </div>
    </div>
</div>
@endsection