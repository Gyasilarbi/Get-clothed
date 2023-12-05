<?php

include 'config.php';

if (isset($_GET['addCartid'])) {
    $productCode = $_GET["addCartid"];
    try{
        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

         // SQL query to update the database (replace 'your_table' and 'your_item_id' with actual values)
         $sql = "UPDATE Items SET IN_CART = '1' WHERE PRODUCT_NO = :product_no";
         // Prepare the SQL statement
         $stmt = $pdo->prepare($sql);
 
         // Bind parameters
         $stmt->bindParam(':product_no', $productCode);
        
         $stmt->execute();
         $status = $stmt->execute();

        if ($status) {
            header("location: cart.php");
            // echo "done";
            die;
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>