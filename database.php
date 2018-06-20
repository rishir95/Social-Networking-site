<!-- This php file is used for connection to the database  -->

<?php
/* Database connection settings */
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'social_network1';
$mysqli = new mysqli($host,$user,$pass,$db) or die($mysqli->error);
?>
