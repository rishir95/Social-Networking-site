<?php
/* Displays all error messages */
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Error</title>
</head>
<body>
<div class="form">
    <h1>Error</h1>

<!-- This page is launched if the user directly logs into the account page -->
    <p>
    <?php 
    if( isset($_SESSION['message']) AND !empty($_SESSION['message']) ): 
        echo $_SESSION['message'];    
    else:
        header( "location: register.php" );
    endif;
    ?>
    </p>     
    <a href="register.php"><button class="button button-block"/>Home</button></a>
</div>
</body>
</html>
