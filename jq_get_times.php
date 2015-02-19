<?php
$quien_soy = 'reservation';
$front_end = true;
require_once('admin/includes/configs.php');

$date = r_get_post('date');

$days =  ['', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];

$week_days = [
    'lunes' => 'Lunes',
    'martes' => 'martes',
    'miercoles' => 'Miércoles',
    'jueves' => 'Jueves',
    'viernes' => 'Viernes',
    'sabado' => 'Sábado',
    'domingo' => 'Domingo'
];

$day = $days[date('N', strtotime($date))];

echo '<h4 id="cuando" class="reservation-subtitles">Horarios disponibles:</h4>';

$horarios = $db->result('reservations_times', NULL, ['week_day', '=', $day], 'time ASC');

$configs = $db->result('reservations_configs', NULL, ['id', '=', 1], NULL, 1);

$configLimitsReservations = $configs['numbers_per_time'];

$disabled = 'disabled';

foreach($horarios as $horarios){

    $id = $horarios['id'];
    $time = $horarios['time'];
    $week_day = $horarios['week_day'];

    $the_time = explode(':', $time);

    if($the_time[0] > 12){
        $the_time[0] = $the_time[0] - 12;
        $the_time[2] = 'pm';
    }else{
        $the_time[2] = 'am';

    }

    $timesReserved = $db->result('reservations', '*', ['time', '=', $id]);
    $timesReservedQuantity = $db->rowsCount();

    if($timesReservedQuantity >= $configLimitsReservations){
        $disabled = 'disabled';
    }else{
        $disabled = '';
    }

    $the_time = "{$the_time[0]}:{$the_time[1]}{$the_time[2]}";

    echo  '<div class="content_time"><input value="'."{$id}::{$week_day}::{$date}::{$time}".'" type="radio" name="input_time" id="input_time_'.$the_time.'" '.$disabled.' /> <label for="input_time_'.$the_time.'">'.$the_time.'</label></div>';
}