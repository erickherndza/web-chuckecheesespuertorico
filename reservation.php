<?php
$steps =
    [
        'reset',
        'inicio',
        'paquete',
        'comida',
        'opciones',
        'pago'

    ];
$step = (isset($_GET['step']) && in_array($_GET['step'], $steps)) ? $_GET['step'] : $steps[0];


?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" lang="en"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->



<head>
	<meta charset="utf-8">
	<title>Chucke Cheeses | Puerto Rico</title>

	<!-- Meta -->
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"> -->
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">
	<meta name="robots" content="noindex, nofollow" />
  	<meta name="robots" content="noarchive" />
	<meta http-equiv="imagetoolbar" content="no" />

	<!-- Fav and Touch Icons -->
	<link rel="shortcut icon" href="/_assets/_ico/favicon.ico">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/_assets/_ico/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/_assets/_ico/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/_assets/_ico/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="/_assets/_ico/apple-touch-icon-57-precomposed.png">

	<!-- Typekit Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Titan+One' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Shadows+Into+Light' rel='stylesheet' type='text/css'>

	<!-- Styles -->
	<link href="js/bootstrap.css" rel="stylesheet">
	<link href="js/jquery-ui.min.css" rel="stylesheet">
	<link href="js/jquery-ui.structure.min.css" rel="stylesheet">
	<link href="js/jquery-ui.theme.min.css" rel="stylesheet">
    

    <link href="_css/site.css" rel="stylesheet" type="text/css"/>
