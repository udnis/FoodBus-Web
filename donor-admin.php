<?php
session_start();

require "includes/credentials.php";
require "includes/donor-functions.php";
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

if($type == "receiver"){
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/receiver-admin.php");
    exit();
}

$credentials = get_detail($conn,$username,$password);

$donor_address = get_address($conn,$user_id);

if(isset($_POST['submit']) && !empty($_POST['name']) && !empty($_POST['amount']) && !empty($_POST['expire'])){

    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $expire = $_POST['expire'];

    add_food($conn,$user_id,$name,$amount,$expire);
}

if(isset($_GET['skioid']) && isset($_GET['fkioid']) && isset($_GET['tkioid'])){

    $receiver_id = $_GET['skioid'];
    $food_id = $_GET['fkioid'];
    $type = $_GET['tkioid'];
    $request_id = $_GET['rkioid'];
    $food_amount = $_GET['amount'];

    if($type == "accept"){
        echo "Accept";
        accept_request($conn,$receiver_id,$food_id,$food_amount,$request_id);
    }else{
        reject_request($conn,$receiver_id,$food_id,$food_amount,$request_id);
    }

}

if(isset($_GET['rkioid']) && isset($_GET['rkiois'])){


    $receiver_complete = 0;
    $check_receiver_complete = "SELECT receiver_complete FROM food_details_receiver WHERE id = {$_GET['rkioid']};";
    echo $check_receiver_complete;
    echo $check_receiver_complete;
    $tmp_res = mysqli_query($conn,$check_receiver_complete);
    while($row = mysqli_fetch_row($tmp_res)){
        if($row[0] == 1){
            $receiver_complete = 1;
            
        }else{
            $receiver_complete = 0;
        }
    }

    $donor_complete = "UPDATE food_details_receiver SET donor_complete = 1 WHERE id = {$_GET['rkioid']};";
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

$imaglink = "SELECT profile_url FROM donor_address WHERE user_id = {$user_id} LIMIT 1";

$result = mysqli_query($conn,$imaglink);
if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_row($result)) {

        $profile_id = $row[0];
    }
}else{
    echo "Error";
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

            $("#food-button").click(function(){

                $("#request-complete-content").css("display","none");
                $("#food-request-content").css("display","none");
                $("#food-detail-content").css("display","inline-block");
            });

            $("#request-button").click(function(){

                $("#food-detail-content").css("display","none");
                $("#food-request-content").css("display","block");
                $("#request-complete-content").css("display","none");
            });

            $("#request-complete-button").click(function(){

                $("#food-detail-content").css("display","none");
                $("#food-request-content").css("display","none");
                $("#request-complete-content").css("display","block");
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
        <h1 class="donor-name"><?= $donor_address[0] ?></h1>
        <h4 class="donor-address"> <?= "#" . $donor_address[1] .", " . $donor_address[2] .", <br/> " . $donor_address[3] .", <br/> " . $donor_address[4] .", <br/> " .
            $donor_address[5] .", <br/> " .$donor_address[6]?></h4>
    </div>
    <div class="donor-phone" >
        <h4 class="donor-phone-text"><?= "Call us <br/>" . $donor_address[9] ?></h4>
    </div>
</div>



<div class="add-food con1">
    <h1 style="text-align: center">Add Food Entry</h1> <br/><br/>
    <form action="" method="POST" class="food-add-form con2-5">

        <label for="foodname" class="food-add-label">Enter Food Name</label> &nbsp; &nbsp; &nbsp;
        <input type="text" name="name" class="food-add-entry" /> <br/><br/>
        <label for="amount" class="food-add-label">Enter Amount</label> &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
        <input type="number" name="amount" class="food-add-entry" /> <br/><br/>

        <label for="status" class="food-add-label">Select Expiry</label> &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
        <select class="food-add-select" name="expire">
            <option class="food-add-option" value="3">3 Hours</option>
            <option class="food-add-option" value="6">6 Hours</option>
            <option class="food-add-option" value="12">12 hours</option>
            <option class="food-add-option" value="24">1 Day</option>
            <option class="food-add-option" value="48">2 Days</option>
            <option class="food-add-option" value="72">3 Days</option>
        </select>

        <br/><br/>

        <input type="submit" class="food-add-submit" name="submit" id="submit" value="Add"/>
    </form>
</div>



<div class="donor-food-request con1">

    <div class="request-food-change">
        <div class="request-button" id="request-button">View Requests</div>
        <div class="food-button" id="food-button">View Food Details</div>
        <div class="food-button" id="request-complete-button">Finish Request</div>
    </div>

    <br>

    <div class="request-complete-content" id="request-complete-content">

        <?php

        $get_all_foods1 = "SELECT * FROM food_details_receiver WHERE donor_id = {$user_id} AND approved = 1 AND donor_complete = 0 ORDER BY time DESC";
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

                $r_n = "SELECT name FROM receiver_address WHERE user_id={$row[2]};";
                $result2 = mysqli_query($conn, $r_n);
                $n = "";
                while($rw = mysqli_fetch_row($result2)){
                    $n = $rw[0];
                }

                echo "<tr class='food-row-item'>\n";
                echo "<td class='food-row-item-in'>{$row[1]}</td>\n";
                echo "<td class='food-row-item-in'>{$row[5]}</td>\n";
                echo "<td class='food-row-item-in'>{$row[6]}</td>\n";
                echo "<td class='food-row-item-in'>{$n}</td>\n";
                echo "<td class='food-row-item-in'>{$row[11]}</td>\n";
                echo "<td class='food-row-item-in'>".get_local_date($row[4])."</td>\n";
                echo "<td class='food-row-item-in'>".get_local_time($row[3])."</td>\n";
                echo "<td class='food-row-item-in'><a href='donor-admin.php?rkioid={$row[0]}&rkiois={$row[0]}' class='food-item-edit'>Complete</a></td>\n";
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

    <div class="food-request-content" id="food-request-content">

        <?php

        $total_amount = 0;

        $get_requested_foods = "SELECT user_id,food_id,name,amount_claimed,id FROM food_details_receiver WHERE claim_request = 1 AND donor_id = {$user_id} AND approved = 0;";




        $result = mysqli_query($conn, $get_requested_foods);

        if (mysqli_num_rows($result) > 0) {

            $f_id = 0;



            while($food_row = mysqli_fetch_row($result)) {

                $f_id = $food_row[1];

                $total_food = "SELECT amount_left from food_details_donor WHERE food_id = {$f_id}";

                $r = mysqli_query($conn, $total_food);
                if (mysqli_num_rows($r) > 0) {

                    while($donor_row = mysqli_fetch_row($r)) {

                        $total_amount = $donor_row[0];
                    }
                }

                echo "<div class='request-main'>";

                    $get_receiver_details = "SELECT user_id,name,door,street,area,village,city,pin,phone FROM receiver_address WHERE user_id = {$food_row[0]};";



                    $result2 = mysqli_query($conn, $get_receiver_details);

                if (mysqli_num_rows($result) > 0) {

                    while($receiver_row = mysqli_fetch_row($result2)) {


                        echo "<div class='request-item'>";
                        echo "<div class='request-item-in'><br/>";

                        echo "<a href='#' class='claim-check-donor-link' class='card-name'>{$receiver_row[1]}</a>";

                        echo "<br/><br/>";

                        echo "<p class='card-detail'>User ID : {$receiver_row[0]}</p>";
                        echo "<p class='card-detail'>Food ID : {$food_row[1]}</p>";
                        echo "<p class='card-detail'>Request ID : {$food_row[4]}</p>";
                        echo "<p class='card-detail'>Food Name : {$food_row[2]}</p>";
                        echo "<p class='card-detail'>Amount Left : {$total_amount}</p>";
                        echo "<p class='card-detail'>Claimed Amount : {$food_row[3]}</p>";
                        echo "<p class='card-detail'>#{$receiver_row[2]} , " . "{$receiver_row[3]}" . "</p>";
                        echo "<p class='card-detail'>{$receiver_row[4]}, ". "</p>";
                        echo "<p class='card-detail'>{$receiver_row[5]}, ". "</p>";
                        echo "<p class='card-detail'>{$receiver_row[6]}, ". "</p>";

                        echo "<br/>";

                        echo "<a href='donor-admin.php?skioid={$receiver_row[0]}&fkioid={$food_row[1]}&tkioid=accept&rkioid={$food_row[4]}&amount={$food_row[3]}' class='request-accept-button'>Accept</a>";
                        echo "<a href='donor-admin.php?skioid={$receiver_row[0]}&fkioid={$food_row[1]}&tkioid=reject&rkioid={$food_row[4]}&amount={$food_row[3]}' class='request-reject-button'>Reject</a>";

                        echo "<br/><br/>";

                        echo "</div>";
                        echo "</div>";
                    }
                }


                echo "</div>";

            }

        }else{
            echo "<div class='request-no-item'>";
            echo "<p class='request-no-inner'>No requests to view</p>";
            echo "</div>";
        }

        ?>

    </div>

    <div class="food-detail-content" id="food-detail-content">

        <?php


            $get_all_foods = "SELECT * FROM food_details_donor WHERE user_id = $user_id ORDER BY time DESC";
            $result = mysqli_query($conn, $get_all_foods);

            if (mysqli_num_rows($result) > 0) {

                echo "<div class='donor-food-item'>\n";
                echo "<table class='food-item-table'>\n";
                echo "<tr class='food-row-item-head'>\n";
                echo "<td class='food-row-item-in-head'>ID</td>\n";
                echo "<td class='food-row-item-in-head'>Name</td>\n";
                echo "<td class='food-row-item-in-head'>Amount</td>\n";
                echo "<td class='food-row-item-in-head'>Left</td>\n";
                echo "<td class='food-row-item-in-head'>Date</td>\n";
                echo "<td class='food-row-item-in-head'>Time</td>\n";
                echo "<td class='food-row-item-in-head'>Available</td>\n";
                echo "<td class='food-row-item-in-head'>Claimed</td>\n";
                echo "<td class='food-row-item-in-head'>Wasted</td>\n";
                echo "<td class='food-row-item-in-head'>Edit</td>\n";
                echo "</tr>\n";

                while($row = mysqli_fetch_row($result)) {

                    echo "<tr class='food-row-item'>\n";
                    echo "<td class='food-row-item-in'>{$row[0]}</td>\n";
                    echo "<td class='food-row-item-in'>{$row[4]}</td>\n";
                    echo "<td class='food-row-item-in'>{$row[5]}</td>\n";
                    echo "<td class='food-row-item-in'>{$row[6]}</td>\n";
                    echo "<td class='food-row-item-in'>".get_local_date($row[3])."</td>\n";
                    echo "<td class='food-row-item-in'>".get_local_time($row[2])."</td>\n";
                    echo "<td class='food-row-item-in'>".check_bool($row[7],"available")."</td>\n";
                    echo "<td class='food-row-item-in'>".check_bool($row[8],"claimed")."</td>\n";
                    echo "<td class='food-row-item-in'>".check_bool($row[9],"wasted")."</td>\n";
                    echo "<td class='food-row-item-in'><a href='donor-food-edit.php?id={$row[0]}&name={$row[4]}&amount={$row[5]}&ia={$row[7]}&ic={$row[8]}&iw={$row[9]}' class='food-item-edit'>Edit</a></td>\n";
                    echo "</tr>\n";

                }

                echo "</table>\n";
                echo "</div>\n";

            }else{
                echo "<div class='request-no-item'>";
                echo "<p class='request-no-inner'>No Food Items Added</p>";
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
