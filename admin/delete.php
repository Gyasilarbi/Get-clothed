<?php
include '../config.php'; // Include your database connection code here

if (isset($_GET['deleteid'])) {
    $productCode = $_GET['deleteid'];
    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL query to update the database (replace 'your_table' and 'your_item_id' with actual values)
        $sql = "UPDATE Items SET ITEM_STATUS = '0' WHERE PRODUCT_NO = :product_no";
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':product_no', $productCode);
       
        $stmt->execute();
        $status = $stmt->execute();

        if ($status) {
            // After successful delete, set a session variable with a success message
            $_SESSION['delete_message'] = "Item deleted!";
            header("location: product.php");
            die();
        } else {
            var_dump($stmt->errorInfo());
            exit;
        }

        // echo "Deleted successfully";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
