<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Publico
Route::get('/', [App\Http\Controllers\PublicoController::class, 'index']);
Route::get('archivos/{codigo}/descargar', [App\Http\Controllers\PublicoController::class, 'descargar']);
Route::get('portafolios/{id}', [App\Http\Controllers\PublicoController::class, 'portafolio']);

//Autenticacion
Route::get('login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('login', [App\Http\Controllers\AuthController::class, 'login_post'])->middleware('throttle:limite_email');
Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);

//Administracion
Route::get('admin', [App\Http\Controllers\AdminController::class, 'index']);

Route::get('admin/mantenimiento/facultades', [App\Http\Controllers\AdminController::class, 'facultades'])->middleware('submodulo:PORTMANTENIMIENTO');
Route::get('admin/mantenimiento/departamentos', [App\Http\Controllers\AdminController::class, 'departamentos'])->middleware('submodulo:PORTMANTENIMIENTO');
Route::get('admin/mantenimiento/escuelas', [App\Http\Controllers\AdminController::class, 'escuelas'])->middleware('submodulo:PORTMANTENIMIENTO');
Route::get('admin/mantenimiento/asignaturas', [App\Http\Controllers\AdminController::class, 'asignaturas'])->middleware('submodulo:PORTMANTENIMIENTO');
Route::get('admin/mantenimiento/directores', [App\Http\Controllers\AdminController::class, 'directores'])->middleware('submodulo:PORTMANTENIMIENTO');
Route::get('admin/mantenimiento/secciones', [App\Http\Controllers\AdminController::class, 'secciones'])->middleware('submodulo:PORTMANTENIMIENTO');

Route::get('admin/carga', [App\Http\Controllers\AdminController::class, 'carga_academica'])->middleware('submodulo:CARGAACADEMICA');

Route::get('admin/gestion/portafolios', [App\Http\Controllers\AdminController::class, 'portafolios'])->middleware('submodulo:ADMPORTAFOLIO');

Route::get('admin/docente/portafolios', [App\Http\Controllers\AdminController::class, 'mis_portafolios'])->middleware('submodulo:MISPORTAFOLIO');
Route::get('admin/docente/portafolios/{id}', [App\Http\Controllers\AdminController::class, 'mi_portafolio'])->middleware('submodulo:MISPORTAFOLIO');


