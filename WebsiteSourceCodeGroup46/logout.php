<?php
session_start();
if (session_destroy()) {
    header("Location: 1firstpage.html");
    exit;
}
