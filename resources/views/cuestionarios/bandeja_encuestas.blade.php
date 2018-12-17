@extends('layouts.principal_tpl')

@section('header')



{{-- <link rel="stylesheet" type="text/css" href="/libs/modify/pivot___.css"> --}}
{{-- <link rel="stylesheet" href="/libs/bower_components/bootstrap-urban-master/urban.css" type="text/css" /> --}}

{{-- <link rel="stylesheet" type="text/css" href="/libs/admindesigns/vendor/plugins/slick/slick.css" />
<link rel="stylesheet" href="/libs/bower_components/select2/dist/css/select2.min.css" type="text/css"/> --}}
<link rel="stylesheet" href="/css/style-quest.css" type="text/css"/> 
<style>
.popup-basic {
    position: relative;
    background: #FFF;
    width: auto;
    max-width: 900px;
    margin: 40px auto;
    padding: 0px !important;
}

#modalEncuesta textarea{
	resize: none;
	width: 100%;
}


</style>
@endsection


@section('content')
<div class='container-fluid'>
    <div class='row bg-warning light ' style="padding: 0px 3px">
    	<div class="col-md-12 bg-white " style="width: 100%; min-height: 750px; " >


    		<h1 style="color:#eee; padding: 10px 25px; margin: 0px; " >Generador de Encuestas</h1>
    		<hr>
    		<button id="nueva_encuesta" class=" btn btn-success br6"><i class="fa fa-plus fa-lg"></i> Agregar nuevo</button>
    		<h5><i class="glyphicons glyphicons-show_big_thumbnails"></i> Vista en Cuadricula </h5>
    		<div id="contenedor_encuestas">

    		</div>

    	</div>
    </div>
</div>



<div id="modalEncuesta" class="white-popup-block popup-basic admin-form mfp-with-anim mfp-hide " >
	<div class="panel">
		<div class="row">
			<div class="col-xs-1  p10 text-center"><i class="fa fa-pencil fa-2x"></i></div>
			<div class="pr40 mb20 col-xs-11">
				<input type="hidden" id="c_id">
				<input type="text" id="c_nombre" class="form-control fs12 quest-input quest-h2"   name="" placeholder="..Nombre de la encuesta" >
				{{-- <input type="text" id="c_descripcion" class="form-control quest-input quest-sm " placeholder="..Breve descripción sobre esta encuesta"></textarea> --}}
			</div>
		</div>

		<div class="panel-heading bg-dark" style="height: 200px">
			<span class="pull-right">o</span>
			<div class="panel-title  " style="padding: 50px 0 60px 0">
				<input type="text" id="c_titulo" name="" class="quest-input quest-h1 " placeholder="..Título principal" style="background-color: transparent;color: white; width: 90%">
			</div>			
		</div>
		<div class="panel-body of-a bg-light darker ptn" >
			<div class="row bg-white" style="max-width: 700px; margin:0 auto;">
				<div id="agregar_elementos" class=" br-b pl50 pv5" >
					<span id="agregar_pregunta" class="glyphicon glyphicon-plus text-center pt10 mh15" style="cursor: pointer; width: 30px; height: 30px; " title="Agregar Pregunta"></span>
					|
					<span id="agregar_titulo" class="glyphicons glyphicons-text_underline text-center pt10 mh15" style="cursor: pointer; width: 30px; height: 30px;" title="Agregar titulo"></span>
				</div>
		

				<div id="contenido_elementos" class="" style="height: 500px; max-height: 900px; overflow-y: scroll;">	
				</div>
			</div>

		</div>
        <div class="panel-footer">
            <button  class="btn btn-primary br6 __save"   ><i class="fa fa-check"></i><span> Guardar</span></button>
            <button  class="btn btn-warning br6 __cancel ml10" ><i class="fa fa-times"></i><span> Cerrar</span></button>
        </div>
    </div>
</div>

{{-- Estadisticas de las RESPUESTAS --}}
<div id="mdlEstRespuestas" class="white-popup-block popup-basic admin-form mfp-with-anim mfp-hide " >
	<div class="panel">
		<div class="panel-heading bg-dark" style="height: 200px">
			<span class="pull-right">o</span>
			<div class="panel-title  " style="padding: 50px 0 60px 0">
				<input type="text" id="c_titulo" name="" class="quest-input quest-h1 " placeholder="..Título principal" style="background-color: transparent;color: white; width: 90%">
			</div>			
		</div>
		<div class="panel-body of-a bg-light darker ptn" >
			<div class="row bg-white" style="max-width: 700px; margin:0 auto;">
				<div id="contenido_elementos" class="" style="height: 500px; max-height: 900px; overflow-y: scroll;">	
				</div>
			</div>

		</div>
        <div class="panel-footer">
            <button  class="btn btn-warning br6 __cancel ml10" ><i class="fa fa-times"></i><span> Cerrar</span></button>
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


