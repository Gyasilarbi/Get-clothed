<?php

include '../config.php';

if (isset($_GET['orderid'])) {
    $order_no = $_GET['orderid'];

    try {
        include '../config.php';

        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM orderDetails WHERE ORDER_NO = :order_no";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':order_no', $order_no);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $totalSum = 0; // Initialize the variable to store the sum of TOTAL values

            echo "<h2>Order Details for Order ID: <strong>$order_no</strong></h2>";

            // Display all rows with the same $order_no
            echo "<table class='table'>";
            echo "<thead><tr><th>Product ID</th><th>Product Name</th><th>Product Image</th><th>Customer No</th><th>Price</th><th>Quantity</th><th>Total</th><th>Payment Method</th></tr></thead>";
            echo "<tbody>";

            // Loop through each row
            do {
                echo "<tr>";
                echo "<td>{$result['PRODUCT_ID']}</td>";
                echo "<td>{$result['PRODUCT_NAME']}</td>";
                echo "<td><img src='{$result['PRODUCT_IMAGE']}' width='20%' /></td>";
                echo "<td>{$result['CUSTOMER_NO']}</td>";
                echo "<td>GH₵: {$result['UNIT_PRICE']}</td>";
                echo "<td><strong>{$result['QUANTITY']}</strong></td>";
                echo "<td>GH₵: {$result['TOTAL']}</td>";
                echo "<td>{$result['PAYMENTMETHOD']}</td>";
                echo "</tr>";

                // Accumulate the TOTAL values
                $totalSum += $result['TOTAL'];
            } while ($result = $stmt->fetch(PDO::FETCH_ASSOC));

            echo "</tbody>";

            // Display the row for the total sum
            echo "<tfoot><tr><td colspan='6'></td><td>Total Sum: </td><td><strong>GH₵ {$totalSum}</strong></td></tr></tfoot>";

            echo "</table>";
        } else {
            // echo "No values found in the database for Order ID: $order_no";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="txt" style="margin: 20px;">
        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#activateModal<?php echo $order_no ?>">Done</button> -->
        <a href="complete.order.php?doneid=<?php echo $order_no ?>"><button class="btn btn-success">Complete</button></a>
    </div>

    <div class="modal" id="activateModal<?php echo $order_no ?>">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Complete Order</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <a href="complete.order.php?doneid=<?php echo $order_no ?>"><button class="btn btn-success">Complete</button></a>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
</body>

</html>