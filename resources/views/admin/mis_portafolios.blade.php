@extends('layouts.admin')
@section('titulo', 'Carga académica')

@section('js')
<script src="{{ asset('lib/datatables/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/mis_portafolios.js?v='.config('app.version')) }}" type="text/javascript"></script>
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
                        <li class="breadcrumb-item active" aria-current="page">Portafolio</li>
                    </ol>
                </div>
                <h2 class="page-title">
                    Mi portafolio
                </h2>
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
                                <div class="col-md-4 mb-2">      
                                    <div class="input-icon mb-3">
                                        <span class="input-icon-addon">                                          
                                          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path></svg>
                                        </span>
                                        <input type="text" class="form-control" value="{{ $user->usr_persona->nombre.' '.$user->usr_persona->apaterno.' '.$user->usr_persona->amaterno }}" disabled>
                                    </div>
                                </div>   
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
                            </div>
                        </div>
                    </div>
                    <div id="t_principal">
                        <table id="t_portafolios" class="table card-table table-vcenter text-nowrap datatable" width="100%">
                            <thead>
                                <tr>
                                    <th>ESCUELA PROFESIONAL</th>
                                    <th>CICLO</th>
                                    <th>ASIGNATURA</th>
                                    <th>GRUPO</th>
                                    <th>FECHA</th>
                                    <th>ACANCE</th>
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