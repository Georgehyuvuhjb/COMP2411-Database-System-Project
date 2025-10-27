<?php

require_once "session_admin_log_out.php";
require_once "config.php";

$adminId = $_SESSION["adminId"];
$store_query = "SELECT * FROM Store WHERE Store.Admin_ID = :adminId";
$store_stmt = oci_parse($conn, $store_query);
oci_bind_by_name($store_stmt, ':adminId', $adminId);
oci_execute($store_stmt);
$storeRow = oci_fetch_assoc($store_stmt);
$storeId = $storeRow["STORE_ID"];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
    <script src="https://unpkg.com/feather-icons@4.28.0/dist/feather.min.js"></script>
</head>


<body>
    <!--Navigation Bar-->
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

    <!-- Page 19 -->
    <div class="" style="margin: 20px;">

      <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid lightgray;
            padding: 8px;
            text-align: left;
        }
      </style>

      <h1>Report</h1>

      <h3> Sales Report by Product </h3>
      <p>Find the most popular products based on quantity sold:</p>
      <?php

      $query = "SELECT
          Products.Product_ID,
          Products.product_name,
          SUM(Contain_Order.buying_quantity) AS Total_Quantity_Sold,
          SUM(Contain_Order.buying_quantity * Contain_Order.selling_price) AS Total_Revenue
      FROM
          Contain_Order
      JOIN
          Products ON Contain_Order.Product_ID = Products.Product_ID
      WHERE
          Products.Store_ID = :storeId
      GROUP BY
          Products.Product_ID,
          Products.product_name
      ORDER BY
          Total_Quantity_Sold DESC";

      $stmt = oci_parse($conn, $query);
      oci_bind_by_name($stmt, ':storeId', $storeId);
      oci_execute($stmt);

      echo '<table>
              <tr>
                  <th>Product Name</th>
                  <th>Total Quantity Sold</th>
                  <th>Total Revenue</th>
              </tr>';
      while ($row = oci_fetch_assoc($stmt)) {
          echo '<tr>
                  <td>' . $row['PRODUCT_NAME'] . '</td>
                  <td>' . $row['TOTAL_QUANTITY_SOLD'] . '</td>
                  <td>' . $row['TOTAL_REVENUE'] . '</td>
              </tr>';
      }
      echo '</table>';
      ?>

      <h3> Sales Trend Over Time </h3>
      <p>Analyze sales trends over a period (e.g., monthly):</p>

      <?php
      $query = "SELECT TO_CHAR(Orders.order_date, 'YYYY-MM') AS Month,
          SUM(Contain_Order.buying_quantity * Contain_Order.selling_price) AS Monthly_Sales
        FROM Contain_Order, Products, Orders
        WHERE Contain_Order.Order_ID = Orders.Order_ID
          AND Products.Product_ID = Contain_Order.Product_ID
          AND Products.Store_ID = :storeId
        GROUP BY TO_CHAR(Orders.order_date, 'YYYY-MM')
        ORDER BY Month";

      $stmt = oci_parse($conn, $query);
      oci_bind_by_name($stmt, ':storeId', $storeId);
      oci_execute($stmt);

      echo '<table>
              <tr>
                  <th>Month</th>
                  <th>Total Revenue</th>
              </tr>';
      while ($row = oci_fetch_assoc($stmt)) {
          echo '<tr>
                  <td>' . $row['MONTH'] . '</td>
                  <td>' . $row['MONTHLY_SALES'] . '</td>
              </tr>';
      }
      echo '</table>';
      ?>

      <h3> User Purchase Behavior </h3>
      <p>Analyze purchase behavior of users:</p>

      <?php
      $query = "SELECT Users.User_Name, Users.actual_name,
          COUNT(DISTINCT Orders.Order_ID) AS Number_of_Orders,
          SUM(Contain_Order.buying_quantity) AS Total_Products_Purchased
        FROM Users, Orders,Contain_Order, Products
        WHERE Users.User_Name = Orders.User_Name AND Orders.Order_ID = Contain_Order.Order_ID
          AND Products.Product_ID = Contain_Order.Product_ID AND Products.Store_ID = :storeId
        GROUP BY Users.User_Name, Users.actual_name
        ORDER BY Total_Products_Purchased DESC";

      $stmt = oci_parse($conn, $query);
      oci_bind_by_name($stmt, ':storeId', $storeId);
      oci_execute($stmt);

      echo '<table>
              <tr>
                  <th>Username</th>
                  <th>Actual Name</th>
                  <th>Total Number of Orders</th>
                  <th>Total Number of Products Purchased</th>
              </tr>';
      while ($row = oci_fetch_assoc($stmt)) {
          echo '<tr>
                  <td>' . $row['USER_NAME'] . '</td>
                  <td>' . $row['ACTUAL_NAME'] . '</td>
                  <td>' . $row['NUMBER_OF_ORDERS'] . '</td>
                  <td>' . $row['TOTAL_PRODUCTS_PURCHASED'] . '</td>
              </tr>';
      }
      echo '</table>';
      ?>

      <h3> Category-Wise Sales Report </h3>
      <p>Analyze sales by product categories: </p>

      <?php
      $query = "SELECT Category.Category_ID, Category.category_name,
        SUM(Contain_Order.buying_quantity) AS Total_Quantity_Sold
      FROM Contain_Order, Products, Product_Belong_To, Category
      WHERE Contain_Order.Product_ID = Products.Product_ID
        AND Product_Belong_To.Product_ID =  Products.Product_ID
        AND Product_Belong_To.Category_ID = Category.Category_ID
        AND Products.Store_ID = :storeId
      GROUP BY Category.Category_ID, Category.category_name
      ORDER BY Total_Quantity_Sold DESC";

      $stmt = oci_parse($conn, $query);
      oci_bind_by_name($stmt, ':storeId', $storeId);
      oci_execute($stmt);

      echo '<table>
              <tr>
                  <th>Category</th>
                  <th>Total Quantity Sold</th>
              </tr>';
      while ($row = oci_fetch_assoc($stmt)) {
          echo '<tr>
                  <td>' . $row['CATEGORY_NAME'] . '</td>
                  <td>' . $row['TOTAL_QUANTITY_SOLD'] . '</td>
              </tr>';
      }
      echo '</table>';
      ?>

      <h3> Customer Lifetime Value (CLV) </h3>
      <p>Estimate the value a customer brings over their lifetime:</p>

      <?php
      $query = "SELECT Users.User_Name, Users.actual_name,
          SUM(Contain_Order.buying_quantity * Contain_Order.selling_price) AS Total_Spent,
          COUNT(DISTINCT Orders.Order_ID) AS Total_Orders,
          (SUM(Contain_Order.buying_quantity * Contain_Order.selling_price) / COUNT(DISTINCT Orders.Order_ID)) AS Average_Order_Value
        FROM Orders,  Users, Contain_Order, Products
        WHERE Orders.User_Name = Users.User_Name
          AND Contain_Order.Order_ID = Orders.Order_ID
          AND Contain_Order.Product_ID = Products.Product_ID
          AND Products.Store_ID = :storeId
        GROUP BY Users.User_Name, Users.actual_name
        ORDER BY Total_Spent DESC";

      $stmt = oci_parse($conn, $query);
      oci_bind_by_name($stmt, ':storeId', $storeId);
      oci_execute($stmt);

      echo '<table>
              <tr>
                  <th>Username</th>
                  <th>Actual Name</th>
                  <th>Total Amount Spent</th>
                  <th>Total Number of Orders</th>
                  <th>Average Order Value</th>
              </tr>';
      while ($row = oci_fetch_assoc($stmt)) {
          echo '<tr>
                  <td>' . $row['USER_NAME'] . '</td>
                  <td>' . $row['ACTUAL_NAME'] . '</td>
                  <td>' . $row['TOTAL_SPENT'] . '</td>
                  <td>' . $row['TOTAL_ORDERS'] . '</td>
                  <td>' . $row['AVERAGE_ORDER_VALUE'] . '</td>
              </tr>';
      }
      echo '</table>';
      ?>

      <h3> Inventory Turnover Rate </h3>
      <p>Analyze how often inventory is sold and replaced over a period:</p>

      <?php
      $query = "SELECT Products.Product_ID, Products.product_name,
        SUM(Contain_Order.buying_quantity) AS Total_Sold,
        Products.stock AS Current_Stock,
        (SUM(Contain_Order.buying_quantity) / Products.stock) AS Turnover_Rate
      FROM Products, Contain_Order
      WHERE Products.Product_ID = Contain_Order.Product_ID
        AND Products.Store_ID = :storeId
      GROUP BY Products.Product_ID, Products.product_name, Products.stock
      ORDER BY Turnover_Rate DESC";

      $stmt = oci_parse($conn, $query);
      oci_bind_by_name($stmt, ':storeId', $storeId);
      oci_execute($stmt);

      echo '<table>
              <tr>
                  <th>Product Name</th>
                  <th>Total Sold</th>
                  <th>Current Stock</th>
                  <th>Turnover Rate</th>
              </tr>';
      while ($row = oci_fetch_assoc($stmt)) {
          echo '<tr>
                  <td>' . $row['PRODUCT_NAME'] . '</td>
                  <td>' . $row['TOTAL_SOLD'] . '</td>
                  <td>' . $row['CURRENT_STOCK'] . '</td>
                  <td>' . $row['TURNOVER_RATE'] * 100 . '% </td>
              </tr>';
      }
      echo '</table>';
      ?>

      <h3> Discount Effectiveness </h3>
      <p>Analyze the effectiveness of discounts on sales:</p>

      <?php
      $query = "SELECT Products.Product_ID, Products.product_name, Products.discount,
        SUM(CASE WHEN Contain_Order.selling_price < Products.current_selling_price THEN Contain_Order.buying_quantity ELSE 0 END) AS Units_Sold_With_Discount,
        SUM(CASE WHEN Contain_Order.selling_price >= Products.current_selling_price THEN Contain_Order.buying_quantity ELSE 0 END) AS Units_Sold_Without_Discount
      FROM Products, Contain_Order, Orders
      WHERE Products.Product_ID = Contain_Order.Product_ID
        AND Products.Store_ID = :storeId
      GROUP BY Products.Product_ID,
        Products.product_name,
        Products.discount
      ORDER BY Products.discount DESC";

      $stmt = oci_parse($conn, $query);
      oci_bind_by_name($stmt, ':storeId', $storeId);
      oci_execute($stmt);

      echo '<table>
              <tr>
                  <th>Product Name</th>
                  <th>Current Discount</th>
                  <th>Products Sold With Discount</th>
                  <th>Products Sold Without Discount</th>
              </tr>';
      while ($row = oci_fetch_assoc($stmt)) {
          echo '<tr>
                  <td>' . $row['PRODUCT_NAME'] . '</td>
                  <td>' . (1 - $row['DISCOUNT']) * 100 . '% </td>
                  <td>' . $row['UNITS_SOLD_WITH_DISCOUNT'] . '</td>
                  <td>' . $row['UNITS_SOLD_WITHOUT_DISCOUNT'] . '</td>
              </tr>';
      }
      echo '</table>';
      ?>

    </div>

</body>
</html>
