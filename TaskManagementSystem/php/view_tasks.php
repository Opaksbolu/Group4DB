<?php
include 'db_connect.php';
include 'header.php';

// Start session safely
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user_id"])) {
    header("Location: ../HTML/login.html");
    exit();
}

// Styled navigation bar
echo '<nav class="bg-blue-600 p-4 mb-6 text-white flex justify-center space-x-4 rounded-t-lg shadow-lg">
        <a href="../HTML/create_task.html" class="hover:underline text-lg">Create Task</a>
        <a href="view_tasks.php" class="hover:underline text-lg">View Tasks</a>
        <a href="../HTML/settings.html" class="hover:underline text-lg">Settings</a>
        <a href="../HTML/logout.html" class="hover:underline text-lg">Logout</a>
      </nav>';

// Container for the main content
echo '<div class="container mx-auto p-6 bg-white shadow-md rounded-lg">';

// Page header
echo '<h2 class="text-2xl font-bold text-blue-700 mb-4">Your Tasks</h2>';

$user_id = $_SESSION["user_id"];
$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$filter_priority = isset($_GET['filter_priority']) ? $_GET['filter_priority'] : '';
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'due_date';

$order_clause = $sort_by === 'priority' ? 'priority DESC' : $sort_by . ' ASC';

// Pagination setup
$items_per_page = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// Base SQL query
$sql = "SELECT task_id, title, description, due_date, priority, status FROM Tasks WHERE user_id = ? AND title LIKE ?";
$params = [$user_id, $search];
$param_types = "is";

// Add priority and status filters if selected
if (!empty($filter_priority)) {
    $sql .= " AND priority = ?";
    $params[] = $filter_priority;
    $param_types .= "s";
}
if (!empty($filter_status)) {
    $sql .= " AND status = ?";
    $params[] = $filter_status;
    $param_types .= "s";
}

// Append sorting and pagination
$sql .= " ORDER BY " . $order_clause . " LIMIT ?, ?";
$params[] = $offset;
$params[] = $items_per_page;
$param_types .= "ii";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $conn->error);
}

$stmt->bind_param($param_types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Enhanced table with consistent styling
if ($result->num_rows > 0) {
    echo "<div class='overflow-x-auto'>
            <table class='min-w-full bg-white shadow-lg rounded-lg border-collapse'>
                <thead class='bg-blue-500 text-white'>
                    <tr>
                        <th class='px-6 py-3 text-left text-sm font-semibold'>Title</th>
                        <th class='px-6 py-3 text-left text-sm font-semibold'>Description</th>
                        <th class='px-6 py-3 text-left text-sm font-semibold'>Due Date</th>
                        <th class='px-6 py-3 text-left text-sm font-semibold'>Priority</th>
                        <th class='px-6 py-3 text-left text-sm font-semibold'>Status</th>
                        <th class='px-6 py-3 text-left text-sm font-semibold'>Actions</th>
                    </tr>
                </thead>
                <tbody class='bg-gray-50 divide-y divide-gray-200'>";
    while ($row = $result->fetch_assoc()) {
        $task_id = $row['task_id'];
        echo "<tr class='hover:bg-blue-100'>
                <td class='px-6 py-4'>" . htmlspecialchars($row['title']) . "</td>
                <td class='px-6 py-4'>" . htmlspecialchars($row['description']) . "</td>
                <td class='px-6 py-4'>" . htmlspecialchars($row['due_date']) . "</td>
                <td class='px-6 py-4'>" . htmlspecialchars($row['priority']) . "</td>
                <td class='px-6 py-4'>" . htmlspecialchars($row['status']) . "</td>
                <td class='px-6 py-4 space-x-2'>
                    <a href='../PHP/edit_task.php?task_id=$task_id' class='text-blue-500 hover:text-blue-700'>Edit</a>
                    <a href='../PHP/delete_task.php?task_id=$task_id' class='text-red-500 hover:text-red-700' onclick='return confirm(\"Are you sure you want to delete this task?\");'>Delete</a>
                    <a href='../PHP/toggle_completion.php?task_id=$task_id' class='text-green-500 hover:text-green-700'>" . ($row['status'] === 'Pending' ? 'Mark as Completed' : 'Mark as Pending') . "</a>
                </td>
              </tr>";
    }
    echo "</tbody>
          </table>
          </div>";

    // Pagination links
    echo "<div class='pagination flex justify-between mt-6'>";
    if ($page > 1) {
        echo "<a href='../PHP/view_tasks.php?page=" . ($page - 1) . "' class='text-blue-600 hover:text-blue-800'>Previous</a>";
    }
    if ($result->num_rows == $items_per_page) {
        echo "<a href='../PHP/view_tasks.php?page=" . ($page + 1) . "' class='text-blue-600 hover:text-blue-800'>Next</a>";
    }
    echo "</div>";
} else {
    echo "<p class='mt-6 text-gray-700'>No tasks found. <a href='../HTML/create_task.html' class='text-blue-600 hover:text-blue-800'>Create a new task</a>.</p>";
}
echo '</div>';

$stmt->close();
$conn->close();
?>
