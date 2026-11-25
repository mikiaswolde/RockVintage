<?php
$servername = getenv('DB_HOST') ?: 'localhost';
$username   = getenv('DB_USER') ?: 'root';
$password   = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$dbname     = getenv('DB_NAME') ?: 'rockvintage_db';
$port       = (int)(getenv('DB_PORT') ?: 3306);
$useSsl     = in_array(strtolower((string)getenv('DB_SSL')), ['1', 'true', 'yes'], true);

$conn = mysqli_init();
if (!$conn) {
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Database initialization failed.']));
}

$flags = 0;
if ($useSsl) {
    $caPath = getenv('DB_SSL_CA') ?: '/etc/ssl/certs/ca-certificates.crt';
    if (!file_exists($caPath)) {
        $caPath = null;
    }
    $conn->ssl_set(null, null, $caPath, null, null);
    $flags = MYSQLI_CLIENT_SSL;
}

if (!$conn->real_connect($servername, $username, $password, $dbname, $port, null, $flags)) {
    error_log('Database connection failed: ' . $conn->connect_error);
    http_response_code(500);
    die(json_encode(['success' => false, 'message' => 'Database connection failed.']));
}

$conn->set_charset('utf8mb4');
?>