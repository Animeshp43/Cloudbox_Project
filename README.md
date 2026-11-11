# ☁️ CloudBox – Cloud File Storage System

A Docker-based cloud file storage application built using **Nginx, PHP, MySQL, and Docker Compose**, deployed on an AWS EC2 instance.

CloudBox provides a web-based dashboard to upload, manage, view, and delete files. It uses MySQL to maintain file metadata and Docker volumes for persistent file storage.

---

## 🚀 Project Overview

CloudBox is a lightweight cloud storage solution developed to demonstrate the practical implementation of cloud infrastructure, containerization, networking, and web application deployment.

The application separates the frontend, backend, and database into individual containers, allowing each component to operate independently while communicating through a custom Docker bridge network.

## ✨ Features

- **File Upload:** Upload files through a web-based dashboard.
- **File Management:** View uploaded files and their metadata.
- **File Deletion:** Delete files from physical storage and the database.
- **Persistent Storage:** Store uploaded files using Docker named volumes.
- **Database Integration:** Maintain file information in MySQL.
- **Containerized Architecture:** Run services independently using Docker Compose.
- **Custom Docker Network:** Enable communication between application containers.
- **Nginx Web Server:** Serve the frontend and forward API requests to PHP-FPM.

## 🏗️ System Architecture

```text
                 User / Web Browser
                         |
                         |
                    HTTP :80
                         |
                         v
                 +---------------+
                 |     Nginx     |
                 |   Frontend    |
                 +---------------+
                         |
                      /api
                         |
                         v
                 +---------------+
                 |    PHP-FPM    |
                 |  Backend API  |
                 +---------------+
                    |         |
             SQL Queries     File I/O
                    |         |
                    v         v
              +----------+  +----------------+
              |  MySQL   |  | Docker Volume  |
              | Database |  | Uploaded Files |
              +----------+  +----------------+

           All containers communicate through
              cloudbox-network (Bridge)
```

## 🛠️ Technologies Used

| Technology | Purpose |
|---|---|
| AWS EC2 | Cloud hosting and deployment |
| Docker | Containerization |
| Docker Compose | Multi-container management |
| Docker Bridge Network | Container-to-container communication |
| Nginx | Web server and API request routing |
| PHP 8.3 | Backend API development |
| PHP-FPM | PHP application processing |
| MySQL 8.4 | Database and file metadata storage |
| HTML, CSS, JavaScript | Frontend dashboard |
| Docker Volumes | Persistent file storage |
| Git & GitHub | Version control and project hosting |

## 📁 Project Structure

```text
cloudbox/
│
├── api/
│   ├── Dockerfile
│   ├── db.php
│   ├── index.php
│   └── init.sql
│
├── filebrowser/
│   └── Dockerfile
│
├── nginx/
│   ├── Dockerfile
│   ├── nginx.conf
│   └── index.html
│
├── data/
├── uploads/
├── docker-compose.yml
├── .env
├── .gitignore
└── README.md
```

## ⚙️ How CloudBox Works

1. The user accesses the CloudBox dashboard through the EC2 public IP.
2. Nginx serves the frontend dashboard.
3. When a user uploads or deletes a file, the frontend sends an API request to the backend.
4. Nginx forwards API requests to PHP-FPM.
5. PHP processes the request and communicates with MySQL to store or retrieve file metadata.
6. Uploaded files are stored in a shared Docker volume.
7. The frontend retrieves updated file information from the API.

## 🔌 API Endpoints

The backend exposes the following endpoints:

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api` | Retrieve uploaded file information |
| POST | `/api` | Upload a file |
| DELETE | `/api?id=ID` | Delete a file using its ID |

## 🗄️ MySQL Database Management

CloudBox uses **MySQL 8.4** to store uploaded file metadata, including filename, file size, file type, storage path, and upload timestamp.

MySQL runs inside a dedicated Docker container named `cloudbox-mysql`.

### 1. Check Running Containers

```bash
docker compose ps
```

Displays the running CloudBox services, including MySQL.

### 2. Access the MySQL Container

```bash
docker exec -it cloudbox-mysql mysql -u root -p
```

Enter the MySQL root password configured in your `.env` file when prompted.

### 3. Show Available Databases

```sql
SHOW DATABASES;
```

### 4. Select the CloudBox Database

```sql
USE cloudbox;
```

### 5. Show Database Tables

```sql
SHOW TABLES;
```

### 6. View Uploaded File Records

```sql
SELECT * FROM files;
```

This displays the metadata of files uploaded through the CloudBox dashboard.

### 7. View Specific File Information

```sql
SELECT id, filename, file_size, file_type, uploaded_at
FROM files;
```

### 8. Exit MySQL

```sql
EXIT;
```

### Useful Docker Commands for MySQL

| Command | Purpose |
|---|---|
| `docker compose ps` | Check running services |
| `docker logs cloudbox-mysql` | View MySQL container logs |
| `docker restart cloudbox-mysql` | Restart MySQL container |
| `docker exec -it cloudbox-mysql bash` | Open a shell inside MySQL container |
| `docker compose down` | Stop and remove Compose containers |
| `docker compose up -d` | Start the application again |

**Note:** Database credentials are stored in `.env`. Never upload this file to GitHub. MySQL data is maintained using a persistent Docker volume.

## 🚀 Deployment Guide

### Prerequisites

- AWS account
- Amazon Linux EC2 instance
- Docker Engine
- Docker Compose
- Git

### 1. Clone the Repository

```bash
git clone https://github.com/Animeshp43/Cloudbox_Project.git
```

### 2. Enter the Project Directory

```bash
cd Cloudbox_Project
```

### 3. Configure Environment Variables

Create a `.env` file based on the required database configuration.

Keep database credentials private. Never commit `.env` to GitHub.

### 4. Build and Start the Containers

```bash
docker compose up -d --build
```

### 5. Check Running Containers

```bash
docker compose ps
```

### 6. Access CloudBox

Open your browser and visit:

```text
http://YOUR_EC2_PUBLIC_IP
```

Make sure the EC2 security group allows inbound HTTP traffic on port 80.

## 💾 Storage and Networking

### Docker Volumes

CloudBox uses persistent Docker storage so uploaded files remain available across container restarts and recreations.

### Docker Bridge Network

A custom bridge network named `cloudbox-network` enables internal communication between Nginx, PHP-FPM, MySQL, and the File Browser service.

## 🔐 Security Considerations

This project is intended for learning and demonstration purposes.

Before using it in a production environment, consider implementing:

- User authentication and authorization.
- HTTPS using SSL/TLS.
- File type and upload validation.
- Strong database credentials and secret management.
- Upload size restrictions and additional security checks.
- Proper access controls and monitoring.

## 🎯 Learning Outcomes

Through this project, I gained practical experience in:

- Deploying applications on AWS EC2.
- Building custom Docker images using Dockerfiles.
- Managing multi-container applications using Docker Compose.
- Configuring Nginx and PHP-FPM integration.
- Connecting PHP applications with MySQL.
- Implementing Docker bridge networking.
- Managing persistent storage with Docker volumes.
- Using Git and GitHub for version control.

## 👨‍💻 Author

**Animesh**

GitHub: [@Animeshp43](https://github.com/Animeshp43)

---

⭐ If you find this project useful, feel free to explore the repository and share your feedback.
