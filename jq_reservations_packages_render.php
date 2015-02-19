<?php
$quien_soy = 'reservation';
$front_end = true;
require_once('admin/includes/configs.php');

Session::init();

$packages = $db->result('packages', '*', NULL, 'id ASC');

$packages_things = $db->result('packages_things', '*', NULL, 'orden ASC');

$packages_content = $db->result('packages_content', '*', NULL);

$packages_content_rendered = [];

$step = 'el paquete';

$needStep1 = '
                Antes de seleccionar '.$step.',
                debe proporcionar la cantidad de niños e invitados adultos, así como la fecha de la celebración del cumpleaños<br>
                <a href="reservation.php?step=reset" title="Clic para seleccionar el lugar, niños e invitados"><span class="hilight">Ir al primer paso</span></a>
                ';

foreach($packages_content as $pkc){

    $Splace = Session::get('place');
//    $Splace = Session::destroy('place');
    $Schildren = Session::get('children');
    $Sadults = Session::get('adults');
    $Stime = Session::get('time');

    if(!$Splace || !$Schildren || !$Sadults || !$Stime){
        die($needStep1);
    }

    $pkc_package_id = $pkc['package_id'];
    $pkc_package_thing_id = $pkc['package_thing_id'];

    $packages_content_rendered[$pkc_package_id][$pkc_package_thing_id] =  true;
}
//echo '<pre>';
//print_r($packages_content_rendered);
//echo '</pre>';

$config = $db->result('reservations_configs', '*', ['id', '=', 1], NULL, 1);

?>
<table class="package_content">
    <thead>
        <tr>
            <td>
            <strong>Seleccione uno de los paquetes</strong><br>
                (Para un mínimo de <?php echo $config['minimum_children']; ?> niños)
            </td>

            <?php
            foreach($packages as $package){
                $pk_id = $package['id'];
                $pk_name = $package['name'];
            ?>
                <td>
                    <input type="radio" name="radio_package" id="radio_package_<?php echo $pk_id; ?>" value="<?php echo $pk_id; ?>" <?php if(Session::get('package') == $pk_id){echo 'checked';} ?>><br>
                    <label for="radio_package_<?php echo $pk_id; ?>"><?php echo $pk_name; ?></label>
                </td>

            <?php
            }
            ?>

        </tr>
    </thead>

    <tbody>
        <?php
        foreach($packages_things as $pkt){
            $pkt_id = $pkt['id'];
            $pkt_name = $pkt['name'];
            ?>

        <tr>
            <td><?php echo $pkt_name; ?></td>

            <?php
            foreach($packages as $package){

                if(isset($packages_content_rendered[$package['id']][$pkt_id])) {
                    ?>
                    <td><img src="<?php echo FOLDER_ROOT_PUBLICO . 'imgs/party-package-star.png' ?>" width="15" height="16"></td>
                <?php
                }else{
                    ?>
                    <td>&nbsp;</td>
            <?php
                }
            }
            ?>
         </tr>

        <?php
        }
        ?>

    </tbody>

    <tfoot>
        <tr>
            <td>
                Precio por niño<br>
                (No incluye impuestos)
            </td>

            <?php
            foreach($packages as $package){
                $pk_price = $package['price'];
                ?>
                <td>
                    $<?php echo $pk_price; ?>
                </td>

            <?php
            }
            ?>

        </tr>
    </tfoot>
</table>