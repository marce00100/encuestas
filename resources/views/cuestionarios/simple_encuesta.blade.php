<!DOCTYPE html>
<html>

<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <title>Encuestas</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="/libs/admindesigns/vendor/plugins/magnific/magnific-popup.css">
    <!-- Admin Forms CSS -->
    <link rel="stylesheet" type="text/css" href="/libs/admindesigns/assets/admin-tools/admin-forms/css/admin-forms.css">
    <!-- Admin Modals CSS -->
    <link rel="stylesheet" type="text/css" href="/libs/admindesigns/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css">
    <!-- Theme CSS -->
    <link rel="stylesheet" type="text/css" href="/libs/admindesigns/assets/skin/default_skin/css/theme.css">

    <!-- estilo de la aplicacion -->
    <link rel="stylesheet" href="/css/style-quest.css" type="text/css"/> 

    <!-- Favicon -->
    <link rel="shortcut icon" href="/libs/admindesigns/assets/img/ico.ico ">

    <style media="screen">
        .activo{
            background-color: #e5e5ee;
        }

        /*demo styles*/
        body {
            min-height: 300px;
        }
        .custom-nav-animation li {
            display: none;
        }
        .custom-nav-animation li.animated {
            display: block;
        }

        /* nav fixed settings */
        ul.tray-nav.affix {
            width: 319px;
            top: 80px;
        }
    </style>



</head>

<body class="admin-panels-page sb-l-c sb-l-o" data-spy="scroll" data-target="#nav-spy" data-offset="300">

<div id="" class=" " >
    <div class="panel">
        <input type="hidden" id="id_c">
        <div class="panel-heading bg-dark" style="height: 200px">
            <div class="panel-title  " style="padding: 50px 0 60px 0; font-weight:normal">
                <div id="c_titulo" name="" class="quest-h1 col-md-10 col-md-offset-1" style="background-color: transparent;color: white; "></div>
            </div> 
            <small id="pruebareal" class="fs11 col-md-12" style="margin: 50px 0 0 30px;"></small>         
        </div> 
        <div class="panel-body of-a bg-light darker ptn" style="position: relative;" >
            <div class="row bg-white" style="max-width: 700px; margin:0 auto; box-shadow: 1px 2px 5px;">    
                <div id="contenido_elementos" class="" >   
                </div>

                <div id="contenido_enviar"  class="text-center  p50 " hidden="" >
                    <button  class="btn btn-primary btn-lg br6 __save w300"   ><i class="fa fa-send"></i><span> Enviar</span></button>
                    <p class="pt15">Se enviaran sus respuestas, no revele contraseñas ni información confidencial, atravez de este formulario.</p>
                </div>

                <div id="contenido_despues_enviar" class="text-center  p50 " style="min-height: 350px" >
                </div>
            </div>

        </div>
    </div>

    <form id="logout-form" action="" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
</div>




<script type="text/javascript" src="/libs/min/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="/libs/admindesigns/vendor/jquery/jquery_ui/jquery-ui.min.js"></script>

<!-- Bootstrap -->
<script type="text/javascript" src="/libs/admindesigns/assets/js/bootstrap/bootstrap.min.js"></script>

<script type="text/javascript" src="/libs/admindesigns/assets/admin-tools/admin-forms/js/advanced/steps/jquery.steps.js"></script>
<script type="text/javascript" src="/libs/admindesigns/assets/admin-tools/admin-forms/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/libs/admindesigns/assets/admin-tools/admin-forms/js/additional-methods.min.js"></script>
<script type="text/javascript" src="/libs/admindesigns/vendor/plugins/magnific/jquery.magnific-popup.js"></script>

<!-- Theme Javascript -->
<script type="text/javascript" src="/libs/admindesigns/assets/js/utility/utility.js"></script>

<script type="text/javascript" src="/libs/underscore/underscore-min.js"></script>
<script type="text/javascript" src="/libs/bower_components/select2/dist/js/select2.full.min.js"></script>

