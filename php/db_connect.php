<?php
// Include Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';


// Load environment variables from the .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Ensure JSON response
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *"); // CORS: allow all origins (be careful in production)

// Error handling (turn off display_errors in production)
if (getenv('ENVIRONMENT') !== 'development') {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', '/path/to/log/file.log');
}

error_reporting(E_ALL); // Log all errors for debugging

// Retrieve database connection settings from environment variables
$servername = $_ENV['DB_SERVER'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

// Create a connection to the database using mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    // Log error and send JSON response
    error_log("Database connection failed: " . $conn->connect_error);
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}
?>
