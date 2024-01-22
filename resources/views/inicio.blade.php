@extends('layouts.publico')

@section('titulo', 'Buscador')

@section('css')
<link href="{{ asset('css/portafolios.css?v='.config('app.version')) }}" rel="stylesheet"/>   
@endsection

@section('js')
<script src="{{ asset('js/publico/portafolios.js?v='.config('app.version')) }}" type="text/javascript"></script>
@endsection

@section('buscador')
<div class="container-xl">
    <div class="py-md-5 py-4">
        <div class="row">
            <div class="col-md-6 px-2">
                <h1 class="mb-0 lh-sm">{{ config('app.name') }}</h1>
                <p>{{ config('app.organization') }}</p>
            </div>
        </div>						
        <div class="buscador pt-2 ps-2 pe-2 pb-0">
            <div class="row">                
                <div class="search-section col-md-2 mb-2">
                    <div class="d-flex align-items-center">
                        <div class="ps-2 text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar-month" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M7 14h.013" /><path d="M10.01 14h.005" /><path d="M13.01 14h.005" /><path d="M16.015 14h.005" /><path d="M13.015 17h.005" /><path d="M7.01 17h.005" /><path d="M10.01 17h.005" /></svg>
                        </div>
                        <div class="flex-fill">
                            <select id="year_select" class="search-select" title="AÑO">
                                @for ($i = 0; $i < 7; $i++)
                                <option value="{{ $ahora->year - $i}}">{{ $ahora->year - $i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>                    
                </div>
                <div class="search-section col-md-2 mb-2">    
                    <div class="d-flex align-items-center">
                        <div class="ps-2 text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-calendar" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1" /><path d="M12 15v3" /></svg>
                        </div>
                        <div class="flex-fill">
                            <select id="semestre_select" class="search-select" title="SEMESTRE">
                                <option value="1">I - PRIMERO</option>    
                                <option value="2" {{ $ahora->month > 6 ? 'selected' : '' }}>II - SEGUNDO</option>                                       
                            </select>
                        </div>
                    </div>  
                </div>                            
                <div class="search-section col-md-5 mb-2">
                    <div class="d-flex align-items-center">
                        <div class="ps-2 text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" /><path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" /></svg>
                        </div>
                        <div class="flex-fill">
                            <select id="escuelas_select" class="search-select" title="ESCUELA PROFESIONAL">
                                <option value="0">SELECCIONE...</option>
                                @foreach ($facultades as $facultad)
                                    @foreach ($facultad->prt_dept_academicos as $departamento)
                                        @foreach ($departamento->prt_prof_escuelas as $escuela)
                                        <option value="{{ $escuela->id }}">{{ $facultad->nombre }} : {{ $escuela->nombre }}</option>
                                        @endforeach
                                    @endforeach                                    
                                @endforeach                                   
                            </select>
                        </div>
                    </div> 
                </div>                
                <div class="search-section col-md-3 mb-2">
                    <div class="row g-2">                        
                        <div class="col">
                          <input id="texto_buscar" type="text" class="search-control" placeholder="Buscar…" title="BUSCAR ASIGNATURA">
                        </div>
                        <div class="col-auto">
                            <button onclick="buscar_texto()" class="btn btn-primary btn-icon" aria-label="Button">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path><path d="M21 21l-6 -6"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('contenido')
<div class="page-body">
    <div class="container-xl">
        <div class="row mb-3">
            <div class="col-6">
                <span class="fw-bold">Portafolios</span>
            </div>
            <div class="col-6">
                <div class="d-flex justify-content-end">
                    <a href="javascript:void(0);" onclick="reset();">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                        Reset
                    </a>
                </div>
            </div>
        </div>
        <div class="row row-cards">
            <div class="col-12">
                <div id="lista_portafolios">
                    <div class="alert alert-info alert-important " role="alert">
                        <div class="d-flex">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path><path d="M12 9h.01"></path><path d="M11 12h1v4h1"></path></svg>
                            </div>
                            <div>
                                Seleccione una <b>ESCUELA PROFESIONAL</b>
                            </div>
                        </div>
                      </div>
                </div>                
            </div>
        </div>
        <div class="row">
            <div id="paginacion" class="col-12">                
            </div>
        </div>
    </div>
</div>
@endsection