<script type="text/javascript" src="/libs/bower_components/sweetalert/sweetalert.min.js"></script>
<script type="text/javascript" src="/libs/bower_components/sweetalert/jquery.sweet-alert.custom.js"></script>


<script type="text/javascript">

    var ctxG ={
        rutabase: '/encuesta/api',
        timeFin: new Date()
    }

    var elm = {
        pregunta_line: `<div class="__elemento row" __tipo="pregunta" __id_elemento="">                          
                            <div class="__texto quest-h2"  style="height: 30px"></div>
                            <div class="__elem_respuesta pl20 col-md-12"  __tipo_respuesta="">
                            </div>  
                        </div>`,
        titulo_line: `  <div class="__elemento row mv15" __tipo="titulo" __id_elemento="">
                            <div  class="quest-h1 __texto  mb15" style="height: 35px"></div>
                            <div  class="quest-sm __descripcion" style="height: 30px"></div>                        
                        </div>`,
        respuesta_corta_line: ` <div class="mt15 mb20">
                                    <input class="bg-white line form-control quest-input-line quest-h2 __open_sm" placeholder="Escribe tu respuesta" style="width:80%"  >
                                </div>`,

        respuesta_seleccion_line: ` <ul class="__opciones_respuesta mv10 pl15" style="list-style: none;">                                      
                                    </ul>`,       
        opcion_individual_line:`<li class="mt5 __opcion_individual" > 
                                    <input id="" type="" name="" class="__opcion_individual" value="">
                                    <label class="__opcion_individual quest-md" for="reemplaza_id"></label> 
                                </li>`, 
        adicionaElemento: function(tipoElemento){
            var contenidoElementos = $("#contenido_elementos");
            if(tipoElemento == 'pregunta'){
               contenidoElementos.append(elm.pregunta_line);
            }
            if(tipoElemento == 'titulo'){
                contenidoElementos.append(elm.titulo_line);
            }

        },    
    }


    var ctxC = {
        pruebareal: '',
        cargarCuestionario :function(){
            var urlArray = window.location.href.split('/');
            var id_c = urlArray[urlArray.length - 1]; 
            ctxC.pruebareal = urlArray[urlArray.length - 2] == 'prueba' ? 'prueba' : 'real';
            $.get(ctxG.rutabase + '/getcuestionario', {id:id_c}, function(res){
                ctxC.setData(res.data);
                ctxC.mostrarEstadoVista("new");
            })            
        },
        setData: function(objCuestionario){

            $("#id_c").val(objCuestionario.id);
            $("#c_titulo").html(objCuestionario.titulo);
            $("#pruebareal").html(ctxC.pruebareal == 'prueba'? 'Modo Previsualizacion, La respuestas emitidas seran para fines de PRUEBA.' : '');

            objCuestionario.elementos.forEach(function(objElem)
            {
                elm.adicionaElemento(objElem.tipo);

                var elemento = $("#contenido_elementos .__elemento").last();
                $(elemento).attr('__id_elemento', objElem.id);
                $(elemento).find('.__texto').html(objElem.texto);
                $(elemento).find('.__descripcion').html(objElem.descripcion);

                if(objElem.tipo == 'pregunta'){                    
                    var cnf = objElem.config;
                    $(elemento).find('[__tipo_respuesta]').first().attr('__tipo_respuesta', cnf.tipo_respuesta);
                    if(cnf.tipo_respuesta == 'single' || cnf.tipo_respuesta == 'multiple'){
                        $(elemento).find('.__elem_respuesta').append(elm.respuesta_seleccion_line);                        
                        objElem.opciones.forEach(function(op){
                            var typeinput = cnf.tipo_respuesta == 'single' ? 'radio' : 'checkbox';
                            var opcion = $(elm.opcion_individual_line);
                            $(opcion).find('input').attr('id', op.id).attr('name', objElem.id).attr('type', typeinput).val(op.opcion); 
                            $(opcion).find('label.__opcion_individual').attr('for',op.id).text(op.opcion);
                            $(elemento).find('.__opciones_respuesta').append(opcion);
                        })
                        
                    }
                    if(cnf.tipo_respuesta == 'open_sm'){
                        $(elemento).find('.__elem_respuesta').append(elm.respuesta_corta_line);
                    }      
                }
                
            });
        },
        getData(){
            timeFin = new Date();
            var contest = {
                id_cuestionario :  $("#id_c").val(),
                tiempo_seg : Math.floor((timeFin - ctxG.timeFin)/1000),
                pruebareal: ctxC.pruebareal ,
                _token : $('input[name=_token]').val(),
                respuestas: []
            };
            $(".__elemento[__tipo='pregunta']").each(function(k, elemento){
                if($(elemento).attr('__tipo') == 'pregunta'){                    

                    var tipoRespuesta = $(elemento).find('[__tipo_respuesta]').first().attr('__tipo_respuesta');
                    if(tipoRespuesta == 'single' || tipoRespuesta == 'multiple'){
                        $(elemento).find("input:checked").each(function(){
                            var opChecked = $(this);
                            var objResp = {
                                id_elemento : $(elemento).attr('__id_elemento'),
                                id_opcion: opChecked.attr('id') || null,
                                respuesta_opcion: opChecked.val(),
                                respuesta: opChecked.val(),
                            }

                            contest.respuestas.push(objResp); 

                        })
                    }
                    if(tipoRespuesta == 'open_sm'){
                        var respuesta_corta = $(elemento).find("input");
                        var objResp = {
                            id_elemento: $(elemento).attr('__id_elemento'),
                            respuesta: respuesta_corta.val(),
                        }
                        contest.respuestas.push(objResp);
                    }

                   
                }
            });
            return contest;


        },
        save(){
            dataSend = ctxC.getData();
            $.post(`${ctxG.rutabase}/saverespuestas`, dataSend, function(res){
                ctxC.mostrarEstadoVista('afterSave');
            })
        },
        mostrarEstadoVista(estadoVista){

            var enviar_nuevamente = `<div>
                                        <h4>Desea llenar el cuestionario nuevamente ?</h4>
                                        <button id="enviar_otro_cuestionario"  class="btn btn-warning btn-lg br6 mt20">
                                            <i class="fa fa-send"></i><span> Si, deseo llenar el cuestionario nuevamente. </span>
                                        </button>
                                    </div>`;
            var gracias = `<div class=" text-center bg-success light br12 br-a br-success p10 mt20">
                                <i class="fa fa-send fa-2x"></i>
                                <h3>Gracias por llenar el formulario!!. Sus respuestas fueron enviadas satisfactoriamente.</h3>
                            </div>`;
            if(estadoVista == 'new'){
                $("#contenido_elementos input, #contenido_elementos textarea").val('');
                $("#contenido_elementos input:radio, #contenido_elementos input:checkbox").prop("checked",false);
                $("#contenido_elementos, #contenido_enviar").show();
                $("#contenido_despues_enviar").html('').css('display','none');
            }
            if(estadoVista == 'afterSave'){
                $("#contenido_enviar").hide();
                $("#contenido_elementos").slideUp(500);
                $("#contenido_despues_enviar").html( ctxC.pruebareal == 'prueba' ? enviar_nuevamente : gracias).css('display','block');
            }

        },

        init(){
            $(".__save").click(function(){
                ctxC.save();
            }),

            $("#contenido_despues_enviar").on('click', '#enviar_otro_cuestionario', function(){
                ctxC.mostrarEstadoVista('new');
            })            
        }
        
    }

$(function(){
    (function(){
        ctxC.init();
        ctxC.cargarCuestionario();


    })()
    

})

</script>

</body>

</html>