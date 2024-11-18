<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../HTML/login.html");
    exit();
}

if (isset($_GET['task_id'])) {
    $task_id = (int)$_GET['task_id'];

    $stmt = $conn->prepare("DELETE FROM Tasks WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $_SESSION["user_id"]);

    if ($stmt->execute()) {
        header("Location: ../HTML/view_tasks.html");
        exit();
    } else {
        echo "Error deleting task: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
