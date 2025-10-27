<?php
$db = "(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(Host = studora.comp.polyu.edu.hk)(Port = 1521))) (CONNECT_DATA = (SID=DBMS)))";
global $conn;
$conn = oci_connect('"23011608x"', 'diomnqdu', $db);
?>