<link href="_css/print.css" rel="stylesheet" media="print"/>
<!--[if IE 8]><link href="_css/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->
<!--[if IE 7]><link href="_css/ie7.css" rel="stylesheet" type="text/css"/><![endif]-->    
<link href="_css/main.css" rel="stylesheet" type="text/css"/>
<link href="_css/normalize.css" rel="stylesheet" type="text/css"/>
<link href="_css/home.css" rel="stylesheet" type="text/css"/>


	<!-- Libraries and Plugins -->
	<script src="js/jquery.min.js" type="text/javascript"></script>
	<script src="js/jquery-ui.min.js" type="text/javascript"></script>

    <script>
        $(document).ready(function(){

            $('.breadcrumbs[href="#"]').click(function(){
               return false;
            });

            $('.breadcrumbs[href!="#"]').css('cursor','pointer');

            $( "#datepicker" ).datepicker({
                dateFormat: "yy-mm-dd",
                gotoCurrent: true,
                maxDate: "+1y",
                minDate: "0",
                onSelect: function($date){
                    getting_times($date);

                },
                onLoad : function($date){
                    alert($date);
                }
            });

            function getting_times($date){
                $('#reservation-nav-bar').remove();
                $('#time-picker').html('<img src="imgs/ajax-loader.gif"> Cargando horarios disponibles!');
                $.ajax({
                    url         : 'jq_get_times.php',
                    data        : {'date':$date},
                    type        : 'POST',
                    success     : function($return){
                        $('#time-picker').html($return);
                    }
                });
            }

//            $('#show-calendar-div').css('display', 'none');

            function strip(number) {
                return (parseFloat(number.toFixed(0)));
            }

//            $('#show-calendar').click(function(){
//
//
//            });

            $('input[name="input_time"]').live('change',function(){
                $('#reservation-nav-bar').remove();
                $data = '<div id="reservation-nav-bar"><div class="reservation-previous-step"><a href="reservation.php?step=reset" id="bt-go-step1" tabindex="2" class="btn">&laquo;&laquo; Volver a comenzar la reservación</a></div><div class="reservation-next-step"><a id="bt-go-step2" tabindex="1" class="btn" href="">Próximo (Paquetes)  &raquo;&raquo;</a></div></div>';
                $('#reservations-container').append($data);

            });

            function display_step1($reset){

                $.ajax({
                    url : 'jq_reservations_steps.php',
                    data : {
                        'action' : 'go-step1',
                        'reset'  : $reset
                    },
                    type : 'POST',
                    dataType: 'json',
                    success : function($result){

                        $response = $result.response;

                        if($response == 'good') {

                            window['minimumChildren'] = $result.minimumChildren;
                            window['minimumAdults'] = $result.minimumAdults;

                            $('#where-select').html($result.places);

                            if($reset == 'no'){

                                $('#txtChildren').val($result.children);
                                $('#txtAdults').val($result.adults);

                                $date = $result.time[2];
                                $('#datepicker').datepicker('setDate', $date);
                                getting_times($date);
//                                console.log();
                            }

                        }else{
                            alert('No se pudo gestionar los datos para la reservación, por favor, refresque la página');
                        }

                    }

                });

                render_resumen();

            }

            $('#bt-go-step1').live('click', function(){
                document.location.href = 'reservation.php';
            });

            function display_step2(){
                $('#breadcrumbs_package').addClass('current').removeClass('disabled');

                $.get('jq_reservations_packages_render.php', function($result){
                    $('#reservations-container').html($result);

                    render_resumen();

                });

            }

            $('#bt-go-step2').live('click', function(e){
                $anchor = $(this);
                $anchorHtml = $anchor.html();
                $anchor.html('<img src="imgs/ajax-loader.gif"> procesando!');

                $place = $('input[name="place_input_radio"]:checked').val();

                if($place != undefined && is_number($place)){

                    $num_children = $('#txtChildren').val();
                    $num_guests = $('#txtAdults').val();

                    $num_children = $num_children*1;
                    $num_guests = $num_guests*1;

                    minimumChildren = minimumChildren*1;
                    minimumAdults = minimumAdults*1;

                    if(!is_number($num_children) || $num_children < minimumChildren ){
                        alert('Un mínimo de ' + minimumChildren + ' niños es requerido');
                    }else{
                        if(!is_number($num_guests) || $num_guests < minimumAdults ){
                            alert('Un mínimo de ' + minimumAdults + ' invitado (Adulto) es requerido');
                        }else{
//                        $('#show-calendar-div').css('display', 'block');

                            $.ajax({
                                url : 'jq_reservations_steps.php',
                                data : {
                                    'action'      :   'go-step2',
                                    'txtChildren' :   $('#txtChildren').val(),
                                    'txtAdults'   :   $('#txtAdults').val(),
                                    'place'       :   $place,
                                    'input_time'  :   $('input[name="input_time"]:checked').val()
                                },
                                type : 'POST',
                                dataType: 'json',
                                success : function($result){
                                    $response = $result.response;

                                    if($response == 'good'){
                                        display_step2();
                                    }else{
//                            TODO: errores cometidos por los usuarios desde el step 1...
                                    }
                                }

                            });

                        }

                    }

                }else{
                    alert('Debe seleccionar la sucursal donde se selebrará el cumpleaños');
                }

                return false;
            });

            $('input[name="radio_package"]').live('change', function(){
                $('#reservation-nav-bar').remove();
                $data = '<div id="reservation-nav-bar"><div class="reservation-next-step"><a id="bt-go-step3" tabindex="1" class="btn" href="">Próximo (Comidas)  &raquo;&raquo;</a></div></div>';
                $('#reservations-container').append($data);
            });

            $('#bt-go-step3').live('click', function () {
                $anchor = $(this);
                $anchorHtml = $anchor.html();
                $anchor.html('<img src="imgs/ajax-loader.gif"> procesando!');
                $package = $('input[name="radio_package"]:checked').val();

                $.ajax({
                    url : 'jq_reservations_steps.php',
                    data : {
                        'action'      :   'go-step3',
                        'package' :   $package
                    },
                    type : 'POST',
                    dataType: 'json',
                    success : function($result){
                        $response = $result.response;

                        if($response == 'good'){
                            display_step3();
//                            console.log($result.package);
                        }else{
//                            TODO: errores cometidos por los usuarios desde el step 2...
                        }
                    }
                });

                return false;
            });

            function display_step3(){
                $('#breadcrumbs_food').addClass('current').removeClass('disabled');
                $('#breadcrumbs_package').addClass('current').removeClass('disabled');

                $.get('jq_reservations_food_render.php', function($result){
                    $('#reservations-container').html($result);

                    $data = '<div id="reservation-nav-bar"><div class="reservation-next-step"><a id="bt-go-step4" tabindex="1" class="btn" href="">Próximo (Opciones)  &raquo;&raquo;</a></div></div>';
                    $('#reservations-container').append($data);

                    render_resumen();
                });

            }

            $('#bt-go-step4').live('click', function(){
                $anchor = $(this);
                $anchorHtml = $anchor.html();
                $anchor.html('<img src="imgs/ajax-loader.gif"> procesando!');

                $foods_array = [];

                $('.food_input').each(function(){
                    $food = $(this);
                    $food_id = $food.attr('name');
                    $food_val = $food.val();

                    if($food_val > 0) {
                        $foods_array.push({id: $food_id, val: $food_val});
                    }

                });

                $.ajax({
                    url : 'jq_reservations_steps.php',
                    data : {
                        'action'    : 'go-step4',
                        'foods'     : $foods_array
                    },
                    type : 'POST',
                    dataType: 'json',
                    success : function($result){
                        $response = $result.response;
                        if($response == 'good'){
                            display_step4();
                        }else{
//                            TODO: errores cometidos por los usuarios desde el step 3...
                        }
                    }
                });

                return false;
            });

            function display_step4(){
                $('#breadcrumbs_package').addClass('current').removeClass('disabled');
                $('#breadcrumbs_food').addClass('current').removeClass('disabled');
                $('#breadcrumbs_options').addClass('current').removeClass('disabled');

                $.get('jq_reservations_options_render.php', function($result){
                    $('#reservations-container').html($result);

                    $data = '<div id="reservation-nav-bar"><div class="reservation-next-step"><a id="bt-go-step5" tabindex="1" class="btn" href="">Próximo (Pago)  &raquo;&raquo;</a></div></div>';
                    $('#reservations-container').append($data);

                    render_resumen();
                });
            }

            $('#bt-go-step5').live('click', function(){
                $anchor = $(this);
                $anchorHtml = $anchor.html();
                $anchor.html('<img src="imgs/ajax-loader.gif"> procesando!');

                $options_array = [];

                $('.option_input').each(function(){
                    $option = $(this);
                    $option_id = $option.attr('name');
                    $option_val = $option.val();

                    if($option_val > 0) {
                        $options_array.push({id: $option_id, val: $option_val});
                    }

                });

                $.ajax({
                    url : 'jq_reservations_steps.php',
                    data : {
                        'action'    : 'go-step5',
                        'options'     : $options_array
                    },
                    type : 'POST',
                    dataType: 'json',
                    success : function($result){
                        $response = $result.response;
                        if($response == 'good'){
                            display_step5();
                        }else{
//                            TODO: errores cometidos por los usuarios desde el step 3...
                        }
                    }
                });

                return false;
            });

            function display_step5(){ //payment
                $('#breadcrumbs_package').addClass('current').removeClass('disabled');
                $('#breadcrumbs_food').addClass('current').removeClass('disabled');
                $('#breadcrumbs_options').addClass('current').removeClass('disabled');
                $('#breadcrumbs_payment').addClass('current').removeClass('disabled');

                $.get('jq_reservations_payment_render.php', function($result){
                    $('#reservations-container').html($result);

                    $data = '<div id="reservation-nav-bar"><div class="reservation-next-step"><a id="bt-go-step6" tabindex="1" class="btn" href="">Próximo (Revisión)  &raquo;&raquo;</a></div></div>';
                    $('#reservations-container').append($data);

                    render_resumen();

                });
            }

            function render_resumen(){
                $.get('jq_reservations_resumen_render.php', function($result){
                    $('#details-inner').html($result);
                });
            }

            <?php if($step == 'reset'){ ?>
            display_step1('si');
            <?php }else if($step == 'inicio'){ ?>
             display_step1('no');
            <?php }else if($step == 'paquete'){ ?>
             display_step2();
            <?php }else if($step == 'comida'){ ?>
             display_step3();
            <?php }else if($step == 'opciones'){ ?>
             display_step4();
            <?php }else if($step == 'pago'){ ?>
             display_step5();
             <?php } ?>

        });
    </script>

	<script src="js/css_browser_selector.js" type="text/javascript"></script>
	<!--[if (gte IE 6)&(lte IE 8)]><script type="text/javascript" src="js/selectivizr-min.js"></script><![endif]-->
	<script src="js/jquery.validate.js" type="text/javascript"></script>
	<script src="js/jquery.placeholder.min.js" type="text/javascript"></script>
	<script src="js/tracking.js" type="text/javascript"></script>
	
	<!-- Bootstrap Scripts for Responsive Side Nav -->
    <script src="js/bootstrap-transition.js"></script>
    <script src="js/bootstrap-collapse.js"></script>
    <script src="js/bootstrap-modal.js"></script>

    <!-- Custom Scripts -->
	<script src="js/index.js" type="text/javascript"></script>
    <script src="js/scripts.js?v=1s2310033" language="javascript" type="text/javascript"></script>
	<!--<script src="https://partytest.chuckecheese.com/chatTEST/javascript/chat.js?v2" language="javascript" type="text/javascript"></script>-->

	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

    <style id="antiClickjack">body{display:none !important;}</style>
    <script type="text/javascript">
        if (self === top) {
            var antiClickjack = document.getElementById("antiClickjack");
            antiClickjack.parentNode.removeChild(antiClickjack);
        } else {
            top.location = self.location;
        }
    </script>






