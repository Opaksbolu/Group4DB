<?php
$servername = "localhost"; // Typically 'localhost' for XAMPP
$username = "root"; // Default XAMPP MySQL username
$password = ""; // Default password is empty for XAMPP
$dbname = "task_management"; // Replace with your actual database name

// Create a secure connection with error handling
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection and display a user-friendly message
if ($conn->connect_error) {
    die("Connection to the database failed. Please try again later.");
}

// Set the charset to ensure proper handling of special characters
if (!$conn->set_charset("utf8mb4")) {
    die("Error loading character set utf8mb4: " . $conn->error);
}
?>
