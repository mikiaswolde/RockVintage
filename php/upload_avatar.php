<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized. Please log in.']);
    exit();
}

$targetDir = '../assets/';
if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'No valid file uploaded.']);
    exit();
}

$file = $_FILES['avatar'];
$maxSize = 2 * 1024 * 1024; // 2MB
if ($file['size'] > $maxSize) {
    echo json_encode(['success' => false, 'message' => 'File exceeds 2MB size limit.']);
    exit();
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($file['tmp_name']);
$allowedMimeMap = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/gif'  => 'gif',
    'image/webp' => 'webp',
];

if (!isset($allowedMimeMap[$mimeType])) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type.']);
    exit();
}

$ext = $allowedMimeMap[$mimeType];
$filename = uniqid('avatar_', true) . '.' . $ext;
$targetFile = $targetDir . $filename;

if (move_uploaded_file($file['tmp_name'], $targetFile)) {
    echo json_encode(['success' => true, 'path' => 'assets/' . $filename]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to upload avatar.']);
}
?>
