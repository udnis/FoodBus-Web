<?php
session_start();

require "includes/credentials.php";
require "includes/donor-functions.php";
require "includes/donor-admin-functions.php";

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
$expire = "";

if( isset($_GET['id']) && isset($_GET['name']) && isset($_GET['amount']) && isset($_GET['ia']) && isset($_GET['id']) && isset($_GET['iw'])){
    $id = $_GET['id'];
    $name = $_GET['name'];
    $amount = $_GET['amount'];
    $ia = $_GET['ia'];
    $ic = $_GET['ic'];
    $iw = $_GET['iw'];

}


if(isset($_POST['submit']) && !empty($_POST['name']) && !empty($_POST['amount']) && !empty($_POST['status'])){
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];
    $expire = $_POST['expire'];

    edit_food($conn,$id,$name,$amount,$status,$expire);
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
    <h1 style="text-align: center">Edit Food Entry</h1> <br/><br/>
    <form action="" method="POST" class="food-add-form con2-5">

        <label for="foodname" class="food-add-label">Edit Food Name</label> &nbsp; &nbsp; &nbsp;
        <input type="text" name="name" class="food-add-entry" value="<?= $name ?>"/> <br/><br/>
        <label for="food_name" class="food-add-label">Edit Amount</label> &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
        <input type="number" name="amount" class="food-add-entry" value="<?= $amount ?>"> <br/><br/>

        <label for="status" class="food-add-label">Edit Status</label> &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
        <select class="food-add-select" name="status">

            <?php if($ia == 1):?>
                <option class="food-add-option" value="available" selected>Available</option>
                <option class="food-add-option" value="claimed">Claimed</option>
                <option class="food-add-option" value="wasted">Wasted</option>
            <?php elseif($ic == 1):?>
                <option class="food-add-option" value="available">Available</option>
                <option class="food-add-option" value="claimed" selected>Claimed</option>
                <option class="food-add-option" value="wasted">Wasted</option>
            <?php else: ?>
                <option class="food-add-option" value="available">Available</option>
                <option class="food-add-option" value="claimed">Claimed</option>
                <option class="food-add-option" value="wasted" selected>Wasted</option>
            <?php endif ?>
        </select>

        <br/><br/>

        <label for="status" class="food-add-label">Select Expiry</label> &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;
        <select class="food-expire-select" name="expire">
            <option class="food-add-option" value="3">3 Hours</option>
            <option class="food-add-option" value="6">6 Hours</option>
            <option class="food-add-option" value="12">12 hours</option>
            <option class="food-add-option" value="24">1 Day</option>
            <option class="food-add-option" value="48">2 Days</option>
            <option class="food-add-option" value="72">3 Days</option>
        </select>

        <br/><br/>

        <input type="submit" class="food-add-submit" name="submit" id="submit" value="Save"/>
    </form>
</div>



<div class="donor-food-request con1" style="text-align: center">

    <div class="request-food-change" style="text-align: center;">
        <a class="request-button" href="<?php echo "http://{$GLOBALS['ip']}/FoodWebApp/app-login.php" ?>">Goto Home</a>
        <a class="remove-button" href="donor-food-remove.php?id=<?= $id ?>&name=<?= $name ?>&amount=<?= $amount ?>&ia=<?= $ia ?>&ic=<?= $ic ?>&iw=<?= $iw ?>">Remove Food</a>
    </div>


        <?php

        $get_all_foods = "SELECT * FROM food_details_donor ORDER BY time DESC";
        $result = mysqli_query($conn, $get_all_foods);

        ?>

    </div>

</div>




</body>
</html>
