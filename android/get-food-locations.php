<?php
session_start();
require "../includes/credentials.php";


$sql = "SELECT DISTINCT a.latitude,a.longitude,a.name,a.door,a.street,a.area,a.city,a.village,a.pin,a.phone,a.user_id,c.email,a.profile_url,a.rating,a.times FROM donor_address a,food_details_donor b,donor_credential c WHERE a.user_id = b.user_id AND c.user_id = a.user_id  AND b.is_available = 1;";






    $array = array();

    $result = mysqli_query($conn,$sql);
            if(mysqli_num_rows($result) > 0){

                while($row1 = mysqli_fetch_assoc($result)) {

                    $array[] = array("lat" => $row1['latitude'] , "lng" => $row1['longitude'],"name" => $row1['name'],
                        "door" => $row1['door'] , "street" => $row1['street'], "city" => $row1['city'],"village" => $row1['village'],
                        "area" => $row1['area'] , "phone" => $row1['phone'],"pin" => $row1['pin'],"user_id" => $row1['user_id'] , "email" => $row1['email'],"profile" => $row1['profile_url'],"rating" => $row1['rating'],"times" => $row1['times']);

                }


                echo json_encode($array);
            }else{
                echo "no";
            }






?>