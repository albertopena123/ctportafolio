@extends('layouts.admin')

@section('titulo', 'Panel administrador')

@section('contenido')
<div class="container-xl">
    <!-- Page title -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    {{ config('app.organization') }}
                </div>
                <h2 class="page-title">
                    {{ config('app.name') }}
                </h2>
            </div>            
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        {{$s_operativo}}
                        <hr class="my-3">
                        <p class="mb-3">Disco [{{ $almacenamiento['disco'] }}], utilizado el <strong>{{ $almacenamiento['porcentaje'] }}% </strong>de {{ $almacenamiento['total_disk_text'] }}</p>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-green" style="width: {{ $almacenamiento['porcentaje'] }}%" role="progressbar" aria-valuenow="{{ $almacenamiento['porcentaje'] }}" aria-valuemin="0" aria-valuemax="100">
                                <span class="visually-hidden">{{ $almacenamiento['porcentaje'] }}% Completo</span>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-auto d-flex align-items-center pe-2">
                            <span class="legend me-2 bg-green"></span>
                            <span>Usado</span>
                            <span class="d-none d-md-inline  d-xxl-inline ms-2 text-muted">{{ $almacenamiento['used_disk_text'] }}</span>
                          </div>
                          <div class="col-auto d-flex align-items-center ps-2">
                            <span class="legend me-2"></span>
                            <span>Libre</span>
                            <span class="d-none d-md-inline  d-xxl-inline ms-2 text-muted">{{ $almacenamiento['free_disk_text'] }}</span>
                          </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="card-title">Material de ayuda</h4>
                    </div>  
                    <div class="list-group list-group-flush list-group-hoverable">
                        <a href="{{ asset('pdf/manual_usuario.pdf') }}" class="list-group-item" target="_blank">
                            <div class="row align-items-center">                          
                                <div class="col-auto">
                                    <span class="avatar bg-teal-lt">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon avatar-icon icon-tabler icon-tabler-book-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19 4v16h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12z" /><path d="M19 16h-12a2 2 0 0 0 -2 2" /><path d="M9 8h6" /></svg>
                                    </span>                                    
                                </div>
                                <div class="col text-truncate">
                                    Manual de usuario                              
                                </div>
                            </div>                            
                        </a>                        
                    </div>  
                </div>       
            </div>
        </div>
    </div>
</div>
@endsection