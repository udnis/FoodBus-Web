<?php

function get_detail($conn,$username,$password){

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{
        $sql = "SELECT user_id,username,password,email FROM donor_credential WHERE username='$username' AND password='{$password}' LIMIT 1;";
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

function get_address($conn,$id){

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{
        $sql = "SELECT name,door,street,area,village,city,pin,latitude,longitude,phone,rating,times FROM donor_address WHERE user_id = '{$id}' LIMIT 1;";
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

function insert_detail_donor($conn, $username,$password,$email,$name, $latitude, $longitude, $door, $street, $area, $village, $city, $pin,$phone){

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $email_present = 0;
    $username_present = 0;

            $email_check = "SELECT email FROM donor_credential;";
            $email_result = mysqli_query($conn, $email_check);

            if (mysqli_num_rows($email_result) > 0) {

                while($email_row = mysqli_fetch_row($email_result)) {
                    if($email_row[0] == $email){

                        $email_present = 1;

                }

            }

            $username_check = "SELECT username FROM donor_credential;";
            $username_result = mysqli_query($conn, $username_check);

            if (mysqli_num_rows($username_result) > 0) {

                while($username_row = mysqli_fetch_row($username_result)) {
                    if($username_row[0] == $username){

                        $username_present = 1;

                    }

                }
            }


            if($username_present){

                header("Location:app-register.php?erioid=username");
                exit();


            }

            if($email_present){

                header("Location:app-register.php?erioid=email");
                exit();
            }

            if(!$email_present && !$username_present){

                $sql_donor_credential = "INSERT INTO donor_credential (username,password,email) VALUES ('" .$username."','".$password."','" .$email."');";

                $sql_donor_address = "INSERT INTO donor_address ";
                $sql_donor_address .= "(user_id,name,latitude,longitude,door,street,area,village,city,pin,phone,profile_url) ";
                $sql_donor_address .= "VALUES (";
                $sql_donor_address .= "(SELECT user_id FROM donor_credential WHERE username = '{$username}'),";
                $sql_donor_address .= " '{$name}' , '$latitude' , '$longitude' ,'$door' ,'$street' , '$area' ,'$village' ,'$city' ,'$pin' ,'{$phone}' ,'profiles/profile.jpg' );";

                echo $sql_donor_address;


                $result = mysqli_query($conn, $sql_donor_credential);

                if ($result) {

                    $result = mysqli_query($conn, $sql_donor_address);

                    if($result)
                        return;

                } else {
                    echo "0 results";
                }
            }
    }



}


function insert_detail_receiver($conn, $username,$password,$email,$name, $latitude, $longitude, $door, $street, $area, $village, $city, $pin,$phone){

    $sql_donor_credential = "INSERT INTO receiver_credential (username,password,email) VALUES ('" .$username."','".$password."','" .$email."');";

    $sql_donor_address = "INSERT INTO receiver_address ";
    $sql_donor_address .= "(user_id,name,latitude,longitude,door,street,area,village,city,pin,phone,profile_url) ";
    $sql_donor_address .= "VALUES (";
    $sql_donor_address .= "(SELECT user_id FROM receiver_credential WHERE username = '{$username}'),";
    $sql_donor_address .= " '{$name}' , '$latitude' , '$longitude' ,'$door' ,'$street' , '$area' ,'$village' ,'$city' ,'$pin' ,'{$phone}' ,'profiles/profile.jpg' );";

    echo $sql_donor_address;


    $result = mysqli_query($conn, $sql_donor_credential);

    if ($result) {

        $result = mysqli_query($conn, $sql_donor_address);

        if($result)
            return;

    } else {
        echo "0 results";
    }

}


function accept_request($conn,$receiver_id,$food_id,$food_amount,$request_id)
{

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }


    $tmp = "SELECT amount_left from food_details_donor WHERE food_id={$food_id} LIMIT 1";

    $tmp_amount = 0;

    $result = mysqli_query($conn, $tmp);

    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_row($result)) {

            $tmp_amount = $row[0];

        }

        if($tmp_amount < $food_amount){
            $food_amount = $tmp_amount;

            $accept_request = "UPDATE food_details_donor SET approved = 1 ,is_available = 0,is_claimed = 1,claim_request = 1,amount_left = ";
            $accept_request .= " $tmp_amount - {$food_amount}";
            $accept_request .= " WHERE food_id={$food_id};";

            $amount_update_alone = "UPDATE food_details_receiver SET amount_got={$food_amount} WHERE id={$request_id} AND approved = 0;";

            $result3 = mysqli_query($conn, $amount_update_alone);

            $receiver_table_update = "UPDATE food_details_receiver SET approved = 1,claim_request = 1 WHERE id={$request_id};";


            $result1 = mysqli_query($conn, $accept_request);
            $result2 = mysqli_query($conn, $receiver_table_update);

            if ($result1 && $result2 && $result3) {


                $tmp = "SELECT amount,amount_left FROM food_details_donor WHERE food_id = " . $food_id . ";";

                $result = mysqli_query($conn, $tmp);

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_row($result)) {

                        if ($row[1] == 0) {
                            $change_status = "UPDATE food_details_donor SET is_available = 0,is_claimed = 1 WHERE food_id = {$food_id};";

                            $result = mysqli_query($conn, $change_status);

                            if ($result) {
                            }
                        }
                    }
                }

            } else {
                echo "0 results";
            }
        }else{

            $accept_request = "UPDATE food_details_donor SET approved = 1 ,is_claimed = 0,is_available=1,claim_request = 1,amount_left = ";
            $accept_request .= " $tmp_amount - {$food_amount}";
            $accept_request .= " WHERE food_id={$food_id};";

            $receiver_table_update = "UPDATE food_details_receiver SET approved = 1,amount_got={$food_amount},claim_request = 1 WHERE id={$request_id};";


            $result1 = mysqli_query($conn, $accept_request);
            $result2 = mysqli_query($conn, $receiver_table_update);

            if ($result1 && $result2) {



                $tmp = "SELECT amount,amount_left FROM food_details_donor WHERE food_id = " . $food_id . ";";

                $result = mysqli_query($conn, $tmp);

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_row($result)) {

                        if ($row[1] == 0) {
                            $change_status = "UPDATE food_details_donor SET is_available = 0,is_claimed = 1 WHERE food_id = {$food_id};";

                            $result = mysqli_query($conn, $change_status);

                            if ($result) {
                            }
                        }
                    }
                }

            } else {
                echo "0 results";
            }

        }



    }
}

    function reject_request($conn,$receiver_id,$food_id,$food_amount){

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }



            $receiver_table_update = "UPDATE food_details_receiver SET declined = 1 ,claim_request = 0 WHERE food_id={$food_id} AND user_id = {$receiver_id};";

            $result2 = mysqli_query($conn, $receiver_table_update);

            if ($result2) {

                echo "ALL DONE Beautifully";

            } else {
                echo "0 results";
            }

    }



?>