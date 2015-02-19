<?php
$quien_soy = 'reservation';
$front_end = true;
require_once('admin/includes/configs.php');

Session::init();

$costsPackage = 0;
$costsFood = 0;
$costsOptions = 0;
$costsTotal = 0;

?>

<?php

if(Session::get('children') && Session::get('adults') && Session::get('time') && Session::get('place')){ //#DONDE Y NIñOS
    $place = Session::get('place');
    $place = $db->result('places', '*', ['id', '=', $place], NULL, 1);

?>
    <div id="FormDetails1_DivWhere" class="location">
        <h3><div class="headerLine1">Donde</div></h3>
        <div>
            <span class="hilight">
            <?php echo $place['name']; ?>
            </span>
            <p>
                <?php echo nl2br($place['description']); ?>
            </p>


        </div>
    </div>

    <div id="FormDetails1_DivWhen" class="location">
        <div> <span class="location-h"></span><div class="line" id="when"></div><br>
            <span id="FormDetails1_HowManyWhen"><span class="hilight">Cumpleaño</span> <br>
                <?php echo Session::get('children'); ?> Niños,<br><?php echo Session::get('adults'); ?> invitado(s) (Adultos)<br>

                <?php
                $times = Session::get('time');

                $time = $times[3];

                $time = explode(':', $time);

                if($time[0] > 12){
                    $time[0] = $time[0] - 12;
                    $time[2] = 'PM';
                }else{
                    $time[2] = 'AM';

                }

                $time = "{$time[0]}:{$time[1]} $time[2]";

                $date = $times[2];

                $date = explode('-', $date);

                $date = "{$date[2]}/{$date[1]}/{$date[0]}";

                ?>
                a las <?php echo $time; ?>, el <?php echo $date; ?></span>
            <br><br>
            <div id="FormDetails1_DivLinkWhen" style="text-align:right; width:100%; vertical-align:top;position: relative; top:-5px">
                <a href="reservation.php?step=inicio"><span class="hilight">Clic para editar</span></a>
            </div>
        </div>
    </div>
<?php
}

if(Session::get('package')) { #PAQUETE
    $packageID = Session::get('package');

    $package = $db->result('packages', '*', ['id', '=', $packageID], '', 1);

    $packageName  = $package['name'];
    $packagePrice = $package['price'];

    $costsPackage = $packagePrice * Session::get('children');
    ?>

    <div id="FormDetails1_DivPackage" class="location"><br>
    <div>
    <h3>
        <div class="headerLine1">Paquete</div>
    </h3>

    <span id="FormDetails1_PackageName"
          style="position: relative; top:-10px" class="hilight"><br> <?php echo $packageName . ', $' . $packagePrice . ' por niño' ?></span>
    <ul id="FormDetails1_PackageItems" style="position: relative; top:-10px">
    <?php
    $package_content = $db->result(
                'packages_content as pc
                INNER JOIN packages_things as pt
                ON pt.id = pc.package_thing_id',
        [
            'pc.id AS pc_id',
            'pt.name AS pt_name'
        ],
        ['pc.package_id', '=', $packageID],
        'pt.orden ASC'
    );
        foreach ($package_content as $package_content) {
        ?>
            <li><?php echo $package_content['pt_name'] ?></li>
        <?php
        }?>
            </ul>
        <span class="hilight">Costo estimado por el paquete: $<?php echo number_format($costsPackage, 2); ?></span>
            <div id="FormDetails1_DivLinkPackage" style="text-align:right; width:100%; vertical-align:top;position: relative; top:-5px">
                <a href="reservation.php?step=paquete"><span class="hilight">Clic para editar</span></a>
            </div>
        </div>
    </div>
<?php

}

