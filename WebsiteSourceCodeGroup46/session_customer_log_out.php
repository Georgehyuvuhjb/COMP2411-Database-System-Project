<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("location: 2customerlogin.php");
    exit;
}
?> 
