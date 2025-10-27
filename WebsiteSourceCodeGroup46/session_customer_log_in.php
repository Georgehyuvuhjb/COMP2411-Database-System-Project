<?php
session_start();

if (isset($_SESSION["username"])) {
  header("location: 13customerhome.php");
  exit;
}
?>
