<?php

include '../../config.php';
try {


    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Fetch sales data from the database
    $sql = "SELECT PRODUCT_ID, SUM(TOTAL) as total_sales FROM sales GROUP BY PRODUCT_ID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Close the database connection
    $pdo = null;

    // Generate a simple HTML report
    echo "<h2>Daily Sales Report</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product ID</th><th>Total Sales</th></tr>";

    foreach ($salesData as $row) {
        echo "<tr>";
        echo "<td>" . $row['PRODUCT_ID'] . "</td>";
        echo "<td>" . $row['total_sales'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<button class='btn btn-info' style='margin: 20px;' onclick='window.print()'><i class='fa fa-print' aria-hidden='true'></i>  Print</button>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
