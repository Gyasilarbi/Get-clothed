<?php
include '../config.php'; // Include your database connection code here

if (isset($_GET['blockid'])) {
    $CustomerCode = $_GET['blockid'];
    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQL query to update the database (replace 'your_table' and 'your_item_id' with actual values)
        $sql = "UPDATE customers SET CUSTOMER_STATUS = '0' WHERE CUSTOMER_NO = :customer_no";
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':customer_no', $CustomerCode);
        //    $stmt->bindParam(':itemstatus', '0');


        // var_dump($CustomerCode,$item_color,$item_name);
        // exit;
        // Execute the statement
        $stmt->execute();
        $status = $stmt->execute();

        if ($status) {
            // After successful delete, set a session variable with a success message
            $_SESSION['block_message'] = "Customer blocked!";
            header("location: index.php");
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
