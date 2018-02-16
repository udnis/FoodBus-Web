<?php
session_start();
require "../includes/credentials.php";

if(isset($_GET['user_id'])){

    $donor_id = $_GET['user_id'];

    $approved = "SELECT a.food_id,a.time,a.date,a.name,a.amount_claimed,a.amount_got,b.latitude,b.longitude,b.name,b.door,b.street,b.area,b.village,c.amount_left,b.city,b.pin,b.phone,a.user_id,a.id,a.is_wasted FROM";
    $approved .= " food_details_receiver a,receiver_address b,food_details_donor c WHERE a.approved = 1 AND a.donor_complete = 0  AND a.donor_id = c.user_id AND a.food_id = c.food_id AND a.user_id = b.user_id;";

    $result = mysqli_query($conn,$approved);


    $array = array();

    if(mysqli_num_rows($result) > 0){

        while($row = mysqli_fetch_row($result)){

            $array[] = array("food_id" => $row[0],
                "time" => $row[1],
                "date" => $row[2],
                "name" => $row[3],
                "amount_claimed" => $row[4],
                "receiver_lat" => $row[6],
                "receiver_lng" => $row[7],
                "receiver_name" => $row[8],
                "receiver_door" => $row[9],
                "receiver_street" => $row[10],
                "receiver_area" => $row[11],
                "receiver_village" => $row[12],
                "receiver_city" => $row[14],
                "receiver_pin" => $row[15],
                "receiver_phone" => $row[16],
                "amount_got" => $row[5],
                "amount_left" => $row[13],
                "user_id" => $row[17],
                "request_id" => $row[18],
                "is_wasted" => $row[19]);
        }

        echo json_encode($array);
    }else{
        echo "error";
    }


}

?>