//API
Route::prefix('json')->group(function () {

    //PUBLICO
    Route::post('publico/navegar', [App\Http\Controllers\Api\PublicoController::class, 'navegar']); 
    Route::post('publico/portafolios', [App\Http\Controllers\Api\PublicoController::class, 'portafolios']); 
    

    //FACULTADES
    Route::post('facultades', [App\Http\Controllers\Api\FacultadController::class, 'listar']);    
    Route::post('facultades/nuevo', [App\Http\Controllers\Api\FacultadController::class, 'nuevo']);
    Route::post('facultades/{id}/modificar', [App\Http\Controllers\Api\FacultadController::class, 'modificar']); 
    Route::post('facultades/{id}/eliminar', [App\Http\Controllers\Api\FacultadController::class, 'eliminar']); 

    //DEPARTAMENTOS ACADEMICOS
    Route::post('departamentos', [App\Http\Controllers\Api\DepartamentoController::class, 'listar']);    
    Route::post('departamentos/nuevo', [App\Http\Controllers\Api\DepartamentoController::class, 'nuevo']);
    Route::post('departamentos/{id}/modificar', [App\Http\Controllers\Api\DepartamentoController::class, 'modificar']); 
    Route::post('departamentos/{id}/eliminar', [App\Http\Controllers\Api\DepartamentoController::class, 'eliminar']); 

    //ESCUELAS PROFESIONALES
    Route::post('escuelas', [App\Http\Controllers\Api\EscuelaController::class, 'listar']);    
    Route::post('escuelas/nuevo', [App\Http\Controllers\Api\EscuelaController::class, 'nuevo']);
    Route::post('escuelas/{id}/modificar', [App\Http\Controllers\Api\EscuelaController::class, 'modificar']); 
    Route::post('escuelas/{id}/eliminar', [App\Http\Controllers\Api\EscuelaController::class, 'eliminar']);

    //ASIGNATURAS
    Route::post('asignaturas', [App\Http\Controllers\Api\AsignaturaController::class, 'listar']);    
    Route::post('asignaturas/nuevo', [App\Http\Controllers\Api\AsignaturaController::class, 'nuevo']);
    Route::post('asignaturas/{id}/modificar', [App\Http\Controllers\Api\AsignaturaController::class, 'modificar']); 
    Route::post('asignaturas/{id}/eliminar', [App\Http\Controllers\Api\AsignaturaController::class, 'eliminar']);
    Route::post('asignaturas/buscar', [App\Http\Controllers\Api\AsignaturaController::class, 'buscar']);

    //CARGA ACADEMICA
    Route::post('cargas', [App\Http\Controllers\Api\CargaController::class, 'listar']);    
    Route::post('cargas/nuevo', [App\Http\Controllers\Api\CargaController::class, 'nuevo']);
    Route::post('cargas/{id}/modificar', [App\Http\Controllers\Api\CargaController::class, 'modificar']); 
    Route::post('cargas/{id}/eliminar', [App\Http\Controllers\Api\CargaController::class, 'eliminar']);
    
    //SECCIONES
    Route::post('secciones', [App\Http\Controllers\Api\SeccionController::class, 'listar']); 
    Route::post('secciones/nuevo', [App\Http\Controllers\Api\SeccionController::class, 'nuevo']);
    Route::post('secciones/{id}/modificar', [App\Http\Controllers\Api\SeccionController::class, 'modificar']); 
    Route::post('secciones/{id}/mover', [App\Http\Controllers\Api\SeccionController::class, 'mover']); 
    Route::post('secciones/{id}/eliminar', [App\Http\Controllers\Api\SeccionController::class, 'eliminar']);

    //PORTAFOLIOS
    Route::post('portafolios', [App\Http\Controllers\Api\PortafolioController::class, 'listar']);    
    Route::post('portafolios/nuevo', [App\Http\Controllers\Api\PortafolioController::class, 'nuevo']);
    Route::post('portafolios/{id}/modificar', [App\Http\Controllers\Api\PortafolioController::class, 'modificar']); 
    Route::post('portafolios/{id}/eliminar', [App\Http\Controllers\Api\PortafolioController::class, 'eliminar']);

    //PERSONAS
    Route::post('personas/buscar', [App\Http\Controllers\Api\PersonaController::class, 'buscar']); 

    //DOCENTE
    Route::post('docente/portafolios', [App\Http\Controllers\Api\DocenteController::class, 'listar']);    
    Route::post('docente/portafolios/archivos', [App\Http\Controllers\Api\DocenteController::class, 'archivos']);

    //CARPETAS
    Route::post('docente/secciones/{id}/carpetas', [App\Http\Controllers\Api\DocenteController::class, 'carpetas']); 
    Route::post('docente/carpetas/nuevo', [App\Http\Controllers\Api\DocenteController::class, 'carpeta_nuevo']);   
    Route::post('docente/carpetas/{id}/modificar', [App\Http\Controllers\Api\DocenteController::class, 'carpeta_modificar']); 
    Route::post('docente/carpetas/{id}/eliminar', [App\Http\Controllers\Api\DocenteController::class, 'carpeta_eliminar']);    
    
    //ARCHIVOS
    Route::post('docente/archivos/nuevo', [App\Http\Controllers\Api\DocenteController::class, 'archivo_nuevo']);
    Route::post('docente/archivos/{id}/eliminar', [App\Http\Controllers\Api\DocenteController::class, 'archivo_eliminar']);
    Route::post('docente/archivos/{id}/mover', [App\Http\Controllers\Api\DocenteController::class, 'archivo_mover']);
    
    //DIRECTORES
    Route::post('directores', [App\Http\Controllers\Api\DirectorController::class, 'listar']);    
    Route::post('directores/nuevo', [App\Http\Controllers\Api\DirectorController::class, 'nuevo']);
    Route::post('directores/{id}/estado', [App\Http\Controllers\Api\DirectorController::class, 'estado']); 
    Route::post('directores/{id}/eliminar', [App\Http\Controllers\Api\DirectorController::class, 'eliminar']);

});
