<?php $pid=$_GET['postid'];
session_start();
if ( $_SESSION['logged_in'] != 1 ) {
    $_SESSION['message'] = "You must log in before viewing your profile page!";
    header("location: error.php");
}
else {
    // Makes it easier to read
    $currentuserid = $_SESSION['userid'];
}
?>
<!-- Page to edit caption or delete uploaded images. -->
<?php 
include 'database.php';

$sql= "SELECT *,DATE_FORMAT(post_time, '%W %M %e %Y %I:%i %p') AS dates FROM posts WHERE post_id='$pid'";
$result = $mysqli->query($sql);
$row=mysqli_fetch_array($result);
$text = $row['post_text'];
$post_id = $row['post_id'];
$n = $row['likes'];
$date = $row['dates'];
$uid = $row['user_id'];
$imagepath = $row['post_imagepath'];
?>

<html>
<head></head>
<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/slate.min.css"/>
	<link rel="stylesheet" href="css/fontawesome-all.css"/>  
  	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary ">
<a class="navbar-brand my-lg-0" href="newsfeed.php">
<i class="fab fa-fort-awesome"></i>   My network
  </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="newsfeed.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="profilehome.php">Profile</a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="setting.php">Settings</a>
          <a class="dropdown-item" href="update.php">Edit Profile</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php">Log Out</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li>
    </ul>
    
   </div>
    
    
	<form class="form-inline my-lg-0 mx-auto" action="search.php"  method="post" enctype="multipart/form-data">
	  
      <input class="form-control ml-auto col-sm-7" type="text" placeholder="Search" aria-label="Search" name="searchvalue">
      <input class="btn btn-link my-2 my-sm-0 fas fa-search" type="submit" value='&#xf002;'>
	</form>
</nav>
<br>

<form action="" method="post" enctype="multipart/form-data">
<div class="col-sm-8 mx-auto">

<!-- Card 1 -->
<div class="card">
<div class="card-header">Edit your post</div>
<div class="card-body">
        <div class="card">
        <div class="d-flex card-header">
        
         <div >
        <?php $user ="SELECT * FROM user_info WHERE user_id='$currentuserid'";
              $resuser =$mysqli->query($user);
              $row1 = mysqli_fetch_array($resuser);  
        ?>
                
        <?php if ($row1['profile_img']!=''){?>
        <img src="<?php echo $row1['profile_img'];?>" width="30" height="30" class="d-inline-block align-top rounded-circle" alt="">
        <?php }?>
        <?php if ($row1['profile_img']==''){?>
        <i class="fas fa-user-circle"></i>
        <?php }?>
        <?php echo $row1['first_name']." ".$row1['last_name'];?>
  		</div>
  		<div class="ml-auto">
  		<?php echo $date;?></div>
  		</div> 
        <?php 
        $query = "SELECT * FROM post_like WHERE user_id='$currentuserid' AND post_id=".$post_id."";
        $res = $mysqli->query($query);
        ?>
        <div class="card card-body">
        <?php if ($imagepath==""){}
        else {            
        ?>
        <img src="<?php echo $imagepath;?>" class="mx-auto" style="height: 480px; width:600px;">
        <hr>
        <?php }?>
        <p class="card-text">
        <div class="input-group mb-3">
        <div class="custom-file">
		<input type="file" class="custom-file-input" id="file" accept="image/*" onchange="return fileValidation()" name="images">
    	<label class="custom-file-label" for="inputGroupFile04">Choose image</label>
         </div>
		<div class="input-group-append">
   		<input type="submit" value="Upload" class="btn btn-primary" style="height: px;" name="updateimg">
 	 	</div>
        </div>
        <br>
        <p class="card-text">
        <input type="text" value="<?php echo $text;?>" style="width: 1160px; height:150px;" name="posttext">
        </p>
        
        </div>
        </div>
        <?php echo $n;?> likes
        </div>
        <br>
        <p align="center">
		<input type="submit" class="mx-auto btn btn-outline-secondary" value="Update" name="updatetext">
		<input type="submit" class="mx-auto btn btn-outline-secondary" value="Delete" name="deletetext">
		
		</p>
        </div>
        
        
        </div>
        
        
</form>
</body>
</html>

<?php 
require 'database.php';

if (isset($_POST['updatetext'])){//Update query to edit the caption in the database
    $text= $_POST['posttext'];
    
    $sql = "UPDATE `posts` SET `post_text`='$text', post_time=sysdate() WHERE `post_id`='$pid';";
    $result1  = $mysqli->query($sql);
    
    if($result1){
        echo "<script>alert('Post Updated Succesfully')</script>";
        echo "<script>window.open('profilehome.php','_self')</script>";
    }
    
}
if (isset($_POST['deletetext'])){//Update query to edit the caption in the database
    $text= $_POST['posttext'];
    
    $sql = "DELETE FROM `posts` WHERE `post_id`='$pid';";
    $result1  = $mysqli->query($sql);
    
    if($result1){
        echo "<script>alert('Post Deleted Succesfully')</script>";
        echo "<script>window.open('profilehome.php','_self')</script>";
    }
    
}
if (isset($_POST['updateimg'])){
    
    
    $imagename=$_FILES["images"]["name"];
    $imageval=$_FILES['images']['tmp_name'];
    
    // $caption=mysqli_escape_string($mysqli, $caption);
    
    $imagepath="posts/".($uid).$imagename;
    
    // The images are stored in the photo directory
    move_uploaded_file($imageval, $imagepath);
    $sql = "UPDATE `posts` SET `post_imagepath`='$imagepath', post_time=sysdate() WHERE `post_id`='$pid';";
    $result1  = $mysqli->query($sql);
    
    if($result1){
        echo "<script>alert('Post Image Updated Succesfully')</script>";
        echo "<script>window.open('profilehome.php','_self')</script>";
    }
}

if (isset($_POST['removeimg'])){
 //   unl
}
/* if($result2){
    echo "<script>alert('Image Deleted Succesfully')</script>";
    echo "<script>window.open('account.php','_self')</script>";
}
 */?>
