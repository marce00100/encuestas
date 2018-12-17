@extends('layouts.principal_tpl')

@section('header')



<link rel="stylesheet" type="text/css" href="/libs/modify/pivot___.css">
{{-- <link rel="stylesheet" href="/libs/bower_components/bootstrap-urban-master/urban.css" type="text/css" /> --}}

<link rel="stylesheet" type="text/css" href="/libs/admindesigns/vendor/plugins/slick/slick.css" />
<link rel="stylesheet" href="/libs/bower_components/select2/dist/css/select2.min.css" type="text/css"/>
<style>
.popup-basic {
    position: relative;
    background: #FFF;
    width: auto;
    max-width: 700px;
    margin: 40px auto;
}
.sidenav {
    position: absolute;
    z-index: 1;
    background-color: #fff;
    overflow-x: hidden;
    transition: 1s;
    margin: 0px 0px 0px -20px;
}
.menuDetail{
    margin: 0px 0px 0px -20px;
    padding: 55px 2px 5px 72px ;
    overflow-y: scroll;
    /*transition: width 1s, margin-left 1s;*/
    font-size: 12px;
}
.sidenav a {
    text-decoration: none;
    color: #818181;
    display: block;
    transition: 1s;
}

.sidenav a:hover, .menuDetail a:hover {
    /*color: #f1f1f1;*/
    background-color: #F5F5DC;
}

.tituloDetail{
    color: #333;
}

.activoPri{
   background-color: #444449 !important; /* 4c5064 dark-dark */
   color: #f1f1f1 !important;
   border-color: #aed248;
}
.activoSub{
   background-color: #ABEBC6   !important;
   border-color: #aed248;
}

/*#contenido {
    transition: margin-left 1s;
    margin-left: 70px;
}*/
/*
@media screen and (max-height: 450px) {
    .sidenav {padding-top: 15px;}
    .sidenav a {font-size: 12px;}
}*/

#chartdiv {
    width   : 100%;
    height  : 500px;
} 

.jqx-pivotgrid{
    background-color: #fff;
}

.oculta_pvt .pvtTdForRender, .oculta_pvt .pvtAxisContainer, .oculta_pvt  .pvtVals{
    display: none}

.activo{
    /*font-weight: bolder;*/
    background-color: white;
    border-bottom: 1px solid blue; 
}
/*.pvtTotal, .pvtTotalLabel, .pvtGrandTotal {display: none}*/



/* mINPUTS PARA QUE SE VEAN MAS PEQUEÑOS */
.admin-form .select > select {
/*    -moz-appearance: none;
    background: #fff none repeat scroll 0 0;
    border: 1px solid #ddd;
    color: #626262;
    display: block;
    margin: 0;
    outline: medium none;*/
    padding: 8px 10px;
/*    text-indent: 0.01px;
    text-overflow: "";
    z-index: 10;*/
}

.admin-form .select, .admin-form .gui-input, .admin-form .gui-textarea, .admin-form .select > select, .admin-form .select-multiple select {
/*    border: 1px solid #ddd;
    color: #626262;
    display: inline-block;*/
    height: 35px;
/*    outline: medium none;
    position: relative;
    vertical-align: top;
    width: 100%;*/
}

.admin-form label, .admin-form input, .admin-form button, .admin-form select, .admin-form textarea {
/*    color: #626262;
    font-family: "Roboto",Arial,Helvetica,sans-serif;*/
    font-size: 11px;
/*    font-weight: 400;
    margin: 0;
    outline: medium none;*/
}


