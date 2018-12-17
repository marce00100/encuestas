<?php

namespace App\Http\Controllers\ModuloAdministracion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ModuloAdministracion\Users;
use App\Models\ModuloAdministracion\Roles;


class AbmUsuariosController extends Controller
{
    public function index()
    {
      return view('ModuloAdministracion.abm_usuarios');
    }


    public function listarUsers(Request $request)
    {
     //if($request->ajax()) {
          $users = Users::all();
          return \Response::json($users);
     // }
    }

    public function listarRoles(Request $request)
    {
     //if($request->ajax()) {
          $roles = Roles::all();
          return \Response::json($roles);
     // }
    }

    public function listarUsers2(Request $request)
    {
     //if($request->ajax()) {
          $roles = \DB::select("SELECT U.id, U.name, U.email, U.password, U.remember_token, U.username, U.id_rol,R.rol,U.id_institucion,  U.permisos_abm, U.cargo, U.carnet, U.telefono
  FROM users AS U LEFT JOIN roles AS R ON U.id_rol=R.id ORDER BY U.name ASC");
          return \Response::json($roles);
     // }
    }

    public function guardarUsuario(Request $request)
    {
     
        if ( \Auth::user()->permisos_abm == 'false') {
          return "¡No Autorizado!";
        }

        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $password = bcrypt($request->input('password'));
        $remember_token = str_random(60);
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        $username = $request->input('username');
        $id_rol = $request->input('id_rol');
        $id_institucion = $request->input('id_institucion');
        $permisos_abm = ($request->input('permisos_abm')? $request->input('permisos_abm'):'false');
        $cargo = $request->input('cargo');
        $carnet = $request->input('carnet');
        $telefono = $request->input('telefono');


        if ( intval($id) > 0 ) {
            $affected = \DB::update('UPDATE users SET name = ?, email = ?, password = ?, updated_at = ?, username = ?, id_rol = ?, id_institucion = ?, permisos_abm = ?, cargo = ?, carnet = ?, telefono = ? WHERE id = ?', [$name, $email, $password, $updated_at, $username, $id_rol, $id_institucion, $permisos_abm, $cargo, $carnet, $telefono, $id]);
            echo "Se actualizó satisfactoriamente ($affected)...<br/>";
        } elseif( $id == '' ) {
            \DB::insert('insert into users (name, email, password, remember_token, created_at, username, id_rol, id_institucion, permisos_abm, cargo, carnet, telefono) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [$name, $email, $password, $remember_token, $created_at, $username, $id_rol, $id_institucion ,$permisos_abm, $cargo, $carnet, $telefono]);
            $lastInsertId = app('db')->getPdo()->lastInsertId();
            echo "ID=$lastInsertId\nSe guardó satisfactoriamente ($lastInsertId)...<br/>";
        } else {
            echo "No se guardó nada :(<br/>";
        }
    }

    public function borrarUsuario(Request $request)
    {
     
        if ( \Auth::user()->permisos_abm == 'false') {
          return "¡No Autorizado!";
        }

        $id = $request->input('id');
        $affected = \DB::delete('delete from users where id = ?', [$id]);
        echo "Se borro satisfactoriamente ($affected)...<br/>";
    }  

    public function autocompletarInstitucion(Request $request)
    {
        $algo = $request->input('_');
        $callback = $request->input('callbackjQuery');
        $termino = $request->input('term');

        $Inst = \DB::select("SELECT id, ( nombre || coalesce(' ' || localidad,'') ) AS value, ( nombre || coalesce(' ' || localidad,'') ) AS label 
                              FROM spie_instituciones
                              WHERE 
                              translate(nombre || coalesce(' ' || localidad,''), 'áéíóúÁÉÍÓÚüÜñÑ', 'aeiouAEIOUuUnN')
                              ILIKE 
                              '%' || translate('" . $termino . "', 'áéíóúÁÉÍÓÚüÜñÑ', 'aeiouAEIOUuUnN') || '%'
                              ORDER BY nombre");

        return \Response::json($Inst);
    }


    public function __construct()
    {      

        $this->middleware(function ($request, $next) {
            $this->user= \Auth::user();
            $idrol = (int) $this->user->id_rol;

            $rol_permitido = 'superusuario' ;

            $acceso = collect(\DB::select("SELECT * from roles where id = {$idrol} and rol='{$rol_permitido}' "))->first();
            if(!$acceso){
              \Auth::logout();
              abort(404);
            }

            return $next($request);
        });

    }

}
