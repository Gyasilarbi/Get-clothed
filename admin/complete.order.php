<?php
include '../config.php'; // Include your database connection code here

if (isset($_GET['doneid'])) {
    $order_no = $_GET['doneid'];
    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE orders SET ORDER_STATUS = '1' WHERE ORDER_NO = :order_no";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':order_no', $order_no);

        $status = $stmt->execute();

        if ($status) {

            $sql1 = "SELECT * FROM orders WHERE ORDER_NO = '$order_no' LIMIT 1";

            $stmt1 = $pdo->query($sql1);
            $row = $stmt1->fetch(PDO::FETCH_ASSOC);
            $order_no = $row['ORDER_NO'];
            $customer_no = $row['CUSTOMER_NO'];
            $paymentMethod = $row['PAYMENTMETHOD'];
            $address = $row['ADDRESS'];
            $datetime = $row['DATE_TIME'];  
        }
        
        $sql2 = "INSERT INTO deliveries (ORDER_NO, PAYMENTMETHOD, ADDRESS, CUSTOMER_NO, DATETIME) VALUES ('$order_no', '$paymentMethod',  '$address', '$customer_no', '$datetime')";

        $stmt2 = $pdo->prepare($sql2);
        $status1 = $stmt2->execute();
        header("location: index.php");
        die();
        // } else {
        //     echo "123";
        //     var_dump($stmt->errorInfo());
        //     exit;
        // }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
