<?php


require "../includes/credentials.php";


function insert_user_donor($conn,$id,$reg_id){

    $check = "SELECT gcm_regid FROM gcm_users_donor WHERE gcm_regid = '{$reg_id}';";
    $present = 0;

    $result2 = mysqli_query($conn,$check);
    if(mysqli_num_rows($result2) > 0){
        $present = 1;

    }

    if($present == 0){
        $result = mysqli_query($conn,"INSERT INTO gcm_users_donor(user_id, gcm_regid,created_at) VALUES('$id', '$reg_id', NOW())");

        if($result){

            echo "ok";
        }else{
            echo "error";
        }
    }else{
        echo "ok";
    }


}




function insert_user($conn,$id,$reg_id){

    $check = "SELECT gcm_regid FROM gcm_users WHERE gcm_regid = '{$reg_id}';";
    $present = 0;

    $result2 = mysqli_query($conn,$check);
    if(mysqli_num_rows($result2) > 0){
        $present = 1;

    }

    if($present == 0){
        $result = mysqli_query($conn,"INSERT INTO gcm_users(user_id, gcm_regid,created_at) VALUES('$id', '$reg_id', NOW())");

        if($result){

            echo "ok";
        }else{
            echo "error";
        }
    }else{
        echo "ok";
    }


}

function get_all_users($conn)
{
    $result = mysqli_query($conn,"select * FROM gcm_users");
    return $result;
}




if(isset($_GET['id']) && isset($_GET['regId'])){


    $id = $_GET["id"];
    $gcm_regid = $_GET["regId"];

    $res = insert_user($conn,$id,$gcm_regid);



}



if(isset($_GET['id']) && isset($_GET['regIdDonor'])){


    $id = $_GET["id"];
    $gcm_regid = $_GET["regIdDonor"];

    $res = insert_user_donor($conn,$id,$gcm_regid);



}











?>