/* Para los selectc 2 */
.select_excepciones  li.select2-selection__choice{ background-color: #e63f24 !important; font-size: 12px; color: #ffffff !important;}
.select_agregador li.select2-selection__choice{ background-color: #3bafda !important; font-size: 12px; color: #ffffff !important;}
.select_default li.select2-selection__choice{ background-color: #f5b025 !important;  font-size: 12px; color: #ffffff !important;}
.select_titulos li.select2-selection__choice{ background-color: #666 !important; font-size: 12px; color: #ffffff !important;}
</style>
@endsection


@section('content')
<div class='container-fluid'>
    <div class=row>
        <div class="col-md-12 ">

            {{--  ===============================      VISTA INICIO  ========================--}}
            <div id="vistaInicio" style="width: 100%; height: 750px; background: white; overflow: hidden; position: relative;" >
                <h1 style="color:#eee; margin: 5px 25px">Pivot Visualizer</h1>
                {{-- <img src="/img/pivot-fondo-1.png" style=" opacity: 0.1; width: 90%; margin: 0px 0 0 50px ; position: absolute;">  --}}
            </div>


            {{-- ===============================       LOADING     ===============================================--}}
            <div id="loading" class="bg-white" style="width: 100%; height: 1000px; background: white" hidden="" > 
                <div style="left: 40%; top: 200px; width: 100%; position: absolute;">
                    <div  style="width: 20%; padding: 0 25px">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class=""> Cargango...</span>
                    </div>
                    <div class="progress progress-striped active" style="margin-top: 20px; width: 20%;height: 20px"><div class="progress-bar bg-dark" style="width: 100%"></div></div>                  
                </div>
            </div>

            {{-- ===============================       ERROR Mensajes     ===============================================--}}
            <div id="div_error" class="bg-white" hidden=""  style="width: 100%; height: 1000px; background: white" >     
                    <div  class="p25 br6 m10" style="top: 100px;  position: absolute;">
                        <i class="fa fa-warning fa-3x "></i>
                        <span class=""></span>
                    </div>
            </div>




            <div id="contenedor" hidden="">

                <div class="row m-0">
                    {{-- ============================= CONTENEDOR ====================================== --}}
                    <div id="contenedorDatos" style="height: 1300px; max-height: auto; width: 100%; "  class="bg-white p15 mt-1" > 

                         {{-- ===========================    BOTONES DE PANTALLAS y CONFIGURACION  ===========================  --}}
                        <div id="divTitulo" class="row mb5">
                            <div id="titulo" class="col-sm-9"></div>
                            <div class="col-sm-3">      

                                {{-- <a href="javascript:void(0)" id="btn_grafico" class="btn btn-default btn-xs  " hidden="" ><i class="fa fa-lg fa-bar-chart"></i></a>
                                <a href="javascript:void(0)" id="btn_tabla" class="btn btn-default btn-xs " hidden=""><i class="fa fa-lg fa-table"></i></a> --}}
                                
                                <div class="pull-right">
                                    <a href="#" id="btn_vista_Usuario" class="text-default"  title ="Cambiar el modo de vista Administrador / Solo Lectura" >
                                        <i class="fa fa-lg fa-user-plus   pr5 pl5  round"></i><span ></span>
                                    </a>
                                    <a id="btn_menuconfig_acciones" class="dropdown-toggle text-default" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" >
                                        <i class="fa fa-lg fa-cog  pr5 pl5  round"></i><span ></span>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <h5 class="ml10"><b>Sets predefinidos</b></h5>
                                        <li><a href="javascript:void(0)" id="predef_new"><i class="fa fa-clone fa-lg p2"></i><span> Guardar como Nueva  </span></a></li>
                                        <li><a href="javascript:void(0)" id="predef_update"><i class="fa fa-save fa-lg p2"></i><span> Guardar/actualizar Cambios</span></a></li>
                                        <li><a href="javascript:void(0)" id="predef_del"><i class="fa fa-trash-o fa-lg p2 text-danger"></i><span> Eliminar actual  </span></a></li>
                                        <li class="divider mv5"></li>
                                        <h5 class="ml10"><b>Anexos externos</b></h5>
                                        <li><a href="javascript:void(0)" id="anexar_archivo_ext"><i class="fa fa-stack-overflow fa-lg p2"></i><span> Archivos para Descargas </span></a></li>
                                        <li><a href="javascript:void(0)" id="anexar_dashboard_ext"><i class="fa fa-external-link fa-lg p2"></i><span> Dashboard Externo </span></a></li>
                                    </ul>
                                    
                                </div>
                                
                            </div>

                            <div id="submenus_pv" class=" ml30 col-sm-12">                                
                                <ul class="nav nav-list nav-list-topbar pull-left pbn mn">
                                    <li id="pv_submenu_1">
                                        <span style="cursor: pointer; " class="mh40"  id="1">Visualizador  </span>
                                    </li>
                                    <li id="pv_submenu_2">
                                        <span style="cursor: pointer; " class="mh40"  id="2">Descargas  </span>
                                    </li>
                                    <li id="pv_submenu_3">
                                        <span style="cursor: pointer; " class="mh40"  id="3">Dashboards</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <hr class="mb5 mt5">

                        
                        <div id="contenido_principal">

                            {{-- =========================================== CONTENIDO PIVOT ============================================================--}}
                            <div class="col-md-12 slick-slide" id="contenido_pivot">
                                {{-- ===============================     Iconos y Sets Predefinidos   ===========================  --}}
                                <div class="row bg-dark dark">
                                    <div id="contenedorPredefinidos" class="col-sm-12 stats-row m-0 p-3" >
                                    </div>
                                    <hr>
                                </div>

                                {{-- *============================================   PIVOT  PARA ADMIN *====================================================== --}}
                                <div id='divDatosUI' class="divPivot">
                                    <div id=tituloDatosUI class="mb15 tituloDatos"></div>
                                    <div class="row m-0 bg-white mt-2" style="overflow: auto; width: 100%; max-height: 600px; padding: 2px">
                                        <div id="pvtTableUI" ></div>                
                                    </div>
                                    <hr style="margin: 25px 0">
                                </div>
                                
                                {{-- ============================================ CHARTS ==================--}}
                                <div id='divGrafico'>
                                    <div id="tituloGrafico" class="mb15"></div>
                                    <div class="row" >
                                        <div class="col-sm-2" id="configuracionGrafico">
                                            <h5>OPCIONES DE GRAFICO</h5>
                                            <label >Tipo Gráfico</label>
                                            <select id="opcionesGrafico"  style="width: 100%">
                                                <option value="spline">Linea</option>
                                                <option value="column">Columnas</option>
                                                <option value="column-stacked">Columnas apiladas</option>    
                                                <option value="column-stackedp">Columnas apiladas en proporcion</option>
                                                <option value="bar">Barras</option> 
                                                <option value="bar-stacked">Barras apiladas</option>    
                                                <option value="bar-stackedp">Barras apiladas en proporcion</option>
                                                <option value="area">Area</option>
                                                <option value="area-stacked">Areas apiladas</option>    
                                                <option value="area-stackedp" >Areas apiladas en proporcion</option>
                                                <option value="pie" >Dona</option> 
                                            </select>
                                            <hr>                                    
                                            <label class="block"  ><input type="checkbox" id="viewlabel" name="viewlabel" /> Ver Datos</label>
                                            <label class="block" ><input type="checkbox" id="view3d" name="view3d" /> 3D</label>
                                        </div>
                                        <div class="col-sm-10" style="height: 400px">
                                            <div id="divChart" style="font-family: arial; width: 60%; min-height: 100%; margin: 0 auto"></div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ===========================       PIVOT PARA SOLO VER    =========================== --}}
                                <div id='divDatosRead' class="divPivot oculta_pvt " >
                                    <hr style="margin: 25px 0">
                                    <div id=tituloDatos class="mb15 tituloDatos"></div>
                                    <div class="row m-0 bg-white mt-2" style="overflow: auto; width: 100%; max-height: 600px; padding: 2px">
                                        <div id="pvtTableUIRead"  ></div>                
                                    </div>
                                </div>

                            </div>

                            {{-- =========================== Contenidooo de Archivos Externos Descargas ===================== --}}
                            <div id="archivos_ext" class="col-md-12 slick-slide">
                                <h4>Archivos</h4>
                                <div class="m25"></div>
                                
                            </div>

                            {{-- =========================== Contenidooo de Dash externos =========================== --}}
                            <div id="contenido_ext" class="col-md-12 slick-slide">
                                <h4>Otras fuentes (dashboards)</h4>
                                <select class="form-control" id="iframe_combo"></select>

                                <iframe style="width: 98%; height: 900px; " id="iframe_url" src="">
                                    
                                </iframe>
                                
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- <div id="predefModal" class="modal  " role="dialog"> --}}
<div id="predefModal" class="white-popup-block popup-basic admin-form mfp-with-anim mfp-hide " >
    <div class="panel">
        <div class="panel-heading bg-dark">
            <span class="panel-title text-white" id=""><i class="fa fa-pencil"></i> <span class="__modal_titulo">__</span></span>
        </div>
            <div class="panel-body of-a" >
                <div class="row">
                    <div class=" __item_campo_predefinido col-sm-2 col-sm-offset-5"  title=''  style="cursor:pointer;">
                        <img id="predef_imagen_previsualizacion"  src='' alt='' class="image" style="width:80px;height:60px">
                        <div class="filt" >
                            <div id='dixTextoImagen' class="text"></div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-horizontal" role="form" id='predefNewUpdate'>
                    <div class="form-group">
                        <label class="control-label col-md-3" for="predef_etiqueta">Etiqueta visible</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="predef_etiqueta" placeholder="Etiqueta visible ">
                        </div>
                    </div>    
                    <div class="form-group">
                        <label class="col-md-12">Imágenes</label>   <input type="hidden" id="predef_imagen">
                        <div id="selectImagenes" style="width: 90%; margin: 0px auto; overflow-x: scroll;">
                        </div>
                    </div>  
                    <div class="form-group">
                        <label class="control-label col-md-3" for="predef_posicion">Posicion</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="predef_posicion" placeholder="Posicion 1,2,3.. ">
                        </div>
                    </div> 
                </div>

                <div id="predefDel">
                    <div class="bg-danger-dark row m25" style="border-radius: 6px">
                        <div class="col-sm-2">
                            <i class="fa fa-exclamation-triangle fa-3x mt15"></i>
                        </div>
                        <div class="col-sm-9">
                            <h5 style="color: white">Se va a Eliminar la configuración que esta actualmente visualizando. Si elimina se perdará definitivamente dicha configuracion de visualizacion, pero no los datos.</h5>
                        </div>
                    </div>
                    <h5 class=""><span><b>Está seguro que desea eliminar la configuarcion de visualizacion de datos actual ?</b></span></h5>
                </div>
            </div>
            <div class="panel-footer">
                <button id="btnGuardar" type="submit" class="btn btn-primary  br6"   ><i class="fa fa-check"></i><span> Guardar</span></button>
                <button  class="btn btn-warning  br6 __pv_cancel" ><i class="fa fa-times"></i><span> Cancelar</span></button>

                
                
            </div>
    </div>
