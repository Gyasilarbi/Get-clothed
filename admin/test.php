<?php
include "../config.php";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT Items.ITEM_NAME, Items.PRODUCT_NO, Items.ITEM_TYPE, orderDetails.PRODUCT_ID, orderDetails.PRODUCT_NAME, SUM(orderDetails.TOTAL) AS TOTAL_SALES FROM Items INNER JOIN
    orderDetails ON Items.ITEM_NAME = orderDetails.PRODUCT_NAME WHERE Items.ITEM_TYPE = 'Wrap Bonnet' GROUP BY Items.ITEM_NAME, Items.PRODUCT_NO, Items.ITEM_TYPE, orderDetails.PRODUCT_ID, orderDetails.PRODUCT_NAME ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    //collect data for javascript
    $chartData = [];
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        
        var_dump($row);die;
        $product_id = $row['Items.PRODUCT_NO'];
        $totalsales = $row['TOTAL_SALES'];

        // Add the current item data to the $chartData array
        $chartData[] = [
            'items.product_' => $product_id,
            'total_sales' => $totalsales,
        ];
    }
} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <style>
        body {
            width: auto;
        }

        img {
            height: 100px;
            width: 100px;
        }

        .date {
            width: 60%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            align-items: center;
            justify-content: center;
        }

        input {
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            align-items: center;
            justify-content: center;

        }

        table {
            width: max-content;
        }

        td,
        th {
            border: 1px solid black;
            text-align: center;
        }

        header {
            background-color: rgb(21, 115, 71);
            height: 60px;
            width: 100%;
        }

        h4 {
            font-size: 40px;
        }

        h3 {
            text-align: center;
        }

        label {
            width: 18%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-success {
            margin-left: 70%;
        }
    </style>
    <title>Report</title>
</head>

<body>

    <div class="container">
        <canvas id="myChart" style="width:100%;max-width:700px"></canvas>
    </div>

</body>
<script>
    const chartData = <?php echo $chartDataJSON; ?>;
    const xValues = chartData.map(data => data.product_id);
    const yValues = chartData.map(data => data.totalsales);
    const barColors = ["green", "blue", "orange", "brown", "yellow", "red", "violet", "black", "gray"];

    new Chart("myChart", {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: barColors,
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
                        max: 1000
                    }
                }],
            }
        }
    });

    function goBack() {
        window.history.back();
    }
</script>

</html>