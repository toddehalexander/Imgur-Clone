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
    if (file && (file.type === 'image/png' || file.type === 'image/jpeg' || file.type === 'image/gif')) {
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

        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('File uploaded successfully!');
                // Optionally handle the response, e.g., display uploaded file details
                console.log('Uploaded filename:', data.fileName);
            } else {
                alert('File upload failed. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error uploading file:', error);
            alert('File upload failed. Please try again.');
        });
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
