<?php
require_once "config.php";
require_once "session_customer_log_out.php";


if (isset($_POST['review'])) {

    $username = $_SESSION["username"];
    $productId = $_GET['productID'];

    $rating = $_POST['rating'];
    $comments = trim($_POST['comments']);

    // get the number of past reviews written by the user -- partial key for review
    $countQuery = "SELECT count(*) AS counting
    FROM Review R 
    WHERE  R.User_Name = :username AND R.Product_ID = :productId";

    $countStmt = oci_parse($conn, $countQuery);

    // Bind the parameters
    oci_bind_by_name($countStmt, ':productId', $productId);
    oci_bind_by_name($countStmt, ':username', $username);

    // Execute the statement
    oci_execute($countStmt);

    $countRow = oci_fetch_assoc($countStmt);
    $count = $countRow['COUNTING'] + 1;

    $addQuery = "INSERT INTO Review
    VALUES (:count, :rating, :comments, :username, :productId)";

    // Prepare the statement
    $addStmt = oci_parse($conn, $addQuery);

    // Bind the parameters
    oci_bind_by_name($addStmt, ':count', $count);
    oci_bind_by_name($addStmt, ':rating', $rating);
    oci_bind_by_name($addStmt, ':comments', $comments);
    oci_bind_by_name($addStmt, ':username', $username);
    oci_bind_by_name($addStmt, ':productId', $productId);

    // Execute the statement
    oci_execute($addStmt);

    $headerString = "Location: 8productpage.php?productID=" . $productId;

    // header($headerString);
    // exit;

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leave a Review - OSS</title>

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
            <h1>Your Experience Matters To Us.<br>We Appreciate Your Ratings & Reviews!</h1>
            <label for="rating"><h3>Your Rating For This Product: </h3>Bad</label>
            <input id="rating" name="rating" type="range" min="0" max="5" step="1">
            <label for="rating">Excellent</label>
            <br><br>
            <label for="comments"><h3>Your Comments For the Product:</h3></label>
            <textarea id="comments" name="comments" rows="7" cols="60" placeholder="(Optional)"></textarea>
            <br><br>
            <?php
            $productId = $_GET['productID'];
            echo "<a href='8productpage.php?productID=".$productId."'><button type='button'>Cancel</button></a>"
            ?>
            <input type="submit" name="review" value="Submit">
    </form>
    </div>
</body>
</html>