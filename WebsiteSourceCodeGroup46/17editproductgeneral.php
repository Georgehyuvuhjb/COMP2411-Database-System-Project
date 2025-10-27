<?php
require_once "config.php";
require_once "session_admin_log_out.php";

if (isset($_POST['editproductgen'])) {
    
    $adminId = $_SESSION["adminId"];
    $productId = $_GET['productID'];
    
    $productName = trim($_POST['product-name']);
    $brand = trim($_POST['brand']);
    $category = trim($_POST['category']);
    $descr = trim($_POST['descr']);
    $ageRange = $_POST['age_range'];
    $weight = $_POST['weight'];
    $length = $_POST['length'];
    $width = $_POST['width'];
    $depth = $_POST['depth'];
    $dimensions = $length . "x" . $width . "x" . $depth;


    $updateQuery = "UPDATE Products
    SET product_name = :productName, product_description = :descr, product_brand = :brand, dimensions = :dimensions, weight = :weight, suitable_age_range = :ageRange
    WHERE Product_ID = :productId";

    $updateStmt = oci_parse($conn, $updateQuery);
    
    oci_bind_by_name($updateStmt, ':productName', $productName);
    oci_bind_by_name($updateStmt, ':descr', $descr);
    oci_bind_by_name($updateStmt, ':brand', $brand);
    oci_bind_by_name($updateStmt, ':dimensions', $dimensions);
    oci_bind_by_name($updateStmt, ':weight', $weight);
    oci_bind_by_name($updateStmt, ':ageRange', $ageRange);
    oci_bind_by_name($updateStmt, ':productId', $productId);

    oci_execute($updateStmt);

    $categoryMap = array(
        'Sports' => 1,
        'Clothes' => 2,
        'Food' => 3,
        'Drinks' => 4,
        'Entertainment' => 5,
        'Electronics' => 6,
        'Outdoors' => 7,
        'Furniture and Appliance' => 8,
        'Books and Media' => 9,
        'Office Supplies' => 10
    );

    $categoryID = $categoryMap[$category];

    $catUpdateQuery = "UPDATE Product_Belong_To 
    SET Category_ID = :categoryID 
    WHERE Product_ID = :productID"; 

    $catUpdateStmt = oci_parse($conn, $catUpdateQuery);
        
    oci_bind_by_name($catUpdateStmt, ':productID', $productId);
    oci_bind_by_name($catUpdateStmt, ':categoryID', $categoryID);
    oci_execute($catUpdateStmt);

    $headerURL = "Location: 16productdetail.php?productID=" . $productId;
    header($headerURL);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit product general details - OSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
<body>

    <div class="navbar">
        <div class ="logo">
            <a href="14adminhome.php" ><img src="images/logo_smaller.jpg" width="80"></a>
        </div>
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
            <h1>EDIT PRODUCT DETAILS</h1>

            <?php

            $productId = $_GET['productID'];

            $productQuery = "SELECT * FROM Products WHERE Product_ID = :productId";
            $productStmt = oci_parse($conn, $productQuery);
            oci_bind_by_name($productStmt, ':productId', $productId);
            oci_execute($productStmt);
            $productRow = oci_fetch_assoc($productStmt);

            $categoryQuery = "SELECT C.category_name 
            FROM Category C, Product_Belong_To PBT 
            WHERE PBT.Product_ID  = :productId AND C.Category_ID = PBT.Category_ID";
            $categoryStmt = oci_parse($conn, $categoryQuery);
            oci_bind_by_name($categoryStmt, ':productId', $productId);
            oci_execute($categoryStmt);
            $categoryRow = oci_fetch_assoc($categoryStmt);
            $category = $categoryRow["CATEGORY_NAME"];

            echo "

            <form action='' method='post'>
            <label for='product-name'>Product Name: </label>
            <input type='text' name='product-name' id='product-name' value='".$productRow['PRODUCT_NAME']."'required>
            <br>
            <label for='brand'>Brand: </label>
            <input type='text' name='brand' id='brand' value='".$productRow['PRODUCT_BRAND']."' required>
            <br>

            <label for='category'>Category:</label>
            <select name='category' id='category'>";

            $categoryOptions = array(
                'Sports',
                'Clothes',
                'Food',
                'Drinks',
                'Entertainment',
                'Electronics',
                'Outdoors',
                'Furniture and Appliance',
                'Books and Media',
                'Office Supplies'
            );
            foreach ($categoryOptions as $option) {
                if ($option == $category) {
                    echo '<option value='.$option.' selected="selected">'.$option.'</option>';
                } else {
                    echo '<option value='.$option.'>'.$option.'</option>';
                }
            }

            echo "</select><br>
            <label for='descr'>Description: </label><br>
            <textarea id='descr' name='descr' rows='7' cols='60' placeholder='Please input the description of the product here' required>".$productRow['PRODUCT_DESCRIPTION']."</textarea>
            <br>

            <label for='age_range'>Suitable age range: </label><br>
            <select id='age_range' name='age_range'>";

            $ageOptions = array(
                '&lt;3' => '<3',
                '3~10' => '3~10',
                '11~17' => '11~17',
                '18~64' => '18~64',
                '65+' => '65+',
                'All' => 'All'
            );
            foreach ($ageOptions as $option => $value) {
                if ($option == $productRow['SUITABLE_AGE_RANGE']) {
                    echo '<option value='.$value.' selected="selected">'.$option.'</option>';
                } else {
                    echo '<option value='.$value.'>'.$option.'</option>';
                }
            }

            $dimensions = $productRow['DIMENSIONS'];
            $dimensionsArr = explode("x", $dimensions);
            echo "
            </select><br>
            <label for='weight'>Weight (kg): </label><br>
            <input id='weight' name='weight' type='number' min='0' step='0.1' value='".$productRow['WEIGHT']."'><br>
            <label>Dimensions (length, width, depth): </label><br>
            <input id='length' name='length' type='number' min='0' value='".$dimensionsArr[0]."'> x
            <input id='width' name='width' type='number' min='0' value='".$dimensionsArr[1]."'> x
            <input id='depth' name='depth' type='number' min='0' value='".$dimensionsArr[2]."'> m<br>
            <br>
            <br>
            <a href='16productdetail.php?productID=$productId'><button type='button'>Cancel</button></a>
            <input type='submit' name='editproductgen' value='Confirm'>";
            ?>
        </form>
    </div>
</body>
</html>