</div>


{{-- ANEXAR MODAL --}}
<div id="anexarModal" class="white-popup-block popup-basic admin-form mfp-with-anim mfp-hide w500" >
    <div class="panel">
        <div class="panel-heading bg-dark">
            <span class="panel-title text-white" id=""><i class="fa fa-pencil"></i> <span class="__modal_titulo">__</span></span>
        </div>

            <div class="panel-body of-a" >
                <form id="form_anexos"  method="post" action="/" enctype="multipart/form-data">
                    <div class="form" role="form" id=''>
                        <input class="hidden"  id="accion">
                        <div class="form-group div_anexar_dashboard" >
                            <label class="control-label" for="anx_dashboard">Url o tag del dashboard</label>
                            <div class="">
                                <textarea class="form-control" id="anx_dashboard" placeholder="Url / tag "></textarea>
                            </div>
                        </div>                      


                        <div class="section div_anexar_archivo">
                            <label class="field prepend-icon file">
                                <span class="button bg-warning "><i class="fa fa-search"></i> Buscar archivo</span>
                                <input name="anx_archivo" id="anx_archivo" class="gui-file" onchange="document.getElementById('uploader').value = this.value;" type="file">
                                <input class="gui-input" id="uploader" type="text" placeholder="Archivo ...">
                                <label class="field-icon"><i class="fa fa-upload"></i>
                                </label>
                            </label>
                        </div>  

                        <div class="form-group mb2" id="" >
                            <label class="control-label" for="descripcion">Descripción</label>
                            <div class="">
                                <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="breve descripción">
                            </div>
                        </div>



                        <div class="form-group"><button class="btn btn-sm btn-success w300" id="btn_anexar" style="margin:0 25%"> <i class="fa fa-plus"></i> <span> Agregar</span> </button></div>

                        <label class="col-md-12 bg-bluegrey text-white">Elementos existentes</label>
                        <div id="elementos" class="bordered" style="height: 300px; margin: 0px auto;" >
                        </div>

                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <button id="btnGuardar" type="submit" class="btn btn-primary __pv_guardar br6"   ><i class="fa fa-check"></i><span> Guardar</span></button>
                <button  class="btn btn-warning __pv_cancel ml15 br6" ><i class="fa fa-times"></i><span> Cancelar</span></button>

            </div>
    </div>
