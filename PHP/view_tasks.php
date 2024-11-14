<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../HTML/login.html");
    exit();
}

$user_id = $_SESSION["user_id"];

// Default sorting
$order_by = "priority ASC";

// Capture filters and sorting options
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $filter_priority = $_GET['filter_priority'] ?? null;
    $filter_status = $_GET['filter_status'] ?? null;
    $sort_by = $_GET['sort_by'] ?? "priority";

    // Build dynamic ORDER BY
    if (in_array($sort_by, ['priority', 'due_date', 'status', 'created_at'])) {
        if ($sort_by == 'priority') {
            $order_by = "$sort_by DESC";
        } else {
            $order_by = "$sort_by ASC";
        }
    }

    // Build WHERE clause
    $filters = [];
    if ($filter_priority) {
        $filters[] = "priority = '$filter_priority'";
    }
    if ($filter_status) {
        $filters[] = "status = '$filter_status'";
    }

    $where_clause = "";
    if (count($filters) > 0) {
        $where_clause = "AND " . implode(" AND ", $filters);
    }
}

// Prepare SQL query dynamically
$sql = "SELECT task_id, title, description, due_date, priority, status, created_at 
        FROM Tasks 
        WHERE user_id = ? $where_clause 
        ORDER BY $order_by";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Tasks</title>
</head>
<body>
    <!-- Navigation Menu -->
    <nav>
        <a href="../HTML/create_task.html">Create Task</a> |
        <a href="../PHP/view_tasks.php">View Tasks</a> |
        <a href="../HTML/settings.html">Settings</a> |
        <a href="../PHP/logout.php">Logout</a>
    </nav>

    <h2>Your Tasks</h2>

    <!-- Filter Form -->
    <form method="get" action="view_tasks.php">
        <label for="filter_priority">Filter by Priority:</label>
        <select name="filter_priority" id="filter_priority">
            <option value="">All</option>
            <option value="High" <?php if ($filter_priority === 'High') echo 'selected'; ?>>High</option>
            <option value="Medium" <?php if ($filter_priority === 'Medium') echo 'selected'; ?>>Medium</option>
            <option value="Low" <?php if ($filter_priority === 'Low') echo 'selected'; ?>>Low</option>
        </select>

        <label for="filter_status">Filter by Status:</label>
        <select name="filter_status" id="filter_status">
            <option value="">All</option>
            <option value="Pending" <?php if ($filter_status === 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="Complete" <?php if ($filter_status === 'Complete') echo 'selected'; ?>>Complete</option>
        </select>

        <label for="sort_by">Sort by:</label>
        <select name="sort_by" id="sort_by">
            <option value="priority" <?php if ($sort_by === 'priority') echo 'selected'; ?>>Priority</option>
            <option value="due_date" <?php if ($sort_by === 'due_date') echo 'selected'; ?>>Due Date</option>
            <option value="status" <?php if ($sort_by === 'status') echo 'selected'; ?>>Status</option>
            <option value="created_at" <?php if ($sort_by === 'created_at') echo 'selected'; ?>>Created At</option>
        </select>

        <button type="submit">Apply Filters</button>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["title"]); ?></td>
                    <td><?php echo htmlspecialchars($row["description"]); ?></td>
                    <td><?php echo htmlspecialchars($row["due_date"]); ?></td>
                    <td><?php echo htmlspecialchars($row["priority"]); ?></td>
                    <td><?php echo htmlspecialchars($row["status"]); ?></td>
                    <td><?php echo htmlspecialchars($row["created_at"]); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No tasks found. <a href="../HTML/create_task.html">Create a new task</a>.</p>
    <?php endif; ?>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>
