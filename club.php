<?php
$quien_soy = 'club';
$front_end = true;
require_once('admin/includes/configs.php');

$action = r_get_post('action');

$action_good = r_get_get('g');
$action_warning = '';
$action_error = '';

if($action == 'register'){

    $nombre_tp      = r_get_post('your-name');
    $email          = r_get_post('your-email', 'email');
    $nombre_nino    = r_get_post('text-63');
    $fecha          = r_get_post('date-96');
    $pueblo         = r_get_post('menu-60');
    $frecuencia     = r_get_post('radio-116');
    $interesado     = r_get_post('radio-667');

    if($nombre_tp == ''){
        $action_warning .= '<br>Proporcione el nombre del padre o tutor';
    }

    if($email == ''){
        $action_warning .= '<br>El email que trata de colocar es inválido';
    }

    if($nombre_nino == ''){
        $action_warning .= '<br>Coloque el nombre del niño';
    }

    if($fecha == ''){
        $action_warning .= '<br>Fecha inválida';
    }

    if($pueblo == ''){
        $action_warning .= '<br>Seleccione el pueblo';
    }

    if($frecuencia == ''){
        $action_warning .= '<br>¿Con qué frecuencia suelen visitar un restaurante Chuck E. Cheese?';
    }

    if($interesado == ''){
        $action_warning .= '<br>Déjenos saber si le interesa recibir promociones';
    }

    if($action_warning === '' && $action_error === ''){


        if($db->insert('club',
            [
            'nombres_pt'    => $nombre_tp,
            'email'         => $email,
            'nombre_nino'   => $nombre_nino,
            'fecha'         => $fecha,
            'pueblo'       => $pueblo,
            'frecuencia'    => $frecuencia,
            'interesado'    => $interesado
            ]
        )){
            $nombre_tp      = '';
            $email          = '';
            $nombre_nino    = '';
            $fecha          = '';
            $pueblo         = '';
            $frecuencia     = '';
            $interesado     = '';

            redirect_no_cache('club.php?g=Su inscripción ha sido realizada, pronto sabrá de nosotros#wpcf7-f606-p610-o1');
        }else{
            $action_error = 'Hubo un error al tratar de agregar su inscripción, trate más tarde, o comuníquese con la administración si el error persiste';
        }

    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Chuck E club</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Typekit Fonts -->
    <script type="text/javascript" src="//use.typekit.net/sml4yoz.js"></script>
    <script type="text/javascript">try{Typekit.load();}catch(e){}</script>
    <script src="_js/css_browser_selector.js"></script>
    <script src="formulario/progreso.js" type="text/javascript"></script>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

    <link href="_css/site.css" rel="stylesheet" type="text/css"/>
    <link href="_css/print.css" rel="stylesheet" media="print"/>
    <!--[if IE 8]><link href="_css/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->
    <!--[if IE 7]><link href="_css/ie7.css" rel="stylesheet" type="text/css"/><![endif]-->
    <link href="_css/main.css" rel="stylesheet" type="text/css"/>
    <link href="_css/normalize.css" rel="stylesheet" type="text/css"/>
    <link href="_css/home.css" rel="stylesheet" type="text/css"/>
    <link href="formulario/progressBar.css" rel="stylesheet" type="text/css"/>

    <style>

        .message-good, .message-warning, .message-error {
            display: block;
            margin: 10px 0;
            padding: 10px;
            padding-left: 45px;
            border: 2px solid;
            background-position: 10px;
            background-repeat: no-repeat;
        }

        .message-good ul, .message-warning ul, .message-error ul { margin: 0 0 0 15px;padding: 0;}

        .message-good {
            border-color: #4f8a10;
            background-color: #dff2bf;
            background-image: url(../imgs/rm_exito.png);
            color: #4f8a10;
        }

        .message-warning {
            border-color: #9f6000;
            background-color: #feefb3;
            background-image: url(../imgs/rm_advertencia.png);
            color: #9f6000;
        }

        .message-error {
            border-color: #d8000c;
            background-color: #ffbaba;
            background-image: url(../imgs/rm_error.png);
            color: #d8000c;
        }

    </style>

</head>
<body style="" id="home">

<header class="clearfix">
    <div class="bg-purple">
        <div class="wrapper">
            <div id="locationFinder" class="visible-phone" style="display: block!important">
                <form action="/experience/locations" id="validatetopzip" method="get">
                    <label for="location-finder-zip-code">Location Finder</label>
                    <div id="locationInput">
                        <input id="location-finder-zip-code" type="text" name="zip" class="required" placeholder="Enter Zip Code" value="" />
                        <button type="submit" class="btn btn-warning"><i class="icon-chevron-right"></i></button>
                    </div>
                </form>
            </div>
            <a id="headerRibbon" href="/chuckecheesespuertorico/chuck_E_club"><img id="best_deals" src="_images/ribbon_best-deals.png"></a>
            <a class="nav-toggle">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <nav>
                <ul class="clearfix">
                    <li>
                        <span class="plus">&#43;</span><span class="minus">&#8211;</span>
                        <a class='nav-btn' id='experience_btn' href='/chuckecheesespuertorico/la-experiencia' data-image='_images/experience.jpg'>La Experiencia</a>
                        <div class="bg-purple dropdown experience">
                            <ul>
                                <li id='visiting-cecsn'><a href='/chuckecheesespuertorico/visita_chuck.html' data-image='_images/meganav/experience_visiting.jpg'><span>Visita Chuck E. Cheeses</span></a></li>
                                <li id='locationssn'><a href='/chuckecheesespuertorico/localidades' data-image='_images/meganav/experience_location-finder.jpg'><span>Buscador Tiendas</span></a></li>
                                <li id='our-promisesn'><a href='/chuckecheesespuertorico/promesa' data-image='_images/meganav/experience_our-promise.jpg'><span>Nuestra Promesa</span></a></li>
                                <li id='menusn'><a href='/chuckecheesespuertorico/menu' data-image='_images/meganav/experience_menu.jpg'><span>Menú</span></a></li>
                                <li id='couponssn'><a href='/chuckecheesespuertorico/cupones' data-image='_images/meganav/experience_coupons.jpg'><span>Cupones</span></a></li>
                            </ul>
                            <img src='_images/experience.jpg' alt=''/>
                        </div>
                    </li>
                    <li>
                        <span class="plus">&#43;</span><span class="minus">&#8211;</span>
                        <a class='nav-btn' id='events_btn' href='/chuckecheesespuertorico/cumpleaños' data-image='_images/Birthday_100_Bonus_Nav_0414.png'>Cumpleaños</a>
                        <div class="bg-purple dropdown events">
                            <ul>
                                <li id='visiting-cecsn'><a href='/chuckecheesespuertorico/actividad' data-image='_images/Website_Nav_GroupEvents_0814.jpg'><span>Actidad de grupos</span></a></li>
                                <li id='locationssn'><a href='/chuckecheesespuertorico/recaudar' data-image='_images/events_fundraising_school.jpg'><span>Recaudar fondos</span></a></li>
                                <li id='our-promisesn'><a href='/chuckecheesespuertorico/invitaciones' data-image='_images/events_invitations.jpg'><span>Invitaciones</span></a></li>
                            </ul>
                            <img src='_images/events.jpg' alt=''/>
                        </div>
                    </li>
                </ul>
                <ul>
                    <li><a href="http://chuckecheesespr.com/chuckecheesespuertorico" id="logo"></a></li>
                </ul>
                <ul>
                    <li>
                        <a class='nav-btn' id='activities_btn' href='/chuckecheesespuertorico/actividades' data-image='_images/activities.jpg'>Actividades</a>
                        <div class="bg-purple dropdown activities">
                            <ul>
                                <li id='gamessn'><a href='/chuckecheesespuertorico/juegos' data-image='_images/activities_games.jpg'><span>Juegos</span></a></li>
                                <li id='downloadssn'><a href='/chuckecheesespuertorico/descagas' data-image='_images/activities_downloads.jpg'><span>Descargas</span></a></li>
                                <li id='videossn'><a href='/chuckecheesespuertorico/videos' data-image='_images/activities_videos.jpg'><span>Videos</span></a></li>
                                <li id='rewards-calendarssn'><a href='/chuckecheesespuertorico/calendarios' data-image='_images/activities_rewards-calendars.jpg'><span>Calendarios</span></a></li>
                            </ul>
                            <img src='_images/activities.jpg' alt=''/>
                        </div>
                    </li>
                    <li>
                        <a class='nav-btn' id='share_btn' href='/chuckecheesespuertorico/chuck_E_club' data-image='_images/events_birthday_tokens.jpg'>Chuck E Club</a>
                        <div class="bg-purple dropdown share">
                            <ul>
                                <li id='/sharesn'><a href='/chuckecheesespuertorico/chuck_E_club' data-image='_images/meganav/share-memories.jpg'><span>Chuck E Club</span></a></li>
                            </ul>
                            <img src='_images/meganav/share-memories.jpg' alt=''/>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<style type="text/css">
    a.active.experience
    {
        opacity: 0.4;
    }
</style>
<div class="container" id="main">
<div class="row">

<div class="span2 navsidebar">
    <div class="box orange">
        <div class="tl">
            <div class="tr">
                <div class="tm">
                </div>
            </div>
        </div>
        <div class="ml">
            <div class="mr">
                <div class="mm">

                    <div class="navbar">

                        <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                        <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse" id="link23">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>

                        <!-- Everything you want hidden at 940px or less, place within here -->
                        <div class="nav-collapse">

                            <nav class="side">
                                <ul class="side-nav sub-menu nav nav-list"><li class="first" id="location-findersn"><a class="sub-section" href="/chuckecheesespuertorico/cumpleaños" id="link24">Cumpleaños</a></li><li class="" id="visiting-cecsn"><a class="sub-section" href="/chuckecheesespuertorico/actividades" id="link25">Actividades de Grupo</a></li><li class="" id="our-promisesn"><a class="sub-section" href="//chuckecheesespuertorico/recaudar" id="link26">Recaudar Fondos</a></li><li class="" id="menusn"><a class="sub-section" href="/chuckecheesespuertorico/invitaciones" id="link27">Invitaciones</a></li></ul>
                            </nav>

                        </div>

                    </div> <!-- /.navbar -->


                </div>
            </div>
        </div>
        <div class="bl">
            <div class="br">
                <div class="bm">
                </div>
            </div>
        </div>
    </div> <!-- /.box -->
</div> <!-- /.span nav-->

<!--Main Content-->
<div class="span8">
    <!--Start Top Main Row-->
    <div class="box red ml20">
        <div class="tl">
            <div class="tr">
                <div class="tm">
                </div>
            </div>
        </div>
        <div class="ml">
            <div class="mr">
                <div class="mm">
                    <div class="content-top row-fluid clearfix">
                        <ul class="breadcrumb pull-left">
                            <li><a href="/" id="link30">Inicio</a><span class="divider">/</span></li><li class="active">Chuck E Club</li>
                        </ul>

                        <!--<fb:like data-track="chuckecheeseapps:facebook like" href="http://chuckecheese.clickherestaging.com/experience" send="false" width="100" show_faces="false"></fb:like>-->
                        <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fchuckecheesespr&amp;width=425&amp;layout=standard&amp;action=like&amp;show_faces=false&amp;share=true&amp;height=35&amp;appId=499946833431521" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:425px; height:35px;float:right; margin-left: 3em" allowTransparency="true"></iframe>

                    </div>
                    <h1 class="contentHeader">Chuck E. Club</h1>


                    <style type="text/css">
                        #thumb-next, #thumb-next:hover {
                            background: url("/_images/carousel-next.png") no-repeat scroll left top transparent!important;
                        }
                        #thumb-prev, #thumb-prev:hover {
                            background: url("/_images/carousel-prev.png") no-repeat scroll left top transparent!important;
                            top: 0;
                        }
                    </style>

                    <style type="text/css">
                        .row-fluid_form {position: relative; width:550px;}
                        .table.locations {margin-top: 175px;}
                        .image_container_ {position: absolute;width: 225px;
                            margin-top: -12em;}
                        .image-wrapper-tickets,
                        .image-wrapper-tokens {margin-left: 430px; }
                        .image-wrapper-tickets {margin-top: -20px;}
                        @media only screen and (max-width:678px) {
                            .table.locations { margin-top: 5px;}
                            .image_container_ {position: relative; margin-top: -10px; left: 50%; margin-left: -218px;}
                            .image-wrapper-tickets,
                            .image-wrapper-tokens {display: inline-block; margin-left: 0;}
                            .image-wrapper-tokens {margin-left: 20px;}
                        }
                        @media only screen and (max-width:495px) {
                            .image_container_ {margin-left: -104px;}
                            .image-wrapper-tickets,
                            .image-wrapper-tokens {display: block; margin-left: 0;}
                        }
                    </style>
                    <script>
                        $(document).ready( function() {
                            var imgObj = $('.image_container_'),
                                imgPlaceA = $('.mm.dcontainer > h1.contentHeader').next(),
                                imgPlaceB = $('p.reservation-links');
                            function respLayout() {
                                var winwidth = $(window).width();
                                if (winwidth <= 678) {
                                    imgObj.insertAfter(imgPlaceB);
                                } else {
                                    imgObj.insertAfter(imgPlaceA);
                                }
                            };
                            respLayout();
                            $(window).resize(function() {respLayout()} );
                        });
                    </script>
                    <!--[if lt IE 9]>
                    <style type="text/css">
                        .image-wrapper-tickets img,
                        .image-wrapper-tokens img {position: absolute;}
                        .image-wrapper-tokens img {top: 188px;}
                    </style>
                    <![endif]-->
                    <div class="image_container_">
                        <div class="image-wrapper-tokens">
                            <img src="_images/ribbons.png" alt="" style="width: 208px; height: 210px; margin-top: 6.5em" id="bonus_tokens">

                        </div>
                    </div>
                    <p>
                        Conviértase en un miembro de Chuck E-Club® hoy y recibe <br>
                        nuestras mejores ofertas, premios exclusivos y actividades<br>
                        para niños enviados directamente a su bandeja de entrada.</p>
                    <div class="row-fluid_form" id="wpcf7-f606-p610-o1">

                        <?php
                        if($action_good != ''){
                            ?>
                            <div class="message-good"><?php echo $action_good; ?></div>
                        <?php
                        }

                        if($action_warning != ''){
                        ?>
                        <div class="message-warning"><?php echo $action_warning; ?></div>
                        <?php
                        }

                        if($action_error != ''){
                        ?>
                        <div class="message-error"><?php echo $action_error; ?></div>
                        <?php
                        }
                        ?>

                        <div class="span12" style="width:450px">

                            <form name="" action="club.php#wpcf7-f606-p610-o1" method="post" class="wpcf7-form" novalidate="novalidate">
                                <div style="display: none;">
                                    <input type="hidden" name="_wpcf7" value="606">
                                    <input type="hidden" name="_wpcf7_version" value="4.0.1">
                                    <input type="hidden" name="_wpcf7_locale" value="es_ES">
                                    <input type="hidden" name="_wpcf7_unit_tag" value="wpcf7-f606-p610-o1">
                                    <input type="hidden" name="_wpnonce" value="e3cd950819">
                                    <input type="hidden" name="action" value="register">
                                </div>
                                <p>Nombre y Apellido (Padre o tutor) *<br>
                                    <span class="wpcf7-form-control-wrap your-name"><input type="text" name="your-name" value="<?php r_echo_isset('nombre_tp') ?>" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false"></span> </p>
                                <p>Correo Electrónico *<br>
                                    <span class="wpcf7-form-control-wrap your-email"><input type="email" name="your-email" value="<?php r_echo_isset('email') ?>" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false"></span> </p>
                                <p>Nombre del Niñ@<br <br="">
                                    <span class="wpcf7-form-control-wrap text-63"><input type="text" name="text-63" value="<?php r_echo_isset('nombre_nino') ?>" size="40" class="wpcf7-form-control wpcf7-text" id="nombrenino" aria-invalid="false"></span></p>
                                <p>Fecha de Nacimiento<br <br="">
                                    <span class="wpcf7-form-control-wrap date-96"><input type="date" name="date-96" value="<?php r_echo_isset('fecha') ?>" class="wpcf7-form-control wpcf7-date wpcf7-validates-as-date" id="cumplenino" aria-invalid="false"></span></p>
                                <p>Pueblos<br>
                                    <span class="wpcf7-form-control-wrap menu-60">
                                        <select name="menu-60" class="wpcf7-form-control wpcf7-select wpcf7-validates-as-required" id="pueblos" aria-required="true" aria-invalid="false">
                                            <option <?php r_input_select_selected('pueblo', '1. Adjuntas'); ?> value="1. Adjuntas">Adjuntas</option>
                                            <option <?php r_input_select_selected('pueblo', '2. Aguada'); ?> value="2. Aguada">Aguada</option>
                                            <option <?php r_input_select_selected('pueblo', '3. Aguadilla'); ?> value="3. Aguadilla">Aguadilla</option>
                                            <option <?php r_input_select_selected('pueblo', '4. Aguas Buenas'); ?> value="4. Aguas Buenas">Aguas Buenas</option>
                                            <option <?php r_input_select_selected('pueblo', '5. Aibonito'); ?> value="5. Aibonito">Aibonito</option>
                                            <option <?php r_input_select_selected('pueblo', '6. Añasco'); ?> value="6. Añasco"> Añasco</option>
                                            <option <?php r_input_select_selected('pueblo', '7. Arecibo'); ?> value="7. Arecibo">Arecibo</option>
                                            <option <?php r_input_select_selected('pueblo', '8. Arroyo'); ?> value="8. Arroyo">Arroyo</option>
                                            <option <?php r_input_select_selected('pueblo', '9. Barceloneta'); ?> value="9. Barceloneta">Barceloneta</option>
                                            <option <?php r_input_select_selected('pueblo', '10. Barranquitas'); ?> value="10. Barranquitas">Banquitas</option>
                                            <option <?php r_input_select_selected('pueblo', '11. Bayamón'); ?> value="11. Bayamón">Bayamón</option>
                                            <option <?php r_input_select_selected('pueblo', '12. Cabo Rojo'); ?> value="12. Cabo Rojo">Cabo Rojo</option>
                                            <option <?php r_input_select_selected('pueblo', '13. Caguas'); ?> value="13. Caguas">Caguas</option>
                                            <option <?php r_input_select_selected('pueblo', '14. Camuy'); ?> value="14. Camuy">Camuy</option>
                                            <option <?php r_input_select_selected('pueblo', '15. Canóvanas'); ?> value="15. Canóvanas">Canóvanas</option>
                                            <option <?php r_input_select_selected('pueblo', '16. Carolina'); ?> value="16. Carolina">Carolina</option>
                                            <option <?php r_input_select_selected('pueblo', '17. Cataño'); ?> value="17. Cataño">Cataño</option>
                                            <option <?php r_input_select_selected('pueblo', '18. Cayey'); ?> value="18. Cayey">Cayey</option>
                                            <option <?php r_input_select_selected('pueblo', '19. Ceiba'); ?> value="19. Ceiba">Ceiba</option>
                                            <option <?php r_input_select_selected('pueblo', '20. Ciales'); ?> value="20. Ciales">Ciales</option>
                                            <option <?php r_input_select_selected('pueblo', '21. Cidra'); ?> value="21. Cidra">Cidra</option>
                                            <option <?php r_input_select_selected('pueblo', '22. Coamo'); ?> value="22. Coamo">Coamo</option>
                                            <option <?php r_input_select_selected('pueblo', '23. Comerío'); ?> value="23. Comerío">Comerío</option>
                                            <option <?php r_input_select_selected('pueblo', '24. Corozal'); ?> value="24. Corozal">Corozal</option>
                                            <option <?php r_input_select_selected('pueblo', '25. Culebra'); ?> value="25. Culebra">Culebra</option>
                                        </select></span></p>
                                <p>¿Con qué frecuencia <br>
                                    suelen visitar un restaurante Chuck E. Cheese?<br>
                                    <span class="wpcf7-form-control-wrap radio-116">
                                        <span class="wpcf7-form-control wpcf7-radio" id="vicita">
                                            <span class="wpcf7-list-item first"><label><input type="radio" name="radio-116" <?php r_input_radio_chequed('frecuencia', '3 o más veces por mes'); ?> value="3 o más veces por mes">&nbsp;<span class="wpcf7-list-item-label">3 o más veces por mes</span></label></span>
                                            <span class="wpcf7-list-item"><label><input type="radio" name="radio-116" <?php r_input_radio_chequed('frecuencia', '1-2 veces por mes'); ?> value="1-2 veces por mes">&nbsp;<span class="wpcf7-list-item-label">1-2 veces por mes</span></label></span>
                                            <span class="wpcf7-list-item"><label><input type="radio" name="radio-116" <?php r_input_radio_chequed('frecuencia', 'Cada mes'); ?> value="Cada mes">&nbsp;<span class="wpcf7-list-item-label">Cada mes</span></label></span>
                                            <span class="wpcf7-list-item"><label><input type="radio" name="radio-116" <?php r_input_radio_chequed('frecuencia', 'Cada dos meses'); ?> value="Cada dos meses">&nbsp;<span class="wpcf7-list-item-label">Cada dos meses</span></label></span>
                                            <span class="wpcf7-list-item last"><label><input type="radio" name="radio-116" <?php r_input_radio_chequed('frecuencia', 'Otro'); ?> value="Otro">&nbsp;<span class="wpcf7-list-item-label">Otro</span></label></span></span></span></p>
                                <p>¿Estaría usted interesado en recibir <br>
                                    promociones especiales entre semana?<br>
                                    <span class="wpcf7-form-control-wrap checkbox-667">
                                        <span class="wpcf7-form-control wpcf7-checkbox" id="seleccion">
                                            <span class="wpcf7-list-item first"><input type="radio" name="radio-667" id="radio-667-si" value="Si" <?php r_input_radio_chequed('interesado', 'Si'); ?>>&nbsp;<span class="wpcf7-list-item-label"><label for="radio-667-si">Si</label></span></span>
                                            <span class="wpcf7-list-item last"><input type="radio" name="radio-667" id="radio-667-no" value="No" <?php r_input_radio_chequed('interesado', 'No'); ?>>&nbsp;<span class="wpcf7-list-item-label"><label for="radio-667-no">No</label></span></span></span></span></p>
                                <p><input type="submit" value="Enviar" class="wpcf7-form-control wpcf7-submit"><img class="ajax-loader" src="http://v2.chuckecheesespr.com/wp-content/plugins/contact-form-7/images/ajax-loader.gif" alt="Enviando..." style="visibility: hidden;"></p>
                                <div class="wpcf7-response-output wpcf7-display-none"></div></form>
                        </div>
                        <!-- span -->
                    </div>



                </div>
            </div>
        </div>
        <div class="bl">
            <div class="br">
                <div class="bm">
                </div>
            </div>
        </div>
    </div> <!-- /.box -->
    <!--End Top Main Row-->


