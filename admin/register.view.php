<?php
require "../config.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin | Sign Up</title>
  <link rel="stylesheet" href="admin.style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
  <div class="admin">
    <div class="signup">
      <div class="row" style="width: 950px;">
        <div class="col-sm-5">
        </div>
        <div class="col-sm-1">
          <div class="vertical-line"></div>
        </div>
        <div class="col-sm-6" id="form">
          <div class="form">
            <form action="register.php" method="POST" enctype="multipart/form-data">
              <h3><span>Admin.</span> Sign Up</h3>

              <input type="text" placeholder="First Name(s)" name="fname" id="fname" required>
              <span class="error"><?php echo $fnameErr; ?></span><br>

              <input type="text" placeholder="Last Name" name="lname" id="lname" required>
              <span class="error"><?php echo $lnameErr; ?></span><br>

              <input type="email" name="email" placeholder="Email" id="email" required>
              <span class="error"><?php echo $emailErr; ?></span><br>

              <input type="text" name="address" placeholder="Address" id="address" required>
              <span class="error"><?php echo $addressErr; ?></span><br>

              <input type="password" name="password" placeholder="Password" id="password" required>
              <span class="error"><?php echo $passwordErr; ?></span><br>

              <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password" required>
              <span class="error"><?php echo $confirm_passwordErr; ?></span><br>

              <input type="tel" name="phone" placeholder="Phone" id="phone" required>
              <span class="error"><?php echo $phoneErr; ?></span><br>

              <label for="gender">Male:</label>
              <input type="radio" name="gender" id="male" value="Male" required>
              <label for="gender">Female:</label>
              <input type="radio" name="gender" id="female" value="Female" required>
              <span class="error"><?php echo $genderErr; ?></span><br>

              <label for="image">Profile Image:</label>
              <input type="file" name="image" accept="image/*" required>
              <br>

              <input type="submit" name="submit" value="Register"><br>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>