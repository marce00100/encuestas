<?php

namespace App\Http\Controllers\Cuestionarios;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class CuestionarioController extends Controller
{
    
    public $user;


    /* DE RUTA : decuelve view*/
    public function index()
    {
        return view('cuestionarios.bandeja_encuestas');
    }
    
    /* DE RUTA : Obtiene todos los cuestionarios de un usuario */
    public function getCuestionariosUs()
    {
        $cuestionarios = collect(\DB::select("SELECT * from cuestionarios where created_by = {$this->user->id} order by id desc "))
                                ->map(function($item, $k){
                                    $item->id =  $this->encryptId($item->id);
                                    return $item;
                                });
        return response()->json([
            'data' => $cuestionarios,
        ]);
    }

    /* DE RUtA : Obtiene un Cuestionario con todas sus elementos-opciones de ID */
    public function getCuestionarioElementos(Request $req)
    {
        $cuestionario = $this->cuestionarioElementos($req->id);
        return response()->json([
            'data' => $cuestionario,
        ]);
    }


    /* DE CLASE: Obtiene un Cuestionario con todas sus elementos-opciones */
    public function cuestionarioElementos($id_cuestionario)
    {
        $id_cuestionario = $this->decryptId($id_cuestionario);
        $cuestionario = collect(\DB::select("SELECT * from cuestionarios WHERE id = {$id_cuestionario}"))->first();
        if(!$cuestionario) {
            return response()->json([ 'estado'=>'error', 'msg' => 'No existe el identificador' ]);
        }

        $elementos = collect(\DB::select("SELECT * FROM elementos WHERE id_cuestionario = {$id_cuestionario} ORDER BY orden "))
                        ->map(function($el, $k ){
                            $el->config = json_decode($el->config);
                            $opciones = \DB::select("SELECT *  FROM opciones WHERE id_elemento = {$el->id} ORDER BY orden ");
                            $el->opciones = $opciones; 
                            return $el;
                        });
        $cuestionario->elementos = $elementos;
        $cuestionario->id = $this->encryptId($cuestionario->id);
        return $cuestionario;
    }

    /* DE RUTA: Guarda un cuestionario con sus elementos y sus opciones respectivas*/
    public function saveCuestionario(Request $req)
    {
        $req->id = $this->decryptId($req->id);
        // $req = $req->json()->all();
        $cuestionario = new \stdClass();
        $cuestionario->id = $req->id;
        $cuestionario->nombre = $req->nombre;
        $cuestionario->titulo = $req->titulo;
        $cuestionario->config = $req->config;
        $cuestionario->estado = 'A'; //$req->estado;
        $cuestionario->id = $this->saveObjectTabla($cuestionario, 'cuestionarios');

        $this->delOpsDeCuestionario($cuestionario->id);
        $this->delElemsDeCuestionario($cuestionario->id);

        foreach ($req->elementos as $el) {
            $el = (object)$el;
            $elem = new \stdClass();
            $elem->id_cuestionario = $cuestionario->id;
            $elem->texto = $el->texto;
            $elem->descripcion = $el->descripcion  ;
            $elem->tipo = $el->tipo;
            $elem->orden = $el->orden;
            $elem->estado = 'A'; /* estado = {A:Activo, I:Inactivo, D:Deleted}*/ 
            $elem->config = isset($el->config)? $el->config : null;
            $elem->url  = isset($el->url) ?  $el->url : null;
            $elem->id = \DB::table('elementos')->insertGetId( get_object_vars($elem) ); 

            isset($el->opciones) or $el->opciones = [];
            foreach ($el->opciones as $op) {
                $op = (object)$op;
                $opcion = new \stdClass();
                $opcion->id_elemento = $elem->id;
                $opcion->opcion = $op->opcion;
                $opcion->orden = $op->orden;
                if($op->opcion){
                    \DB::table('opciones')->insert( get_object_vars($opcion) );
                }
            }  
        }
        $cuestionario->id = $this->encryptId( $cuestionario->id);
        return response()->json([
            'estado' => 'ok',
            'msg' => 'Se guardo correctamente',
            'data' => $cuestionario
        ]);     
    }

