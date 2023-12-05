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

    echo "<table border='1'>
            <tr>
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
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
