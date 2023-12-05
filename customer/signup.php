<?php 

include '../config.php';
include "library.php";

$name = $email = $address =$gender = $phone = $confirm_password = $password= "";
$nameErr = $emailErr = $addressErr = $genderErr = $phoneErr = $confirm_passwordErr = "";

//Check if request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
    }

    if (empty($_POST["phone"])) {
        $phoneErr = "Phone is required";
    } else {
        $phone = test_input($_POST["phone"]);
    }



    if (empty($_POST["password"])) {
        $passwordErr = "Password is required.";
    } else {
        $password = $_POST["password"];
    }

    $confirm_password = $_POST["confirm_password"];
        if($password!=$confirm_password){
            $hasError = true;
            $errorString = "Passwords do not match";
        }      

    if (empty($_POST["address"])) {
        $addressErr = "Address is required";
    } else {
            $address = test_input($_POST["address"]);
    }

    // die;
    
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }
    
    if($hasError){
         echo $errorString;
        exit;
    }
   
    $customer_no = generateCustomerCode();

    // var_dump($customerCode);
    // die;

    //INSERT statement for customers
    $stmt = $conn->prepare("INSERT INTO customers (NAME, EMAIL, PHONE, PWD, ADDRESS, GENDER, CUSTOMER_NO, SIZE) VALUES (:name, :email, :phone, :password, :address, :gender, :customer_no, :size)");
    
    // var_dump($stmt);
    // exit;

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':size', $size);
    $stmt->bindParam(':customer_no', $customer_no);

    //execute
    $status = $stmt->execute();
    if ($status) {
        $_SESSION["phone"] = $phone;
        $_SESSION["customer_no"] = $customer_no;
        $_SESSION["name"] = $name;
        $_SESSION["email"] = $email;
        $_SESSION["phone"] = $phone;
        $_SESSION["address"] = $address;
        $_SESSION["gender"] = $gender;
        header("location: ../checkout.php");
        die;
        
    } else {
        var_dump($stmt->errorInfo());exit;
        
    }
}

//To clean and validate input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>