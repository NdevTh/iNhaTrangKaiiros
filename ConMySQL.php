<?php
$servername = "localhost";
//$username = "ct-huynh_eshop";
//$password = "3shop2025**";
$username = "ct-huynh";
$password = "fxdMNbTiCj9sjsG";
$dbname = "ct-huynh_eshop";

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>