<style type="text/css" media="screen">	
ul#legend { padding: 0; width: 100%; margin: 10px 0 30px 0; _margin-bottom: 10px; }
	
ul#legend li { list-style: none; display: block; float: left; padding: 0 20px 0 17px; text-align: right; font: bold 11px arial; }
	
li.av { background: transparent url("images/li-green.gif") no-repeat; }
li.booth { background: transparent url("images/li-blue.gif") no-repeat; }
li.unav { background: transparent url("images/li-red.gif") no-repeat; }
li.stclosed { background: transparent url("images/li-purple.gif") no-repeat; }

	
	table#calendar { font: 11px arial; width: 100%; margin-top: 10px; clear: both; }
	
	td div { margin-bottom: 10px; }

	table.months { background: #f2f2f2; font-weight: bold; text-align: center; margin: 0 4px 0 0; border: 1px solid #e6e6e6; padding-bottom: 5px; width: 96%; }
	
	td.times table { background: #69cf66; width: 100%; }
	td.times td { padding: 1px 2px 1px 2px; }
	
	
	td.monthname, td.weekday { padding: 1px; }
	td.monthname { color: #4a3374; }
	td.weekday { color: #a3709d; }
	
	/* Past or unavailable days are light gray */
	td.past { background: #e6e6e6; }
	/* Selected days are purple */
	td.selected { background: #a3709d; }
	/* Closed days are dark gray */
	td.closed { background: #999; }
	/* Available days are green */
	td.available, td.tavailable { background: #69cf66; }
	/* Unavailable hours are light red */
	td.notavailable, td.tnotavailable { background: #fd3333; }
	/* Available hours are light green and have a dark green border */
	td.tavailable { border-left: 1px solid #2da82a; border-right: 1px solid #2da82a; }
	/* Unavailable hours have a dark green red border */
	td.tnotavailable { border-left: 1px solid #ff090a; border-right: 1px solid #ff090a; }
	
	td#first { border-top: 1px solid #2da82a; }
	td#last { border-bottom: 1px solid #ff090a; }
	
	.past, .selected, .available, .closed, .notavailable { border: 1px solid #f2f2f2; padding: 1px; }
	
	.error { font-size:13px; font-weight:bold; color:#b94a48; }

    #show-calendar-div{
        width: 100%;
        float: left;
    }

    .half-content{
        width: 49%;
        display: inline;
        float: left;
    }

.half-content:nth-child(even){
        float: right;
}

.content_time{margin-bottom: 7px;}
    .content_time label{ display: inline}

    .rform_title{font-size: 14px; font-weight: bold;}

    #reservation-nav-bar{width: 100%; float: left; margin-top: 10px;}
    .reservation-previous-step{
        float: left;
    }
    .reservation-next-step{
    float: right;
    }

.package_content{
    width: 557px;
    font-size:0.95em;
    border:solid 1px #843487;
    margin-bottom: 15px;
    padding: 0;
    border-spacing: 0;
}

.package_content thead tr td, .package_content tfoot tr td{
    background-color : #843487;
    border-right: 1px solid #FFF;
    padding: 0 0 5px 0;
    margin: 0;
    border-spacing: 0;
    color: #FFF;
    text-align: center;
    width: 55px;
}

.package_content thead tr td:first-child{
    text-align: left;
    padding-left: 15px;
    width: 380px;
}

.package_content tfoot tr td:first-child{
    text-align: right;
    padding-right: 5px;
}

.package_content thead tr td:last-child, .package_content tfoot tr td:last-child, .package_content tbody tr td:last-child{
    border-right-width: 0;
}

.package_content tbody tr td{
    text-align: center;
    padding: 5px;
    border-right: 1px solid #FFF;
}

.package_content tbody tr td:first-child{
    text-align: left;
}

.package_content tbody tr:nth-child(odd) td{
    background-color: #F9F9F9;
}
.package_content tbody tr:nth-child(even) td{
    background-color: #edd4ee;
}

    .food_container, .options_container{
        width: 48%;
        display: inline-table;
        vert-align: top;
        margin-bottom: 10px;
    }

    .food_container strong, .options_container strong{
        font-size: 16px;
        color: #f8a23b;
        font-weight: 600;
    }

    .food_container:nth-child(even), .options_container:nth-child(even){
        margin-left: 2%;
    }

    span.hilight{
        color: #f8a23b;
    }

    #where-select{width: 100%; margin: 10px 0; float: left;}
    #where-select .place_content{width: 100%; float: left; box-sizing: border-box; padding: 5px; background-color: rgb(234, 246, 231); margin-bottom: 10px;}
    #where-select .place_content .place_name{width: 95%; float: left; display: inline;}
    #where-select .place_content .place_radio{width: 5%; float: right; margin: auto 0; display: inline;}

    h4.reservation-subtitles{margin-bottom: 10px;}
    /*#where-select .place_content{width: 100%; padding: 5px 0; background-color: rgba(248, 162, 59, 0.17)*/


    /*#details{*/
        /*min-height: 250px;*/
    /*}*/

</style>

<script type="text/javascript" language="javascript">
function hideNext() {
	        var control = document.getElementById("Panel1");
	        if (control != null)
	            control.style.visibility = "hidden";

	        control = document.getElementById("Panel1");
	        if (control != null) {
	            control.innerHTML = "";
	        }
	        // Hide DivPromo
//	        var control = document.getElementById("DivPromo");
//	        if (control != null)
//	            control.style.visibility = "hidden";
	    }
</script>

</head>

<body>

<script src="js/wz_tooltip.js" language="javascript" type="text/javascript"></script>

<form name="formwhen" method="post" action="When.aspx?city=Arvada&amp;state=CO&amp;id=717&amp;restype=0" id="formwhen" style="margin-bottom: 0px;">
<input type="hidden" name="__LASTFOCUS" id="__LASTFOCUS" value="" />
<input type="hidden" name="__EVENTTARGET" id="__EVENTTARGET" value="" />
<input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="" />
<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="/wEPDwUKLTgwMTY0MTUwNg9kFgICBQ9kFhACFQ8PFgIeBFRleHQFFUJpcnRoZGF5IFJlc2VydmF0aW9uc2RkAhgPD2QWBB4Lb25tb3VzZW92ZXIF8AJUaXAoJ0R1ZSB0byB0aGUgbmF0dXJlIG9mIG91ciBidXNpbmVzcywgUGFydHkgUGFja2FnZXMgYXJlIHR5cGljYWxseSBvcmRlcmVkIGZvciBjaGlsZHJlbiwgYnV0IENodWNrIEUuIENoZWVzZSYjMzk7cyBkb2VzIG5vdCBoYXZlIGFuIGFnZSBsaW1pdCBhcyBmYXIgYXMgYnV5aW5nIGEgUGFydHkgUGFja2FnZS4gWW91IGFyZSBtb3JlIHRoYW4gd2VsY29tZSB0byBpbmNsdWRlIGFkdWx0cyBmb3IgYSBQYXJ0eSBQYWNrYWdlLicsIFdJRFRILDIwMCwgUEFERElORywgOCwgQkdDT0xPUiwnI2VkZDRlZScsIFRJVExFQkdDT0xPUiwnIzgwMDA4MCcgLCAgVElUTEUsICdDaGlsZHJlbicsIFRJVExFRk9OVEZBQ0UsICdWZXJkYW5hLHNhbnMtc2VyaWYnKR4Kb25tb3VzZW91dAUHVW5UaXAoKWQCGg8PZBYEHgpvbmtleXByZXNzBRlyZXR1cm4gb25seU51bWJlcihldmVudCk7HgdvbmZvY3VzBQ50aGlzLnNlbGVjdCgpO2QCGw8PZBYCHwMFGXJldHVybiBvbmx5TnVtYmVyKGV2ZW50KTtkAh0PZBYGAgEPDxYCHwAFCDEvMy8yMDE1ZGQCAw88KwAKAQAPFgIeAlNEFgEGAAAEWv/00QhkZAIHD2QWBAIBD2QWHmYPFgQeB2JnY29sb3IFByNmZmZmMDAeB1Zpc2libGVoFgJmD2QWAmYPDxYEHwAFhwE8Y2VudGVyPjxmb250IHNpemU9Jy0yJz5MaW1pdGVkIFRpbWUgT2ZmZXI8L2ZvbnQ+PC9jZW50ZXI+MTAwIEJPTlVTIFRPS0VOUzxCUj48Y2VudGVyPjxmb250IHNpemU9Jy0yJz48dT5MZWFybiBNb3JlPC91PjwvZm9udD48L2NlbnRlcj4fB2gWBB8BBegBVGlwKCdMaW1pdGVkIHRpbWUgb2ZmZXIuIFJlY2VpdmUgMTAwIGJvbnVzIHRva2VucyBhdCBhIHJlc2VydmVkICBCaXJ0aGRheSBQYXJ0eSBTYXR1cmRheSBhdCA5OjMwYW0gb3IgMTA6MDBhbS4nLCBXSURUSCwyMDAsIEJHQ09MT1IsJyNlZGQ0ZWUnLCBUSVRMRUJHQ09MT1IsJyM4MDAwODAnICwgVElUTEUsICcgMTAwIEJvbnVzIFRva2VucycsIFRJVExFRk9OVEZBQ0UsICdWZXJkYW5hLHNhbnMtc2VyaWYnKR8CBQdVblRpcCgpZAIBDxYCHwYFByNmZmZmMDAWAmYPZBYGAgEPEA8WAh8ABQgxMDowMCBBTWRkZGQCAw8PFgIfAAUDMTYwZGQCBQ8PFgIfAAUDMTYwZGQCAg8WBB8GBQcjNGE5NTM2HwEFDnRoaXMudGl0bGU9Jyc7FgJmD2QWBgIBDxAPFgIfAAUIMTI6MzAgUE1kZGRkAgMPDxYCHwAFAjU0ZGQCBQ8PFgIfAAUCNTRkZAIDDxYCHwYFByNGRDMzMzMWAmYPZBYGAgEPEA8WBB8ABQczOjAwIFBNHgdFbmFibGVkaGRkZGQCAw8PFgIfAAUCLTFkZAIFDw8WBB8ABQItMR8IaGRkAgQPFgQfBgUHIzRhOTUzNh8BBQ50aGlzLnRpdGxlPScnOxYCZg9kFgYCAQ8QDxYCHwAFBzU6MzAgUE1kZGRkAgMPDxYCHwAFAjgxZGQCBQ8PFgIfAAUCODFkZAIFDxYEHwYFByM0YTk1MzYfAQUOdGhpcy50aXRsZT0nJzsWAmYPZBYGAgEPEA8WAh8ABQc4OjAwIFBNZGRkZAIDDw8WAh8ABQMxMjBkZAIFDw8WAh8ABQMxMjBkZAIGDxYCHwdoFgJmD2QWBgIBDxAPFgIfAGVkZGRkAgMPDxYCHwAFATBkZAIFDw8WAh8ABQEwZGQCBw8WAh8HaBYCZg9kFgYCAQ8QDxYCHwBlZGRkZAIDDw8WAh8ABQEwZGQCBQ8PFgIfAAUBMGRkAggPFgIfB2gWAmYPZBYGAgEPEA8WAh8AZWRkZGQCAw8PFgIfAAUBMGRkAgUPDxYCHwAFATBkZAIJDxYCHwdoFgJmD2QWBgIBDxAPFgIfAGVkZGRkAgMPDxYCHwAFATBkZAIFDw8WAh8ABQEwZGQCCg8WAh8HaBYCZg9kFgYCAQ8QDxYCHwBlZGRkZAIDDw8WAh8ABQEwZGQCBQ8PFgIfAAUBMGRkAgsPFgIfB2gWAmYPZBYGAgEPEA8WAh8AZWRkZGQCAw8PFgIfAAUBMGRkAgUPDxYCHwAFATBkZAIMDxYCHwdoFgJmD2QWBgIBDxAPFgIfAGVkZGRkAgMPDxYCHwAFATBkZAIFDw8WAh8ABQEwZGQCDQ8WAh8HaBYCZg9kFgYCAQ8QDxYCHwBlZGRkZAIDDw8WAh8ABQEwZGQCBQ8PFgIfAAUBMGRkAg4PFgIfB2gWAmYPZBYGAgEPEA8WAh8AZWRkZGQCAw8PFgIfAAUBMGRkAgUPDxYCHwAFATBkZAIDDw8WAh8ABQ1TYXR1cmRheSAgMS8zZGQCHg8PFgQfAGUfB2hkZAIjD2QWEAIBDxYCHwdnFgICAQ8PFgIfAAUuQXJ2YWRhPGJyPjkzMDEgUmFsc3RvbiBSZC48YnI+QXJ2YWRhLCBDTyA4MDAwMmRkAgMPFgIfB2gWAgIBDw8WAh8AZWRkAgUPFgIfB2gWBAIBDw8WAh8AZWRkAgMPEGRkFgBkAgcPFgIfB2gWAgIBDxBkZBYAZAIJDxYCHwdoFgICAQ8QZGQWAGQCCw8WAh8HaGQCDQ8WAh8HaBYCAgEPDxYCHwBlZGQCDw8WAh8HaGQCJA8WAh8HaGRkVtpZOXnBG63WTz5rvTWChBm+mEU=" />

<script type="text/javascript">
<!--
var theForm = document.forms['formwhen'];
if (!theForm) {
    theForm = document.formwhen;
}
function __doPostBack(eventTarget, eventArgument) {
    if (!theForm.onsubmit || (theForm.onsubmit() != false)) {
        theForm.__EVENTTARGET.value = eventTarget;
        theForm.__EVENTARGUMENT.value = eventArgument;
        theForm.submit();
    }
}
// -->


	    var domain = window.location.hostname;
	    var _gaq = _gaq || [];
	    _gaq.push(['_setAccount', 'UA-272154-1']);
	    _gaq.push(['_setDomainName', domain]);
	    _gaq.push(['_setAllowLinker', true]);
	    _gaq.push(['_trackPageview']);

	    (function () {
	        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	    })();

	    var url = 'http://www.chuckecheese.com/';
	    function findLocation() {
	        var zip = document.getElementById("zip");
	        if (zip != null) {
	            window.location = url + "discover/locations?zip=" + zip.value;
	        }
	    }

</script>

	

<header class="clearfix">
  <div class="bg-purple">
    <div class="wrapper">
      <div id="locationFinder" class="visible-phone" style="display: block!important">
        <a class="btn" href="/chuckecheesespuertorico/localidades.html" id="link33">Ver Tiendas</a>
      </div>     
      <a id="headerRibbon" href="/chuckecheesespuertorico/chuck_E_club.html"><img id="best_deals" src="_images/ribbon_best-deals.png"></a>
      <a class="nav-toggle">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
       <nav>
        <ul class="clearfix">          
          <li>
            <span class="plus">&#43;</span><span class="minus">&#8211;</span>
            <a class='nav-btn' id='experience_btn' href='/chuckecheesespuertorico/la-experiencia.html' data-image='_images/experience.jpg'>La Experiencia</a>
            <div class="bg-purple dropdown experience">
              <ul>
                
                <li id='our-promisesn'><a href='/chuckecheesespuertorico/promesa.html' data-image='_images/experience_our-promise.jpg'><span>Promesa a los Padres</span></a></li>
                <li id='couponssn'><a href='/chuckecheesespuertorico/visita_chuck.html' data-image='_images/experience_visiting.jpg'><span>Visita Chuck E. Cheeses</span></a></li>
                <li id='locationssn'><a href='/chuckecheesespuertorico/localidades.html' data-image='_images/experience_location-finder.jpg'><span>Localidades</span></a></li>
                <li id='check'><a href='/chuckecheesespuertorico/kid_check.html' data-image='_images/KID-CHECK-frame.jpg'><span>Kid Check</span></a></li>
                <li id='menusn'><a href='/chuckecheesespuertorico/menu.html' data-image='_images/experience_menu.jpg'><span>Menú</span></a></li>
                <li id='couponssn'><a href='/chuckecheesespuertorico/cupones.html' data-image='_images/experience_coupons.jpg'><span>Cupones</span></a></li>
              </ul>
              <img src='_images/experience.jpg' alt=''/>
            </div>    
          </li>
          <li>
            <span class="plus">&#43;</span><span class="minus">&#8211;</span>
            <a class='nav-btn' id='events_btn' href='/chuckecheesespuertorico/cumple.html' data-image='_images/BirthdayPartyfull.jpg'>Cumpleaños</a>
            <div class="bg-purple dropdown events">
              <ul>
                <li id='locationssn'><a href='/chuckecheesespuertorico/paquetes_cumple.html' data-image='_images/Birthdaygrupofull.jpg'><span>Paquetes de Cumpleaños</span></a></li>
                <li id='our-promisesn'><a href='/chuckecheesespuertorico/reservation.php' data-image='_images/events_invitations.jpg'><span>Reserva tu Cumpleaños</span></a></li>
               </ul>
              <img src='_images/events.jpg' alt=''/>
            </div>    
          </li>
        </ul>
        <ul>
          <li><a href="http://chuckecheesepr.com/chuckecheesespuertorico" id="logo"></a></li>
        </ul>                  
        <ul>        
          <li>
            <a class='nav-btn' id='activities_btn' href='/chuckecheesespuertorico/eventos.html' data-image='_images/activities.jpg'>Fiestas y Eventos </a>
            <div class="bg-purple dropdown activities">
              <ul>
                <li id='visiting-cecsn'><a href='/chuckecheesespuertorico/eventos_de_grupo.html' data-image='_images/Website_Nav_GroupEvents_0814.jpg'><span>Eventos de Grupo</span></a></li>
                <li id='locationssn'><a href='/chuckecheesespuertorico/recaudacion_fondos.html' data-image='_images/events_fundraising_school.jpg'><span>Recaudación de Fondos</span></a></li>
                <li id='locationssn'><a href='/chuckecheesespuertorico/disco_kids.html' data-image='_images/events_fundraising_school.jpg'><span>Disco Kids</span></a></li>
                <li id='our-promisesn'><a href='/chuckecheesespuertorico/retro_party.html' data-image='_images/events_invitations.jpg'><span>Retro Party</span></a></li>
                <li id='our-promisesn'><a href='/chuckecheesespuertorico/movie_night.html' data-image='_images/events_invitations.jpg'><span>Movie Night</span></a></li>
                <li id='rewards-calendarssn'><a href='/chuckecheesespuertorico/calendario.html' data-image='_images/activities_rewards-calendars.jpg'><span>Calendario de Actividades</span></a></li>
                 </ul>
              <img src='_images/activities.jpg' alt=''/>
            </div>    
          </li>
          <li>
            <a class='nav-btn' id='memorias_btn' href='/chuckecheesespuertorico/memorias_compartidas.html' data-image='_images/activities.jpg'>Memorias Compartidas</a>
            <div class="bg-purple dropdown memorias">
              <ul>
                <li id='gamessn'><a href='/chuckecheesespuertorico/juegos.html' data-image='_images/activities_games.jpg'><span>Juegos</span></a></li>
                <li id='downloadssn'><a href='/chuckecheesespuertorico/descargas.html' data-image='_images/activities_downloads.jpg'><span>Descargas</span></a></li>
                <li id='videossn'><a href='/chuckecheesespuertorico/videos.html' data-image='_images/activities_videos.jpg'><span>Videos</span></a></li>
                <li id='rewards-calendarssn'><a href='/chuckecheesespuertorico/calendario_de_premios.html' data-image='_images/activities_rewards-calendars.jpg'><span>Calendarios de Premios</span></a></li>
              </ul>
              <img src='_images/activities.jpg' alt=''/>
            </div>    
          </li>
         </ul>
      </nav>
    </div>
  </div>
</header>

	</div> <!-- #headerBG -->



	<div class="container">
		<div class="row">



	  		<div class="span7">

				<div class="box green">
					<div class="tl">
						<div class="tr">
							<div class="tm">
							</div>
						</div>
					</div>
					<div class="ml">
						<div class="mr">
							<div class="mm">

<table width="100%"><tr><td valign="top">
    <ul class="breadcrumb">
	    <li><a href="/chuckecheesespuertorico/">Inicio</a> <span class="divider">/</span></li>
	    <li class="active">Reservacion Cumpleaños</li>
    </ul>
</td><td></td></tr></table>

								
                                <h1 class="contentHeader"><span id="lblPartyTypeHeader">Reservacion de Cumpleaños</span></h1>

                                <span class="progressBarArrows">
									<a href="reservation.php?step=inicio" style="z-index: 8;">Donde</a><!--
								 --><a href="reservation.php?step=inicio" style="z-index: 7;" class="breadcrumbs current" id="breadcrumbs_when">Cuando</a><!--
								 --><a href="reservation.php?step=paquete" style="z-index: 6;" class="breadcrumbs disabled" id="breadcrumbs_package">Paquetes</a><!--
								 --><a href="reservation.php?step=comida" style="z-index: 5;" class="breadcrumbs disabled" id="breadcrumbs_food">Comida</a><!--
								 --><a href="reservation.php?step=opciones" style="z-index: 4;" class="breadcrumbs disabled" id="breadcrumbs_options">Opciones</a><!--
								 --><a href="reservation.php?step=pago" style="z-index: 3;" class="breadcrumbs disabled" id="breadcrumbs_payment">Pagos</a><!--
                                 --><a href="#" style="z-index: 2;" class="breadcrumbs disabled" id="breadcrumbs_review">Revisar</a><!--
                                 --><a href="#" style="z-index: 1;" class="breadcrumbs disabled" id="breadcrumbs_confirm">Confirmar</a>
								</span>
<!-- MAIN PAGE CONTENT GOES HERE -->
<!-- ************************************************************************************************************************************************************************* --> 
<!-- ************************************************************************************************************************************************************************* -->
<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

	     
<div id="reservations-container">
    <h4 class="reservation-subtitles">Seleccione la sucursal donde celebrará:</h4>
    <div id="where-select">
        <img src="imgs/ajax-loader.gif" width="16" height="16"> Cargando las sucursales...
    </div>
    <h4 class="reservation-subtitles">Cantidad de niños e invitados:</h4>

			        <table>
			            <tr>
			                <td valign="top"><p class="span6" >
                                <span id="Label1">Para cuantos </span><a id="lbChildrenNote" onmouseover="Tip('Due to the nature of our business, Party Packages are typically ordered for children, but Chuck E. Cheese&amp;#39;s does not have an age limit as far as buying a Party Package. You are more than welcome to include adults for a Party Package.', WIDTH,200, PADDING, 8, BGCOLOR,'#edd4ee', TITLEBGCOLOR,'#800080' ,  TITLE, 'Children', TITLEFONTFACE, 'Verdana,sans-serif')" onmouseout="UnTip()" href="javascript:__doPostBack('lbChildrenNote','')" style="color:#5DBA44;font-weight:bold;">niños</a><span id="lblText2"> incluyendo el festejado a usted le gustaría ordenar un paquete de cumpleaños?</span></p></td>
                            <td valign="top" align="right"><input autocomplete="off" name="txtChildren" type="text" value="0" maxlength="3" id="txtChildren" tabindex="1" onchange="hideNext();" onkeypress="return onlyNumber(event);" onfocus="this.select();" style="width:50px;" /></td>
					    </tr>
					    <tr>
					        <td valign="top"><p class="span6">Cuantos asientos para invitados adicionales le gustaría reservar en el paquete de cumpleaños?</p></td>
			                <td valign="top" align="right"><input autocomplete="off" name="txtAdults" type="text" value="0" maxlength="3" id="txtAdults" tabindex="1" onchange="hideNext();" onkeypress="return onlyNumber(event);" style="width:50px;" /></td>
			            </tr>
                        <tr>
					        <td colspan="2"><span style="font-size:10px; color:red">Adult supervision is required for all parties.</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right" style=" padding-bottom:20px;">
<!--                                <a id="show-calendar" tabindex="1" class="btn" href="#show-calendar">Ver calendario</a>-->
                            </td>
			            </tr>
                    </table>
    <h4 id="cuando" class="reservation-subtitles">Fecha:</h4>
                        <div id="show-calendar-div">
                            <form action="#" method="post" enctype="multipart/form-data">
                                <div class="half-content">
                                    <div id="datepicker"></div>
                                </div>
                                <div id="time-picker" class="half-content">
                                    Seleccione el día que desea realizar el cumpleaños, y aquí aparecerán los horarios disponibles
                                </div>

                            </form>
                        </div>

</div> <!-- reservations container -->
                        
<!-- MAIN PAGE CONTENT GOES HERE -->
<!-- ************************************************************************************************************************************************************************* --> 
<!-- ************************************************************************************************************************************************************************* -->
<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

							</div> <!-- /.mm -->
						</div>
					</div>
	  				<div class="bl">
	  					<div class="br">
	  						<div class="bm">
	  						</div>
	  					</div>
	  				</div>
	  			</div> <!-- /.box -->
	  		</div> <!-- /.span -->

	  		<div class="span3 sidebar">

            

				<div class="box teal">
					<div class="tl">
						<div class="tr">
							<div class="tm">
							</div>
						</div>
					</div>
					<div class="ml">
						<div class="mr">
							<div class="mm">
                            <div id="details">
                                <div id="details-inner">

                                    </div>
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

                <!-- /Promo start -->
                
                <!-- /Promo end -->

                
			        

	  		</div> <!-- /.span -->

		</div> <!-- /.row -->

	</div> <!-- / .container -->





	<footer class="container" style="background: url('_images/bg.jpg') repeat #9655b9; width: 100%; margin-top: 0;">
  <nav>
        <a href="/chuckecheesespuertorico/la-experiencia.html">La Experiencia</a>
        <a href="/chuckecheesespuertorico/eventos.html">Fiestas y Eventos</a>
    <a href="/chuckecheesespuertorico/cumple.html">Cumpleaños</a>
        <a href="/chuckecheesespuertorico/memorias_compartidas.html">memorias_compartidas</a>
        <a href="/chuckecheesespuertorico/chuck_E_club.html">Chuck E-Club</a>
    <a href="/chuckecheesespuertorico/reglas-de-Chuck-E-Cheeses.html">Reglas de Chuck E. Cheese’s</a>
    <a href="/chuckecheesespuertorico/localidades.html">Contacto</a>
  </nav>
  <div id="socialicons">
    <fb:like href="https://www.facebook.com/chuckecheesespr" show_faces="false" send="false" layout="button_count" font="arial" colorscheme="light" action="like"></fb:like>
    <a id="facebook" class="social-media trackable external" href="https://www.facebook.com/chuckecheesespr" title="Facebook Footer" target="_blank"><span>Facebook</span></a><!--
        --><a id="twitter" class="social-media trackable external" href="https://twitter.com/ChuckECheesespr" title="Twitter Footer" target="_blank"><span>Twitter</span></a><!--
        -->
    </div>
</footer>

	<div class="modal hide fade fixed-width" id="modal">
	  <div class="modal-header warning">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    <h3>Warning</h3>
	  </div>
	  <div class="modal-body">
	    <p>You are leaving the Chuck E. Cheese website and going to that of a non-affiliated third-party vendor engaged to provide online services for us. The vendor's site is not under our control and the security and privacy protection offered may be less than or differ from that of Chuck E. Cheese. Do you want to continue?</p>
	  </div>
	  <div class="modal-footer">
	    <a href="#" class="btn" data-dismiss="modal">Close</a>
	    <a href="#" class="btn btn-purple" id="confirm-external-link">Continue</a>
	  </div>
	</div>

<script type='text/javascript' src='https://engaged-by.rovion.com/play/201005121154222rjrdHPgpjs'></script>
<script type="text/javascript">
<!--

theForm.oldSubmit = theForm.submit;
theForm.submit = WebForm_SaveScrollPositionSubmit;

theForm.oldOnSubmit = theForm.onsubmit;
theForm.onsubmit = WebForm_SaveScrollPositionOnSubmit;
WebForm_AutoFocus('txtChildren');// -->
</script>
</form>
<script src="_js/main.js" type="text/javascript"></script>
</body>
</html>

