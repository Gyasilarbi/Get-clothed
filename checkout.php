<?php

include 'config.php';
include './customer/library.php';

session_start();

$referenceID = generateReferenceID();
// echo "<pre>";
// print_r($_SESSION);
// exit;
// Check if the user is logged in and the user's email is set in the session
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



// Update the last activity time
$_SESSION["last_activity"] = time();

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



<!Doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="ss.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
  <title>Checkout | GetClothed</title>
</head>

<body class="bg-light">
  <a href="cart.php"><button class="btn btn-danger" style="margin: 20px; position: fixed;">Close</button></a>

  <div class="container">
    <div class="py-5 text-center">
      <img class="d-block mx-auto mb-4" src="../Get-clothed/Photos/Symbols/GEtClothed-removebg-preview-removebg-preview.png">
      <h2>Checkout form</h2>
      <p class="lead">Our Purpose Is To Sustainably Make the Pleasure and Benefits of Hair Care Accessible to the Many.</p>
    </div>

    <div class="row">
      <div class="col-md-4 order-md-2 mb-4">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-muted">Your cart</span>
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
              <h6 class="my-0">Promo code</h6>
              <small>EXAMPLECODE</small>
            </div>
            <span class="text-success">0</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Total (USD)</span>
            <strong><?php echo "GH₵ " . number_format($total_price, 2); ?></strong>
          </li>


        </ul>

        <form class="card p-2">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Promo code">
            <div class="input-group-append">
              <button type="submit" class="btn btn-secondary">Redeem</button>
            </div>
          </div>
        </form>
      </div>
      <div class="col-md-8 order-md-1">
        <h4 class="mb-3">Billing address</h4>
        <form class="needs-validation" method="POST" action="orders.php" novalidate>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="customer_name">Name</label>
              <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="" value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : "" ?>" required>
              <div class="invalid-feedback">
                Valid first name is required.
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <label for="phone">Phone</label>
              <input type="tel" class="form-control" id="phone" name="phone" placeholder="" value="<?php echo isset($_SESSION['phone']) ? $_SESSION['phone'] : "" ?>" required>
              <div class="invalid-feedback">
                Valid Phone Number is required.
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label for="email">Email <span class="text-muted">(Optional)</span></label>
            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : "" ?>">
            <div class="invalid-feedback">
              Please enter a valid email address for shipping updates.
            </div>
          </div>

          <div class="mb-3">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" value="<?php echo isset($_SESSION['address']) ? $_SESSION['address'] : "" ?>" required>
            <div class="invalid-feedback">
              Please enter your shipping address.
            </div>
          </div>

          <div class="mb-3">
            <label for="address2">Nearest Landmark<span class="text-muted">(Optional)</span></label>
            <input type="text" class="form-control" id="address2" placeholder="Apartment or suite">
          </div>

          <hr class="mb-4">

          <h4 class="mb-3">Payment</h4>

          <div class="d-block my-3">
            <div class="custom-control custom-radio">

              <input id="momo" name="paymentMethod" type="radio" class="custom-control-input" value="MOMO" required data-bs-toggle="modal" data-bs-target="#myModal">
              <label class="custom-control-label" for="momo" data-bs-toggle="modal" data-bs-target="#myModal">MTN Mobile Money</label>

              <div class="modal" id="myModal">
                <div class="modal-dialog">
                  <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                      <h4 class="modal-title"><img src="Photos/Symbols/momo.png" width="30%"> MTN Mobile Money</h4>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">

                      <h5>Send to: <strong>
                          <h1>0243009448</h1>
                        </strong> </h5>
                      <h5>Name: <strong>
                          <h1>GetClothed Stores</h1>
                        </strong> </h5>
                      <h5>Reference: <strong>
                          <h1><?php echo $referenceID; ?></h1>
                          <?php $_SESSION["reference_no"] = $referenceID;

                          // var_dump($_SESSION["reference_no"]);
                          // die;

                          ?>
                        </strong></h5>
                      <h5 class="text-success">Amount: <strong>
                          <h1><?php echo "GH₵ " . number_format($total_price, 2) ?></h1>
                        </strong></h5>

                      <hr class="mb-4">

                      <div class="text-start">
                        <p>How to make payment</p>
                        <p style="color: grey;">1. Dial *170#.</p>
                        <p style="color: grey;">2. Choose option "1. Transfer Money".</p>
                        <p style="color: grey;">3. Choose option "1. MoMo User".</p>
                        <p style="color: grey;">4. Enter mobile number.</p>
                        <p style="color: grey;">5. Enter mobile number to confirm number</p>
                        <p style="color: grey;">6. Enter Amount</p>
                        <p style="color: grey;">7. Enter Reference <strong>(<?php echo $referenceID; ?>)</strong></p>
                        <p style="color: grey;">8. Enter your MoMo Pin to confirm transaction.</p>
                      </div>

                      <hr class="mb-4">

                      <!-- <input type="submit" name="update" class="btn btn-success" id="update" value="Payment Complete"> -->

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>

                  </div>
                </div>
              </div>

            </div>
            <div class="custom-control custom-radio">
              <input id="cash" name="paymentMethod" value="CASH" type="radio" class="custom-control-input" checked required>
              <label class="custom-control-label" for="cash">Cash on Delivery</label>
            </div>
          </div>

          <input type="hidden" name="total" value="<?php echo $total_price; ?>" />

          <hr class="mb-4">
          <button name="update" id="update" class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button>
        </form>
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