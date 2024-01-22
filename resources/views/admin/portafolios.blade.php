@extends('layouts.admin')
@section('titulo', 'Gestión de Portafolio')

@section('css')
<link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('lib/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/portafolios.css?v='.config('app.version')) }}" rel="stylesheet"/>   
@endsection

@section('js')
<script src="{{ asset('lib/select2/js/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/i18n/es.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/portafolios.js?v='.config('app.version')) }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item active" aria-current="page">Portafolios</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Gestión de Portafolio
                </h2>
            </div>
            <div class="col-md-auto pt-2 pt-md-0">
                <div class="btn-list">                   
                    <button onclick="actualizar();"  type="button" class="btn btn-white" >
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                        Actualizar                                        
                    </button>    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12">
                <div class="card">
                    <div class="card-body pb-2">
                        <div class="row">
                            <input type="hidden" id="prt_academ_carga_id" value="0">
                            <div class="col-md-2 select_label_container mb-2">
                                <div class="select_label_min">AÑO</div>
                                <select id="year_select" class="form-select" title="AÑO">
                                    @for ($i = 0; $i < 7; $i++)
                                    <option value="{{ $ahora->year - $i}}">{{ $ahora->year - $i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-2 select_label_container mb-2">
                                <div class="select_label_min">SEMESTRE</div>
                                <select id="semestre_select" class="form-select" title="SEMESTRE">
                                    <option value="1">I - PRIMERO</option>    
                                    <option value="2" {{ $ahora->month > 6 ? 'selected' : '' }}>II - SEGUNDO</option>                                       
                                </select>
                            </div>                            
                            <div class="col-md-3 select_label_container mb-2">
                                <div class="select_label_min">ESCUELAS PROFESIONALES</div>
                                <select id="escuelas_select" class="form-select" title="FACULTADES">
                                    <option value="0">SELECCIONE...</option>
                                    @if(count($asignados) > 0)
                                        @foreach ($asignados as $asignado)
                                            @foreach ($asignado->prt_dept_academico->prt_prof_escuelas as $escuela)
                                            <option value="{{ $escuela->id }}">{{ $escuela->nombre }}</option>
                                            @endforeach
                                        @endforeach     
                                    @else
                                    <option value="0">NO TIENES ASIGNADO DEPARTAMENTO COMO DIRECTOR</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-2 select_label_container mb-2">
                                <div class="select_label_min">ESTADO</div>
                                <select id="estado_select" class="form-select" title="ESTADO">
                                    <option value="0">TODOS</option>            
                                    <option value="2">ASIGNADOS</option>         
                                    <option value="1">SIN ASIGNAR</option>                                 
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div class="row g-2">
                                    <div class="col">
                                      <input id="texto_buscar" type="text" class="form-control" placeholder="Buscar…">
                                    </div>
                                    <div class="col-auto">
                                        <button onclick="buscar_texto()" class="btn btn-secondary btn-icon" aria-label="Button">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div id="lista_portafolios"></div>
            </div>
            <div class="col-12" id="paginacion">                
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<!-- MODAL MODIFICAR -->
<div id="editar" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="titulo_editar" class="modal-title">Nuevo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="form_editar" class="modal-body">                                   
                <div class="form-group mb-3">
                    <label class="form-label">Escuela profesional</label>
                    <input type="text" id="prt_prof_escuela" class="form-control" value="" disabled>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Año</label>                        
                        <input type="text" id="year" class="form-control" value="" disabled>                  
                    </div> 
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Semestre</label>                        
                        <input type="text" id="semestre" class="form-control" value="" disabled>                
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label class="form-label">Ciclo</label>
                        <input type="text" id="ciclo" class="form-control" value="" disabled>  
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Asignatura</label>
                    <input type="text" id="prt_asignatura" class="form-control" value="" disabled>  
                </div>
                <div class="row">
                    <div class="col-md-4 form-group form-required mb-3">
                        <label class="form-label">Grupo</label>                        
                        <input type="text" id="grupo" class="form-control mayuscula" maxlength="10">                
                    </div>
                    <div class="col-md-8 form-group form-required mb-3">
                        <label class="form-label">Docente</label>
                        <select id="usr_persona_id" class="form-select validar_select">
                            <option value="0">Seleccione...</option>                           
                        </select>              
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Oservaciones</label>
                    <textarea id="observaciones" class="form-control mayuscula" rows="2"></textarea>
                </div>                            
            </div>   
            <div class="modal-footer">
                <div>
                    <label class="form-check form-switch mb-0">
                        <input id="estado" class="form-check-input" type="checkbox" checked="">
                        <span class="form-check-label">Visible</span>
                    </label>
                </div>
                <button type="button" class="btn ms-auto" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="guardar()">
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
@endsection
