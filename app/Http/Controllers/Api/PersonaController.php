<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usr_persona;
use Validator;


class PersonaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Busca personal por nombre o dni para users
    public function buscar(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'term' => 'required'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()], 500);
        }

        $result = Usr_persona::where('estado', 1)
        ->where(function ($query) use ($request) {
            $query->where('nro_documento','like', '%'.$request->input('term').'%')
                ->orWhere('nombre','like', '%'.$request->input('term').'%')
                ->orWhere('apaterno','like', '%'.$request->input('term').'%')
                ->orWhere('amaterno','like', '%'.$request->input('term').'%');
        })->get();                              
        return response()->json($result, 200);
    }
}
