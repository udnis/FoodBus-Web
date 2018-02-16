<?php


function add_food($conn,$user_id,$name,$amount,$expire){

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{

        $time = get_time();
        $date = get_date();


        $sql = "INSERT INTO food_details_donor (";
        $sql .= "user_id,time,date,name,amount,amount_left,is_available,is_claimed,is_wasted,date_expire) VALUES (";
        $sql .= " '{$user_id}' , TIME(CONVERT_TZ(NOW(),'-07:00','+05:30')) , TIMESTAMP(CONVERT_TZ(NOW(),'-07:00','+05:30')) , '{$name}', '{$amount}' , '{$amount}' , 1 , 0 , 0 , TIMESTAMP(ADDTIME(CONVERT_TZ(NOW(),'-07:00','+05:30'),'0 {$expire}:0:0'))  );";


        $result = mysqli_query($conn, $sql);

        if ($result) {

            $d_name = "SELECT name FROM donor_address WHERE user_id={$user_id};";
            $r = mysqli_query($conn, $d_name);
            $d_name = "";
            while($row = mysqli_fetch_row($r)){
                $d_name = $row[0];
            }

            $food_details = array("food_name" => $name,
                                    "donor_name" => $d_name ,"amount_left" => $amount);
            sendNoti($conn,$food_details);

            header("Location:http://{$GLOBALS['ip']}/FoodWebApp/donor-home.php");
            exit();

        }else{

        }
    }
}


function edit_food($conn,$food_id,$name,$amount,$status,$expire){

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{

        $is_available = 0;
        $is_claimed = 0;
        $is_wasted = 0;

        if($status == "available")  $is_available = 1;
        if($status == "claimed")    $is_claimed = 1;
        if($status == "wasted")     $is_wasted = 1;


        $previous_approved_sum = "SELECT amount_got FROM food_details_receiver WHERE food_id={$food_id} AND approved = 1;";

        $result1 =    mysqli_query($conn, $previous_approved_sum);

        $previous_amount = 0;

        if (mysqli_num_rows($result1) > 0) {

            while($row = mysqli_fetch_row($result1)) {

                $previous_amount += $row[0];
            }
        }

        $now_available = $amount - $previous_amount;

        if($now_available == 0){
            $is_available = 0;
            $is_claimed = 1;
        }


        $sql = "UPDATE food_details_donor ";
        $sql .= "SET name='{$name}',amount={$amount},amount_left={$now_available},is_available={$is_available},is_claimed={$is_claimed},is_wasted={$is_wasted},date_expire = TIMESTAMP(ADDTIME(CONVERT_TZ(NOW(),'-07:00','+05:30'),'0 {$expire}:0:0')) WHERE food_id = {$food_id}; ";


        $result = mysqli_query($conn, $sql);

        if ($result) {

            header("Location:http://{$GLOBALS['ip']}/FoodWebApp/donor-home.php");
            return true;

        }else{
            return null;
        }
    }
}

function remove_food($conn,$food_id){

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }else{



        $sql = "DELETE FROM food_details_donor WHERE food_id = {$food_id};";


        $result = mysqli_query($conn, $sql);

        if ($result) {

            header("Location:http://{$GLOBALS['ip']}/FoodWebApp/donor-home.php");
            return true;

        }else{
            echo "<script>alert('You cannot remove approved food before the receiver has revoked it');</script>";
        }
    }
}

function get_time(){

    return date("g:i a");
}

function get_date(){
    return date('d-m-Y');
}


function check_bool($tmp,$status){

    if($tmp == "1" && $status == "available" ){
        return "<span class='home-available-inner'>Yes</span>";
    }else if($tmp == "1" && $status == "claimed" ){
        return "<span class='home-claimed-inner'>Yes</span>";
    }else if($tmp == "1" && $status == "wasted" ){
        return "<span class='home-wasted-inner'>Yes</span>";
    }else{
        return "No";
    }
}

function check_bool_simple($tmp,$status){
    if($tmp == "1" && $status == "request"){
        return "<span class='request-span'>Yes</span>";
    }else if($tmp == "1" && $status == "approve"){
        return "<span class='approve-span'>Yes</span>";
    }else if($tmp == "0" && $status == "request"){
        return "<span class='no-span'>No</span>";
    }else if($tmp == "0" && $status == "approve"){
        return "<span class='no-span'>No</span>";
    }else{
        return "";
    }
}

?>