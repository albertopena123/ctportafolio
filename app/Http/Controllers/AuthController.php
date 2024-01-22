<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Hash;
use Session;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * LOGIN
     */
    public function login() //Acceso
    {
        if (Auth::check()) {//si ya esta logeado
            $user = Auth::user();            
            return redirect('/admin');           
        } else {
            return view('auth.login');
        }
    }

    public function login_post(Request $request) //Ingresar
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            return redirect('login')->withErrors($validator);
        }
        //exite correo
        $user = User::with('usr_rol')->where('email',$request->email)->first();
        if($user == null) {
            return redirect('login')->withErrors(['Estas credenciales no coinciden con nuestros registros.'])->withInput();
        }
        //habilitado
        if($user->estado == 0) {
            return redirect('login')->withErrors(['Su cuenta de usuario se encuentra deshabilitada.'])->withInput();
        }
        //tiene privilegios    
        if($user->usr_rol == null) {
            return redirect('login')->withErrors(['No cuenta con los privilegios necesarios para acceder a este sistema.'])->withInput();
        } else {
            $modulos = $user->usr_rol->submodulos();     
            $modulo = config('app.sistema');  
            //no esta el modulo dentro de sus privilegios
            if(!isset($modulos[$modulo])) {
                return redirect('login')->withErrors(['No cuenta con los privilegios necesarios para acceder a este sistema.'])->withInput();
            }
        }
            
        //validar
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials, ($request->input('remember') == 'on') ? true : false)) {
            $user = Auth::user();            
            return redirect()->intended('/admin');            
        }

        return redirect("login")->withErrors(['Estas credenciales no coinciden con nuestros registros.'])->withInput();
    }

    /**
     * LOGOUT
     */
    public function logout() //salir
    {
        Session::flush();
        Auth::logout();
        return Redirect('/');
    }
}
