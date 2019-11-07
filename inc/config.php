<?php

//define the constants
define('SERVER_NAME','localhost');
define('DB_NAME','login');
define('USERNAME','root');
define('PASSWORD','root');

//make a connection to mysql
$conn = mysqli_connect(SERVER_NAME,USERNAME,PASSWORD,DB_NAME);

//test the connection
if(!$conn){
    die("Connection failed".mysqli_connect_error());
}else{
    echo "Connected successfully<br>";
}
?>