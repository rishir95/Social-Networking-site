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

?>
<?php 
$sql = "SELECT * FROM user_info u, locations l, gender g WHERE u.location=l.location_id AND u.gender=g.gender_id AND user_id='$currentuserid'";
$result = $mysqli->query($sql);
$row = mysqli_fetch_array($result);
$path = $row['profile_img'];
$uid = $row['user_id'];
$uname = $row['first_name'];
?>
<?php 
if (isset($_POST['postit'])){
    //echo $_POST['privacy'];
    
    $posttext = $_POST['posttext'];
    $imagename=$_FILES["images"]["name"];
    $imageval=$_FILES['images']['tmp_name'];
    $privacy = $_POST['privacy'];
    
    // $caption=mysqli_escape_string($mysqli, $caption);
    if ($imagename==''){
        $imagepath="";
    }
    else {
    $imagepath="posts/".($uid).$imagename;
    
    // The images are stored in the photo directory
    move_uploaded_file($imageval, $imagepath);
    }
    if($posttext=='' && $imagename==''){
        
    }
    else {
        $querypost = "INSERT INTO posts (`user_id`, `post_text`, `post_time`, `post_imagepath`, `likes`,`privacy_code`) VALUES ('$currentuserid', '$posttext',sysdate(), '$imagepath',0,'$privacy');";
        $mysqli->query($querypost);
    }
}

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
<i class="fab fa-fort-awesome"></i>  The Network   </a>
   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="newsfeed.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="profilehome.php">
		<?php if ($path!=''){?>
        <img src="<?php echo $path;?>" width="30" height="30" class="d-inline-block align-top rounded-circle" alt="">
        <?php }?>
        <?php if ($path==''){?>
        <i class="fas fa-user-circle"></i>
        <?php }?> <?php echo $uname;?>
        </a>
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
<div class="card-header">Post your new findings</div>
<div class="card-body">

<div class="input-group">
  <div class="custom-file">
    <input type="file" class="custom-file-input" id="file" accept="image/*" onchange="return fileValidation()" name="images">
    <label class="custom-file-label" for="inputGroupFile04">Choose Image file or Video</label>
  </div>
</div>
<br>
<div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Your latest Findings..." aria-label="Username" aria-describedby="basic-addon1" name="posttext" style="height: 100px;">
</div>
<div class="input-group">
 
 <div class="form-group" style="height: 30px;">
       
       <select class="form-control" id="Privacy" name="privacy" style="height: 45px;">
      <option value="1">Public</option>
      <option value="2">Friends</option>
      <option value="3">Private</option>
          </select>
 	
  </div>
<input type="submit" value="Upload" class="btn btn-primary" style="height: px;" name="postit">
 
</div>
                  
</div>
</div>
</div>
</form>
<br>
<div class="col-sm-8 mx-auto">

<!-- Card 2 -->
<div class="card">
<div class="card-header">Posts</div>
<div class="card-body">
<?php 
$sql= "SELECT *,DATE_FORMAT(post_time, '%W %M %e %Y %I:%i %p') AS dates FROM posts WHERE (user_id in(SELECT user_id FROM relationship WHERE status =1 AND (receiver_id ='$currentuserid' OR user_id='$currentuserid')
UNION
SELECT receiver_id FROM relationship WHERE status =1 AND (receiver_id ='$currentuserid' OR user_id='$currentuserid')) AND (privacy_code=1 OR privacy_code=2) )OR privacy_code='1' OR user_id='$currentuserid' ORDER BY post_time DESC;";
$result = $mysqli->query($sql);

if ($result->num_rows<=0){?>
<p align="center" style="font-size: 40px;">Welcome to The Network <i class="fab fa-fort-awesome" style="font-size: 40px;"></i></p>
<br>
<br>
<p align="center" style="font-size: 20px;">Looks like you don't have any feeds. Go connect with new friends</p>

<?php }
if ($result->num_rows>0){
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
        ?>
        <div class="input-group mb-3">
  		<input type="text" class="form-control" placeholder="Comment..." aria-label="Recipient's username" aria-describedby="basic-addon2" id="comm<?php echo $post_id?>">
  		<div class="input-group-append">
    	<span  id="basic-addon2"><button  type="button" class="btn btn-link" onclick="comment('<?php echo $post_id;?>','<?php echo $currentuserid;?>',document.getElementById('comm<?php echo $post_id?>').value)" ><i class="fas fa-chevron-circle-right"></i></button></span>
  		</div>
		</div>
		<?php 
		$querycomment = "SELECT *,DATE_FORMAT(comment_date, '%W %M %e %Y %I:%i %p') AS dates FROM comments c,user_info u WHERE c.user_id=u.user_id AND post_id='$post_id';";
        $resultcomm = $mysqli->query($querycomment);
        
        if($resultcomm->num_rows>0){
            while ($rowcomm=mysqli_fetch_array($resultcomm)){
                $profilepath = $rowcomm['profile_img'];
                $comments = $rowcomm['comment'];
                $datecomm = $rowcomm['dates'];
                $namecommentor =$rowcomm['first_name']." ".$rowcomm['last_name'];
        ?><div class="mx-auto">
                <?php if ($profilepath!=''){?>
        <img src="<?php echo $profilepath;?>" width="30" height="30" class="d-inline-block align-top rounded-circle" alt="">
        <?php }?>
        <?php if ($profilepath==''){?>
        <i class="fas fa-user-circle"></i>
        <?php }?>
        <b>
        <?php 
                echo $namecommentor;
           ?>
           </b> &nbsp;
           <?php      
                echo $comments;
                ?><br>
                <div class="ml-auto">
                <?php echo $datecomm;?>
                </div>
            </div>
            <?php }
            
        }?>
        <br>
        <br>
        <hr>
<?php }
}?>

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
    	location.href = "newsfeed.php";    
    });
}
function unliker(post_id) {
    $.post('like.php',{postid:post_id,unliked:'1'},
    function(data)
    {
     	console.log();
    	location.href = "newsfeed.php";    
    });
}
function comment(post_id,userid,comment) {
    $.post('comment.php',{postid:post_id,user:userid,comm:comment},
    function(data)
    {
     	console.log();
    	location.href = "newsfeed.php";    
    });
}
function search(value) {
    $.post('like.php',{searchvalue:value, liked:'1'},
    function(data)
    {
     	console.log(searchvalue);
    });
}
</script>
</html>

