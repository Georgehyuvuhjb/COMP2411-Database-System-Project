<?php
require_once "session_customer_log_out.php";
require_once "config.php";

$username = $_SESSION["username"];

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Retrieve product data from the database
$query = 'SELECT * FROM Products';
$stid = oci_parse($conn, $query);
oci_execute($stid);

// Retrieve average ratings for all products
$query_ratings = "SELECT Product_ID, AVG(rating) AS avg_rating FROM Review GROUP BY Product_ID";
$stid_ratings = oci_parse($conn, $query_ratings);
oci_execute($stid_ratings);
$averageRatings = array();
while ($row = oci_fetch_array($stid_ratings, OCI_ASSOC)) {
    $averageRatings[$row['PRODUCT_ID']] = $row['AVG_RATING'];
}

// Retrieve the store name of all products
$query_stores = "SELECT p.Product_ID, s.store_name FROM Products p, Store s WHERE p.Store_ID = s.Store_ID";
$stid_stores = oci_parse($conn, $query_stores);
oci_execute($stid_stores);
$stores = array();
while ($row = oci_fetch_array($stid_stores, OCI_ASSOC)) {
    $stores[$row['PRODUCT_ID']] = $row['STORE_NAME'];
}

// Display the retrieved product data dynamically
function displayProductCard($product, $averageRatings, $stores)
{
    echo '<div class="product-card">';
    $productId = $product['PRODUCT_ID'];
    echo '<a href="8productpage.php?productID=' . $productId . '"><h4>' . $product['PRODUCT_NAME'] . '</h4></a>';
    echo $product['PRODUCT_BRAND'] . '<br>';
    echo '$' . $product['CURRENT_SELLING_PRICE'] . '<br>';
    echo ($stores[$productId] ?? 'N/A') . '<br>';
    echo 'Average rating: ' . ($averageRatings[$productId] ?? 'N/A');
    echo '</div>';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - OSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--external stylesheets-->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">

    <style>
        .container {
            width: 80%;
            margin: auto;
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
        }
        .product-card {
            margin-right: 80px;
            background-color: lightcoral;
            padding: 20px;
        }

        .product-card2 {
            margin-right: 80px;
            background-color: lightskyblue;
            padding: 20px;
        }

        .product-card3 {
            margin-right: 80px;
            background-color: lightseagreen;
            padding: 20px;
        }

        .product-card4 {
            margin-right: 80px;
            background-color: mediumpurple;
            padding: 20px;
        }

        .product-card5 {
            margin-right: 80px;
            background-color: lightpink;
            padding: 20px;
        }

        .product-card6 {
            margin-right: 80px;
            background-color: lightyellow;
            padding: 20px;
        }

        h2, h3, h4 {
        text-align: center
        }

        @keyframes fade {
            0% {
                opacity: 0;
            }
            25% {
                opacity: 1;
            }
            75% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }

        .mySlides {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .mySlides img {
            width: 50%;
            display: block;
            margin: 0 auto;
        }

        .slideshow-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Adjust this value as needed */
        }

        .video-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        body{
            background-image: url("images/Nightsky.jpeg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: top;
        }

        .search-bar {
            width: 75%;
            margin: 0 auto;
            padding: 10px;
            margin-left: 120px;
            border: none;
            border-radius: 10px;
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
            font-size: 16px;
            color: #333;
            display: inline-block;
        }

        .search-bar:focus {
            outline: none;
            background-color: #fff;
        }

        .custom-button {
            background-color: #4CAF50; /* Green background color */
            border: none; /* Remove border */
            color: white; /* White text color */
            padding: 10px 20px; /* Add padding */
            text-align: center; /* Center text */
            text-decoration: none; /* Remove underline */
            display: inline-block; /* Display as inline block */
            font-size: 16px; /* Set font size */
            border-radius: 5px; /* Add border radius */
            cursor: pointer; /* Add cursor pointer */
        }

        .my-button {
            display: block; /* Ensures the button takes up the full width of the container */
            margin: 0 auto; /* Centers the button horizontally */
            width: 80%; /* Sets the button width to 90% of the screen */
            margin-left:120px;
            padding: 5px; /* Adds padding around the button for better visual appeal */
            background-color: #007bff; /* Sets the background color of the button */
            color: #fff; /* Sets the text color of the button */
            border: none; /* Removes the default button border */
            text-align: center; /* Centers the text within the button */
            text-decoration: none; /* Removes underline from the button text */
            font-size: 14px; /* Sets the font size of the button text */
            font-weight: bold; /* Sets the font weight of the button text */
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div class="logo">
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
                <a href="logout.php">Sign Out</a>
            </div>
        </div>
        <div class="cart-button">
            <a href="10cart.php"><i class="fa fa-shopping-cart"></i></a>
        </div>
    </div>

    <h2 style="color:white">Welcome to OSS!</h2>
    <form action="7productlist.php" method="GET">
        <input type="text" class="search-bar" placeholder="Search by product name..." name="search">
        <button type="submit" class="custom-button"><i class="fa fa-search"></i></button>
    </form>
    <br><br>
    <a href="7productlist.php"><button class="my-button">Want to Search By Category, Price Range, Brand and Many Others? Click Here!</button></a>
    <br>

    <h4 style="color:white">---------------------------------------------- Hot products ----------------------------------------------</h4>

    <div class="container" id="container">
        <?php
        while ($row = oci_fetch_array($stid, OCI_ASSOC + OCI_RETURN_NULLS)) {
            displayProductCard($row, $averageRatings, $stores);
        }
        ?>
    </div>
    
    <!-- <h4 style="color:white">---------------------------------------------- On Sale ----------------------------------------------</h4>
    <div class="container" name="container">
    <div class="product-card4">
        <a href="8productpage.html"><h4>Cooler hat</h4></a>
        Cool brand<br>
        $999.99<br>
        Secret store<br>
        Average rating: 1.2
    </div> -->

    <?php
    // oci_close($conn);
    ?>

    <script>

        function addScroll(element) {
        const scrollWidth = element.scrollWidth;
        window.addEventListener('load', () => {
          self.setInterval(() => {
            if (element.scrollLeft !== scrollWidth) {
              element.scrollTo(element.scrollLeft + 1, 0);
            }
          }, 60);
        });
        }

        let containers = document.getElementsByClassName('container');
        Array.prototype.forEach.call(containers, (element) => addScroll(element));

    </script>
    <div class="video-container">
        <video width="1000" height="auto" autoplay muted loop>
            <source src="images/VideoCustomerHome.mp4">
            Your browser does not support the video tag.
        </video>
    </div>
    <div class="slideshow-container">
        <div class="mySlides">
            <img src="images/First1.png" style="width:84%">
        </div>

        <div class="mySlides">
            <img src="images/Admin5.png" style="width:86%">
        </div>

        <div class="mySlides">
            <img src="images/drone_shipping.jpg" style="width:60%">
        </div>
        <script src="script.js"></script>
    </div>
</body>
</html>
