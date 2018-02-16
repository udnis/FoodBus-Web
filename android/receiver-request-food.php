<?php


session_start();
require "../includes/credentials.php";


if(isset($_GET['receiver_id']) && isset($_GET['donor_id']) && isset($_GET['food_id']) && isset($_GET['amount_claimed']) && isset($_GET['name'])){

    $name = $_GET['name'];
    $receiver_id = $_GET['receiver_id'];
    $donor_id = $_GET['donor_id'];
    $food_id = $_GET['food_id'];
    $amount_claimed = $_GET['amount_claimed'];


    $check = "SELECT food_id,user_id FROM food_details_receiver WHERE food_id={$food_id} AND user_id={$receiver_id} AND claim_request = 1;";

    $tmp_lat = "";
    $tmp_lng = "";

    $result = mysqli_query($conn, $check);

    if(mysqli_num_rows($result) > 0){
        echo "present";
    }else{


        $location = "SELECT latitude,longitude FROM receiver_address WHERE user_id = {$receiver_id};";

        $loc_result = mysqli_query($conn, $location);


        if(mysqli_num_rows($loc_result)){
            while($rowl = mysqli_fetch_assoc($loc_result)){
                $tmp_lat = $rowl['latitude'];
                $tmp_lng = $rowl['longitude'];
            }
        }


        $receiver_food_insert = "INSERT INTO food_details_receiver( ";
        $receiver_food_insert .= "food_id,user_id,time,date,name,amount_claimed,donor_id,claim_request,approved,declined,amount_got,tmp_lat,tmp_lng) VALUES (";
        $receiver_food_insert .= "'$food_id', '{$receiver_id}' , TIME(NOW()) , DATE(NOW()) , '{$name}', '{$amount_claimed}' , '{$donor_id}' , 1 , 0 ,0,0,'{$tmp_lat}','{$tmp_lng}');";


        
        $result2 = mysqli_query($conn, $receiver_food_insert);

            if ($result2) {


                $d_name = "SELECT name FROM receiver_address WHERE user_id={$receiver_id};";
                $r = mysqli_query($conn, $d_name);
                $d_name = "";
                while($row = mysqli_fetch_row($r)){
                    $d_name = $row[0];
                }


                $demo = array("food_name" => $name,
                    "receiver_name" => $d_name,
                    "amount_claimed" =>$amount_claimed);


                sendNotiDonor($conn,$demo,$donor_id);
                echo "ok";
         }

    }

}


?>