if(Session::get('foods')){ #COMIDA
    $foods = Session::get('foods');

    if($foods != 0 && is_array($foods)){

        $arrayFoodsIDs = [];
        $foodsToQuery = [];


        $directConn = $db->direct_excution();

        foreach ($foods as $food){
            array_push($arrayFoodsIDs, $food['id']);
            $foodsToQuery[$food['id']] = ['id' => $food['id'], 'quantity' => $food['val']];
        }

        $foodsSQL = 'SELECT * FROM food WHERE id IN ('.implode(',', array_fill(0, count($arrayFoodsIDs), '?')).')';
        $directExt = $directConn->prepare($foodsSQL);

        $directExt->execute($arrayFoodsIDs);
        $fromDB_foods = $directExt->fetchAll(PDO::FETCH_ASSOC);


    ?>
    <div id="FormDetails1_DivOptionsFood" class="location" style="position: relative; top:-10px"><br>
        <div><h3><div class="headerLine1">Comida</div></h3><br>
            <ul id="FormDetails1_OptionItemsFood">
                <?php
                foreach($fromDB_foods as $fromDB_foods){

                    $foodQuantity = $foodsToQuery[$fromDB_foods['id']]['quantity'];
                    $foodPrice = $fromDB_foods['price'];

                    $costsFood += $foodPrice * $foodQuantity;

                ?>
                <li><span class="hilight"><?php echo $foodQuantity; ?></span> <?php echo $fromDB_foods['name']; ?> <span class="hilight">$<?php echo number_format($foodPrice, 2); ?></span></li>
                    <?php } ?>
            </ul>
            <span class="hilight">Costo estimado de la comida: $<?php echo number_format($costsFood, 2); ?></span>
            <table width="100%"><tbody><tr><td width="100%" align="right"><a href="reservation.php?step=comida"><span class="hilight">Clic para editar</span></a></td></tr></tbody></table>
        </div>
    </div>
<?php
    }
}


if(Session::get('options')){ #OPCIONES
    $options = Session::get('options');

    if($options != 0 && is_array($options)){

        $arrayOptionsIDs = [];
        $optionsToQuery = [];

        $directConn = $db->direct_excution();

        foreach ($options as $option){
            array_push($arrayOptionsIDs, $option['id']);
            $optionsToQuery[$option['id']] = ['id' => $option['id'], 'quantity' => $option['val']];
        }

        $optionsSQL = 'SELECT * FROM options WHERE id IN ('.implode(',', array_fill(0, count($arrayOptionsIDs), '?')).')';

        $directExt = $directConn->prepare($optionsSQL);

        $directExt->execute($arrayOptionsIDs);

        $fromDB_options = $directExt->fetchAll(PDO::FETCH_ASSOC);


        ?>
        <div id="FormDetails1_DivOptionsFood" class="location" style="position: relative; top:-10px"><br>
            <div><h3><div class="headerLine1">Opciones</div></h3><br>
                <ul id="FormDetails1_OptionItemsFood">
                    <?php
                    foreach($fromDB_options as $fromDB_options){

                        $optionQuantity = $optionsToQuery[$fromDB_options['id']]['quantity'];
                        $optionPrice = $fromDB_options['price'];

                        $costsOptions += $optionPrice * $optionQuantity;

                        ?>
                        <li><span class="hilight"><?php echo $optionQuantity; ?></span> <?php echo $fromDB_options['name']; ?> <span class="hilight">$<?php echo number_format($optionPrice, 2); ?></span></li>
                    <?php } ?>
                </ul>
                <span class="hilight">Costo estimado de las opciones: $<?php echo number_format($costsOptions, 2); ?></span>
                <table width="100%"><tbody><tr><td width="100%" align="right"><a href="reservation.php?step=opciones"><span class="hilight">Clic para editar</span></a></td></tr></tbody></table>
            </div>
        </div>
    <?php
    }
}


if(Session::get('package')) { #COSTOS TOTALES



    $costsTotal = $costsPackage + $costsFood + $costsOptions;



    ?>

    <div id="FormDetails1_DivTotal" class="location" style="position: relative; top:-10px"><br>

        <div>
            <h3>
                <div class="headerLine1">Total Estimado</div>
            </h3>
            <br>

            <div style="text-align:right; width:90%; vertical-align:top;position: relative;top:-5px">
            <span id="FormDetails1_lblEstimatedTotal" title="Estimated Total (Excluding Taxes)"
                  style="color:Black;font-size:Larger;font-weight:bold;">$<?php echo number_format($costsTotal, 2); ?></span>&nbsp;&nbsp; <br>(Sin incluir los impuestos)<br>
            </div>
        </div>
    </div>
<?php
}
//echo '<pre>';
//print_r($_SESSION);
//echo '</pre>';
