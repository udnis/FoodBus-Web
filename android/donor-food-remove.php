<?php


session_start();
require "../includes/credentials.php";



if(isset($_GET['food_id'])){

    $foodid = $_GET['food_id'];
    $user_id = $_GET['user_id'];

    remove_food($conn,$foodid,$user_id);



}

function remove_food($conn,$food_id,$user_id){

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{

        $sql = "DELETE FROM food_details_donor WHERE food_id = {$food_id} AND user_id = {$user_id}; ";


        $result = mysqli_query($conn, $sql);

        if ($result) {

            echo "ok";

        }else{
            echo "error";
        }
    }
}


?>