<?php

include 'config.php';
include './customer/library.php';

session_start();

$referenceID = $_SESSION["reference_no"];
$orderID = $_SESSION['order_id'];
$dateTime = $_SESSION['datetime'];
$paymentMethod = $_SESSION['paymentmethod'];


if (isset($_SESSION["phone"])) {
    $phone = $_SESSION["phone"];
} else {
    // If the user is not logged in, you can redirect them to the login page or take appropriate action.
    header("Location: customer/login.view.php");
    die();
}

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the value from the database (replace 'your_query' with your actual SQL query)
    $sql = "SELECT * FROM customers WHERE PHONE = :phone";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':phone', $phone);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // var_dump($result);die;

    if ($result) {
        $name = $result['NAME'];
        $address = $result['ADDRESS'];
        $customerCode = $result['CUSTOMER_NO'];
        $phone = $result['PHONE'];
        $email = $result['EMAIL'];
    } else {
        echo "No values found in the database.";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$pdo = null;


if (!empty($_GET["action"])) {
    switch ($_GET['action']) {

        case "add":
            $prodCode = $_GET["code"];

            if (!empty($_POST["quantity"])) {
                // Fetch the product details from the database
                $productByCode = $conn->prepare("SELECT * FROM Items WHERE PRODUCT_NO = :prodCode");
                $productByCode->bindParam(':prodCode', $prodCode);
                $productByCode->execute();
                $product_array = $productByCode->fetchAll();

                // Create an array with item details
                $itemArray = array(
                    $product_array[0]["PRODUCT_NO"] => array(
                        'name' => $product_array[0]["ITEM_NAME"],
                        'code' => $product_array[0]["PRODUCT_NO"],
                        'quantity' => $_POST["quantity"],
                        'price' => $product_array[0]["PRICE"],
                        'image' => $product_array[0]["ITEM_IMAGE"],
                    )
                );

                // Check if the cart session is set
                if (!empty($_SESSION["cart_item"])) {
                    if (in_array($product_array[0]["PRODUCT_NO"], array_keys($_SESSION["cart_item"]))) {
                        // Product is already in the cart, update quantity
                        foreach ($_SESSION["cart_item"] as $k => $v) {
                            if ($product_array[0]["PRODUCT_NO"] == $k) {
                                $_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
                            }
                        }
                    } else {
                        // Product is not in the cart, merge with existing cart items
                        $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"], $itemArray);
                    }
                } else {
                    // Cart session is not set, set it with the current item
                    $_SESSION["cart_item"] = $itemArray;
                }
            }
            break;


        case "remove":
            if (!empty($_SESSION["cart_item"])) {
                foreach ($_SESSION["cart_item"] as $k => $v) {
                    if ($_GET["code"] == $k)
                        unset($_SESSION["cart_item"][$k]);
                    if (empty($_SESSION["cart_item"]))
                        unset($_SESSION["cart_item"]);
                }
            }
            break;

        case "empty":
            unset($_SESSION["cart_item"]);
            break;

        case "close":
            unset($_SESSION["cart_item"]);
            break;
    }
}


?>





<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />


    <title>Thank you for buying from us! | GETClothed</title>

    <link href="./customer/bootstrap.min.css" rel="stylesheet">
    <link href="ss.css" rel="stylesheet">
</head>

<body class="bg-light">
    <!-- <a href="shop.php"><button class="btn btn-danger" style="margin: 20px; position: fixed;">Close</button></a> -->
    <a href="shop.php?action=close&code=<?php echo $_SESSION["cart_item"]; ?>"><button class="btn btn-danger" style="margin: 20px; position: fixed;">Close</button></a>


    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="./Photos/Symbols/GEtClothed-removebg-preview-removebg-preview.png" alt="" width="300" height="72">
            <h2>Invoice</h2>
            <p>Thank you for shopping with us! Kindly visit us again. Visit <a href="../Get-clothed/index.php">www.getclothed.com.gh</a> for more products and any enquiries.</p>
        </div>

        <div class="row">
            <div class="col-md-4 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Order Details</span>

                </h4>
                <ul class="list-group mb-3">
                    <?php
                    foreach ($_SESSION["cart_item"] as $item) {
                        $item_price = $item["quantity"] * $item["price"];
                    ?>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0"><?php echo $item["name"]; ?></h6>
                                <small class="text-muted"><?php echo "Quantity: " . $item["quantity"]; ?></small>
                                <small class="text-muted"><?php echo "GH₵ " . $item["price"]; ?></small>
                            </div>
                            <span class="text-muted"><?php echo "GH₵ " . number_format($item_price, 2); ?></span>
                        </li>
                        <?php
                        $total_quantity += $item["quantity"];
                        $total_price += ($item["price"] * $item["quantity"]);
                        $_SESSION['totalPrice'] = $total_price;
                        ?>
                    <?php } ?>

                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <div class="text-success">
                            <h6 class="my-0">Discount</h6>
                            <small>%</small>
                        </div>
                        <span class="text-success">0</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (GH₵)</span>
                        <strong><?php echo "GH₵ " . number_format($total_price, 2); ?></strong>
                    </li>
                </ul>
                <div class="col-md-6 mb-3">
                    <h6>Order Date: <?php echo $dateTime; ?></h6>
                </div>
            </div>
            <div class="col-md-8 order-md-1">
                <h4 class="mb-3">Customer Details</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6>Name: <?php echo $name; ?></h6>
                        <h6>Phone: <?php echo $phone; ?></h6>
                    </div>
                </div>

                <div class="mb-3">
                    <h6>Email: <?php echo $email; ?></h6>
                </div>

                <div class="mb-3">
                    <h6>Address: <?php echo $address; ?></h6>
                </div>

                <hr class="mb-4">

                <h4 class="mb-3">Payment</h4>

                <div class="d-block my-3">
                    <h6>Order Number: <strong><?php echo $orderID; ?></strong></h6>
                    <h6>Payment Method: <?php echo $paymentMethod; ?></h6>
                    <h6>Reference ID: <strong><?php echo $referenceID; ?></strong></h6>
                    <h5 class="text-danger">Kindly take note and keep your REFERENCE ID!</h5>
                    <button class="btn btn-info" onclick="window.print()"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">

                    </div>
                </div>

            </div>



        </div>
    </div>

    <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">&copy; GETClothed - 2023</p>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="#">Privacy</a></li>
            <li class="list-inline-item"><a href="#">Terms</a></li>
            <li class="list-inline-item"><a href="#">Support</a></li>
        </ul>
    </footer>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>
        window.jQuery || document.write('<script src="../../../../assets/js/vendor/jquery-slim.min.js"><\/script>')
    </script>
    <script src="../../../../assets/js/vendor/popper.min.js"></script>
    <script src="../../../../dist/js/bootstrap.min.js"></script>
    <script src="../../../../assets/js/vendor/holder.min.js"></script>
    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict';

            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');

                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>

</html>