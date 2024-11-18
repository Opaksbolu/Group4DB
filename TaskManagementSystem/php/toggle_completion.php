<?php
include 'db_connect.php';
include 'header.php';

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../HTML/login.html");
    exit();
}

if (isset($_GET['task_id'])) {
    $task_id = (int)$_GET['task_id'];

    // Get current status
    $stmt = $conn->prepare("SELECT status FROM Tasks WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $_SESSION["user_id"]);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    $stmt->close();

    // Toggle status
    $new_status = ($status === 'Pending') ? 'Completed' : 'Pending';

    $stmt = $conn->prepare("UPDATE Tasks SET status = ? WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param("sii", $new_status, $task_id, $_SESSION["user_id"]);

    if ($stmt->execute()) {
        header("Location: ../HTML/view_tasks.html");
        exit();
    } else {
        echo "Error updating task status: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
