<?php

include '../../config.php';
try {
    

    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT ITEM_NAME, SUM(ITEM_TALLY) as total_quantity, SUM(PRICE * ITEM_TALLY) as total_revenue 
            FROM Items 
            WHERE ITEM_STATUS = '1' 
            GROUP BY ITEM_NAME";
    
    $stmt = $pdo->query($sql);

    echo "<table border='1'>
            <tr>
                <th>Item Name</th>
                <th>Total Quantity Sold</th>
                <th>Total Revenue</th>
            </tr>";

            

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['ITEM_NAME']}</td>";
        echo "<td>{$row['total_quantity']}</td>";
        echo "<td>{$row['total_revenue']}</td>";
        echo "</tr>";
    }

    echo "</table>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
