<?php

session_start();

//unset all the session variables
$_SESSION = array();

//destroy the session
session_destroy();

//redirect back to the login page
header("location: login.php");
exit;

?>