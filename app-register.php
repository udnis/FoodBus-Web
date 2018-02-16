<?php

require "includes/credentials.php";
require "includes/donor-functions.php";

$error_msg = "";
$error = false;
$registered_successfully = false;
$upload_ok = 0;

if(isset($_GET['erioid']) && $_GET['erioid']=="email"){
    echo "<p class='error'>Email is already registered.Press back button to change.</p>";
}

if(isset($_GET['erioid']) && $_GET['erioid']=="username"){
    echo "<p class='error'>Username already taken. Press back button to change.</p>";
}

if(isset($_POST['submit']) && !$registered_successfully) {

    $type = $_POST['type'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password1'];
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
    $image = $_FILES["profile"]["name"];

    if(!isset($_POST["profile"])){


        if (!$conn) {

            die("Connection failed: " . mysqli_connect_error());

        } else {

            if ($type == "donor") {

                insert_detail_donor($conn, $username,$password,$email,$name, $latitude, $longitude, $door, $street, $area, $village, $city, $pin, $phone,$type);
                    header("Location:login-redirect.php");

            } else {

                insert_detail_receiver($conn, $username,$password,$email,$name, $latitude, $longitude, $door, $street, $area, $village, $city, $pin, $phone,$type);
                    header("Location:login-redirect.php");


            }


        }


    }else{

        $login_success = false;


        if (!$conn) {

            die("Connection failed: " . mysqli_connect_error());

        } else {

            if ($type == "donor") {

                insert_detail_donor($conn, $username,$password,$email,$name, $latitude, $longitude, $door, $street, $area, $village, $city, $pin, $phone,$type);


                $sql = "SELECT user_id from donor_credential WHERE username = '{$username}' LIMIT 1;";
                $result = mysqli_query($conn, $sql);


                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_row($result)) {

                        $tmp_id = $row[0];

                        echo $tmp_id;
                    }
                }

                file_upload($tmp_id,$conn);


            } else {
                insert_detail_receiver($conn, $username,$password,$email,$name, $latitude, $longitude, $door, $street, $area, $village, $city, $pin, $phone,$type);

                $sql = "SELECT user_id from receiver_credential WHERE username = '{$username}' LIMIT 1;";
                $result = mysqli_query($conn, $sql);


                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_row($result)) {

                        $tmp_id = $row[0];

                        echo $tmp_id;
                    }
                }

                file_upload_r($tmp_id,$conn);


            }


        }



    }






    function file_upload($base,$conn)
    {

        $id = $base;

        $base = "__profile_".$base;

        $target_dir = "uploads/";

        $tmP_type = pathinfo(basename($_FILES["profile"]["name"]),PATHINFO_EXTENSION);

        echo $tmP_type . "<br/>";

        $target_file = $target_dir . $base . "." .$tmP_type ;
        echo $target_file. "<br/>";;
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        $check = getimagesize($_FILES["profile"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".". "<br/>";;
            $uploadOk = 1;
        } else {
            echo "<p class='error'>File is not an image.</p>". "<br/>";;
            $uploadOk = 0;
        }

        if (file_exists($target_file)) {
            echo "<p class='error'>Sorry, file already exists.</p>". "<br/>";;
            $uploadOk = 0;
        }

        if ($_FILES["profile"]["size"] > 500000) {
            echo "<p class='error'>Sorry, your file is too large.</p>". "<br/>";;
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "<p class='error'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>". "<br/>";;
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "<p class='error'>Sorry, your file was not uploaded.</p>". "<br/>";;

        } else {
            if (move_uploaded_file($_FILES["profile"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["profile"]["name"]) . " has been uploaded.". "<br/>";

                $img_link = "UPDATE donor_address SET profile_url = '$target_file' WHERE user_id = {$id};";
                $result = mysqli_query($conn, $img_link);
                if($result){
                    echo "OK";
                }


                header("Location:login-redirect.php");
            } else {
                echo "<p class='error'>Sorry, there was an error uploading your file.</p>". "<br/>";;
            }
        }

    }

    function file_upload_r($base,$conn)
    {

        $id = $base;

        $base = "__profile_".$base;

        $target_dir = "uploads/";

        $tmP_type = pathinfo(basename($_FILES["profile"]["name"]),PATHINFO_EXTENSION);

        echo $tmP_type . "<br/>";

        $target_file = $target_dir . $base . "." .$tmP_type ;
        echo $target_file. "<br/>";;
        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        $check = getimagesize($_FILES["profile"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".". "<br/>";;
            $uploadOk = 1;
        } else {
            echo "<p class='error'>File is not an image.</p>". "<br/>";;
            $uploadOk = 0;
        }

        if (file_exists($target_file)) {
            echo "<p class='error'>Sorry, file already exists.</p>". "<br/>";;
            $uploadOk = 0;
        }

        if ($_FILES["profile"]["size"] > 500000) {
            echo "<p class='error'>Sorry, your file is too large.</p>". "<br/>";;
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "<p class='error'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>". "<br/>";;
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "<p class='error'>Sorry, your file was not uploaded.</p>". "<br/>";;

        } else {
            if (move_uploaded_file($_FILES["profile"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["profile"]["name"]) . " has been uploaded.". "<br/>";

                $img_link = "UPDATE receiver_address SET profile_url = '$target_file' WHERE user_id = {$id};";
                $result = mysqli_query($conn, $img_link);
                if($result){
                    echo "OK";
                }


                header("Location:login-redirect.php");
            } else {
                echo "<p class='error'>Sorry, there was an error uploading your file.</p>". "<br/>";;
            }
        }

    }



}







?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link href="styles/style.css" rel="stylesheet"/>
    <link href='https://fonts.googleapis.com/css?family=Neucha' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width ,initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABrj0AaxsM7ZgoqngH5q376c6AUKMU_EI&libraries=places"></script>
        <script>

        $(document).ready(function(){


        });


        function initMap() {


            var myLatLng = {lat: 13.0652299, lng: 80.2112275};

            var input = document.getElementById('map-search');


            var map = new google.maps.Map(document.getElementById('map'), {
                center: myLatLng,
                scrollwheel: true,
                zoom: 13
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
            <a href="index.html"><li>Home</li></a>
            <a href="#"><li>What is Foodbus?</li></a>
            <a href="#"><li>Why Foodbus?</li></a>
            <a href="#"><li>Foodbus Media</li></a>
            <a href="#"><li>Contact Foodbus</li></a>
        </ul>
    </div>

    <br>
    <br>
    <br>

    <h1>Register</h1>

    <br>
    <br>
    <br>



    <form class="register-form" method="POST" submit="" id="register-form" enctype="multipart/form-data">


        <label for="type" class="login-form-label">Type</label>
        <select class="register-form-select" name="type">
                <option class="login-select-type-item" value="donor" selected>Donor</option>
                <option class="login-select-type-item" value="receiver">Receiver</option>
        </select>

        <br><br>

        <label for="name" class="login-form-label">Enter Name</label>
        <input type="text" name="name" id="name" class="register-form-input register-name" required>


        <br><br>

        <label for="username" class="login-form-label">Enter Username</label>
        <input type="text" name="username" id="username" class="register-form-input register-username" required>

        <br><br>

        <label for="password1" class="login-form-label">Enter Password</label>
        <input type="text" name="password1" id="password1" class="register-form-input register-password1" required>

        <br><br>

        <label for="email" class="login-form-label">Enter Email</label>
        <input type="email" name="email" id="email" class="register-form-input register-email" required>

        <br><br>

        <label for="profile" class="login-form-label">Upload Image</label>
        <input type="file" name="profile" id="profile" class="register-profile" >
        <br><br>

        <label for="door" class="login-form-label">Enter Door No</label>
        <input type="text" name="door" id="door" class="register-form-input register-door" required>

        <br><br>

        <label for="street" class="login-form-label">Enter Street</label>
        <input type="text" name="street" id="street" class="register-form-input register-street" required>

        <br><br>

        <label for="area" class="login-form-label">Enter Area</label>
        <input type="text" name="area" id="area" class="register-form-input register-area" required>

        <br><br>

        <label for="village" class="login-form-label">Enter Village/Town</label>
        <input type="text" name="village" id="village" class="register-form-input register-village" required>

        <br><br>

        <label for="city" class="login-form-label">Enter City</label>
        <input type="text" name="city" id="city" class="register-form-input register-city" required>

        <br><br>

        <label for="pin" class="login-form-label">Enter PIN</label>
        <input type="text" name="pin" id="pin" class="register-form-input register-pin" required>

        <br><br>

        <label for="phone" class="login-form-label">Enter Phone</label>
        <input type="phone" name="phone" id="phone" class="register-form-input register-street" required>

        <br><br>

        <label for="map" class="login-form-label location-label">Enter your Location</label><br><br>

        <input type="text" class="register-map-search" id="map-search"  /> <br/><br/>

        <div class="register-location-lat" id="lat-pos">Latitude</div>
        <div class="register-location-lng" id="lng-pos">Longitude</div> <br><br><br>

        <input type="hidden" id="latitude" name="latitude" required/>
        <input type="hidden" id="longitude" name="longitude" required/>



        <div id="map" class="register-map" "></div> <br> <br><br>

        <input type="submit" name="submit" value="Submit" class="register-submit"/>

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
