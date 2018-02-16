<?php
session_start();
require "../includes/credentials.php";


if(isset($_GET['user_id'])){

    $user_id = $_GET['user_id'];


    $sql = "SELECT food_id,date,time,name,amount_left,date_expire FROM food_details_donor WHERE user_id = {$user_id} AND is_available = 1;";
    $array = array();

    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result) > 0){

        while($row1 = mysqli_fetch_assoc($result)) {



            $array[] = array("food_id" => $row1['food_id'],
                "date" => $row1['date'],
                "time" => $row1['time'],
                "name" => $row1['name'],
                "amount_left" => $row1['amount_left'],"expire" => $row1['date_expire']);

        }


        echo json_encode($array);
    }
}




?>