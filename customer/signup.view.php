<?php

require "../config.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | GETClothed</title>
    <link rel="stylesheet" href="customer.style.css">
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body class="text-center mx-auto">
    <form action="signup.php" class="form-signin" method="POST">
        <img class="mb-4" src="../Photos/Symbols/GEtClothed-removebg-preview-removebg-preview.png" alt="" width="250px" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Please Sign Up</h1>

        <label for="inputName" class="sr-only">Name</label>
        <input type="text" id="inputName" name="name" class="form-control" placeholder="Name" required autofocus>
        <span class="error"><?php echo $nameErr; ?></span>

        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" required autofocus>
        <span class="error"><?php echo $emailErr; ?></span>

        <label for="inputPhone" class="sr-only">Telephone</label>
        <input type="tel" id="inputPhone" class="form-control" placeholder="Telephone" name="phone" required autofocus>
        <span class="error"><?php echo $telephone; ?></span>

        <label for="inputAddress" class="sr-only">Address</label>
        <input type="text" id="inputAddress" class="form-control" placeholder="Address" name="address" required autofocus>
        <span class="error"><?php echo $addressErr; ?></span>

        <label for="inputSize" class="sr-only">Size</label>
        <input type="number" id="inputSize" class="form-control" placeholder="Size" name="size" required autofocus>
        <span class="error"><?php echo $sizeErr; ?></span>

        <label for="gender">Male:</label>
        <input type="radio" name="gender" id="male" value="Male" required>
        <label for="gender">Female:</label>
        <input type="radio" name="gender" id="female" value="Female" required>
        <span class="error"><?php echo $genderErr; ?></span><br>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password" required autofocus>
        <span class="error"><?php echo $passwordErr; ?></span>

        <label for="inputConfirmPassword" class="sr-only">Confirm Password</label>
        <input type="password" id="inputConfirmPassword" class="form-control" placeholder="Confirm Password" name="confirm_password" required autofocus>
        <span class="error"><?php echo $confirm_passwordErr; ?></span>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign Up</button>
        <p class="mt-5 mb-3 text-muted">&copy; GETClothed - 2023</p>
    </form>
</body>

</html>