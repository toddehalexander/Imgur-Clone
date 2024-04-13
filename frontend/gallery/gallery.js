// gallery.js

// Fetch the list of uploaded images from the server
fetch('get_uploaded_images.php')
  .then(response => response.json())
  .then(images => {
    const imageGrid = document.getElementById('image-grid');

    // Generate HTML elements for each image
    images.forEach(image => {
      const imageElement = document.createElement('img');
      imageElement.src = `uploads/${image}`;
      imageElement.alt = image;
      imageGrid.appendChild(imageElement);
    });
  })
  .catch(error => {
    console.error('Error fetching uploaded images:', error);
  });