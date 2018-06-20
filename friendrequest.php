<?php 
include 'database.php';
session_start();
$uid=$_POST['uid'];

    $currentuserid = $_SESSION['userid'];

$sql="INSERT INTO relationship VALUES('','$currentuserid','$uid',0);";
$mysqli->query($sql);
?>