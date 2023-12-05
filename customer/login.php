<?php
// session_start();
include "../config.php";

$phone = $password = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST['phone']) || empty($_POST['password'])) {
        $_SESSION["status"] = true;
        $_SESSION["message"] = "Mandatory fields are required";
        header("Location: login.view.php");
        die();
    } else {
        $phone = test_input($_POST["phone"]);
        $password = $_POST["password"];

        $sql = "SELECT * FROM customers WHERE PHONE = :phone AND PWD = :password LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":password", $password);
// var_dump($phone, $password);exit;
        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $datax = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION["phone"] = $phone;
                $_SESSION["customer_no"] = $datax['CUSTOMER_NO'];
                $_SESSION["name"] = $datax['NAME'];
                $_SESSION["email"] = $datax['EMAIL'];
                $_SESSION["phone"] = $datax['PHONE'];
                $_SESSION["address"] = $datax['ADDRESS'];
                $_SESSION["gender"] = $datax['GENDER'];
                $_SESSION["status"] = false;
                $_SESSION["message"] = "Login successful";
                header("Location: ../checkout.php");
                die();
            } else {
                $_SESSION["status"] = true;
                $_SESSION["message"] = "Login failed";
                header("Location: login.view.php");
                die();
            }
        } catch (PDOException $e) {
            $_SESSION["status"] = true;
            $_SESSION["message"] = "Database error: " . $e->getMessage();
            header("Location: login.view.php");
            die();
        }

    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
