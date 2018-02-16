<?php

session_start();
require "../includes/credentials.php";


if(isset($_GET['user_id'])){




    $user_id = $_GET['user_id'];

    $food = "SELECT name,amount,food_id,amount_left,time,date,is_available,is_claimed,is_wasted,date_expire FROM food_details_donor WHERE user_id = {$user_id};";

    $result = mysqli_query($conn, $food);

    if (mysqli_num_rows($result) > 0) {

        $rows = array();

        while($row = mysqli_fetch_row($result)) {

            $status = "available";

            if($row[6] == 1){
                $status = "available";
            }elseif ($row[7] == 1){
                $status = "claimed";
            }else{
                $status = "wasted";
            }

            $rows[] = array("name" => $row[0],"amount" => $row[1],"food_id" =>  $row[2] ,"amount_left" =>  $row[3],
                "time" =>  $row[4],"date" =>  $row[5],"status" => $status,"expire" => $row[9]);


        }

        echo json_encode($rows);


    }

}else{
    echo "error";
}



?>