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
        // Log the uploaded file name and current time to a text file
        $logMessage = "File '$image_name' uploaded at " . date("Y-m-d H:i:s") . "\n";
        file_put_contents("upload_log.txt", $logMessage, FILE_APPEND | LOCK_EX);
        
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
    const dropZone = document.querySelector('.drop-zone'); // Selects the drop zone element
    const input = document.getElementById('image'); // Selects the file input element
    const form = document.getElementById('upload-form'); // Selects the upload form element
    const uploadButton = document.getElementById('upload-button'); // Selects the upload button element
    const imagePreview = document.getElementById('image-preview'); // Selects the image preview element
    const previewPlaceholder = document.getElementById('preview-placeholder'); // Selects the preview placeholder element

    // Event listener for click on the drop zone, triggers file input click
    dropZone.addEventListener('click', () => {
        input.click();
    });

    // Event listener for drag over the drop zone, adds CSS class for visual feedback
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });

    // Event listener for drag leave the drop zone, removes CSS class for visual feedback
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('drag-over');
    });

    // Event listener for drop on the drop zone, handles dropped files
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        input.files = e.dataTransfer.files;
        handleFileUpload(file);
    });

    // Event listener for file input change, handles selected files
    input.addEventListener('change', () => {
        const file = input.files[0];
        handleFileUpload(file);
    });

    // Function to handle file upload, checks file type and size, and displays preview
    function handleFileUpload(file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            // Display an alert if the file is not a valid image
            alert('Please upload a JPG, PNG, or GIF file.');
            input.value = '';
            uploadButton.classList.remove('enabled');
            uploadButton.setAttribute('disabled', 'disabled');
            imagePreview.src = '';
            previewPlaceholder.style.display = 'block';
            return;
        }
        
        const maxSize = 2 * 1024 * 1024; // 2 MB in bytes
        if (file.size > maxSize) {
            // Display an alert if the file size exceeds the limit
            alert('File size exceeds the limit of 2 MB.');
            input.value = '';
            uploadButton.classList.remove('enabled');
            uploadButton.setAttribute('disabled', 'disabled');
            imagePreview.src = '';
            previewPlaceholder.style.display = 'block';
            return;
        }
            // Display the file name in the drop zone
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => {
            imagePreview.src = reader.result;
            uploadButton.classList.add('enabled');
            uploadButton.removeAttribute('disabled');
            previewPlaceholder.style.display = 'none';
        };
    }

    // Event listener for upload button click, submits the form if file is selected and valid
    uploadButton.addEventListener('click', () => {
        if (input.files.length === 0) {
            alert('Please select a file to upload.');
            return;
        }

        const file = input.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please upload a JPG, PNG, or GIF file.');
            input.value = '';
            return;
        }

        form.submit();
    });

    // Prevents file manager opening 2 times if clicked "choose file", counts for both the button and the drop zone
    input.addEventListener('click', (e) => {
        e.stopPropagation();
    });
</script>
</body>
</html>
