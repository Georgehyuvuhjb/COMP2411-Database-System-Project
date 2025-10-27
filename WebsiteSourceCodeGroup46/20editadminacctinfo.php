<?php
require_once "config.php";
require_once "session_admin_log_out.php";

// This is called when the admin presses `submit`
if (isset($_POST['editAdminInfo'])) {
    $adminID = $_SESSION["adminId"];

    $name = trim($_POST['name']);
    $dateOfBirth = trim($_POST['birthdate']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $password = trim($_POST['pwd']);
    $storeName = trim($_POST['storename']);
    $storeDescription = trim($_POST['desc']);

    $updateAdmin = "UPDATE Admin SET Admin.actual_name = :name, Admin.date_of_birth = TO_DATE(:dob, 'YYYY/MM/DD'), Admin.email_address = :email,
               Admin.telephone_number = :tel, Admin.password = :password WHERE Admin.Admin_ID = :adminID";
    $updateStmt = oci_parse($conn, $updateAdmin);
    oci_bind_by_name($updateStmt, ':adminID', $adminID);
    oci_bind_by_name($updateStmt, ':name', $name);
    oci_bind_by_name($updateStmt, ':dob', $dateOfBirth);
    oci_bind_by_name($updateStmt, ':email', $email);
    oci_bind_by_name($updateStmt, ':tel', $tel);
    oci_bind_by_name($updateStmt, ':password', $password);
    oci_execute($updateStmt);

    $updateStore = "UPDATE Store SET Store.store_name = :storeName, Store.store_description = :description
                    WHERE Store.Admin_ID = :adminID";
    $storeUpdateStmt = oci_parse($conn, $updateStore);
    oci_bind_by_name($storeUpdateStmt, ':adminID', $adminID);
    oci_bind_by_name($storeUpdateStmt, ':storeName', $storeName);
    oci_bind_by_name($storeUpdateStmt, ':description', $storeDescription);
    oci_execute($storeUpdateStmt);

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Account Info - OSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
</head>
<body>

<!--Nav bar-->
<div class="navbar">
    <a href="14adminhome.php"><img src="images/logo_smaller.jpg" width="80"></a>
    <div class="search-container">
        <form action="15InventorySearch.php" method="GET">
            <input type="text" placeholder="Search your products..." name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
    <div class="dropdown">
        <button class="dropbtn"><i class="fa fa-user-o"></i></button>
        <div class="dropdown-content">
            <a href="20adminacctinfo.php">View Account Information</a>
            <a href="logout.php">Sign Out</a>
        </div>
    </div>
</div>

<div class="content">
    <h1>Edit account info</h1>
    <?php
    $adminID = $_SESSION["adminId"];
    $adminQuery = "SELECT * FROM Admin WHERE Admin_ID = :adminID";
    $adminStmt = oci_parse($conn, $adminQuery);
    oci_bind_by_name($adminStmt, ':adminID', $adminID);
    oci_execute($adminStmt);
    $adminRow = oci_fetch_assoc($adminStmt);

    $storeName = "SELECT * FROM Store WHERE Store.Admin_ID = :adminID";
    $store_stmt = oci_parse($conn, $storeName);
    oci_bind_by_name($store_stmt, ':adminID', $adminID);
    oci_execute($store_stmt);
    $storeRow = oci_fetch_assoc($store_stmt);
    echo"<form action = '' method = 'post'>
        <b>Your Information</b><br>
        <label for='name'>Name:</label>
        <input type='text' id='name' name='name' value = '".$adminRow['ACTUAL_NAME']."' required><br>
        <label for='email'>Email address:</label>
        <input type='email' id='email' name='email' value = '".$adminRow['EMAIL_ADDRESS']."' required><br>
        <label for='tel'>Phone number:</label>
        <input type='tel' id='tel' name='tel' value = '".$adminRow['TELEPHONE_NUMBER']."' required><br>
        <label for='birthdate'>Date of birth:</label>
        <input type='date' id='birthdate' name='birthdate' value = '".$adminRow['DATE_OF_BIRTH']."' required><br>
        <label for='pwd'>Password:</label>
        <input type='password' id='pwd' name='pwd' value = '".$adminRow['PASSWORD']."' required><br>
        <label for='storename'>Store Name:</label>
        <input type='text' id='storename' name='storename' value='".$storeRow['STORE_NAME']."' required><br>
        <label for='storedes'>Store Description:</label><br>
        <textarea id='storedes' name='desc' rows='4' cols='50'>".$storeRow['STORE_DESCRIPTION']."</textarea><br>"
        ?>
        <a href="20adminacctinfo.php"><button type="button">Cancel</button></a>
        <button name = "editAdminInfo" type="submit">Confirm</button><br>
    </form>
</div>

</body>
</html>
