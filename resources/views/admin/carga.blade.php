@extends('layouts.admin')
@section('titulo', 'Carga académica')

@section('css')
<link href="{{ asset('lib/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('lib/select2/css/select2-bootstrap4.min.css') }}" rel="stylesheet">
@endsection

@section('js')
<script src="{{ asset('lib/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('lib/select2/js/i18n/es.js') }}" type="text/javascript"></script>
<script>
    const departamentos = {!! $departamentos !!};
    const escuelas = {!! $escuelas !!};
</script>
<script src="{{ asset('js/carga_academica.js?v='.config('app.version')) }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item active" aria-current="page">Carga académica</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Carga académica
                </h2>
            </div>
            <div class="col-md-auto pt-2 pt-md-0">
                <div class="btn-list">                   
                    <button onclick="nuevo();"  class="btn btn-primary" >
	                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" /></svg>
                        Agregar
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
                    <div class="card-header pb-1 bg_header">
                        <div class="w-100">
                            <div class="row">
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
                                    <div class="select_label_min">FACULTADES</div>
                                    <select id="facultad_select" class="form-select" title="FACULTADES">
                                        <option value="0">TODOS</option>
                                        @foreach ($facultades as $facultad)
                                        <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 select_label_container mb-2">
                                    <div class="select_label_min">ESCUELAS PROFESIONALES</div>
                                    <select id="escuelas_select" class="form-select" title="FACULTADES">
                                        <option value="0">TODOS</option>                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="t_principal">
                        <table id="t_carga" class="table card-table table-vcenter text-nowrap datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>ESCUELA PROFESIONAL</th>
                                    <th>CICLO</th>
                                    <th></th>
                                    <th>ASIGNATURA</th>
                                    <th title="PORTAFOLIOS">PORT.</th>
                                    <th>ESTADO</th>
                                    <th>ACCIONES</th>     
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7">Cargando...</td>
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
<!-- MODAL MODIFICAR -->
<div id="editar" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="titulo_editar" class="modal-title">Nuevo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="form_editar" class="modal-body">                                   
                <div class="form-group form-required mb-3">
                    <label class="form-label">Facultad</label>
                    <select id="prt_facultad_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>
                        @foreach ($facultades as $facultad)
                        <option value="{{ $facultad->id }}">{{ $facultad->nombre }}</option>
                        @endforeach
                    </select>
                </div>                                   
                <div class="form-group form-required mb-3">
                    <label class="form-label">Departamento académico</label>
                    <select id="prt_dept_academico_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>                        
                    </select>
                </div>              
                <div class="form-group form-required mb-3">
                    <label class="form-label">Escuela profesional</label>
                    <select id="prt_prof_escuela_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>                        
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group form-required mb-3">
                        <label class="form-label">Año</label>                        
                        <select id="year" class="form-select validar_select" title="AÑO">
                            <option value="0">Seleccione...</option>
                            @for ($i = 0; $i < 7; $i++)
                            <option value="{{ $ahora->year - $i}}">{{ $ahora->year - $i}}</option>
                            @endfor
                        </select>
                    </div> 
                    <div class="col-md-4 form-group form-required mb-3">
                        <label class="form-label">Semestre</label>                        
                        <select id="semestre" class="form-select validar_select" title="SEMESTRE">
                            <option value="0">Seleccione...</option>
                            <option value="1">I - PRIMERO</option>    
                            <option value="2">II - SEGUNDO</option> 
                        </select>
                    </div>
                    <div class="col-md-4 form-group form-required mb-3">
                        <label class="form-label">Ciclo</label>
                        <select id="ciclo" class="form-select validar_select_1">
                            <option value="-1">Seleccione...</option>
                            <option value="0">0 - CERO</option>
                            <option value="1">I - PRIMERO</option>    
                            <option value="2">II - SEGUNDO</option>    
                            <option value="3">III - TERCERO</option>    
                            <option value="4">IV - CUARTO</option>    
                            <option value="5">V - QUINTO</option>    
                            <option value="6">VI - SEXTO</option>    
                            <option value="7">VII - SEPTIMO</option>    
                            <option value="8">VIII - OCTAVO</option>    
                            <option value="9">IX - NOVENO</option>  
                            <option value="10">X - DECIMO</option>                 
                        </select>
                    </div>
                </div>
                <div class="form-group form-required mb-3">
                    <label class="form-label">Asignatura</label>
                    <select id="prt_asignatura_id" class="form-select validar_select">
                        <option value="0">Seleccione...</option>                        
                    </select>
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