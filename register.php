<?php

include_once('./inc/config.php');

$sql = "CREATE TABLE IF NOT EXISTS users(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if($_SERVER['REQUEST_METHOD'] == "POST"){

    //validate username
    if(empty($_POST["username"])){
        $username_err = "Please enter a username";
    }else{
        $sql = "SELECT id FROM users WHERE username =?";

        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("s",$param_username);

            $param_username = $_POST["username"];

        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $username_err = "This username is already taken";
            }else{
                $username = $_POST["username"];
            }
        }else{
            echo "Oops! Something went wrong. Please try again later.";
        }
     }
        $stmt->close();
    }

    //validate password
    if(empty($_POST["password"])){
        $password_err = "Please enter a password";
    }elseif(strlen($_POST["password"]) < 6){
        $password_err = "Password must have atleast 6 characters long";
    }else{
        $password = $_POST["password"];
    }

    //validate confirm_password
    if(empty($_POST["confirm_password"])){
        $confirm_password_err = "Please confirm your password";
    }else{
        $confirm_password = $_POST["confirm_password"];
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match";
        }
    }

    //set "terms" to false by default
    $termsAccepted = false;

    if(isset($_POST["terms_of_service"])){
        $termsAccepted = true;
    }

    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && $termsAccepted == true){

        $sql = "INSERT INTO users(username,password) VALUES(?,?)";
        if($stmt = $conn->prepare($sql)){

         $stmt->bind_param("ss", $param_username,$param_password);
         $param_username = $username;
         $param_password = password_hash($password,PASSWORD_BCRYPT);

         if($stmt->execute()){
             header("location: login.php");
         }else{
             echo "Something went wrong. Please try again later";
         }
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body{background-color:gray;}
        .wrapper{width:550px; padding:100px 100px; margin-left:30%;}
        .form-text{color:red;}
    </style>
    <title>REGISTER</title>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
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
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password;?>">
            <span class="form-text">*<?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="terms_of_service" value="Y">
                I accept the terms of service.
            </label>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-default" value="Reset">
        </div>
        <p>Already have an account? <a href="login.php">Login Here</a>.</p>

    
    </form>
    </div>
    </div>
    </div>
</body>
</html>