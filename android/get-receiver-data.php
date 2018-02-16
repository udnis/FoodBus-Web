<?php

session_start();
require "../includes/credentials.php";

if(isset($_GET['user_id'])){

    $make_wasted = "UPDATE food_details_donor SET is_wasted = 1,is_claimed=0,is_available = 0 WHERE TIMESTAMP((CONVERT_TZ(NOW(),'-07:00','+05:30'))) > date_expire AND is_wasted = 0";
    $w_result = mysqli_query($conn, $make_wasted);
    
    $select_foods = "SELECT food_id,name FROM food_details_donor WHERE is_wasted = 1";
    $food_result = mysqli_query($conn, $select_foods);
    
    if(mysqli_num_rows($food_result) > 0){
        while($row = mysqli_fetch_row($food_result)){
        
            $f_id = $row[0];
            $f_name = $row[1];
			
			$get_receivers = "SELECT user_id FROM food_details_receiver WHERE is_wasted = 0 AND food_id = {$f_id};";
			
			$receiver_list = mysqli_query($conn, $get_receivers);
			while($r_row = mysqli_fetch_row($receiver_list)){
				
				$demo = array("f_name" => $f_name,
                    			"d_name" => "Jeeva");
                    			
                		sendNotiReceive($conn,$demo,$r_row[0]);
			}

            $food_wasted = "UPDATE food_details_receiver SET is_wasted = 1 WHERE food_id = {$row[0]}";
            $waste_result = mysqli_query($conn, $food_wasted);
        }
    }

    $id = $_GET['user_id'];
    $name = "";
    $email = "";
    $latitude = "";
    $longitude = "";
    $door = "";
    $area = "";
    $street = "";
    $village = "";
    $city = "";
    $pin = "";
    $phone = "";
    $url = "";
    $rating = "";
    $times = "";

    $data_name = "SELECT name,latitude,longitude,door,street,village,city,pin,phone,area,profile_url,rating,times from receiver_address WHERE user_id = {$id} LIMIT 1;";
    $data_email = "SELECT email from receiver_credential WHERE user_id = {$id} LIMIT 1;";


    $result = mysqli_query($conn, $data_email);

    if (mysqli_num_rows($result) > 0) {

        while($row = mysqli_fetch_row($result)) {
            $email = $row[0];

        }

        $result = mysqli_query($conn, $data_name);

        if (mysqli_num_rows($result) > 0) {

            while($row = mysqli_fetch_row($result)) {
                $name = $row[0];
                $latitude = $row[1];
                $longitude = $row[2];
                $door = $row[3];
                $street = $row[4];
                $village = $row[5];
                $city = $row[6];
                $pin = $row[7];
                $phone = $row[8];
                $area = $row[9];
                $url = $row[10];
                $rating = $row[11];
                $times = $row[12];
            }
        }

        $arr = array("name" => $name ,"email" => $email,"latitude" => $latitude , "longitude" => $longitude ,
            "area" => $area ,"door" => $door ,"street" => $street ,"village" => $village ,"city" => $city ,
            "pin" => $pin ,"phone" => $phone ,"profile" => $url,"rating" => $rating,"times" => $times );
        $json = json_encode($arr);

        echo $json;
    }

    else{

        echo "fail";
    }




}








?>