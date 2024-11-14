<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../HTML/login.html");
    exit();
}

$user_id = $_SESSION["user_id"];
$title = $_POST["title"];
$description = $_POST["description"];
$due_date = $_POST["due_date"];
$priority = $_POST["priority"];
$status = "Pending";  // Default status for new tasks

$stmt = $conn->prepare("INSERT INTO Tasks (user_id, title, description, due_date, priority, status) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isssss", $user_id, $title, $description, $due_date, $priority, $status);

if ($stmt->execute()) {
    echo "Task created successfully. <a href='../PHP/view_tasks.php'>View Tasks</a>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
