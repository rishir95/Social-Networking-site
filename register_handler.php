<?php
$fname = ""; //Full Name
$em = ""; //Email
$em2 = ""; //Email2
$password = ""; //Password
$password2 = ""; //Password2
$date = ""; //Date user signed up
$error_array = array(); //Error messages

if (isset($_POST['register_button']))
{
    //Registration form values
    //FirstName
    $fname = strip_tags($_POST['reg_fname']); //Remove HTML tags
    $fname = str_replace(' ', '', $fname); // Remove spaces
    $fname = ucfirst(strtolower($fname)); //Uppercase first letter only
    $_SESSION['reg_fname'] = $fname; //Session Variable
    
    //LastName
    $lname = strip_tags($_POST['reg_lname']); //Remove HTML tags
    $lname = str_replace(' ', '', $lname); // Remove spaces
    $lname = ucfirst(strtolower($lname)); //Uppercase first letter only
    $_SESSION['reg_lname'] = $lname; //Session Variable
    
    //Email
    $em = strip_tags($_POST['reg_email']); //Remove HTML tags
    $em = str_replace(' ', '', $em); // Remove spaces
    $em = strtolower($em); //Lowercase email
    $_SESSION['reg_email'] = $em; //Session Variable
    
    //Email2
    $em2 = strip_tags($_POST['reg_email2']); //Remove HTML tags
    $em2 = str_replace(' ', '', $em2); // Remove spaces
    $em2 = strtolower($em2); //Lowercase email2
    $_SESSION['reg_email2'] = $em2; //Session Variable
    
    //Password
    $password = strip_tags($_POST['reg_password']); //Remove HTML tags
    
    
    //Password2
    $password2 = strip_tags($_POST['reg_password2']); //Remove HTML tags
    
    //Date
    $date = date("Y-m-d"); //Current Date
    
    if($em == $em2){
        //Check if email is in valid format
        if(filter_var($em, FILTER_VALIDATE_EMAIL)) {
            $em = filter_var($em, FILTER_VALIDATE_EMAIL);
            
            //Check if email already exists
            $email_check = $conn->query("SELECT email FROM user_info WHERE email = '$em'");
            $num_rows = $email_check -> num_rows;
            
            if($num_rows > 0){
                array_push($error_array, "Email already in use.<br>");
            }
        }
        else{
            array_push($error_array, "Invalid email format.<br>");
        }
    }
    else{
        array_push($error_array, "Emails do not match.<br>");
    }
    
    //Firstname check
    if(strlen($fname) > 25 || strlen($fname) < 2){
        array_push($error_array, "Firstname must be between 2 and 25 characters.<br>");
    }
    
    //Lastname check
    if(strlen($lname) > 25 || strlen($lname) < 2){
        array_push($error_array, "Lastname must be between 2 and 25 characters.<br>");
    }
    
    //Password check
    if($password != $password2){
        array_push($error_array, "Passwords do not match.<br>");
    }
    else {
        if(preg_match('/[^a-zA-Z0-9 ]/i', $password)){
            array_push($error_array, "Password must contain only English letters and/or numbers.<br>");
        }
    }
    
    if(strlen($password) < 5){
        array_push($error_array, "Password must contain atleast 5 characters.<br>");
    }
    
    if(empty($error_array)){
        $password = md5($password); // Encrypt password before updating database
        
        //Generate unique username
        $username = strtolower($fname . "_" . $lname);
        
        $check_username_query = $conn->query("SELECT user_name FROM user_info WHERE user_name = '$username'");
        
        $i = 0;
        while(($check_username_query -> num_rows) != 0){
            $i++;
            $username = $username . "_" . $i;
            $check_username_query = $conn->query("SELECT user_name FROM user_info WHERE user_name = '$username'");
        }
        
        //Profile Picture
        $profile_pic = "images/profile_pics/defaults/default_pic.png";
        
        //Update Database
        $query = $conn->query("INSERT INTO user_info VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '', '$profile_pic', '', '', '$date','','active')");
        
        array_push($error_array, "<span> You're all set! Go ahead and login!</span><br>");
        
        //Clear Session variables
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";
    }
}


?>
