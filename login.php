<?php

session_start();

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){

    header("location: welcome.php");
    exit;
}

require_once('./inc/config.php');

$username = $password = "";
$username_err = $password_err = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){

    //validate username
    if(empty($_POST["username"])){
        $username_err = "Please enter a username";
    }else{
        $username = $_POST["username"];
    }

    //validate password
    if(empty($_POST["password"])){
        $password_err = "Please enter a password";
    }elseif(strlen($_POST["password"]) < 6){
        $password_err = "Password must have atleast 6 characters long";
    }else{
        $password = $_POST["password"];
    }

    if(empty($username_err) && empty($password_err)){

        $sql = "SELECT id,username,password FROM users WHERE username = ?";

        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s", $param_username);
            $param_username = $username;
   
            if($stmt->execute()){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    
                    mysqli_stmt_bind_result($stmt,$id,$username,$hashed_password);

                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password,$hashed_password)){
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            header("location: welcome.php");

                        }else $password_err = "The password you entered was not valid";
                        }
                        else $username_err = "No account found with that username.";
                    }
                    }else
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    $stmt->close();
                }
                $conn->close();
            }
  

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        body{background-color:aqua;}
        .wrapper{width:550px; padding:100px 100px; margin-left:30%;}
        .form-text{color:red;}
    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>LOGIN</title>
</head>
<body>
<div class="wrapper">
        <h2>Login</h2>
        <p>Please fill this form to login.</p>
        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo $username;?>">
            <span class="form-text">*<?php echo $username_err; ?></span>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" value="<?php echo $password;?>">
            <span class="form-text">*<?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p>Dont't have an account? <a href="register.php">SignUp here</a>.</p>
    </form>
    </div>
    </div>
    </div>


</body>
</html>