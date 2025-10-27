<?php

require_once "session_customer_log_out.php";
require_once "config.php";

$userName = $_SESSION["username"];

// Update the database
if (isset($_POST['eleven'])) {

    $payment = $_POST['payment'];
    $address = $_POST['address'];

    // Prepare the query to update the stock quantity after purchase
    $query5 = "SELECT DISTINCT I.quantity, I.Product_ID, P.current_selling_price
    FROM In_Cart I, Products P
    WHERE I.User_Name = :userName
    AND I.Product_ID = P.Product_ID";
    $stmt5 = oci_parse($conn, $query5);
    oci_bind_by_name($stmt5, ':userName', $userName);
    oci_execute($stmt5);

    // Create order
    $query4 = "INSERT INTO Orders (order_date, Method_ID, Address_ID, User_Name)
      VALUES (CURRENT_TIMESTAMP, :payment, :address, :userName)";
    $stmt4 = oci_parse($conn, $query4);
    oci_bind_by_name($stmt4, ':payment', $payment);
    oci_bind_by_name($stmt4, ':address', $address);
    oci_bind_by_name($stmt4, ':userName', $userName);
    oci_execute($stmt4);

    // Get the order ID of the the new order
    /* $query8 = "SELECT COUNT(*) AS NUM_ORDERS FROM Orders";
    $stmt8 = oci_parse($conn, $query8);
    oci_execute($stmt8);
    $row8 = oci_fetch_array($stmt8, OCI_ASSOC + OCI_RETURN_NULLS);
    $orderID = $row8['NUM_ORDERS'];
    echo '8 done: ' . $orderID . '<br>'; */
    $query8 = "SELECT Order_ID_Sequence.CURRVAL FROM dual";
    $stmt8 = oci_parse($conn, $query8);
    oci_execute($stmt8);
    oci_fetch($stmt8);
    $orderID = oci_result($stmt8, 'CURRVAL');

    while ($row5 = oci_fetch_array($stmt5, OCI_ASSOC)) {

      // Update stock
      $query3 = "UPDATE Products
        SET stock = stock - :quantity
        WHERE Product_ID = :product_id";
      $stmt3 = oci_parse($conn, $query3);
      oci_bind_by_name($stmt3, ':quantity', $row5['QUANTITY']);
      oci_bind_by_name($stmt3, ':product_id', $row5['PRODUCT_ID']);
      oci_execute($stmt3);

      // Add the Contain_Order tuples
      $query7 = "INSERT INTO Contain_Order (buying_quantity, selling_price, Order_ID, Product_ID)
        VALUES(:quantity, :price, :orderID, :productID)";
      $stmt7 = oci_parse($conn, $query7);
      oci_bind_by_name($stmt7, ':price', $row5['CURRENT_SELLING_PRICE']);
      oci_bind_by_name($stmt7, ':quantity', $row5['QUANTITY']);
      oci_bind_by_name($stmt7, ':orderID', $orderID);
      oci_bind_by_name($stmt7, ':productID', $row5['PRODUCT_ID']);
      oci_execute($stmt7);
    }

    //Clear the Shopping Cart
    $query6 = "DELETE FROM In_Cart WHERE User_Name = :userName";
    $stmt6 = oci_parse($conn, $query6);
    oci_bind_by_name($stmt6, ':userName', $userName);
    oci_execute($stmt6);
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Checkout - OSS</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>

        <div class="navbar">
            <div class ="logo">
                <a href="13customerhome.html" ><img src="images/logo_smaller.jpg" width="80"></a>
            </div>
            <div class="search-container">
                <form action="/action_page.php">
                    <input type="text" placeholder="Search..." name="search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
            <div class="dropdown">
                <button class="dropbtn"><i class="fa fa-user-o"></i></button>
                <div class="dropdown-content">
                    <a href="4customeracctinfo.html">View Account Information</a>
                    <a href="1firstpage.html">Sign Out</a>
                </div>
            </div>
            <div class="cart-button">
                <a href="10cart(W).html"><i class="fa fa-shopping-cart"></i></a>
            </div>
        </div>

        <div class="content">
            <form action="" method="post">
                <h1>CHECKOUT</h1>

                <label for="address"><h3>Choose shipping address (From Your Addresses):</h3></label>
                <?php
                $query = "SELECT S.Address_ID, S.address_name FROM Shipment_Address S WHERE S.User_Name = :userName";
                $stmt = oci_parse($conn, $query);
                oci_bind_by_name($stmt, ':userName', $userName);
                oci_execute($stmt);
                echo '<select id="address" name="address" style="width: 800px;">';
                while($row=oci_fetch_array($stmt,OCI_RETURN_NULLS))
                {
                    echo '<option value="' . $row['ADDRESS_ID'] . '">' . $row['ADDRESS_NAME'] . '</option>';
                }
                echo '</select>';
                ?>

                <br><br>

                <label for="payment"><h3>Choose payment method:</h3></label>
                <select id="payment" name="payment">
                    <?php
                    $query = "SELECT M.method_id, M.method_name
                    FROM Payment_Method M, Has_Payment H
                    WHERE M.method_id = H.method_id
                      AND H.User_Name = :userName";
                    $stmt = oci_parse($conn, $query);
                    oci_bind_by_name($stmt, ':userName', $userName);
                    oci_execute($stmt);
                    while($row=oci_fetch_array($stmt,OCI_RETURN_NULLS))
                    {
                        echo '<option value="' . $row['METHOD_ID'] . '">' . $row['METHOD_NAME'] . '</option>';
                    }
                    ?>
                </select>

                <?php
                $query_total = "SELECT SUM(I.quantity * P.current_selling_price) AS TOTAL FROM In_Cart I JOIN Products P ON I.Product_ID = P.Product_ID WHERE I.User_Name = :userName";
                $stmt_total = oci_parse($conn, $query_total);
                oci_bind_by_name($stmt_total, ':userName', $userName);
                oci_execute($stmt_total);
                $row_total = oci_fetch_assoc($stmt_total);
                echo "<h3>Your Total: </h3>$" . $row_total['TOTAL'] . "<br>";
                ?>
                <br>
                <!-- <h2>Your Total: $100</h2> -->
                <a href="10cart.php"><button type="button">Cancel</button></a>
                <input name = "eleven" type="submit" value="Confirm" >
            </form>
        </div>
    </body>
</html>
