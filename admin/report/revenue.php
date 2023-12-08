<?php
include '../../config.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT
    Items.ITEM_NAME,
    Items.PRODUCT_NO,
    Items.ITEM_TYPE,
    Items.ITEM_TALLY,
    SUM(sales.QUANTITY) AS QUANTITY_SOLD,
    sales.PRODUCT_NAME,
    SUM(sales.TOTAL) AS TOTAL_SALES
FROM
    Items
INNER JOIN
    sales ON Items.ITEM_NAME = sales.PRODUCT_NAME
GROUP BY
    Items.ITEM_NAME,
    Items.PRODUCT_NO,
    Items.ITEM_TYPE,
    Items.ITEM_TALLY,
    sales.PRODUCT_NAME";

    $stmt = $pdo->query($sql);

    // Include the HTML header
    include 'header.php';

    echo "<a href='../analytics.php'><button class='btn btn-danger' style='position: fixed;'>Close</button></a><br>"; 


    echo "<h2>Item Sales Report</h2>";
    echo "<table>";
    echo "<tr>
            <th>Item Name</th>
            <th>Product Number</th>
            <th>Product Type</th>
            <th>Item Tally</th>
            <th>Total Quantity Sold</th>
            <th>Total Revenue (GH₵)</th>
          </tr>";

    $totalRevenue = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['ITEM_NAME']}</td>";
        echo "<td>{$row['PRODUCT_NO']}</td>";
        echo "<td>{$row['ITEM_TYPE']}</td>";
        echo "<td>{$row['ITEM_TALLY']}</td>";
        echo "<td>{$row['QUANTITY_SOLD']}</td>";
        echo "<td>{$row['TOTAL_SALES']}</td>";
        echo "</tr>";

        $totalRevenue += $row['TOTAL_SALES'];
    }

    // Display total revenue in a separate row at the bottom
    echo "<tr>
            <td colspan='2'><strong>Total Revenue</strong></td>
            <td><strong>GH₵ {$totalRevenue}.00</strong></td>
          </tr>";

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
    <title>Item Sales Report</title>
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
        margin-top: 20px;
    }

    th,
    td {
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