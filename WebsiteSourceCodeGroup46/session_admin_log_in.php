<?php
session_start();

if (isset($_SESSION["adminId"])) {
  header("location: 14adminhome.php");
  exit;
}
?>
