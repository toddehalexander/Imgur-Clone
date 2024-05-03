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

      $sql = "SELECT * FROM image_database.image_table";
      $result = $mysqli->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $base64 = base64_encode($row['image_file']);
          $imageData = "data:image/jpeg;base64," . $base64;
          echo "<li>";
          echo "<img src='" . $imageData . "' alt='" . $row['image_name'] . "' onclick='openImage(\"" . $imageData . "\")' />";
          echo "<div class='overlay' onclick='openImage(\"" . $imageData . "\")'><span>" . $row['image_name'] . "</span></div>";
          echo "</li>";
      }
      } else {
        echo "<li><p>No images uploaded yet.</p></li>";
      }

      $mysqli->close();
      ?>
    </ul>
  </div>
  <script>
function openImage(url) {
    var img = new Image();
    img.src = url;
    img.onload = function() {
        var win = window.open();
        win.document.write("<img src='" + url + "' />");
    }
}
</script>
</body>

</html>