<?php
session_start();

require "includes/credentials.php";
require "includes/donor-functions.php";
require "includes/receiver-functions.php";
require "includes/donor-admin-functions.php";

$username = $_SESSION['username'];
$password = $_SESSION['password'];
$user_id = $_SESSION['user_id'];

if(!isset($username)){
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/app-login.php");
    exit();
}

$credentials = get_detail_receiver($conn,$username,$password);

$receiver_address = get_address_receiver($conn,$user_id);

$receiver_id = "";
$food_id = "";
$donor_id = "";
$claimed = "";
$approved = "";
$amount = "";

if(isset($_GET['ukioid']) && isset($_GET['fkioid']) && isset($_GET['dkioid']) && isset($_GET['rkioid'])){

    $receiver_id = $_GET['ukioid'];
    $food_id = $_GET['fkioid'];
    $donor_id = $_GET['dkioid'];
    $request_id = $_GET['rkioid'];

}elseif(isset($_POST['ukioid']) && isset($_POST['fkioid']) && isset($_POST['dkioid']) && isset($_POST['rkioid'])){

    $receiver_id = $_POST['ukioid'];
    $food_id = $_POST['fkioid'];
    $donor_id = $_POST['dkioid'];
    $claimed =  $_POST['claimed'];
    $approved =  $_POST['approved'];
    $amount =  $_POST['amount'];
    $request_id = $_POST['rkioid'];



    
    remove_request($conn,$user_id,$food_id,$donor_id,$claimed,$approved,$amount,$request_id);
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/receiver-admin.php");
    exit();

}else{
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/receiver-admin.php");
    exit();
}

$imaglink = "SELECT profile_url FROM receiver_address WHERE user_id = {$receiver_id} LIMIT 1";

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




<div class="add-food con1">
    <h1 style="text-align: center">Remove Food Request</h1> <br/><br/>
    <form action="receiver-edit-request.php" method="POST" class="food-add-form con2-5">

        <?php



        $food_details = "SELECT food_id,time,date,name,amount_claimed,donor_id,claim_request,approved,amount_got,id FROM food_details_receiver WHERE user_id={$receiver_id} AND food_id = {$food_id} LIMIT 1;";



        $result = mysqli_query($conn, $food_details);

        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_row($result)) {

                $donor_address = get_address($conn,$row[5]);

                echo "<div class='request-item-remove'>";
                echo "<div class='request-item-in'><br/>";

                echo "<a href='#' class='claim-check-donor-link' class='card-name'>{$donor_address[0]}</a>";

                echo "<br/><br/>";

                echo "<p class='card-detail'>Food ID : {$row[0]}</p>";
                echo "<p class='card-detail'>Food Name : {$row[3]}</p>";
                echo "<p class='card-detail'>Claimed Amount : {$row[4]}</p>";
                echo "<p class='card-detail'>Got Amount : {$row[8]}</p>";
                echo "<p class='card-detail'>Food Name : {$row[3]}</p>";



                echo "</div>";
                echo "</div>";

                echo '<input type="hidden" value="'. $user_id .'" name="ukioid"/>';
                echo '<input type="hidden" value="'. $row[0] .'" name="fkioid"/>';
                echo '<input type="hidden" value="'. $row[5] .'" name="dkioid"/>';
                echo '<input type="hidden" value="'. $row[6] .'" name="claimed"/>';
                echo '<input type="hidden" value="'. $row[7] .'" name="approved"/>';
                echo '<input type="hidden" value="'. $row[8] .'" name="amount"/>';
                echo '<input type="hidden" value="'. $row[9] .'" name="rkioid"/>';


            }
        }



        ?>



        <br/><br/>
        <input type="submit" class="remove-request" name="submit" id="submit" value="Remove"/>
    </form>
</div>





</body>
</html>
