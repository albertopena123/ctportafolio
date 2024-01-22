@extends('layouts.blank')

@section('titulo', 'Iniciar sesión')

@section('js')
<script src="{{ asset('js/auth/login.js?v='.config('app.version')) }}"></script>
@endsection

@section('contenido')
<div class="container container-tight py-4">    
    <div class="text-center mb-3">
        <a href="{{ url('/') }}" class="navbar-brand navbar-brand-autodark">
            <img src="{{ asset('org/logo_principal.png') }}" height="40" alt="">
        </a>
    </div>
    <form class="card card-md" method="POST" action="{{ url('login') }}" onsubmit="return login(event);">
        @csrf
        <div class="card-body">
            <h2 class="h2 text-center mb-2">Ingrese sus credenciales</h2>
            <div class="pb-3">
                @if ($errors->any())
                <div class="alert alert-important alert-danger alert-dismissible mb-2" role="alert">                
                    <div>
                        <ul style="margin: 0; padding: 0; list-style: none;">
                            @foreach ($errors->all() as $error)
                            <li class="lh-1 my-1">{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </div>                
                    <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                </div>
                @endif
            </div>               
            <div class="mb-3 form-group form-required">
                <label class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" name="email" required autocomplete="email" value="{{ old('email') }}" autofocus>
            </div>
            <div class="mb-3 form-group form-required">
                <label class="form-label">Contraseña <span class="form-label-description">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#modal_password" tabindex="5">¿Olvidaste tu contraseña?</a></span>
                </label>
                <input type="password" id="password" class="form-control" name="password" required autocomplete="off">                    
                <div id="may_act" class="form-check-description text-warning mt-1 oculto">Bloq Mayús activado</div>
            </div>
            <div class="mb-2">
                <label class="form-check">
                <input type="checkbox" class="form-check-input" name="remember">
                <span class="form-check-label">Recordarme en este dispositivo</span>
                </label>
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </div>
        </div>        
    </form>    
</div>
@endsection

@section('modal')
<div class="modal modal-blur fade" id="modal_password" tabindex="-1" role="dialog" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Recuperar acceso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="m-0 text-justify">En caso de presentar problemas para el acceso a {{ config('app.name')}} , comunicarse con <b>{{ config('app.contact_name') }}</b> o al correo electrónico <a href="mailto:{{ config('app.contact_email') }}">{{ config('app.contact_email') }}</a>.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection