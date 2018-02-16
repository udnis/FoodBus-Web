<?php

require "../includes/credentials.php";



$demo = array("food_name" => "Samosa",
    "receiver_name" => "Jeeva",
    "amount_claimed" => "100");

sendNotiDonor($conn,$demo);


?>