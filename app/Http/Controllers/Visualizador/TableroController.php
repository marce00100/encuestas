<?php

namespace App\Http\Controllers\Visualizador;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class TableroController extends Controller
{
    public $carpeta;
    private $longitudNivel = 2;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->carpeta = "archivos/archivos_anexos/";
        // $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            return $next($request);
        });

    }

    public function index()
    {
        $this->user= \Auth::user();
        return view('visualizador.tablero');
    }


    public function menusTablero()
    {
        $user = \Auth::user();
        $id_rol = $user->id_rol;
        $listaMenus = collect(\DB::select("SELECT m.id, m.cod_str, m.nombre,  m.descripcion, 
                                            m.nivel, m.tipo, m.orden, m.id_dash_config  --, c.variable_estadistica, c.configuracion
                                            FROM  dash_menu m JOIN dash_menu_rol mr ON m.id = mr.id_dash_menu  AND m.activo AND mr.id_rol = {$id_rol}
                                            -- LEFT JOIN dash_config c ON m.id_dash_config = c.id
                                            ORDER BY m.cod_str
                                "));

        $nodosMenu = $listaMenus->where('nivel',1)->sortBy('orden')->values();

        foreach ($nodosMenu as $nivel1) {
            $codigo = $nivel1->cod_str;
            $nombre = $nivel1->nombre;
            $niveles2 = $listaMenus->where('nivel', '2')->filter(function($item, $key) use ($codigo, $nombre){
                if(substr($item->cod_str, 0, 2) == $codigo)
                {
                    $item->padre = $nombre;
                    return $item;
                }

            })->sortBy('orden')->values();

            $nivel1->hijos = $niveles2;
            foreach ($niveles2 as $nivel2) {
                $cod2 = $nivel2->cod_str;
                $nombre = $nivel2->nombre;
                $niveles3 =  $listaMenus->where('nivel', '3')->filter(function($item, $key) use ($cod2, $nombre){
                    if(substr($item->cod_str, 0, 4) == $cod2)
                    {
                        $item->padre = $nombre;
                        return $item;
                    }
                    // return (substr($item->cod_str, 0, 4) == $cod2);
                })->sortBy('orden')->values();

                $nivel2->hijos = $niveles3;
            }
        }   

        return response()->json([
            'mensaje' => 'ok' .  (($user->permisos_abm == 'true') ? "_success" : "_access"  ),
            'perabm' => $user->permisos_abm,
            'nodosMenu'=> $nodosMenu,
        ]);     
    }

    /**
     * Obtiene el dash_config -> configuracion del menu 
     * @param  Request $req = {id_dash_config : id}
     * @return object con la configuracion y los datos vinculados a este
     */
    public function datosVariableEstadistica(Request $req) 
    {
        $id_dash_config = $req->id_dash_config;
        try {
            $config = collect(\DB::select("SELECT  * FROM dash_config WHERE  id = {$id_dash_config} "))->first();
        } catch (\Exception $e) {            
            return response()->json([
                'status' => 'error',
                'mensaje'=> "No existe una configuracion asociada actualmente al menu !!"
            ]);
        }

        $obj =  json_decode($config->configuracion);

        $id_indicador           = $config->id_indicador;
        $variable_estadistica   = $config->variable_estadistica;
        $tabla_vista            = $config->tabla_vista;
        $campo_agregacion       = $config->agregador;
        $condicion_sql          = $config->condicion_sql;
        // Obtiene los campos con sus alias
        $campos_disponibles_select = $config->dimensiones;
        // Para el group by se le quitan los alias
        $campos_originales_groupby = collect(explode(',', $config->dimensiones ))
                                ->map(function($item, $key){
                                    return stripos($item, ' as ') ?  substr($item, 0, stripos($item, ' as ')) : $item;
                                })->implode(', ');

        $qrySelect = $qryCondicion = $qryGroupBy = '';

        $tablas = collect(\DB::connection("dbestadistica")->select("select table_name from information_schema.tables 
                                where table_schema='public' and table_type='VIEW'
                                and table_name ilike '%{$tabla_vista}%' "));
        if($tablas->count()<=0)
            return response()->json([ 
                'status' => 'error',
                'mensaje' => "No existe ninguna tabla o vista que coincida con {$tabla_vista}"
            ]) ;

        $tabla = $tablas->first()->table_name;

        $qrySelect = "SELECT {$campos_disponibles_select}, SUM( {$campo_agregacion} ) AS valor
                    FROM {$tabla} 
                    WHERE 1 = 1 " ; 

        $qryCondicion = trim($condicion_sql) == '' ? '' : ' AND ' . $condicion_sql . ' ' ;

        $qryGroupBy = " GROUP BY {$campos_originales_groupby} ";
              // ORDER BY t_ano, {$campos_disponibles} " ;

        $query = $qrySelect . $qryCondicion . $qryGroupBy;

        try {
            $collection  =   collect(\DB::connection('dbestadistica')->select($query));   
        }
        catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'mensaje'=> "Existe un error en la configuracion de esta variable/menu, revisela por favor!!"
            ]);
        }

        try {
            $unidadesMedida = collect(\DB::connection('dbestadistica')->select("
                SELECT valor_unidad_medida, valor_defecto_um, valor_tipo FROM {$tabla} LIMIT 1"))->first();
        }
        catch(\Exception $e) {
            $unidadesMedida = '_';
        }

        return Response()->json([ 
                    'mensaje'   => 'ok',
                    'status'    => 'ok',
                    'collection'=> $collection,
                    'unidad_medida' => $unidadesMedida,
                    // 'indicador' => $indicador,
                    // 'metas'     => $metasPeriodo,
                    'query'     => $query,
                    'configuracionObj' => $obj
        ]);

    }

    
    public function archivosExtVarEst(Request $req)
    {
        $id_dash_config = $req->id_dash_config;
        $archivos_ext = \DB::select("SELECT a.id as id_archivo, a.nombre,  a.descripcion, c.id_dash_config, c.id as id_dash_config_archivo 
                                        FROM archivos a, dash_config_archivo c 
                                        WHERE a.id = c.id_archivo AND c.id_dash_config = {$id_dash_config} ");
        return response()->json([
            'data' => $archivos_ext,
        ]);
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

    /*------------------------------------------------------------------------------------------------
    | Verifica si ya existe el archivo archivos $req->files = [obj1, obj2], cada elemento
    | tiene la forma obj={id:1, nombre:'file1.txt'}
     */    
    public function verificaArchivos(Request $req)
    {
        $newFiles = [];
        if($req->files){ 
            $archivosBD = collect(\DB::select("SELECT nombre from archivos "));

            $newFiles = collect($req->files)->diff($archivosBD); 
        }
        return response()->jason(['data' => $newFiles]);
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

    /*-----------------------------------------------------------------------------------

    /* obtiene las conexxiones para las distintas Base de Datos*/
    public function getConnections(){
        $connections =  collect(\Config::get('database.connections'))->keys();
        $connections_externas = [];
        foreach ($connections as $value) {
            if($value<> 'pgsql')
                $connections_externas[] = $value;
        }

        return response()->json([
            'data' => $connections_externas,
        ]);
    }


    /* Obtiene las vistas de una conection */
    public function getViews(Request $req)
    {
        $conn = $req->connection;     
        $objects_vistas = collect(\DB::connection($conn)->select("select table_name as db_object from information_schema.tables 
            where table_schema='public' and table_type='VIEW' order by db_object"));

        return response()->json([
            'data' => $objects_vistas,
        ]);  

    }

    /* Obtiene las columnas dimensiones de una tabla */
    public function getColumns(Request $req)
    {
        $columns = collect(\DB::connection($req->connection)->select("select column_name from information_schema.columns 
            where table_schema='public' and table_name   = '{$req->tabla}' order by column_name"));

        return response()->json([
            'data' => $columns,
        ]);  
    }

    /* guarda las vistas en menu */
    public function saveMenuViews(Request $req)
    {
        $connection = $req->connection;
        $vistasSel = $req->vistasSel;

        $codstrTitulo1 = $req->titulo_menu1 ? $req->titulo_menu1[0] : 'anonimo';
        $codstrTitulo2 = $req->titulo_menu2 ? $req->titulo_menu2[0] : 'anonimo';

        $codstr_actual = 1;
        $objTitulo1 = [];
        $objTitulo2 = [
            'nombre' => $codstrTitulo2,
            'descripcion' => $codstrTitulo2, 
            'nivel' => 2,
            'tipo' => 'titulo',
            'activo' => true,
        ];
        $nodos1 = collect(\DB::select("SELECT * FROM dash_menu WHERE substr(cod_str, 1, 2) = '{$codstrTitulo1}'  order by cod_str "));
        $nodos2 = [];

        if($nodos1->count() > 0){
            $nodos2 = $nodos1->filter(function($elem, $k) use($codstrTitulo2){
                return  substr($elem->cod_str, 0, $this->longitudNivel * 2) ==  $codstrTitulo2 ;
            });

            if($nodos2->count() > 0){
                $codstr_actual = $nodos2->last()->cod_str;            
            }
            else{
                $codstr2 = $nodos1->last(function($elem){
                    return strlen($elem->cod_str) == $this->longitudNivel * 2;
                })->cod_str;

                $objTitulo2['cod_str'] = $codstr2 + 1;

                $codstr_actual = $objTitulo2['cod_str'] * pow(10, $this->longitudNivel)  ;

            }
        }
        else{
            $codstr1 = collect(\DB::select("SELECT * FROM dash_menu WHERE length(cod_str) = 2 ORDER BY cod_str "))->last()->cod_str;

            $objTitulo1 = [
                'nombre' => $codstrTitulo1,
                'descripcion' => $codstrTitulo1, 
                'nivel' => 1,
                'tipo' => 'titulo',
                'activo' => true,
                'cod_str' => $codstr1 + 1 
            ];

            $objTitulo2['cod_str'] = $objTitulo1['cod_str'] * pow(10, $this->longitudNivel) + 1;
            $codstr_actual = $objTitulo2['cod_str'] * pow(10, $this->longitudNivel)  ;
        }

        $vistasIns = [];
        foreach ($vistasSel as $vista) {
            $codstr_actual++;
            $el = [
                'nombre' => $vista,
                'descripcion' => $vista, 
                'nivel' => 3,
                'tipo' => 'link',
                'activo' => true,
                'cod_str' => $codstr_actual 
            ];
            $vistasIns[] = $el;
        }

        return response()->json([
            'codstr1' => $codstrTitulo1,
            'codstr2' => $codstrTitulo2,
            'data1' => $nodos1, 
            'data2' => $nodos2 , 
            'objtitulo1' => $objTitulo1 ? $objTitulo1 : null,
            'objtitulo2' => $objTitulo2 ? $objTitulo2 : null,
            'vistas' => $vistasIns
            ]);

    }

    public function saveMenu(Request $req)
    {

    }

    public function auxiliar_tablas()
    {
        /* =============== INSERTA LAS VISTAS COMO SUBMENUS  NO EJECUTAR*/
        // $tablas = collect(\DB::connection('dbestadistica')->select("select table_name from information_schema.tables 
        //     where table_schema='public' and table_type='VIEW'
        //     and table_name ilike '%v_ve%'  and table_name >'v_ve0067' "));
        //                         // return response()->json($tablas); 
        //                         $hu = '';
        // for($i = 0; $i< $tablas->count(); $i++) {
        //     $nombre = $tablas[$i]->table_name;
        //     // $cod = $i<9 ? '0' . ($i +1) : $i+1 ;
        //     $cod = 68 + $i;
        //     $cod_str = '1101' . $cod;
        //     $orden = 268 + $i +1;
        //     $id = $cod +102;
        //     $hu .=  "insert into dash_menu(id, cod_str, nombre, descripcion, nivel, tipo, orden, activo) 
        //         values ({$id}, '{$cod_str}', '{$nombre}', '{$nombre}', 3, 'link', {$orden},   true   )";
        //     \DB::select("insert into dash_menu(cod_str, nombre, descripcion, nivel, tipo, orden, activo) 
        //         values ('{$cod_str}', '{$nombre}', '{$nombre}', 3, 'link', {$orden},   true   )");
        // }
        
        
        /* =============== OTRA FUNCION : PASAR LOS CAMPOS  JSON  A LAS COLUMNAS dentro de dash_config */

        // $configs = collect(\DB::select("SELECT  * FROM dash_config "));

        // foreach ($configs as $config) {

        //      $obj =  json_decode($config->configuracion);


        //      $objUpdate = [ 
        //        'id_indicador'          => null, 
        //        'variable_estadistica'   => $obj->variable_estadistica,
        //        'titulo'                 => $obj->variable_estadistica,
        //        'tabla_vista'            => $obj->tabla_vista,
        //        'agregador'       => $obj->campo_agregacion,
        //        'condicion_sql'          => $obj->condicion_sql,
        // // Obtiene los campos con sus alias
        //        'dimensiones' => implode(', ', $obj->campos_disponibles)
        //    ];

        //      \DB::table('dash_config') ->where('id', $config->id)->update($objUpdate);


        // }


        /* ===============  CAMBIA NOMBRE DE LOS DASHMENUS quitando el v0001_p_ .... */
        // $menus = \DB::select("SELECT * from dash_menu");

        // foreach ($menus as $menu) {
        //     if(substr($menu->nombre, 0,2) == 'v_')
        //     {
        //         $newMenu = [
        //             'nombre' => substr($menu->nombre, 6) 
        //         ];

        //         \DB::table('dash_menu')->where('id', $menu->id)->update($newMenu);
        //     }

        // }


        // CAMBIAR CONFIGURACION DE DB
        //         $db =  \Config::get('database');

        // \Config::set('database.connections.dbest_a', array(
        //     'driver'    => 'pgsql',
        //     'host'      => 'localhost',
        //     'database'  => 'news',
        //     'username'  => 'postgres',
        //     'password'  => '1234',
        //     'charset'   => 'utf8',
        //     'collation' => 'utf8_unicode_ci',
        //     'prefix'    => '',
        // ));
        // $fuentes = \DB::connection('dbest_a')->select("SELECT * from fuentes");

        // return response()->json(['all_dbs' => $db,
                                    // 'fuentes'=> $fuentes]);


    }

}
