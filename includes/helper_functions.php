<?php

function get_local_time($time){



    $hrs = intval(substr($time, 0,2));
    $min = substr($time, 2,3);


    if($hrs <= 12){
        return $hrs . $min . " AM";
    }else{
        return ($hrs-12) . $min . " PM";
    }
}

function get_local_date($date){

    $d = strtotime($date);

    return date("d F Y",$d);
}




?>