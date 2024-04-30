<?php

$servername = "172.20.0.5";
$username = "root";
$password = "root";

$mysqli = new mysqli($servername, $username, $password);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS image_database";
$mysqli->query($sql);

$sql = "CREATE TABLE IF NOT EXISTS image_database.image_table (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    image_name VARCHAR(255) NOT NULL,
    image_file LONGBLOB NOT NULL
)";
$mysqli->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES['image']['tmp_name'])) {
    $image_name = $_FILES['image']['name']; 
    $image = $_FILES['image']['tmp_name'];
    $imgContent = addslashes(file_get_contents($image));

    $sql = "INSERT INTO image_database.image_table (image_name, image_file) VALUES ('$image_name', '$imgContent')";

    if ($mysqli->query($sql) === TRUE) {
        // Redirect the user to the gallery.php page
        header("Location: gallery.php");
        exit();
    } else {
        echo "Error uploading image: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Upload Image</title>
</head>
<body>

    <nav>
        <div class="nav-container">
            <a href="index.php" class="nav-logo">Image Upload</a>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="gallery.php">Gallery</a></li>
            </ul>
        </div>
    </nav>

    <div id="upload-container">
        <h2>Upload Image</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <label for="image" class="file-label">Select image to upload:</label>
            <input type="file" name="image" id="image" class="file-input">
            <input type="submit" value="Upload Image" name="submit" class="upload-button">
        </form>
    </div>

</body>
</html>