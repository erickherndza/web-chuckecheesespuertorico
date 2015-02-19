<?php
$quien_soy = 'reservation';
$front_end = true;
require_once('admin/includes/configs.php');


//    $packageID = 19;
//
//    $package = $db->result('packages', '*', ['id', '=', 19], '', 1);
//
//    $packageName  = $package['name'];
//    $packagePrice = $package['price'];
//    ?>
<!---->
<!--    <div id="FormDetails1_DivPackage" class="location"><br>-->
<!--    <div>-->
<!--    <h3>-->
<!--        <div class="headerLine1">Paquete</div>-->
<!--    </h3>-->
<!---->
<!--    <span id="FormDetails1_PackageName"-->
<!--          style="position: relative; top:-10px" class="hilight"><br> --><?php //echo $packageName . ', $' . $packagePrice . ' por niño' ?><!--</span>-->
<!--    <ul id="FormDetails1_PackageItems" style="position: relative; top:-10px">-->
<!--    --><?php
//    $package_content = $db->result(
//                'packages_content as pc
//                INNER JOIN packages_things as pt
//                ON pt.id = pc.package_thing_id',
//        [
//            'pc.id AS pc_id',
//            'pt.name AS pt_name'
//        ],
//        ['pc.package_id', '=', $packageID],
//        'pt.orden ASC'
//    );
//        foreach ($package_content as $package_content) {
//        ?>
<!--            <li>--><?php //echo $package_content['pt_name'] ?><!--</li>-->
<!--        --><?php
//        }?>
<!--            </ul>-->
<!--            <div id="FormDetails1_DivLinkPackage" style="text-align:right; width:100%; vertical-align:top;position: relative; top:-5px">-->
<!--                <a id="FormDetails1_LinkPackage" href="javascript:__doPostBack('FormDetails1$LinkPackage','')" style="font-weight:bold;">Click to Edit</a>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<?php


    $foods = [
            ['id' => 2,'val' => 2],
            ['id' => 3,'val' => 5],
            ['id' => 7,'val' => 1]
        ];


    if($foods != 0 && is_array($foods)){

        $arrayFoodsIDs = [];
        $arrayFoodsQuantitys = [];

        $directConn = $db->direct_excution();

        foreach ($foods as $food){
            array_push($arrayFoodsIDs, $food['id']);
            array_push($arrayFoodsQuantitys, $food['val']);
        }

        $foodsSQL = 'SELECT * FROM food WHERE id IN ('.implode(',', array_fill(0, count($arrayFoodsIDs), '?')).')';
        $directExt = $directConn->prepare($foodsSQL);

        $directExt->execute($arrayFoodsIDs);
        $fromDB_foods = $directExt->fetchAll(PDO::FETCH_ASSOC);

//        die($foodsSQL);

//        $fromDB_foods = $db->result('food', '*', ['id', 'IN', [2,3]]);

//        Session::destroy();
        echo '<pre>';
        print_r($fromDB_foods);
        echo '</pre>';

        ?>
    <!--<div id="FormDetails1_DivOptionsFood" class="location" style="position: relative; top:-10px"><br>
        <div><h3><div class="headerLine1">Comida</div></h3><br>
            <ul id="FormDetails1_OptionItemsFood">
                <li>Qty. 1 - 8" Round Vanilla Cake $9.99</li><li>Qty. 5 - 1/4 sheet Chocolate Cake $16.99</li><li>Qty. 2 - Chocolate Ice Cream Cups .99 each</li>
            </ul>
            <table width="100%"><tbody><tr><td width="100%" align="right"><a id="FormDetails1_LinkOptionsFood" href="javascript:__doPostBack('FormDetails1$LinkOptionsFood','')" style="font-weight:bold;">Click to Edit</a></td></tr></tbody></table>
        </div>
    </div>-->
<?php
    }
