<?php
require_once "config.php";
require_once "session_admin_log_out.php";

if (isset($_POST['addproduct'])) {
    $productName = trim($_POST['product-name']);
    $brand = trim($_POST['brand']);
    $category = trim($_POST['category']);
    $descr = trim($_POST['descr']);
    $ageRange = $_POST['age_range'];
    $weight = $_POST['weight'];
    $length = $_POST['length'];
    $width = $_POST['width'];
    $depth = $_POST['depth'];
    $sellingPrice = $_POST['selling-price'];
    $factoryPrice = $_POST['factory-price'];
    $discount = $_POST['discount'];
    $quantity = $_POST['quantity'];

    $adminId = $_SESSION["adminId"];
    $storeQuery = "SELECT store_id FROM Store WHERE admin_id = :adminId";
    $storeStmt = oci_parse($conn, $storeQuery);
    oci_bind_by_name($storeStmt, ':adminId', $adminId);
    oci_execute($storeStmt);
    $storeRow = oci_fetch_assoc($storeStmt);
    $storeId = $storeRow['STORE_ID'];

    // Prepare the query to check the admin credentials
    $dimensions = $length . "x" . $width . "x" . $depth;
    $query = "INSERT INTO Products (product_name, product_description, product_brand, stock, out_of_factory_price, dimensions, weight, suitable_age_range, current_selling_price, discount,Store_ID)
	Values(:productName, :descr, :brand, :quantity, :factoryPrice, :dimensions, :weight, :ageRange, :sellingPrice, :discount , :storeId)";

    // Prepare the statement
    $stmt = oci_parse($conn, $query);

    // Bind the parameters
    oci_bind_by_name($stmt, ':productName', $productName);
    oci_bind_by_name($stmt, ':descr', $descr);
    oci_bind_by_name($stmt, ':brand', $brand);
    oci_bind_by_name($stmt, ':quantity', $quantity);
    oci_bind_by_name($stmt, ':factoryPrice', $factoryPrice);
    oci_bind_by_name($stmt, ':dimensions', $dimensions);
    oci_bind_by_name($stmt, ':weight', $weight);
    oci_bind_by_name($stmt, ':ageRange', $ageRange);
    oci_bind_by_name($stmt, ':sellingPrice', $sellingPrice);
    oci_bind_by_name($stmt, ':discount', $discount);
    oci_bind_by_name($stmt, ':storeId', $storeId);

    // Execute the statement
    oci_execute($stmt);


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

    $productIDQuery = "SELECT MAX(Product_ID) AS MAX_ID FROM Products";
    $productIDStmt = oci_parse($conn, $productIDQuery);
    oci_execute($productIDStmt);
    $row = oci_fetch_assoc($productIDStmt);
    $productId = $row['MAX_ID'];

    $categoryID = $categoryMap[$category];

    $catUpdateQuery = "INSERT INTO Product_Belong_To (Product_ID, Category_ID) 
    VALUES (:productID, :categoryID)";

    $catUpdateStmt = oci_parse($conn, $catUpdateQuery);
        
    oci_bind_by_name($catUpdateStmt, ':productID', $productId);
    oci_bind_by_name($catUpdateStmt, ':categoryID', $categoryID);
    oci_execute($catUpdateStmt);

    $headerURL = "Location: 16productdetail.php?productID=" . $productId;
    header($headerURL);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add product - OSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet" href="styles.css">
</head>

<!--Navigation Bar-->
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

    <!-- Page 18 -->
    <div class="content">
        <form action="" method="post">
            <h1>ADD MORE PRODUCTS</h1>

            <label for="product-name">Product Name: </label>
            <input type="text" id="product-name" name="product-name" required>
            <br>
            <label for="brand">Brand: </label>
            <input type="text" id="brand" name="brand" required>
            <br>

            <label for="category">Category:</label>
            <select id="category" name="category">
                <option value="Sports">Sports</option>
                <option value="Clothes">Clothes</option>
                <option value="Food">Food</option>
                <option value="Drinks">Drinks</option>
                <option value="Entertainment">Entertainment</option>
                <option value="Electronics">Electronics & High-tech</option>
                <option value="Outdoors">Outdoors</option>
                <option value="Furniture and Appliance">Furniture and Appliance</option>
                <option value="Books and Media">Books and Media</option>
                <option value="Office Supplies">Office Supplies</option>
            </select>

            <br>
            <label for="descr">Description: </label><br>
            <textarea id="descr" name="descr" rows="7" cols="60" placeholder="Please input the description of the product here" required></textarea>
            <br>

            <label for="age_range">Suitable age range: </label><br>
            <select id="age_range" name="age_range">
                <option value="<3">&lt;3</option>
                <option value="3~10">3~10</option>
                <option value="11~17">11~17</option>
                <option value="18~64">18~64</option>
                <option value="65+">65+</option>
                <option value="All" selected>All</option>
            </select><br>
            <label for="weight">Weight (kg): </label><br>
            <input id="weight" name="weight" type="number" min="0"><br>
            <label>Dimensions (length, width, depth): </label><br>
            <input id="length" name="length" type="number" min="0"> x
            <input id="width" name="width" type="number" min="0"> x
            <input id="depth" name="depth" type="number" min="0"><br>
            <br>
            <label for="selling-price">Selling Price: </label>
            <input id="selling-price" name="selling-price" type="number" min="0" required><br>
            <label for="price1">Factory Price: </label>
            <input id="factory-price" name="factory-price" type="number" min = "0" required><br>
            <label for="discount">Discount: </label>
            <input id="discount" name="discount" type="number" step="0.01" max = "1" required><br>
            <label for="quantity">Quantity: </label>
            <input id="quantity" name="quantity" type="number" step="1" pattern = "[0-9]" min = "0" required><br>


            <br><br>
            <a href="15InventorySearch.php"><button type="button">Cancel</button></a>
            <input type="submit" name="addproduct" value="Confirm">
        </form>
    </div>
</body>
</html>