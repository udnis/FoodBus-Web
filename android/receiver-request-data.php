<?php

session_start();
require "../includes/credentials.php";

if(isset($_GET['user_id'])){

    $id = $_GET['user_id'];



    $requests = "SELECT food_id,donor_id,time,date,name,amount_claimed,amount_got,claim_request,approved,declined,id FROM food_details_receiver WHERE user_id = {$id}";

    $rows = array();

    $donor_amount = 0;
    $donor_wasted = 0;
    $donor_name = "";


    $result = mysqli_query($conn, $requests);

        if (mysqli_num_rows($result) > 0) {




            while($row = mysqli_fetch_row($result)) {
                $food_id = $row[0];
                $donor_id = $row[1];
                $request_id = $row[10];
                $time = $row[2];
                $date = $row[3];
                $name = $row[4];
                $amount_claimed = $row[5];
                $amount_got = $row[6];
                $claim_request = $row[7];
                $approved = $row[8];
                $declined = $row[9];

                $amount_left = "SELECT amount_left,name,is_wasted FROM food_details_donor WHERE food_id = {$food_id};";
                $name1 = "SELECT name from donor_address WHERE user_id = {$donor_id};";

                $result3 = mysqli_query($conn, $name1);
                $result2 = mysqli_query($conn, $amount_left);

                if (mysqli_num_rows($result2)) {

                    while($row = mysqli_fetch_row($result2)) {

                        $donor_amount = $row[0];
                        $donor_wasted = $row[2];
                    }

                }

                if (mysqli_num_rows($result3)) {

                    while($row = mysqli_fetch_row($result3)) {

                        $donor_name = $row[0];
                    }

                }


                $rows[] = array("food_id" => $food_id ,"donor_id" => $donor_id,"time" => $time , "date" => $date ,
                    "name" => $name ,"amount_claimed" => $amount_claimed ,"amount_got" => $amount_got ,"claim_request" => $claim_request ,"approved" => $approved ,
                    "declined" => $declined,"request_id" => $request_id ,"amount_left" => $donor_amount,"donor_name" => $donor_name,"is_wasted" => $donor_wasted);



            }

            $json = json_encode($rows);

            echo $json;


        }
    else{

        echo "fail";
    }




}

?>