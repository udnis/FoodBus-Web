<?php


session_start();
require "../includes/credentials.php";

function get_time(){

    return date("g:i a");
}

function get_date(){
    return date('d-m-Y');
}

if(isset($_GET['user_id'])){

    $id = $_GET['user_id'];
    $name = $_GET['name'];
    $amount = $_GET['amount'];
    $expire = $_GET['expire'];

    add_food($conn,$id,$name,$amount,$expire);



}

function add_food($conn,$user_id,$name,$amount,$expire){

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{

        $time = get_time();
        $date = get_date();
        $expire_time = "72";

        if($expire == "6")  $expire_time = "6";
        if($expire == "12")   $expire_time = "12";
        if($expire == "24")     $expire_time = "24";
        if($expire == "48")     $expire_time = "48";
        if($expire == "72")     $expire_time = "72";



        $sql = "INSERT INTO food_details_donor (";
        $sql .= "user_id,time,date,name,amount,amount_left,is_available,is_claimed,is_wasted,date_expire) VALUES (";
        $sql .= " '{$user_id}' , TIME(CONVERT_TZ(NOW(),'-07:00','+05:30')) ,TIMESTAMP(CONVERT_TZ(NOW(),'-07:00','+05:30'))  , '{$name}', '{$amount}' , '{$amount}' , '1' , '0' , '0' , TIMESTAMP(ADDTIME(CONVERT_TZ(NOW(),'-07:00','+05:30'),'0 {$expire_time}:0:0')));";

        //CONVERT_TZ(NOW(),'-07:00','+05:30')

        $result = mysqli_query($conn, $sql);

        if ($result) {

            $d_name = "SELECT name FROM donor_address WHERE user_id={$user_id};";
            $r = mysqli_query($conn, $d_name);
            $d_name = "";
            while($row = mysqli_fetch_row($r)){
                $d_name = $row[0];
            }

            $food_details = array("food_name" => $name,
                                    "donor_name" => $d_name,
                                    "amount_left" => $amount);
            sendNoti($conn,$food_details);

            echo "ok";

        }else{

            echo "error";

        }
    }
}


?>