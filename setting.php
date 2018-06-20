<?php 
session_start();
require 'database.php';

?>

<html>
<head>
</head>
<body>
<form method="post" action="" enctype="multipart/form-data">




<!-- Input code for adding more images. -->		
<input type="file" name="myimage" id="file" accept="image/*" onchange="return fileValidation()" required>
<br>
<br>
<input type="text" name="caption" maxlength="150">	
<br>		
<br>
<br>
<br>
<input type="submit" name="submit" value="Upload">
</form>


</body>
</html>

<?php 

    if (isset($_POST['submit'])) {

    $imagename = $_FILES["myimage"]["name"];
    $imagetmp = addslashes(file_get_contents($_FILES['myimage']['tmp_name']));
    $imageval = $_FILES['myimage']['tmp_name'];
    $userid = $row['user_id'];

    $imagepath="profile/".($userid).$imagename;

    // The images are stored in the photo directory
    move_uploaded_file($imageval, $imagepath);

    $sql = "UPDATE user_info SET profile_img ='$imagepath' WHERE `user_id`='1';";
    $mysqli->query($sql);
}
?>
