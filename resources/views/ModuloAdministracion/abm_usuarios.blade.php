@extends('layouts.principal_tpl')

@section('header')

<link rel="stylesheet" href="/libs/jqwidgets5.5.0/jqwidgets/styles/jqx.base.css" type="text/css" />
<link rel="stylesheet" href="/libs/jqwidgets5.5.0/jqwidgets/styles/jqx.ui-start.css" type="text/css" />

<link rel="stylesheet" href="/libs/bower_components/jqueryui/jquery-ui.min.css" type="text/css" />
@endsection


@section('content')
  <div class="container" id="miContenedor"> 

    <div style="width: 800px; height: 100%; margin-left: auto; margin-right: auto;"> <!-- Centrador --> 
      <h2>Listado de Usuarios</h2>

      <div id="UsuariosGrid">
      </div>

      <div id="mensajesUsuarios" style="width: 800px; min-height:20px;"></div>

    </div> <!-- Centrador --> 

  </div>

  <div id="popupUsuario">
     <div>Usuario</div>
     <div style="overflow: hidden;">
       <div id="mensajes"></div>
       <form class="form" id="form" target="form-iframe"  method="post" action="guardarusuario" >
           <table class="tabla-ventana">
               <tr>
                   <td align="right" style="color:#000000; width: 50%;">Usuario:</td>
                   <td><input name="username" type="text" id="userInput" /></td>
                   <input type="hidden" name="id" id="idInput" value="">
                   <input type="hidden" name="_token" value="{{ csrf_token() }}">
               </tr>
               <tr>
                   <td align="right" style="color:#000000">Contraseña:</td>
                   <td><input name="password" type="password" id="passwordInput" /></td>
               </tr>
               <tr>
                   <td align="right" style="color:#000000">Confirme Contraseña:</td>
                   <td><input type="password" id="passwordConfirmInput" /></td>
               </tr>
               <tr>
                   <td align="right" style="color:#000000">Nombre Completo:</td>
                   <td><input name="name" type="text" id="realNameInput" /></td>
               </tr>
               <tr>
                   <td align="right" style="color:#000000">Carnet de Identidad:</td>
                   <td><input name="carnet" type="text" id="carnetInput" /></td>
               </tr>
               <tr>
                   <td align="right" style="color:#000000">E-mail:</td>
                   <td><input name="email" type="text" id="emailInput" placeholder="someone@mail.com" /></td>
               </tr>
               <tr>
                   <td align="right" style="color:#000000">Teléfono:</td>
                   <td><div name="telefono" id="telefonoInput"></div></td>
               </tr>

               <tr>
                   <td align="right" style="color:#000000">Rol:</td>
                   <td align="left"><div name="id_rol"  id="id_rol"></div></td>
               </tr>
               <tr>
                   <td align="right" style="color:#000000">Institución:</td>
                   <td><input name="id_institucion" type="hidden" id="id_institucion" />
                       <input type="text" id="institucion_label"  placeholder="Escriba y seleccione.." /></td>
               </tr>
               <tr>
                   <td align="right" style="color:#000000">Cargo:</td>
                   <td><input type="text" name="cargo" id="cargoInput" /></td>
               </tr>
               <tr>
                   <td align="right" style="color:#000000">Permisos de ABM:</td>
                   <td align="left"><div name="permisos_abm"  id="permisos_abm"></div></td>
               </tr>
               <tr>
                   <td align="right"></td>
                   <td style="padding-top: 10px;" align="right">
                       <input style="margin-right: 5px;" type="button" id="Save" value="Guardar" />
                       <input id="Cancel" type="button" value="Cerrar" />
                   </td>
               </tr>

           </table>
       </form>
     </div>
  </div>


  <div id="popupDeleteConfirm">
    <div>
        <img width="14" height="14" src="" alt="" />
        ¿Borrar Datos?
    </div>
    <div>
      <div>
          ¿Realmente desea borrar el Registro?, una vez borrados los datos no es posible deshacer la acción, para borrar presione el boton "OK", para cancelar el borrado precione "Cancelar".
      </div>
      <div>
        <div style="float: right; margin-top: 15px;">
            <input type="button" id="okDelete" value="OK" style="margin-right: 10px" />
            <input type="button" id="cancelDelete" value="Cancelar" />
        </div>
      </div>
    </div>
  </div>


@endsection


