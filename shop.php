<?php
include 'config.php';

session_start();

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


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SHOP | GETCLOTHED</title>
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
          <li><button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#demo"><i class="bi bi-cart3"></i> Cart</button></li>
        </ul>
      </div>

      <div class="offcanvas offcanvas-end" id="demo">
        <div class="offcanvas-header">
          <h3 class="offcanvas-title"><i class="bi bi-cart3"></i> Your Cart</h1>
            <hr>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
          <div class="sticky-top bg-white">
            <button class="btn btn-danger" type="button"><a href="shop.php?action=empty" id="btnEmpty">Empty Cart</a></button>
            <button class="btn btn-success" type="button"><a href="cart.php">Open Cart</a></button>
          </div>

          <?php
          if (isset($_SESSION["cart_item"])) {
            $total_quantity = 0;
            $total_price = 0;
          }
          ?>

          <?php
          foreach ($_SESSION["cart_item"] as $item) {
            $item_price = $item["quantity"] * $item["price"];
          ?>

            <div class="images text-center mx-auto">
              <img src="<?php echo "admin/" . $item["image"]; ?>" width="40%">
              <h6><?php echo $item["name"]; ?></h6>
              <p><?php echo "GH₵ " . $item["price"] . "  Quantity: " . $item["quantity"]; ?></p>
              <p><?php echo "Total: " . "GH₵ " . number_format($item_price, 2); ?></p>
              <p><a href="shop.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction" style="color: red;"><i class="fa fa-trash" aria-hidden="true"></i></a></p>
            </div>

            <?php
            $total_quantity += $item["quantity"];
            $total_price += ($item["price"] * $item["quantity"]);
            ?>

          <?php } ?>

          <div class="results">
            <ul>
              <li><?php echo "Quantity:" . " " . $total_quantity; ?></li>
              <li style="color: green;"><strong><?php echo "Total:" . " " . "GH₵ " . number_format($total_price, 2); ?></strong></li>
            </ul>
          </div>



          <?php
          if (empty($_SESSION["cart_item"])) { ?>
            <div class="no-records">Your Cart is Empty</div>
          <?php } ?>
        </div>
      </div>

      <div class="container-fluid bg-dark" id="navbar2">
        <form class="search" action="">
          <input type="text" placeholder="Search for item" list="browsers" name="browser" id="browser">
          <datalist id="browsers">
            <option value="Bonnets">
            <option value="Scrunchies">
            <option value="Wraps">
            <option value="Bands">
            <option value="Dresses">
          </datalist>
          <button type="submit"><i class="fa fa-search"></i></button>
        </form>

        <ul>
          <li><a href="">Hair</a></li>
        </ul>
      </div>
    </div>

    <div class="dropdown container-fluid">
      <h5>ADVERTISMENTS!!!!</h5>
      <div class="dropdown-content">
        <p>*Enter code EXTRALOVE at checkout to receive discount. Ends 8am UTC on 12 November 2023. Code can be used multiple times per customer up to a maximum pre-discount spend of £500/€690 per order. Can’t be used with other promo codes or on gift vouchers, delivery charges, Premier Delivery or ASOS Marketplace. Country exclusions apply. Selected marked products excluded from promo.</p>
      </div>
    </div>

    <div class="showcase container-fluid">
      <?php
      include "config.php";

      $stmt = $conn->prepare("SELECT * FROM Items WHERE ITEM_STATUS = '1'");
      $stmt->execute();
      $product_array = $stmt->fetchAll();
      ?>
      <div class="row container-fluid">
        <?php
        if (!empty($product_array)) {
          foreach ($product_array as $key => $value) {
        ?>
            <div class="col-sm-3">

              <div class="images text-center mx-auto">
                <a id="images" href="item.detail.php?detailid=<?php echo $product_array[$key]["PRODUCT_NO"] ?>">
                  <img src="<?php echo 'admin/' . $product_array[$key]["ITEM_IMAGE"]; ?>" width="70%" />
                  <h5><?php echo $product_array[$key]["ITEM_NAME"]; ?></h5>
                  <p><?php echo $product_array[$key]["BRAND"]; ?></p>
                  <p>more details...</p>
                  <p><?php echo "GH₵" . $product_array[$key]["PRICE"]; ?></p>
                </a>
                <!-- Modify the form to include product information -->
                <form method="post" action="shop.php?action=add&code=<?php echo $product_array[$key]["PRODUCT_NO"] ?>">
                  <input type="number" class="product-quantity" name="quantity" value="1" size="2" />
                  <input type="submit" value="Add to Cart" class="btn btn-warning" />
                </form>
              </div>


            </div>
        <?php
          }
        }
        ?>
      </div>
    </div>

    <div class="footer container-fluid">
      <div class="row">
        <div class="col">
          <h5>HELP AND INFORMATION</h5>
          <ul>
            <li><a href="">Help</a></li>
            <li><a href="">Track Order</a></li>
            <li><a href="">Delivery and Returns</a></li>
          </ul>
        </div>
        <div class="col">
          <h5>ABOUT GETCLOTHED</h5>
          <ul>
            <li><a href="">About Us</a></li>
            <li><a href="">Corporate Responsibility</a></li>
            <li><a href="">Investor's Site</a></li>
          </ul>
        </div>
        <div class="col">
          <h5>MORE FROM GETCLOTHED</h5>
          <ul>
            <li><a href="">Discounts</a></li>
            <li><a href="">Partner with Us</a></li>
            <li><a href="">Gift Vouchers</a></li>
            <li><a href="">GetClothed x SHEIN</a></li>
          </ul>
        </div>
        <div class="col">
          <h5>SHOPPING FROM:</h5>
        </div>
      </div>

      <hr>

      <p><i class="fa fa-copyright"></i> Copyright 2023 - GetClothed</p>
    </div>
  </main>
</body>

</html>