<?php
require_once "config.php";
require_once "session_customer_log_out.php";
if (isset($_POST["confirmAddress"])) {
    $userName= $_SESSION["username"];
    $address1 = trim($_POST['address1']);
    $address2 = trim($_POST['address2']);
    $address3 = trim($_POST['address3']);
    $address4 = trim($_POST['address4']);
    $array = array($address1,$address2,$address3,$address4);
    $countTheRow = "SELECT COUNT(*) AS counting FROM Shipment_Address WHERE :userName = Shipment_Address.User_Name";
    $countStmt = oci_parse($conn, $countTheRow);
    oci_bind_by_name($countStmt, ':userName', $userName);
    oci_execute($countStmt);
    $countRow = oci_fetch_assoc($countStmt);
    $count = $countRow['COUNTING']+1;
    $checkUpdate = "SELECT * 
                    FROM Shipment_Address ShAdd 
                    WHERE :userName = ShAdd.User_Name 
                    AND ShAdd.Is_active = 1
                    ORDER BY ShAdd.Address_ID";

    $updateStmt = oci_parse($conn, $checkUpdate);
    oci_bind_by_name($updateStmt, ':userName', $userName);
    oci_execute($updateStmt);

    foreach ($array as $value){
        $addressName = null;
        $addressID = null;
        if($AddressRow = oci_fetch_assoc($updateStmt)){
            $addressName = $AddressRow['ADDRESS_NAME'];
            $addressID = $AddressRow['ADDRESS_ID'];
        }
        $flag = $addressName != $value && !is_null($addressName);
        if($flag){
            $replaceOldAddress = "UPDATE Shipment_Address 
                                  SET Shipment_Address.Is_active = 0 
                                  WHERE :userName = Shipment_Address.User_Name 
                                  AND Shipment_Address.Address_ID = :numb 
                                  AND Shipment_Address.Is_active = 1";
            $replaceStmt = oci_parse($conn, $replaceOldAddress);
            oci_bind_by_name($replaceStmt, ':userName', $userName);
            oci_bind_by_name($replaceStmt, ':numb', $addressID);
            oci_execute($replaceStmt);
        }
        if(is_null($addressName) && !empty($value) &&  $addressName != $value || $flag) {
            echo "Executed ".$count." ".$value." ".$userName;
            $insertAddress = "INSERT INTO Shipment_Address VALUES(:count,:addressName,:userName,1)";
            $insertStmt = oci_parse($conn, $insertAddress);
            oci_bind_by_name($insertStmt, ':userName', $userName);
            oci_bind_by_name($insertStmt, ':addressName', $value);
            oci_bind_by_name($insertStmt, ':count', $count);
            oci_execute($insertStmt);
            $count = $count +1;
        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit addresses - OSS</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--external stylesheets-->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

    <style>
        body * {
            font-family: "Verdana", sans-serif;
        }

        .li {
            float: right;
        }

        .addressinput {
            width: 700px;
        }

        body {
            background-image: url("images/Nightsky3.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: top;
        }
    </style>
</head>
<body>

<!--Nav bar-->
<div class="navbar">
        <div class ="logo">
            <a href="13customerhome.php"><img src="images/logo_smaller.jpg" width="80"></a>
        </div>
        <div class="search-container">
            <form action="7productlist.php" method="GET">
                <input type="text" placeholder="Search..." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="dropdown">
            <button class="dropbtn"><i class="fa fa-user-o"></i></button>
            <div class="dropdown-content">
                <a href="4customeracctinfo.php">View Account Information</a>
                <a href="logout.php">Sign Out</a>
            </div>
        </div>
        <div class="cart-button">
            <a href="10cart.php"><i class="fa fa-shopping-cart"></i></a>
        </div>
    </div>

<div class="content" style="color:white">
    <h1><b>Edit addresses</b></h1>
    <?php
    $userName= $_SESSION["username"];
    $currentAddress = "SELECT ShAdd.Address_ID, ShAdd.address_name 
                           FROM Shipment_Address ShAdd 
                           WHERE :userName = ShAdd.User_Name AND ShAdd.is_active =1 
                           ORDER BY  ShAdd.Address_ID";
    $addressStmt = oci_parse($conn, $currentAddress);
    oci_bind_by_name($addressStmt, ':userName', $userName);
    oci_execute($addressStmt);
    $address1 = oci_fetch_assoc($addressStmt);
    $address2 = oci_fetch_assoc($addressStmt);
    $address3 = oci_fetch_assoc($addressStmt);
    $address4 = oci_fetch_assoc($addressStmt);
    echo"<form action='' method = 'post'>
            <ol>
                <li>
                    <input type='text' id='address1' name='address1' value='".$address1['ADDRESS_NAME']."' class='addressinput'>
                    <i class='fa fa-trash' onclick='clearAddress(1)'></i>
                </li>
                <li>
                    <input type='text' id='address2' name='address2' value='".$address2['ADDRESS_NAME']."' class='addressinput'>
                    <i class='fa fa-trash' onclick='clearAddress(2)'></i>
                </li>
                <li>
                    <input type='text' id='address3' name='address3' value='".$address3['ADDRESS_NAME']."' class='addressinput'>
                    <i class='fa fa-trash' onclick='clearAddress(3)'></i>
                </li>
                <li>
                    <input type='text' id='address3' name='address4' value='".$address4['ADDRESS_NAME']."' class='addressinput'>
                    <i class='fa fa-trash' onclick='clearAddress(4)'></i>
                </li>";
    ?>
    </ol>
    <a href="4customeracctinfo.php"><button type = "button">Cancel</button></a>
    <button name = "confirmAddress" type="submit">Confirm</button><br>
    </form>
</div>

<script>
    function clearAddress(id) {
        document.getElementById("address" + id).value = "";
    }
</script>

</body>
</html>