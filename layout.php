<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title>SISTEMA DE RADIO-TAXI  "COOPERATIVA BENJAMÍN CARRIÓN"</title>
        <script language="javascript" type="text/javascript">
            //Detectando navegadores....
            var is_safari = navigator.userAgent.toLowerCase().indexOf('safari/') > -1;
            var is_opera = navigator.userAgent.toLowerCase().indexOf('opera/') > -1;
            var is_chrome= navigator.userAgent.toLowerCase().indexOf('chrome/') > -1;
            var ie=(document.all)? true:false;
            var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox/') > -1;

            if((is_safari||is_opera||ie)!=is_chrome){
                if(ie!=is_chrome){
                    alert("Incompatibilidad del navegador detectada");
                    var pagina = '<?php echo url_for('Redirecciones/internet') ?>';
                    var segundos = 0.1;
                    document.location.href=pagina;
                    setTimeout("redireccion()",segundos); 
                }else{
                    alert("Incompatibilidad del navegador detectada");
                    var pagina = '<?php echo url_for('Redirecciones/index') ?>';
                    var segundos = 0.1;
                    document.location.href=pagina;
                    setTimeout("redireccion",segundos); 
                }
 
            }else{
    
            }
        </script>
        <script type="text/javascript">
            var rootPath = '<?php echo $sf_request->getScriptName(), '/' ?>';
        </script>
        <?php include_http_metas() ?>
        <?php include_metas() ?>
        <?php include_title() ?>
        <link rel="shortcut icon" href="/favicon.ico" />
        <link rel="stylesheet" href="/css/central/menu.css" type="text/css  "/>
        <?php include_stylesheets() ?>
    </head>
    <body onload="iniciar()">
        <div id="container">
            <div id="header">
                <h1 id="titulo"> RADIO TAXI <sup >CENTRAL</sup></h1> 
                <h2> Benjamín Carrión </h2> 
            </div>

            <?php if ($sf_user->isAuthenticated() && $sf_user->hasCredential('operador')): ?>
                <nav class="animenu">	
                    <input type="checkbox" id="button">
                        <label for="button" onclick>Menu</label>
                        <ul>
                            <li>
                                <a href="">Clientes</a>
                                <ul>
                                    <li><a href="<?php echo url_for('cliente/new') ?>">Crear Cliente</a></li>
                                    <li><a href="<?php echo url_for('cliente/index') ?>">Lista de Clientes</a></li>
                                    <li><a href="<?php echo url_for('codigo/index') ?>">Lista de Códigos</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="">Propietarios y Unidades </a>
                                <ul>
                                    <li><a href="<?php echo url_for('propietario/index') ?>">Lista de Propietarios</a></li>
                                    <li><a href="<?php echo url_for('vehiculo/index') ?>">Lista de Unidades</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="<?php // echo url_for('carrera/gestion'); ?>">Carreras</a>
                                <ul>
                                    <li><a href="<?php echo url_for('carrera/index') ?>">Historial de Carreras</a></li>
<!--                                    <li><a href="<?php // echo url_for('carrera/gestion') ?>">Gestión de Carreras</a></li>-->
                                    <li><a href="<?php echo url_for('reportes/index')?>">Reportes</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="<?php echo url_for('carrera/simulacion') ?>">Gestión de Servicios</a>
                                <ul>                                    
                                    <li><a href="<?php echo url_for('carrera/simulacion') ?>">Gestionar Servicios</a></li>
                                    <li><a href="<?php echo url_for('sector/index') ?>">Lista de sectores</a></li>
                                    <li><a href="<?php echo url_for('sector/new') ?>">Nuevo Sector</a></li>
                                    <li><a href="<?php echo url_for('reservacion/index') ?>">Lista de reservaciones</a></li>
                                    <li><a href="<?php echo url_for('reservacion/new') ?>">Nueva Reservación</a></li>
                                </ul>      
                            </li>
                            <li id="user">
                                <a ><?php echo sfContext::getInstance()->getUser()->getGuardUser() //. $_SERVER["DOCUMENT_ROOT"]?></a>
                            </li>
                            <li>
                                <a href="<?php echo url_for('cooperativa/inicio') ?>" >Inicio</a>
                                <ul>
                                    <li><a id="last-a" href="<?php echo url_for('operador/show?id=' . sfContext::getInstance()->getUser()->getGuardUser()->getID()) ?>">Usuario</a></li>
                                    <li><a id="last-a" href="<?php echo url_for('sfGuardAuth/signout') ?>">Salir</a></li>
                                </ul>
                            </li>
                        </ul>
                </nav>
            <?php endif ?>
            <div id="content">
                <?php if ($sf_user->hasFlash('notice')): ?>
                    <div class="flash_notice">
                        <?php echo $sf_user->getFlash('notice') ?>
                    </div>
                <?php endif; ?>

                <?php if ($sf_user->hasFlash('error')): ?>
                    <div class="flash_error">
                        <?php echo $sf_user->getFlash('error') ?>
                    </div>
                <?php endif; ?>
                <div class="content">  
                    <?php echo $sf_content ?>
                </div>
            </div>

        </div>
        <div id="footer">
            <div class="content">
                <ul>
                    <!--                        <li ><a href="">Radio Taxi</a></li>
                                            <li><a href="">Indicaciones</a></li>-->
                </ul>
            </div>
        </div>
        <!-- Comienzo menu flotante -->
        <div id='menuflotante' style="display: none">
            <ul>
                <!--    <li class='current'><a expr:href='data:blog.homepageUrl'>Inicio</a></li>-->
                <li><a id="menu-opciones">OPCIONES</a></li>
                <li><a id="menu-unidades">UNIDADES</a></li>
                <li><a id="menu-notificaciones">CARRERAS EN CURSO<span style="display: none;" id="num-notificaciones" class="bubble"></span></a></li>
                <li><a id="menu-solicitudes">SOLICITUDES<span style="display: none;" id="num-solicitudes" class="bubble"></span></a></li>
                <li><a id="menu-reservaciones">RESERVACIONES<span style="display: none;" id="num-reservaciones" class="bubble"></span></a></li>
                <li><a id="menu-alertas">ALERTAS<span style="display: none;" id="num-alertas" class="bubble"></span></a></li>
            </ul>
        </div>
        <!-- Fin menu flotante -->
        <?php include_javascripts() ?>     
        <script type="text/javascript"> 
            var config = {
                '.chzn-select'           : {},
                '.chzn-select-deselect'  : {allow_single_deselect:true},
                '.chzn-select-no-single' : {disable_search_threshold:10},
                '.chzn-select-no-results': {no_results_text:'Oops, nothing found!'},
                '.chzn-select-width'     : {width:"95%"}
            }
            for (var selector in config) {
                $(selector).chosen(config[selector]);
            }
        </script>
    </body>
</html>

