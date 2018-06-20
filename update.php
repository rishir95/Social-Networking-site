<!-- Page to edit caption or delete uploaded images. -->
<?php 

include 'database.php';
session_start();
if ( $_SESSION['logged_in'] != 1 ) {
    $_SESSION['message'] = "You must log in before viewing your profile page!";
    header("location: error.php");
}
else {
    // Makes it easier to read
    $currentuserid = $_SESSION['userid'];
    $currentuser = $_SESSION['username'];
}

$sql = "SELECT * FROM user_info u, locations l, gender g WHERE u.location=l.location_id AND u.gender=g.gender_id AND user_name='$currentuser'";
$result = $mysqli->query($sql);
$row = mysqli_fetch_assoc($result);
$first = $row['first_name'];
$last = $row['last_name'];
$uid = $row['user_id'];
$path = $row['profile_img'];
$interest = $row['research_interest'];
$dob = $row['date_of_birth'];
$from = $row['name'];
$joined = $row['created_on'];
$genderid = $row['gender_id'];
$gender = $row['sex'];
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
        <a class="nav-link" href="profilehome.php"><?php if ($path!=''){?>
        <img src="<?php echo $path;?>" width="30" height="30" class="d-inline-block align-top rounded-circle" alt="">
        <?php }?>
        <?php if ($path==''){?>
        <i class="fas fa-user-circle"></i>
        <?php }?> <?php echo $first;?></a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="update.php">Edit Profile</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="logout.php">Log Out</a>
        </div>
      </li>
      <li class="nav-item">
      <?php 
      $check = "SELECT * FROM relationship WHERE receiver_id='$currentuserid' AND status=0";
      $rescheck =$mysqli->query($check);
      
      if ($rescheck->num_rows>0){
       $no = "SELECT COUNT(*) FROM relationship WHERE receiver_id='$currentuserid' AND status=0;";
       $rescount = $mysqli->query($no);
       $rowcount = mysqli_fetch_array($rescount);
       ?>
              
        <a class="nav-link" href="friends.php"><?php echo $rowcount['COUNT(*)']?> <i class="fas fa-users" style="font-size: 20px;"></i></a>
       <?php }?>
       <?php if ($rescheck->num_rows<=0){?>
        <a class="nav-link disabled" href=""><i class="fas fa-users" style="font-size: 20px;"></i></a>
      <?php }?>
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
<div class="card">
<div class="card mx-auto" style="width: 18rem; height: 21rem; padding: 7.5px; margin-left:15px;">
<?php if ($path!=""){?>
  <img class="card-img-top" src="<?php echo $path;?>" alt="Card image cap" style="width: 17rem; height: 17rem;">
<?php }?>
<?php if ($path==''){?>
          <i class="fas fa-user-circle" style="font-size:17rem;"></i>
  <?php }?>
  <div class="input-group">
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="file" accept="image/*" onchange="return fileValidation()" name="images">
    <label class="custom-file-label" for="inputGroupFile04">Choose image</label>
  </div>
  <div class="input-group-append">
   <input type="submit" value="Upload" class="btn btn-primary" style="height: px;" name="updateimg">
  </div>
</div>
    </div>
    <div class="mx-auto">
    <input type="submit" value="Remove profile image" class="btn btn-primary" style="height: px;" name="removeimg">
	</div>
	
    <hr>
<div class="card card-custom mx-auto">
<div class="card-header"><i class="fas fa-globe"></i> About</div>
<div class="card-body">
<p class="card-text"><i class="fas fa-user"></i> First Name: <input type="text" name="updatename" value="<?php echo $first;?>"></p>
<p class="card-text"><i class="fas fa-user"></i> Last Name: <input type="text" name="updatename2" value="<?php echo $last;?>"></p>
<p class="card-text"><i class="fas fa-book"></i> Research Interests: <input type="text" name="updateresearch" value="<?php echo $interest;?>"></p>
<p class="card-text"><i class="fas fa-calendar"></i> Born: <input type="text" name="updatedob" value="<?php echo $dob;?>"></p>
<p class="card-text"><i class="fas fa-map-marker-alt"></i> From: <input type="text" name="updatefrom" value="<?php echo $from;?>"></p>
<p class="card-text"><i class="far fa-clock"></i> Joined on: <?php echo $joined;?></p>
<p class="card-text"><i class="fas fa-venus-mars"></i> Gender: <?php echo $gender;?> 
<select class="card-text" id="Privacy" name="gender" style="height: 45px;">
      <option value="1">Male</option>
      <option value="2">Female</option>
          </select></p>
</div>
</div>

<br>
<br>
<p align="center">
<input type="submit" class="btn btn-primary" name="update" value="Update">
</p>
</div>
</div>
</form>
</body>
</html>

<?php 
require 'database.php';

if (isset($_POST['update'])){//Update query to edit the caption in the database
    $name = $_POST['updatename'];
    $name2 = $_POST['updatename2'];
    $research = $_POST['updateresearch'];
    $dob = $_POST['updatedob'];
    $from = $_POST['updatefrom'];
    $gender = $_POST['gender'];
    
    $sql = "UPDATE user_info SET `first_name`='$name', `last_name`='$name2',`research_interest`='$research', `date_of_birth`='$dob', `gender`=$gender WHERE user_id='$currentuserid';"; 
    $result1  = $mysqli->query($sql);

    if($result1){
        echo "<script>alert('Caption Updated Succesfully')</script>";
        echo "<script>window.open('profilehome.php','_self')</script>";
    }
    
}
if (isset($_POST['updateimg'])){
    
    
    $imagename=$_FILES["images"]["name"];
    $imageval=$_FILES['images']['tmp_name'];
    
    // $caption=mysqli_escape_string($mysqli, $caption);
    
    $imagepath="profile/".($uid).$imagename;
    
    // The images are stored in the photo directory
    move_uploaded_file($imageval, $imagepath);
    $sql = "UPDATE `user_info` SET `profile_img`='$imagepath' WHERE `user_id`='$currentuserid';";
    $result1  = $mysqli->query($sql);
    
    if($result1){
        echo "<script>alert('Profile Image Updated Succesfully')</script>";
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
