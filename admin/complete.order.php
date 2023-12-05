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
