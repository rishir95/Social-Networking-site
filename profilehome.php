<?php
/* Displays user information and some useful messages */
session_start();
require 'database.php';
if ( $_SESSION['logged_in'] != 1 ) {
    $_SESSION['message'] = "You must log in before viewing your profile page!";
    header("location: error.php");
}
else {
    // Makes it easier to read
    $currentuserid = $_SESSION['userid'];
    $currentuser = $_SESSION['username'];
}

?>
<?php 
$sql = "SELECT * FROM user_info u, locations l, gender g WHERE u.location=l.location_id AND u.gender=g.gender_id AND user_name='$currentuser'";
$result = $mysqli->query($sql);
$row = mysqli_fetch_array($result);
$fullname = $row['first_name']." ".$row['last_name'];
$path = $row['profile_img'];

$interest = $row['research_interest'];
$dob = $row['date_of_birth'];
$from = $row['name'];
$joined = $row['created_on'];
$genderid = $row['gender_id'];
$gender = $row['sex'];
?>


<html>
  
<head>

	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/slate.min.css"/>
	<link rel="stylesheet" href="css/fontawesome-all.css"/>  
  	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<title>Welcome to The Network</title>
  	<style>
    .card-custom {
        max-width: 550px;
    }
    </style>  	
  	<meta charset="UTF-8">

</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary ">
   <a class="navbar-brand my-lg-0" href="newsfeed.php">
<i class="fab fa-fort-awesome"></i>   The Network
  </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="newsfeed.php">Home</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="profilehome.php"><?php if ($path!=''){?>
        <img src="<?php echo $path;?>" width="30" height="30" class="d-inline-block align-top rounded-circle" alt="">
        <?php }?>
        <?php if ($path==''){?>
        <i class="fas fa-user-circle"></i>
        <?php }?> <?php echo $row['first_name'];?><span class="sr-only">(current)</span></a>
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

<div class="card" style="width: 18rem; padding: 7.5px; margin-left:15px; height:18rem;">
<?php if ($path!=''){?>
  <img class="card-img-top" src="<?php echo $path;?>" alt="Card image cap" style="width:17rem; height:17rem;">
  <?php }?>
  <?php if ($path==''){?>
          <i class="fas fa-user-circle" style="font-size:17rem;"></i>
  <?php }?>    </div>
        
<div class="container-fluid">
<div class="row">
<div class="col-sm-3">

<!-- Card 1 -->
<div class="card card-custom">
<div class="card-header"><i class="fas fa-globe"></i> About</div>
<div class="card-body">
<p class="card-text"><i class="fas fa-user"></i> Full Name: <?php echo $fullname;?></p>
<p class="card-text"><i class="fas fa-book"></i> Research Interests: <?php echo $interest;?></p>
<p class="card-text"><i class="fas fa-calendar"></i> Born: <?php echo $dob;?></p>
<p class="card-text"><i class="fas fa-map-marker-alt"></i> From: <?php echo $from;?></p>
<p class="card-text"><i class="far fa-clock"></i> Joined on: <?php echo $joined;?></p>
<p class="card-text"><?php if ($genderid == 2) {
    echo '<i class="fas fa-venus"></i>';
} elseif ($genderid == 1) {
    echo '<i class="fas fa-mars"></i>';
} else {
    echo '<i class="fas fa-venus-mars"></i>';
}
?> Gender: <?php echo $gender;?></p>
</div>
</div>
</div>
<div class="col-sm-9">

<!-- Card 2 -->
<div class="card">
<div class="card-header">Posts</div>
<div class="card-body">
<?php 
$sql= "SELECT *,DATE_FORMAT(post_time, '%W %M %e %Y %I:%i %p') AS dates FROM posts WHERE user_id='$currentuserid' ORDER BY post_time DESC";
$result = $mysqli->query($sql);
while ($row=mysqli_fetch_array($result)){
$text = $row['post_text'];
$post_id = $row['post_id'];
$n = $row['likes'];
$date = $row['dates'];
$imagepath = $row['post_imagepath'];
$query = "SELECT * FROM post_like WHERE user_id='$currentuserid' AND post_id=".$post_id."";
$res = $mysqli->query($query);
?>

<div class="card">
        <div class="d-flex card-header">
        <div >
        <?php if ($path!=''){?>
        <img src="<?php echo $path;?>" width="30" height="30" class="d-inline-block align-top rounded-circle" alt="">
        <?php }?>
        <?php if ($path==''){?>
        <i class="fas fa-user-circle"></i>
        <?php }?>
        <?php echo $fullname;?>
  		</div>
  		<div class="ml-auto">
  		<?php echo $date;?></div>
  		</div> 
        <?php 
        $query = "SELECT * FROM post_like WHERE user_id='$currentuserid' AND post_id='$post_id'";
        $res = $mysqli->query($query);
        ?>
        <div class="card card-body">
        <?php if ($imagepath==""){}
        else {            
        ?>
        <img src="<?php echo $imagepath;?>" class="mx-auto" style="height: 480px; width:600px;">
        <hr>
        <?php }?>
        <p class="card-text"><?php echo $text;?></p>
        
        </div>
        </div>
<?php if ($res->num_rows==1){?>
<button  type="button" class="btn btn-link" onclick="unliker('<?php echo $post_id;?>')" ><i class="fas fa-thumbs-up"></i></button>
<?php } else {?>
<button  type="button" class="btn btn-link" onclick="liker('<?php echo $post_id;?>')" ><i class="far fa-thumbs-up"></i></button><?php }
echo $n." likes";
?> &nbsp;
<a href="edit.php?postid=<?php echo $post_id;?>"> Edit/Delete</a>

<br>
<br>
<?php }
?>

</div>
</div>
</div>
</div>
</div>                   
</body>
<script type="text/javascript">
function liker(post_id) {
    $.post('like.php',{postid:post_id,liked:'1'},
    function(data)
    {
     	console.log();     
    	location.href = "profilehome.php";    
    });
}
function unliker(post_id) {
    $.post('like.php',{postid:post_id,unliked:'1'},
    function(data)
    {
     	console.log();
    	location.href = "profilehome.php";    
    });
}

</script>
</html>