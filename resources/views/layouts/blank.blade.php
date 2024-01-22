<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
<body class="d-flex flex-column">
    <div class="page pagina-centro">
        @yield('contenido')
    </div>
    <div id="cargando_pagina" class="cargando" style="position: fixed !important;">
        <div class="text-center pt-4">
          <div class="spinner-border text-blue" role="status"></div> <b>Cargando...</b>
        </div>
    </div> 
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