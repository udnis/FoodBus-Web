<?php
session_start();

require "includes/credentials.php";
require "includes/login_functions.php";

$username = "";
$password = "";
$user_id = "";
$type = "";
$tmp_type = "";

if(isset($_GET['type'])){
    $tmp_type = $_GET['type'];
}

if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $user_id = $_SESSION['user_id'];
    $type = $_SESSION['type'];
}

if(isset($_GET['err'])){
    if($_GET['err'] == 'yes'){
        echo "<p class='error'> Invalid Credentials </p>";
    }
}

if($type == "donor" && !empty($username)){
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/donor-home.php");
    exit();
}

if($type == "receiver"  && !empty($username)){
    header("Location:http://{$GLOBALS['ip']}/FoodWebApp/receiver-home.php");
    exit();
}


$type = (!isset($_POST['type']))?"donor":$_POST['type'];



if(isset($_POST['submit'])){

    $username = $_POST['username'];
    $password = $_POST['password'];



    if($type == "donor"){

        check_login($username,$password,$type,$conn);

    }else{

        check_login($username,$password,$type,$conn);
    }

}











?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="styles/style.css" rel="stylesheet"/>
    <link href='https://fonts.googleapis.com/css?family=Neucha' rel='stylesheet' type='text/css'>
</head>
<body>

<div class="con1 login-form-container">


    <div class="con1 main-header">
        <ul>
            <a href="index.html"><li>Home</li></a>
            <a href="#"><li>What is Foodbus?</li></a>
            <a href="#"><li>Why Foodbus?</li></a>
            <a href="#"><li>Foodbus Media</li></a>
            <a href="#"><li>Contact Foodbus</li></a>
        </ul>
    </div>

    <br>
    <br>
    <br>

    <h1>Login</h1>

    <br>
    <br>
    <br>


    <form class="login-login-form con1" method="POST" submit="">




        <label for="type" class="login-form-label">Type</label> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
        <select class="food-add-select" name="type">
            <?php if($tmp_type == "donor"):?>
                <option class="login-select-type-item" value="donor" selected>Donor</option>
                <option class="login-select-type-item" value="receiver">Receiver</option>
            <?php else: ?>
                <option class="login-select-type-item" value="donor" >Donor</option>
                <option class="login-select-type-item" value="receiver" selected>Receiver</option>
            <?php endif ?>
        </select>



        <br> <br>


        <label for="username" class="login-form-label">Username</label> &nbsp; &nbsp; &nbsp;
        <input type="text" name="username" id="username" class="login-form-input">

        <br> <br>

        <label for="username" class="login-form-label">Password</label> &nbsp; &nbsp; &nbsp;
        <input type="text" name="password" id="password" class="login-form-input">

        <br><br><br>


        <input type="submit" value="Login" name="submit" class="login-form-submit">

    </form>

    <br><br>

    <h2> Don't have and Account? <span class="login-register-here"><a href="app-register.php">Register Here !</a></span></h2>

    <br><br>

    <br><br>
</div>





</body>
</html>
