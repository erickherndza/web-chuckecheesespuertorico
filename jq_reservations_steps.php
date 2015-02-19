<?php
$front_end = true;
$quien_soy = 'reservation';

require_once('admin/includes/configs.php');

Session::init();

$action = r_get_post('action');
$action_error = '';
$action_waring = '';
$action_good = '';

$return = ['response' => 'error', 'message' => 'Debe proporcionar los datos para la reservación'];

$needStep1 = '
                Antes de realzar este paso debe seleccionar el lugar,
                cantidad de niños e invitados adultos, así como la fecha de la selebración del cumpleaños<br>
                <a href="reservation.php?step=inicio"><span class="hilight">Ir al primer paso</span></a>
                ';

$ahoramismo = date("Y-n-j H:i:s");

if ($action != '') {
    if ($action == 'go-step1') { #STEP 1 --- RESETEO DE TTODO, PARA COMENZAR DE NUEVO

        $reset = r_get_post('reset');

        if($reset == 'no'){
            $Splace = Session::get('place');
            $Schildren = Session::get('children');
            $Sadults = Session::get('adults');
            $Stime = Session::get('time');

            if(!$Splace || !$Schildren || !$Sadults || !$Stime){
                $reset = 'si';
            }

        }else{
          $reset = 'si';
        }

        if($reset == 'si'){
            Session::destroy([
                'place',
                'children',
                'adults',
                'time',
                'package',
                'foods',
                'options'
            ]);
        }

        $fromDB_places = $db->result('places', '*');
        $places = '<div id="places_content">';

        foreach($fromDB_places as $place){
            $placeID = $place['id'];
            $placeName = $place['name'];
            $placeDescription = $place['description'];

            $placeChequed = (isset($Splace) && $Splace == $placeID) ? 'checked' : '';

            $places .= '<div class="place_content">
                            <div class="place_name"><label for="input_radio'.$placeID.'"><span class="hilight"><strong>'.$placeName.'</strong></span><br>'.nl2br($placeDescription).'</label></div>
                            <div class="place_radio"><input type="radio" name="place_input_radio" id="input_radio'.$placeID.'" value="'.$placeID.'" '.$placeChequed.'></div>
                        </div>';
        }

        $places .= '</div>';

        $config = $db->result('reservations_configs', '*', ['id', '=', 1], NULL, 1);


        if($reset == 'no') {
            $return = [
                'response'          => 'good',
                'message'           => 'Primer step realizado',
                'minimumChildren'   => $config['minimum_children'],
                'minimumAdults'     => $config['minimum_adults'],
                'places'            => $places,
                'children'          => $Schildren,
                'adults'            => $Sadults,
                'time'              => $Stime
            ];

        }else{
            $return = ['response' => 'good', 'message' => 'Primer step realizado', 'minimumChildren' => $config['minimum_children'], 'minimumAdults' => $config['minimum_adults'], 'places' => $places];
        }



    }elseif ($action == 'go-step2') { #STEP 2 --- PROCESADO DE LOS NIñOS Y FECHA

        $txtChildren = r_get_post('txtChildren', 'numeric');
        $txtAdults   = r_get_post('txtAdults', 'numeric');
        $input_time  = r_get_post('input_time');
        $place       = r_get_post('place', 'numeric');

        if($txtChildren != '' && $txtAdults != '' && $place != ''){

            $input_time = explode('::', $input_time);

            if(!validate::is_numeric($input_time[0])){
                $action_waring = true;
                $return = ['response' => 'warning', 'message' => $needStep1];
            }

        }else{
            $action_waring = true;
            $return = ['response' => 'warning', 'message' => $needStep1];
        }

        if($action_error == '' && $action_waring == ''){
            /*if(
            $db->insert('reservations',
                [
                    'children'  => $txtChildren,
                    'adults'    => $txtAdults,
                    'date'      => "{$input_time[2]} {$input_time[3]}",
                    'time'      => $input_time[0]
                ]
                )){*/
            Session::set('place', $place);
            Session::set('children', $txtChildren);
            Session::set('adults', $txtAdults);
            Session::set('time', $input_time); // ID, day, date, time (01::martes::01-30-2015::11:30:00)

            $return = ['response' => 'good', 'message' => 'Step 1 realizado con éxito'];

//            }else{
//                $return = ['response' => 'error', 'message' => 'No se pudo guardar la reservacion, debido a un error interno, trate otra vez'];
//            }
        }

    }else if($action == 'go-step3'){ #STEP 3 --- PAQUETES
        $package = r_get_post('package', 'numeric');

        if($package != ''){
            Session::set('package', $package);
            $return = ['response' => 'good', 'message' => 'Step 2 realizado con éxito'];
        }else{
            $return = ['response' => 'warning', 'message' => 'Proporcione el paquete en el Step 2'];
        }


    }else if($action == 'go-step4') { #STEP 4 --- COMIDA

        $foods = r_get_post('foods', 'array');

        if(!is_array($foods)){
            $foods = 0;
        }

            Session::set('foods', $foods);
            $return = ['response' => 'good', 'message' => 'Step 3 realizado con éxito'];

    }else if($action == 'go-step5') { #STEP 5 --- OPCIONES

        $options = r_get_post('options', 'array');

        if(!is_array($options)){
            $options = 0;
        }

        Session::set('options', $options);
        $return = ['response' => 'good', 'message' => 'Step 4 realizado con éxito'];

    }


}

echo json_encode($return);