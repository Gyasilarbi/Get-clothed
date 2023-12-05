<?php

require "../config.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | GETClothed</title>
    <link rel="stylesheet" href="customer.style.css">
    <link href="bootstrap.min.css" rel="stylesheet">
</head>

<body class="text-center mx-auto">
    <form class="form-signin" action="login.php" method="POST">
        <img class="mb-4" src="../Photos/Symbols/GEtClothed-removebg-preview-removebg-preview.png" alt="" width="250px" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
        
        <label for="inputPhone" class="sr-only">Phone</label>
        <input type="tel" id="inputPhone" class="form-control" placeholder="Phone" name="phone" required autofocus>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="password" required>

        <div class="checkbox mb-3">
            <label>
                <input type="checkbox" value="remember-me"> Remember me
            </label>
        </div>

        <p>Are you an admin? Don't have an account?</p>
        <p><a href="signup.view.php">Register Here</a></p>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <p class="mt-5 mb-3 text-muted">&copy; GETClothed - 2023</p>
    </form>
</body>

</html>