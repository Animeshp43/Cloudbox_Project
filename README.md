
# CloudBox – Cloud File Storage System

CloudBox is a cloud-based file storage application deployed on AWS EC2 using Docker and Docker Compose.

## Project Overview

CloudBox allows users to upload, view, and delete files through a web-based dashboard. File metadata is stored in MySQL, while actual uploaded files are stored in persistent Docker volumes.

## Technologies Used

- AWS EC2
- Amazon Linux 2023
- Git and GitHub
- Docker
- Docker Compose
- Custom Docker Images
- Docker Bridge Network
- Nginx
- PHP 8.3 and PHP-FPM
- MySQL 8.4
- HTML, CSS and JavaScript

## Architecture

Browser → Nginx → PHP-FPM → MySQL

Uploaded files are stored in a shared Docker volume accessible by PHP and File Browser.

## Project Structure

```text
CloudBox/
├── api/
│   ├── Dockerfile
│   ├── db.php
│   ├── index.php
│   └── init.sql
├── filebrowser/
│   └── Dockerfile
├── nginx/
│   ├── Dockerfile
│   ├── nginx.conf
│   └── index.html
├── docker-compose.yml
└── .gitignore
```

## Features

- CloudBox web dashboard
- File upload
- Dynamic file listing
- File deletion
- MySQL metadata storage
- Persistent file storage
- Custom Docker images
- Docker Compose orchestration
- Custom Docker bridge network

## Deployment

1. Launch an AWS EC2 instance.
2. Install Docker and Docker Compose.
3. Clone this repository.
4. Create a `.env` file with the required MySQL credentials.
5. Build and start the containers:

```bash
docker compose up -d --build
```

6. Access the dashboard using your EC2 public IP.

## API Endpoints

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/api` | List files |
| POST | `/api` | Upload a file |
| DELETE | `/api?id=ID` | Delete a file |

## Security Note

This is a learning project and does not currently implement user authentication. Do not use it to store sensitive files or expose it as a production service.

## Author

Animesh

