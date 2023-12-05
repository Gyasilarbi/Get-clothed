<!-- <?php

include '../../config.php';
try {

    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Assuming you have a customers table and an orders table
    $sql = "SELECT c.customer_id, c.customer_name, SUM(i.PRICE * o.ITEM_TALLY) as total_spending
            FROM customers c
            JOIN orders o ON c.customer_id = o.customer_id
            JOIN items i ON o.product_code = i.PRODUCT_NO
            GROUP BY c.customer_id, c.customer_name
            ORDER BY total_spending DESC";
    
    $stmt = $pdo->query($sql);

    echo "<table border='1'>
            <tr>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Total Spending</th>
            </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['customer_id']}</td>";
        echo "<td>{$row['customer_name']}</td>";
        echo "<td>{$row['total_spending']}</td>";
        echo "</tr>";
    }

    echo "</table>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> -->

<?php echo "not done"; ?>
