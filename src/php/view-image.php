<?php
$servername = "172.20.0.5";
$username = "root";
$password = "root";

$mysqli = new mysqli($servername, $username, $password);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    // Get the 'id' parameter from the URL
    $id = $_GET['id'];
    
    // SQL query to select image data from the database based on the provided 'id'
    $sql = "SELECT * FROM image_database.image_table WHERE id = $id";
    
    // Execute the SQL query
    $result = $mysqli->query($sql);

    // Check if any rows are returned
    if ($result->num_rows > 0) {
        // Fetch the first row from the result set
        $row = $result->fetch_assoc();
        
        // Encode the image file data in base64 format
        $base64 = base64_encode($row['image_file']);
        
        // Construct the data URI for displaying the image
        $imageData = "data:image/jpeg;base64," . $base64;
        
        // Display the image using an HTML <img> tag
        echo "<img src='" . $imageData . "' alt='" . $row['image_name'] . "' />";
    } else {
        // Display message if no image is found with the provided 'id'
        echo "Image not found.";
    }
} else {
    // Display message if 'id' parameter is not provided in the URL
    echo "Invalid image ID.";
}

$mysqli->close();
?>
