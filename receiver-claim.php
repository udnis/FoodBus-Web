<?php
session_start();

require "includes/credentials.php";
require "includes/helper_functions.php";
require "includes/donor-functions.php";
require "includes/receiver-functions.php";
require "includes/donor-admin-functions.php";

$username = $_SESSION['username'];
$password = $_SESSION['password'];
$user_id = $_SESSION['user_id'];
$type = $_SESSION['type'];

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $food_id = $_GET['food_id'];
}else{
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/receiver-home.php");
}

if(!isset($username)){
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/app-login.php");
    exit();
}

if($type == "donor"){
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/donor-home.php");
    exit();
}

$credentials = get_detail($conn,$username,$password);

$receiver_address = get_address($conn,$id);

$food_details = get_food_details($conn,$food_id);


if(isset($_POST['submit'])){

    $claim_amount = $_POST['claim'];
    $donor_id = $_POST['donor'];
    $name = $_POST['name'];

    claim_food($conn,$food_id,$user_id,$claim_amount,$donor_id,$name);

}

$imaglink = "SELECT profile_url FROM donor_address WHERE user_id = {$id} LIMIT 1";

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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABrj0AaxsM7ZgoqngH5q376c6AUKMU_EI&libraries=places"></script>
    <script>

        $(document).ready(function(){


        });


        function initMap() {

            var address = "<h2 class='map-marker-head'><?= $receiver_address[4] ?></h2>";
            address += "<p class='map-marker-address'><?php echo $receiver_address[1].' ,'.$receiver_address[2].', <br/>' ?>";
            address += "<?php echo $receiver_address[3].' ,<br/>'.$receiver_address[4].' <br/>'.$receiver_address[5].' <br/>'.$receiver_address[6] ?></p>";
            address += "<h3 class='map-marker-phone'><?php echo $receiver_address[9] ?></h3>";


            var myLatLng = {lat: <?= $receiver_address[7] ?>, lng: <?= $receiver_address[8] ?>};


            var map = new google.maps.Map(document.getElementById('map'), {
                center: myLatLng,
                scrollwheel: true,
                zoom: 18
            });

            var infowindow = new google.maps.InfoWindow({
                content: address
            });



            var marker = new google.maps.Marker({
                map: map,
                position: myLatLng,
                animation: google.maps.Animation.DROP,
                title: 'Hello World!'
            });

            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });



        }

        google.maps.event.addDomListener(window, 'load', initMap);


    </script>
</head>
<body>


<div class="con1 main-header">
    <ul>
        <a href="donor-home.php"><li>Home</li></a>
        <a href="donor-profile.php?id=<?= $user_id ?>"><li>Profile</li></a>
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
        <h4 class="donor-phone-text"><?= "Call them <br/>" . $receiver_address[9] ?></h4>
    </div>
</div>


<div class="donor-form-claim-container">

    <?php
        if(isset($_GET['err'])){
            if($_GET['err'] == "mra"){
                echo '<p class="pop-up" id="pop"> You cannot make a request while your previous request is still pending for approval</p><br/>';
            }
        }

    ?>

    <h1> Claim Food </h1> <br/>
    <form action="" method="POST" class="donor-form-claim">

        <p class="claim-form-label claim-name"><?= $food_details[1] ?></p>
        <br/><br/>

        <p class="claim-form-label">Food ID : <?= $food_details[0] ?></p>

        <br/>

        <p class="claim-form-label">Food Amount : <?= $food_details[2] ?></p>
        <br/>

        <p class="claim-form-label">Amount Left : <?= $food_details[3] ?></p>

        <br/><br/>

        <label for="claim" class="claim-form-label">Claim Amount</label> &nbsp; &nbsp; &nbsp; <br/> <br/>
        <input type="number" name="claim" class="claim-form-input" min="1" max="<?= $food_details[3] ?>" value="" required/> <br/><br/>
        <input type="hidden" name="donor" value="<?= $food_details[4] ?>" />
        <input type="hidden" name="name" value="<?= $food_details[1] ?>" />

        <input type="submit" class="claim-form-submit" name="submit" id="submit" value="Claim"/>


    </form>
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
