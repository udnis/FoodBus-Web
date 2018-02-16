<?php

session_start();
require "../includes/credentials.php";

if(isset($_GET['user_id'])){

    $id = $_GET['user_id'];
    $total_amount = 0;





    $req = "SELECT food_id,user_id,time,date,name,amount_claimed from food_details_receiver WHERE donor_id = {$id} AND claim_request  = 1 AND approved = 0;";

    $rows = array();

    $result = mysqli_query($conn, $req);

    if (mysqli_num_rows($result) > 0) {



        while($row = mysqli_fetch_row($result)) {


            $donor_amount = "SELECT amount_left FROM food_details_donor WHERE user_id={$id} AND food_id = {$row[0]};";

            $result1 =  mysqli_query($conn, $donor_amount);

            if (mysqli_num_rows($result1) > 0) {

                while ($row1 = mysqli_fetch_row($result1)) {

                    $total_amount = $row1[0];
                }

            }

            $receiver = $row[1];

            $rec = "SELECT name from receiver_address WHERE user_id = {$receiver} LIMIT 1 ;";

            $result2 = mysqli_query($conn, $rec);

            if (mysqli_num_rows($result) > 0) {

                $name = "";

                while($row2 = mysqli_fetch_row($result2)) {

                    $name = $row2[0];


                    $receiver_detail = "SELECT latitude,longitude,user_id,name,phone,door,area,street,village,city,pin,profile_url,rating,times FROM receiver_address WHERE user_id={$row[1]};";


                    $result10 = mysqli_query($conn, $receiver_detail);

                    while($row3 = mysqli_fetch_row($result10)) {

                        $email = "SELECT email FROM receiver_credential WHERE user_id = {$row[1]};";


                        $result11 = mysqli_query($conn, $email);

                        while($row4 = mysqli_fetch_row($result11)){

                            $rows[] = array("food_id" => $row[0],"receiver_name" => $name ,"receiver_id" => $row[1],
                                "name" => $row[4],"amount_claimed" => $row[5]
                            ,"time" =>  $row[2],"date" =>  $row[3],"total_amount" => $total_amount,
                                "lat" => $row3[0],
                                "lng" => $row3[1],
                                "r_id" => $row3[2],
                                "r_name" => $row3[3],
                                "r_phone" => $row3[4],
                                "r_door" => $row3[5],
                                "r_area" => $row3[6],
                                "r_street" => $row3[7],
                                "r_village" => $row3[8],
                                "r_city" => $row3[9],
                                "r_pin" => $row3[10],
                                "r_profile" => $row3[11],
                                "r_rating" => $row3[12],
                                "r_times" => $row3[13],
                                "r_email" => $row4[0]
                            );
                        }


                    }





                }


            }






        }

        echo json_encode($rows);

    }else{
        echo "error";
    }



}


?>