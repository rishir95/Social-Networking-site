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
}

if(isset($_POST['searchvalue'])){
    $searchvalue= $_POST['searchvalue'];
    $val = "unknown";
}
if(isset($_GET['query'])){
    $searchvalue= $_GET['query'];
    $val = $_GET['val'];
}
?>
<?php 
$sql = "SELECT * FROM user_info u, locations l, gender g WHERE u.location=l.location_id AND u.gender=g.gender_id AND user_id='$currentuserid'";
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
      <li class="nav-item ">
        <a class="nav-link" href="newsfeed.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profilehome.php"><?php if ($path!=''){?>
        <img src="<?php echo $path;?>" width="30" height="30" class="d-inline-block align-top rounded-circle" alt="">
        <?php }?>
        <?php if ($path==''){?>
        <i class="fas fa-user-circle"></i>
        <?php }?> <?php echo $row['first_name'];?></a>
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
<form action=""  method="post" enctype="multipart/form-data">

<div class="col-sm-8 mx-auto">
<div class="card">
<div class="card-header">
<nav class="navbar navbar-expand-lg navbar-primary bg-dark ">
  
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
      <?php if ($val=='people'){?>
      <li class="nav-item active">
        <a class="nav-link" href="search.php?val=people&query=<?php echo $searchvalue;?>">People <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="search.php?val=posts&query=<?php echo $searchvalue;?>">Posts <span class="sr-only">(current)</span></a>
      </li>
      <?php }?>
      <?php if ($val=='posts'){?>
		<li class="nav-item ">
        <a class="nav-link" href="search.php?val=people&query=<?php echo $searchvalue;?>">People <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="search.php?val=posts&query=<?php echo $searchvalue;?>">Posts <span class="sr-only">(current)</span></a>
      </li>
      <?php }?>
      <?php if ($val=='unknown'){?>
      <li class="nav-item ">
        <a class="nav-link" href="search.php?val=people&query=<?php echo $searchvalue;?>">People <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="search.php?val=posts&query=<?php echo $searchvalue;?>">Posts <span class="sr-only">(current)</span></a>
      </li>
      <?php }?>
    </ul>
    
   </div>
    
    
</nav>
</div>
<div class="card-body">

<br>
<?php if ($searchvalue!=""){?>
<?php if ($val=='people'){
//exclude logged in user
    $sql = "SELECT * FROM user_info WHERE (user_name LIKE '%$searchvalue%' OR first_name LIKE '%$searchvalue%' OR last_name LIKE '%$searchvalue%') AND user_id<>'$currentuserid';";
    $result = $mysqli->query($sql);
    while ($row=mysqli_fetch_array($result)){
        $uid = $row['user_id'];
        $uname = $row['user_name'];
        $imagepath = $row['profile_img'];
        $friend = "SELECT * FROM relationship WHERE (user_id='$currentuserid' OR receiver_id='$currentuserid') AND (user_id=$uid OR receiver_id=$uid) AND status=1;";
        $resultfriend = $mysqli->query($friend);
        $sentfriend = "SELECT * FROM relationship WHERE (user_id='$currentuserid' OR receiver_id='$currentuserid') AND (user_id=$uid OR receiver_id=$uid) AND status=0;";
        $resultsent = $mysqli ->query($sentfriend);?>
	 <div class="card">
        <div class="d-flex card-header">
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
		
		<div class="ml-auto">
         <?php if ($resultsent->num_rows>0){?>
         <br>
         Friend request Pending
        <?php }?>
        </div>
		        
        <div class="ml-auto">
         <?php if ($resultsent->num_rows<=0 && $resultfriend->num_rows<=0){?>
        <input type="submit" class="btn btn-link my-2 my-sm-0" value="Add friend" onclick="friend('<?php echo $uid;?>')">
        <?php }?>
        </div>
        <div class="ml-auto">
        <?php if ($resultfriend->num_rows>0){?>
		<br>
		Friends
		<?php  }?>
        </div>
  		</div>
</div>
</div>
<br>

<?php }?>
<?php }?>

<?php if ($val=='posts'){
//exclude logged in user
    $sql = "SELECT *,DATE_FORMAT(post_time, '%W %M %e %Y %I:%i %p') AS dates FROM posts WHERE post_text LIKE '%$searchvalue%';";
    $result = $mysqli->query($sql);
    while ($row=mysqli_fetch_array($result)){
        $text = $row['post_text'];
        $post_id = $row['post_id'];
        $n = $row['likes'];
        $date = $row['dates'];
        $uid = $row['user_id'];
        $imagepath = $row['post_imagepath'];
        $userquery = "SELECT * FROM user_info WHERE user_id=$uid;";
        $results = $mysqli->query($userquery);
        $row1=mysqli_fetch_array($results);
        ?>
        <div class="card">
        <div class="d-flex card-header">
        <div >
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
        <p class="card-text"><?php echo $text;?></p>
        
        </div>
        </div>
        <?php if ($res->num_rows==1){?>
        <button  type="button" class="btn btn-link" onclick="unliker('<?php echo $post_id;?>')" ><i class="fas fa-thumbs-up"></i></button>
        <?php } else {?>
        <button  type="button" class="btn btn-link" onclick="liker('<?php echo $post_id;?>')" ><i class="far fa-thumbs-up"></i></button><?php }
        echo $n." likes";
        ?>
        <br>
        <br>
<?php }
?>
<?php }?>
<?php if ($val=='unknown'){
    echo "Select People or posts";}?>
    <?php }
    else{
    echo "Enter something";
    }?>
    
</div>
</div>
</div>
</form>
<br>
                  
</body>
</html>

<script type="text/javascript">
function liker(post_id) {
    $.post('like.php',{postid:post_id,liked:'1'},
    function(data)
    {
     	console.log();     
    	location.href = "search.php?val=<?php echo $val?>&query=<?php echo $searchvalue?>";    
    });
}
function unliker(post_id) {
    $.post('like.php',{postid:post_id,unliked:'1'},
    function(data)
    {
     	console.log();
    	location.href = "search.php?val=<?php echo $val?>&query=<?php echo $searchvalue?>";    
    });
}
function friend(uid) {
    $.post('friendrequest.php',{uid:uid},
    function(data)
    {
     	console.log();
    	location.href = "search.php?val=<?php echo $val?>&query=<?php echo $searchvalue?>";    
    });
}
</script>