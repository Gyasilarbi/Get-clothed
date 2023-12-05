<?php
include '../../config.php';

try {

    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Sales Report
    $salesSql = "SELECT ITEM_NAME, SUM(ITEM_TALLY) as total_quantity, SUM(PRICE * ITEM_TALLY) as total_revenue 
                FROM Items
                WHERE ITEM_STATUS = '1' 
                GROUP BY ITEM_NAME";
    
    $salesStmt = $pdo->query($salesSql);

    // Inventory Report
    $inventorySql = "SELECT ITEM_NAME, ITEM_TYPE, ITEM_COLOR, SUM(ITEM_TALLY) as total_quantity 
                    FROM Items
                    WHERE ITEM_STATUS = '1' 
                    GROUP BY ITEM_NAME, ITEM_TYPE, ITEM_COLOR";
    
    $inventoryStmt = $pdo->query($inventorySql);

    // // Revenue Report
    // $revenueSql = "SELECT ITEM_NAME, SUM(ITEM_TALLY) as total_quantity, SUM(PRICE * ITEM_TALLY) as total_revenue 
    //             FROM Items
    //             WHERE ITEM_STATUS = '1' 
    //             GROUP BY ITEM_NAME";
    
    // $revenueStmt = $pdo->query($revenueSql);

    // // Operational Efficiency Report
    // $operationSql = "SELECT operation_type, COUNT(*) as operation_count 
    //                 FROM operation_logs 
    //                 GROUP BY operation_type";
    
    // $operationStmt = $pdo->query($operationSql);

    echo "<h1>Sales Report</h1>";
    echo "<table border='1'>
            <tr>
                <th>Item Name</th>
                <th>Total Quantity Sold</th>
                <th>Total Revenue</th>
            </tr>";

    while ($row = $salesStmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['ITEM_NAME']}</td>";
        echo "<td>{$row['total_quantity']}</td>";
        echo "<td>{$row['total_revenue']}</td>";
        echo "</tr>";
    }

    echo "</table>";

    echo "<h1>Inventory Report</h1>";
    echo "<table border='1'>
            <tr>
                <th>Item Name</th>
                <th>Item Type</th>
                <th>Item Color</th>
                <th>Total Quantity</th>
                <th>In Stock</th>
            </tr>";

    while ($row = $inventoryStmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['ITEM_NAME']}</td>";
        echo "<td>{$row['ITEM_TYPE']}</td>";
        echo "<td>{$row['ITEM_COLOR']}</td>";
        echo "<td>{$row['total_quantity']}</td>";
        $inStock = ($row['total_quantity'] > 0) ? 'Yes' : 'No';
        echo "<td>{$inStock}</td>";
        echo "</tr>";
    }

    echo "</table>";

    echo "<h1>Revenue Report</h1>";
    echo "<table border='1'>
            <tr>
                <th>Item Name</th>
                <th>Total Quantity Sold</th>
                <th>Total Revenue</th>
            </tr>";

    while ($row = $revenueStmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['ITEM_NAME']}</td>";
        echo "<td>{$row['total_quantity']}</td>";
        echo "<td>{$row['total_revenue']}</td>";
        echo "</tr>";
    }

    echo "</table>";

    echo "<h1>Operational Efficiency Report</h1>";
    echo "<table border='1'>
            <tr>
                <th>Operation Type</th>
                <th>Operation Count</th>
            </tr>";

    while ($row = $operationStmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['operation_type']}</td>";
        echo "<td>{$row['operation_count']}</td>";
        echo "</tr>";
    }

    echo "</table>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
