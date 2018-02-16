<?php
session_start();

require "includes/credentials.php";
require "includes/receiver-functions.php";
require "includes/donor-admin-functions.php";
require "includes/helper_functions.php";

$username = $_SESSION['username'];
$password = $_SESSION['password'];
$user_id = $_SESSION['user_id'];
$type = $_SESSION['type'];

if(!isset($username)){
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/app-login.php");
    exit();
}

if($type == "donor"){
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/donor-admin.php");
    exit();
}

$credentials = get_detail_receiver($conn,$username,$password);

$receiver_address = get_address_receiver($conn,$user_id);

$imaglink = "SELECT profile_url FROM receiver_address WHERE user_id = {$user_id} LIMIT 1";

$result = mysqli_query($conn,$imaglink);
if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_row($result)) {

        $profile_id = $row[0];
    }
}else{
    echo "Error";
}


if(isset($_GET['rkioid']) && isset($_GET['rkiois'])){

    $receiver_complete = 0;
    $check_receiver_complete = "SELECT donor_complete FROM food_details_receiver WHERE id = {$_GET['rkioid']};";
    echo $check_receiver_complete;
    $tmp_res = mysqli_query($conn,$check_receiver_complete);
    while($row = mysqli_fetch_row($tmp_res)){
        if($row[0] == 1){
            $receiver_complete = 1;
            
        }else{
            $receiver_complete = 0;
        }
    }

    $donor_complete = "UPDATE food_details_receiver SET receiver_complete = 1 WHERE id = {$_GET['rkioid']};";
    $res = mysqli_query($conn,$donor_complete);
    if($res){

        if($receiver_complete == 1){

            

            $delete = "DELETE FROM food_details_receiver WHERE id = {$_GET['rkioid']}";

           
            $res1 = mysqli_query($conn,$delete);
            if($res1){
                echo "OK";
            }
        }

    }
}



?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main</title>
    <link href="styles/style.css" rel="stylesheet"/>
    <link href='https://fonts.googleapis.com/css?family=Neucha' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script>
        $(document).ready(function(){

            $("#request-complete-button").click(function(){

                $("#request-complete-content").css("display","block");
                $("#food-detail-receiver-content").css("display","none");
            });

            $("#request-button").click(function(){

                $("#request-complete-content").css("display","none");
                $("#food-detail-receiver-content").css("display","block");
            });


        });
    </script>
</head>
<body>


<div class="con1 main-header">
    <ul>
        <a href="donor-home.php"><li>Home</li></a>
        <a href="donor-profile.php"><li>Profile</li></a>
        <a href="donor-admin.php"><li>Admin</li></a>
        <a href="donor-logout.php"><li>Logout</li></a>
    </ul>
</div>

<div class="donor-header">
    <div class="donor-profile-image" style="background-image:url('<?= $profile_id ?>');background-size: cover;" ></div>
    <div class="donor-details" >
        <h1 class="donor-name"><?= $receiver_address[0] ?></h1>
        <h4 class="donor-address"> <?= "#" . $receiver_address[1] .", " . $receiver_address[2] .", <br/> " . $receiver_address[3] .", <br/> " . $receiver_address[4] .", <br/> " .
            $receiver_address[5] .", <br/> " .$receiver_address[6]?></h4>
    </div>
    <div class="donor-phone" >
        <h4 class="donor-phone-text"><?= "Call us <br/>" . $receiver_address[9] ?></h4>
    </div>
