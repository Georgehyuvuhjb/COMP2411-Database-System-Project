<?php

require_once "session_customer_log_out.php";
require_once "config.php";

$product_id = $_GET['productID'];
$db = "(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(Host = studora.comp.polyu.edu.hk)(Port = 1521))) (CONNECT_DATA = (SID=DBMS)))";
$conn = oci_connect('"23011608x"', 'diomnqdu', $db);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
$query = oci_parse($conn, 'SELECT * FROM Products WHERE Product_ID = :product_id');
oci_bind_by_name($query, ':product_id', $product_id);
oci_execute($query);
$product = oci_fetch_assoc($query);

// Retrieve the store name of the product
$query_store = oci_parse($conn, 'SELECT s.store_name FROM Products p, Store s WHERE p.Store_ID = s.Store_ID AND p.Product_ID = :product_id');
oci_bind_by_name($query_store, ':product_id', $product_id);
oci_execute($query_store);
if ($row = oci_fetch_array($query_store, OCI_ASSOC)) {
  $store = $row['STORE_NAME'];
} else {
  $store = 'N/A';
}

// Retrieve the reviews of the product
$query_reviews = oci_parse($conn, 'SELECT * FROM Products p, Review r WHERE p.Product_ID = r.Product_ID AND p.Product_ID = :product_id');
oci_bind_by_name($query_reviews, ':product_id', $product_id);
oci_execute($query_reviews);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product - OSS</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!--Montserrat font-->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons@4.28.0/dist/feather.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles2.css" />
    <style>
        .sticky {
            position: fixed;
            top: 0;
            width: 100%;
        }

        .sticky + .content {
            padding-top: 60px;
        }
    </style>
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

    <!-- MOBILE NAVIGATION -->
    <div class="w3-container w3-padding-16 w3-border-bottom w3-hide-medium w3-hide-large">
        <a href="#home" class="w3-bar-item w3-button w3-hover-none">PRODUCT</a>
        <a href="javascript:void(0)" class="w3-bar-item w3-button w3-right w3-hide-large w3-hide-medium" onclick="toggleNavigation()">&#9776;</a>
    </div>
    <div id="mobile-nav" class="w3-bar-block w3-hide w3-hide-large w3-hide-medium w3-sticky">
        <a href="#DETAILS" class="w3-bar-item w3-button w3-center w3-hover-none w3-border-white w3-bottombar w3-hover-border-green w3-hover-text-green" onclick="toggleNavigation()">DETAILS</a>
        <a href="#COMMENTS" class="w3-bar-item w3-button w3-center w3-hover-none w3-border-white w3-bottombar w3-hover-border-green w3-hover-text-green" onclick="toggleNavigation()">COMMENTS</a>
        <a href="#contact" class="w3-bar-item w3-button w3-center w3-hover-none w3-border-white w3-bottombar w3-hover-border-green w3-hover-text-green" onclick="toggleNavigation()">CONTACT</a>
    </div>

    <!-- SIDE-BAR SECTION -->
    <section class="image-section w3-quarter w3-fixed w3-padding-small">
        <!--BUYING BUTTON-->
        <div class="sidebar" id="sidebar">
            <br>
            <?php echo '<a href="9addtocart.php?productID=' . $product_id . '">><img src = "images/Add_Cart.jpg" width = 300px></a>'; ?>
            <h5 style="color: white">Click the Picture to Add to Shopping Cart</h5>
            <br>
            <a href="7productlist.php"><button style="width:75%">Return to Search Results</button></a>
        </div>
    </section>

    <!--CONTENT SECTION-->
    <section class="w3-threequarter w3-padding-large w3-right">
        <!--DESKTOP NAVIGATION-->
        <br><br><br>
        <div class="w3-container w3-padding-large w3-border-bottom w3-hide-small">
            <a href="#home" class="w3-bar-item w3-button w3-hover-none w3-border-white w3-bottombar w3-hover-border-green">PRODUCT</a>
            <a href="#contact" class="w3-bar-item w3-button w3-hover-none w3-border-white w3-bottombar w3-hover-border-green w3-hover-text-green w3-right w3-hide-small" style="border-width: 2px !important;" onclick="toggleNavigation()">CONTACT</a>
            <a href="#COMMENTS" class="w3-bar-item w3-button w3-hover-none w3-border-white w3-bottombar w3-hover-border-green w3-hover-text-green w3-right w3-hide-small" style="border-width: 2px !important;" onclick="toggleNavigation()">COMMENTS</a>
            <a href="#DETAILS" class="w3-bar-item w3-button w3-hover-none w3-border-white w3-bottombar w3-hover-border-green w3-hover-text-green w3-right w3-hide-small" style="border-width: 2px !important;" onclick="toggleNavigation()">DETAILS</a>
        </div>
        <div class="content-container w3-margin-top-2">
            <!--HOME SECTION-->
            <div id="home" class="home w3-container w3-margin-top-4 w3-cursive">
                <p style="font-style: italic; font-size:x-large;"><?php echo $store; ?></p>
                <h1><?php echo $product['PRODUCT_NAME']; ?></h1>
                <h2 style="display: inline;">Price: </h2>
                <?php
                if ($product['DISCOUNT'] < 1) {
                  echo '<h4 style="display: inline; text-decoration: line-through;">$' . $product['CURRENT_SELLING_PRICE'] . '</h4>';
                  $new_price = $product['CURRENT_SELLING_PRICE'] * $product['DISCOUNT'];
                  echo '<h2 style="display: inline;"> $' . $new_price . '</h2>';
                } else {
                  echo '<h2 style="display: inline;">' . $product['CURRENT_SELLING_PRICE'] . '</h2>';
                }
                ?>
                <h3>Brand: <?php echo $product['PRODUCT_BRAND']; ?></h3>
                <h3>Quantity: <?php echo $product['STOCK']; ?></h3>
            </div>

            <!--DETAILS SECTION-->
            <div id="DETAILS" class="w3-container w3-margin-top-20-percent w3-cursive w3-large">
                <h2 class="w3-border-bottom w3-border-amber" style="border-width: 3px !important;">PRODUCT DETAILS</h2>
                <br/>
                <h3 class="w3-border-amber">Description</h3>
                <p class="w3-margin-top-2">
                    <?php echo $product["PRODUCT_DESCRIPTION"]; ?>
                </p>
                <h3>Specification</h3>
                <p class="">
                    Dimensions: <?php echo $product["DIMENSIONS"]; ?> <br />
                    Weight: <?php echo $product["WEIGHT"]; ?> <br />
                    Suitable age range: <?php echo $product["SUITABLE_AGE_RANGE"]; ?> <br />
                </p>
            </div>

            <!--COMMENTS-->
            <div id="COMMENTS" class="w3-container w3-margin-top-20-percent w3-cursive">
                <h2 class="w3-border-bottom w3-border-amber" style="border-width: 3px !important;">RATING & COMMENT</h2>
                <div class="w3-container w3-margin-top-2 w3-cursive">
                    <!--Previous User Comments SECTION-->
                    <h3>User Comments & Reviews</h3>
                    <?php
                    $rating_sum = 0;
                    $num_reviews = 0;
                    while ($row = oci_fetch_array($query_reviews, OCI_ASSOC)) {
                      echo '<div class="">';
                      echo '<p><b>Posted By ' . $row["USER_NAME"] . ' (rating: ' . $row["RATING"] . ')</b></p>';
                      echo '<p>' . $row["COMMENTS"] . '</p>';
                      echo '</div>';
                      $rating_sum = $rating_sum + $row["RATING"];
                      $num_reviews = $num_reviews + 1;
                    }
                    if ($num_reviews > 0) {
                      $average_rating = $rating_sum / $num_reviews;
                    } else {
                      $average_rating = 0;
                    }
                    echo '<h3 class="w3-border-amber">OVERALL RATING (full mark: 5): ' . $average_rating . '</h3>';
                    ?>
                </div>

                <br>
                <!---YOUR OWN COMMENTS SECTION-->

                <?php
                echo '<a href="9review.php?productID=' . $product_id . '"><button class="comment-button">Write Your Own Ratings & Comments</button></a>'
                ?>


            </div>

            <!--CONTACT SECTION-->
            <div id="contact" class="w3-container w3-margin-top-20-percent w3-cursive">
                <h2 class="w3-border-bottom w3-border-amber" style="border-width: 3px !important;">CONTACT FOR FURTHER INQUIRIES</h2>
                <div class="w3-margin-top-2" style="font-weight: 500;">
                    <p>For Further inquiries, please contact <?php echo $store; ?> for more details.</p>
                    <p>Or contact us through</p>
                    <p>Phone number: +0123456789</p>
                    <p>E-mail: oss@email.hk.com</p>
                </div>
            </div>

            <!--END OF CV SECTION-->
        </div>
    </section>

    <script>
        // Function to toggle mobile navigation
        function toggleNavigation() {
            let nav = document.getElementById("mobile-nav");
            if (nav.classList.contains('w3-show')) {
                nav.classList.remove('w3-show');
            } else {
                nav.classList.add('w3-show');
            }
        }
    </script>
    <script>
        // Script to load feather icons
        feather.replace()
    </script>
    <script>
        // When the user scrolls the page, execute myFunction
        window.onscroll = function() {myFunction()};

        // Get the navbar
        var navbar = document.getElementById("navbar");
        var sidebar = document.getElementById("sidebar");

        // Get the offset position of the navbar
        var sticky = navbar.offsetTop;

        // Add the sticky class to the navbar when you reach its scroll position. Remove "sticky" when you leave the scroll position
        function myFunction() {
            if (window.pageYOffset >= sticky) {
                navbar.classList.add("sticky");
                sidebar.style.margin = "47px 0px";
            } else {
                navbar.classList.remove("sticky");
                sidebar.style.margin = "0px 0px";
            }
        }
    </script>
</body>
</html>
