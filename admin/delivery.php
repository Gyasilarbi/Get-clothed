<?php

include '../config.php';

if (isset($_GET['deliveredid'])) {
    $order_no = $_GET['deliveredid'];

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE deliveries SET DELIVERED = '1' WHERE ORDER_NO = :order_no";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':order_no', $order_no);
        $stmt->execute();

        $status = $stmt->execute();

        if ($status) {

            $_SESSION['delivery_message'] = "Item delivered!";
            header("location: index.php");
            die();
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>