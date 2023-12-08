<?php
include '../../config.php';

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT
                customers.CUSTOMER_NO,
                customers.`NAME`,
                customers.EMAIL,
                SUM(orders.TOTAL) AS TOTAL_SPENDING
            FROM
                customers
            INNER JOIN
                orders ON customers.`NAME` = orders.CUSTOMER_NAME
            GROUP BY
                customers.CUSTOMER_NO,
                customers.`NAME`,
                customers.EMAIL;";

    $stmt = $pdo->query($sql);

    // Include the HTML header
    include 'header.php';

    echo "<a href='../analytics.php'><button class='btn btn-danger' style='position: fixed;'>Close</button></a><br>";


    echo "<h2>Customer Spending Report</h2>";
    echo "<table>";
    echo "<tr>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Total Spending (GH₵)</th>
          </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['CUSTOMER_NO']}</td>";
        echo "<td>{$row['NAME']}</td>";
        echo "<td>{$row['EMAIL']}</td>";
        echo "<td>{$row['TOTAL_SPENDING']}</td>";
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
    </style>
</head>

<body>

</body>

</html>