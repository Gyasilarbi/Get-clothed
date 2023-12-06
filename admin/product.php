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

    // Fetch the value from the database (replace 'your_query' with your actual SQL query)
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
    <link rel="stylesheet" href="https://fontawesome.com/icons/house?f=classic&s=solid">
    <link rel="stylesheet" href="https://fontawesome.com/icons/user?f=classic&s=solid">
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

                    // var_dump(empty( $imagePath));die;
                    //  echo '<img src="' . $imagePath . '" style="width:70px;" class="rounded-pill">';   
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

                // var_dump($successMessage);
                // exit;

                // Display the success message in a notification
                echo "<div class='success-notification'>$successMessage</div>";

                // Clear the success message from the session so it won't be displayed again
                unset($_SESSION['success_message']);
            }
            ?>

            <?php

            // Check if a delete message is set in the session
            if (isset($_SESSION['delete_message'])) {
                $deleteMessage = $_SESSION['delete_message'];

                // var_dump($deleteMessage);
                // exit;

                // Display the success message in a notification
                echo "<div class='delete-notification'>$deleteMessage</div>";

                // Clear the success message from the session so it won't be displayed again
                unset($_SESSION['delete_message']);
            }

            ?>

            <?php

            // Check if a delete message is set in the session
            if (isset($_SESSION['update_message'])) {
                $updateMessage = $_SESSION['update_message'];

                // var_dump($updateMessage);
                // exit;

                // Display the success message in a notification
                echo "<div class='update-notification'>$updateMessage</div>";

                // Clear the success message from the session so it won't be displayed again
                unset($_SESSION['update_message']);
            }

            ?>

            <?php

            // Check if a delete message is set in the session
            if (isset($_SESSION['block_message'])) {
                $blockMessage = $_SESSION['block_message'];

                // var_dump($blockMessage);
                // exit;

                // Display the success message in a notification
                echo "<div class='delete-notification'>$blockMessage</div>";

                // Clear the success message from the session so it won't be displayed again
                unset($_SESSION['block_message']);
            }

            ?>
        </div>

        <hr class="mb-4">

        <script>
            function w3_open() {
                document.getElementById("mySidebar").style.display = "block";
            }

            function w3_close() {
                document.getElementById("mySidebar").style.display = "none";
            }
        </script>

        <div class="navbar" style="padding: 10px;">
            <h1 style="color: pink;"><i class="fa fa-truck" aria-hidden="true"></i> Products</h1>

            <div class="sp btn btn-primary">
                <h3 style="color: white;"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i> Selling Products:
                    <?php
                    try {
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
            </div>

            <div class="ip btn btn-danger">
                <h3 style="color: white;"><i class="fa fa-minus-circle" aria-hidden="true"></i> Inactive Products:
                    <?php
                    try {
                        $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // SQL query to count the total number of values in a table
                        $sql = "SELECT COUNT(*) as total FROM items WHERE ITEM_STATUS = '0' ";

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

            <div class="so btn btn-success"">
                <h3 style=" color: white;"><i class="fa fa-money" aria-hidden="true"></i> Sold Out Products: </h3>
            </div>
        </div>

        <hr class="mb-4">

        <main class="container-fluid">
            <button type="button" style="margin: 20px;" id="btn-add" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal"><i class="fa fa-plus" aria-hidden="true"></i> Add Item</button>

            <!-- The Modal -->
            <div class="modal" id="myModal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Add Item</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">

                            <form action="add.item.procesing.php" method="POST" enctype="multipart/form-data">
                                <!-- <h2>Add Item</h2> -->
                                <!-- <label for="text" class="form-label"></label> -->
                                <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="text" placeholder="Item name" name="item_name" id="item_name" required>
                                <span class="error"><?php echo $item_nameErr ?></span><br>

                                <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="text" placeholder="Item type" name="item_type" id="item_type" required>
                                <span class="error"><?php echo $item_typeErr ?></span><br>

                                <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="text" placeholder="Item color" name="item_color" id="item_color" required>
                                <span class="error"><?php echo $item_colorErr ?></span><br>

                                <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="number" placeholder="Price" name="Price" id="Price" required>
                                <span class="error"><?php echo $priceErr ?></span><br>

                                <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="text" placeholder="Item tally" name="item_tally" id="item_tally" required>
                                <span class="error"><?php echo $item_typeErr ?></span><br>

                                <label for="item_image">Item Image:</label>
                                <input type="file" name="itemImage" multiple accept="image/*" required><br>

                                <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="text" placeholder="Item description" name="item_descr" id="item_descr" required>
                                <span class="error"><?php echo $item_descrErr ?></span><br>

                                <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="text" placeholder="Brand" name="brand" id="brand" required>
                                <span class="error"><?php echo $brandErr ?></span><br>

                                <input type="submit" name="add" class="btn btn-primary" id="add" value="Add">
                            </form>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="table-responsive overflow">
                <table class="table table-hover">
                    <thead class="sticky-top">
                        <tr>
                            <th scope="col">Product Code</th>
                            <th scope="col">Image</th>
                            <th scope="col">Item name</th>
                            <th scope="col">Item type</th>
                            <th scope="col">Item color</th>
                            <th scope="col">Price</th>
                            <th scope="col">Item tally</th>
                            <th scope="col">Date registered</th>
                            <th scope="col">Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            // include 'config.php';
                            $servername = "localhost"; //127.0.0.1
                            $database = "getclothed";
                            $username = "root";
                            $password = "root";

                            $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



                            $sql = "SELECT * FROM items WHERE ITEM_STATUS = '1' ";
                            $stmt = $pdo->query($sql);

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $productCode = $row['PRODUCT_NO'];
                                $image_path = $row['ITEM_IMAGE'];
                                $item_name = $row['ITEM_NAME'];
                                $item_type = $row['ITEM_TYPE'];
                                $dateTimeCode = $row['DATE_TIME'];
                                $item_color = $row['ITEM_COLOR'];
                                $price = $row['PRICE'];
                                $item_tally = $row['ITEM_TALLY'];
                                $item_id = $row['ITEM_ID']; ?>

                                <tr>
                                    <th scope="row"><?php echo $productCode ?> </th>
                                    <td><?php echo '<img src="' . $image_path . '" style="width: 80px; height: 80px">'; ?></td>
                                    <td><?php echo $item_name ?></td>
                                    <td><?php echo $item_type ?></td>
                                    <td><?php echo $item_color ?></td>
                                    <td><?php echo $price ?></td>
                                    <td><?php echo $item_tally ?></td>
                                    <td><?php echo $dateTimeCode ?></td>

                                    <td>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#openModal<?php echo $productCode ?>">Open</button>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal<?php echo $productCode ?>">Update</button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $productCode ?>">Delete</button>

                                    </td>
                                    <td>
                                        <div class="modal" id="updateModal<?php echo $productCode ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Update Item</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <form action="update.php" method="POST">
                                                            <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="text" placeholder="Item name" name="item_name" id="item_name" required>
                                                            <span class="error"><?php echo $item_nameErr ?></span><br>
                                                            <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="hidden" name="updateKey" value="<?php echo $productCode; ?>" />

                                                            <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="text" placeholder="Item type" name="item_type" id="item_type" required>
                                                            <span class="error"><?php echo $item_typeErr ?></span><br>

                                                            <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="text" placeholder="Item color" name="item_color" id="item_color" required>
                                                            <span class="error"><?php echo $item_colorErr ?></span><br>

                                                            <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="number" placeholder="Price" name="Price" id="Price" required>
                                                            <span class="error"><?php echo $priceErr ?></span><br>

                                                            <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="text" placeholder="Item tally" name="item_tally" id="item_tally" required>
                                                            <span class="error"><?php echo $item_typeErr ?></span><br>

                                                            <input type="submit" name="update" class="btn btn-primary" id="update" value="Update">
                                                        </form>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="modal" id="openModal<?php echo $productCode ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"><?php echo $productCode ?></h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body container">
                                                        <div class="row">
                                                            <div class="col-sm-5">
                                                                <?php echo '<img src="' . $image_path . '" style="width: 150px; height: 150px">'; ?>
                                                            </div>
                                                            <div class="col-sm-7">
                                                                <h2>
                                                                    <?php echo $item_name ?><br>
                                                                    <?php echo $price ?><br>
                                                                    <?php echo $item_tally ?>
                                                                </h2>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal<?php echo $productCode ?>">Update</button>
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="modal" id="deleteModal<?php echo $productCode ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Confirm Delete</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <button class="btn btn-danger"><a href="delete.php?deleteid=<?php echo $productCode ?>">Delete</a></button>
                                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                        <?php }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <h2 style="margin: 20px; padding: 20px;"><i class="fa fa-minus-circle" aria-hidden="true"></i> Inactive Products</h2>

            <div class="table-responsive overflow" style="margin: 10px;">
                <table class="table table-hover">
                    <thead class="sticky-top">
                        <tr>
                            <th scope="col">Product Code</th>
                            <th scope="col">Image</th>
                            <th scope="col">Item name</th>
                            <th scope="col">Item type</th>
                            <th scope="col">Price</th>
                            <th scope="col">Item tally</th>
                            <th scope="col">Date Registered</th>
                            <th scope="col">Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        try {
                            // include 'config.php';
                            $servername = "localhost"; //127.0.0.1
                            $database = "getclothed";
                            $username = "root";
                            $password = "root";

                            $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



                            $sql = "SELECT * FROM items WHERE ITEM_STATUS = '0' ";
                            $stmt = $pdo->query($sql);

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $productCode = $row['PRODUCT_NO'];
                                $image_path = $row['ITEM_IMAGE'];
                                $item_name = $row['ITEM_NAME'];
                                $item_type = $row['ITEM_TYPE'];
                                $item_color = $row['ITEM_COLOR'];
                                $dateTimeCode = $row['DATE_TIME'];
                                $price = $row['PRICE'];
                                $item_tally = $row['ITEM_TALLY'];
                                $item_id = $row['ITEM_ID']; ?>

                                <tr>
                                    <th scope="row"><?php echo $productCode ?> </th>
                                    <td><?php echo '<img src="' . $image_path . '" style="width: 80px; height: 80px">'; ?></td>
                                    <td><?php echo $item_name ?></td>
                                    <td><?php echo $item_type ?></td>
                                    <td><?php echo $price ?></td>
                                    <td><?php echo $item_tally ?></td>
                                    <td><?php echo $dateTimeCode ?></td>

                                    <td>
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#openModal<?php echo $productCode ?>">Open</button>
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#activateModal<?php echo $productCode ?>">Activate</button>
                                    </td>
                                    <td>
                                        <div class="modal" id="activateModal<?php echo $productCode ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Activate</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <button class="btn btn-success"><a href="activate.php?activateid=<?php echo $productCode ?>">Activate</a></button>
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="modal" id="updateModal<?php echo $productCode ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Update Item</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <form action="update.php" method="POST">
                                                            <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="text" placeholder="Item name" name="item_name" id="item_name" required>
                                                            <span class="error"><?php echo $item_nameErr ?></span><br>
                                                            <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="hidden" name="updateKey" value="<?php echo $productCode; ?>" />

                                                            <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="text" placeholder="Item type" name="item_type" id="item_type" required>
                                                            <span class="error"><?php echo $item_typeErr ?></span><br>

                                                            <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="text" placeholder="Item color" name="item_color" id="item_color" required>
                                                            <span class="error"><?php echo $item_colorErr ?></span><br>

                                                            <input style="width: 95%; height: 40px; border: o.5px solid black; margin-bottom: 10px; font-size: 20px;" type="number" placeholder="Price" name="Price" id="Price" required>
                                                            <span class="error"><?php echo $priceErr ?></span><br>

                                                            <input type="text" placeholder="Item tally" name="item_tally" id="item_tally" required>
                                                            <span class="error"><?php echo $item_typeErr ?></span><br>

                                                            <input type="submit" name="update" class="btn btn-primary" id="update" value="Update">
                                                        </form>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="modal" id="openModal<?php echo $productCode ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title"><?php echo $productCode ?></h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body container">
                                                        <div class="row">
                                                            <div class="col-sm-5">
                                                                <?php echo '<img src="' . $image_path . '" style="width: 150px; height: 150px">'; ?>
                                                            </div>
                                                            <div class="col-sm-7">
                                                                <h2>
                                                                    <?php echo $item_name ?><br>
                                                                    <?php echo $price ?><br>
                                                                    <?php echo $item_tally ?>
                                                                </h2>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal<?php echo $productCode ?>">Update</button>
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="modal" id="deleteModal<?php echo $productCode ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Confirm Delete</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <button class="btn btn-danger"><a href="delete.php?deleteid=<?php echo $productCode ?>">Delete</a></button>
                                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
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
    </main>

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