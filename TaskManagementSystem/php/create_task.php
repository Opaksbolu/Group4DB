<?php error_reporting(0); ?>
<?php
include 'db_connect.php';
include 'header.php';
if (session_status() == PHP_SESSION_NONE) { session_start(); }

// Start output buffering to prevent accidental output before headers
ob_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../html/login.html");
    exit();
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION["user_id"];
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $due_date = $_POST['due_date'];
        $priority = $_POST['priority'];
        $status = "Pending";

        // Validate required fields
        if (empty($title) || empty($description) || empty($due_date) || empty($priority)) {
            throw new Exception("All fields are required.");
        }

        // Prepare and execute the SQL statement
        $stmt = $conn->prepare("INSERT INTO Tasks (user_id, title, description, due_date, priority, status) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("Statement preparation failed: " . $conn->error);
        }

        $stmt->bind_param("isssss", $user_id, $title, $description, $due_date, $priority, $status);

        if ($stmt->execute()) {
            // Redirect to view_tasks.html after successful task creation
            header("Location: ../html/view_tasks.html");
            exit();
        } else {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        $stmt->close();
    }
} catch (Exception $e) {
    // Display a friendly error message and redirect back to the create task page
    echo "<script>alert('Error: " . htmlspecialchars($e->getMessage()) . "'); window.location.href = '../html/create_task.html';</script>";
}

$conn->close();
ob_end_flush();
?>
