<?php


function get_detail_receiver($conn,$username,$password){

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{
        $sql = "SELECT user_id,username,password,email FROM receiver_credential WHERE username='$username' AND password='{$password}' LIMIT 1;";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {

            while($row = mysqli_fetch_row($result)) {

                return $row;
            }
        }else{
            return null;
        }
    }
}

function get_address_receiver($conn,$id){

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{
        $sql = "SELECT name,door,street,area,village,city,pin,latitude,longitude,phone,rating,times FROM receiver_address WHERE user_id = '{$id}' LIMIT 1;";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {

            while($row = mysqli_fetch_row($result)) {

                return $row;
            }
        }else{
            return null;
        }
    }
}

function get_food_details($conn,$food_id){

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{
        $sql = "SELECT food_id,name,amount,amount_left,user_id FROM food_details_donor WHERE food_id = '{$food_id}' LIMIT 1;";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {

            while($row = mysqli_fetch_row($result)) {

                return $row;
            }
        }else{
            return null;
        }
    }
}

function claim_food($conn,$food_id,$receiver_id,$claim_amount,$donor_id,$name){



    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{




        $check_previous = "SELECT food_id FROM food_details_receiver WHERE food_id = {$food_id} AND user_id={$receiver_id} AND approved = 0;";


        $tmp_lat = "";
        $tmp_lng = "";

        $result = mysqli_query($conn, $check_previous);

        if(mysqli_num_rows($result) <= 0){
            $sql = "UPDATE food_details_donor SET claimed_from = '{$receiver_id}', claim_request = 1 WHERE food_id = {$food_id};";

            $location = "SELECT latitude,longitude FROM receiver_address WHERE user_id = {$receiver_id};";

            $loc_result = mysqli_query($conn, $location);

            if(mysqli_num_rows($loc_result)){
                while($rowl = mysqli_fetch_assoc($loc_result)){
                    $tmp_lat = $rowl['latitude'];
                    $tmp_lng = $rowl['longitude'];
                }
            }


            $receiver_food_insert = "INSERT INTO food_details_receiver( ";
            $receiver_food_insert .= "food_id,user_id,time,date,name,amount_claimed,donor_id,claim_request,approved,declined,amount_got,tmp_lat,tmp_lng) VALUES (";
            $receiver_food_insert .= "'$food_id', '{$receiver_id}' , TIME(NOW()) , DATE(NOW()) , '{$name}', '{$claim_amount}' , '{$donor_id}' , 1 , 0 ,0,0,'{$tmp_lat}','{$tmp_lng}');";


            $result1 = mysqli_query($conn, $sql);
            $result2 = mysqli_query($conn, $receiver_food_insert);

            if ($result1 && $result2) {


                $d_name = "SELECT name FROM receiver_address WHERE user_id={$receiver_id};";
                $r = mysqli_query($conn, $d_name);
                $d_name = "";
                while($row = mysqli_fetch_row($r)){
                    $d_name = $row[0];
                }


                $demo = array("food_name" => $name,
                    "receiver_name" => $d_name,
                    "amount_claimed" =>$claim_amount);


                sendNotiDonor($conn,$demo,$donor_id);

                header("Location:http://{$GLOBALS['ip']}/FoodWebApp/receiver-admin.php");
                exit();

            }else{
                return null;
            }
        }else{

            
            header("Location:http://{$GLOBALS['ip']}/FoodWebApp/receiver-claim.php?food_id={$food_id}&id={$donor_id}&err=mra");
            exit();
        }



    }

}

function remove_request($conn,$user_id,$food_id,$donor_id,$claimed,$approved,$amount,$request_id){


    

    if($claimed == $approved){
        $tmp = "SELECT amount_left FROM food_details_donor WHERE food_id = " . $food_id .";";

        


        $result = mysqli_query($conn, $tmp);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_row($result)) {



                $donor_update = "UPDATE food_details_donor SET amount_left = ". ($row[0] + $amount) .",is_available=1,is_claimed=0,claimed_from = 0,claim_request = 0,approved = 0 WHERE food_id = {$food_id} AND user_id = {$donor_id} ;";

                


                $result = mysqli_query($conn, $donor_update);

                if($result){

                }


                $delete_request_entry = "DELETE FROM food_details_receiver WHERE food_id = {$food_id} AND user_id = {$user_id} AND id={$request_id} ;";

                

                $result = mysqli_query($conn, $delete_request_entry);

                if($result){
                    return;
                }
            }



        }else{
            echo "Error";
        }
    }
    else{



        $donor_update = "UPDATE food_details_donor SET claim_request = 0,claimed_from = 0" ." WHERE food_id = {$food_id};";
       
        $result = mysqli_query($conn, $donor_update);



        $delete_request_entry = "DELETE FROM food_details_receiver WHERE food_id = {$food_id} AND donor_id = {$donor_id} ;";



        $result = mysqli_query($conn, $delete_request_entry);

        if($result){
            return;
        }




    }


}

?>