<?php


session_start();
require "../includes/credentials.php";

if(isset($_GET['receiver_id']) && isset($_GET['donor_id'])){

    $id = $_GET['receiver_id'];
    $d_id = $_GET['donor_id'];
    $food_id = $_GET['food_id'];
    $status = $_GET['status'];
    $amount = $_GET['amount'];
    $d_name = "";

    if($status == "yes"){


        $tmp = "SELECT amount_left,name from food_details_donor WHERE food_id={$food_id} LIMIT 1";
        $d_name = "SELECT name from donor_address WHERE user_id = {$d_id}";
        
        $r = mysqli_query($conn, $d_name);
        
        if (mysqli_num_rows($r) > 0) {

            while ($row1 = mysqli_fetch_row($r)) {

                $d_name = $row1[0];

            }

        }

        $total_amount = 0;
        $f_name = "";

        $result = mysqli_query($conn, $tmp);

        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_row($result)) {

                $total_amount = $row[0];
                $f_name = $row[1];

            }

        }

        $accept_request ="";


        if($total_amount <= $amount) {

            $amount = $total_amount;
            $accept_request = "UPDATE food_details_donor SET is_claimed = 1,is_available = 0,is_wasted = 0,amount_left =";
            $accept_request .= " $total_amount - {$amount}";
            $accept_request .= " WHERE food_id={$food_id};";
            
            $q  = "UPDATE food_details_receiver SET approved = 1,amount_got={$amount},claim_request=1 WHERE user_id = {$id} AND food_id = {$food_id};";



            $result = mysqli_query($conn, $accept_request);

            if($result){

                $result = mysqli_query($conn, $q);
                if($result){
                    echo "ok";
                    $demo = array("food_accepted_name" => $f_name,
                    	"donor_accepted_name" => $d_name,"donor_accepted_amount" => $amount);
			 sendNotiAccepted($conn,$demo,$id);
                }else{
                    echo "error";
                }
            }else{
                echo "error";
            }


        }else{
            $accept_request = "UPDATE food_details_donor SET approved = 1 ,is_claimed = 0,is_available = 1,is_wasted = 0,amount_left = ";
            $accept_request .= " $total_amount - {$amount}";
            $accept_request .= " WHERE food_id={$food_id};";
            
            
            $q  = "UPDATE food_details_receiver SET approved = 1,amount_got={$amount},claim_request=1 WHERE user_id = {$id} AND food_id = {$food_id};";



            $result = mysqli_query($conn, $accept_request);

            if($result){

                $result = mysqli_query($conn, $q);
                if($result){
                    echo "ok";
                    $demo = array("food_accepted_name" => $f_name,
                    	"donor_accepted_name" => $d_name,"donor_accepted_amount" => $amount);
			 sendNotiAccepted($conn,$demo,$id);
                }else{
                    echo "error";
                }
            }else{
                echo "error";
            }

        }



            








    }else{
    
     	$tmp = "SELECT amount_left,name from food_details_donor WHERE food_id={$food_id} LIMIT 1";
        $d_name = "SELECT name from donor_address WHERE user_id = {$d_id}";
        
        $r = mysqli_query($conn, $d_name);
        
        if (mysqli_num_rows($r) > 0) {

            while ($row1 = mysqli_fetch_row($r)) {

                $d_name = $row1[0];

            }

        }
        
        $result = mysqli_query($conn, $tmp);

        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_row($result)) {

                $total_amount = $row[0];
                $f_name = $row[1];

            }

        }


        $q  = "UPDATE food_details_receiver SET claim_request = 0,declined = 1 WHERE user_id = {$id} AND food_id = {$food_id};";

        $result = mysqli_query($conn, $q    );

        if($result){
            echo "ok";
            $demo = array("food_rejected_name" => $f_name,
                    	"donor_rejected_name" => $d_name,"donor_rejected_amount" => $amount);
			 sendNotiRejected($conn,$demo,$id);
        }else{
            echo "error";
        }
    }
}






?>