</div>

    <div class="donor-food-request con1">

    <div class="request-food-change">
    <div class="request-button" id="request-button">View Requests</div>
    <div class="food-button" id="request-complete-button">Finish Request</div>
    </div>


    <div class="food-detail-receiver-content" id="food-detail-receiver-content">



        <h1> Food Requests Made </h1> <br/>

        <?php

        $get_all_foods = "SELECT food_id,name,amount_claimed,date,time,donor_id,claim_request,approved,donor_id,declined,amount_got,id FROM food_details_receiver WHERE user_id = {$user_id} ORDER BY time DESC;";

        $result = mysqli_query($conn, $get_all_foods);

        if (mysqli_num_rows($result) > 0) {

            echo "<div class='donor-food-item'>\n";
            echo "<table class='food-item-table'>\n";
            echo "<tr class='food-row-item-head'>\n";
            echo "<td class='food-row-item-in-head'>To</td>\n";
            echo "<td class='food-row-item-in-head'>Food ID</td>\n";
            echo "<td class='food-row-item-in-head'>Request ID</td>\n";
            echo "<td class='food-row-item-in-head'>Name</td>\n";
            echo "<td class='food-row-item-in-head'>Amount Claimed</td>\n";
            echo "<td class='food-row-item-in-head'>Amount Got</td>\n";
            echo "<td class='food-row-item-in-head'>Date</td>\n";
            echo "<td class='food-row-item-in-head'>Time</td>\n";
            echo "<td class='food-row-item-in-head'>Requested</td>\n";
            echo "<td class='food-row-item-in-head'>Approved</td>\n";
            echo "<td class='food-row-item-in-head'>Rejected</td>\n";
            echo "<td class='food-row-item-in-head'>Edit</td>\n";
            echo "</tr>\n";



            while($row = mysqli_fetch_row($result)) {

                echo "<tr class='food-row-item'>\n";
                echo "<td class='food-row-item-in'><a href='receiver-check.php?id={$row[8]}' class='food-item-edit'>{$row[8]}</a></td>\n";
                echo "<td class='food-row-item-in'>{$row[0]}</td>\n";
                echo "<td class='food-row-item-in'>{$row[11]}</td>\n";
                echo "<td class='food-row-item-in'>{$row[1]}</td>\n";
                echo "<td class='food-row-item-in'>{$row[2]}</td>\n";
                echo "<td class='food-row-item-in'>{$row[10]}</td>\n";
                echo "<td class='food-row-item-in'>".get_local_date($row[3])."</td>\n";
                echo "<td class='food-row-item-in'>".get_local_time($row[4])."</td>\n";
                echo "<td class='food-row-item-in'>".check_bool_simple($row[6],'request')."</td>\n";
                echo "<td class='food-row-item-in'>".check_bool_simple($row[7],'approve')."</td>\n";
                echo "<td class='food-row-item-in'>".check_bool_simple($row[9],'approve')."</td>\n";
                echo "<td class='food-row-item-in'><a href='receiver-edit-request.php?fkioid={$row[0]}&ukioid={$user_id}&dkioid={$row[8]}&rkioid={$row[11]}'' class='food-item-edit'>Edit</a></td>\n";
                echo "</tr>\n";

            }

            echo "</table>\n";
            echo "</div>\n";

        }else{
            echo "<div class='request-no-item'>";
            echo "<p class='request-no-inner'>No requests are made</p>";
            echo "</div>";
        }

        ?>

    </div>

    <br/>

    <div class="request-complete-content" id="request-complete-content">

    <?php

    $get_all_foods1 = "SELECT * FROM food_details_receiver WHERE user_id = {$user_id} AND approved = 1 AND receiver_complete = 0 ORDER BY time DESC";
    $result1 = mysqli_query($conn, $get_all_foods1);

    if (mysqli_num_rows($result1) > 0) {



        echo "<div class='donor-food-item'>\n";
        echo "<table class='food-item-table'>\n";
        echo "<tr class='food-row-item-head'>\n";
        echo "<td class='food-row-item-in-head'>Food ID</td>\n";
        echo "<td class='food-row-item-in-head'>Food Name</td>\n";
        echo "<td class='food-row-item-in-head'>Receiver Name</td>\n";
        echo "<td class='food-row-item-in-head'>Amount Claimed</td>\n";
        echo "<td class='food-row-item-in-head'>Amount Got</td>\n";
        echo "<td class='food-row-item-in-head'>Date</td>\n";
        echo "<td class='food-row-item-in-head'>Time</td>\n";
        echo "<td class='food-row-item-in-head'>Edit</td>\n";
        echo "</tr>\n";

        while($row = mysqli_fetch_row($result1)) {

            $r_n = "SELECT name FROM donor_address WHERE user_id={$row[7]};";
            $result2 = mysqli_query($conn, $r_n);
            $n = "";
            while($rw = mysqli_fetch_row($result2)){
                $n = $rw[0];
            }

            echo "<tr class='food-row-item'>\n";
            echo "<td class='food-row-item-in'>{$row[1]}</td>\n";
            echo "<td class='food-row-item-in'>{$row[5]}</td>\n";
            echo "<td class='food-row-item-in'>{$n}</td>\n";
            echo "<td class='food-row-item-in'>{$row[6]}</td>\n";
            echo "<td class='food-row-item-in'>{$row[11]}</td>\n";
            echo "<td class='food-row-item-in'>".get_local_date($row[4])."</td>\n";
            echo "<td class='food-row-item-in'>".get_local_time($row[3])."</td>\n";
            echo "<td class='food-row-item-in'><a href='receiver-admin.php?rkioid={$row[0]}&rkiois={$row[0]}' class='food-item-edit'>Complete</a></td>\n";
            echo "</tr>\n";

        }

        echo "</table>\n";
        echo "</div>\n";

    }else{
        echo "<div class='request-no-item'>";
        echo "<p class='request-no-inner'>No requests to finish</p>";
        echo "</div>";
    }


    ?>

    </div>
        </div>


<div class="main-footer">
    <ul>
        <a href="index.html"><li>Foodbus Home</li></a>
        <a href="index.html#what"><li>What is Foodbus?</li></a>
        <a href="index.html#why"><li>Why Foodbus?</li></a>
        <a href="index.html#media"><li>Foodbus Media</li></a>
        <a href="index.html#contact"><li>Contact Foodbus</li></a>
    </ul>
</div>



</body>
</html>
