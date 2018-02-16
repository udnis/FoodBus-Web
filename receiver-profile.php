<?php
session_start();

require "includes/credentials.php";
require "includes/helper_functions.php";
require "includes/receiver-functions.php";

$username = $_SESSION['username'];
$password = $_SESSION['password'];
$user_id = $_SESSION['user_id'];
$type = $_SESSION['type'];

if($type == "donor"){
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/donor-profile.php");
    exit();
}

$credentials = get_detail_receiver($conn,$username,$password);

$receiver_address = get_address_receiver($conn,$user_id);



function file_upload($base,$conn)
{

    $id = $base;

    $base = "__profile_".$base;

    $target_dir = "uploads/";

    $tmP_type = pathinfo(basename($_FILES["profile"]["name"]),PATHINFO_EXTENSION);

    echo $tmP_type . "<br/>";

    $target_file = $target_dir . $base . "." .$tmP_type ;
    echo $target_file. "<br/>";;

    unlink($target_file);

    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    $check = getimagesize($_FILES["profile"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".". "<br/>";;
        $uploadOk = 1;
    } else {
        echo "File is not an image.". "<br/>";;
        $uploadOk = 0;
    }

    if (file_exists($target_file)) {
        echo "Sorry, file already exists.". "<br/>";;
        $uploadOk = 0;
    }

    if ($_FILES["profile"]["size"] > 500000) {
        echo "Sorry, your file is too large.". "<br/>";;
        $uploadOk = 0;
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.". "<br/>";;
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.". "<br/>";;

    } else {
        if (move_uploaded_file($_FILES["profile"]["tmp_name"], $target_file)) {
            echo "The file " . basename($_FILES["profile"]["name"]) . " has been uploaded.". "<br/>";

            $img_link = "UPDATE receiver_address SET profile_url = '{$target_file}' WHERE user_id = {$id};";
            $result = mysqli_query($conn, $img_link);
            if($result){
                echo "OK";
            }



        } else {
            echo "Sorry, there was an error uploading your file.". "<br/>";;
        }
    }

}

if(isset($_POST['submit'])){

    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $door = $_POST['door'];
    $street = $_POST['street'];
    $area = $_POST['area'];
    $village = $_POST['village'];
    $city = $_POST['city'];
    $pin = $_POST['pin'];
    $phone = $_POST['phone'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];


    if (!$conn) {

        die("Connection failed: " . mysqli_connect_error());

    }else {


        $update = "UPDATE receiver_credential SET email = '{$email}' WHERE user_id = {$id};";
        $update_address = "UPDATE receiver_address SET name = '{$name}' , door = '{$door}' , street = '{$street}' ,area = '{$area}' ,village = '{$village}' ,city = '{$city}' ,pin = {$pin} ";
        $update_address .= " ,phone = '{$phone}',latitude = '{$latitude}',longitude = '{$longitude}' WHERE user_id = {$id} ;";


        $result = mysqli_query($conn, $update);

        if ($result) {

            $result = mysqli_query($conn, $update_address);

            if($result){
                file_upload($id,$conn);
            }


                //header("Location:http://{$GLOBALS['ip']}/FoodWebApp/receiver-home.php");

        } else {
            echo "0 results";
        }
    }
}else{

}



?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link href="styles/style.css" rel="stylesheet"/>
    <link href='https://fonts.googleapis.com/css?family=Neucha' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABrj0AaxsM7ZgoqngH5q376c6AUKMU_EI&libraries=places"></script>
    <script>

        $(document).ready(function(){

            document.getElementById("lat-pos").innerHTML = "Latitude: " + '<br/>' + <?= $receiver_address[7]  ?>;
            document.getElementById("lng-pos").innerHTML = "Longitude: " + '<br/>' + <?= $receiver_address[8] ?>;

        });


        function initMap() {


            var myLatLng = {lat: <?= $receiver_address[7] ?>, lng: <?= $receiver_address[8] ?>};

            var input = document.getElementById('map-search');


            var map = new google.maps.Map(document.getElementById('map'), {
                center: myLatLng,
                scrollwheel: true,
                zoom: 18
            });

            var marker = new google.maps.Marker({
                map: map,
                position: myLatLng,
                animation: google.maps.Animation.DROP,
                title: 'Hello World!'
            });

            map.addListener('click', function (e) {
                marker.setPosition(e.latLng);
                document.getElementById("lat-pos").innerHTML = "Latitude: " + '<br/>' + e.latLng.lat();
                document.getElementById("lng-pos").innerHTML = "Longitude: " + '<br/>' + e.latLng.lng();
                document.getElementById("latitude").value =  e.latLng.lat();
                document.getElementById("longitude").value = e.latLng.lng();
            });

            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);

            var infowindow = new google.maps.InfoWindow();

            autocomplete.addListener('place_changed', function() {
                infowindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }


                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                marker.setIcon(({
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(35, 35)
                }));
                marker.setPosition(place.geometry.location);
                document.getElementById("lat-pos").innerHTML = "Latitude: " + '<br/>' + place.geometry.location.lat();
                document.getElementById("lng-pos").innerHTML = "Longitude: " + '<br/>' + place.geometry.location.lng();
                document.getElementById("latitude").value = place.geometry.location.lat();
                document.getElementById("longitude").value =  place.geometry.location.lng();
                marker.setVisible(true);

            });

        }

        google.maps.event.addDomListener(window, 'load', initMap);


    </script>
