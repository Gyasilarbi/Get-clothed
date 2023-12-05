<?php

include 'config.php';

session_start();

// echo "<pre>";
// print_r($_SESSION);
// exit;

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
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart | GetClothed</title>
    <link rel="stylesheet" href="ss.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

</head>

<body>
    <main>
        <div class="container-fluid" style="background: whitesmoke; height: 30px;">
            <ul style="list-style-type: none; margin: 0; padding: 0; overflow: hidden; display: flex; float: right;">
                <li style="float: left; margin-right: 20px;"><a href="" style="text-decoration: none; color: black;">Help & FAQS</a></li>
            </ul>
        </div>

        <div class="sticky-top bg-white">
            <div class="container-fluid sticky-top bg-white" id="navbar">
                <a href="index.php"><img src="../Get-clothed/Photos/Symbols/GEtClothed-removebg-preview-removebg-preview.png" width="12%" height="1%"></a>

                <ul>
                    <li><a href="shop.php"><i class="bi bi-shop"></i> Shop</a></li>
                    <li><a href=""><i class="bi bi-info-circle-fill"></i> About</a></li>
                    <li><a href=""><i class="bi bi-person-fill"></i> Account</a></li>
                </ul>
            </div>
        </div>

        <div class="dropdown container-fluid">
            <h5>ADVERTISMENTS!!!!</h5>
            <div class="dropdown-content">
                <p>*Enter code EXTRALOVE at checkout to receive discount. Ends 8am UTC on 12 November 2023. Code can be used multiple times per customer up to a maximum pre-discount spend of £500/€690 per order. Can’t be used with other promo codes or on gift vouchers, delivery charges, Premier Delivery or ASOS Marketplace. Country exclusions apply. Selected marked products excluded from promo.</p>
            </div>
        </div>

        <div class="shopping-cart container-fluid">
            <div class="txt-head">
                <h3><i class="bi bi-cart3"></i> Your Cart</h3>

                <div class="btns" style="float: right;">

                    <button class="btn btn-danger" type="button"><a href="cart.php?action=empty" id="btnEmpty">Empty Cart</a></button>
                    <button class="btn btn-success" type="button"><a href="checkout.php" id="btnEmpty">Checkout</a></button>

                </div>
            </div>
        </div>

        <?php
        if (isset($_SESSION["cart_item"])) {
            $total_quantity = 0;
            $total_price = 0;
        }
        ?>

        <div class="table-responsive container-fluid">
            <table class="table table-hover table-bordered">
                <tbody>
                    <tr class="table-info">
                        <th style="text-align: left;">Item</th>
                        <th style="text-align: left;">Code</th>
                        <th style="text-align: left;">Quantity</th>
                        <th style="text-align: left;">Unit Price</th>
                        <th style="text-align: left;">Price</th>
                        <th style="text-align: left;">Remove</th>
                    </tr>

                    <?php
                    foreach ($_SESSION["cart_item"] as $item) {
                        $item_price = $item["quantity"] * $item["price"];
                    ?>

                        <tr>
                            <td><img src="<?php echo "admin/" . $item["image"]; ?>" class="cart-item-image" width="50px" /><?php echo $item["name"];?></td>
                            <td><?php echo $item["code"]; ?></td>
                            <td style="text-align: right;"><?php echo $item["quantity"]; ?></td>
                            <td style="text-align: right;"><?php echo "GH₵ " . $item["price"]; ?></td>
                            <td style="text-align: right;"><?php echo "GH₵ " . number_format($item_price, 2); ?></td>
                            <td style="text-align: center;"><a href="cart.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction" style="color: red; height: 50px;"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                        </tr>

                        <?php
                        $total_quantity += $item["quantity"];
                        $total_price += ($item["price"] * $item["quantity"]);
                        ?>

                    <?php } ?>
                    <tr class="table-success">
                        <td colspan="2" align="right">Total:</td>
                        <td align="right"><?php echo "Quantity: " . $total_quantity; ?></td>
                        <td align="right" colspan="2"><strong><?php echo "GH₵ " . number_format($total_price, 2); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <?php

        if (empty($_SESSION["cart_item"])) { ?>
            <div class="no-records">Your Cart is Empty</div>
        <?php } ?>

    </main>
</body>

</html>