</div> <!-- container -->


<footer class="container" style="background: url('/_assets/img/bg-purple.jpg') repeat #9655b9; width: 100%; margin-top: 0; float:left;">
    <nav>
        <a href="/franchising" id="link40">Franchising</a>
        <a href="/product-recalls" id="link41">Product Recalls</a>
        <a href="/terms-and-privacy" id="link42">Terms and Privacy</a>
        <a href="/site-map" id="link43">Site Map</a>
        <a href="/careers" id="link44">Careers</a>
        <a href="http://phx.corporate-ir.net/phoenix.zhtml?c=72589&amp;p=irol-irhome" id="link45">Investor Relations</a>
        <a href="/snacks" target="_blank" id="link46">Snacks</a>
        <a href="http://radiantcustomervoice.com/organizations/1996/pos_surveys/new" id="link47">Survey</a>
        <a href="/forms/contactus" id="link48">Contact Us</a>
        <a href="/contact-us/party-faqs" id="link49">FAQs</a>
    </nav>
    <div id="socialicons">
        <fb:like href="https://www.facebook.com/OfficialChuckECheese" show_faces="false" send="false" layout="button_count" font="arial" colorscheme="light" action="like" style="display:inline !important" class=" fb_iframe_widget" fb-xfbml-state="rendered" fb-iframe-plugin-query="action=like&amp;app_id=111630799034453&amp;color_scheme=light&amp;font=arial&amp;href=https%3A%2F%2Fwww.facebook.com%2FOfficialChuckECheese&amp;layout=button_count&amp;locale=en_US&amp;sdk=joey&amp;send=false&amp;show_faces=false"><span style="vertical-align: bottom; width: 86px; height: 20px;"><iframe name="f21363f69" width="1000px" height="1000px" frameborder="0" allowtransparency="true" scrolling="no" title="fb:like Facebook Social Plugin" src="http://www.facebook.com/plugins/like.php?action=like&amp;app_id=111630799034453&amp;channel=http%3A%2F%2Fstatic.ak.facebook.com%2Fconnect%2Fxd_arbiter%2FQjK2hWv6uak.js%3Fversion%3D41%23cb%3Df227678ad4%26domain%3Dwww.chuckecheese.com%26origin%3Dhttp%253A%252F%252Fwww.chuckecheese.com%252Ff217f36124%26relation%3Dparent.parent&amp;color_scheme=light&amp;font=arial&amp;href=https%3A%2F%2Fwww.facebook.com%2FOfficialChuckECheese&amp;layout=button_count&amp;locale=en_US&amp;sdk=joey&amp;send=false&amp;show_faces=false" class="" style="border: none; visibility: visible; width: 86px; height: 20px;"></iframe></span></fb:like>
        <a id="facebook" class="social-media trackable external" href="https://www.facebook.com/OfficialChuckECheese" title="Facebook Footer" target="_blank"><span>Facebook</span></a><!--
		--><a id="twitter" class="social-media trackable external" href="http://twitter.com/chuckecheese/" title="Twitter Footer" target="_blank"><span>Twitter</span></a><!--
		--><a id="youtube" class="social-media trackable external" href="http://www.youtube.com/user/ChuckECheeseVideo" title="YouTube Footer" target="_blank"><span>YouTube</span></a><!--
		--><a id="pinterest" class="social-media trackable external" href="http://pinterest.com/chuckecheese/" title="Pinterest Footer" target="_blank"><span>Pinterest</span></a><!--
		--><a id="foursquare" class="social-media trackable external" href="https://foursquare.com/chuckecheese" title="Foursquare Footer" target="_blank"><span>foursquare</span></a><!--
		--><a id="instagram" class="social-media trackable external" href="http://instagram.com/chuckecheese" title="Instagram Footer" target="_blank" style="background: url('/_assets/_images/instagram.png') no-repeat 0 0;"><span>Instagram</span></a><!--
        --><a title="Google+ Footer" class="social-media trackable external" href="https://plus.google.com/100667335861370380047?prsrc=3" rel="publisher" target="_blank" style="text-decoration:none;" id="link56"><img src="//ssl.gstatic.com/images/icons/gplus-32.png" alt="Google+" style="border:0;width:24px;height:24px; margin-bottom: 16px;"></a>
    </div>
