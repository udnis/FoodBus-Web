<?php


session_start();
require "../includes/credentials.php";



if(isset($_GET['food_id'])){

    $foodid = $_GET['food_id'];
    $name = $_GET['name'];
    $amount = $_GET['amount'];
    $status = $_GET['status'];
    $expire = $_GET['expire'];

    edit_food($conn,$foodid,$name,$amount,$status,$expire);



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
        
        

        $previous_amount = "SELECT amount_got FROM food_details_receiver WHERE food_id={$food_id} AND approved = 1;";

        $result2 = mysqli_query($conn, $previous_amount);

        $previous_amount = 0;

        if (mysqli_num_rows($result2) > 0) {

            while($row = mysqli_fetch_row($result2)) {

                $previous_amount += $row[0];
            }
        }

        $now_available = $amount - $previous_amount;

        if($now_available == 0 && $is_wasted != 1){

            $is_available = 0;
            $is_claimed = 1;
        }


        $sql = "UPDATE food_details_donor ";
        $sql .= "SET name='{$name}',amount={$amount},amount_left={$now_available},is_available={$is_available},is_claimed={$is_claimed},is_wasted={$is_wasted},date_expire = TIMESTAMP(ADDTIME(CONVERT_TZ(NOW(),'-07:00','+05:30'),'0 {$expire}:0:0')) WHERE food_id = {$food_id}; ";
        
        


        $result = mysqli_query($conn, $sql);

        if ($result) {
        
        	if($is_available == 1){
        	
        	$receiver_waste_update = "UPDATE food_details_receiver SET is_wasted = 0 WHERE food_id = {$food_id}; ";
        	$w_r = mysqli_query($conn, $receiver_waste_update);
        	
        	
        	
        	}

                if($is_claimed == 1){
        	
        	$receiver_waste_update = "UPDATE food_details_receiver SET is_wasted = 0 WHERE food_id = {$food_id}; ";
        	$w_r = mysqli_query($conn, $receiver_waste_update);
        	
        	
        	
        	}
        	
        	if($is_wasted == 1){
        	
        	
        	
        	$select_foods = "SELECT food_id,name FROM food_details_donor WHERE is_wasted = 1";
    		$food_result = mysqli_query($conn, $select_foods);

		    if(mysqli_num_rows($food_result) > 0){
		        while($row = mysqli_fetch_row($food_result)){
		
		            $f_id = $row[0];
		            $f_name = $row[1];
					
					$get_receivers = "SELECT user_id FROM food_details_receiver WHERE is_wasted = 0 AND food_id = {$f_id};";
					
					
					$receiver_list = mysqli_query($conn, $get_receivers);
					while($r_row = mysqli_fetch_row($receiver_list)){
						
						$demo = array("f_name" => $f_name,
		                    			"d_name" => "Jeeva");
		                    			
		        
		                    			
		                		sendNotiReceive($conn,$demo,$r_row[0]);
					}
		     }
		     }
		     
		     $receiver_waste_update = "UPDATE food_details_receiver SET is_wasted = 1 WHERE food_id = {$food_id}; ";
        	     $w_r = mysqli_query($conn, $receiver_waste_update);
        	
        	
        	
        	}

            echo "ok";

        }else{
            echo "error";
        }
    }
}


?>