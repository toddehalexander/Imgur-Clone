# Imgur Clone

This is a web application that allows users to upload and view images, similar to the popular image hosting service Imgur. The application is built using PHP and MySQL, with Docker containers for easy deployment and management.

## Overview

  <img src="https://github.com/toddehalexander/Imgur-Clone/blob/main/assets/usecase.gif" alt="Your GIF">

## Features 

- **Image Upload**: Users can upload JPG, PNG, and GIF image files to the application.
- **Image Gallery**: All uploaded images are displayed in a gallery view, with thumbnails and image names.
- **Image Preview**: Users can preview uploaded images in a larger size by clicking on the thumbnail.
- **File Size Limit**: The application enforces a file size limit of 2 MB for uploaded images.
- **File Type Validation**: Only JPG, PNG, and GIF image files are accepted for upload.
- **Upload Logging**: The application logs the name and timestamp of each uploaded file to a text file (`upload_log.txt`).

## Technologies Used

- **PHP**: The server-side scripting language used for handling image uploads and database operations.
- **MySQL**: The relational database management system used for storing uploaded image data.
- **Docker**: The containerization platform used for packaging and deploying the application and its dependencies.
- **Docker Compose**: The tool used for defining and running multi-container Docker applications.
- **Adminer**: A web-based database management tool used for interacting with the MySQL database through a web interface.

## How to Run

1. Navigate to the `src` folder of **this** project in your terminal: `cd path/to/src`

2. Run the following command to start the Docker containers: `docker compose up`

3. Once the containers are up and running, you can access the services using the following URLs:

- PHP Runtime Service: [http://localhost:8000](http://localhost:8000)
- Adminer: [http://localhost:8080](http://localhost:8080)

4. Use the following login information to access Adminer and see the database of files:
- **Server**: `database`
- **Username**: `root`
- **Password**: `root`

5. After logging into Adminer, follow these steps:
- Click on `image_database` from the list of databases.
- Click on `image_table` from the list of tables.
- Select `Select data` to see the uploaded files information.

## Service Ports

### PHP Runtime Service

- **Container Port**: 8000
- **Host Port**: 8000
- **Description**: Exposes the PHP runtime environment for hosting PHP web applications.

### Database Service (MySQL)

- **Container Port**: 3306
- **Host Port**: 3306
- **Description**: Exposes the MySQL database server for storing and managing application data.

### Adminer Service

- **Container Port**: 8080
- **Host Port**: 8080
- **Description**: Exposes Adminer, a web-based database management tool, for interacting with the MySQL database through a web interface.

## Usage

1. Open the PHP Runtime Service URL ([http://localhost:8000](http://localhost:8000)) in your web browser.
2. You will be presented with the image upload page.
3. Drag and drop an image file (JPG, PNG, or GIF) onto the drop zone, or click the "Choose File" button to select a file from your local file system.
4. Once an image is selected, a preview will be displayed, and the "Upload Image" button will become enabled.
5. Click the "Upload Image" button to upload the selected image to the server.
6. After a successful upload, you will be redirected to the image gallery page, where you can view all uploaded images.
7. To view a larger version of an image, click on its thumbnail in the gallery.

## Contributing

Contributions to this project are welcome. If you find any issues or have suggestions for improvements, please open an issue or submit a pull request.

## License

This project is licensed under the [GNU License](LICENSE).
