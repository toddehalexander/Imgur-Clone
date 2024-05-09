<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/gallery.css">
</head>

<body>
    <!-- Navigation Bar -->
    <nav>
        <div class="nav-container">
            <a href="index.php" class="nav-logo">Image Upload</a>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="gallery.php" class="active">Gallery</a></li>
            </ul>
        </div>
    </nav>

    <!-- Gallery Page -->
    <div class="container">
        <h2 class="heading-text"><span>Image</span> Gallery</h2>
        <ul class="image-gallery">
        <?php
            $servername = "172.20.0.5";
            $username = "root";
            $password = "root";

            $mysqli = new mysqli($servername, $username, $password);

            if ($mysqli->connect_error) {
                die("Connection failed: " . $mysqli->connect_error);
            }

            // SQL query to select all images from the database
            $sql = "SELECT * FROM image_database.image_table";
            $result = $mysqli->query($sql);

            // If there are images in the result
            if ($result->num_rows > 0) {
                // Loop through each image in the result
                while ($row = $result->fetch_assoc()) {
                    // Converting image data to base64 format
                    $base64 = base64_encode($row['image_file']);
                    // Creating image data URL
                    $imageData = "data:image/jpeg;base64," . $base64;
                    // Generating URL for viewing the image
                    $imageUrl = "view-image.php?id=" . $row['id'];
                    // Displaying image and its details
                    echo "<li>";
                    echo "<a href='" . $imageUrl . "' target='_blank' class='image-link'>";
                    echo "<img src='" . $imageData . "' alt='" . $row['image_name'] . "' />";
                    echo "<div class='overlay'><span>" . $row['image_name'] . "</span></div>";
                    echo "</a>";
                    echo "</li>";
                }
            } else {
                // If no images are uploaded yet
                echo "<li><p>No images uploaded yet.</p></li>";
            }

            $mysqli->close();
            ?>
        </ul>
    </div>
</body>

</html>
