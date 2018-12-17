<!DOCTYPE html>
<html>

<head>
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <title>Encuestas</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @yield('headerIni')

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="/libs/admindesigns/vendor/plugins/magnific/magnific-popup.css">
    <!-- Admin Forms CSS -->
    <link rel="stylesheet" type="text/css" href="/libs/admindesigns/assets/admin-tools/admin-forms/css/admin-forms.css">
    <!-- Admin Modals CSS -->
    <link rel="stylesheet" type="text/css" href="/libs/admindesigns/assets/admin-tools/admin-plugins/admin-modal/adminmodal.css">
    <!-- Theme CSS -->
    <link rel="stylesheet" type="text/css" href="/libs/admindesigns/assets/skin/default_skin/css/theme.css">



    <!-- Favicon -->
    <link rel="shortcut icon" href="/libs/admindesigns/assets/img/ico.ico ">

    <style media="screen">
        .activo{
            background-color: #e5e5ee;
        }

        /*demo styles*/
        body {
            min-height: 2000px;
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

    @yield('header')

</head>

<body class="admin-panels-page sb-l-c sb-l-o" data-spy="scroll" data-target="#nav-spy" data-offset="300">
    <!-- Start: Main -->
    <div id="main">
        <!-- Start: Header -->
        <header class="navbar navbar-fixed-top bg-light ">
            <div class="navbar-branding">
                
                {{-- <span id="toggle_sidemenu_l" class="glyphicons glyphicons-show_lines"></span> --}}
                <ul class="nav navbar-nav pull-right hidden">
                    <li>
                        <a href="#" class="sidebar-menu-toggle">
                            <span class="octicon octicon-ruby fs20 mr10 pull-right "></span>
                        </a>
                    </li>
                </ul>
            </div> 
            <ul class="nav navbar-nav navbar-left">
                <div class="text-primary-darker pt15 fs20"><i clasS="fa fa-th-list fa-lg"></i> <span class="" > <b> <b class="text-warning-darker"> E </b>n c u e s t a s</b> <span class="fs14">v1.1 </span></span></div>
                
                {{-- ___________ icono ruby que habilita opciones sobre menu --}}
                {{-- <li >
                    <a class="sidebar-menu-toggle" href="#">
                        <span class="octicon octicon-ruby fs18"></span>
                    </a>
                </li> --}}
                {{-- <li>
                    <a class="topbar-menu-toggle" href="#">
                        <span class="glyphicons glyphicons-show_thumbnails fs16"></span>
                    </a>
                </li> --}}
                {{-- <li>
                    <span id="toggle_sidemenu_l2" class="glyphicon glyphicon-log-in fa-flip-horizontal hidden"></span>
                </li> --}}

                {{-- ______________ menu desplegable de config --}}
                {{-- <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <span class="glyphicons glyphicons-settings fs14"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href="javascript:void(0);">
                                <span class="fa fa-times-circle-o pr5 text-primary"></span> Reset LocalStorage </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <span class="fa fa-slideshare pr5 text-info"></span> Force Global Logout </a>
                        </li>
                        <li class="divider mv5"></li>
                        <li>
                            <a href="javascript:void(0);">
                                <span class="fa fa-tasks pr5 text-danger"></span> Run Cron Job </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                <span class="fa fa-wrench pr5 text-warning"></span> Maintenance Mode </a>
                        </li>
                    </ul>
                </li>--}}
                {{-- <li class="hidden-xs" title="Pantalla completa">
                    <a class="request-fullscreen toggle-active" href="#">
                        <span class="octicon octicon-screen-full fs18"></span>
                    </a>
                </li>  --}}
            </ul>

            {{-- ______________opcion buscar --}}
            {{-- <form class="navbar-form navbar-left navbar-search ml5" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Buscar..." value="">
                </div>
            </form> --}}

            {{-- ______________opciones de usuario --}}
            <ul class="nav navbar-nav navbar-right">
                <li class="ph10 pv20 hidden-xs"> <i class="fa fa-circle text-tp fs8"></i>
                </li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle fw600 p15" data-toggle="dropdown">
                        {{-- <img src="{{ asset('sty-mode-2/assets/img/avatars/1.jpg') }}" alt="avatar" class="mw30 br64 mr15"> --}}
                        <span>{{ Auth::user()->username }}</span>
                        <span class="caret caret-tp hidden-xs"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-persist pn w250 bg-white" role="menu">

                        <li class="br-t of-h">
                            <a href="{{ url('/home') }}" class="fw600 p12 animated animated-short fadeInDown">
                                <span class="fa fa-gear pr5"></span> Cerrar Modulo </a>
                        </li>
                        <li class="br-t of-h">
                            <a href="#" class="fw600 p12 animated animated-short fadeInDown" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <span class="fa fa-power-off pr5"></span> Cerrar Sesión </a>
                                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </header>
        <!-- End: Header -->
 
        





<!-- Start: Sidebar -->
        <aside id="sidebar_left" class="nano sidebar-default affix has-scrollbar">
            <div class="nano-content">
                <!-- sidebar menu -->
                <!-- ================ MENU PRINCIPAL===================== -->
                {{-- <ul class="nav sidebar-menu fs10" id="menu-principal">
                </ul> --}}
                <div class="sidebar-toggle-mini">
                    <a href="#">
                        <span class="fa fa-sign-out"></span>
                    </a>
                </div>
                <!-- END:  sidebar menu -->
            </div>
        </aside>

        <!-- Start: Content-Wrapper -->
        <section id="content_wrapper">


            <!-- Start: breadcrumb -->
            <!--_______________________  BREAD CRUM -->
            <header id="topbar" >
               {{-- @yield('title-topbar') --}}
               <div class="row">
                    <div class="topbar-left ">
                        <ol class="breadcrumb">
                            <li class="crumb-active">
                                <a id="breadcrumb1" href=""></a>
                            </li>
                            <li class="crumb-icon">
                                <a id="breadcrumb2" href="/">
                                    <span class="glyphicon glyphicon-home"></span>
                                </a>
                            </li>
                            <li class="crumb-link">
                                <a id="breadcrumb3" href="/">Inicio</a>
                            </li>
                            <li id="breadcrumb4" class="crumb-trail"></li>
                        </ol>
                    </div>
                    {{-- <div class="topbar-right ">
                        <div class="ml15 ib va-m" id="toggle_sidemenu_r">
                            <a href="#" class="pl5"> <i class="fa fa-sign-in fs22 text-primary"></i>
                                <span class="badge badge-hero badge-danger">3</span>
                            </a>
                        </div>
                    </div> --}}
                </div>
                <div class="row">
                    <div class="text-center">
                        <h4 id="tituloCabecera"></h4>
                        <h5 id="titulo2Cabecera"></h5>
                    </div>
                </div>
            </header>
            <!-- End: breadcrumb -->

            <!-- Begin: Content -->
            <!-- =========================== CONTENIDO ============================ -->
            <section id="content" class="table-layout_ animated fadeIn col-md-10 col-md-offset-1" style="min-height: 3500px;">
                @yield('content')
            </section>
            <!-- End: Content -->

        </section>

 

    </div>
    <!-- End: Main -->




    <!-- BEGIN: PAGE SCRIPTS -->

    <!-- jQuery -->
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
{{--         <script type="text/javascript" src="/libs/admindesigns/assets/js/main.js"></script>
        <script type="text/javascript" src="/libs/admindesigns/assets/js/demo.js"></script> --}}


    <script type="text/javascript">
        jQuery(document).ready(function() {

            "use strict";

            // // Init Theme Core
            // Core.init();

            // // Init Theme Core
            // Demo.init();






        });




$(function(){


        masterPage = {
            activarMenu: function(mn){
                if(mn=='0')  // si es 0 se abren todos los menus
                    $("#menu-principal .grupo").addClass('menu-open');
                else {
                    $("#menu-principal  li").removeClass('activo');
                    $('#M'+mn).addClass('activo');
                    padre = $('#M'+mn).parent().parent();
                    padre.find('a').addClass('menu-open');
                }
            },
            generarMenu: function(idplan, idMenuActivo){

            },
            cargaMenu: function(htmlmenu){
                $("#menu-principal").html( htmlmenu);

                // Init Theme Core
                // Core.init();
                // Init Theme Core
                // Demo.init();
            },
            configuraMenu: function(plan){
                etapas = plan.etapas_completadas.split('|').filter(function(val){
                    return val != '';
                });
                $("#menu-principal i").remove();
                var icon = '<i class="fa fa-tags pull-right text-success" style="font-size: 10px; "></i>';
                etapas.forEach(function(idmenu){
                    $("#" + idmenu).append(icon);
                });

                // coloca el titulo del menu Politica Sectorial
                (plan.cod_tipo_plan == 'PSDI') ? $("#27 span").html('Política Sectorial')
                                    :  $("#27 span").html('Política Institucional');

            },



        }





})

    </script>
    <!-- END: PAGE SCRIPTS -->
  @stack('script-head')
</body>

</html>
