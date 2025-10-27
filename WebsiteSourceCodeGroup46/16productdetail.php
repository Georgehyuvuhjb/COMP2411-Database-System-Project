<?php
require_once "config.php";
require_once "session_admin_log_out.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product detail - OSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet" href="styles.css">
    <style>
        .box {
    display: inline-block;
    padding: 10px;
            font-size: 16px;
            line-height: 1.2;
            width: 98.2%;
            word-wrap: break-word;
        }

        .box i {
    float: right;
}
    </style>
</head>
<body>

    <!--Nav bar-->
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

        <h1>Product Details</h1>
        <div class="box" style="background-color: tan;">
            <a href="17editproductgeneral.php"><i class="fa fa-pencil-square-o"></i></a>
            <b>General Information</b><br><br>
            <?php
            //$userName= $_SESSION["adminId"]; // Redundant
            $product_id = $_GET['productID'];
            $query = "SELECT * FROM Products P, Product_Belong_To B, Category C WHERE P.Product_ID = :product_id AND B.Product_ID = P.Product_ID AND C.Category_ID = B.Category_ID";
            $stmt = oci_parse($conn, $query);

            // Bind the parameters
            oci_bind_by_name($stmt, ':product_id', $product_id);

            oci_execute($stmt);

            $row = oci_fetch_assoc($stmt);
            echo "Name: ".$row['PRODUCT_NAME']."<br>";
            echo "Brand: ".$row['PRODUCT_BRAND']. "<br>";
            echo "Category: ".$row['CATEGORY_NAME']."<br>";
            echo "Description: ".$row['PRODUCT_DESCRIPTION']. "<br>";
            echo "Age Range: ".$tel = $row['SUITABLE_AGE_RANGE']."<br>";
            echo "Weight: ".$tel = $row['WEIGHT']."<br>";
            echo "Dimensions: ".$tel = $row['DIMENSIONS']."<br>";
            ?>
<!--            Name: Interesting Product(re)<br>-->
<!--            Brand: Adidas<br>-->
<!--            Category: Books & Media<br>-->
<!--            Description: This is a fabulous product!(re)<br>-->
<!--            Specification: This product can make you feel good.(re)<br>-->
        </div>
        <br><br>
        <div class="box" style="background-color: lightgray">
            <?php echo '<a href="17editproductprice.php?productID=' . $product_id . '"><i class="fa fa-pencil-square-o"></i></a>'; ?>
            <b>Price & Quantity</b><br><br>
            <?php
            //$userName= $_SESSION["adminId"]; // Redundant
            $product_id = $_GET['productID']; // Not Sure whether it will work
            $query = "SELECT * FROM Products P WHERE P.Product_ID = :product_id";
            $stmt = oci_parse($conn, $query);

            // Bind the parameters
            oci_bind_by_name($stmt, ':product_id', $product_id);

            oci_execute($stmt);

            $row = oci_fetch_assoc($stmt);
            echo "Selling Price: ".$row['CURRENT_SELLING_PRICE']."<br>";
            echo "Factory Price: ".$row['OUT_OF_FACTORY_PRICE']. "<br>";
            echo "Discount: ".$row['DISCOUNT']."<br>";
            echo "Quantity: ".$row['STOCK']. "<br>";
            ?>

<!--            Selling Price: $100(re)<br>-->
<!--            Factory Price: $70(re)<br>-->
<!--            Discount: 0.2(re)<br>-->
<!--            Quantity: 100(re)<br>-->
        </div>

        <br><br>

        <?php
        //$userName= $_SESSION["adminId"]; // Redundant
        $product_id = $_GET['productID']; // Not Sure whether it will work
        $query = "SELECT AVG(R.rating) AS avg_rating FROM Review R WHERE R.Product_ID = :product_id GROUP BY R.Product_ID";
        $stmt = oci_parse($conn, $query);
        oci_bind_by_name($stmt, ':product_id', $product_id);
        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);
        $avg_rating = $row['AVG_RATING'];
        if ($avg_rating == '') {
          $avg_rating = 'N/A';
        }
        echo "<b>CURRENT RATING: </b>" . $avg_rating . "<br>";
        ?>
    </div>
</body>
</html>
