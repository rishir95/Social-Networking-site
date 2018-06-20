<?php
ob_start();
session_start();

$timezone = date_default_timezone_set("America/New_York");

$dbservername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "social_network1";
// Create connection
$conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
