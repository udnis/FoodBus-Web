<?php
session_start();

require "includes/credentials.php";
require "includes/donor-functions.php";
require "includes/donor-admin-functions.php";
require "includes/helper_functions.php";

$username = $_SESSION['username'];
$password = $_SESSION['password'];
$user_id = $_SESSION['user_id'];

if(!isset($username)){
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/app-login.php");
    exit();
}



$credentials = get_detail($conn,$username,$password);

$donor_address = get_address($conn,$user_id);


$id = 0;
$name = "";
$amount = "";
$ia = 0;
$ic = 0;
$iw = 0;

if( isset($_GET['id']) && isset($_GET['name']) && isset($_GET['amount']) && isset($_GET['ia']) && isset($_GET['id']) && isset($_GET['iw'])){
    $id = $_GET['id'];
    $name = $_GET['name'];
    $amount = $_GET['amount'];
    $ia = $_GET['ia'];
    $ic = $_GET['ic'];
    $iw = $_GET['iw'];
}

if(isset($_POST['submit']) && !empty($_POST['id'])){

    remove_food($conn,$id);
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
    <div class="donor-profile-image" ></div>
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
    <h1 style="text-align: center">View Food Entry</h1> <br/><br/>
    <form action="" method="POST" class="delete-check">

        <?php

        $get_all_foods = "SELECT * FROM food_details_donor WHERE food_id = {$id}";
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
            echo "</tr>\n";

            while($row = mysqli_fetch_row($result)) {

                echo "<tr class='food-row-item'>\n";
                echo "<td class='food-row-item-in'>{$row[0]}</td>\n";
                echo "<td class='food-row-item-in'>{$row[4]}</td>\n";
                echo "<td class='food-row-item-in'>{$row[5]}</td>\n";
                echo "<td class='food-row-item-in'>{$row[6]}</td>\n";
                echo "<td class='food-row-item-in'>". get_local_date($row[3]) . "</td>\n";
                echo "<td class='food-row-item-in'>". get_local_time($row[2]) . "</td>\n";
                echo "<td class='food-row-item-in'>".check_bool($row[7],"available")."</td>\n";
                echo "<td class='food-row-item-in'>".check_bool($row[8],"claimed")."</td>\n";
                echo "<td class='food-row-item-in'>".check_bool($row[9],"wasted")."</td>\n";
                echo "</tr>\n";

            }

            echo "</table>\n";
            echo "</div>\n";

        }else{

        }

        ?>

        <br/>

        <?php echo "<h2>$name</h2>"; ?>
        <?php echo "<h2>$amount</h2>"; ?>


            <?php if($ia == 1):?>
                <h2 class="tell-status"> Available </h2>
            <?php elseif($ic == 1):?>
                <h2 class="tell-status"> Claimed </h2>
            <?php else: ?>
                <h2 class="tell-status"> Wasted </h2>
            <?php endif ?>

        <br/><br/>

        <input type="hidden" value="<?= $id ?>" name="id"/>
        <input type="submit" class="remove-button" name="submit" id="submit" value="Confirm Delete "/>
    </form>
</div>



</div>




</body>
</html>
