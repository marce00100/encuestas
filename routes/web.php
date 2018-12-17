<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/* illuminate/router.php */
Auth::routes();

Route::get('home', 'HomeController@index')->name('home')->middleware('auth');



/* ENCUESTAS ADM */
Route::group(['prefix' => 'admcuestionario', 'middleware' => 'auth'], function(){

    Route::get('main', 'Cuestionarios\CuestionarioController@index');

    Route::group( array('prefix' => 'api'), function() {      
        Route::get('getcuestionarios_us', 'Cuestionarios\CuestionarioController@getCuestionariosUs');
        Route::post('savecuestionario', 'Cuestionarios\CuestionarioController@saveCuestionario');
        Route::get('getcuestionarioelementos', 'Cuestionarios\CuestionarioController@getCuestionarioElementos');        
    });
});

/* ENCUESTAS PUBLICO*/
Route::group(['prefix' => 'encuesta'], function(){

    Route::get('llenar/prueba/{id}', 'Cuestionarios\CuestionarioPublicoController@cuestionarioPublic');
    Route::get('llenar/r/{id}', 'Cuestionarios\CuestionarioPublicoController@cuestionarioPublic');

    Route::group( array('prefix' => 'api'), function() {      
        Route::get('getcuestionario', 'Cuestionarios\CuestionarioPublicoController@getCuestionarioElems');        
        Route::post('saverespuestas', 'Cuestionarios\CuestionarioPublicoController@saveRespuestas');        
    });
});


Route::group(['middleware' => 'auth'],function(){
      Route::group(
          array('prefix' => 'administracion'),
          function() {

              Route::get('abm_usuarios', 'ModuloAdministracion\AbmUsuariosController@index');
              Route::get('listausers', 'ModuloAdministracion\AbmUsuariosController@listarUsers');
              Route::get('listaroles_usu', 'ModuloAdministracion\AbmUsuariosController@listarRoles');
              Route::get('listausers2', 'ModuloAdministracion\AbmUsuariosController@listarUsers2');
              Route::post('guardarusuario', 'ModuloAdministracion\AbmUsuariosController@guardarUsuario');
              Route::post('borrarusuario', 'ModuloAdministracion\AbmUsuariosController@borrarUsuario');
              Route::get('autocompletarinst', 'ModuloAdministracion\AbmUsuariosController@autocompletarInstitucion');


          }
      );
});




      



