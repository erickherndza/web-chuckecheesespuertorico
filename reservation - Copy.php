<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" lang="en"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en"> <!--<![endif]-->



<head>
	<meta charset="utf-8">
	<title>Chuck E. Cheese's</title>

	<!-- Meta -->
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes"> -->
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="author" content="">
	<meta name="robots" content="noindex, nofollow" />
  	<meta name="robots" content="noarchive" />
	<meta http-equiv="imagetoolbar" content="no" />

	<!-- Facebook Open Graph -->
	<meta property="og:title" content="Chuck E. Cheese's" />
	<meta property="og:type" content="restaurant" />
	<meta property="og:url" content="http://www.chuckecheese.com" />
	<meta property="og:image" content="http://www.chuckecheese.com/_assets/_ico/facebook-thumb.jpg" />
	<meta property="og:description" content="Chuck E. Cheese's" />

	<!-- Fav and Touch Icons -->
	<link rel="shortcut icon" href="/_assets/_ico/favicon.ico">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/_assets/_ico/apple-touch-icon-144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/_assets/_ico/apple-touch-icon-114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/_assets/_ico/apple-touch-icon-72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" href="/_assets/_ico/apple-touch-icon-57-precomposed.png">

	<!-- Typekit Fonts -->
	<script type="text/javascript" src="js/sml4yoz.js"></script>
	<script type="text/javascript">	    try { Typekit.load(); } catch (e) { }</script>

	<!-- Styles -->
	<link href="js/bootstrap.css" rel="stylesheet">
	<link href="js/jquery-ui.min.css" rel="stylesheet">
	<link href="js/jquery-ui.structure.min.css" rel="stylesheet">
	<link href="js/jquery-ui.theme.min.css" rel="stylesheet">

	<!-- Libraries and Plugins -->
	<script src="js/jquery.min.js" type="text/javascript"></script>
	<script src="js/jquery-ui.min.js" type="text/javascript"></script>

    <script>
        $(document).ready(function(){

            $('.breadcrumbs').click(function(){
               return false;
            });

            $( "#datepicker" ).datepicker({
                dateFormat: "yy-mm-dd",
                gotoCurrent: true,
                maxDate: "+1y",
                minDate: "0",
                onSelect: function($date){
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

                },
                onLoad : function($date){
                    alert($date);
                }
            });

            $('#show-calendar-div').css('display', 'none');

            function strip(number) {
                return (parseFloat(number.toFixed(0)));
            }

            $('#show-calendar').click(function(){

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
                        $('#show-calendar-div').css('display', 'block');
                    }

                }
                return false;
            });

            $('input[name="input_time"]').live('change',function(){
                $('#reservation-nav-bar').remove();
                $data = '<div id="reservation-nav-bar"><div class="reservation-previous-step"><a href="#go-step1" id="bt-go-step1" tabindex="2" class="btn">&laquo;&laquo; Volver a comenzar la reservación</a></div><div class="reservation-next-step"><a id="bt-go-step2" tabindex="1" class="btn" href="">Próximo (Paquetes)  &raquo;&raquo;</a></div></div>';
                $('#reservations-container').append($data);

            });

            function display_step1(){
                $.ajax({
                    url : 'jq_reservations_steps.php',
                    data : {
                        'action' : 'go-step1'
                    },
                    type : 'POST',
                    dataType: 'json',
                    success : function($result){
                        $response = $result.response;

                        if($response == 'good') {

                            window['minimumChildren'] = $result.minimumChildren;
                            window['minimumAdults'] = $result.minimumAdults;

                        }else{
                            alert('No se pudo gestionar los datos para la reservación, por favor, refresque la página');
                        }

                    }

                });

                $('#details-inner').html('<div id="FormDetails1_DivWhere" class="location"><h3><div class="headerLine1">Where</div></h3><div><span id="FormDetails1_storeInfo">Arvada<br>9301 Ralston Rd.<br>Arvada, CO 80002</span><br><div id="FormDetails1_DivLinkWhere" style="text-align:right; width:100%; vertical-align:top;position: relative; top:-5px"><a id="FormDetails1_LinkWhere" href="#" style="font-weight:bold;"></a></div></div></div>');

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
                $.ajax({
                    url : 'jq_reservations_steps.php',
                    data : {
                        'action'      :   'go-step2',
                        'txtChildren' :   $('#txtChildren').val(),
                        'txtAdults'   :   $('#txtAdults').val(),
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

            display_step1();


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

	<div id="headerBG">

		<header class="container">

			<a id="logo" href="http://www.chuckecheese.com/" title="Chuck E. Cheese's"></a>
			
			<a id="ourPromise" href="http://www.chuckecheese.com/discover/our-promise" title="Our Promise To Parents"></a>

			<div id="locationFinder" class="visible-desktop">
				  <label for="zip">Location Finder</label>
				  <input type="text" placeholder="Enter Zip Code" id="zip" tabindex="1" />
                  <a  class="btn btn-warning" onclick="findLocation()"><i class="icon-chevron-right"></i></a>
			</div>

            <input name="lblRType" type="hidden" id="lblRType" value="Birthday" />

			<nav>
							
				<ul id="menu" class="clearfix">

					<li class="dropdown">
					  	<a href="http://www.chuckecheese.com/discover" class="two-lines">Discover <br/>Our Value<i class="icon-caret-down"></i></a>

					  	<ul class="dropdown-menu">
					      	<li><a href="http://www.chuckecheese.com/discover/locations">Location Finder</a></li>
					      	<li><a href="http://www.chuckecheese.com/discover/the-experience">The Experience</a></li>
					      	<li><a href="http://www.chuckecheese.com/discover/our-promise">Our Promise</a></li>
					      	<li><a href="http://www.chuckecheese.com/discover/menu">Menu</a></li>
					      	<li><a href="http://www.chuckecheese.com/discover/eclub">Email Club</a></li>
					      	<li><a href="http://www.chuckecheese.com/discover/coupons">Coupons</a></li>
					      	<li><a href="http://www.chuckecheese.com/discover/activities">Activities</a></li>
					      	<li><a href="http://www.chuckecheese.com/discover/rewards-calendars">Rewards Calendars</a></li>
					      	<li><a href="http://www.chuckecheese.com/discover/videos">Videos</a></li>
					  	</ul>
					</li>

					<li class="dropdown active">
					  	<a href="http://www.chuckecheese.com/plan" class="two-lines">Parties <br/><span class="amp">&amp;</span> Events<i class="icon-caret-down"></i></a>

					  	<ul class="dropdown-menu">
					      	<li class="active"><a href="http://www.chuckecheese.com/plan/birthdays">Birthdays</a></li>
					      	<li><a href="http://www.chuckecheese.com/plan/group-events">Group Events</a></li>
					      	<li><a href="http://www.chuckecheese.com/plan/fundraising">Fundraising</a></li>
					      	<li><a href="http://www.chuckecheese.com/plan/invitations">Invitations</a></li>
					  	</ul>
					</li>

					<li >
					  	<a href="http://www.chuckecheese.com/play" class="two-lines">Play <br/>Games</a>
					</li>

					<li>
					  	<a href="http://www.chuckecheese.com/share" class="two-lines">Share <br/>Memories</a>
					</li>

				</ul>

			</nav>

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
	    <li><a href="#">Home</a> <span class="divider">/</span></li>
	    <li><a href="#">Plan</a> <span class="divider">/</span></li>
	    <li class="active">How Many & When</li>
    </ul>
</td><td></td></tr></table>

								
                                <h1 class="contentHeader"><span id="lblPartyTypeHeader">Birthday Reservations</span></h1>

                                <span class="progressBarArrows">
									<a href="reservation.php" style="z-index: 8;">Where</a><!--
								 --><a href="#" style="z-index: 7;" class="breadcrumbs current" id="breadcrumbs_when">When</a><!--
								 --><a href="#" style="z-index: 6;" class="breadcrumbs disabled" id="breadcrumbs_package">Package</a><!--
								 --><a href="#" style="z-index: 5;" class="breadcrumbs disabled" id="breadcrumbs_food">Food</a><!--
								 --><a href="#" style="z-index: 4;" class="breadcrumbs disabled" id="breadcrumbs_options">Options</a><!--
								 --><a href="#" style="z-index: 3;" class="breadcrumbs disabled" id="breadcrumbs_payment">Payments</a><!--
                                 --><a href="#" style="z-index: 2;" class="breadcrumbs disabled" id="breadcrumbs_review">Review</a><!--
                                 --><a href="#" style="z-index: 1;" class="breadcrumbs disabled" id="breadcrumbs_confirm">Confirm</a>
								</span>
<!-- MAIN PAGE CONTENT GOES HERE -->
<!-- ************************************************************************************************************************************************************************* --> 
<!-- ************************************************************************************************************************************************************************* -->
<!------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

	     
<div id="reservations-container">
			        <table>
			            <tr>
			                <td valign="top"><p class="span6" >
                                <span id="Label1">How many </span><a id="lbChildrenNote" onmouseover="Tip('Due to the nature of our business, Party Packages are typically ordered for children, but Chuck E. Cheese&amp;#39;s does not have an age limit as far as buying a Party Package. You are more than welcome to include adults for a Party Package.', WIDTH,200, PADDING, 8, BGCOLOR,'#edd4ee', TITLEBGCOLOR,'#800080' ,  TITLE, 'Children', TITLEFONTFACE, 'Verdana,sans-serif')" onmouseout="UnTip()" href="javascript:__doPostBack('lbChildrenNote','')" style="color:#5DBA44;font-weight:bold;">children</a><span id="lblText2"> including the birthday child would you like to order a Party Package for?</span></p></td>
                            <td valign="top" align="right"><input autocomplete="off" name="txtChildren" type="text" value="0" maxlength="3" id="txtChildren" tabindex="1" onchange="hideNext();" onkeypress="return onlyNumber(event);" onfocus="this.select();" style="width:50px;" /></td>
					    </tr>
					    <tr>
					        <td valign="top"><p class="span6">How many seats would you like to request for additional guests not receiving the Party Package?</p></td> 
			                <td valign="top" align="right"><input autocomplete="off" name="txtAdults" type="text" value="0" maxlength="3" id="txtAdults" tabindex="1" onchange="hideNext();" onkeypress="return onlyNumber(event);" style="width:50px;" /></td>
			            </tr>
                        <tr>
					        <td colspan="2"><span style="font-size:10px; color:red">Adult supervision is required for all parties.</span></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right" style=" padding-bottom:20px;">
                                <a id="show-calendar" tabindex="1" class="btn" href="#show-calendar">Ver calendario</a>
                            </td>
			            </tr>
                    </table>

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





	<footer class="container">
		<!-- Comments remove the space between inline-block elements -->
		<!-- http://css-tricks.com/fighting-the-space-between-inline-block-elements/ -->

		<nav>
			<a href="http://www.chuckecheese.com/franchising">Franchising</a><!--
		 --><a href="http://www.chuckecheese.com/product-recalls">Product Recalls</a><!--
		 --><a href="http://www.chuckecheese.com/terms-and-privacy">Terms and Privacy</a><!--
		 --><a href="http://www.chuckecheese.com/site-map">Site Map</a><!--
		 --><a href="http://www.chuckecheese.com/careers">Careers</a><!--
		 --><a href="http://phx.corporate-ir.net/phoenix.zhtml?c=72589&p=irol-irhome">Investor Relations</a><!--
         --><a href="http://www.chuckecheese.com/snacks">Snacks</a><!--
		 --><a href="http://dev.chuckecheese.com/survey/validate.php">Survey</a><!--
		 --><a href="http://www.chuckecheese.com/forms/contactus">Contact Us</a><!--
         --><a href="http://www.chuckecheese.com/contact-us/party-faqs">FAQs</a>
		</nav>

		<div id="socialicons">
			<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2FOfficialChuckECheese&amp;send=false&amp;layout=button_count&amp;width=95&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=22&amp;appId=448812155163345" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:95px; height:22px;" allowTransparency="true"></iframe>

			<a id="facebook" class="trackable external" href="https://www.facebook.com/OfficialChuckECheese" title="FacebookFooter" target="_blank"><span>Facebook</span></a><!--
		 --><a id="twitter" class="trackable external" href="http://twitter.com/chuckecheese/" title="TwitterFooter" target="_blank"><span>Twitter</span></a><!--
		 --><a id="youtube" class="trackable external" href="http://www.youtube.com/user/ChuckECheeseVideo" title="YouTubeFooter" target="_blank"><span>YouTube</span></a><!--
		 --><a id="pinterest" class="trackable external" href="http://pinterest.com/chuckecheese/" title="PinterestFooter" target="_blank"><span>Pinterest</span></a><!--
		 --><a id="foursquare" class="trackable external" href="https://foursquare.com/chuckecheese" title="FoursquareFooter" target="_blank"><span>foursquare</span></a>
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

</body>
</html>

