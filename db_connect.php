<?php
// database/db_connect.php - Database Connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "food_waste_management";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