</div>


{{-- MENUS settings --}}
<div id="modalMenuCnf" class="white-popup-block popup-basic admin-form mfp-with-anim mfp-hide " >
    <div class="panel">
            <div class="panel-heading bg-dark">
                <span class="panel-title text-white" id=""><i class="fa fa-pencil"></i> <span class="__modal_titulo">__</span></span>
            </div>
            <div class="panel-body of-a" >
                <div class="row">
                            <div class=" pl5 mvn15">
                                <div>
                                    <span id="op_menus_agregar" class="ml30 activo"   style="cursor: pointer; "><i class="glyphicon glyphicon-tasks"></i> Agregar set de datos como menus</span>
                                    <span id="op_menus_modificar" class="ml30"  style="cursor: pointer; "><i class="fa fa-minus-square-o"></i> Configurar menus</span>
                                </div>
                                <hr>

                                <div id="div_agregar_menusvistas">
                                    <h5 class="bg-dark lighter p5">Seleccionar objetos de la Base de Datos</h5>
                                    <div class="section">
                                        <label class="field-label" for="pmra_id_m">Conexiones disponibles </label>
                                        <label class="field select">
                                            <select id="conexiones" name="conexiones" class="form-control-sm" style="width:100%;">
                                            </select>
                                            <i class="arrow"></i>                  
                                        </label>
                                    </div>
                                    <h4>Vistas </h4>
                                        <div id="contenedor_vistas" class="ph10 br-a" style="min-height: 200px" ></div>

                                        <div id="opciones_campos" class="mt15">
                                            <h5 class="bg-dark lighter p5 mb5" >Los siguientes son Campos que se configuraran por defecto para todos los seleccionados (muestra valores del primer seleccionado, luego se puede modificar estos valores)</h5 >
                                            <div class="section select_excepciones">
                                                <label class="field-label" for="campos_excepciones">Campos excepcion (dimension que se van a excluir en todos los seleccionados )</label>
                                                <label class="field select">
                                                    <select id="campos_excepciones" name="campos_excepciones" multiple="" class="" style="width:100%;">
                                                    </select>
                                                    <i class="arrow"></i>                  
                                                </label>
                                            </div>

                                            <div class="section select_agregador">
                                                <label class="field-label" for="agregador">Agregador (campo acumulador que se pondrá por defecto )</label>
                                                <label class="field select">
                                                    <select id="agregador" name="agregador" multiple="" class="" style="width:100%;" >
                                                    </select>
                                                    <i class="arrow"></i>                  
                                                </label>
                                            </div>

                                            <div class="section select_default">
                                                <label class="field-label" for="combinacion_defecto">Cruce de dimensiones por defecto (para todos los casos será la visualizacion predefinida)</label>
                                                <label class="field select">
                                                    <select id="combinacion_defecto" name="combinacion_defecto" multiple="" class="" style="width:100%;">
                                                    </select>
                                                    <i class="arrow"></i>                  
                                                </label>
                                            </div>
                                        </div>
                                        <div >
                                            <h5 class="bg-dark lighter p5">Grupo donde se agregara (seleccionar de los titulos y subtitulos o escribir nuevos)</h5>
                                            <div class="select_titulos row">
                                                <div class="section col-sm-6">
                                                    <label class="field-label" for="titulo_menu1">Titulo principal</label>
                                                    <label class="field select">
                                                        <select id="titulo_menu1" name="titulo_menu1" multiple="" class="" style="width:100%;">
                                                        </select>                
                                                    </label>
                                                </div>
                                                <div class="section col-sm-6">
                                                    <label class="field-label" for="titulo_menu2">Subtitulo</label>
                                                    <label class="field select">
                                                        <select id="titulo_menu2" name="titulo_menu2" multiple="" class="" style="width:100%;">
                                                        </select>                 
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    <hr class="mv15">
                                    <button id="btn_crearmenu" class="btn btn-system br6"><i class="glyphicon glyphicon-tasks "></i>  <span>generar menus</span></button>
                                </div>

                                <div id="div_modificar_menus">
                                    Menus disponibles
                                    <div id="contenedor" class="" style="height: 300px;">

                                    </div>
                                </div>

                            </div>
                </div>

            </div>
            <div class="panel-footer">
                {{-- <button type="submit" class="btn btn-primary br6 __pv_guardar"   ><i class="fa fa-check"></i><span> Guardar</span></button> --}}
                <button  class="btn btn-warning br6 __pv_cancel " ><i class="fa fa-times"></i><span> Cerrar</span></button>
            </div>
    </div>
