<?php

header("Content-Type: application/json");

require_once __DIR__ . "/db.php";

// Shared upload directory
$uploadDir = "/srv/";

// GET: Fetch all uploaded files
if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $sql = "SELECT id, filename, file_size, file_type, uploaded_at
            FROM files
            ORDER BY uploaded_at DESC";

    $result = $conn->query($sql);

    if (!$result) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Unable to retrieve files"
        ]);
        exit;
    }

    $files = [];

    while ($row = $result->fetch_assoc()) {
        $files[] = $row;
    }

    echo json_encode([
        "success" => true,
        "files" => $files
    ]);

    exit;
}

// POST: Upload a file
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_FILES["file"])) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "No file uploaded"
        ]);
        exit;
    }

    $file = $_FILES["file"];

    if ($file["error"] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "File upload failed"
        ]);
        exit;
    }

    // Maximum application upload size: 20 MB
    if ($file["size"] > 20 * 1024 * 1024) {
        http_response_code(413);
        echo json_encode([
            "success" => false,
            "message" => "File exceeds 20 MB limit"
        ]);
        exit;
    }

    // Create a safe, unique stored filename
    $originalName = basename($file["name"]);
    $storedName = bin2hex(random_bytes(16)) . "_" . $originalName;
    $targetPath = $uploadDir . $storedName;

    if (!move_uploaded_file($file["tmp_name"], $targetPath)) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Unable to save file"
        ]);
        exit;
    }

    // Get file information
    $fileType = mime_content_type($targetPath)
        ?: "application/octet-stream";

    // Insert file metadata into MySQL
    $stmt = $conn->prepare(
        "INSERT INTO files (filename, file_size, file_type, storage_path)
         VALUES (?, ?, ?, ?)"
    );

    if (!$stmt) {
        unlink($targetPath);
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Unable to prepare database query"
        ]);
        exit;
    }

    $stmt->bind_param(
        "siss",
        $originalName,
        $file["size"],
        $fileType,
        $targetPath
    );

    if (!$stmt->execute()) {
        unlink($targetPath);
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Unable to save file information"
        ]);
        $stmt->close();
        exit;
    }

    $fileId = $stmt->insert_id;
    $stmt->close();

    echo json_encode([
        "success" => true,
        "message" => "File uploaded successfully",
        "file_id" => $fileId
    ]);

    exit;
}

// DELETE: Delete a file by its ID
if ($_SERVER["REQUEST_METHOD"] === "DELETE") {

    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

    if (!$id || $id < 1) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Valid file ID is required"
        ]);
        exit;
    }

    // Find the physical file path
    $stmt = $conn->prepare(
        "SELECT storage_path FROM files WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $file = $result->fetch_assoc();
    $stmt->close();

    if (!$file) {
        http_response_code(404);
        echo json_encode([
            "success" => false,
            "message" => "File not found"
        ]);
        exit;
    }

    // Validate that the file is inside /srv
    $realPath = realpath($file["storage_path"]);
    $realUploadDir = realpath($uploadDir);

    if (
        $realPath === false ||
        $realUploadDir === false ||
        dirname($realPath) !== $realUploadDir
    ) {
        http_response_code(400);
        echo json_encode([
            "success" => false,
            "message" => "Invalid storage path"
        ]);
        exit;
    }

    // Delete the physical file
    if (!unlink($realPath)) {
        http_response_code(500);
        echo json_encode([
            "success" => false,
            "message" => "Unable to delete physical file"
        ]);
        exit;
    }

    // Delete its metadata from MySQL
    $stmt = $conn->prepare(
        "DELETE FROM files WHERE id = ?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo json_encode([
        "success" => true,
        "message" => "File deleted successfully"
    ]);

    exit;
}

// Handle unsupported HTTP methods
http_response_code(405);

echo json_encode([
    "success" => false,
    "message" => "Method not allowed"
]);

