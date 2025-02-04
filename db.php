<?php
$host = "localhost";  // or "127.0.0.1"
$username = "root";   // Default XAMPP MySQL user
$password = "";       // Default XAMPP MySQL has no password
$database = "useprofile"; // Your database name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
