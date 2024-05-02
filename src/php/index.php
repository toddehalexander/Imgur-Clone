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

// Clear the file name
$image_name = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_FILES['image']['tmp_name'])) {
    $image_name = $_FILES['image']['name'];
    $image = $_FILES['image']['tmp_name'];
    $image_type = $_FILES['image']['type'];

    // Check if the uploaded file is a valid image format
    $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
    if (!in_array($image_type, $allowed_types)) {
        echo "Error: Only JPG, PNG, and GIF files are allowed.";
        exit();
    }

    $imgContent = addslashes(file_get_contents($image));

    $sql = "INSERT INTO image_database.image_table (image_name, image_file) VALUES ('$image_name', '$imgContent')";

    if ($mysqli->query($sql) === TRUE) {
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
    <title>Upload Image</title>
    <link rel="stylesheet" href="css/styles.css">
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

<div id="drag-drop-container" class="drop-zone">
    <h2 style="text-decoration: underline;">Upload Image</h2>
    <p>Accepted file types: <br><div style="padding-top: .2em; font-style: italic;">JPG, PNG, GIF</div></p>    
    <div class="drop-message">
    <div class="upload-icon"></div>
    <span id="file-name"></span>
    </div>
    <form id="upload-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <input type="file" name="image" id="image" class="file-input" accept="image/*">
    </form>
</div>

    <button id="upload-button" class="upload-button" disabled>Upload Image</button>

<script>
    const dropZone = document.querySelector('.drop-zone');
    const input = document.getElementById('image');
    const form = document.getElementById('upload-form');
    const uploadButton = document.getElementById('upload-button');

    dropZone.addEventListener('click', () => {
        input.click();
    });

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('drag-over');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        input.files = e.dataTransfer.files;
        handleFileUpload(file);
    });

    input.addEventListener('change', () => {
        const file = input.files[0];
        handleFileUpload(file);
    });

    function handleFileUpload(file) {
        // Check if the file is of a valid image type
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            // Display an alert if the file is not a valid image
            alert('Please upload a JPG, PNG, or GIF file.');
            // Clear the file input
            input.value = '';
            uploadButton.classList.remove('enabled');
            uploadButton.setAttribute('disabled', 'disabled');
        } else {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => {
                uploadButton.classList.add('enabled');
                uploadButton.removeAttribute('disabled');
            };
        }
    }

    uploadButton.addEventListener('click', () => {
        // Check if a file is selected
        if (input.files.length === 0) {
            alert('Please select a file to upload.');
            return;
        }

        // Check if the selected file is of a valid image type
        const file = input.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please upload a JPG, PNG, or GIF file.');
            input.value = '';
            return;
        }

        form.submit();
    });
    input.addEventListener('click', (e) => {
    e.stopPropagation();
});

window.onload = function() {
    // Force a hard refresh
    if (window.performance) {
        if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
            // Page was reloaded
        } else {
            // Page was not reloaded, force a hard refresh
            location.reload(true);
        }
    }
};
</script>
</body>
</html>