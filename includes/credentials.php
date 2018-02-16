<?php

//CONVERT_TZ(NOW(),'-07:00','+05:30')


$mysql_servername = "localhost";
$mysql_username = "root";
$mysql_password = "";
$mysql_database = "foodbus";
$ip = "foodbusapp.com";

$conn = mysqli_connect($mysql_servername, $mysql_username, $mysql_password,$mysql_database);

date_default_timezone_set("Asia/Kolkata");


define("GOOGLE_API_KEY", "AIzaSyDKWh-UGUN2jRYLyFpcILXMqcrINxsgSaI");

function sendNoti($conn,$data){

    $ids = "SELECT gcm_regid FROM gcm_users;";


    $result = mysqli_query($conn,$ids);

    $rows = array();

    if(mysqli_num_rows($result) > 0){

        while($row = mysqli_fetch_row($result)){
            $rows[] = $row[0];
        }
    }



    $total = array( "data" => $data, "registration_ids" => $rows);



    $url = "https://android.googleapis.com/gcm/send";



    $headers = array('Authorization: key=' . GOOGLE_API_KEY,'Content-Type: application/json');

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($total));

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    // Close connection
    curl_close($ch);

    return true;


}

function sendNotiAccepted($conn,$data,$receiver_id){

    $ids = "SELECT gcm_regid FROM gcm_users WHERE user_id = {$receiver_id};";


    $result = mysqli_query($conn,$ids);

    $rows = array();

    if(mysqli_num_rows($result) > 0){

        while($row = mysqli_fetch_row($result)){
            $rows[] = $row[0];
        }
    }



    $total = array( "data" => $data, "registration_ids" => $rows);



    $url = "https://android.googleapis.com/gcm/send";



    $headers = array('Authorization: key=' . GOOGLE_API_KEY,'Content-Type: application/json');

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($total));

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    // Close connection
    curl_close($ch);

    return true;


}

function sendNotiRejected($conn,$data,$receiver_id){

    $ids = "SELECT gcm_regid FROM gcm_users WHERE user_id = {$receiver_id};";


    $result = mysqli_query($conn,$ids);

    $rows = array();

    if(mysqli_num_rows($result) > 0){

        while($row = mysqli_fetch_row($result)){
            $rows[] = $row[0];
        }
    }



    $total = array( "data" => $data, "registration_ids" => $rows);



    $url = "https://android.googleapis.com/gcm/send";



    $headers = array('Authorization: key=' . GOOGLE_API_KEY,'Content-Type: application/json');

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($total));

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    // Close connection
    curl_close($ch);

    return true;


}

function sendNotiReceive($conn,$data,$id){

    $ids = "SELECT DISTINCT(gcm_regid) FROM gcm_users WHERE user_id={$id};";


    $result = mysqli_query($conn,$ids);

    $rows = array();

    if(mysqli_num_rows($result) > 0){

        while($row = mysqli_fetch_row($result)){
            $rows[] = $row[0];
        }
    }



    $total = array( "data" => $data, "registration_ids" => $rows);
    



    $url = "https://android.googleapis.com/gcm/send";



    $headers = array('Authorization: key=' . GOOGLE_API_KEY,'Content-Type: application/json');

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($total));

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    // Close connection
    curl_close($ch);

    return true;


}



function sendNotiDonor($conn,$data,$id){

    $ids = "SELECT gcm_regid FROM gcm_users_donor WHERE user_id={$id};";


    $result = mysqli_query($conn,$ids);

    $rows = array();

    if(mysqli_num_rows($result) > 0){

        while($row = mysqli_fetch_row($result)){
            $rows[] = $row[0];
        }
    }

    $total = array( "data" => $data, "registration_ids" => $rows);



    $url = "https://android.googleapis.com/gcm/send";



    $headers = array('Authorization: key=' . GOOGLE_API_KEY,'Content-Type: application/json');

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($total));

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    // Close connection
    curl_close($ch);




}

function sendNotiReceiver($conn,$data,$id){

    $ids = "SELECT gcm_regid FROM gcm_users WHERE user_id={$id};";


    $result = mysqli_query($conn,$ids);

    $rows = array();

    if(mysqli_num_rows($result) > 0){

        while($row = mysqli_fetch_row($result)){
            $rows[] = $row[0];
        }
    }

    $total = array( "data" => $data, "registration_ids" => $rows);
    echo json_encode($total)."<br/><br/><br/>";


    $url = "https://android.googleapis.com/gcm/send";



    $headers = array('Authorization: key=' . GOOGLE_API_KEY,'Content-Type: application/json');

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($total));

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    // Close connection
    curl_close($ch);
    echo "<br/><br/><br/>". $result;


}


function sendNotiWaste($conn,$data,$id){

    $ids = "SELECT gcm_regid FROM gcm_users WHERE user_id={$id};";


    $result = mysqli_query($conn,$ids);

    $rows = array();

    if(mysqli_num_rows($result) > 0){

        while($row = mysqli_fetch_row($result)){
            $rows[] = $row[0];
        }
    }

    $total = array( "data" => $data, "registration_ids" => $rows);


    $url = "https://android.googleapis.com/gcm/send";



    $headers = array('Authorization: key=' . GOOGLE_API_KEY,'Content-Type: application/json');

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);


    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($total));

    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }

    // Close connection
    curl_close($ch);
    echo "<br/>". $result;


}


?>