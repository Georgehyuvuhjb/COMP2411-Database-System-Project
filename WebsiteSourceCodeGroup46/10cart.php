<?php
require_once "config.php";
require_once "session_customer_log_out.php";

if(isset($_POST["deletItemFromCart"])){
    $userName= $_SESSION["username"];
    $productID = (int)$_POST["deletItemFromCart"];
    $deleteProductID = "DELETE FROM In_Cart WHERE In_Cart.Product_ID = :this_productID AND In_Cart.User_Name = :userName";
    $deleteStmt = oci_parse($conn, $deleteProductID);
    oci_bind_by_name($deleteStmt, ':this_productID', $productID);
    oci_bind_by_name($deleteStmt, ':userName', $userName);
    oci_execute($deleteStmt);
    header("Location: 10cart.php");

}

if(isset($_POST['input'])){

    $userName= $_SESSION["username"];
    $productQty = $_POST['tempQtyName'];
    $productIDArray = $_POST['productID'];

    $count = 0;
    foreach($productIDArray as $value){
        echo "Hi: ".$productQty[$count];
        $updateCart = "UPDATE In_Cart SET In_Cart.quantity = $productQty[$count] 
                       WHERE In_Cart.Product_ID = :this_productID AND In_Cart.User_Name = :userName";
        $updateStmt = oci_parse($conn, $updateCart);
        oci_bind_by_name($updateStmt, ':this_productID', $value);
        oci_bind_by_name($updateStmt, ':userName', $userName);
        oci_execute($updateStmt);
        $count += 1;
    }
    if ($_POST['input'] == 'checkOut'){
        header("Location: 11checkout.php");
        

    }else if($_POST['input'] == 'saveCart'){
        header("Location: 13customerhome.php");

    }



}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Shopping Cart - OSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <style>
        .cart-items input[type='number']{
            width: 50px;
        }
        .cart-items input[type='text']{
            border: 0px;
            width: 120px;
        }
        #cart {
            border-collapse: collapse;
        }

        #cart td, #cart th {
            padding-right: 30px;
        }

        #cart th {
            text-align: left;
        }
    </style>
</head>


<body>

<div class="navbar">
    <div class ="logo">
        <a href="13customerhome.php" ><img src="images/logo_smaller.jpg" width="80"></a>
    </div>
    <div class="search-container">
        <form action="7productlist.php" method="GET">
            <input type="text" class="search-bar" placeholder="Search by product name..." name="search">
            <button type="submit" class="custom-button"><i class="fa fa-search"></i></button>
        </form>
    </div>
    <div class="dropdown">
        <button class="dropbtn"><i class="fa fa-user-o"></i></button>
        <div class="dropdown-content">
            <a href="4customeracctinfo.php">View Account Information</a>
            <a href="1firstpage.html">Sign Out</a>
        </div>
    </div>
    <div class="cart-button">
        <a href="10cart.php"><i class="fa fa-shopping-cart"></i></a>
    </div>
</div>

<!-- PAGE 10-->
<div class="content">
    <h1>YOUR SHOPPING CART</h1>
    <form action="" method="post"></form>
    <div class="cart-items">
        <form action='' method='post' id='checkout'>
            <table id='cart'>
                <tr>
                    <th></th>
                    <th>Product name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th></th>
                </tr>
                <?php
                $userName= $_SESSION["username"];

                $cartQuery = "SELECT * FROM In_Cart WHERE In_Cart.User_Name = :userName";

                $cartStmt = oci_parse($conn, $cartQuery);

                // Bind the parameters
                oci_bind_by_name($cartStmt, ':userName', $userName);

                oci_execute($cartStmt);



                $count = 1;
                $productIDName = "productID";
                $nameForJavaScript = "price";
                $nameForJSQty = "qty";
                $nameForJSTot = "total";
                $nameForJSDel = "del";
                while($cartRow = oci_fetch_assoc($cartStmt)){

                    $tempProductID = $productIDName.$count;
                    $tempName = $nameForJavaScript.$count;
                    $tempQtyName = $nameForJSQty.$count;
                    $tempTotName = $nameForJSTot.$count;
                    $tempDelName = $nameForJSDel.$count;

                    $priceForOne = "SELECT * FROM Products WHERE Products.Product_ID = :this_product_id";

                    $priceStmt = oci_parse($conn, $priceForOne);

                    // Bind the parameters
                    oci_bind_by_name($priceStmt, ':this_product_id', $cartRow['PRODUCT_ID']);


                    oci_execute($priceStmt);

                    $priceRow = oci_fetch_assoc($priceStmt);
                    $singlePrice = $priceRow['CURRENT_SELLING_PRICE']*$priceRow['DISCOUNT'];
                    $itemTotal = $singlePrice*$cartRow['QUANTITY'];
                    $maxValue = $priceRow['STOCK'];
                    $Product_ID = $cartRow['PRODUCT_ID'];

                    echo"<tr>
                        <td><input type='hidden' id=$tempProductID name='productID[]' size = '0' value= $Product_ID readonly></td>
                        <td>".$priceRow['PRODUCT_NAME']."</td>
                        <td><input type='text' id=$tempName name='tempName[]' value= $singlePrice readonly></td>
                        <td><input type='number' id=$tempQtyName name='tempQtyName[]' min='1' max= $maxValue onchange='findItemTotal($count); findTotal()' value='".$cartRow['QUANTITY']."' required></td>
                        <td><input type='text' id=$tempTotName name=$tempTotName value= $itemTotal readonly></td>
                        <td><button name = 'deletItemFromCart' type='submit' id=$tempDelName value = $Product_ID><i class='fa fa-trash'></i></button></td>
                    </tr>";
                    $count += 1;
                }
                ?>
            </table>
            <br>
            <b>Total: </b>$<input type="text" id="total" name="total" value="" readonly>
            <br><br>
            <button type="button">Cancel</button> <!--TODO: go to previous page -->
            <button name = "input" type="submit" value = "checkOut">Proceed to checkout</button>
            <button name = "input" type = "submit" value = "saveCart">Save the current cart</button>
        </form>
    </div>
</div>

<script>
    function findTotal() {
        var prices = document.querySelectorAll('[id^=price]');
        var quantities = document.querySelectorAll('[id^=qty]');
        var tot = 0;
        for(var i=0;i<prices.length;i++){
            price = parseFloat(prices[i].value.replace('$', ''));
            qty = parseInt(quantities[i].value.replace('$', ''));
            tot += price * qty;
            console.log('price: ' + price);
            console.log('qty: ' + qty);
        }
        document.getElementById('total').value = tot;
    }

    function findItemTotal(itemNum) {
        var price = parseFloat(document.getElementById('price' + itemNum).value.replace('$', ''));
        var qty = parseInt(document.getElementById('qty' + itemNum).value);
        document.getElementById('total' + itemNum).value = '$' + price * qty;
    }
    findTotal();
</script>
</body>
</html>
