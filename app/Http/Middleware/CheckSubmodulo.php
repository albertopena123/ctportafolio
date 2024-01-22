<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckSubmodulo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $submodulo): Response
    {
        $user = Auth::user();
        //modulos a los que tiene acceso el rol del usuario [modulo] => [submodulos|...]
        $sumbodulos = $user->usr_rol->submodulos();
        $sistema = config('app.sistema');

        if(array_key_exists($sistema, $sumbodulos)){
            if(in_array($submodulo, $sumbodulos[$sistema])) {
                return $next($request);
            } else {
                return abort(403);
            }
        } else {
            return abort(403);
        }       
    }
}
