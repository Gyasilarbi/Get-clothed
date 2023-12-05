<?php

require "../config.php";

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Clothe - Admin</title>
    <link rel="stylesheet" href="admin.style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        .error {
            color: red !important;
        }

        .success {
            color: green !important;
        }
    </style>
</head>

<body>

    <div class="admin">
        <p class="error"></p>
        <div class="login">
            <div class="row">
                <div class="col-sm-5">
                </div>
                <div class="col-sm-1">
                    <div class="vertical-line"></div>
                </div>
                <div class="col-sm-6" id="form">
                    <div class="form">
                        <form action="login.php" method="POST" id="logs">
                            <h3><span>Admin. </span>Login.</h3>

                            <input type="email" placeholder="Email" name="email" id="email" required>
                            <span class="error"><?php echo $emailErr; ?></span>

                            <input type="password" placeholder="Password" name="password" id="password" required>
                            <span class="error"><?php echo $passwordErr; ?></span>

                            <input type="submit" name="login" value="Login">

                            <p>Are you an admin? Don't have an account?</p>
                            <p><a href="register.view.php">Register Here</a></p>

                            <?php
                            if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
                                if ($_SESSION['status']) { ?>
                                    <p class="error"><?php echo $_SESSION['message'] ?></p>
                                <?php
                                } else { ?>
                                    <p class="success" ›<?php echo $_SESSION['message'] ?> <?php
                                    }
                                    unset($_SESSION['status'], $_SESSION['message']);
                            }
                            ?> 
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>