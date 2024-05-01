# Imgur Clone Overview

This document provides an overview of the ports used in the provided Docker Compose file and how to run the project on your local machine.

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

## How to Run

1. Navigate to the `backend` folder of **this** project in your terminal:
    ```
    cd path/to/backend
    ```

2. Run the following command to start the Docker containers:
    ```
    docker compose up
    ```

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

  **To be continued**
