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
    <link href="{{ asset('css/publico.css?v='.config('app.version')) }}" rel="stylesheet"/>    
    @yield('css')
</head>
<body>    
    <div class="page">
			<div class="banner" style="background-image: url({{ asset('img/background.jpg') }});">
				<!-- HEADER PRINCIPAL -->
				<header class="navbar navbar-expand-md d-print-none navbar-transparente">
						<div class="container-xl">								
								<!-- Logo -->
								<h1 class="navbar-brand d-none-navbar-horizontal pe-0 pe-md-3">
										<a href="{{ url('/') }}">
										<img src="{{ asset('org/logo_principal.png') }}" height="32" alt="Tabler" class="navbar-brand-image" >
										</a>
								</h1>
								<!-- Menu derecha -->
								<div class="navbar-nav flex-row order-md-last">
										<div class="nav-item">              				   
												<a href="{{ url('admin') }}" class="btn btn-success">
														<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-login" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
																<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
																<path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path>
																<path d="M20 12h-13l3 -3m0 6l-3 -3"></path>
														</svg>
														Ingresar
												</a>
										</div>
								</div>
						</div>
				</header>
        @yield('buscador')				
			</div>
      
      
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