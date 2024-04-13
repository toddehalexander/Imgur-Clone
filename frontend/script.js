const dragDropContainer = document.getElementById('drag-drop-container');
const fileInput = document.getElementById('file-input');
const uploadForm = document.getElementById('upload-form');

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

  // Check if the file is a PNG or JPEG
  if (file.type === 'image/png' || file.type === 'image/jpeg') {
    fileInput.files = event.dataTransfer.files;
    uploadForm.submit();
  } else {
    alert('Please drop a PNG or JPEG file.');
  }
});

dragDropContainer.addEventListener('click', () => {
  fileInput.click();
});