<?php
session_start();

require "includes/credentials.php";
require "includes/helper_functions.php";
require "includes/donor-functions.php";
require "includes/donor-admin-functions.php";

$username = $_SESSION['username'];
$password = $_SESSION['password'];
$user_id = $_SESSION['user_id'];
$type = $_SESSION['type'];

if(isset($_GET['id'])){
    $id = $_GET['id'];
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

<div class="donor-home-food">

    <h1> Food Currently Available </h1> <br/>


    <?php

    $get_all_foods = "SELECT * FROM food_details_donor WHERE is_available = 1 AND user_id = $id  ORDER BY time DESC;";
    $result = mysqli_query($conn, $get_all_foods);



    if (mysqli_num_rows($result) > 0) {

        while($row = mysqli_fetch_row($result)) {

            $status = "";

            echo "<div class='donor-home-food-item'>";

            echo "<p class='card-time'>". get_local_time($row[2]) ."</p>";

            echo "<p class='card-date'>".get_local_date($row[3])."</p>";

            echo "<p  class='card-name'>{$row[4]}</p>";

            echo "<br/><br/>";

            echo "<p class='card-detail'>Food ID : {$row[0]}</p>";
            echo "<p class='card-detail'>Amount Available : {$row[6]}</p>";

            echo "<br/>";

            if($row[7] == 1){
                $status = "Available";
                echo "<div class='card-available'>Available</div><br/><br/>";
                echo "<a href='receiver-claim.php?food_id={$row[0]}&id={$id}' class='card-available-link'>Claim Some</a>";

            }elseif($row[8] == 1){
                $status = "Claimed";
                echo "<div class='card-claimed'>Claimed</div>";
            }else{
                $status = "Wasted";
                echo "<div class='card-wasted'>Wasted</div>";
            }



            echo "</div>";

        }

    }else{
        echo "<div class='donor-home-no-item'>";
        echo "No Food is available right now. Please try after a while";
        echo "</div>";
    }

    ?>
</div>

<div class="donor-home-food">

    <h1> Food History </h1> <br/>

    <?php

    $get_all_foods = "SELECT * FROM food_details_donor WHERE user_id = $id ORDER BY time DESC";
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
            echo "<td class='food-row-item-in'>".get_local_date($row[3])."</td>\n";
            echo "<td class='food-row-item-in'>".get_local_time($row[2])."</td>\n";
            echo "<td class='food-row-item-in'>".check_bool($row[7],"available")."</td>\n";
            echo "<td class='food-row-item-in'>".check_bool($row[8],"claimed")."</td>\n";
            echo "<td class='food-row-item-in'>".check_bool($row[9],"wasted")."</td>\n";
            echo "</tr>\n";

        }

        echo "</table>\n";
        echo "</div>\n";

    }else{
        echo "<div class='donor-home-no-item'>";
        echo "No Entry in Table";
        echo "</div>";
    }

    ?>

</div>

</div>

<div class="donor-map-container">
    <h1> Location </h1> <br/>
    <div class="donor-map-location" id="map">



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