{{-- <script type="text/javascript" src="/libs/modify/pivot___.js"></script>
<script type="text/javascript" src="/libs/modify/pivot___.es.js"></script> --}}


{{-- <script type="text/javascript" src="/libs/admindesigns/vendor/plugins/slick/slick.min.js"></script> --}}
<script type="text/javascript" src="/libs/underscore/underscore-min.js"></script>
<script type="text/javascript" src="/libs/bower_components/select2/dist/js/select2.full.min.js"></script>

<script type="text/javascript" src="/libs/bower_components/sweetalert/sweetalert.min.js"></script>
<script type="text/javascript" src="/libs/bower_components/sweetalert/jquery.sweet-alert.custom.js"></script>


{{-- *********************  MAIN APP ************************ --}}
{{-- <script type="text/javascript" src="/js/tablero.js"></script> --}}

<script>


	/*----   ctxG variable que contiene el contexto global, variables globales */
	var ctxG = {
		rutabase: '/admcuestionario/api',
        c : {   
            activoPri : 'activoPri',
            activoSub : 'activoSub',
            bgSub : 'bg-dark-dark',
            bgHeaderSub : 'bg-dark-light',
        },
        showModal : function(modal){
        	$(".state-error").removeClass("state-error")
        	$(modal + " em").remove();
        	$.magnificPopup.open({
        		removalDelay: 500, 
                // focus: '#pmra_id_pilar',
                items: {
                	src: modal
                },
                // overflowY: 'hidden', //
                callbacks: {
                	beforeOpen: function(e) {
                		var Animation = "mfp-zoomIn";
                		this.st.mainClass = Animation;
                	}
                },
                closeOnBgClick: false,
                midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
            });
        }, 
        mostrarMensajeFloat:function(obj){
        	new PNotify({
        		title: obj.estado == 'ok' ? 'Guardado' : 'Error',
        		text: obj.msg,
        		shadow: true,
        		opacity: 0.9,
                // addclass: noteStack,
                type: (obj.estado == 'ok') ? "success" : "danger",
                // stack: Stacks[noteStack],
                width: 300,
                delay: 2000
            });

        },
        tituloModal: function(titulo){
        	$(".__modal_titulo").html(titulo);
        }
    }

    /* Context de encuestas */
    var ctxQ = {
    	encuestasList: [],    
    	elems: {
    		thumbnail: function(elem){
    			return `<div class="col-lg-3 col-md-4 col-xs-6" style="overflow:hide">
			    			<div class="br-a br6 m10 pb5" __id_c="${elem.id}" style="height:150px; overflow:hidden; box-shadow: 1px 2px 5px; position:relative">
				    			<div class="bg-dark fs12 p15 pt30" style="height:100px"><span class="text-white">${elem.titulo}</span> </div>
				    			<div class="p10" title="${elem.nombre}"><i class="fa fa-th-list fa-lg mr10"></i> ${elem.nombre}</div>
			    			</div>
		    			</div>`;
    		},
    		opcionesThumbnail:`<div class="__acciones_thumbnail pull-right pr10" style="position:absolute; top: 0; right: 5px">
									<span accion="edit" 	class="btn btn-sm fa fa-pencil fa-lg text-white-darker ph5 "  title="Editar"></span>
									<span accion="info" 	class="btn btn-sm fa fa-info fa-lg text-white-darker ph5"  title="Informacion"></span>
									<span accion="preview" 	class="btn btn-sm glyphicons glyphicons-eye_open fa-lg text-white-darker ph5"  title="Vista previa"></span>
								</div>`,

    	},
    	fillLista: function(){
    		$.get(ctxG.rutabase + '/getcuestionarios_us', function(res){
    			ctxQ.encuestasList = res.data;
    			var listaMapeada = ctxQ.encuestasList.map(function(elem){
    									return elem; // por el momento no hace nada ,pero se puede filtrar u ordenar
    								});
    			ctxQ.fillMapa(listaMapeada)

    		})
    	},	
    	fillMapa: function(lista){
    		var htmlThumbnail = '';
    		lista.forEach(function(elem){
    			htmlThumbnail += ctxQ.elems.thumbnail(elem);
    		});
    		$("#contenedor_encuestas").html(htmlThumbnail);

    	},
    	nuevaEncuesta: function(){
    		ctxMod.setData();
    		ctxG.showModal(ctxMod.idmodal);
    	},
    	editarEncuesta: function(id){
    		$.get(`${ctxG.rutabase}/getcuestionarioelementos`, {id: id}, function(res){
    			ctxMod.setData(res.data);
    			ctxG.showModal(ctxMod.idmodal);
    		})
    	},
    	mostrarVistaPrevia: function(idCuestionario){
    		window.open('/encuesta/llenar/prueba/'+idCuestionario, '_blank'); 
    	},
    	ejecutaAccionThumbnail: function(accion, idCuestionario){
    		if(accion=='edit'){
    			ctxQ.editarEncuesta(idCuestionario);
    		}
    		if(accion=='preview'){    			
    			ctxQ.mostrarVistaPrevia(idCuestionario);
    		}
    	}
    }

    var elm = {
    	acciones_elemento: `<div class="__acciones_elemento pull-right" style="position:absolute; top: 0; right: 5px">
								<span accion="subir" class="fa fa-chevron-up fa-lg text-muted " style="cursor: pointer; " title="Subir posicion"></span>
								<span accion="bajar" class="fa fa-chevron-down fa-lg text-muted " style="cursor: pointer; " title="Bajar posicion"></span>
								<span accion="quitar" class="fa fa-trash-o fa-lg text-muted ml10" style="cursor: pointer; " title="Quitar elemento"></span>
							</div>`,
    	pregunta: `<div class="__elemento row" __tipo="pregunta" >    						
						<textarea type="text" class="__texto quest-input quest-h2" placeholder="Escriba la pregunta.." style="height: 30px" rows="1"></textarea>
						<div class="col-md-6">
							<select class="__elem_tipo_respuesta form-control">
								<option value="single">Selección simple</option>
								<option value="multiple">Selección multiple</option>
								<option value="open_sm">Respuesta corta</option>
							</select>
						</div>
						<div class="__elem_respuesta pl20 col-md-12">
						</div>	
					</div>`,
		titulo: `<div class="__elemento row" __tipo="titulo">
					<textarea type="text" class="quest-input quest-h1 __texto" placeholder="Escriba el título.." style="height: 35px" rows="1"></textarea>
					<textarea type="text" class="quest-input quest-sm __descripcion" placeholder="descripcion" style="height: 30px" rows="1"></textarea>						
				</div>`,

		respuesta_seleccion: `	<ul class="__opciones_respuesta quest_option_single mv10 pl15" style="list-style: none;""> 										
								</ul>
								<a  href="javascript:void(0)" class="__agregar_opcion_individual ml15">Agregar opción</a>`,			
		respuesta_corta: `<div>
							<input class="bg-white quest-input adorno  form-control " placeholder="Respuesta" style="width:80%"  >
						</div>`,
		opcion_individual: `<li class="mt5 __opcion_individual"> 
								<bullet></bullet><input class="quest-input __opcion_individual " style="width:80%"  placeholder="Escriba la opcion" value=""> 
								<span class="hidden">
									<span accion="subir" class="fa fa-chevron-circle-up text-muted" style="cursor:pointer"title="subir" ></span> 
									<span accion="bajar" class="fa fa-chevron-circle-down text-muted" style="cursor:pointer"title="bajar"></span> 
									<span accion="quitar" class="glyphicons glyphicons-remove_2 text-muted" style="cursor:pointer"title="eliminar opcion"></span> 
								<span>	
						</li>`,    	
    	adicionaElemento: function(tipoElemento){
    		var contenidoElementos = $("#contenido_elementos");
    		if(tipoElemento == 'pregunta'){
    			var newElem = $(elm.pregunta);
    			/*le agrega la respuesta de seleccion*/
    			newElem.find('.__elem_respuesta').first().append(elm.respuesta_seleccion);
    			/*le agrega por defecto la respuesta de seleccion simple*/
    			newElem.find('.__opciones_respuesta').first().append(elm.opcion_individual);
    			contenidoElementos.append(newElem);
    			// contenidoElementos.append(elm.pregunta);
    		}
    		if(tipoElemento == 'titulo'){
    			contenidoElementos.append(elm.titulo);
    		}


    		var height = contenidoElementos[0].scrollHeight;
    		contenidoElementos.animate({ scrollTop: height }, 1000);
    		var elementoAdd = contenidoElementos.find('.__elemento').last();
    		elementoAdd.trigger('click');
    		elementoAdd.find('textarea').first().focus();
    	},
    	ejecutaAccionElemento: function(accion, elemento){
    		if(accion == 'quitar'){
    			$(elemento).remove();
    		}
    		if(accion == 'subir'){
    			$(elemento).insertBefore(elemento.prev())
    		}
    		if(accion == 'bajar'){
    			$(elemento).insertAfter(elemento.next())
    		}
    	},
    	seleccionaTipoRespuesta: function(tipo, elemento){
    		var elem_respuesta = $(elemento).find('.__elem_respuesta');

    		/* si ya es de opciones y se cambia a tipo opciones  , no se hace nada*/
    		if( elem_respuesta.find('.__opciones_respuesta').length > 0 && (tipo=='single' || tipo=='multiple') ){
    			elem_respuesta.find('.__opciones_respuesta').removeClass('quest_option_single quest_option_multiple').addClass('quest_option_' + tipo)
    			return 0;
    		}

    		if(tipo=='single'){
    			elem_respuesta.html(elm.respuesta_seleccion);
    			elem_respuesta.find('.__opciones_respuesta').removeClass('quest_option_single quest_option_multiple').addClass('quest_option_single');
    		}
    		if(tipo=='multiple'){
    			elem_respuesta.html(elm.respuesta_seleccion);
    			elem_respuesta.find('.__opciones_respuesta').removeClass('quest_option_single quest_option_multiple').addClass('quest_option_multiple');
    		}
    		if(tipo=='open_sm'){
    			elem_respuesta.html(elm.respuesta_corta)
    		}
    	},
    	agregaOpcion: function(elemento){
    		var opcionesRespuesta = $(elemento).find('.__opciones_respuesta');
    		opcionesRespuesta.append(elm.opcion_individual);
    		opcionesRespuesta.find('.__opcion_individual').last().focus();
    	},

    }

    var ctxMod = {
    	idmodal: '#modalEncuesta',
    	getData: function(){
    		var cuestionario = {
    			id: $("#c_id").val(),
    			nombre: $("#c_nombre").val(),
    			titulo: $("#c_titulo").val(),
    			_token : $('input[name=_token]').val(),
    			elementos: [],
    		};
    		var elementosDOM = $(`#contenido_elementos .__elemento`);
    		elementosDOM.each(function(k,elemento){
    			var tipoElemento = $(elemento).attr('__tipo');
    			var objElem = {};
    			objElem.tipo = tipoElemento;
    			objElem.texto = $(elemento).find('.__texto').first().val();
    			objElem.descripcion = $(elemento).find('.__descripcion').first().val() || '';
    			objElem.orden = k;

    			if(tipoElemento == 'titulo'){}
    			else if(tipoElemento == 'pregunta'){ 	
    				objElem.config = `{"tipo_respuesta" : "${ $(elemento).find('select.__elem_tipo_respuesta').first().val()}" }`;
    			}
    			objElem.opciones = [];

    			$(elemento).find('.__opciones_respuesta .__opcion_individual').each(function(k, op){
    				var opc = $(this);
    				var objOp = {
    					opcion: opc.val(),
    					orden: k
    				}
    				objElem.opciones.push(objOp);
    			})
    			cuestionario.elementos.push(objElem);
    		})
    		return cuestionario;
    	},
    	setData: function(objCuestionario){
    		$("#contenido_elementos").html('');
    		/* NUEVO : verifica si no existe el obj es para nuevo cuestionario*/
    		if(!objCuestionario){    			
    			$(`${ctxMod.idmodal} textarea, ${ctxMod.idmodal} input`).val('');
    			elm.adicionaElemento("titulo");
    		}
    		/* EDITAR */
    		else{
    			$("#c_id").val(objCuestionario.id);
    			$("#c_nombre").val(objCuestionario.nombre);
    			$("#c_titulo").val(objCuestionario.titulo);

    			objCuestionario.elementos.forEach(function(objElem)
    			{
    				elm.adicionaElemento(objElem.tipo);
    				var elemento = $("#contenido_elementos .__elemento").last();
    				$(elemento).find('.__texto').val(objElem.texto);
    				$(elemento).find('.__descripcion').val(objElem.descripcion);

    				if(objElem.tipo == 'pregunta'){
    					var cnf = objElem.config;
    					$(elemento).find('select.__elem_tipo_respuesta').val(cnf.tipo_respuesta);
    					$(elemento).find('select.__elem_tipo_respuesta').trigger('change');

    					if(cnf.tipo_respuesta == 'single' || cnf.tipo_respuesta == 'multiple' ){
    						$(elemento).find('.__opciones_respuesta').html(''); // limpia el espacio de opciones, por la opcion por defecto que deja el select trigger
    						objElem.opciones.forEach(function(el) {
    							var opcionIndividual = $(elm.opcion_individual)
    							opcionIndividual.find('.__opcion_individual').val(el.opcion);
    							$(elemento).find('.__opciones_respuesta').append(opcionIndividual);
    						});
    					} 		
    				}
    			});
    		}    		
    	},
    	save: function(){
    		var objSend = ctxMod.getData();
    		$.post(ctxG.rutabase + '/savecuestionario', objSend, function(res){
    			ctxG.mostrarMensajeFloat(res);
    			var cuestionario = res.data
    			$(`${ctxMod.idmodal} #c_id`).val(cuestionario.id);
    		});
    		
    	},
    	init: function(){
    		/* heigh automatica en textareas*/
        	$('#modalEncuesta').on('change drop keydown cut paste','textarea', function() {
        		$(this).height('auto');
        		$(this).height($(this).prop('scrollHeight'));
        	});

        	/* click en los span botones para agregar pregunta o tituklo*/
        	$(`${ctxMod.idmodal} #agregar_elementos span`).click(function(){
        		var tipo = $(this).attr('id').replace('agregar_','');
        		elm.adicionaElemento(tipo);        		
        	})

        	/* Acciones mouse over y seleccion de algun elemento*/
        	$(ctxMod.idmodal).on('mouseenter ', '.__elemento', function(){
        		var div = $(this);
        		div.addClass('elemento_over');
        		div.prepend(elm.acciones_elemento);        	
        	});
        	$(ctxMod.idmodal).on('mouseleave', '.__elemento', function(){
        		var div = $(this);
        		div.removeClass('elemento_over')
        		div.find('.__acciones_elemento').remove();      	
        	});
        	$(ctxMod.idmodal).on('click', '.__elemento', function(){
        		var div = $(this);
        		$(".__elemento").removeClass('elemento_sel');
        		div.addClass('elemento_sel');
        	});

        	/*  click en Acciones del elemento */
        	$(ctxMod.idmodal).on('click', '.__acciones_elemento span', function(){
        		elm.ejecutaAccionElemento( $(this).attr('accion'), $(this).closest('.__elemento') );
        	});

        	/* Seleccionar el tipo de respuesta */
        	$(ctxMod.idmodal).on('change', 'select.__elem_tipo_respuesta', function(){
        		elm.seleccionaTipoRespuesta( $(this).val(), $(this).closest('.__elemento'))
        	});

        	/* CLick en Agregar opcion*/
        	$(ctxMod.idmodal).on('click', '.__agregar_opcion_individual', function(){
        		elm.agregaOpcion( $(this).closest('.__elemento'))
        	});  
        	/* Mouse enter de las opciones  y sus acciones*/
        	$(ctxMod.idmodal).on('mouseenter ', 'li.__opcion_individual', function(){
        		var div = $(this);
        		div.children('span').removeClass('hidden');        	
        	});
        	$(ctxMod.idmodal).on('mouseleave', 'li.__opcion_individual', function(){
        		var div = $(this);
        		div.children('span').addClass('hidden');      	
        	});

        	/*  click en Acciones de la opcion */
        	$(ctxMod.idmodal).on('click', '.__opciones_respuesta li span', function(){
        		elm.ejecutaAccionElemento( $(this).attr('accion'), $(this).closest('li') );
        	});
        	/* boton guardar */
        	$(ctxMod.idmodal + " .__save").click(function(){
        		ctxMod.save();
        	});
    	}

    }


