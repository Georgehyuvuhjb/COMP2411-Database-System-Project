<?php
require_once "config.php";
require_once "session_customer_log_out.php";
$username = $_SESSION["username"];

// Get product name
$productId = $_GET['productID'];
$query = oci_parse($conn, 'SELECT * FROM Products WHERE Product_ID = :productId');
oci_bind_by_name($query, ':productId', $productId);
oci_execute($query);
$product = oci_fetch_assoc($query);
$product_name = $product['PRODUCT_NAME'];

// Get current cart quantity
$query_qty = oci_parse($conn, 'SELECT * FROM In_Cart WHERE Product_ID = :product_id AND User_Name = :username');
oci_bind_by_name($query_qty, ':product_id', $productId);
oci_bind_by_name($query_qty, ':username', $username);
oci_execute($query_qty);
if ($cart = oci_fetch_assoc($query_qty)) {
  $current_quantity = $cart['QUANTITY'];
} else {
  $current_quantity = 0;
}

// Add produc to cart if user clicks `Submit`
if (isset($_POST['addtocart'])) {
    $quantity = $_POST['quantity'];
    if ($current_quantity == 0) {  // new row
      $query_add = oci_parse($conn, "INSERT INTO In_Cart (quantity, Product_ID, User_Name)
        VALUES (:quantity, :productId, :username)");
      oci_bind_by_name($query_add, ':quantity', $quantity);
      oci_bind_by_name($query_add, ':productId', $productId);
      oci_bind_by_name($query_add, ':username', $username);
      oci_execute($query_add);
    } else {  // update row
      $query_update = oci_parse($conn, "UPDATE In_Cart
        SET quantity = :quantity
        WHERE Product_ID = :productId
        AND User_Name = :username");
      oci_bind_by_name($query_update, ':quantity', $quantity);
      oci_bind_by_name($query_update, ':productId', $productId);
      oci_bind_by_name($query_update, ':username', $username);
      oci_execute($query_update);
    }
} else {
  $quantity = $current_quantity;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add to Cart - OSS</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--external stylesheets-->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

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

    <div class="content">
        <form action="" method="post">
            <h1>Add the <?php echo $product_name; ?> to your cart!</h1>
            <label for="quantity"><h3>Quantity: </h3>
            <?php
            echo '<input id="quantity" name="quantity" type="number" value="' . $quantity . '" required><br>';
            echo "<a href='8productpage.php?productID=" . $productId . "'><button type='button'>Back</button></a>";
            ?>
            <input type="submit" name="addtocart" value="Submit">
    </form>
    </div>
</body>
</html>
