<?php

include 'config.php';
include './customer/library.php';

session_start();

$name = $address = $phone = $paymentMethod = "";
$nameErr = $addressErr = $phoneErr = $paymentMethodErr = "";
// Check if request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["customer_name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["customer_name"]);
    }

    if (empty($_POST["phone"])) {
        $phoneErr = "Phone is required";
    } else {
        $phone = test_input($_POST["phone"]);
    }

    if (empty($_POST["address"])) {
        $addressErr = "Address is required";
    } else {
        $address = test_input($_POST["address"]);
    }

    if (empty($_POST["paymentMethod"])) {
        $paymentMethodErr = "paymentMethod is required";
    } else {
        $paymentMethod = test_input($_POST["paymentMethod"]);
    }

    if ($nameErr || $phoneErr || $addressErr || $paymentMethodErr) {
        echo "Validation errors. Please fix and resubmit.";
        exit;
    }

    $total_price = $_SESSION['totalPrice'];
    $customer_no = $_SESSION["customer_no"];
    $orderID = generateOrderID();
    $_SESSION['order_id'] = $orderID;
    $dateTime = generateDateTimeCode();
    $reference_no = $_SESSION["reference_no"];
    $email = $_SESSION['email'];

    
    // INSERT statement for orders
    $stmt = $conn->prepare("INSERT INTO orders (ORDER_NO, DATE_TIME, CUSTOMER_NAME, ADDRESS, TOTAL, PHONE, CUSTOMER_NO, PAYMENTMETHOD, EMAIL) VALUES (:order_no, :datetime, :customer_name, :address, :total, :phone, :customer_no, :paymentmethod, :email)");

    // Assuming $total and $customer_no are defined somewhere

    $stmt->bindParam(':order_no', $orderID);
    $stmt->bindParam(':datetime', $dateTime);
    $stmt->bindParam(':customer_name', $name);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':total', $total_price);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':customer_no', $customer_no);
    $stmt->bindParam(':paymentmethod', $paymentMethod);
    $stmt->bindParam(':email', $email);

    // Execute
    $status = $stmt->execute();
    $_SESSION['paymentmethod'] = $paymentMethod;
    $_SESSION['datetime'] = $dateTime;

    if ($status) {
        $orders = $_SESSION['cart_item'];
        
        foreach ($orders as $order) {
            $orderCode = $order['code'];
            $name = $order['name'];
            $image = $order['image'];
            $price = $order['price'];
            $quantity = $order['quantity'];
            $total = $order['price'] * $order['quantity'];
            $paymentMethod = $order['paymentmethod'];

            $stmt2 = $conn->prepare("INSERT INTO orderDetails (ORDER_NO, DATE_TIME, PRODUCT_ID, PRODUCT_NAME, PRODUCT_IMAGE, UNIT_PRICE, QUANTITY, TOTAL ,CUSTOMER_NO) VALUES ( '$orderID', $dateTime, '$orderCode', '$name', '$image', '$price', '$quantity', '$total' , '$customer_no')");

            $status2 = $stmt2->execute();

            if ($status2) {
                $stmt4 = $conn->prepare("INSERT INTO sales (PRODUCT_ID, PRODUCT_NAME, PRICE, QUANTITY, TOTAL, DATETIME) VALUES ('$orderCode', '$name', '$price', '$quantity', '$total', '$dateTime')");
                
                $status4 = $stmt4->execute();
                
               
            }
        }

    
        
        if ($status2) {
            $stmt3 = $conn->prepare("INSERT INTO transactions (ORDER_NO, TOTAL, REFERENCE_NO, CUSTOMER_NO, DATETIME) VALUES (:order_no, :total, :reference_no, :customer_no, :datetime)");
            
            $stmt3->bindParam(':order_no', $orderID);
            $stmt3->bindParam(':total', $total_price);
            $stmt3->bindParam(':reference_no', $reference_no);
            $stmt3->bindParam(':customer_no', $customer_no);
            $stmt3->bindParam(':datetime', $dateTime);

            $status3 = $stmt3->execute();
            
            header("location: receipt.php");
            die;
        } 
    } else {
        var_dump($stmt,$stmt2->errorInfo());
        exit;
    }
}

// To clean and validate input data
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