</div>




@endsection

@push('script-head')
<script type="text/javascript" src="/libs/Highcharts-6.0.4/code/highcharts.js"></script>
<script type="text/javascript" src="/libs/Highcharts-6.0.4/code/highcharts-3d.js"></script>
<script type="text/javascript" src="/libs/Highcharts-6.0.4/code/modules/exporting.js"></script>

{{-- <script type="text/javascript" src="/libs/moCdify/hightcharts/themes/dark-unica_.src.js"></script> --}}
<script type="text/javascript" src="/libs/modify/hightcharts/themes/grid_.src.js"></script>
{{-- <script type="text/javascript" src="/libs/modify/hightcharts/themes/sand-signika_.src.js"></script> --}}

{{-- <script type="text/javascript" src="/libs/pivottable/dist/jquery-ui.min.js"></script> --}}
<script type="text/javascript" src="/libs/modify/pivot___.js"></script>
<script type="text/javascript" src="/libs/modify/pivot___.es.js"></script>

{{-- <script type="text/javascript" src="/libs/admindesigns/vendor/plugins/magnific/jquery.magnific-popup.js"></script> --}}
<script type="text/javascript" src="/libs/admindesigns/vendor/plugins/slick/slick.min.js"></script>
<script type="text/javascript" src="/libs/underscore/underscore-min.js"></script>
<script type="text/javascript" src="/libs/bower_components/select2/dist/js/select2.full.min.js"></script>


