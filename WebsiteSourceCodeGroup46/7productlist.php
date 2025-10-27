<?php
require_once "session_customer_log_out.php";
require_once "config.php";
$username = $_SESSION["username"];
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Retrieve the search criteria from the URL
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? [];
$low = $_GET['low'] ?? '';
$high = $_GET['high'] ?? '';
$brand = $_GET['brand'] ?? '';
$age = $_GET['age'] ?? 'All';
if ($low == '') {
  $low = 0;
}
if ($high == '') {
  $high = 999999;
}

// Prepare the query with placeholders
$query = "SELECT * FROM Products p, Product_Belong_To pb, Category c
WHERE p.Product_ID = pb.Product_ID
AND c.Category_ID = pb.Category_ID";
if (!empty($category)) {
    $categoryConditions = array();
    foreach ($category as $cat) {
        $categoryConditions[] = "c.Category_ID = " . $cat;
    }
    $categoryConditionsString = implode(" OR ", $categoryConditions);
    $query .= " AND ($categoryConditionsString)";
}
if ($search != '') {
    $query .= " AND p.Product_Name LIKE '%' || :search || '%'";
}
if ($brand != '') {
    $query .= " AND PRODUCT_BRAND = :brand";
}

if ($age != 'All') {
    $query .= " AND SUITABLE_AGE_RANGE = :age OR SUITABLE_AGE_RANGE = 'All' ";
}
$query .= " AND CURRENT_SELLING_PRICE >= :low AND CURRENT_SELLING_PRICE <= :high";

// Bind the parameter names and execute the query
$stmt = oci_parse($conn, $query);
if ($brand != '') {
    oci_bind_by_name($stmt, ':brand', $brand);
}
if ($age != 'All') {
    oci_bind_by_name($stmt, ':age', $age);
}
if ($search != '') {
    oci_bind_by_name($stmt, ':search', $search);
}
oci_bind_by_name($stmt, ':low', $low);
oci_bind_by_name($stmt, ':high', $high);
oci_execute($stmt);

// Retrieve the store name of all products
$query_stores = "SELECT p.Product_ID, s.store_name FROM Products p, Store s WHERE p.Store_ID = s.Store_ID";
$stid_stores = oci_parse($conn, $query_stores);
oci_execute($stid_stores);
$stores = array();
while ($row = oci_fetch_array($stid_stores, OCI_ASSOC)) {
    $stores[$row['PRODUCT_ID']] = $row['STORE_NAME'];
}

// Retrieve average ratings for all products
$query_ratings = "SELECT Product_ID, AVG(rating) AS avg_rating FROM Review GROUP BY Product_ID";
$stid_ratings = oci_parse($conn, $query_ratings);
oci_execute($stid_ratings);
$averageRatings = array();
while ($row = oci_fetch_array($stid_ratings, OCI_ASSOC)) {
    $averageRatings[$row['PRODUCT_ID']] = $row['AVG_RATING'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product List - OSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--external stylesheets-->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>

        .sidebar {
            position: fixed;
            top: 70px;
            height: 100%;
            margin: 0;
            padding: 10px;
            width: 200px;
            background-color: #f1f1f1;
            position: fixed;
            overflow: auto;
        }

        .content {
            margin-left: 220px;
        }

        .sidebar input[type='number']{
            width: 50px;
        }

        .products {
          display: grid;
          grid-template-columns: repeat(3, 1fr);
        }

        .product-card {
          padding: 2%;
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

<div class="sidebar" id="sidebar">
    <form action="#">
        <h4>Filters</h4>
        Select category<br>

        <?php
        $categoriesQuery = oci_parse($conn, "SELECT * FROM Category");
        oci_execute($categoriesQuery);
        while ($categoryRow = oci_fetch_assoc($categoriesQuery)) {
            echo '<input type="checkbox" name="category[]" value="' . $categoryRow['CATEGORY_ID'] . '">' . $categoryRow['CATEGORY_NAME'] . '<br>';
        }
        ?>

        <br>
        Price range<br>
        $<input type="number" name="low" id="low" size="5"> - <input type="number" name="high" id="high" size="5">
        <br><br>
        Brand<br>
        <input type="text" name="brand" size="15">
        <br><br>
        <label for="age">Age range:</label>
        <select name="age" id="age">
            <option value="All">All</option>
            <option value="<3"><3</option>
            <option value="3~10">3~10</option>
            <option value="11~17">11~17</option>
            <option value="18~64">18~64</option>
            <option value="65+">65+</option>
        </select>
        <br><br>
        <input type="submit" value = "Search">
        <br><br><br><br><br>
    </form>

</div>

<div class="content">

    <?php
    echo '<section class="products">';
    while ($row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS)) {
        echo '<div class="product-card">';
        echo '<a href="8productpage.php?productID=' . $row['PRODUCT_ID'] . '"><h4>' . $row["PRODUCT_NAME"] . '</h4></a>';
        echo $row["PRODUCT_BRAND"] . '<br>';
        echo '$' . $row["CURRENT_SELLING_PRICE"] . '<br>';
        echo ($stores[$row['PRODUCT_ID']] ?? 'N/A') . '<br>';
        echo 'Average rating: ' . ($averageRatings[$row['PRODUCT_ID']] ?? 'N/A') . '<br>';
        echo '</div>';
    }
    echo '</section>';
    ?>

</div>

</body>
</html>
