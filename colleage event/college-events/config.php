<?php
// ===================================================
// Database connection settings
// Change these 4 values to match your own MySQL setup
// ===================================================
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "college_events";

// Create connection using MySQLi
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Stop the script if connection fails
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start a session on every page (needed for admin login)
session_start();
?>
