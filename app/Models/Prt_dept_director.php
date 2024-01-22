<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prt_dept_director extends Model
{
    use HasFactory;

    protected $table = 'prt_dept_directores';

    protected $fillable = [
        'prt_dept_academico_id', 'usr_persona_id', 'inicio', 'fin', 'estado', 'user_id'
    ];

    protected $casts = [
        'inicio' => 'datetime',
        'fin' => 'datetime',
    ];

    public function prt_dept_academico()
    {
        return $this->belongsTo(Prt_dept_academico::class, 'prt_dept_academico_id');
    }   

    public function usr_persona()
    {
        return $this->belongsTo(Usr_persona::class, 'usr_persona_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
