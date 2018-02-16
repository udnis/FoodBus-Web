<?php
session_start();
require "../includes/credentials.php";

if(isset($_GET['user_id']) && isset($_GET['food_id'])){


    $user_id = $_GET['user_id'];
    $food_id = $_GET['food_id'];

    $receiver_gps = "SELECT tmp_lat,tmp_lng FROM food_details_receiver WHERE user_id = {$user_id} AND food_id = {$food_id} LIMIT 1;";


    $result = mysqli_query($conn,$receiver_gps);

    if($result){

        if(mysqli_num_rows($result) > 0){


            while($row = mysqli_fetch_assoc($result)){

                echo json_encode($row);
            }
        }

    }else{
        echo "error";
    }



}else{
    echo "error";
}


?>