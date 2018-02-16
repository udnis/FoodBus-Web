<?php

session_start();

require "../includes/credentials.php";

if(isset($_GET['username']) && isset($_GET['password']) && isset($_GET['type']) ){

    $username = $_GET['username'];
    $password = $_GET['password'];
    $type = $_GET['type'];
    $id = 0;


    $check_login = "SELECT user_id,username,password from {$type}_credential WHERE username = '{$username}' AND password = '{$password}' LIMIT 1;";


    $result = mysqli_query($conn, $check_login);

    if (mysqli_num_rows($result) > 0) {



        while($row = mysqli_fetch_row($result)) {
            $id = $row[0];
        }
        $_SESSION['userid']  = $id;
        $_SESSION['username']  = $username;
        $_SESSION['password']  = $password;

        echo $id;

    }else{

        echo $id;
    }







}






?>