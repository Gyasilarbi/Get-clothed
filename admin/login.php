<?php
// session_start();
include "../config.php";

$email = $password = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $_SESSION["status"] = true;
        $_SESSION["message"] = "Mandatory fields are required";
        header("Location: login.view.php");
        die();
    } else {
        $email = test_input($_POST["email"]);
        $password = $_POST["password"];

        $sql = "SELECT * FROM admins WHERE EMAIL = :email AND PWD = :password LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);

        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $_SESSION["email"] = $email;
                $_SESSION["status"] = false;
                $_SESSION["message"] = "Login successful";
                header("Location: index.php");
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
