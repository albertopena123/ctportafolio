@extends('layouts.admin')
@section('titulo', 'Secciones')

@section('css')
<link href="{{ asset('css/secciones.css?v='.config('app.version')) }}" rel="stylesheet"/>   
@endsection

@section('js')
<script>
    var lista_secciones = [];
</script>
<script src="{{ asset('js/secciones.js?v='.config('app.version')) }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item active" aria-current="page">Secciones</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Secciones
                </h2>
            </div>
            <div class="col-md-auto pt-2 pt-md-0">
                <div class="btn-list">            
                    <button onclick="obtener_secciones();"  type="button" class="btn btn-white" >
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                        Actualizar                                        
                    </button>       
                    <button onclick="nuevo('','PRINCIPAL');"  class="btn btn-primary" >
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
            <div class="col-md-12">
                <div id="lista_secciones">

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
                <div class="row">
                    <div class="col-md-9 form-group mb-3">
                        <label class="form-label">Padre</label>
                        <input id="prt_seccion_id" type="hidden">
                        <input id="prt_seccion_padre" type="text" class="form-control" placeholder="" maxlength="190" disabled>
                    </div> 
                    <div class="col-md-3 form-group form-required mb-3">
                        <label class="form-label">Orden</label>
                        <input id="numero" type="text" class="form-control validar_numero" placeholder="" maxlength="190">
                    </div> 
                </div>
                <div class="form-group form-required mb-3">
                    <label class="form-label">Nombre</label>
                    <input id="nombre" type="text" class="form-control mayuscula" placeholder="" maxlength="190">
                </div> 
                <div class="form-group">
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

<!-- MODAL MOVER -->
<div id="mover" class="modal modal-blur fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mover a sección</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">  
                <ul class="lista_mover mb-0">
                    <li class="item_mover">
                        <a href="javascript:void(0)" class="accion_mover" onclick="guardar_mover(0)">PRINCIPAL</a>
                        <ul id="lista_mover" class="lista_mover"></ul>
                    </li>
                </ul>
            </div>   
            <div class="modal-footer">
                <button type="button" class="btn ms-auto" data-bs-dismiss="modal">Cancelar</button>                
            </div>       
        </div>
    </div>
</div>
@endsection