    /* DE CLASE : Elimina todas las opciones vinculadas a un cuestionario */
    private function delOpsDeCuestionario($idCuestionario){
        $opciones = collect(\DB::select("SELECT o.* FROM cuestionarios c, elementos e, opciones o
                                    WHERE c.id = e.id_cuestionario and e.id = o.id_elemento AND c.id = {$idCuestionario} "));
        foreach ($opciones as $opcion) {
            \DB::table('opciones')->where('id', $opcion->id)->delete();
        }
    }
    /* DE CLASE: Elimina todos los elementos vinculados a un cuestionario */
    private function delElemsDeCuestionario($idCuestionario){
        $elems = collect(\DB::select("SELECT e.* FROM cuestionarios c, elementos e
                                    WHERE c.id = e.id_cuestionario AND c.id = {$idCuestionario} "));
        foreach ($elems as $el) {
            \DB::table('elementos')->where('id', $el->id)->delete();
        }
    }

    /* DE CLASE inserta las lo Contestado y las respuestas del Formulario*/
    public function saveRespuestas($contest)
    {
        $idCuestionario = $this->decryptId($contest->id_cuestionario);
        $contestado = (object)[];
        $contestado->id_cuestionario = $idCuestionario;
        $contestado->tiempo_seg = $contest->tiempo_seg;
        $contestado->estado = $contest->pruebareal;
        $contestado->created_at = \Carbon\Carbon::now(-4);
        // $contestado->id = \DB::table('contestados')->insertGetId(get_object_vars($contestado));
        // $contestado->respuestas = [];
        // foreach ($contest->respuestas as $resp) {
        //     $respuesta = (object)[];
        //     $resp = (object)($resp);
        //     $respuesta->id_contestado = $contestado->id;
        //     $respuesta->id_elemento = $resp->id_elemento;
        //     $respuesta->id_opcion = isset($resp->id_opcion) ? $resp->id_opcion : null;
        //     $respuesta->respuesta_opcion = isset($resp->respuesta_opcion) ? $resp->respuesta_opcion : null;
        //     $respuesta->respuesta = $resp->respuesta;
        //     \DB::table('respuestas')->insertGetId(get_object_vars($respuesta));

        // }
        return $contestado;
    }


    /*--------------------------------------------------------------------------------------------------------------
    |   Funcion Generica para insertar o modificar las tablas
     */
    private function saveObjectTabla($obj, $tabla)
    {
        try{
            if ($obj->id) // UPDATE 
            {
                $obj->updated_by = $this->user->id;
                $obj->updated_at = \Carbon\Carbon::now(-4);
                \DB::table($tabla)->where('id', $obj->id)->update(get_object_vars($obj));
                return $obj->id;
            }
            else // INSERT
            {
                unset($obj->id);
                $obj->created_by =  $this->user->id;
                $obj->created_at = \Carbon\Carbon::now(-4);
                return \DB::table($tabla)->insertGetId(get_object_vars($obj));
            }
        }
        catch (Exception $e)
        {
            return response()->json(array(
                'estado' => "error",
                'msg'    => $e->getMessage())
            );
        }
    }


  

    /*---------------------------------------------------------------------------
    | Guarda el JSON de configuracion
     */
    public function guardaConfiguracion(Request $req)
    {
        $id_dach_menu = $req->id_dash_menu;
        $configuracionString = str_replace("'", "''", $req->configuracionString);
        $dash_menu = collect(\DB::select("SELECT * from dash_menu where id = {$id_dach_menu} " ));
        $id_config = $dash_menu->first()->id_dash_config;

        \DB::select(" UPDATE dash_config set configuracion = '{$configuracionString}'    where id = {$id_config}");

        return response()->json([
            "mensaje"=>"ok"
        ]);


    }



    /* ----------------------------------------------------------------------------------
    | Guarda los archivos enviados 
     */
    public function saveAnnexedFiles(Request $req)
    {
        $id_dash_config = $req->id_dash_config;

        if($req->archivos_anexos){

            /* en archivos_anexos vienen solo los archivos que se quearon despues de eliminar .Es una lista */

            $olds = collect(\DB::select ("SELECT ca.id, a.nombre from dash_config_archivo ca, archivos a 
                                    where ca.id_archivo = a.id AND  ca.id_dash_config = {$id_dash_config}"));
            foreach ($olds as $old) {
                $existe = 0;
                foreach ($req->archivos_anexos as $n) {
                    $existe = $old->nombre == $n['url'] ? 1 : $existe;
                }
                if($existe == 0) 
                    \DB::table('dash_config_archivo')->where('id', $old->id )->delete();
            }
        }
        if($req->archivo_nuevo){ 
            $file=$req->archivo_nuevo;

            $nombreArchivo = $file->getClientOriginalName();
            // $tipo   = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();
            $ruta_archivo_temp = $file->getPathName();
            // $size = $file->getSize();
            // $nombreArchivoSystem = uniqid('ORG-');
            $target = $this->carpeta . $nombreArchivo;

            if(move_uploaded_file($ruta_archivo_temp,$target)){

                $msgFile="Archivo subido correctamente";

                $id_archivo = \DB::table('archivos')->insertGetId([
                    'nombre' => $nombreArchivo,
                    'extension' => $extension,
                    'ruta'=> $target,
                    'descripcion' => $req->descripcion,
                    'created_at' => \Carbon\Carbon::now(-4),
                    'user_id'=> \Auth::user()->id
                ]);
                \DB::table('dash_config_archivo')->insert([
                    'id_archivo' => $id_archivo,
                    'id_dash_config' => $id_dash_config,
                ]);
            }else{
                $msgFile = "Error al subir el Archivo";
            }

            return response()->json(
                ['mensaje'=>$msgFile,
                'nom'=>$file->getClientOriginalName(),
                'nombre' => $file->getClientOriginalName(),
                'extension' => $file->getClientOriginalExtension(),
                'ruta' => $file->getPathName(),
                'size' => $file->getSize(),
                'nombre_UI'=> uniqid('ORG-'),
            ]);
        }

       return response()->json(['data' => 'no hay']);
    }

    /****************************************************************************************************************************************************************/
    /*********************************************************** funciones privaadas propias, auxialires, constructor *************************************/
    /****************************************************************************************************************************************************************/
    public function __construct()
    { 
        $this->middleware(function ($request, $next) {
            $this->user= \Auth::user();
            $idrol = (int) $this->user->id_rol;

            $roles_permitidos = "'superusuario', 'administrador'" ;

            $acceso = collect(\DB::select("SELECT * from roles where id = {$idrol} and rol in ( {$roles_permitidos} ) "))->first();
            if(!$acceso){
                \Auth::logout();
                abort(404);
            }

            return $next($request);
        });

    }

    private function encryptId ($id)
    {
        if(strlen($id)>100)
            return $id;
        else
            return \Crypt::encrypt($id);
    }

    private function decryptId ($id)
    {
        if(strlen($id)>100)
            return \Crypt::decrypt($id);
        else
            return $id;
    }




}
