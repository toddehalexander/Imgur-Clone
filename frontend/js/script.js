const dragDropContainer = document.getElementById('drag-drop-container');
const fileInput = document.getElementById('file-input');
const uploadButton = document.getElementById('upload-button');
let selectedFile = null;

// Initially disable the upload button and set its background color to grey
disableUploadButton();

dragDropContainer.addEventListener('dragover', (event) => {
  event.preventDefault();
  dragDropContainer.classList.add('drag-over');
});

dragDropContainer.addEventListener('dragleave', () => {
  dragDropContainer.classList.remove('drag-over');
});

dragDropContainer.addEventListener('drop', (event) => {
  event.preventDefault();
  dragDropContainer.classList.remove('drag-over');

  // Get the first file from the dropped files
  const file = event.dataTransfer.files[0];

  // Check if the file is a PNG, JPEG, or GIF
  if (file.type === 'image/png' || file.type === 'image/jpeg' || file.type === 'image/gif') {
    selectedFile = file;
    updateFileInfo(file.name);
    enableUploadButton();
  } else {
    alert('Please drop a PNG, JPEG, or GIF file.');
  }
});

dragDropContainer.addEventListener('click', () => {
  fileInput.click();
});

fileInput.addEventListener('change', () => {
  const file = fileInput.files[0];
  if (file && (file.type === 'image/png' || file.type === 'image/jpeg' || file.type === 'image/gif')) {
    selectedFile = file;
    updateFileInfo(file.name);
    enableUploadButton();
  } else {
    updateFileInfo('');
    disableUploadButton();
  }
});

uploadButton.addEventListener('click', () => {
  if (selectedFile) {
    const formData = new FormData();
    formData.append('file', selectedFile);
    // Send the formData to the server using fetch or XMLHttpRequest
    // ...
    console.log('Uploading file:', selectedFile.name);

    // Open the uploaded image in a new tab
    const imageUrl = URL.createObjectURL(selectedFile);
    window.open(imageUrl, '_blank');
  } else {
    alert('Please select a file to upload.');
  }
});

function updateFileInfo(fileName) {
  const fileInfo = document.getElementById('file-info');
  fileInfo.textContent = fileName ? `Selected file: ${fileName}` : '';
}

function enableUploadButton() {
  uploadButton.disabled = false;
  uploadButton.style.backgroundColor = '#4CAF50'; // Green
  uploadButton.style.cursor = 'pointer';
}

function disableUploadButton() {
  uploadButton.disabled = true;
  uploadButton.style.backgroundColor = '#ccc'; // Gray
  uploadButton.style.cursor = 'default';
}