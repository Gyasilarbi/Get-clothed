<?php
include '../config.php'; // Include your database connection code here

if (isset($_GET['activateid'])) {
    $productCode = $_GET['activateid'];
    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE Items SET ITEM_STATUS = '1' WHERE PRODUCT_NO = :product_no";
      
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':product_no', $productCode);
        
        $stmt->execute();
        $status = $stmt->execute();

        if ($status) {
            
            $_SESSION['activate_message'] = "Item Activated!";
            header("location: product.php");
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

