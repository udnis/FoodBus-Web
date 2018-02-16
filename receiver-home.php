<?php
session_start();

require "includes/credentials.php";
require "includes/helper_functions.php";
require "includes/receiver-functions.php";

$username = $_SESSION['username'];
$password = $_SESSION['password'];
$user_id = $_SESSION['user_id'];
$type = $_SESSION['type'];


if(!isset($username)){
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/app-login.php");
    exit();
}

if($type == "donor"){
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/donor-home.php");
    exit();
}

$credentials = get_detail_receiver($conn,$username,$password);

$receiver_address = get_address_receiver($conn,$user_id);


$make_wasted = "UPDATE food_details_donor SET is_wasted = 1,is_claimed=0,is_available = 0 WHERE TIMESTAMP((CONVERT_TZ(NOW(),'-07:00','+05:30'))) > date_expire AND is_wasted = 0";
$w_result = mysqli_query($conn, $make_wasted);

$select_foods = "SELECT food_id,name FROM food_details_donor WHERE is_wasted = 1";
$food_result = mysqli_query($conn, $select_foods);

if(mysqli_num_rows($food_result) > 0){
    while($row = mysqli_fetch_row($food_result)){
    
    	$f_id = $row[0];
            $f_name = $row[1];
			
			$get_receivers = "SELECT user_id FROM food_details_receiver WHERE is_wasted = 0 AND food_id = {$f_id};";
			
			$receiver_list = mysqli_query($conn, $get_receivers);
			while($r_row = mysqli_fetch_row($receiver_list)){
				
				$demo = array("f_name" => $f_name,
                    			"d_name" => "Jeeva");
                    			
                		sendNotiReceive($conn,$demo,$r_row[0]);
			}

        $food_wasted = "UPDATE food_details_receiver SET is_wasted = 1 WHERE food_id = {$row[0]}";
        $waste_result = mysqli_query($conn, $food_wasted);
    }
}

$imaglink = "SELECT profile_url FROM receiver_address WHERE user_id = {$user_id} LIMIT 1";

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


            var myLatLng = {lat: <?= $receiver_address[7] ?>, lng: <?= $receiver_address[8] ?>};

            var address = "<h2 class='map-marker-link-home'>My Location</h2><br/>";
            address += "<p class='map-marker-address'><?php echo $receiver_address[1].' ,'.$receiver_address[2].', <br/>' ?>";
            address += "<?php echo $receiver_address[3].' ,<br/>'.$receiver_address[4].' <br/>'.$receiver_address[5].' <br/>'.$receiver_address[6] ?></p>";
            address += "<h3 class='map-marker-phone'><?php echo $receiver_address[9] ?></h3>";


            var map = new google.maps.Map(document.getElementById('map'), {
                center: myLatLng,
                scrollwheel: true,
                zoom: 13
            });

            var marker = new google.maps.Marker({
                map: map,
                icon:"http://maps.google.com/mapfiles/ms/icons/blue.png",
                position: myLatLng,
                animation: google.maps.Animation.DROP,
                title: 'Hello World!'
            });

            var infowindow = new google.maps.InfoWindow({
                content: address
            });

            infowindow.open(map,marker);

            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });

            <?php

            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }else{
                $sql = "SELECT DISTINCT a.latitude,a.longitude,a.name,a.door,a.street,a.area,a.village,a.pin,a.phone,a.user_id FROM donor_address a,food_details_donor b WHERE a.user_id = b.user_id AND b.is_available = 1;";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {

                    $count = 0;



                    while($row = mysqli_fetch_row($result)) {

                        $add = "";
                        $add .= "var address{$count} =" . "'<br/><a class=\'map-marker-link\' href=\'receiver-check.php?id={$row[9]}\'> {$row[2]} </a><br/><br/>';";
                        $add .= " address{$count} +=" . "'<p>"."#"."{$row[3]} ".", "."{$row[4]}".","." </p>';";
                        $add .= " address{$count} +=" . "'<p>{$row[5]}".","."</p>';";
                        $add .= " address{$count} +=" . "'<p>{$row[6]}".","."</p>';";
                        $add .= " address{$count} +=" . "'<p>{$row[7]}".","."</p>';";
                        $add .= " address{$count} +=" . "'<p>{$row[8]}</p>';";

                        echo $add;

                        echo "var myLatLng{$count} = {lat: {$row[0]}, lng: {$row[1]}};";

                        echo "var marker{$count} = new google.maps.Marker({
                                     map:map ,
                                     position: myLatLng{$count},
                                     animation: google.maps.Animation.DROP,
                                     title: 'Hello World!'
                                });";


                        echo "var infowindow{$count} = new google.maps.InfoWindow({
                                    content: address{$count}
                                });";

                        echo "infowindow{$count}.open(map,marker{$count});";

                        echo "marker{$count}.addListener('click', function() {
                                    infowindow{$count}.open(map, marker{$count});
                                });";
                        $count++;


                    }
                }else{
                }
            }



            ?>








        }

        google.maps.event.addDomListener(window, 'load', initMap);



    </script>
</head>
<body>


<div class="con1 main-header">
    <ul>
        <a href="#"><li>Home</li></a>
        <a href="receiver-profile.php?id=<?= $user_id ?>"><li>Profile</li></a>
        <a href="receiver-admin.php"><li>Admin</li></a>
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
        <h4 class="donor-phone-text"><?= "Call me <br/>" . $receiver_address[9] ?></h4>
    </div>
    <div class="donor-rating" >
        <h4 class="donor-rating-text"><?= "Rating Average <br/>" . number_format($receiver_address[10]/$receiver_address[10],1) ?></h4>
    </div>
</div>

<div class="receiver-map-wrapper">

    <h1> Foods Available </h1>
    <div id="map" class="receiver-map">
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