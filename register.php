<?php
require 'config.php';
require 'register_handler.php';
require 'login_handler.php';
?>

<html>
<head>
    <title>Welcome</title>
    <link rel="stylesheet" type="text/css" href="css/register_page.css" href="fonts.googleapis.com/css?family=Righteous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="register.js"></script>
</head>
<body>
    <?php

        if(isset($_POST['register_button'])) {
            echo '
                <script> $(document).ready(function() {
                            $("#first").hide();
                            $("#second").show();
                }); </script>';
        }
    ?>
<div class="wrapper">

    <div class="login_box">

            <div class="login_header">
                <h1>Scientists Social Network</h1>
                Login or sign up below!
            </div>
            <br>
            <div id="first">
    <form action="register.php" method="POST">

        <input type = "email" name="log_email" placeholder="Email address" value="<?php
                    if(isset($_SESSION['log_email'])) {
                        echo $_SESSION['log_email'];
                    }
                    ?>" required> <br>

        <input type = "password" name="log_password" placeholder="Password" required> <br> <br>

        <?php if(in_array("Email or password was incorrect<br>", $error_array)) echo  "Email or password was incorrect<br>"; ?>

        <input type = "submit" name="login_button" value="Login">
        <br>
        <a href="#" id="signup" class="signup">Need an account? Register here!</a>

    </form>

    </div>

            <div id="second">

    <form action="register.php" method="POST">
        <input type = "text" name="reg_fname" placeholder="First Name" value="<?php if(isset($_SESSION['reg_fname'])){
            echo $_SESSION['reg_fname'];
        } ?>" required> <br> <br>

        <?php if(in_array("Firstname must be between 2 and 25 characters.<br>", $error_array))
            echo "Firstname must be between 2 and 25 characters.<br>"; ?>

        <input type = "text" name="reg_lname" placeholder="Last Name" value="<?php if(isset($_SESSION['reg_lname'])){
            echo $_SESSION['reg_lname'];
        } ?>" required> <br> <br>

        <?php if(in_array("Lastname must be between 2 and 25 characters.<br>", $error_array))
            echo "Lastname must be between 2 and 25 characters.<br>"; ?>

        <input type = "email" name="reg_email" placeholder="Email" value="
        <?php if(isset($_SESSION['reg_email'])){
            echo $_SESSION['reg_email'];
        } ?>" required> <br> <br>
        <input type = "email" name="reg_email2" placeholder="Confirm Email" value="
        <?php if(isset($_SESSION['reg_email2'])){
            echo $_SESSION['reg_email2'];
        } ?>" required> <br> <br>

        <?php if(in_array("Email already in use.<br>", $error_array))
            echo "Email already in use.<br>"; else if(in_array("Invalid email format.<br>", $error_array))
            echo "Invalid email format.<br>"; else if(in_array("Emails do not match.<br>", $error_array))
            echo "Emails do not match.<br>"; ?>

        <input type = "password" name="reg_password" placeholder="Password" required> <br> <br>
        <input type = "password" name="reg_password2" placeholder="Confirm Password" required> <br> <br>

        <?php if(in_array("Passwords do not match.<br>", $error_array))
            echo "Passwords do not match.<br>"; else if(in_array("Password must contain only English letters and/or numbers.<br>", $error_array))
            echo "Password must contain only English letters and/or numbers.<br>"; else if(in_array("Password must contain atleast 5 characters.<br>", $error_array))
            echo "Password must contain atleast 5 characters.<br>"; ?>

        <input type = "submit" name="register_button" value="Register">

        <?php if(in_array("<span> You're all set! Go ahead and login!</span><br>", $error_array))
            echo "<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>";
            ?>
            <br><br>
            <a href="#" id="signin" class="signin">Already have an account? Sign in here!</a>

    </form>
</div>
</div>
</div>

</body>
</html>