@push('script-head')

  {{-- <script type="text/javascript" src="/libs/js/jquery-u5.min.js"></script> --}}

  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxcore.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxdata.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxbuttons.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxscrollbar.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxmenu.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxgrid.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxgrid.selection.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxgrid.filter.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxlistbox.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxcombobox.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxdropdownlist.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxcheckbox.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxwindow.js"></script>

  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxgrid.sort.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxgrid.pager.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxgrid.columnsresize.js"></script>

  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxgrid.edit.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxpanel.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxcalendar.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxdatetimeinput.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxnumberinput.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxinput.js"></script>
  
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxgrid.columnsresize.js"></script>

  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/globalization/globalize.js"></script>
  {{-- <script type="text/javascript" src="/libs/js/jqwidget5-localization.js"></script> --}}

  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxdata.export.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxgrid.export.js"></script>

  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxmaskedinput.js"></script>
  <script type="text/javascript" src="/libs/jqwidgets5.5.0/jqwidgets/jqxvalidator.js"></script>


  <style type="text/css">

    html, body, #wrapper, .container-fluid {
      height:100% !important;
      padding: 0 0 0 0 !important;
    }

    #page-wrapper{
      height: calc(100% - 60px) !important;
      /*min-height: calc(100% - 160px) !important; - 60px -60px (100 80 60) cabecera y piede pagina*/
    }

    ul {
      margin: 0 !important;
    }

    h1, h2, h3, h4, h5, h6 {
      margin: 0 !important;
      padding: 10px 0 !important;
    }

    #miContenedor { /* para que en los celulares salga mas optimizada la pantalla */
      overflow:auto; 
      width: 100% !important; 
      /*height:calc(100% - 42px);*/
      min-height:calc(100% - 60px);
    }

    .jqx-validator-hint {  /* Cambiamos el color guindo de los mensajes del validador*/
      border: 1px solid #DEABA0;
      background-color: #de857b;
      opacity: 0.85;
    }

    .jqx-validator-hint-arrow{ /* creamos otro rombo */  
      margin: 3px 0px 0px 0px;
      width: 8px; 
      height: 8px; 
      border: 1px solid; 
      border-top-color: #de857b;
      border-right-color: #de857b;
      border-bottom-color: #DEABA0;
      border-left-color: #DEABA0;
      background: #de857b;
      -webkit-transform: rotate(45deg);
      -moz-transform: rotate(45deg);
      -ms-transform: rotate(45deg);
      -o-transform: rotate(45deg);
      transform: rotate(45deg);
      opacity: 0.85;
    }

    .jqx-validator-hint-arrow img {display: none;} /* ya no mostramos el rombo del validador*/ 

    .text-input-popup {
        height: 23px;
       width: 150px;
    }    
    
    .clase-iframe {
            border: none;
            clear: both;
            /*display: none;*/
            height: 50px;
            width: 800px;
    } 
    .tabla-ventana {
      width:100%;
    } 
    .tabla-ventana td {
      padding: 3px 0px 3px 2px;
    }   

    .ui-autocomplete-loading {
      background: white url("../images/ui-anim_basic_16x16.gif") right center no-repeat;
    } 

    #pagerUsuariosGrid > div{
        width: 400px !important;
    }

  </style>

<script type="text/javascript">

// ==================================================================================================
    

