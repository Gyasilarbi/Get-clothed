<?php

require '../config.php';
// Start or resume the session
session_start();

// Check if the user is logged in and the user's email is set in the session
if (isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
} else {
    // If the user is not logged in, you can redirect them to the login page or take appropriate action.
    header("Location: login.view.php");
    die();
}

// Set the inactivity timeout (in seconds)
$inactivity_timeout = 630; // 10 minutes 30 seconds

// Check if the user is logged in and the last activity time is set
if (isset($_SESSION["last_activity"])) {
    // Calculate the time since the last activity
    $elapsed_time = time() - $_SESSION["last_activity"];

    // If the elapsed time is greater than the inactivity timeout, log the user out
    if ($elapsed_time > $inactivity_timeout) {
        // Destroy the session
        session_unset();
        session_destroy();

        // Redirect to the login page or any other appropriate action
        header("Location: login.view.php");
        die();
    }
}

// Update the last activity time
$_SESSION["last_activity"] = time();



try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $sql = "SELECT * FROM admins WHERE email = :email";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // var_dump($result);die;

    if ($result) {
        $adminCode = $result['ADMIN_CODE'];

        $imagePath = $result['IMAGE_PATH'];
    } else {
        echo "No values found in the database.";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
$pdo = null;




$sqlu = "SELECT * FROM Items WHERE PRODUCT_NO = :";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':', $id);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

$item_name = $result['ITEM_NAME'];
$item_type = $result['ITEM_TYPE'];
$item_color = $result['ITEM_COLOR'];
$price = $result['PRICE'];
$item_tally = $result['ITEM_TALLY'];

//order details
$sqlR = "SELECT * FROM orderDetails WHERE ORDER_NO = :order_no";
$stmt = $conn->prepare($sqlR);
$stmt->bindParam(':order_no', $order_no);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

$order_no = $result['ORDER_NO'];
$product_id = $result['PRODUCT_ID'];
$product_name = $result['PRODUCT_NAME'];
$price = $result['PRICE'];
$product_image = $result['PRODUCT_IMAGE'];
$price = $result['UNIT_PRICE'];
$quantity = $result['QUANTITY'];
$total = $result['TOTAL'];
$paymentMethod = $result['PAYMENTMETHOD'];
$customer_no = $result['CUSTOMER_NO'];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin|GetClothed</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body {
            font-family: "Lato", sans-serif;
        }

        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: rgb(255, 202, 211);
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }

        .sidebar a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: black;
            display: block;
            transition: 0.3s;
        }

        .sidebar h6 {
            color: grey;
        }

        .sidebar a:hover {
            color: rgb(41, 41, 126);
        }

        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }

        .openbtn {
            font-size: 30px;
            cursor: pointer;
            background: transparent;
            color: black;
            border: none;
        }

        .openbtn:hover {
            color: white;
            background-color: black;
        }

        #main {
            transition: margin-left .5s;
        }

        .logo {
            display: flex;
            flex-direction: row;
        }

        .logo1 {
            margin-left: -10px;
        }

        .logo2 {
            margin-top: 20px;
            margin-left: -10px;
        }

        .logo2 a {
            text-decoration: none;
            color: black;
        }

        .logo2 a:hover {
            color: darkblue;
        }

        /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
        @media screen and (max-height: 450px) {
            .sidebar {
                padding-top: 15px;
            }

            .sidebar a {
                font-size: 18px;
            }
        }

        .footer {
            background: black;
            color: #8a8a8a;
            font-weight: 14px;
        }

        .footer p {
            color: #8a8a8a;

        }

        .footer h3 {
            color: #fff;
            margin-bottom: 20px;
        }

        #foot {
            text-align: center;
        }

        .footer hr {
            border: none;
            background: #b5b5b5;
            height: 1px;
            margin: 20px 0;
        }

        #foot1 {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .foot1 ul li a {
            color: #b5b5b5;
            text-decoration: none;
        }

        .foot1 ul li a:hover {
            color: white;
        }

        .copyright {
            text-align: center;
            color: #b5b5b5;
        }

        .social-links {
            text-align: center;
            margin-top: 25px;
        }

        .social-links a {
            margin: 0 10px;
            font-size: 30px;
            display: inline-block;
            transition: transform 0.3s;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-15px);
            }

            60% {
                transform: translateY(-10px);
            }
        }

        .social-links a:hover {
            animation: bounce 0.5s;
        }

        .social-links p {
            text-align: center;
            color: white;
        }

        .fa-twitter {
            color: white;
        }

        .fa-snapchat {
            color: rgb(202, 202, 6);
        }

        .fa-whatsapp {
            color: green;
        }

        .fa-instagram {
            color: violet;
        }
    </style>

