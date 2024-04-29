<!DOCTYPE html>
<html>
<head>
    <title>Upload Image</title>
</head>
<body>


<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="image" id="image">
    <input type="submit" value="Upload Image" name="submit">
</form>

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

        // Devin: NEED TO REDIRECT USER, BECAUSE IF PAGE IS REFRESHED IT WILL UPLOAD TO DATABASE AGAIN
        // There probably is a lot better solution, like clearing the form on refresh? Not sure. What do I know? I just
        // use chatGPT and don't know anything about web development.
        if ($mysqli->query($sql) === TRUE) {
            echo "Uploaded $image_name successfully.";
        } else {
            echo "Error uploading image: " . $mysqli->error;
        }
    }

?>




</body>
</html>


