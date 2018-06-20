<?php
    if (isset($_POST['login_button'])){

        $email = filter_var($_POST['log_email'], FILTER_SANITIZE_EMAIL);
        $_SESSION['log_email'] = $email; //Store email into session variable
        //Check password
        $password = md5($_POST['log_password']);
        $check_database_query = $conn->query("SELECT * FROM user_info WHERE email = '$email' AND password = '$password'");
        if(($check_database_query->num_rows) == 1){
            $row = $check_database_query->fetch_assoc();
            $uid = $row['user_id'];
            $username = $row['user_name'];

            //Account status change
            $user_account_status = $conn -> query("SELECT * FROM user_info WHERE email='$email' AND account_status='inactive'");
        if(($user_account_status -> num_rows) == 1) {
            $rowcheck = $user_account_status->fetch_assoc();
            
            $reopen_account = $conn -> query("UPDATE user_info SET account_status='active' WHERE email='$email'");
        }
            $_SESSION['userid'] = $uid;
            $_SESSION['logged_in'] = 1;
            $_SESSION['username'] = $username;
            header("Location: newsfeed.php");
            exit();
        }

    else{

        array_push($error_array, "Email or password was incorrect<br>");
    }
}

 ?>