</head>

<body>
    <div id="mySidebar" class="sidebar">

        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="logo">
            <div class="logo1">
                <a class="navbar-brand" href="#">
                    <?php

                    echo    !empty($imagePath) ?  '<img src="' . $imagePath . '" style="width:70px;height:70px;" class="rounded-pill">' : '<img src="./uploads/user.svg" style="width:70px;" class="rounded-pill">';

                    ?>
                </a>
            </div>
            <div class="logo2">
                <?php echo $email; ?>
                <?php echo $adminCode; ?><br>
            </div>
        </div>
        <hr>

        <h6>MAIN</h6>

        <a href="index.php" class=""><i class="fa fa-home" aria-hidden="true"></i> Overview</a>
        <a href="product.php" class=""><i class="fa fa-truck" aria-hidden="true"></i> Products</a>
        <a href="analytics.php" class=""><i class="fa fa-bar-chart" aria-hidden="true"></i> Analytics</a>
        <a href="customer.php" class=""><i class="fa fa-users" aria-hidden="true"></i> Customers</a>

        <hr>

        <h6>ACCOUNTING SETTINGS</h6>

        <a href="" class=""><i class="fa fa-user-circle" aria-hidden="true"></i> Account</a>
        <a href="" class=""><i class="fa fa-question-circle-o" aria-hidden="true"></i> FAQ</a>
        <a href="" class=""><i class="fa fa-phone" aria-hidden="true"></i> Support</a>
        <a href="logout.php" class=""><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>
    </div>

    <div id="main">
        <div class="navbar sticky-top" style="background: white;">
            <button class="openbtn" onclick="openNav()">&#9776;</button>

            <img src="../Photos/Symbols/GEtClothed-removebg-preview-removebg-preview.png" width="200px">

            <i style="font-size: 30px; float: right; padding: 10px;" class="fa fa-bell" aria-hidden="true"></i>

            <?php

            session_start(); // Start or resume the session

            // Check if a success message is set in the session
            if (isset($_SESSION['success_message'])) {
                $successMessage = $_SESSION['success_message'];

                // Display the success message in a notification
                echo "<div class='success-notification'>$successMessage</div>";

                // Clear the success message from the session so it won't be displayed again
                unset($_SESSION['success_message']);
            }


            // Check if a delete message is set in the session
            if (isset($_SESSION['delete_message'])) {
                $deleteMessage = $_SESSION['delete_message'];

                // Display the success message in a notification
                echo "<div class='delete-notification'>$deleteMessage</div>";

                // Clear the success message from the session so it won't be displayed again
                unset($_SESSION['delete_message']);
            }



            // Check if a delete message is set in the session
            if (isset($_SESSION['update_message'])) {
                $updateMessage = $_SESSION['update_message'];

                // Display the success message in a notification
                echo "<div class='update-notification'>$updateMessage</div>";

                // Clear the success message from the session so it won't be displayed again
                unset($_SESSION['update_message']);
            }

            // Check if a delivered message is set in the session
            if (isset($_SESSION['delivery_message'])) {
                $deliveredMessage = $_SESSION['delivery_message'];

                // Display the success message in a notification
                echo "<div class='delivered-notification'>$deliveredMessage</div>";

                // Clear the success message from the session so it won't be displayed again
                unset($_SESSION['delivery_message']);
            }

            // Check if a delete message is set in the session
            if (isset($_SESSION['block_message'])) {
                $blockMessage = $_SESSION['block_message'];

                // Display the success message in a notification
                echo "<div class='delete-notification'>$blockMessage</div>";

                // Clear the success message from the session so it won't be displayed again
                unset($_SESSION['block_message']);
            }

            ?>
        </div>

        <hr class="mb-4">

        <div class="main-admin">
            <div class="navbar" style="padding: 10px;">
                <a href="customer.php" style="text-decoration: none; color: white;">
                    <div class="t_users btn btn-warning">
                        <h3><i class="fa fa-users" aria-hidden="true"></i> Total Users:

                            <?php
                            try {
                                // Database connection parameters
                                include "../config.php";

                                $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                // SQL query to count the total number of values in a table
                                $sql = "SELECT COUNT(*) as total FROM customers";

                                // Execute the query
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();

                                // Fetch the result
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                                if ($result) {
                                    $totalValues = $result['total'];
                                    echo  "<span style='font-size: 30px;'>$totalValues</span>";
                                } else {
                                    echo "No values found in the database.";
                                }
                            } catch (PDOException $e) {
                                echo "Connection failed: " . $e->getMessage();
                            }

                            // Close the PDO connection
                            $pdo = null;
                            ?>

                        </h3>
                    </div>
                </a>

                <a href="" style="text-decoration: none; color: white;">
                    <div class="t_users btn btn-success">

                        <h3><i class="fa fa-money" aria-hidden="true"></i> Total Sales:  GH₵
                            <?php
                            try {
                                include "../config.php";

                                $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                $sql = "SELECT SUM(TOTAL) as total FROM orders";

                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();

                                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                                if ($result) {
                                    $totalValues = $result['total'];
                                    echo  "<span style='font-size: 30px;'>$totalValues</span>";
                                } else {
                                    echo "No values found in the database.";
                                }
                            } catch (PDOException $e) {
                                echo "Connection failed: " . $e->getMessage();
                            }
                            ?>
                        </h3>

                    </div>
                </a>

                <a href="" style="text-decoration: none; color: white;">
                    <div class="t_users btn btn-primary">
                        <a href="analytics.php" style="text-decoration: none; color: white;">
                            <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> Reports</h3>
                        </a>
                    </div>
                </a>

                <a href="product.php" style="text-decoration: none; color: white;">
                    <div class="t_users btn btn-info">
                        <a href="product.php" style="text-decoration: none; color: white;">
                            <h3><i class="fa fa-plus" aria-hidden="true"></i> Products:
                                <?php
                                try {
                                    // Database connection parameters
                                    include "../config.php";

                                    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                    // SQL query to count the total number of values in a table
                                    $sql = "SELECT COUNT(*) as total FROM items WHERE ITEM_STATUS = '1' ";

                                    // Execute the query
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute();

                                    // Fetch the result
                                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                                    if ($result) {
                                        $totalValues = $result['total'];
                                        echo  "<span style='font-size: 30px;'>$totalValues</span>";
                                    } else {
                                        echo "No values found in the database.";
                                    }
                                } catch (PDOException $e) {
                                    echo "Connection failed: " . $e->getMessage();
                                }

                                // Close the PDO connection
                                $pdo = null;
                                ?>

                            </h3>
                        </a>
                    </div>
                </a>

                <a href="product.php" style="text-decoration: none; color: white;">
                    <div class="ip btn btn-danger">
                        <h3 style="color: white;"><i class="fa fa-minus-circle" aria-hidden="true"></i> Orders:
                            <?php
                            try {
                                // Database connection parameters
                                include "config.php";

                                $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                // SQL query to count the total number of values in a table
                                $sql = "SELECT COUNT(*) as total FROM orders WHERE ORDER_STATUS = '0' ";

                                // Execute the query
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();

                                // Fetch the result
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                                if ($result) {
                                    $totalValues = $result['total'];
                                    echo  "<span style='font-size: 30px;'>$totalValues</span>";
                                } else {
                                    echo "No values found in the database.";
                                }
                            } catch (PDOException $e) {
                                echo "Connection failed: " . $e->getMessage();
                            }

                            // Close the PDO connection
                            $pdo = null;
                            ?>
                        </h3>
                    </div>
                </a>

            </div>

            <hr class="mb-4">

            <div class="text-strong">
                <h2>Orders</h2>
            </div>
            <div class="table-responsive overflow">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ORDER NO.</th>
                            <th scope="col">DATE & TIME</th>
                            <th scope="col">CUSTOMER NAME</th>
                            <th scope="col">CUSTOMER NO.</th>
                            <th scope="col">PHONE</th>
                            <th scope="col">ADDRESS</th>
                            <th scope="col">TOTAL</th>
                            <th scope="col">PAYMENT METHOD</th>
                            <th scope="col">OPERATIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        try {
                            include '../config.php';

                            $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $sql = "SELECT * FROM orders WHERE ORDER_STATUS = '0'";
                            $stmt = $pdo->query($sql);

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $order_no = $row['ORDER_NO'];
                                $datetime = $row['DATE_TIME'];
                                $customer_name = $row['CUSTOMER_NAME'];
                                $customer_no = $row['CUSTOMER_NO'];
                                $phone = $row['PHONE'];
                                $address = $row['ADDRESS'];
                                $total = $row['TOTAL'];
                                $paymentMethod = $row['PAYMENTMETHOD']; ?>

                                <tr>
                                    <th scope="row"><?php echo $order_no; ?> </th>
                                    <td><?php echo $datetime; ?></td>
                                    <td><?php echo $customer_name; ?></td>
                                    <td><?php echo $customer_no; ?></td>
                                    <td><?php echo $phone; ?></td>
                                    <td><?php echo $address; ?></td>
                                    <td><?php echo $total; ?></td>
                                    <td><?php echo $paymentMethod; ?></td>
                                    <td><a href="order.details.php?orderid=<?php echo $order_no ?>"><button class="btn btn-info">Open Order</button></a></td>
                                </tr>

                        <?php }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>

                    </tbody>
                </table>
            </div>

            <hr class="mb-4">

            <div class="text-strong">
                <h2>Completed Orders</h2>
            </div>

            <div class="table-responsive overflow">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ORDER NO.</th>
                            <th scope="col">DATE & TIME</th>
                            <th scope="col">CUSTOMER NAME</th>
                            <th scope="col">CUSTOMER NO.</th>
                            <th scope="col">PHONE</th>
                            <th scope="col">ADDRESS</th>
                            <th scope="col">TOTAL</th>
                            <th scope="col">PAYMENT METHOD</th>
                            <th scope="col">OPERATIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        try {
                            include '../config.php';

                            $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $sql = "SELECT * FROM orders WHERE ORDER_STATUS = '1'";
                            $stmt = $pdo->query($sql);

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $order_no = $row['ORDER_NO'];
                                $datetime = $row['DATE_TIME'];
                                $customer_name = $row['CUSTOMER_NAME'];
                                $customer_no = $row['CUSTOMER_NO'];
                                $phone = $row['PHONE'];
                                $address = $row['ADDRESS'];
                                $total = $row['TOTAL'];
                                $paymentMethod = $row['PAYMENTMETHOD']; ?>

                                <tr>
                                    <th scope="row"><?php echo $order_no; ?> </th>
                                    <td><?php echo $datetime; ?></td>
                                    <td><?php echo $customer_name; ?></td>
                                    <td><?php echo $customer_no; ?></td>
                                    <td><?php echo $phone; ?></td>
                                    <td><?php echo $address; ?></td>
                                    <td><?php echo $total; ?></td>
                                    <td><?php echo $paymentMethod; ?></td>
                                    <td><a href="delivery.php?deliveredid=<?php echo $order_no ?>"><button class="btn btn-success">Delivered</button></a></td>
                                </tr>

                        <?php }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>

                    </tbody>
                </table>
            </div>

            <div class="text-strong">
                <h2>Delivered Orders</h2>
            </div>

            <div class="table-responsive overflow">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">ORDER NO.</th>
                            <th scope="col">DATE & TIME</th>
                            <th scope="col">CUSTOMER NAME</th>
                            <th scope="col">CUSTOMER NO</th>
                            <th scope="col">PHONE</th>
                            <th scope="col">ADDRESS</th>
                            <th scope="col">TOTAL</th>
                            <th scope="col">PAYMENT METHOD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        try {
                            include '../config.php';

                            $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $sql = "SELECT * FROM deliveries WHERE DELIVERED = '1'";
                            $stmt = $pdo->query($sql);

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $order_no = $row['ORDER_NO'];
                                $datetime = $row['DATETIME'];
                                $customer_no = $row['CUSTOMER_NO'];
                                $address = $row['ADDRESS'];
                                $paymentMethod = $row['PAYMENTMETHOD']; ?>

                                <tr>
                                    <th scope="row"><?php echo $order_no; ?> </th>
                                    <td><?php echo $datetime; ?></td>
                                    <td><?php echo $customer_name; ?></td>
                                    <td><?php echo $customer_no; ?></td>
                                    <td><?php echo $phone; ?></td>
                                    <td><?php echo $address; ?></td>
                                    <td><?php echo $total; ?></td>
                                    <td><?php echo $paymentMethod; ?></td>
                                </tr>

                        <?php }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>

                    </tbody>
                </table>
            </div>


        </div>

        <!--------------Footer-------------->
        <footer class="my-5 pt-5 text-muted text-center text-small">
            <p class="mb-1">&copy; GETClothed - 2023</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#">Privacy</a></li>
                <li class="list-inline-item"><a href="#">Terms</a></li>
                <li class="list-inline-item"><a href="#">Support</a></li>
            </ul>
        </footer>
    </div>




    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/10.6.4/math.min.js" integrity="sha512-iphNRh6dPbeuPGIrQbCdbBF/qcqadKWLa35YPVfMZMHBSI6PLJh1om2xCTWhpVpmUyb4IvVS9iYnnYMkleVXLA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    </script>
</body>

</html>