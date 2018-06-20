<?php
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
$path = $row['profile_img'];
$uid = $row['user_id'];

?>

<html>
  
<head>
	
	<link rel="stylesheet" href="css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/slate.min.css"/>
	<link rel="stylesheet" href="css/fontawesome-all.css"/>  
  	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<title>Welcome to My Network</title>
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
<i class="fab fa-fort-awesome"></i>   My network
  </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item ">
        <a class="nav-link" href="newsfeed.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profilehome.php">Profile</a>
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
<form action=""  method="post" enctype="multipart/form-data">

<div class="col-sm-6 mx-auto">
<div class="card">

<div class="card-body">

<br>
<?php 
$check = "SELECT * FROM relationship WHERE receiver_id='$currentuserid' AND status=0";
$rescheck =$mysqli->query($check);
while($rowcheck = mysqli_fetch_array($rescheck)){
    $id = $rowcheck['id'];
    $senderid = $rowcheck['user_id'];
    $sql = "SELECT * FROM user_info WHERE user_id='$senderid' AND user_name<>'$currentuser';";
    $result = $mysqli->query($sql);
    $row=mysqli_fetch_array($result);
        $uid = $row['user_id'];
        $uname = $row['user_name'];
        $imagepath = $row['profile_img'];
?>
	 	<div class="card col-sm-6 mx-auto">
        <div class="card-header">
        <div >
        <a href="profileview.php?name=<?php echo $uname;?>">
		<?php if ($imagepath!=''){?>
        <img src="<?php echo $imagepath;?>" width="50" height="50" class="d-inline-block align-top rounded-circle" alt="">
        <?php }?>
        <?php if ($imagepath==''){?>
        <i class="fas fa-user-circle" style="font-size:50px;"></i>
        <?php }?>
        <?php echo $row['first_name']." ".$row['last_name'];?>
        </a>
        &nbsp;
        &nbsp;
        &nbsp;
        &nbsp;
        <button  type="button" class="btn btn-link" onclick="accept('<?php echo $id;?>')" ><i class="fas fa-user-plus" style="font-size: 25px"></i></button>
		&nbsp;
        <button  type="button" class="btn btn-link" onclick="decline('<?php echo $id;?>')" ><i class="fas fa-user-times" style="font-size: 25px"></i></button>
		
        </div>
  		</div>
</div>
<br>
<br>
<?php }?>

</div>
<br>


    
</div>
</div>
	</form>
<br>
                  
</body>
<script type="text/javascript">

function accept(id) {
    $.post('friendship.php',{uid:id,accept:1},
    function(data)
    {
        console.log();
    	location.href = "friends.php";    
    });
}

function decline(id) {
    $.post('friendship.php',{uid:id,decline:1},
    function(data)
    {
        console.log();
    	location.href = "friends.php";    
    });
}
</script>

</html>