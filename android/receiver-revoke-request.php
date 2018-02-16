<?php
session_start();
require "../includes/credentials.php";


if(isset($_GET['request_id'])){


    $request_id = $_GET['request_id'];
    $food_id = 0;
    $amount_left = 0;
    $amount_got = 0;
    $amount_left = 0;

    $request = "SELECT food_id,donor_id,amount_got FROM food_details_receiver WHERE id= {$request_id} LIMIT 1;";

    $result = mysqli_query($conn,$request);

    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){

            $food_id = $row['food_id'];
            $amount_got = $row['amount_got'];


            if($amount_got == 0){

                $small_change = "DELETE FROM food_details_receiver WHERE id={$request_id}";

                $result2 = mysqli_query($conn,$small_change);
                if($result2){
                    echo "ok";
                }else{
                    echo "error";
                }

            }else{

                $amount = "SELECT amount_left from food_details_donor WHERE food_id = {$food_id};";

                $result3 = mysqli_query($conn,$amount);
                if(mysqli_num_rows($result3) > 0){
                    while($row = mysqli_fetch_assoc($result3)) {
                        $amount_left = $row['amount_left'];
                        echo "Amount Left" . $amount_left;
                    }

                    $new_left = $amount_left + $amount_got;

                    $amount_update = "UPDATE food_details_donor SET amount_left = {$new_left} WHERE food_id={$food_id};";
                    $result4 = mysqli_query($conn,$amount_update);
                    
                    $wasted_update = "UPDATE food_details_donor SET is_available=1,is_claimed=0,is_wasted=0 WHERE food_id={$food_id} AND is_wasted = 0;";
                    $result5 = mysqli_query($conn,$wasted_update);
                    
                    
                    if($result4){

                        $delete = "DELETE FROM food_details_receiver WHERE id={$request_id}";
                        echo $delete;

                        $result5 = mysqli_query($conn,$delete);
                        if($result5){
                            echo "ok";
                        }else{
                            echo "error";
                        }

                    }else{
                        echo "error";
                    }
                }




            }
        }
    }


}

?>