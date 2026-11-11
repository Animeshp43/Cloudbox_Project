<?php

// Read database configuration from environment variables
$host = getenv('DB_HOST') ?: 'mysql';
$dbname = getenv('DB_NAME') ?: 'cloudbox';
$username = getenv('DB_USER') ?: 'cloudbox_user';
$password = getenv('DB_PASSWORD') ?: 'CloudBoxUser@2026';

// Create MySQL connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]));
}

// Set character encoding
$conn->set_charset("utf8mb4");

?>
