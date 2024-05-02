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

<div id="image-preview-container" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
    <h2>Upload Preview</h2>
    <span id="preview-placeholder" style="display: block;">A preview will appear here once you upload.</span>
    <img id="image-preview" src="#" alt="" style="height: 300px;">
</div>

<script>
    const dropZone = document.querySelector('.drop-zone');
    const input = document.getElementById('image');
    const form = document.getElementById('upload-form');
    const uploadButton = document.getElementById('upload-button');
    const imagePreview = document.getElementById('image-preview');
    const previewPlaceholder = document.getElementById('preview-placeholder');

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
            // Clear the image preview
            imagePreview.src = '';
            previewPlaceholder.style.display = 'block';
            return;
        }
        
        // Check if the file size is within the limit
        const maxSize = 2 * 1024 * 1024; // 2 MB in bytes
        if (file.size > maxSize) {
            // Display an alert if the file size exceeds the limit
            alert('File size exceeds the limit of 2 MB.');
            // Clear the file input
            input.value = '';
            uploadButton.classList.remove('enabled');
            uploadButton.setAttribute('disabled', 'disabled');
            // Clear the image preview
            imagePreview.src = '';
            previewPlaceholder.style.display = 'block';
            return;
        }

        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => {
            imagePreview.src = reader.result;
            uploadButton.classList.add('enabled');
            uploadButton.removeAttribute('disabled');
            previewPlaceholder.style.display = 'none';
        };
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

</script>
</body>
</html>
