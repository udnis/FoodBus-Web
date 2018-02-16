<?php

function check_login($username,$password,$type,$connection){

    $login_success = false;
    $conn = $connection;


    if($type == 'donor'){
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }else{
            $sql = "SELECT user_id,username,password FROM donor_credential;";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {

                while($row = mysqli_fetch_row($result)) {
                    if($row[1] == $username && $row[2] == $password){

                        $_SESSION['username'] = $username;
                        $_SESSION['password'] = $password;
                        $_SESSION['user_id'] = $row[0];
                        login_success("donor",$username,$password,$row[0]);

                    }

                }


                login_failure();
            }
        }
    }else if($type == 'receiver'){
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }else{
            $sql = "SELECT user_id,username,password FROM receiver_credential;";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {

                while($row = mysqli_fetch_row($result)) {
                    if($row[1] == $username && $row[2] == $password){


                        login_success("receiver",$username,$password,$row[0]);

                    }
                }

                login_failure();

            }


        }
    }else{
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }else{
            $sql = "SELECT user_id,username,password FROM receiver_credential;";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {

                while($row = mysqli_fetch_row($result)) {
                    if($row[1] == $username && $row[2] == $password){


                        login_success("receiver",$username,$password,$row[0]);

                    }


                }


                login_failure();
            }

        }
    }


}

function login_success($type,$username,$password,$user_id){

    if($type == "donor"){

        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['type'] = "donor";
        header("Location:http://{$GLOBALS['ip']}/FoodWebApp/donor-home.php");
        exit();
    }else {

        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['type'] = "receiver";
        header("Location:http://{$GLOBALS['ip']}/FoodWebApp/receiver-home.php");
        exit();
    }
}

function login_failure(){

    echo "<p class='error'>Invalid Credentials</p>";
}

?>