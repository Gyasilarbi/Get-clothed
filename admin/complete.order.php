<?php
include '../config.php'; // Include your database connection code here

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

$subject = "Thank you for buying from GETCLOTHED!";

if (isset($_GET['doneid'])) {
    $order_no = $_GET['doneid'];
    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "UPDATE orders SET ORDER_STATUS = '1' WHERE ORDER_NO = :order_no";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':order_no', $order_no);

        $status = $stmt->execute();

        if ($status) {

            $sql1 = "SELECT * FROM orders WHERE ORDER_NO = '$order_no' LIMIT 1";

            $stmt1 = $pdo->query($sql1);
            $row = $stmt1->fetch(PDO::FETCH_ASSOC);
            $order_no = $row['ORDER_NO'];
            $customer_no = $row['CUSTOMER_NO'];
            $paymentMethod = $row['PAYMENTMETHOD'];
            $address = $row['ADDRESS'];
            $datetime = $row['DATE_TIME'];
            $email = $row['EMAIL'];
        }

        $sql2 = "INSERT INTO deliveries (ORDER_NO, PAYMENTMETHOD, ADDRESS, CUSTOMER_NO, DATETIME) VALUES ('$order_no', '$paymentMethod',  '$address', '$customer_no', '$datetime')";

        $stmt2 = $pdo->prepare($sql2);
        $status1 = $stmt2->execute();

        try {


            $mail = new PHPMailer(true);


            // $mail->SMTPDebug = 2;                                       
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com;';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'firibuascanda@gmail.com';
            $mail->Password   = 'dqyx nuam rcmz blqq';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom($email, 'GETCLOTHED');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = "Your order with Order Number:" .$order_no. "has been shipped. Kindly have your REFERENCE ID ready to collect order. Thank You!.";
            $mail->send();
            echo "Mail has been sent successfully!";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        header("location: index.php");
        die();
        // } else {
        //     echo "123";
        //     var_dump($stmt->errorInfo());
        //     exit;
        // }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
