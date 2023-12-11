<?php
include '../config.php';

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
            $customer_name = $row['CUSTOMER_NAME'];
        }

        $sql2 = "INSERT INTO deliveries (ORDER_NO, PAYMENTMETHOD, ADDRESS, CUSTOMER_NO, DATETIME) VALUES ('$order_no', '$paymentMethod',  '$address', '$customer_no', '$datetime')";

        $stmt2 = $pdo->prepare($sql2);
        $status1 = $stmt2->execute();

        $sql3 = "SELECT * FROM orderDetails WHERE ORDER_NO = '$order_no'";
        $stmt3 = $pdo->query($sql3);

        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'getclothedgh@gmail.com';
            $mail->Password   = 'knhw ejit ewfa omew';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom($email, 'GETCLOTHED');
            $mail->addAddress($email);

            $mail->isHTML(true);

            // Simple HTML email template with PHP code
            $emailContent = "
            <html>
                <head>
                    <title>Order Shipped - GETCLOTHED</title>
                </head>
                <body>
                    <p>Dear $customer_name,</p>
                    <p>Your order with Order Number: <strong>$order_no</strong> has been shipped to $address.</p>
                    <ul class=\"list-group mb-3\">";
    
        while ($row1 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
            $product = $row1['PRODUCT_NAME'];
            $unitPrice = $row1['UNIT_PRICE'];
            $quantity = $row1['QUANTITY'];
            $item_price = $quantity * $unitPrice;
    
            $emailContent .= "
                <li class=\"list-group-item d-flex justify-content-between lh-condensed\">
                    <div>
                        <h6 class=\"my-0\">$product</h6>
                        <small class=\"text-muted\">Quantity: $quantity</small>
                        <small class=\"text-muted\">GH₵ $unitPrice</small>
                    </div>
                    <span class=\"text-muted\">GH₵ " . number_format($item_price, 2) . "</span>
                </li>";
        }
    
        $total_price = $_SESSION['totalPrice'];
    
        $emailContent .= "
                    <li class=\"list-group-item d-flex justify-content-between\">
                        <span>Total (GH₵)</span>
                        <strong>GH₵ " . number_format($total_price, 2) . "</strong>
                    </li>
                </ul>
                <p>Kindly have your REFERENCE ID ready to collect the order.</p>
                <p>Thank you for shopping with us!</p>
                <p>Best regards,<br>GETCLOTHED Team</p>
                </body>
            </html>
        ";

            $mail->Subject = $subject;
            $mail->Body    = $emailContent;

            $mail->send();
            echo "Mail has been sent successfully!";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        header("location: index.php");
        die();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
