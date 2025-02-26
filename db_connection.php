<?php
$host = "localhost"; 
$username = "root";
$password = "";
$database = "db_vitalink";

try {
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    error_log($e->getMessage()); // Log error instead of showing it
    die("Something went wrong. Please try again later."); // Generic error message
}
?>
