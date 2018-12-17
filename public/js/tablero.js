    /*-----------------------------------------------------------------------
     *      cnf variables de configuracion  del modulo, como coleres, iconos y otros
     */
    var cnf = {
        // rutabase: '/api/modulopriorizacion',
        rutabase: '/visualizador/api',
        m : {   // m menu
            activoPri : 'activoPri',
            activoSub : 'activoSub',
            bgSub : 'bg-dark-dark',
            bgHeaderSub : 'bg-dark-light',
            bgTituloSub : 'bg-default-light tituloDetail',
        },
        c : {  // c contenido
            img: {  
                'imagen_por_default':'/img/icon-graf/3.png',
                '1':'/img/icon-graf/1.png',
                '2':'/img/icon-graf/2.png',
                '3':'/img/icon-graf/3.png',
                '4':'/img/icon-graf/4.png',
                '5':'/img/icon-graf/5.png',
                '6':'/img/icon-graf/6.png',
                '7':'/img/icon-graf/7.png',
                '8':'/img/icon-graf/8.png',
                '9':'/img/icon-graf/9.png',
                '10':'/img/icon-graf/10.png',
                '11':'/img/icon-graf/11.png',
                '12':'/img/icon-graf/12.png',
                '13':'/img/icon-graf/13.png',
                '14':'/img/icon-graf/14.png',
                '15':'/img/icon-graf/15.png',
                '16':'/img/icon-graf/16.png',
                '17':'/img/icon-graf/17.png',
                '18':'/img/icon-graf/18.png',
                '19':'/img/icon-graf/19.png',
                '20':'/img/icon-graf/20.png',
                '21':'/img/icon-graf/21.png',
                '22':'/img/icon-graf/22.png',
                '23':'/img/icon-graf/23.png',
                '24':'/img/icon-graf/24.png',
                '25':'/img/icon-graf/25.png',
                '26':'/img/icon-graf/26.png',
                '27':'/img/icon-graf/27.png',
                '28':'/img/icon-graf/28.png',
                '29':'/img/icon-graf/29.png',
                '30':'/img/icon-graf/30.png',
                '31':'/img/icon-graf/31.png',
                '32':'/img/icon-graf/32.png',
                '33':'/img/icon-graf/33.png',
                '34':'/img/icon-graf/34.png',
                '35':'/img/icon-graf/35.png',
                '36':'/img/icon-graf/36.png',
                'departamento':'/img/icon-graf/r_departamento.png',  
                'urbano_rural':'/img/icon-graf/r_urbano_rural.png',
                'genero':'/img/icon-graf/genero.png',
                'pobreza_extrema':'/img/icon-graf/pex.png',
                'pobreza_moderada':'/img/icon-graf/pmo.png',
                'desempleo':'/img/icon-graf/desem.png',
            },            
        },

    }

    /*----   ctxG variable que contiene el contexto global, variables globales */
    var ctxG = {
        nodos : [],
        nodoSel : {},  // elemento menu  nodo seleccionado
        varEstActual : {},    // objeto JSON VariableEstadisticaActual del nodoSel.configuracion 
        varEstActual_Unidades:{}, // objeto de las unidades de medida
        varEstActual_ArchivosExt: {},
        collection : [],
        pivotInstancia:{},
        pivot:{
            data : [], // Datos del pivot  en formato collection 
            dataGraph : [],
            dimColumna : [],
            dimFila : [],
            total: 0, t_cols : {}, t_filas : {}, total_p : 0, tp_cols : {}, tp_filas: {},
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
                midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
            });
        }, 
        tituloModal: function(titulo){
            $(".__modal_titulo").html(titulo);
        }
    }

    /*---     ctxM variable que contiene el contexto del menu ,  */
    var ctxM = {
        creaMenuBaseHtml : function(){
            $.get(cnf.rutabase + '/menustablero', function(res){
                ctxG.nodos = res.nodosMenu;                

                ctxC.workspace(res.mensaje.split('_')[1]);
                var html = `<div class="input-group ">
                                <input type="text" id="txtBuscaMenu" class="form-control" placeholder="buscar ..." style="border-radius: 8px 0 0 0">
                                <span class="input-group-addon" style="border-radius:  0 8px 0px 0">
                                    <i class="fa fa-search"></i>
                                </span>
                            </div>`;
                ctxG.nodos.forEach(function(nodo){
                    html += `<li class="sidebar-label pt20">${nodo.nombre}</li>`; 
                    html += ctxM.crearSubmenusHtml(nodo);
                });
                masterPage.cargaMenu(html);
                $("#menu-principal #txtBuscaMenu").val('');
                    
            })                     
        },
        crearSubmenusHtml : function(itemSel){
            if(itemSel != null)
            {
                var submenusN2 = itemSel.hijos;   
                var htmlmenu = '';           

                for(i=0; i< submenusN2.length; i++)
                {
                    subN2 = submenusN2[i];
                    // cabecera del menu desplegable
                    htmlmenu += `<li>
                                    <a  class="accordion-toggle grupo" href="#">
                                        <span class="glyphicons glyphicons-fire"></span>
                                        <span class="sidebar-title">${subN2.nombre }</span>
                                        <span class="caret"></span>
                                    </a>`;
                    htmlmenu += '<ul class="nav sub-nav">';

                    for(j=0; j< subN2.hijos.length; j++)
                    {
                        var hijo = subN2.hijos[j];
                        if (hijo.tipo == 'titulo')
                            htmlmenu += "<li class='list-group-item " + cnf.m.bgTituloSub + " ' >" + hijo.nombre + "</li>";
                        else if (hijo.tipo == 'link')
                        {
                            htmlmenu +=`<li id="M${hijo.id}">
                                            <a href="#"  id="${hijo.cod_str}" class="nodo_menu block">                                            
                                               <i class=' ${ (hijo.id_dash_config)? "fa fa-tags  text-info": ""}'></i>   <span> ${hijo.nombre}  </span>
                                            </a>
                                        </li>`;
                        }
                    }
                    htmlmenu += `</ul>
                                </li>`;                    
                }
                return htmlmenu;
            }
        },

        
        
        // activarElem: function(elem)
        // {
        //     if(elem.nivel == 1)
        //     {
        //         $("#menuPrincipal a.nodo_menu").removeClass(cnf.m.activoPri);
        //         $("#menuPrincipal #" + elem.cod_str).addClass(cnf.m.activoPri);
        //     }
        //     else if(elem.nivel == 3)
        //     {
        //         $("#menuDetalle a.nodo_menu").removeClass(cnf.m.activoSub);
        //         $("#menuDetalle #" + elem.cod_str).addClass(cnf.m.activoSub);
        //     }
        // },
        obtenerNodo :  function(cod_str){
            interacciones = cod_str.length / 2;
            var elem = {};
            arr = ctxG.nodos;
            for(i = 0 ; i< interacciones; i++ ) {
                elem = arr.find(function(item){                  
                    return  (item.cod_str == cod_str.substring(0, item.nivel * 2 ))
                });
                if(elem.cod_str == cod_str)
                    return elem;

                if(elem && elem.hijos && elem.hijos.length > 0)
                    arr = elem.hijos;
            }
            return null;

        },
        buscaMenu: function(){
            var filtro = $("#menu-principal #txtBuscaMenu").val().toUpperCase();
            var elems = $("#menu-principal ul a");   
            for (i = 0; i < elems.length; i++) {
                var elem = elems.eq(i);
                if (elem.html().toString().toUpperCase().indexOf(filtro) > -1) {
                    elem.show();
                } else {
                    elem.hide();

                }
            }
        }
    }



    /* Para el modal de menus */
    var ctxMenuCnf = {
        idModal: '#modalMenuCnf',
        mostrarModal: function(){
            ctxG.showModal(this.idModal);
            ctxMenuCnf.mostrarFormulario('op_menus_agregar');

            $.get(cnf.rutabase + '/getconnections', function(res){
                $(`${ctxMenuCnf.idModal} #conexiones`).html(
                    res.data.reduce(function(carr, elem){
                        return carr + `<option value='${elem}'>${elem}</option>`
                    },'<option></option>') 
                )
            });
            ctxMenuCnf.fillComboTitulosMenus('titulos');
        },
        mostrarFormulario: function(id_obj){
            $("#" + id_obj).parent().find('span').each(function(){
                    $(this).removeClass('activo');
                });
            $("#" + id_obj).addClass('activo');
            if(id_obj == 'op_menus_agregar') {
                $("#div_agregar_menusvistas").show()
                $("#div_modificar_menus").hide()
            }
            if(id_obj == 'op_menus_modificar') {
                $("#div_agregar_menusvistas").hide()
                $("#div_modificar_menus").show()
            }

        }, 
        cargarVistasConexion: function(){
            var objsend = { connection : $(`${ctxMenuCnf.idModal} #conexiones`).val() };
            $.get(cnf.rutabase + '/getviews', objsend , function(res){
                html = `<div class="p10 br-b br-t"><span class="text-default  fa fa-lg fa-circle-o __view_select_all" style="cursor:pointer"> </span> </div>`
                html += `<div style="max-height: 300px; overflow-y: scroll;" class="p10">`;
                html += res.data.reduce(function(carry, elem){
                    return carry + `<div class="p5 pl15 mt5 br-b"> <span class="text-default fa fa-circle-o" __attr_view_name="${elem.db_object}" style="cursor:pointer"></span>  <span>${elem.db_object}<span></div>`
                }, '');
                html += ` </div>`;

                $("#contenedor_vistas").html(html)
            });
        },
        cargarColsDimensiones: function(){
            var elem =  $("#contenedor_vistas .__vista_checked").first();
            var objsend = { 
                connection : $(`${ctxMenuCnf.idModal} #conexiones`).val(),
                tabla : elem.attr('__attr_view_name')
            };

            $.get(cnf.rutabase + '/getcolumns', objsend , function(res){
                var htmldims = res.data.reduce(function(carry, elem){
                    return carry + `<option value="${elem.column_name}">${elem.column_name} </option>`
                },'');

                $("#campos_excepciones, #agregador, #combinacion_defecto").html(htmldims);


            });
        },
        fillComboTitulosMenus: function(nivel, codstr){
            if(nivel=='titulos'){
                var opts = ctxG.nodos.reduce(function(carry, elem){
                    return carry + `<option value="${elem.cod_str}">${elem.nombre}</option>`
                }, ''); 
                $("#titulo_menu1").html( opts );
                $("#titulo_menu2").html( '' );
            }
            if(nivel=='subtitulos') { 
                if(codstr) {
                    console.log($("#titulo_menu1").val())
                    var nodoP = ctxG.nodos.find(function(elem){
                        return elem.cod_str.trim() == codstr.toString().trim();
                    });
                    if(nodoP){ 
                        console.log(nodoP)
                        var opts = nodoP.hijos.reduce(function(carry, elem){
                            return carry + `<option value="${elem.cod_str}">${elem.nombre}</option>`
                        }, ''); 
                        $("#titulo_menu2").html( opts );
                    }
                }
                else{
                    $("#titulo_menu2").html( '' );
                }
            }
        },
        guardarMenuViews: function(){
            var objvistas = {
                connection : $(`${ctxMenuCnf.idModal} #conexiones`).val(),
                vistasSel : _.map( $(ctxMenuCnf.idModal + " .__vista_checked") , function(elem){
                    return $(elem).attr('__attr_view_name');                    
                }),
                campos_excepciones: $("#campos_excepciones").val(),
                agregador: $("#agregador").val(),
                combinacion_defecto: $("#combinacion_defecto").val(),
                titulo_menu1: $("#titulo_menu1").val() ,
                titulo_menu2: $("#titulo_menu2").val() , 
                _token : $('input[name=_token]').val(),

            };
            

            $.post(cnf.rutabase + "/savemenuviews", objvistas, function(res){

            } );

        }
    }

    /*----   ctxC variable que contiene el contexto del Contenido, contenedorPredefinidos, titulos, new update del config */
    var ctxC = {
        contenedorPredefinidos: $("#contenedorPredefinidos"),
        contenedorDatos : $("#contenedorDatos"),
        divDatos : $(".divPivot"), // los dos pivot de admin y consulta
        divGrafico : $("#divGrafico"),
        titulo: $("#titulo"),
        tituloGrafico: $("#tituloGrafico"),
        tituloDatos: $(".tituloDatos"),  //son dos uno de admin y otro de consulta       
        cargarHTMLPredefinidos: function(variableEst){
            ctxC.contenedorPredefinidos.html('');
            predef = variableEst.sets_predefinidos;
            for(i=0; i< predef.length; i++)
            {
                item = predef[i];
                imagen = cnf.c.img['imagen_por_default'];
                for(key in cnf.c.img)
                {                     
                    // if(key.indexOf(item.imagen) !== -1 && item.imagen != '')  //Si existe la coincidencia que alguna imagen en cnf.c.img contenga la imagen del predefido entonces se asigna esa imagen
                    if(item.imagen != '' && key == item.imagen)
                        imagen = cnf.c.img[key]; 
                }

                var divImghtml = `<div id="${i}" class="__item_campo_predefinido ml15 mt10"  title="${item.etiqueta}"  style="cursor:pointer; float:left;">
                                        <img  src="${imagen}" alt="${item.etiqueta}" class="image" style="width:50px;height:40px">
                                        <div style="font-size:9px" class="text text-default">  ${item.etiqueta}  </div>
                                    </div>`;
                ctxC.contenedorPredefinidos.append(divImghtml);
            }            
        },
        cargarAnexosExternos: function(variableEst, archivos_ext){
            dashboards_ext = variableEst.dashboards_ext;
            var pv_m = 0;

            if(dashboards_ext && dashboards_ext.length > 0){

                var opts =  dashboards_ext.reduce(function(carry, elem){
                    return carry + `<option value="${elem.url}">${elem.descripcion}</option>`;
                },'');
                $("#iframe_combo").html(opts);
                $("#iframe_url").attr('src', $("#iframe_combo").val());
                $("#pv_submenu_3").show();
                pv_m ++;
            }
            else{
                $("#iframe_combo").html('<option>SIN INFO</option>');
                $("#pv_submenu_3").hide();
            }


            if(archivos_ext && archivos_ext.length > 0){
                var html = archivos_ext.reduce(function(carry, elem){
                    return carry + `<div class="row bb m-b-10">
                                        <div class="col-sm-1"><i class="fa fa-square-o " ></i></div>
                                        <div class="col-sm-8  ">Archivo: <a href="/archivos/archivos_anexos/${elem.nombre}" target="_blank">${elem.nombre} </a><br>
                                            ${elem.descripcion}
                                        </div>
                                                    
                                        
                                    </div>`
                },'');
                $("#archivos_ext div").html(html);
                $("#pv_submenu_2").show();
                pv_m ++;
            }
            else{
                $("#pv_submenu_2").hide();
            }

            (pv_m == 0) ? $("#submenus_pv").hide(150) : $("#submenus_pv").show(150);

        },
        submenu_pv_activo :  function(index){
            $("#submenus_pv li").removeClass('activo');            
            $("#pv_submenu_" + (index)).addClass('activo');
            $("#contenido_principal").slickGoTo(index-1);
        },
        obtenerData: function(nodoSel){
            ctxC.showLoading(1);            
            $.post(cnf.rutabase + '/datosVariableEstadistica', 
                {
                    id_dash_config: nodoSel.id_dash_config, 
                    _token : $('input[name=_token]').val(),
                }, 
                function(res){   
                    if(res.status == 'error'){
                        ctxC.showError(res.mensaje, 'bg-warning darker');
                    }
                    else{
                        ctxG.varEstActual = res.configuracionObj;
                        ctxG.set_predef_actual = ctxG.varEstActual.sets_predefinidos[0]; // por defecto el primero
                        ctxG.set_predef_actual.index = 0;
                        ctxC.actualizaTitulos();
                        ctxC.cargarHTMLPredefinidos(ctxG.varEstActual);   
                        $.get(cnf.rutabase + '/datosVariableEstadistica/archivosext_varest', {id_dash_config: nodoSel.id_dash_config,}, function(res){
                            ctxG.varEstActual_ArchivosExt = res.data;
                            ctxC.cargarAnexosExternos(ctxG.varEstActual, ctxG.varEstActual_ArchivosExt ); 

                            if((ctxG.varEstActual_ArchivosExt && ctxG.varEstActual_ArchivosExt.length > 0) || (ctxG.varEstActual.dashboards_ext && ctxG.varEstActual.dashboards_ext.length > 0 ))
                            {
                                $("#pv_submenu_1").show();
                                if(ctxG.varEstActual_ArchivosExt && ctxG.varEstActual_ArchivosExt.length > 0)
                                    $("#pv_submenu_2").show()
                                else
                                    $("#pv_submenu_2").hide();


                                if(ctxG.varEstActual.dashboards_ext && ctxG.varEstActual.dashboards_ext.length > 0 )
                                    $("#pv_submenu_3").show()
                                else
                                    $("#pv_submenu_3").hide()

                            }
                            else
                            {
                                $("#pv_submenu_1, #pv_submenu_2, #pv_submenu_1").hide();
                            }

                        });
                          
                        ctxG.collection = res.collection;
                        ctxG.varEstActual_Unidades.valor_unidad_medida = res.unidad_medida.valor_defecto_um;
                        ctxG.varEstActual_Unidades.valor_tipo = res.unidad_medida.valor_tipo;

                        ctxC.mostrarData(ctxG.collection);
                        // ctxC.submenu_pv_activo(2);
                        ctxC.submenu_pv_activo(1);
                        ctxC.showLoading(0);

                    }
            });
        },
        mostrarData: function(collection){
            ctxPiv.pivottableUI();
            ctxGra.colocarOpcionesPredefinidas();
            // ctxGra.graficarH();
        },
        actualizaTitulos: function(){
            this.titulo.html('<h4>'  + ctxG.nodoSel.padre + ': ' + ctxG.nodoSel.nombre + '</h4>');
            this.tituloDatos.html('');
            this.tituloGrafico.html( '');
        },
        mostrarPantallas: function(op){
            // $("#divTitulo a").removeClass('disabled');
            // $("#btn_" + op).addClass('disabled'); 
            // if(op == 'grafico')
            // {                
            //     this.contenedorDatos.show();
            //     this.divGrafico.show();
            //     this.divDatos.hide();
            // }
            // else if (op=='tabla')
            // {
            //     this.contenedorDatos.show();
            //     this.divGrafico.hide();
            //     this.divDatos.show();
            // }
            // else
            // {
            //     ctxC.contenedorDatos.hide();
            // }
        },  
        workspace: function (usr = '') {
            if(usr=='success') ocultar = false;
            if(usr=='access') ocultar = true;
            if(usr=='')
                ocultar = $("#btn_vista_Usuario i").hasClass('fa-user-plus');
            $("#divDatosUI").attr('hidden', ocultar);
            $("#divDatosRead").attr('hidden',!ocultar);
            $("#configuracionGrafico").attr('hidden', ocultar);
            $("#btn_menuconfig_acciones").attr('hidden', ocultar);

            $("#btn_vista_Usuario i").removeClass('fa-user-plus fa-user');
            $("#btn_vista_Usuario i").addClass( ocultar ? 'fa-user' : 'fa-user-plus');      
        },
        showLoading : function(op){
            $("#vistaInicio").attr('hidden', true);
            $("#loading").attr('hidden', ( op == 0) );
            $("#contenedor").attr('hidden', ( op == 1 ) );
            $("#div_error").attr('hidden', true );
        },    
        showError: function(msg, bg_color){
            $("#vistaInicio").attr('hidden', true);
            $("#loading").attr('hidden', true);
            $("#contenedor").attr('hidden', true);

            if(msg == false || msg == '' || msg == 0)
                $("#div_error").attr('hidden', true)
            else {
                $("#div_error").attr('hidden', false);
                $("#div_error div").addClass(bg_color);
                $("#div_error span").html(msg);
            }
        }
    };

    /*----------------  Modal para predefinidos   ---------------------------  */
    var ctxmodPred = {
        predefModal : $("#predefModal"),
        mostrarModal: function(op)
        {
            var oculta = op == 'del';
            $('#predefNewUpdate').attr('hidden', oculta);            
            $('#predefDel').attr('hidden', !oculta);
            this.cargarImagenes(); 

            function cargaPredef(predef){
                $("#predefModal #predef_imagen_previsualizacion").attr("src",cnf.c.img[predef.imagen] || '');
                $("#predefModal #divTextoImagen").html(predef.etiqueta || '');  
                $("#predefModal #predef_etiqueta").val(predef.etiqueta || '');
                $("#predefModal #predef_posicion").val( parseInt(predef.index) + 1 || '');
                $("#predefModal #predef_imagen").val(predef.imagen || '');
                $("#predefModal #accion").val(op);
            }

            if(op == 'del') {
                cargaPredef(ctxG.set_predef_actual);
                ctxG.tituloModal("Eliminar Visualización");
            }
            if(op =='update') {
                cargaPredef(ctxG.set_predef_actual);
                ctxG.tituloModal("Guardar Visualización Actual");
            }
            if(op == 'new') {
                cargaPredef({});
                ctxG.tituloModal("Nueva Visualización");
                $("#predefModal #predef_posicion").val(ctxG.varEstActual.sets_predefinidos.length + 1);
            }
            ctxG.showModal(ctxmodPred.predefModal);

        },
        guardarPredef: function(){
            var op = $("#predefModal #accion").val();
            var config = {
                etiqueta : $("#predefModal #predef_etiqueta").val(),
                imagen : $("#predefModal #predef_imagen").val(),
                x: ctxG.pivotInstancia.cols,
                y: ctxG.pivotInstancia.rows,
                agregacion:  ctxG.pivotInstancia.aggregatorName,
                filtros: (function(){
                    var filtro = [];
                    _.mapObject(ctxG.pivotInstancia.inclusions,function(val, key){
                        val.map(function(elem){
                            filtro.push(key + " = '" + elem + "'");
                        })                         
                    })
                    return filtro;
                })(),
                grafico: {
                    tipo : $("#opcionesGrafico").val()
                }
            }

            var setsPredef = ctxG.varEstActual.sets_predefinidos;
            var predef = ctxG.set_predef_actual;
            var posicion = isNaN($("#predef_posicion").val() ) ? 999 : $("#predef_posicion").val() - 1 ;
            if(op == 'del')
                setsPredef.splice(predef.index, 1);
            if(op == 'new'){
                setsPredef.splice(posicion, 0, config);
            };
            if(op == "update"){
                setsPredef.splice(predef.index, 1);
                setsPredef.splice(posicion, 0, config);
            };
            var configuracionString = JSON.stringify(ctxG.varEstActual);
            var objReq = {
                id_dash_menu : ctxG.nodoSel.id,
                configuracionString : configuracionString,
                _token : $('input[name=_token]').val(),
            };
            $.post(cnf.rutabase + "/tablero/guardaconfiguracion", objReq, function(res){
                ctxC.cargarHTMLPredefinidos(ctxG.varEstActual);  
                $.magnificPopup.close();
            });
        },
        cargarImagenes : function(){
            var divImagenes = '<table><tr>';
            _.mapObject(cnf.c.img, function(val, key){
                divImagenes += '<td><div class="ml5 mr5" style="cursor:pointer; border: 1px solid #fff; " onMouseOver= "this.style.border = \'#aaa 1px solid\'"  onMouseOut= "this.style.border = \'1px solid #fff\'">\
                <img id="' + key + '"  src="'+ val + '" alt="" class="image" style="width:80px;height:60px"></div>\
                </td>';                    
            });
            divImagenes += '</tr></table>'
            $("#selectImagenes").html(divImagenes);
        },
    }

    /*----------------  Modal anexar externos -------------------------------------   */
    var ctxmodAnx = {
        mostrarModal: function(op)
        {
            var ocultar = op == 'anexar_archivo_ext';
            $('.div_anexar_dashboard').attr('hidden', ocultar);            
            $('.div_anexar_archivo').attr('hidden', !ocultar);

            ctxG.tituloModal( (op == 'anexar_archivo_ext') ? 'Agregar archivos anexos descargables' : 'Vincular con otra fuente');
            $("#btn_anexar span").html( (op == 'anexar_archivo_ext') ? 'Agregar y Guardar archivo' : 'Agregar link externo' );
            $("#anexarModal input, #anexarModal textarea").val('');
            $("#anexarModal #elementos").html('');
            ctxmodAnx.archivos = [];
            var elems =  (op == "anexar_archivo_ext") ?  ctxG.varEstActual.archivos_ext : ctxG.varEstActual.dashboards_ext            
            
            if(elems && elems.length > 0)  {
                var html = elems.reduce(function(carry, elem){
                    return carry + ctxmodAnx.generaDivs(elem);
                }, '');
                $("#anexarModal #elementos").html(html);
            }
            $("#anexarModal #accion").val(op);

            ctxG.showModal("#anexarModal");

        },
        generaDivs: function(elem){
            return  `<div class="mt5 bb p5 pv_anexo">
                        <div><span class="pv_anx_url">${elem.url}</span> <a href="javascript:void(0)" class="pull-right pv_del_anx"><i class="text-danger fa fa-minus-circle "></i></a></div>
                        <div><b><span class="pv_anx_desc">${elem.descripcion}</span></b></div>
                    </div>`;
        },
        anexarElemento : function(){    
            var elem = {};
            elem.descripcion = $("#anexarModal #descripcion").val();

            if($("#anexarModal #accion").val() == 'anexar_archivo_ext'){                
                elem.url = $("#anx_archivo").val();
                elem.archivo = $('#anx_archivo').prop('files')[0];
                elem.descripcion = $("#anexarModal #descripcion").val();
                ctxmodAnx.guardar(elem);
            }
            else{                
                elem.url = $("#anexarModal #anx_dashboard").val();
                if(elem.url.substring(0,7).toLowerCase() == '<iframe' || elem.url.substring(0,6).toLowerCase() == '<frame' ) {
                    elem.url = $(elem.url).attr('src') || 'error: No valido';
                }
            }

            $("#anexarModal #elementos").append(ctxmodAnx.generaDivs(elem));
            //limpia inputs
            $("#anexarModal #descripcion, #anexarModal textarea").val('');
        },
        guardar: function(elemArch){
            var op = $("#anexarModal #accion").val();

            var anexos = [];
            var files=[];
            $("#anexarModal #elementos .pv_anexo").each(function(){
                var anx = {
                    'url' : $(this).find('.pv_anx_url').html(),
                    'descripcion' : $(this).find('.pv_anx_desc').html(),
                };
                anexos.push(anx);
            });
            
            if(op == 'anexar_archivo_ext'){

                var objReq = {
                   archivos_anexos : anexos,
                   id_dash_config : ctxG.nodoSel.id_dash_config,
                   _token : $('input[name=_token]').val(),
                };
                /*primer post que envia los archivos existentes (los que no se eliminaron)*/
                $.post(cnf.rutabase + '/tablero/saveannexedfiles',objReq, function(res){
                    if(elemArch){
                        var formData = new FormData($("#form_anexos")[0]);
                        formData.append('_token', $('input[name=_token]').val());
                        formData.append('descripcion', elemArch.descripcion);
                        formData.append('archivo_nuevo', elemArch.archivo);
                        formData.append('id_dash_config', ctxG.nodoSel.id_dash_config);
                        
                        /* segundo post que envia el archivo seleccionado */
                        $.ajax({
                            url: cnf.rutabase + '/tablero/saveannexedfiles', 
                            type: "POST",
                            data: formData,
                            dataType: 'json',
                            contentType: false,
                            processData: false,
                            success: function(res){
                                console.log(res.mensaje);
                            }
                        });

                        $.get(cnf.rutabase + '/datosVariableEstadistica/archivosext_varest', {id_dash_config: ctxG.nodoSel.id_dash_config}, function(res){
                            ctxG.varEstActual_ArchivosExt = res.data;
                            ctxC.cargarAnexosExternos(ctxG.varEstActual, ctxG.varEstActual_ArchivosExt ); 

                        });
                    }
                });               
            }
            if(op == 'anexar_dashboard_ext'){
                ctxG.varEstActual.dashboards_ext = anexos;
                ctxG.varEstActual.archivos_ext = null;

                var configuracionString = JSON.stringify(ctxG.varEstActual);
                var objReq = {
                    id_dash_menu : ctxG.nodoSel.id,
                    configuracionString : configuracionString,
                    _token : $('input[name=_token]').val(),
                };

                $.post(cnf.rutabase + "/tablero/guardaconfiguracion", objReq, function(res){
                    ctxC.cargarAnexosExternos(ctxG.varEstActual);                     
                });

            };  
            $.magnificPopup.close();         

        },

    }

    /*------------------------- ctxPiv variable que contiene el contexto del Pivot   */
    var ctxPiv = {
        pvtTableUI: $("#pvtTableUI"),
        pvtTableUIRead: $("#pvtTableUIRead"),
        configParaPivotT : function(set_predefinido){
            var config = {}
            config.columns = set_predefinido.x;
            config.rows = set_predefinido.y;
            config.inclusions = _.chain(set_predefinido.filtros)
                                    .map(function(item){                    
                                        condicion =  item.split("=").map(function(s){ return s.toString().trim().replace(/'/g,"");});
                                        var obj = { 'key': condicion[0], 'value' : condicion[1] };
                                        return obj;   
                                    }).groupBy(function(item){
                                        return item.key;
                                    }).mapObject(function(items, key){
                                        var arr = _.map(items, function(elem){ return elem.value; });
                                        return arr
                                    }).value();
            var existeAgregacion = $.pivotUtilities.locales.es.aggregators[set_predefinido.agregacion]; 
            config.aggregatorName = existeAgregacion ?  set_predefinido.agregacion : "Suma de enteros";
            config.vals = ["valor"];         
            return config;
        },
        pivottableUI: function()
        {
            var pivotConfig = ctxPiv.configParaPivotT(ctxG.set_predef_actual);
            ctxPiv.pvtTableUI.pivotUI(ctxG.collection, {
                cols: pivotConfig.columns, 
                rows: pivotConfig.rows,
                aggregatorName: pivotConfig.aggregatorName,
                vals: pivotConfig.vals,
                inclusions: pivotConfig.inclusions,
                onRefresh: function(p) {
                    ctxG.pivotInstancia = p;
                    ctxPiv.trnDatosDePivot();
                    ctxGra.graficarH();
                    ctxPiv.pivottableUIRead(p);
                }
            }, true, "es");
        }, 
        pivottableUIRead: function(instanciaP)
        {
            ctxPiv.pvtTableUIRead.pivotUI(ctxG.collection, {
                cols: ctxG.pivotInstancia.cols, 
                rows: ctxG.pivotInstancia.rows,
                aggregatorName: ctxG.pivotInstancia.aggregatorName,
                vals: ctxG.pivotInstancia.vals,
                inclusions: ctxG.pivotInstancia.inclusions,
            }, true, "es");
        }, 
        trnDatosDePivot: function(){
            var tree = ctxG.pivotInstancia.pivotData.tree;
            dim_columna = ctxG.pivotInstancia.cols.join('-');
            dim_fila = ctxG.pivotInstancia.rows.join('-');
            ctxG.pivot.data = [];
            for (row in tree){
                for(col in tree[row])
                {
                    var item = {};  
                    arg =   tree[row][col];                   
                    item['valor'] =arg.value();
                    item[dim_columna] = col;
                    item[dim_fila] = row;
                    ctxG.pivot.data.push(item); 
                }
            }
            ctxG.pivot.dimColumna = dim_columna;
            ctxG.pivot.dimFila = dim_fila;
            ctxPiv.obtenerTotales();
            ctxGra.transformarDatosParaGrafico();            
        },
        obtenerTotales: function(){
            var t_cols = {},  t_filas = {}, tp_cols = {}, tp_filas = {};            
            total = ctxG.pivot;
            dimCol = ctxG.pivot.dimColumna;
            dimFil = ctxG.pivot.dimFila;
            _.each(ctxG.collection, function(item){
                t_cols[item[dimCol]] = ( isNaN( t_cols[item[dimCol]])  ? 0 : t_cols[item[dimCol]]) + Number(item.valor );
                t_filas[item[dimFil]] = ( isNaN(t_filas[item[dimFil]]) ? 0 : t_filas[item[dimFil]] ) + Number(item.valor); 
            });
            total.t_cols = t_cols;
            total.t_filas = t_filas;
            total.total = Object.keys(t_cols).reduce(function(total, key){
                return total + t_cols[key];
            }, 0);

            /* Totales Sumas parciales */
            _.each(ctxG.pivot.data, function(item){
                tp_cols[item[dimCol]] = ( isNaN( tp_cols[item[dimCol]])  ? 0 : tp_cols[item[dimCol]]) + Number(item.valor );
                tp_filas[item[dimFil]] = ( isNaN(tp_filas[item[dimFil]]) ? 0 : tp_filas[item[dimFil]] ) + Number(item.valor); 
            });
            total.tp_cols = tp_cols;
            total.tp_filas = tp_filas;
            total.total_p =  Object.keys(tp_cols).reduce(function(total, key){
                return total + tp_cols[key];
            }, 0);
        }, 
    }

    /*------------------------- ctxGra variable que contiene el contexto del grafico  */
    var ctxGra = {
        colocarOpcionesPredefinidas: function()
        {
            try { 
                $("#opcionesGrafico").val(ctxG.set_predef_actual.grafico.tipo);
                if($("#opcionesGrafico").val() == null)
                    $("#opcionesGrafico").val('spline');
            }
            catch(e)/* si no existe le asigna el primer grafico*/           
                { $('#opcionesGrafico option')[0].selected = true;}
        },
        transformarDatosParaGrafico: function()
        {
            var datosGraph = {};
            var pivotData = ctxG.pivotInstancia.pivotData;            
            var pivot = ctxG.pivot;
            // var factorPorcentual = ctxG.pivotInstancia.aggregatorName[0] == '%' ? 100 : 1;
            datosGraph.categorias = pivotData.colKeys.map(function(cat, key){
                return cat.join('-');
            });
            
            datosGraph.series = _.chain(pivot.data).groupBy(function(item){
                                        return item[pivot.dimFila]
                                    }).map(function(setDatos, key){
                                        serie = {};
                                        serie.name = key;

                                        /* con valores ceros los discontinuos */
                                        serie.data = datosGraph.categorias.map(function(elem){
                                            var s = { name : elem, y: 0};
                                            setDatos.forEach(function(sd){
                                                if(sd[pivot.dimColumna] == elem){
                                                    var num;
                                                    if(ctxG.pivotInstancia.aggregatorName[0] == "%")                
                                                        num =  parseFloat((Math.round( sd.valor * 100 * 10 )/10 ).toString()) ;
                                                    else 
                                                        num = sd.valor;
                                                    
                                                    s.y = num;
                                                }
                                            });
                                            return s;
                                        });
                                        return serie;

                                        /*Series discontinuadas */
                                        // serie.data = setDatos.map(function(elem){  
                                        //     var num;
                                        //     if(ctxG.pivotInstancia.aggregatorName[0] == "%")                
                                        //         num =  parseFloat((Math.round( elem.valor * 100 * 10 )/10 ).toString()) ;
                                        //     else 
                                        //         num = elem.valor;
                                        //     return { name : elem[pivot.dimColumna], y: num};
                                        // });
                                        // return serie;
                                    }).value();
            ctxG.pivot.dataGraph = datosGraph;

        },
        graficarH : function()   {
            var tituloChart = ctxG.varEstActual.variable_estadistica;
            var unidadMedida = ctxG.varEstActual.porcentaje  ? ' (porcentaje) ' : '(' + ctxG.varEstActual_Unidades.valor_tipo +': ' + ctxG.varEstActual_Unidades.valor_unidad_medida + ') ';
            var subtituloChart = ctxG.pivot.dimFila + ' vs. ' + ctxG.pivot.dimColumna;
            var tipo = $("#opcionesGrafico").val().split('-');
            var stacked = (tipo[1]  == 'stacked') ? 'normal' : (tipo[1]  == 'stackedp') ? 'percent': '';
            var ifLabel = $("#viewlabel").prop("checked");
            var tipo3d = $("#view3d").prop("checked");;

            var vale = tipo[0];
            var tool = '{series.name}: <b>{point.y:.1f} (' +  ctxG.varEstActual_Unidades.valor_unidad_medida +') </b> ';            
            if(tipo[1]){
                tool += '<br>porcentaje: <b>{point.percentage:.1f} %</b>';
            }

            // colores= [
            // '#E86D00', '#FFB97F', '#E8E400', '#80699B', '#00E820',
            // '#4572A7', '#AA4643', '#89A54E', '#70E800', '#3D96AE',      
            // '#00E8D6', '#00A5E8', '#0054E8', '#A013E6', '#E800CF', 
            // '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92', '#E80000',
            // '#E8007B', '#FF766D', '#EDFF6D', '#8AFF6D', '#89FFEA',
            // '#FF72F4', '#84345E', '#348445', '#C4D21C', '#9C0000'
            // ];

            // colores= [
            // '#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572',
            //     '#FF9655', '#FFF263', '#6AF9C4',
            //     '#E86D00', '#FFB97F', '#E8E400', '#80699B', '#00E820',
            //     '#4572A7', '#AA4643', '#89A54E', '#70E800', '#3D96AE', 
            //     '#00E8D6', '#00A5E8', '#0054E8', '#A013E6', '#E800CF', 
            //     '#E8007B', '#FF766D', '#EDFF6D', '#8AFF6D', '#89FFEA',
            // ],
            // colores = _.chain(colores)
            //                 .map(function(color){ 
            //                     return { id : _.random(100), color:color    }
            //                 }).sortBy('id')
            //                 .map(function(obj){
            //                     return obj.color
            //                 })
            //                 .value();

            // Highcharts.setOptions({
            //     colors: colores,
            // });

            var json = {   
                chart : {
                    type: tipo[0],
                    options3d: {
                        enabled: tipo3d,
                        alpha: tipo=='pie' ? 45 : 23, 
                        beta: 0, depth: 60
                    },
                    zoomType: 'xy',
                },
                title : {
                    text: tituloChart   
                },   
                subtitle :{
                    text: subtituloChart
                }, 
                xAxis :{
                    type: 'category',
                    categories: ctxG.pivot.dataGraph.categorias,
                    // max:  ctxG.pivot.dataGraph.categorias.length
                },
                yAxis : {
                    title: {
                        text: unidadMedida
                    }
                },
                tooltip:  {
                    pointFormat: tool,
                },

                plotOptions : {
                    series: {
                        marker : { 
                            symbol:'circle',
                            // radius: 3,
                        },
                        stacking: stacked,
                        dataLabels: {
                            enabled: ifLabel,
                            format: '{y:.1f}'
                            // color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || '#ccc'
                        }
                    },
                    column: {
                        stacking: stacked,
                        dataLabels: {
                            enabled: true,
                            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || '#ccc'
                        }
                    },
                    area: {
                        stacking: stacked,
                        lineColor: '#eee',
                        lineWidth: 1,
                    },
                    pie: {
                        innerSize: 100,
                        depth: 45,
                        allowPointSelect: true,
                        cursor: 'pointer',
                        // depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '{point.category}'
                        }
                    },                    
                },
                series : ctxG.pivot.dataGraph.series, 
            }
            $('#divChart').highcharts(json);
        }
    }