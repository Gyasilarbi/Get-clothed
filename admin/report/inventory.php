<?php
include '../../config.php';
try {

    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT ITEM_NAME, ITEM_TYPE, ITEM_COLOR, SUM(ITEM_TALLY) as total_quantity 
            FROM items 
            WHERE ITEM_STATUS = '1' 
            GROUP BY ITEM_NAME, ITEM_TYPE, ITEM_COLOR";

    $stmt = $pdo->query($sql);

    // Include the HTML header
    include 'header.php';

    echo "<a href='../analytics.php'><button class='btn btn-danger' style='position: fixed;'>Close</button></a><br>"; 


    echo "<h2>Item Inventory</h2>";
    echo "<table>";
    echo "<tr>
            <th>Item Name</th>
            <th>Item Type</th>
            <th>Item Color</th>
            <th>Total Quantity</th>
            <th>In Stock</th>
          </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['ITEM_NAME']}</td>";
        echo "<td>{$row['ITEM_TYPE']}</td>";
        echo "<td>{$row['ITEM_COLOR']}</td>";
        echo "<td>{$row['total_quantity']}</td>";

        // You can customize the condition for in stock based on your business logic
        $inStock = ($row['total_quantity'] > 0) ? 'Yes' : 'No';
        echo "<td>{$inStock}</td>";

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
    <title>Document</title>
</head>
<style>
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

    tr:nth-child(even) {
        background-color: #f9f9f9;
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

    /* Add more styling as needed */
</style>

<body>

</body>

</html>