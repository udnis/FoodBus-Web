<?php
session_start();
require "../includes/credentials.php";

if(isset($_GET['request_id']) && isset($_GET['rating'])){


    $id = $_GET['request_id'];
    $rating = $_GET['rating'];

    $get_receiver = "SELECT user_id FROM food_details_receiver WHERE id = {$id};";

    $result4 = mysqli_query($conn,$get_receiver);

    while($row = mysqli_fetch_row($result4)){

        $receiver_id = $row[0];

        $prating = "SELECT rating,times FROM receiver_address WHERE user_id={$receiver_id};";
        $result5 = mysqli_query($conn,$prating);

        $pre_rating = 0;
        $pre_times = 0;

        while($row = mysqli_fetch_row($result5)){
            $pre_rating = $row[0];
            $pre_times = $row[1];
        }

        $rating_update = "UPDATE receiver_address SET rating=($pre_rating + $rating),
                               times=($pre_times + 1) WHERE user_id = {$receiver_id}";

        $result5 = mysqli_query($conn,$rating_update);
        if($result5){
            $update = "UPDATE food_details_receiver SET donor_complete = 1  WHERE id={$id}";

            $result = mysqli_query($conn,$update);
            if($result){

                $remove_test = "SELECT donor_complete,receiver_complete FROM food_details_receiver WHERE id={$id}";
                $result2 = mysqli_query($conn,$remove_test);
                if(mysqli_num_rows($result2) > 0){
                    while($row = mysqli_fetch_row($result2)){
                        if($row[0] == 1 && $row[1] == 1){

                            $delete = "DELETE FROM food_details_receiver WHERE id={$id};";
                            $result3 = mysqli_query($conn,$delete);
                            if($result3){
                                echo "ok";
                            }
                        }
                    }
                }else{
                    echo "error";
                }

            }else{
                echo "error";
            }
        }else{
            echo "error";
        }
    }





}


?>