{{-- *********************  MAIN APP ************************ --}}
<script type="text/javascript" src="/js/tablero.js"></script>

<script>
$(function(){
    // ctxM.creaMenuBaseHtml();
    ctxC.mostrarPantallas();

    listeners = (function(){
        $( "form" ).submit(function( event ) {
          event.preventDefault();
        });

        var menuInit = (function (){
            /*  Click sobre elemento del menu     */
            // $("#menuPrincipal, #menuDetalle").on('click', 'a.nodo_menu', function(event){
            $("#menu-principal").on('click', 'a.nodo_menu', function(event){
                str_cod = $(this).attr('id');
                ctxG.nodoSel = ctxM.obtenerNodo(str_cod);
                // ctxM.activarElem(ctxG.nodoSel);
                if(ctxG.nodoSel.nivel == 1)
                {
                    ctxM.crearSubmenusHtml(ctxG.nodoSel);
                }
                else
                {
                    ctxC.obtenerData(ctxG.nodoSel);
                    ctxC.mostrarPantallas('grafico');
                }
                
            }); 
            /* Busqueda en menus */
            $("#menu-principal").on('keyup', '#txtBuscaMenu', function(){
                ctxM.buscaMenu();
            });
        })();

        var predefinidosInit = (function () { 
            /* Click sobre menu de predefinidos     */
            ctxC.contenedorPredefinidos.on('click', '.__item_campo_predefinido', function(e){
                index =  $(this).attr('id');
                ctxG.set_predef_actual = ctxG.varEstActual.sets_predefinidos[index];
                ctxG.set_predef_actual.index = index;
                ctxC.actualizaTitulos();
                ctxC.mostrarData(ctxG.collection);
            });

             /* ------------- Click guardar, modificar, o eliminar predefinidos (new, update, del)   */
            $("#predef_update, #predef_new, #predef_del").click(function(){
                var op = $(this).attr('id').replace('predef_','');
                ctxmodPred.mostrarModal(op);        
            });


            /* Click sobre una imagen de la ventana modal */
            $("#selectImagenes").on('click', 'img', function(){
                id_imagen = $(this).attr('id');
                $("#predefModal #predef_imagen_previsualizacion").attr("src",cnf.c.img[id_imagen]);
                $("#predef_imagen").val(id_imagen)
            })

            /* --------- Guardar Predefinido de modal */
            $("#predefModal #btnGuardar").click(function(){
                ctxmodPred.guardarPredef();
            });   
        })();   

        var gestionMenus = (function(){
            /* click en las opciones de agregar o quitar menus */
            $("#op_menus_agregar, #op_menus_modificar").click(function(){
                ctxMenuCnf.mostrarFormulario($(this).attr('id'));               
            });

            /* Seleccionar una coneccion*/
            $(ctxMenuCnf.idModal + " #conexiones").change(function(){
                ctxMenuCnf.cargarVistasConexion();
            });


            /* Al seleccionar todas las vistas o deseleccionar todas */
            $("#contenedor_vistas").on('click', '.__view_select_all', function(){
                var off = $(this).hasClass('text-default');
                $(this).removeClass('text-default text-success-darker fa-circle-o fa-circle').addClass( off ? 'text-success-darker fa-circle' : 'text-default fa-circle-o' );

                $("#contenedor_vistas  [__attr_view_name]").removeClass('text-default text-success-darker fa-circle-o fa-circle __vista_checked').addClass( off ? 'text-success-darker fa-circle __vista_checked' : 'text-default fa-circle-o' ).parent().removeClass('bg-light dark').addClass(off ? 'bg-light dark' : '');

                ctxMenuCnf.cargarColsDimensiones();
            });

            /* al seleccionar o deseleccionar individualmente una vista*/
            $("#contenedor_vistas").on('click', '[__attr_view_name]', function(){
                var off = $(this).hasClass('text-default');
                $(this).removeClass('text-default text-success-darker fa-circle-o fa-circle __vista_checked').addClass( off ? 'text-success-darker fa-circle __vista_checked' : 'text-default fa-circle-o' ).parent().removeClass('bg-light dark').addClass(off ? 'bg-light dark' : '');
                ctxMenuCnf.cargarColsDimensiones();
            });

            /* de los selects2 sus propiedades */
            {
                $("#campos_excepciones, #agregador, #combinacion_defecto, #tituloMenu1, #tituloMenu2 ").select2({
                    dropdownParent: $(ctxMenuCnf.idModal),
                    cache: false,
                    language: "es",
                });
                $("#agregador").select2({                
                    maximumSelectionLength: 1,
                });
                $("#combinacion_defecto").select2({                
                    maximumSelectionLength: 2,
                });
                $("#titulo_menu1, #titulo_menu2").select2({                
                    maximumSelectionLength: 1,
                    tags: true
                });
            }


            /* AL camiar el select de titulos de menus */
            $("#titulo_menu1").change(function(){
                ctxMenuCnf.fillComboTitulosMenus('subtitulos', $("#titulo_menu1").val() );
            })

            /* pa generar el menu en BD*/
            $(ctxMenuCnf.idModal + " #btn_crearmenu").click(function(){
                ctxMenuCnf.guardarMenuViews();
            });

            



        })();

        /* Click sobre los botones de mostrar tabla o grafico o geo     */
        // $("#btn_tabla, #btn_grafico").click(function(){
        //     var op = $(this).attr('id').replace('btn_',''); // == 'btn_tabla' ? 'tabla' : 'grafico';
        //     ctxC.mostrarPantallas(op);
        // });

        /*  ------------- Cambia config del grafico */
        $("#configuracionGrafico ").change(function(){
            ctxGra.graficarH();
        });

        /* --------- click menu Anexos ---------*/
        $("#anexar_archivo_ext, #anexar_dashboard_ext").click(function(){
            ctxmodAnx.mostrarModal($(this).attr('id'));        
        });

        /* ---------      En boton anexar ----------*/
        $("#anexarModal #btn_anexar").click(function(){
           ctxmodAnx.anexarElemento();
        });
        /* --------- Al remover quitar elemento dashboard o excel ---------*/
        $("#anexarModal").on('click', '.pv_del_anx', function(){
            $(this).parent().parent().remove();
        });
        /* ------   Guardar Anexo --------------------*/
        $("#anexarModal .__pv_guardar").click(function(){
            ctxmodAnx.guardar();
        });
        /* Al cambiar un dashboard cargado en el combo*/
        $("#iframe_combo").change(function(){
            url = $(this).val();
            $("#iframe_url").attr('src', url);
        })


        /* Click Boton de vista usuario Admin , usuariop normal
        TODO QUITAR function click  y volver el botton span 
        */
        $("#btn_vista_Usuario").click(function(){
            ctxC.workspace();
        })

        /* De los Bpotones Cancelar*/
        $(".__pv_cancel").click(function(){
             $.magnificPopup.close();
        });

        $('#contenido_principal').slick({
            dots: false,
            infinite: false,
            speed: 500,
            arrows:false,
            touchMove:false,
            swipe:false,
        });

        /* De los submenus de arriba */            
        $("#submenus_pv span").click(function(){
            index = $(this).attr('id');
            ctxC.submenu_pv_activo(index);
        });

        /* boton config encima del menu*/
        $(".__over_menu #option_settings").click(function(){
            ctxMenuCnf.mostrarModal();
        });





    })()




});

</script>



@endpush
