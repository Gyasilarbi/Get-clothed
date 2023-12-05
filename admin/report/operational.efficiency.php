<?php

include '../..config.php';
try {

    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Assuming you have a table named operation_logs with columns id, operation_type, timestamp
    $sql = "SELECT operation_type, COUNT(*) as operation_count 
            FROM operation_logs 
            GROUP BY operation_type";
    
    $stmt = $pdo->query($sql);

    echo "<table border='1'>
            <tr>
                <th>Operation Type</th>
                <th>Operation Count</th>
            </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
