<?php
include 'database.php';
session_start();
$currentuserid = $_SESSION['userid'];
    
    $postid = $_POST['postid'];
    $text = $_POST['comm'];
    $sql = "INSERT INTO comments VALUES ('','$text','$currentuserid','$postid',sysdate());";
    $result = $mysqli->query($sql);

?>