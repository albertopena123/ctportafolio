@extends('layouts.admin')
@section('titulo', 'Escuelas profesionales')

@section('js')
<script src="{{ asset('lib/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script>
    const departamentos = {!! $departamentos !!};
</script>
<script src="{{ asset('js/escuelas.js?v='.config('app.version')) }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item active" aria-current="page">Escuelas</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Escuelas profesionales
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
                                    <div class="select_label_min">DEPARTAMENTOS</div>
                                    <select id="departamento_select" class="form-select" title="DEPARTAMENTOS">
                                        <option value="0">TODOS</option>                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="t_principal">
                        <table id="t_escuelas" class="table card-table table-vcenter text-nowrap datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>DEPARTAMENTO</th>
                                    <th>NOMBRE</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th title="CARGA ACADEMICA">CARG.</th>
                                    <th>ESTADO</th>
                                    <th>ACCIONES</th>     
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6">Cargando...</td>
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
                    <label class="form-label">Nombre</label>
                    <input id="nombre" type="text" class="form-control mayuscula" placeholder="" maxlength="190">
                </div> 
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Código</label>
                        <input id="codigo" type="text" class="form-control mayuscula" placeholder="" maxlength="20">
                    </div> 
                    <div class="col-md-6 form-group mb-3">
                        <label class="form-label">Abreviatura</label>
                        <input id="abreviatura" type="text" class="form-control mayuscula" placeholder="" maxlength="20">
                    </div>
                </div>
                <div class="form-group form-required">
                    <label class="form-label">Descripción</label>
                    <textarea id="descripcion" class="form-control mayuscula" rows="3"></textarea>
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