</footer>



<div class="modal hide fade fixed-width" id="modal">
    <div class="modal-header warning">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Warning</h3>
    </div>
    <div class="modal-body">
        <p>You are leaving the Chuck E. Cheese's website and going to that of a non-affiliated third-party vendor engaged to provide online services for us. The vendor's site is not under our control and the security and privacy protection offered may be less than or differ from that of Chuck E. Cheese's. Do you want to continue?</p>
        <!--<p>
            <a href="#" id="external-link-display">External Link</a>
        </p>-->
    </div>
    <footer class="container" style="background: url('_images/bg.jpg') repeat #9655b9; width: 100%; margin-top: 0;">
        <nav>
            <a href="/la-experiencia">La experiencia</a>
            <a href="/fiestas -y-eventos">Fiestas y Eventos</a>
            <a href="/cumpleaños">Cumpleaños</a>
            <a href="/multimedia">Multimedia</a>
            <a href="/ckid-club">Kid Club</a>
            <a href="/reglas-de-Chuck-E-Cheese’s">Reglas de Chuck E. Cheese’s</a>
            <a href="/snacks" target="_blank">Política de Privacidad</a>
            <a href="/contacto">Contacto</a>
        </nav>
        <div id="socialicons">
            <fb:like href="https://www.facebook.com/OfficialChuckECheese" show_faces="false" send="false" layout="button_count" font="arial" colorscheme="light" action="like"></fb:like>
            <a id="facebook" class="social-media trackable external" href="https://www.facebook.com/OfficialChuckECheese" title="Facebook Footer" target="_blank"><span>Facebook</span></a><!--
		--><a id="twitter" class="social-media trackable external" href="http://twitter.com/chuckecheese/" title="Twitter Footer" target="_blank"><span>Twitter</span></a><!--
		--><a id="youtube" class="social-media trackable external" href="http://www.youtube.com/user/ChuckECheeseVideo" title="YouTube Footer" target="_blank"><span>YouTube</span></a><!--
		--><a id="pinterest" class="social-media trackable external" href="http://pinterest.com/chuckecheese/" title="Pinterest Footer" target="_blank"><span>Pinterest</span></a><!--
		--><a id="foursquare" class="social-media trackable external" href="https://foursquare.com/chuckecheese" title="Foursquare Footer" target="_blank"><span>foursquare</span></a><!--
    --><a title="Google+ Footer" class="social-media trackable external" href="https://plus.google.com/100667335861370380047?prsrc=3" rel="publisher" target="_blank" style="text-decoration:none; position: absolute;"><img src="http://ssl.gstatic.com/images/icons/gplus-32.png" alt="Google+" style="border:0;width:24px;height:24px;"/></a>
        </div>
    </footer>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="_js/jquery.gallery.js" type="text/javascript"></script>
    <script src="_js/main.js" type="text/javascript"></script>
</body>
</html>