<?php

include '../../config.php';
try {

    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Assuming you have a customers table and an orders table
    $sql = "SELECT * FROM orders ORDER BY TOTAL ASC";
    
    $stmt = $pdo->query($sql);

    echo "<table border='1'>
            <tr>
                <th>Customer ID</th>
                
                <th>Total Spending</th>
            </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['CUSTOMER_NO']}</td>";
        // echo "<td>{$row['customer_name']}</td>";
        echo "<td>{$row['TOTAL']}</td>";
        echo "</tr>";
    }

    echo "</table>";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!-- <?php

include "../../config.php";

$pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Retrieve data from the database
$sql = "SELECT ORDER_NO, DATE_TIME, CUSTOMER_NAME, ADDRESS, TOTAL, PHONE, CUSTOMER_NO, PAYMENTMETHOD FROM your_table_name ORDER BY TOTAL DESC";
$result = $pdo->query($sql);

// Check if there are results
if ($result->rowCount() > 0) {
    // Print the report header
    echo "| " . str_pad("ORDER_NO", 10) . " | " . str_pad("DATE_TIME", 20) . " | " . str_pad("CUSTOMER_NAME", 20) . " | " . str_pad("ADDRESS", 20) . " | " . str_pad("TOTAL", 10) . " | " . str_pad("PHONE", 15) . " | " . str_pad("CUSTOMER_NO", 15) . " | " . str_pad("PAYMENTMETHOD", 20) . " |\n";
    echo str_repeat("-", 145) . "\n";

    // Print each row of data
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "| " . str_pad($row['ORDER_NO'], 10) . " | " . str_pad($row['DATE_TIME'], 20) . " | " . str_pad($row['CUSTOMER_NAME'], 20) . " | " . str_pad($row['ADDRESS'], 20) . " | " . str_pad($row['TOTAL'], 10) . " | " . str_pad($row['PHONE'], 15) . " | " . str_pad($row['CUSTOMER_NO'], 15) . " | " . str_pad($row['PAYMENTMETHOD'], 20) . " |\n";
    }
} else {
    echo "No results found";
}

?> -->


