<?php
session_start();
require "../includes/credentials.php";

if(isset($_GET['user_id']) && isset($_GET['tmp_lat']) && isset($_GET['tmp_lng']) && isset($_GET['food_id'])){


    $user_id = $_GET['user_id'];
    $food_id = $_GET['food_id'];
    $tmp_lat = $_GET['tmp_lat'];
    $tmp_lng = $_GET['tmp_lng'];

    $update = "UPDATE food_details_receiver SET tmp_lat = '{$tmp_lat}',tmp_lng = '{$tmp_lng}' WHERE user_id = {$user_id} AND food_id = {$food_id};";

    $result = mysqli_query($conn,$update);

    if($result){
        echo "ok";
    }else{
        echo "error";
    }



}else{
    echo "error";
}


?>