<?php 
include 'database.php';
if (isset($_POST['accept'])){
    $uid=$_POST['uid'];
    $sql="UPDATE relationship SET status=1 WHERE id='$uid'";
    $mysqli->query($sql);
}
if (isset($_POST['decline'])){
    $uid=$_POST['uid'];
    $sql="DELETE FROM relationship WHERE id='$uid'";
    $mysqli->query($sql);
}
?>