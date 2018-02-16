<?php

session_start();
require "../includes/credentials.php";

if(isset($_GET['user_id'])){

    $id = $_GET['user_id'];
    
    $remove = "DELETE FROM gcm_users_donor WHERE gcm_regid = '{$id}';";
    $result = mysqli_query($conn, $remove );
    
    if($result){
    	echo "ok";
    }else{
    	echo "error";
    }
    
}


if(isset($_GET['user_rec_id'])){

    $id = $_GET['user_rec_id'];
    
    $remove = "DELETE FROM gcm_users WHERE gcm_regid = '{$id}';";
    $result = mysqli_query($conn, $remove );
    
    if($result){
    	echo "ok";
    }else{
    	echo "error";
    }
    
}
