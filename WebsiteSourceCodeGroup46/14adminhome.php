<?php 

require_once "session_admin_log_out.php";
require_once "config.php";

    // Retrieve the store name from the Store table using the Admin_ID
    $adminId = $_SESSION["adminId"];
    $storeQuery = "SELECT store_name FROM Store WHERE admin_id = :adminId";
    $storeStmt = oci_parse($conn, $storeQuery);
    oci_bind_by_name($storeStmt, ':adminId', $adminId);
    oci_execute($storeStmt);
    $storeRow = oci_fetch_assoc($storeStmt);
    $storeName = $storeRow['STORE_NAME'];
    // Display the admin home page with the store name
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Home - OSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <link rel="stylesheet" href="styles.css">
    <style>
        h1, h2, h3 {
        text-align: center;
        }

        .flex {
          display: flex;
          justify-content: center;
        }

        .flex-item + .flex-item {
          margin-left: 10px;
        }

        .carousel {
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: auto;
            gap: 20px;
            padding-top: 20px;
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

        .flex-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            background-color: hotpink;
            padding: 20px;
            max-width: 940px; /* Adjust this value as needed */
            margin-left: auto;
            margin-right: auto;
        }

        #video-background {
            position: fixed;
            right: 20;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -1;
        }
    </style>
    <link rel="stylesheet" href="AdminHomeStyle.css">
</head>
<body>
    <video id="video-background" autoplay loop muted>
        <source src="images/UniverseAdminHome.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
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

    <!-- Page 14-->
    <h2 style="text-align: center;color:white;">Manage <?php echo $storeName; ?></h2>
    <div class="flex-container" style="display: flex; justify-content: center; margin-top: 20px; background-color: darkslategray; padding: 20px;">
        <a href="15InventorySearch.php" style="text-decoration: none;">
            <button type="button" style="background-color: #4CAF50; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border: none;">
                <h3 style="margin: 0;">Manage Your Inventory</h3>
            </button>
        </a>
        <a href="19report.php" style="text-decoration: none;">
            <button type="button" style="background-color: #008CBA; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border: none;">
                <h3 style="margin: 0;">Report Generation</h3>
            </button>
        </a>
    </div>
    <div class="carousel" style="display: flex; overflow-x: auto; gap: 20px; padding-top: 20px;">
        <img src="images/drone_shipping.jpg" alt="Image 1" style="width: 300px; height: 200px; object-fit: cover;">
        <img src="images/OSS_Amazing1.png" alt="Image 2" style="width: 300px; height: 200px; object-fit: cover;">
        <img src="images/Promotion.jpg" alt="Image 3" style="width: 300px; height: 200px; object-fit: cover;">
        <!-- Add more images as needed -->
    </div>
    <br><br>
    <div class="mySlides">
        <img src="images/Admin4.png" style="width:74.5%">
    </div>

    <div class="mySlides">
        <img src="images/Admin5.png" style="width:74.5%">
    </div>

    <div class="mySlides">
        <img src="images/Admin2.png" style="width:74.5%">
    </div>
    <script src="script.js"></script>
</body>
</html>