$(function(){

    listeners = (function(){
    	/* para los forms*/
        $( "form" ).submit(function( event ) {
          event.preventDefault();
        });

        /* Click boton nueva encuesta*/
        $("#nueva_encuesta").click(function(){
        	ctxQ.nuevaEncuesta();
        });


        /* mouse over y seleccion  de THUMBNAIL __id_c  se refiere a idCuestionario*/
        $('body').on('mouseenter ', '[__id_c]', function(){
        	var div = $(this);
        	div.prepend(ctxQ.elems.opcionesThumbnail);        	
        });
        $('body').on('mouseleave', '[__id_c]', function(){
        	var div = $(this);
        	div.find('.__acciones_thumbnail').remove();      	
        });

        /* click en las opciones del thumbnail*/
        $('body').on('click', '.__acciones_thumbnail .btn', function(){
        	var ac = $(this).attr('accion');
        	var id = $(this).closest('[__id_c]').attr('__id_c');
        	ctxQ.ejecutaAccionThumbnail(ac, id);
        });


        /* DEL MODAL y Encuesta modo edicion edit*/
        var initModal = ctxMod.init();

       



        /* boton cancelar */
        $(".__cancel").click(function(){
        	$.magnificPopup.close();
        });

    })()

    ctxQ.fillLista();


});

</script>



@endpush
