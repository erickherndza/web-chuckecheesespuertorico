<?php
$quien_soy = 'reservation';
$front_end = true;
require_once('admin/includes/configs.php');

Session::init();

$foods = $db->result('food', '*');

$foodsArray = Session::get('foods');

$step = 'la comida';

$needStep1 = '
                Antes de seleccionar '.$step.',
                debe proporcionar la cantidad de niños e invitados adultos, así como la fecha de la celebración del cumpleaños<br>
                <a href="reservation.php?step=reset" title="Clic para seleccionar el lugar, niños e invitados"><span class="hilight">Ir al primer paso</span></a>
                ';

$needStep2 = '
                Antes de seleccionar '.$step.',
                debe seleccionar el paquete de cumpleaños<br>
                <a href="reservation.php?step=paquete" title="Clic para seleccionar el paquete de compleaños"><span class="hilight">Seleccinar el paquete</span></a>
                ';

foreach($foods as $food){

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

    $food_id = $food['id'];
    $food_name = $food['name'];
    $food_description = $food['description'];
    $food_photo = $food['photo'];
    $food_price = $food['price'];

    $food_description = nl2br($food_description);
    $food_photo = FOLDER_ROOT_PUBLICO.FOLDER_FOOD_ROOT.$food_photo;

    $foodQuantity = 0;

    if(is_array($foodsArray)){
        foreach($foodsArray as $fa){
            if($fa['id'] == $food_id){
                $foodQuantity = $fa['val'];
                break;
            }
        }
    }

    echo '<div class="food_container">
                <strong>'.$food_name.'</strong><br>
                <img src="'.$food_photo.'" width="240"><br>
                <p>'.$food_description.'</p>
                <p>
                <label style="display:inline;" for="food_'.$food_id.'"><span class="hilight">Precio:</span> $'.$food_price.' <span class="hilight">Cantidad:</span> </label>
                <input class="food_input" id="food_'.$food_id.'" name="'.$food_id.'" autocomplete="off"  type="text" value="'.$foodQuantity.'" maxlength="3" onchange="hideNext();" onkeypress="return onlyNumber(event);" style="width:50px; display:inline;">
                </p>
            </div>';

}