$(document).ready(function () {

    var Tema = 'ui-start';
    var Global_editrow = -1;
    var Global_nuevo = false;
    var Global_offsetFila = 0;
    var modifButton;
    var deleteButton;
    var Registros;
    var url = "./listausers2";

    var source =
    {
        datatype: "json",
        datafields: [
            { name: 'id', type: 'string' },
            { name: 'name', type: 'string' },
            { name: 'email', type: 'string' },
            { name: 'username', type: 'string' },
            { name: 'id_rol', type: 'int' }, // date  bool  number
            { name: 'rol', type: 'string' }, 
            { name: 'id_institucion', type: 'int' }, 
            { name: 'institucion_label', type: 'string' }, 
            { name: 'permisos_abm',  type: 'bool' },
            { name: 'cargo', type: 'string'},
            { name: 'carnet', type: 'string'},
            { name: 'telefono', type: 'string'}
        ],
        id: 'id',
        url: url,
        root: '',

        updaterow: function (rowid, rowdata, commit) { // esto servira para cuando editen en la fila
            // synchronize with the server - send update command
            // call commit with parameter true if the synchronization with the server is successful
            // and with parameter false if the synchronization failed.
            commit(true);
        },
        loadComplete: function () { // get data records.
            Registros = dataAdapter.records; // records.length  record.username
        }        
     };

    var dataAdapter = new $.jqx.dataAdapter(source);

/*
    // Datos del Combobox
    var rolesSource =
    {
        dataType: "json",
        dataFields: [
            { name: 'rol'},
            { name: 'id'}
        ],
        url: './listaroles_usu' //, async: false
    };
//    var rolesAdapter = new $.jqx.dataAdapter(rolesSource);

    var rolesAdapterAux;
    var rolesAdapter = new $.jqx.dataAdapter(rolesSource, {
        loadComplete: function () {
//            // get data records.
//            var records = rolesAdapter.records;
//            var length = records.length;
//            // loop through the records and display them in a table.
//            var msg='';
//            for (var i = 0; i < length; i++) {
//                var record = records[i];
//                msg += record.id + ' ' + record.rol + ' ';
//            }
//           alert(msg);
 
            var source =
            {
                localdata: rolesAdapter.records,
                datatype: "array"
            };
            var rolesAdapterAux = new $.jqx.dataAdapter(source);    
        }
    });
    rolesAdapter.dataBind(); //  var recordsRoles = dataAdapter.getrecords();
*/

    $("#UsuariosGrid").jqxGrid(
    {
        width: 800,
        source: dataAdapter,
        sortable: true,
        pageable: true,
        autoheight: true,
        showfilterrow: false, ///
        filterable: false, ///
        editable: false,
        // localization: getLocalization('es'),
        selectionmode: 'singlerow', // singlecell multiplerows multiplerowsextended multiplerowsadvanced
        columnsresize: true,

        ready: function () {
            // $("#UsuariosGrid").jqxGrid('sortby', 'name', 'asc'); ya no es necesario, se hace desde la BD
        },
        showtoolbar: true,
        rendertoolbar: function (statusbar) {
            // appends buttons to the status bar.
            var container = $("<div style='overflow: hidden; position: relative; margin: 2px;'></div>");
            var addButton = $("<div style='float: left; margin-left: 5px;'><span style='margin-left: 4px; position: relative; top: 2px !important;'>Adicionar</span></div>");
            modifButton = $("<div style='float: left; margin-left: 5px;'><span style='margin-left: 4px; position: relative; top: 2px !important;'>Editar</span></div>");
            deleteButton = $("<div style='float: left; margin-left: 5px;'><span style='margin-left: 4px; position: relative; top: 2px !important;'>Borrar</span></div>");
            var reloadButton = $("<div style='float: left; margin-left: 5px;'><span style='margin-left: 4px; position: relative; top: 2px !important;'>Recargar</span></div>");
            var searchButton = $("<div style='float: left; margin-left: 5px;'><span style='margin-left: 4px; position: relative; top: 2px !important;'>Buscar</span></div>");

            var pdfButton = $("<div style='float: right; margin-right: 5px;'></div>");
            var excelButton = $("<div style='float: right; margin-right: 5px;'></div>");
            container.append(addButton);
            container.append(modifButton);                    
            container.append(deleteButton);
            container.append(reloadButton);
            container.append(searchButton);
            container.append(pdfButton);
            container.append(excelButton);
            statusbar.append(container);
            addButton.jqxButton({  width: 100, height: 20, theme: Tema });
            modifButton.jqxButton({  width: 100, height: 20, disabled: true, theme: Tema }); 
            deleteButton.jqxButton({  width: 100, height: 20, disabled: true, theme: Tema }); 
            reloadButton.jqxButton({  width: 100, height: 20, theme: Tema });
            searchButton.jqxButton({  width: 100, height: 20, theme: Tema }); 
            pdfButton.jqxButton({  width: 25, height: 20, theme: Tema }); 
            excelButton.jqxButton({  width: 25, height: 20, theme: Tema }); 

            addButton.click(function (event) {

                    // get the clicked row's data and initialize the input fields.
                    $("#idInput").val('');
                    $("#realNameInput").val('');
                    $("#emailInput").val('');
                    $("#userInput").val('');
                    $("#passwordInput").val(''); 
                    $("#passwordConfirmInput").val('');
                    $('#telefonoInput').jqxMaskedInput('clear');
                    $("#id_rol").jqxComboBox('clearSelection'); 
                    $("#id_institucion").val('');
                    $("#institucion_label").val('');                    

                    //$("#id_rol").jqxComboBox('selectItem', dataRecord.id_rol ); 
                    $("#permisos_abm").jqxCheckBox({ checked: false });                 

                    $("#carnetInput").val('');
                    $("#cargoInput").val('');

                    // optiene la posision donde se desplegara la ventana pop-up en funcion de la fila y centro de la tabla.
                    var offsetTabla = $("#UsuariosGrid").offset();
                    var anchoTabla = $("#UsuariosGrid").width();
                    var anchoPopup = $("#popupUsuario").width(); 
                    var altoPopup = $("#popupUsuario").height();
                    var posicionXpopup = parseInt(offsetTabla.left) + parseInt(anchoTabla/2) - parseInt(anchoPopup/2);
                    var posicionYpopup = parseInt(100+offsetTabla.top+80) - parseInt(altoPopup/2);
                    $("#popupUsuario").jqxWindow({ position: { x: posicionXpopup, y: posicionYpopup } });
                    // show the popup window.
                    $("#popupUsuario").jqxWindow('open');
                    Global_nuevo=true;
            });

            modifButton.click(function (event) {
                if (Global_editrow > -1) {
                    // get the clicked row's data and initialize the input fields.
                    var dataRecord = $("#UsuariosGrid").jqxGrid('getrowdata', Global_editrow);
                    $("#idInput").val(dataRecord.id);
                    $("#realNameInput").val(dataRecord.name);
                    $("#emailInput").val(dataRecord.email);
                    $("#userInput").val(dataRecord.username);
                    $("#passwordInput").val(''); 
                    $("#passwordConfirmInput").val('');
                    
                    // no funca  $("#telefonoInput").val(''); // TODO:****     
                    // tampoco $('#telefonoInput').jqxMaskedInput('val', ''); 
                    if ( dataRecord.telefono )
                        $("#telefonoInput").val(dataRecord.telefono);
                    else  
                        $("#telefonoInput").jqxMaskedInput('clear');
                    $("#id_rol").jqxComboBox('selectItem', dataRecord.id_rol ); 

                    $("#id_institucion").val(dataRecord.id_institucion);
                    $("#institucion_label").val(dataRecord.institucion_label);            

                    $("#permisos_abm").jqxCheckBox({ checked: (dataRecord.permisos_abm.toString() === "true") }); // aveces dataRecord.permisos_abm es Objeto

                    $("#carnetInput").val(dataRecord.carnet);
                    $("#cargoInput").val(dataRecord.cargo);

                    // optiene la posision donde se desplegara la ventana pop-up en funcion de la fila y centro de la tabla.
                    var offsetTabla = $("#UsuariosGrid").offset();
                    var anchoTabla = $("#UsuariosGrid").width();
                    var anchoPopup = $("#popupUsuario").width(); 
                    var altoPopup = $("#popupUsuario").height();
                    var posicionXpopup = parseInt(offsetTabla.left) + parseInt(anchoTabla/2) - parseInt(anchoPopup/2);
                    var posicionYpopup = parseInt(Global_offsetFila+offsetTabla.top+80) - parseInt(altoPopup/2);
                    $("#popupUsuario").jqxWindow({ position: { x: posicionXpopup, y: posicionYpopup } });
                    // show the popup window.
                    $("#popupUsuario").jqxWindow('open');
                }
            });

            deleteButton.click(function (event) {
                    // optiene la posision donde se desplegara la ventana pop-up en funcion de la fila y centro de la tabla.
                var offsetTabla = $("#UsuariosGrid").offset();
                var anchoTabla = $("#UsuariosGrid").width();
                var anchoPopup = $("#popupDeleteConfirm").width(); 
                var altoPopup = $("#popupDeleteConfirm").height();
                var posicionXpopup = parseInt(offsetTabla.left) + parseInt(anchoTabla/2) - parseInt(anchoPopup/2);
                var posicionYpopup = parseInt(Global_offsetFila+offsetTabla.top+80) - parseInt(altoPopup/2);
                $("#popupDeleteConfirm").jqxWindow({ position: { x: posicionXpopup, y: posicionYpopup } });
                // show the popup window.
                $("#popupDeleteConfirm").jqxWindow('open');

/*   Este codigo ya esta en el boton de confirmacion de boprrado                
                var selectedrowindex = $("#UsuariosGrid").jqxGrid('getselectedrowindex');
                var rowscount = $("#UsuariosGrid").jqxGrid('getdatainformation').rowscount;
                var id = $("#UsuariosGrid").jqxGrid('getrowid', selectedrowindex);
                $("#UsuariosGrid").jqxGrid('deleterow', id);
                disableDelButtonAndAnothers();

                var parametros = { "id" : id,
                                   "_token" : "{{ csrf_token() }}"
                };                
                $.ajax({
                  type: "POST",
                  url: "./borrarusuario",
                  data: parametros,
                  success: function(datos){
                    $("#mensajesUsuarios").html(datos);
                  },
                  error: function(result) {
                    $("#mensajesUsuarios").html(result.responseText);
                  }
                });
*/
            });

            reloadButton.click(function (event) {
                disableDelButtonAndAnothers();
                $('#UsuariosGrid').jqxGrid('updatebounddata','data');
            });

            searchButton.click(function (event) {
                disableDelButtonAndAnothers();
                $("#UsuariosGrid").jqxGrid('showfilterrow', true);

                var filter = $("#UsuariosGrid").jqxGrid('filterable');
                $("#UsuariosGrid").jqxGrid('filterable', !filter); // sw
                $("#UsuariosGrid").jqxGrid('clearfilters');

            });

            excelButton.click(function (event) {
                $("#UsuariosGrid").jqxGrid('exportdata', 'xls', 'Usuarios');
            });

            pdfButton.click(function (event) {
                $("#UsuariosGrid").jqxGrid('exportdata', 'pdf', 'Usuarios');
            });

        },
/*        columns: [
          { text: 'Apellido', datafield: 'firstname', width: 200 },
          { text: 'Habilitado', datafield: 'available', columntype: 'checkbox', width: 125 },
          { text: 'Datum', datafield: 'date', columntype: 'datetimeinput', filtertype: 'date', width: 210, cellsalign: 'right', cellsformat: 'd' },
          { text: 'Ingresos', datafield: 'quantity', width: 80, cellsalign: 'right' },
          { text: 'Unidad', datafield: 'price', width: 90, cellsalign: 'right', cellsformat: 'c2' },
        ],*/
                columns: [
                  { text: 'Nombre Completo', dataField: 'name', width: 200 },
                  { text: 'Email', dataField: 'email', width: 200 },
                  { text: 'Usuario', dataField: 'username', width: 150 },
                  { text: 'Rol', dataField: 'rol', filtertype: 'checkedlist', width: 150 },
/*                    {
                        text: 'Rol', datafield: 'id_rol', displayField: 'rol', width: 150  

                        , columntype: 'combobox',
                        createeditor: function (row, column, editor) {
                            // assign a new data source to the combobox.
                            editor.jqxComboBox({ autoDropDownHeight: true, source: rolesAdapterAux, promptText: "- Seleccione -", displayMember: 'rol', valueMember: 'id' });  //width: '100%', height: 25,
                        },
                        initeditor: function (row, cellvalue, editor, celltext, pressedChar) {
                            editor.jqxComboBox({
                                selectedIndex: 2
                            });
                        },
                        // update the editor's value before saving it.
                        cellvaluechanging: function (row, column, columntype, oldvalue, newvalue) {
                            // return the old value, if the new value is empty.
                            if (newvalue == "") return oldvalue;
                        }
                    },                  
*/                    
                  { text: 'Permisos ABM', datafield: 'permisos_abm', columntype: 'checkbox', filtertype: 'bool', cellsalign: 'center', align: 'center' }
                ],

        theme: Tema
    });



    $('#UsuariosGrid').on('rowclick', function (event) { // solo para obtener la coordenada Y de la fila
          var args = event.args;
          var boundIndex = args.rowindex;
          var visibleIndex = args.visibleindex;
          var rightclick = args.rightclick; 
          var ev = args.originalEvent; 

          Global_offsetFila = parseInt(event.args.row.top);

    /*    // Inspecciona un Objeto de javascript 
        var msg = '';
        for (var property in obj )
        {
          if (typeof obj[property] == 'function')
          {
            var inicio = obj[property].toString().indexOf('function');
            var fin = obj[property].toString().indexOf(')')+1;
            var propertyValue=obj[property].toString().substring(inicio,fin);
            msg +=(typeof obj[property])+' '+property+' : '+propertyValue+' ;\n';
          }
          else if (typeof obj[property] == 'unknown')
          {
            msg += 'unknown '+property+' : unknown ;\n';
          }
          else
          {
            msg +=(typeof obj[property])+' '+property+' : '+obj[property]+' ;\n';
          }
        }
        alert(msg);
    */        
    });   
    
    function disableDelButtonAndAnothers(){
        if (deleteButton !== undefined) deleteButton.jqxButton({disabled: true });
        if (modifButton !== undefined) modifButton.jqxButton({disabled: true });
        var index = $("#UsuariosGrid").jqxGrid('getselectedrowindex');
        $('#UsuariosGrid').jqxGrid('unselectrow', index);
        $("#mensajesUsuarios").html('');
        Global_editrow = -1;
    };
   
    $('#UsuariosGrid').on('rowselect', function (event) { 
          var args = event.args;
          var rowBoundIndex = args.rowindex;
          var rowData = args.row;
          
          if (Global_Popup_listo) {
              deleteButton.jqxButton({disabled: false });  // Mostramos Boton Borrar
              modifButton.jqxButton({disabled: false });  // Mostramos Boton Modificar
          } else { 
            alert('faltan cargar datos...'); // es importante por que evita errores cuando los datos no esta bien cargados
          }

          Global_editrow = rowBoundIndex;
          $("#mensajesUsuarios").html("");             
    });   

    $('#UsuariosGrid').on('filter', function (event) {
        disableDelButtonAndAnothers();
    });   

    $('#UsuariosGrid').on('pagechanged', function (event) {
        disableDelButtonAndAnothers();
    });   

    $('#UsuariosGrid').on('sort', function (event) {  // esta evento se ejecuta antes que los botones esten creados 
        disableDelButtonAndAnothers();
    });   


//===========================================
//===== Desde aqui la ventana de Pop-up ===== 
//===========================================
    // initialize the input fields.
    // $("#name").jqxInput({ width: 150, height: 23, theme: Tema }); // no funca :(((

    $("#userInput").jqxInput({ theme: Tema });
    $("#userInput").width(200);
    $("#userInput").height(23);

    $("#passwordInput").jqxInput({ theme: Tema });
    $("#passwordInput").width(200);
    $("#passwordInput").height(23);

    $("#passwordConfirmInput").jqxInput({ theme: Tema });
    $("#passwordConfirmInput").width(200);
    $("#passwordConfirmInput").height(23);

    $("#realNameInput").jqxInput({ theme: Tema });
    $("#realNameInput").width(200);
    $("#realNameInput").height(23);

    $("#emailInput").jqxInput({ theme: Tema });
    $("#emailInput").width(200);
    $("#emailInput").height(23);


    $("#telefonoInput").jqxMaskedInput({ mask: '###-#####', width: 200, height: 23, theme: Tema});
    // initialize validator.
    $('#form').jqxValidator({
               // hintType: 'label',
                animationDuration: 500, // milisegundos
                theme: Tema,    
      rules: [
      { input: '#userInput', message: '¡El Usuario es necesario!', action: 'keyup, blur', rule: 'required' },
      { input: '#userInput', message: '¡debe contener entre 3 y 20 caracteres!', action: 'keyup, blur', rule: 'length=3,20' },
      { input: '#realNameInput', message: '¡El Nombre Completo es necesario!', action: 'keyup, blur', rule: 'required' },
      { input: '#realNameInput', message: 'El Nombre Completo debe contener solo letras!', action: 'keyup', rule: 'notNumber' },
      { input: '#realNameInput', message: '¡debe contener entre 3 y 100 caracteres!', action: 'keyup', rule: 'length=3,100' },
      { input: '#passwordInput', message: '¡La Contraseña es necesaria!', action: 'keyup, blur', rule: 'required' },
      { input: '#passwordInput', message: '¡debe contener entre 4 y 20 caracteres!', action: 'keyup, blur', rule: 'length=4,20' },
      { input: '#passwordConfirmInput', message: '¡La Contraseña es necesaria!', action: 'keyup, blur', rule: 'required' },
      { input: '#passwordConfirmInput', message: '¡No coinciden!', action: 'keyup, focus', rule: function (input, commit) {
        // call commit with false, when you are doing server validation and you want to display a validation error on this field. 
        if (input.val() === $('#passwordInput').val()) {
          return true;
        }
          return false;
        }
      },
      { input: '#emailInput', message: '¡El E-mail es necesario!', action: 'keyup, blur', rule: 'required' },
      { input: '#emailInput', message: '¡E-mail inválido!', action: 'keyup', rule: 'email' },
      { input: '#telefonoInput', message: '¡Teléfono es necesario!', action: 'keyup, blur', rule: 'required'},
      { input: '#telefonoInput', message: '¡Teléfono inválido!', action: 'keyup, blur', rule: 'length=8,9'}
      ]
    });

    // Datos del Combobox
    var rolesSourcePopup =
    {
        dataType: "json",
        dataFields: [
            { name: 'rol'},
            { name: 'id'}
        ],
        url: './listaroles_usu' //, async: false
    };

    var Global_Popup_listo = false; // evita que se habra el popup sin no estan sus datos listos
    var rolesAdapterPopup = new $.jqx.dataAdapter(rolesSourcePopup, {
        loadComplete: function () {  // no deberia abrirse el popup su no estan estos datos
            Global_Popup_listo = true; 
        }
    });


    $('#id_rol').jqxComboBox({ selectedIndex: 0,  source: rolesAdapterPopup, displayMember: "rol", valueMember: "id", height: 23, width: 200, theme: Tema });

    $("#institucion_label").jqxInput({ theme: Tema });
    $("#institucion_label").width(200);
    $("#institucion_label").height(23);

    $("#cargoInput").jqxInput({ theme: Tema });
    $("#cargoInput").width(200);
    $("#cargoInput").height(23);

    $("#carnetInput").jqxInput({ theme: Tema });
    $("#carnetInput").width(200);
    $("#carnetInput").height(23);

    $("#permisos_abm").jqxCheckBox({ width: 120, height: 25, theme: Tema });   
    // initialize the popup window and buttons.
    $("#popupUsuario").jqxWindow({
        width: 370, resizable: false,  isModal: true, autoOpen: false, cancelButton: $("#Cancel"), modalOpacity: 0.1, theme: Tema         
    });

    $("#popupUsuario").on('open', function () {
        //$("#name").jqxInput('selectAll');
    });

    $("#popupUsuario").on('close', function () {
        Global_nuevo=false;
        $('#form').jqxValidator('hide');
    });

 
    $("#Cancel").jqxButton({ width: 70, theme: Tema });
    $("#Save").jqxButton({ width: 70, theme: Tema });
    // update the edited row when the user clicks the 'Save' button.
    var Global_row;
    $("#Save").click(function () {

        if (Global_editrow >= 0 || Global_nuevo) {

          var validationResult = function (isValid) {
            if (isValid) {
              $("#form").submit();

              var selIndex = $('#id_rol').jqxComboBox('selectedIndex');
              var itemCB = $("#id_rol").jqxComboBox('getItem', selIndex ); 

              
              if (Global_nuevo) {
                  var row = { id:0, name: $("#realNameInput").val(), email: $("#emailInput").val(), username: $("#userInput").val(),
                              id_rol: itemCB.value, rol: itemCB.label, id_institucion: $("#id_institucion").val(), institucion_label: $("#institucion_label").val(), permisos_abm: $("#permisos_abm").jqxCheckBox('checked'), carnet: $("#carnetInput").val(), telefono: $("#telefonoInput").val(), cargo: $("#cargoInput").val()
                  };

                    


                  //$("#UsuariosGrid").jqxGrid('addrow', null, row); // no tiene aun ID... se lo pondra cuando el sumit (asincrono) nos devuelva la respuesta

                  Global_row = row;
              } else {
                  var row = { name: $("#realNameInput").val(), email: $("#emailInput").val(), username: $("#userInput").val(),
                              id_rol: itemCB.value, rol: itemCB.label, id_institucion: $("#id_institucion").val(), institucion_label: $("#institucion_label").val(), permisos_abm: $("#permisos_abm").jqxCheckBox('checked'), carnet: $("#carnetInput").val(), telefono: $("#telefonoInput").val(), cargo: $("#cargoInput").val()
                  };
                  var rowID = $('#UsuariosGrid').jqxGrid('getrowid', Global_editrow);
                  $('#UsuariosGrid').jqxGrid('updaterow', rowID, row);
              }
              $("#popupUsuario").jqxWindow('hide');
            }
         }
          
          $('#form').jqxValidator('validate', validationResult);

        }

    });

    // adjuntamos un manejador del submit al form
    $("#form").submit(function(event) {

      // Detenemos el envio normal del form
      event.preventDefault();

      // optenemos el atributo action del from ( <form action=""> )
      var $form = $( this ), url = $form.attr( 'action' );

      var parametros = $(this).serialize(); // serializamos los datos del form

      // Enviamos los datos usando post
      var posting = $.post( url, parametros );

      // evento: cuando los resultados son devueltos
      posting.done(function( data ) {
        var tipo_result = data.substring(0,2);
        if ( tipo_result == 'ID' ) {
          var ID = data.substring(3,data.indexOf("\n")) ;
          var mensaje = data.substring(data.indexOf("\n")+1, data.length-1);  
          $("#mensajesUsuarios").html(mensaje);      

          Global_row.id = parseInt(ID);
          //$('#UsuariosGrid').jqxGrid('updaterow', 0, Global_row); // buscamos al que le pusimos 0 por ID
           $("#UsuariosGrid").jqxGrid('addrow', null, Global_row); // aqui nomas va tiene que ser la insercion al grip del registro, una vez que tenemos el ID

        } else {
          $("#mensajesUsuarios").html(data);   
              
        }
      });

      // evento: si hubo algun error
      posting.error(function( data ) {
          $("#mensajesUsuarios").html(data.responseText);   
      });

    });

    $("#institucion_label").autocomplete({
/*        source: function( request, response ) { // por alguna razon mediante este metodo se añaden 2 parametros ('_' y 'callback' )
            $.ajax({  
                //url: "http://jqueryui.com/resources/demos/autocomplete/search.php",
                url: "./autocompletarinst", // NO FUNCA
                dataType: "jsonp",
                data: { term : request.term },
                success: function( respuesta ) {
                    alert(respuesta);
                    response( respuesta );
                }
            });
        }, 
*/        
        source: "./autocompletarinst",
        minLength: 2,
            select: function( event, ui ) {
                $('#id_institucion').val(ui.item.id); // + ui.item.value + ui.item.label
            },
            messages: {
                noResults: '',
                results: function() {
                    $("ul.ui-autocomplete").css({ 'width':'400px', 'height':'250px', 'overflow': 'auto', 'white-space': 'nowrap' });
                    $('ul.ui-autocomplete').css( "z-index",  '' + ($('#popupUsuario').css("z-index")+1));
                }
        },
        autoFocus: true
    });

//===========================================
//===== Hasta aqui la ventana de Pop-up ===== 
//===========================================


//===================================================================
//===== Desde aqui la ventana de Confirmacion de borrado Pop-up ===== 
//===================================================================
    $('#popupDeleteConfirm').jqxWindow({
        position: { x: 50, y: 50},
        theme: Tema,
        maxHeight: 160, maxWidth: 280, minHeight: 30, minWidth: 250, height: 155, width: 270,
        resizable: false, isModal: true, autoOpen: false, modalOpacity: 0.3,
        okButton: $('#okDelete'), cancelButton: $('#cancelDelete'),
        initContent: function () {
            $('#okDelete').jqxButton({ width: '65px', theme: Tema });
            $('#cancelDelete').jqxButton({ width: '65px', theme: Tema });
            
        }
    }); 
    $('#popupDeleteConfirm').on('open', function (event) { $('#cancelDelete').focus(); }); // no esta funcando :(
        
    $("#okDelete").click(function () {
        var selectedrowindex = $("#UsuariosGrid").jqxGrid('getselectedrowindex');
        var rowscount = $("#UsuariosGrid").jqxGrid('getdatainformation').rowscount;
        var idRow = $("#UsuariosGrid").jqxGrid('getrowid', selectedrowindex);

        var dataRecord = $("#UsuariosGrid").jqxGrid('getrowdata', selectedrowindex);

        $("#UsuariosGrid").jqxGrid('deleterow', idRow);
        disableDelButtonAndAnothers();

        var parametros = { "id" : dataRecord.id,
                       "_token" : "{{ csrf_token() }}"
        };                
        
        $.ajax({
            type: "POST",
            url: "./borrarusuario",
            data: parametros,
            success: function(datos){
                $("#mensajesUsuarios").html(datos);
            },
            error: function(result) {
                $("#mensajesUsuarios").html(result.responseText);
            }
        });
    });
//===================================================================
//===== Hasta aqui la ventana de Confirmacion de borrado Pop-up ===== 
//===================================================================








});  // FIN: $(document).ready(function () {
// ==================================================================================================

/*

$(function() {

});

*/





// function activarMenu(id,aux){
//     $('#'+id).addClass('active');
//     $('#'+aux).addClass('activeaux');
// }

// function menuModulosHideShow(ele){
//   //1 hide
//   //2 show
//   switch (ele) {
//     case 1:
//       $("body").addClass("content-wrapper")
//       $(".open-close i").removeClass('icon-arrow-left-circle');
//       $(".sidebar").css("overflow", "inherit").parent().css("overflow", "visible");
//     break;
//     case 2:
//       $("body").removeClass('content-wrapper');
//       $(".open-close i").addClass('icon-arrow-left-circle');
//       $(".logo span").show();
//       break;
//   }
// }

// $(document).ready(function(){
//   activarMenu('x','mp-7');
//   menuModulosHideShow(1)
// });

</script>
@endpush