<?php
// Database configuration
$host = 'localhost'; // MySQL host
$dbname = 'your_database_name'; // Database name
$username = 'your_username'; // Database username
$password = 'your_password'; // Database password

// Create a PDO instance (connect to database)
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if a file was uploaded
if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
    // Validate file type (ensure it's an image)
    $fileType = $_FILES['file']['type'];
    $allowedTypes = ['image/png', 'image/jpeg', 'image/gif'];

    if (in_array($fileType, $allowedTypes)) {
        // Define upload directory (adjust as needed)
        $uploadDirectory = 'uploads/';

        // Create uploads directory if it doesn't exist
        if (!file_exists($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        // Generate a unique filename
        $fileName = uniqid('img_') . '_' . basename($_FILES['file']['name']);
        $targetPath = $uploadDirectory . $fileName;

        // Attempt to move uploaded file to specified directory
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
            // File uploaded successfully, insert into database
            $sql = "INSERT INTO images (filename, filepath) VALUES (:filename, :filepath)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['filename' => $fileName, 'filepath' => $targetPath]);

            // Return success response
            echo json_encode(['success' => true, 'fileName' => $fileName]);
        } else {
            // Error moving file
            echo json_encode(['success' => false, 'message' => 'Error uploading file.']);
        }
    } else {
        // Invalid file type
        echo json_encode(['success' => false, 'message' => 'Invalid file type.']);
    }
} else {
    // Error in file upload
    echo json_encode(['success' => false, 'message' => 'Error in file upload.']);
}
?>
