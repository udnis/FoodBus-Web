<?php
?>

<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="10;url=http://<?=$GLOBALS['ip']?>/FoodWebApp/app-login.php">
    <title>Register Status</title>
    <meta name="viewport" content="width=device-width ,initial-scale=1">
    <link href="styles/style.css" rel="stylesheet"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link href='https://fonts.googleapis.com/css?family=Neucha' rel='stylesheet' type='text/css'>
    <script>
    /*
        $(document).ready(function(){

            tmp = 10;

            setInterval(function(){
                $('#status').text("Redirecting in " + tmp + " seconds");
                tmp--;
            }, 1000);



        });*/

    </script
</head>
<body>


<div class="con1 main-header">
    <ul>
        <a href="index.html"><li>Foodbus Home</li></a>
        <a href="index.html#what"><li>What is Foodbus?</li></a>
        <a href="index.html#why"><li>Why Foodbus?</li></a>
        <a href="index.html#media"><li>Foodbus Media</li></a>
        <a href="index.html#contact"><li>Contact Foodbus</li></a>
    </ul>
</div>


<div class="redirect-text-container">

    <br/>

    <p class="register-status"> Register Successful !</p> <br/>

    <p class="register-status"> You can now login from App or Web using the username and password.</p> <br/>

    <!--<p class="redirect-status" id="status"> Redirecting in 10 seconds </p> <br/> -->

    <a href="app-login.php" class="redirect-login-link">Goto Login Page</a>

</div>

</body>
</html>
