<?php

include "../config.php";
include "library.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

$fname = $lname= $email = $address =$gender = $phone = $confirm_password = $password="";
$fnameErr = $lnameErr = $emailErr = $addressErr = $genderErr = $phoneErr = $confirm_passwordErr = $imageErr = "";

//Check if request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["fname"])) {
        $fnameErr = "First Name is required";
    } else {
        $fname = test_input($_POST["fname"]);
    }

    if (empty($_POST["lname"])) {
        $lnameErr = "Last Name is required";
    } else {
        $lname = test_input($_POST["lname"]);
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
    
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }
        
    // Check if an image file was uploaded
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_path = "profile.photos/" . $image;
        
        // Move the uploaded image to a folder
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // File upload success
        } else {
            $imageErr = "Error uploading image.";
        }
    } else {
        $imageErr = "Image is required.";
    }
    
    if($hasError){
         echo $errorString;
        exit;
    }

    $adminCode = generateAdminCode();
    
    //INSERT statement for customers
    $stmt = $conn->prepare("INSERT INTO admins (FNAME, LNAME, EMAIL, PHONE, PWD, ADDRESS, GENDER, IMAGE_PATH, ADMIN_CODE) VALUES (:fname, :lname, :email, :phone, :password, :address, :gender, :image_path, :admin_code)");
    
    // var_dump($stmt);
    // exit;

    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':image_path', $image_path);
    $stmt->bindParam(':admin_code', $adminCode);

    //execute
    $status = $stmt->execute();
    if ($status) {
        header("location: index.php");
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