<?php
$servername = "172.20.0.5";
$username = "root";
$password = "root";

$mysqli = new mysqli($servername, $username, $password);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM image_database.image_table WHERE id = $id";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $base64 = base64_encode($row['image_file']);
        $imageData = "data:image/jpeg;base64," . $base64;
        echo "<img src='" . $imageData . "' alt='" . $row['image_name'] . "' />";
    } else {
        echo "Image not found.";
    }
} else {
    echo "Invalid image ID.";
}

$mysqli->close();
?>