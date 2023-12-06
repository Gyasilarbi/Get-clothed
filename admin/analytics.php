<?php

require '../config.php';
// Start or resume the session
session_start();

if (isset($_SESSION["email"])) {
    $email = $_SESSION["email"];
} else {

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



// Initialize the $chartData array
$chartData = [];

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT * FROM Items WHERE ITEM_STATUS = '1'";
    $stmt = $pdo->query($sql);

    // Populate the $chartData array inside the loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $item_name = $row['ITEM_NAME'];
        $item_tally = $row['ITEM_TALLY'];

        // Add the current item data to the $chartData array
        $chartData[] = [
            'item_name' => $item_name,
            'item_tally' => $item_tally,
        ];
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$pdo = null;

// Convert the $chartData array to JSON for JavaScript
$chartDataJSON = json_encode($chartData);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin|GetClothed</title>
    <link rel="stylesheet" href="admin.style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fontawesome.com/icons/house?f=classic&s=solid">
    <link rel="stylesheet" href="https://fontawesome.com/icons/user?f=classic&s=solid">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <style>
        body {
            font-family: "Lato", sans-serif;
        }

        .calculator {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        #answer {
            width: 100%;
            height: 60px;
            border-radius: 10px;
            border: 1px solid black;
            font-size: 24px;
            margin-bottom: 10px;
            text-align: right;
            padding: 10px;
            box-sizing: border-box;
        }

        .button-row {
            display: flex;
            width: 100%;
            margin: 5px 0;
        }

        .button {
            flex: 1;
            margin: 5px;
            text-align: center;
            padding: 15px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 18px;
        }

        .operator {
            background-color: orange;
            color: white;
        }

        .number {
            background-color: black;
            color: white;
        }

        .equals {
            background-color: orange;
            color: white;
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

        <div class="navbar">
            <div class="l">
                <a href="./report/sales.php"><button class="btn btn-info">Sales Report</button></a>
            </div>
            <div class="l">
                <a href="./report/revenue.php"><button class="btn btn-info">Revenue</button></a>
            </div>
            <div class="l">
                <a href="./report/inventory.php"><button class="btn btn-info">Inventory Report</button></a>
            </div>
            <div class="l">
                <a href="./report/operational.efficiency.php"><button class="btn btn-info">Operational Efficiency Report</button></a>
            </div>
            <div class="l">
                <a href="./report/top.customer.php"><button class="btn btn-success">Top Customer Report</button></a>
            </div>
            <div class="l">
                <a href="./report/all.reports.php"><button class="btn btn-success">All Reports</button></a>
            </div>
        </div>
    </div>

    <hr class="mb-4">

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-8" style="border-right: 1px solid black;">
                <h3>Line Graph</h3>
                <canvas id="myChart" style="width:50%;"></canvas>
            </div>
            <div class="col-sm-4">
                <h3>Calculator</h3>
                <div class="calculator">
                    <input type="text" id="answer" readonly>

                    <div class="button-row">
                        <button class="button" onclick="clearInput()">AC</button>
                        <button class="button" onclick="appendToInput('()')">()</button>
                        <button class="button" onclick="appendToInput('%')">%</button>
                        <button class="button operator" onclick="appendToInput('/')">/</button>
                    </div>

                    <div class="button-row">
                        <button class="button number" onclick="appendToInput('7')">7</button>
                        <button class="button number" onclick="appendToInput('8')">8</button>
                        <button class="button number" onclick="appendToInput('9')">9</button>
                        <button class="button operator" onclick="appendToInput('*')">*</button>
                    </div>

                    <div class="button-row">
                        <button class="button number" onclick="appendToInput('4')">4</button>
                        <button class="button number" onclick="appendToInput('5')">5</button>
                        <button class="button number" onclick="appendToInput('6')">6</button>
                        <button class="button operator" onclick="appendToInput('-')">-</button>
                    </div>

                    <div class="button-row">
                        <button class="button number" onclick="appendToInput('1')">1</button>
                        <button class="button number" onclick="appendToInput('2')">2</button>
                        <button class="button number" onclick="appendToInput('3')">3</button>
                        <button class="button operator" onclick="appendToInput('+')">+</button>
                    </div>

                    <div class="button-row">
                        <button class="button" onclick="appendToInput('.')">.</button>
                        <button class="button number" onclick="appendToInput('0')">0</button>
                        <button class="button" onclick="clearField()">Clear</button>
                        <button class="button equals" onclick="calculate()">=</button>
                    </div>
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

    <script>
        const chartData = <?php echo $chartDataJSON; ?>;
        const xValues = chartData.map(data => data.item_name);
        const yValues = chartData.map(data => data.item_tally);

        new Chart("myChart", {
            type: "line",
            data: {
                labels: xValues,
                datasets: [{
                    fill: false,
                    lineTension: 0,
                    backgroundColor: "blue",
                    borderColor: "green",
                    data: yValues
                }]
            },
            options: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            min: 0,
                            max: 50
                        }
                    }],
                }
            }
        });
    </script>

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
    <script>
        function clearInput() {
            document.getElementById('answer').value = '';
        }

        function appendToInput(value) {
            document.getElementById('answer').value += value;
        }

        function clearField() {
            document.getElementById('answer').value = '';
        }

        function calculate() {
            var input = document.getElementById('answer').value;
            var result = eval(input);
            document.getElementById('answer').value = result;
        }
    </script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/10.6.4/math.min.js" integrity="sha512-iphNRh6dPbeuPGIrQbCdbBF/qcqadKWLa35YPVfMZMHBSI6PLJh1om2xCTWhpVpmUyb4IvVS9iYnnYMkleVXLA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    </script>

</body>

</html>