<?php

include "../config.php";
include "library.php";

$item_name = $item_type = $item_color = $price = $item_tally = $item_descr = $brand = "";
$item_nameErr = $item_typeErr = $item_colorErr = $priceErr = $item_tallyErr = $imageErr = $item_descrErr = $brandErr ="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["item_name"])) {
        $item_nameErr = "Field is required";
    } else {
        $item_name = test_input($_POST["item_name"]);
    }

    if (empty($_POST["item_type"])) {
        $item_typeErr = "Field is required";
    } else {
        $item_type = test_input($_POST["item_type"]);
    }

    if (empty($_POST["item_color"])) {
        $item_colorErr = "Field is required";
    } else {
        $item_color = test_input($_POST["item_color"]);
    }

    if (empty($_POST["Price"])) {
        $priceErr = "Field is required";
    } else {
        $price = test_input($_POST["Price"]);
    }

    if (empty($_POST["item_tally"])) {
        $item_tallyErr = "Field is required";
    } else {
        $item_tally = test_input($_POST["item_tally"]);
    }

    if (empty($_POST["item_descr"])) {
        $item_descrErr = "Field is required";
    } else {
        $item_descr = test_input($_POST["item_descr"]);
    }

    if (empty($_POST["brand"])) {
        $brandErr = "Field is required";
    } else {
        $brand = test_input($_POST["brand"]);
    }

    $target_dir = "products.photos/";
    $target_file = $target_dir . basename($_FILES["itemImage"]["name"]);

   

    if (!empty($target_file)) {
        if (move_uploaded_file($_FILES["itemImage"]["tmp_name"], $target_file)) {
            
        } else {
            $imageErr = "Error uploading image.";
        }
    } else {
        $imageErr = "Image is required.";
    }


    if ($hasError) {
        echo $errorString;
        exit;
    }

    $productCode = generateProductCode();
    $dateTimeCode = generateDateTimeCode();

    //INSERT statement for customers
    $stmt = $conn->prepare("INSERT INTO Items (ITEM_NAME, ITEM_TYPE, ITEM_COLOR, PRICE, ITEM_TALLY, PRODUCT_NO, ITEM_IMAGE, DATE_TIME, ITEM_DESCR, BRAND) VALUES (:item_name, :item_type, :item_color, :Price, :item_tally, :product_no, :itemImage, :date_time, :item_descr, :brand)");

    $stmt->bindParam(':item_name', $item_name);
    $stmt->bindParam(':item_type', $item_type);
    $stmt->bindParam(':item_color', $item_color);
    $stmt->bindParam(':Price', $price);
    $stmt->bindParam(':item_tally', $item_tally);
    $stmt->bindParam(':product_no', $productCode);
    $stmt->bindParam(':itemImage', $target_file);
    $stmt->bindParam(':date_time', $dateTimeCode);
    $stmt->bindParam(':brand', $brand);
    $stmt->bindParam(':item_descr', $item_descr);
    


    $status = $stmt->execute();
    if ($status) {
        // After successful insertion, set a session variable with a success message
        $_SESSION['success_message'] = "New Item added";

        // echo "Data inserted successful";
        header("location: product.php");
        die();
    } else {
        var_dump($stmt->errorInfo());
        exit;
    }
}

//To clean and validate input data
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
