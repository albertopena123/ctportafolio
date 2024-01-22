<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="theme-color" content="#0054a6"/>

    <link rel="shortcut icon" href="{{ asset('org/favicon.ico') }}" />
    <link rel="icon" sizes="192x192" href="{{ asset('org/logo_entidad.png') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Titulo -->
    <title>{{ config('app.name', 'Farmhouse') }} - @yield('titulo')</title>
    <!-- CSS -->
    <link href="{{ asset('lib/tabler-1.0.0-beta20/css/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('lib/tabler-1.0.0-beta20/css/tabler-vendors.min.css') }}" rel="stylesheet"/> 
    <link href="{{ asset('css/admin.css?v='.config('app.version')) }}" rel="stylesheet"/>    
    @yield('css')
</head>
<body>
    @php
        $user = Auth::user();
        $rol = $user->usr_rol;  
        $sistema = config('app.sistema');
        $sumbodulos = $rol->submodulos();
    @endphp
    <div class="page">
      <!-- HEADER PRINCIPAL -->
      <header class="navbar navbar-expand-md d-print-none" data-bs-theme="dark">
        <div class="container-xl">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".menu-navegacion" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <!-- Logo -->
          <h1 class="navbar-brand d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="{{ url('/') }}">
              <img src="{{ asset('org/logo_principal_alt.png') }}" height="32" alt="Tabler" class="navbar-brand-image" >
            </a>
          </h1>
          <!-- Menu derecha -->
          <div class="navbar-nav flex-row order-md-last">            
            <div class="nav-item dropdown">
              <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <span class="avatar avatar-sm">{{ $user->abreviatura }}</span>
                <div class="d-none d-xl-block ps-2">
                  <div>{{ $user->nombre }}</div>
                  <div class="mt-1 small text-muted">{{ $rol->nombre }}</div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" data-bs-theme="light">
                <a href="{{ url('logout') }}" class="dropdown-item"                          
                  onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();" >
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M7 6a7.75 7.75 0 1 0 10 0"></path>
                    <path d="M12 4l0 8"></path>
                  </svg>
                  Salir
                </a>
                <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </div>
            </div>
          </div>
          <!-- MODULOS -->
          <div class="collapse navbar-collapse menu-navegacion">
            <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
              <ul class="navbar-nav">                
                <li class="nav-item">
                  <a href="{{ url('admin') }}" class="nav-link">
                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-books" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M9 4m0 1a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1z" /><path d="M5 8h4" /><path d="M9 16h4" /><path d="M13.803 4.56l2.184 -.53c.562 -.135 1.133 .19 1.282 .732l3.695 13.418a1.02 1.02 0 0 1 -.634 1.219l-.133 .041l-2.184 .53c-.562 .135 -1.133 -.19 -1.282 -.732l-3.695 -13.418a1.02 1.02 0 0 1 .634 -1.219l.133 -.041z" /><path d="M14 9l4 -1" /><path d="M16 16l3.923 -.98" /></svg>
                    </span>
                    <span class="nav-link-title">
                      Portafolio de docentes
                    </span>
                  </a>
                </li>                
              </ul>
            </div>
          </div>         
        </div>
      </header>
      <!-- HEADER SECUNDARIO -->
      @if(!empty($sumbodulos))
        @if(array_key_exists($sistema, $sumbodulos))
        <header class="navbar-expand-md">
          <div class="collapse navbar-collapse menu-navegacion">
            <div class="navbar navbar-light">
              <div class="container-xl">
                <!-- SUBMODULOS -->
                <ul class="navbar-nav">
                  <!-- Administración de docentes -->
                  @if(in_array('PORTMANTENIMIENTO', $sumbodulos[$sistema]))   
                  <li class="nav-item dropdown {{ (request()->is('admin/mantenimiento*')) ? 'active' : '' }}">
                    <a class="nav-link dropdown-toggle" href="#sistema" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                      <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tool" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" /></svg>
                      </span>
                      <span class="nav-link-title">
                        Mantenimiento
                      </span>
                    </a>
                    <div class="dropdown-menu">
                      <a class="dropdown-item {{ (request()->is('admin/mantenimiento/facultades*')) ? 'active' : '' }}" href="{{ url('admin/mantenimiento/facultades') }}">
                        Facultades
                      </a>
                      <a class="dropdown-item {{ (request()->is('admin/mantenimiento/departamentos*')) ? 'active' : '' }}" href="{{ url('admin/mantenimiento/departamentos') }}">
                        Departamentos académicos
                      </a>
                      <a class="dropdown-item {{ (request()->is('admin/mantenimiento/escuelas*')) ? 'active' : '' }}" href="{{ url('admin/mantenimiento/escuelas') }}">
                        Escuelas profesionales
                      </a>
                      <a class="dropdown-item {{ (request()->is('admin/mantenimiento/asignaturas*')) ? 'active' : '' }}" href="{{ url('admin/mantenimiento/asignaturas') }}">
                        Asignaturas
                      </a>
                      <a class="dropdown-item {{ (request()->is('admin/mantenimiento/directores*')) ? 'active' : '' }}" href="{{ url('admin/mantenimiento/directores') }}">
                        Directores
                      </a>
                      <a class="dropdown-item {{ (request()->is('admin/mantenimiento/secciones*')) ? 'active' : '' }}" href="{{ url('admin/mantenimiento/secciones') }}">
                        Secciones
                      </a>
                    </div>
                  </li>  
                  @endif    
                  
                  <!-- Administración de carga academica -->
                  @if(in_array('CARGAACADEMICA', $sumbodulos[$sistema]))
                  <li class="nav-item {{ (request()->is('admin/carga*')) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('admin/carga') }}">
                      <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-school" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M22 9l-10 -4l-10 4l10 4l10 -4v6" /><path d="M6 10.6v5.4a6 3 0 0 0 12 0v-5.4" /></svg>
                      </span>
                      <span class="nav-link-title">
                        Carga académica
                      </span>
                    </a>
                  </li>
                  @endif

                  <!-- Gestión de porfolio -->
                  @if(in_array('ADMPORTAFOLIO', $sumbodulos[$sistema]))   
                  <li class="nav-item {{ (request()->is('admin/gestion/portafolios*')) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('admin/gestion/portafolios') }}">
                      <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-briefcase" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /><path d="M12 12l0 .01" /><path d="M3 13a20 20 0 0 0 18 0" /></svg>
                      </span>
                      <span class="nav-link-title">
                        Gestión de portafolios
                      </span>
                    </a>                    
                  </li>  
                  @endif
                  
                  <!-- Administración de mis protafolios -->
                  @if(in_array('MISPORTAFOLIO', $sumbodulos[$sistema]))
                  <li class="nav-item {{ (request()->is('admin/docente/portafolios*')) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('admin/docente/portafolios') }}">
                      <span class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/checkbox -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-clipboard-list" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 12l.01 0" /><path d="M13 12l2 0" /><path d="M9 16l.01 0" /><path d="M13 16l2 0" /></svg>
                      </span>
                      <span class="nav-link-title">
                        Mi portafolio
                      </span>
                    </a>
                  </li>
                  @endif
                </ul>
              </div>
            </div>
          </div>
        </header>
        @endif
      @endif
      <!-- CONTENIDO -->
      <div class="page-wrapper position-relative">
        @yield('contenido')
        <!--FOOTER-->
        <footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row text-center align-items-center flex-row-reverse">
              <div class="col-lg-auto ms-lg-auto">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item"><a class="link-secondary">Versión {{ config('app.version') }}</a> </li>
                  <li class="list-inline-item">
                    <a href="https://www.linkedin.com/in/jos%C3%A9-cortijo-bellido-49a513b5/" target="_blank" class="link-secondary" rel="noopener">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon text-primary icon-inline" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 7l5 5l-5 5" /><line x1="12" y1="19" x2="19" y2="19" /></svg>
                        Developer
                    </a>
                  </li>
                </ul>
              </div>
              <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    Copyright © 2023                                      
                  </li>
                  <li class="list-inline-item">                    
                    <a class="link-secondary">{{ config('app.organization') }}</a>                   
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
        <!-- CARGANDO -->
        <div id="cargando_pagina" class="cargando">
          <div class="text-center pt-5">
            <div class="spinner-border text-blue align-middle" role="status"></div> <b class="align-middle ps-1">Cargando...</b>
          </div>
        </div> 
      </div>
    </div>
    
    <!-- ALERTA -->
    <div id="mensaje_container"></div>
    <!-- MODAL -->
    @yield('modal')
    <!-- JS -->
    <script src="{{ asset('lib/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('lib/tabler-1.0.0-beta20/js/tabler.min.js') }}"></script>
    <script src="{{ asset('js/admin.js?v='.config('app.version')) }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });       
      </script>
    @yield('js')   
</body>
</html>