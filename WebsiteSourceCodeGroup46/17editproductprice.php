<?php
require_once "config.php";
require_once "session_admin_log_out.php";

$product_id = $_GET['productID'];

if (isset($_POST['editproductprice'])) {

    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $quantity = $_POST['quantity'];

    $query_set = "UPDATE Products
      SET stock = :quantity, current_selling_price = :price, discount = :discount
      WHERE product_id = :product_id";
    $stid_set = oci_parse($conn, $query_set);
    oci_bind_by_name($stid_set, ':quantity', $quantity);
    oci_bind_by_name($stid_set, ':price', $price);
    oci_bind_by_name($stid_set, ':discount', $discount);
    oci_bind_by_name($stid_set, ':product_id', $product_id);
    oci_execute($stid_set);
}

$query_select = "SELECT stock, current_selling_price, discount
                 FROM Products
                 WHERE product_id = :product_id";
$stid_select = oci_parse($conn, $query_select);
oci_bind_by_name($stid_select, ':product_id', $product_id);
oci_execute($stid_select);
$row = oci_fetch_assoc($stid_select);
$stock = $row['STOCK'];
$price = $row['CURRENT_SELLING_PRICE'];
$discount = $row['DISCOUNT'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit product price and quantity - OSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <style>
        input[type='number']{
            width: 50px;
        }
    </style>
</head>

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
        <form action="" method="post">
            <h1>Edit product quantity and price</h1>
            <?php
            echo '<label for="price">Price: </label>';
            echo '<input id="price" name="price" type="number" value="' . $price . '" required><br>';
            echo '<label for="discount">Discount: </label>';
            echo '<input id="discount" name="discount" type="number" step="0.01" max = "1" value="' . $discount . '" required><br>';
            echo '<label for="quantity">Quantity: </label>';
            echo '<input id="quantity" name="quantity" type="number" step="1" pattern="[0-9]" min="0" value="' . $stock . '" required><br>';
            echo '<br>';
            ?>
            <a href="16productdetail.html"><button type="button">Cancel</button></a>
            <input type="submit" name="editproductprice" value="Confirm">
        </form>
    </div>

</body>
</html>
