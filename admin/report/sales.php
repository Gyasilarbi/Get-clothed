<!-- <?php

include '../../config.php';
try {


    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    // Fetch sales data from the database
    $sql = "SELECT PRODUCT_NAME, SUM(TOTAL) as total_sales FROM sales GROUP BY PRODUCT_NAME";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Close the database connection
    $pdo = null;

    // Generate a simple HTML report
    echo "<h2>Daily Sales Report</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Product Name</th><th>Total Sales</th></tr>";

    foreach ($salesData as $row) {
        echo "<tr>";
        echo "<td>" . $row['PRODUCT_NAME'] . "</td>";
        echo "<td>" . $row['total_sales'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<button class='btn btn-info' style='margin: 20px;' onclick='window.print()'><i class='fa fa-print' aria-hidden='true'></i>  Print</button>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?> -->


<?php
include '../../config.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch sales data from the database
    $sql = "SELECT PRODUCT_NAME, SUM(TOTAL) as total_sales FROM sales GROUP BY PRODUCT_NAME";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Close the database connection
    $pdo = null;

    // Include the HTML header
    include 'header.php';

    // Generate a simple HTML report
    echo "<a href='../analytics.php'><button class='btn btn-danger' style='position: fixed;'>Close</button></a><br>"; 
    echo "<h2>Daily Sales Report</h2>";
    echo "<table>";
    echo "<tr><th>Product Name</th><th>Total Sales</th></tr>";

    foreach ($salesData as $row) {
        echo "<tr>";
        echo "<td>" . $row['PRODUCT_NAME'] . "</td>";
        echo "<td>" . $row['total_sales'] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<button class='btn btn-info print-button' onclick='window.print()'><i class='fa fa-print' aria-hidden='true'></i> Print</button>";

    // Include the HTML footer
    include 'footer.php';

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Sales Report</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    body {
    font-family: 'Arial', sans-serif;
    margin: 20px;
}

h2 {
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

.print-button {
    margin: 20px;
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
}

.print-button:hover {
    background-color: #0056b3;
}

</style>
<body>

</body>
</html>

