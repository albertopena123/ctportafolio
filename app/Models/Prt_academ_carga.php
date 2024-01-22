<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prt_academ_carga extends Model
{
    use HasFactory;

    protected $appends = ['semestre_text', 'ciclo_text'];

    protected $numeros_romanos = array(1 => "I", 2 => "II", 3 => "III", 4 => "IV", 5 => "V", 6 => "VI", 7 => "VII", 8 => "VIII", 9 => "IX", 10 => "X", 11 => "XI", 12 => "XII", 13 => "XIII", 14 => "XIV", 15 => "XV", 16 => "XVI", 17 => "XVII", 18 => "XVIII", 19 => "XIX", 20 => "XX", 21 => "XXI", 22 => "XXII", 23 => "XXIII", 24 => "XXIV", 25 => "XXV", 26 => "XXVI", 27 => "XXVII", 28 => "XXVIII", 29 => "XXIX", 30 => "XXX", 31 => "XXXI", 32 => "XXXII", 33 => "XXXIII", 34 => "XXXIV", 35 => "XXXV", 36 => "XXXVI", 37 => "XXXVII", 38 => "XXXVIII", 39 => "XXXIX", 40 => "XL", 41 => "XLI", 42 => "XLII", 43 => "XLIII", 44 => "XLIV", 45 => "XLV", 46 => "XLVI", 47 => "XLVII", 48 => "XLVIII", 49 => "XLIX", 50 => "L", 51 => "LI", 52 => "LII", 53 => "LIII", 54 => "LIV", 55 => "LV", 56 => "LVI", 57 => "LVII", 58 => "LVIII", 59 => "LIX", 60 => "LX", 61 => "LXI", 62 => "LXII", 63 => "LXIII", 64 => "LXIV", 65 => "LXV", 66 => "LXVI", 67 => "LXVII", 68 => "LXVIII", 69 => "LXIX", 70 => "LXX", 71 => "LXXI", 72 => "LXXII", 73 => "LXXIII", 74 => "LXXIV", 75 => "LXXV", 76 => "LXXVI", 77 => "LXXVII", 78 => "LXXVIII", 79 => "LXXIX", 80 => "LXXX", 81 => "LXXXI", 82 => "LXXXII", 83 => "LXXXIII", 84 => "LXXXIV", 85 => "LXXXV", 86 => "LXXXVI", 87 => "LXXXVII", 88 => "LXXXVIII", 89 => "LXXXIX", 90 => "XC", 91 => "XCI", 92 => "XCII", 93 => "XCIII", 94 => "XCIV", 95 => "XCV", 96 => "XCVI", 97 => "XCVII", 98 => "XCVIII", 99 => "XCIX", 100 => "C");

    protected $fillable = [
        'year', 'semestre', 'prt_prof_escuela_id', 'prt_dept_academico_id', 'prt_facultad_id', 'ciclo', 'prt_asignatura_id', 'observaciones', 'estado'
    ];

    public function getSemestreTextAttribute()
    {
        if(isset($this->numeros_romanos[$this->semestre])) {
            return $this->numeros_romanos[$this->semestre];
        } else {
            return $this->semestre;
        }
    }

    public function getCicloTextAttribute()
    {
        if(isset($this->numeros_romanos[$this->ciclo])) {
            return $this->numeros_romanos[$this->ciclo];
        } else {
            return $this->ciclo;
        }
    }

    public function prt_prof_escuela()
    {
        return $this->belongsTo(Prt_prof_escuela::class, 'prt_prof_escuela_id');
    }

    public function prt_asignatura()
    {
        return $this->belongsTo(Prt_asignatura::class, 'prt_asignatura_id');
    }

    public function prt_portafolios()
    {
        return $this->hasMany(Prt_portafolio::class, 'prt_academ_carga_id');
    }
}
