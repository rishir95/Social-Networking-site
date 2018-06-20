<?php
include 'database.php';
session_start();
$currentuserid = $_SESSION['userid'];
    
if (isset($_POST['liked'])) {
    $postid = $_POST['postid'];
    $sql = "SELECT * FROM posts WHERE post_id=$postid;";
    $result = $mysqli->query($sql);
    $row = mysqli_fetch_array($result);
    $no = $row['likes'];
    
    $sql = "INSERT INTO post_like VALUES ('',$postid,'$currentuserid');";
    $result = $mysqli->query($sql);
    
    
    $sql = "UPDATE posts SET likes=$no+1 WHERE post_id=$postid;";
    $result = $mysqli->query($sql);
    
    echo $no;
}
if (isset($_POST['unliked'])) {
    $postid = $_POST['postid'];
    $sql = "SELECT * FROM posts WHERE post_id=$postid";
    $result = $mysqli->query($sql);
    $row = mysqli_fetch_array($result);
    $no = $row['likes'];
    
    $sql1 = "DELETE FROM post_like WHERE post_id=$postid AND user_id='$currentuserid'";
    $mysqli->query($sql1);
    
    $sql2 = "UPDATE posts SET likes=$no-1 WHERE post_id=$postid";
    $mysqli->query($sql2);
    echo "Uncas";
}

?>