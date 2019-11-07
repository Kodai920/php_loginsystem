<?php

session_start();

if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>WELCOME</title>
</head>
<body>
    <div class="page-header">
    <h1>Hi, <b><?php echo $_SESSION["username"]; ?></b>. Welcome to our site</h1>
    </div>
    <p>
    <a href="logout.php" class="btn btn-danger">Logout from your account</a>
    </p>
</body>
</html>