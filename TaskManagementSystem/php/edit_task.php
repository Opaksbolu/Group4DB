<?php
include 'db_connect.php';
include 'header.php';
 
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../HTML/login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['task_id'])) {
    $task_id = (int)$_GET['task_id'];
    $stmt = $conn->prepare("SELECT title, description, due_date, priority, status FROM Tasks WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
    } else {
        echo "Task not found.";
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = (int)$_POST['task_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE Tasks SET title = ?, description = ?, due_date = ?, priority = ?, status = ? WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param("ssssiii", $title, $description, $due_date, $priority, $status, $task_id, $_SESSION["user_id"]);

    if ($stmt->execute()) {
        header("Location: ../HTML/view_tasks.html");
        exit();
    } else {
        echo "Error updating task: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>
</head>
<body>
    <form method="POST" action="../PHP/edit_task.php">
        <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task_id); ?>">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required><br>
        <label>Description:</label>
        <textarea name="description" required><?php echo htmlspecialchars($task['description']); ?></textarea><br>
        <label>Due Date:</label>
        <input type="date" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>" required><br>
        <label>Priority:</label>
        <select name="priority">
            <option value="Low" <?php if ($task['priority'] == 'Low') echo 'selected'; ?>>Low</option>
            <option value="Medium" <?php if ($task['priority'] == 'Medium') echo 'selected'; ?>>Medium</option>
            <option value="High" <?php if ($task['priority'] == 'High') echo 'selected'; ?>>High</option>
        </select><br>
        <label>Status:</label>
        <select name="status">
            <option value="Pending" <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
        </select><br>
        <button type="submit">Update Task</button>
    </form>
</body>
</html>
