<?php

// Retrieve form data
$hostname = $_POST['hostname'];
$username = $_POST['username'];
$password = $_POST['password'];
$database = $_POST['database'];

// Attempt MySQL server connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";

// Close connection
$conn->close();

?>