</head>
<body>

<div class="con1 register-form-container">


    <div class="con1 main-header">
        <ul>
            <a href="receiver-home.php"><li>Home</li></a>
            <a href="receiver-profile.php?id=<?= $user_id ?>"><li>Profile</li></a>
            <a href="receiver-admin.php"><li>Admin</li></a>
            <a href="donor-logout.php"><li>Logout</li></a>
        </ul>
    </div>

    <br>
    <br>
    <br>

    <h1>Edit your Profile</h1>

    <br>
    <br>
    <br>



    <form class="register-form" method="POST" submit="" id="register-form"  enctype="multipart/form-data">


        <label for="name" class="login-form-label">Edit Name</label>
        <input type="text" name="name" id="name" class="register-form-input register-name" value="<?= $receiver_address[0] ?>" required>

        <br><br>

        <label for="email" class="login-form-label">Edit Email</label>
        <input type="email" name="email" id="email" class="register-form-input register-email" value="<?= $credentials[3] ?>"  required>

        <br><br>


        <label for="profile" class="login-form-label">Profile Image</label>
        <input type="file" name="profile" id="profile" class="register-profile" required>
        <br><br>

        <label for="door" class="login-form-label">Edit Door No</label>
        <input type="text" name="door" id="door" class="register-form-input register-door" value="<?= $receiver_address[1] ?>" required>

        <br><br>

        <label for="street" class="login-form-label">Edit Street</label>
        <input type="text" name="street" id="street" class="register-form-input register-street" value="<?= $receiver_address[2] ?>" required>

        <br><br>

        <label for="area" class="login-form-label">Edit Area</label>
        <input type="text" name="area" id="area" class="register-form-input register-area" value="<?= $receiver_address[3] ?>" required>

        <br><br>

        <label for="village" class="login-form-label">Edit Village/Town</label>
        <input type="text" name="village" id="village" class="register-form-input register-village" value="<?= $receiver_address[4] ?>" required>

        <br><br>

        <label for="city" class="login-form-label">Edit City</label>
        <input type="text" name="city" id="city" class="register-form-input register-city" value="<?= $receiver_address[5] ?>" required>

        <br><br>

        <label for="pin" class="login-form-label">Edit PIN</label>
        <input type="text" name="pin" id="pin" class="register-form-input register-pin" value="<?= $receiver_address[6] ?>" required>

        <br><br>

        <label for="phone" class="login-form-label">Edit Phone</label>
        <input type="text" name="phone" id="phone" class="register-form-input register-phone" value="<?= $receiver_address[9] ?>" required>

        <br><br>

        <label for="map" class="login-form-label location-label">Edit your Location</label><br><br>

        <input type="text" class="register-map-search" id="map-search"  /> <br/><br/>

        <div class="register-location-lat" id="lat-pos">Latitude</div>
        <div class="register-location-lng" id="lng-pos">Longitude</div> <br><br><br>

        <input type="hidden" id="latitude" name="latitude" value="<?= $receiver_address[7] ?>" required/>
        <input type="hidden" id="longitude" name="longitude" value="<?= $receiver_address[8] ?>" required/>
        <input type="hidden" id="id" name="id" value="<?= $credentials[0] ?>"/>



        <div id="map" class="register-map" "></div> <br> <br><br>

<input type="submit" name="submit" value="Save Changes" class="register-submit"/>

</form>
<br/><br/><br/>
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
