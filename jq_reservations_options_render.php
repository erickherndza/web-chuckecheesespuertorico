<?php
$quien_soy = 'reservation';
$front_end = true;
require_once('admin/includes/configs.php');

Session::init();

$options = $db->result('options', '*');

$optionsArray = Session::get('options');

$step = 'las opciones';

$needStep1 = '
                Antes de seleccionar '.$step.', <br>
                debe proporcionar la cantidad de niños e invitados adultos, así como la fecha de la celebración del cumpleaños<br>
                <a href="reservation.php?step=reset" title="Clic para seleccionar el lugar, niños e invitados"><span class="hilight">Ir al primer paso</span></a>
                ';

$needStep2 = '
                Antes de seleccionar '.$step.',
                 debe seleccionar el paquete de cumpleaños<br>
                <a href="reservation.php?step=paquete" title="Clic para seleccionar el paquete de compleaños"><span class="hilight">Seleccinar el paquete</span></a>
                ';

foreach($options as $option){

    $Splace = Session::get('place');
//    $Splace = Session::destroy('place');
    $Schildren = Session::get('children');
    $Sadults = Session::get('adults');
    $Stime = Session::get('time');

    $Spackage = Session::get('package');

    if(!$Splace || !$Schildren || !$Sadults || !$Stime){
        die($needStep1);
    }

    if(!$Spackage){
        die($needStep2);
    }

    $option_id = $option['id'];
    $option_name = $option['name'];
    $option_description = $option['description'];
    $option_photo = $option['photo'];
    $option_price = $option['price'];

    $option_description = nl2br($option_description);
    $option_photo = FOLDER_ROOT_PUBLICO.FOLDER_OPTIONS_ROOT.$option_photo;

    $optionQuantity = 0;

    if(is_array($optionsArray)){
        foreach($optionsArray as $oa){
            if($oa['id'] == $option_id){
                $optionQuantity = $oa['val'];
                break;
            }
        }
    }

    echo '<div class="options_container">
                <strong>'.$option_name.'</strong><br>
                <img src="'.$option_photo.'" width="240"><br>
                <p>'.$option_description.'</p>
                <p>
                <label style="display:inline;" for="option_'.$option_id.'"><span class="hilight">Precio:</span> $'.$option_price.' <span class="hilight">Cantidad:</span> </label>
                <input class="option_input" id="option_'.$option_id.'" name="'.$option_id.'" autocomplete="off"  type="text" value="'.$optionQuantity.'" maxlength="3" onchange="hideNext();" onkeypress="return onlyNumber(event);" style="width:50px; display:inline;">
                </p>
            </div>';

}