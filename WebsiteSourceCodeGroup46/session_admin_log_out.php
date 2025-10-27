<?php
session_start();

if (!isset($_SESSION["adminId"])) {
    header("location: 2adminlogin.php");
    exit;
}
?> 
