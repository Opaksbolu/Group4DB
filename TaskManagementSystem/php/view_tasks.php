<?php
include 'db_connect.php';
include 'header.php';

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: ../HTML/login.html");
    exit();
}

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

if ($result->num_rows > 0) {
    echo "<div class='overflow-x-auto'>
            <table class='min-w-full bg-white border-collapse border border-gray-200 rounded-lg'>
                <thead class='bg-gray-100'>
                    <tr>
                        <th class='px-4 py-2 border text-left'>Title</th>
                        <th class='px-4 py-2 border text-left'>Description</th>
                        <th class='px-4 py-2 border text-left'>Due Date</th>
                        <th class='px-4 py-2 border text-left'>Priority</th>
                        <th class='px-4 py-2 border text-left'>Status</th>
                        <th class='px-4 py-2 border text-left'>Actions</th>
                    </tr>
                </thead>
                <tbody>";
    while ($row = $result->fetch_assoc()) {
        $task_id = $row['task_id'];
        echo "<tr class='hover:bg-gray-50'>
                <td class='border px-4 py-2'>" . htmlspecialchars($row['title']) . "</td>
                <td class='border px-4 py-2'>" . htmlspecialchars($row['description']) . "</td>
                <td class='border px-4 py-2'>" . htmlspecialchars($row['due_date']) . "</td>
                <td class='border px-4 py-2'>" . htmlspecialchars($row['priority']) . "</td>
                <td class='border px-4 py-2'>" . htmlspecialchars($row['status']) . "</td>
                <td class='border px-4 py-2'>
                    <a href='../PHP/edit_task.php?task_id=$task_id' class='text-blue-500 hover:underline'>Edit</a> |
                    <a href='../PHP/delete_task.php?task_id=$task_id' class='text-red-500 hover:underline' onclick='return confirm(\"Are you sure you want to delete this task?\");'>Delete</a> |
                    <a href='../PHP/toggle_completion.php?task_id=$task_id' class='text-green-500 hover:underline'>" . ($row['status'] === 'Pending' ? 'Mark as Completed' : 'Mark as Pending') . "</a>
                </td>
              </tr>";
    }
    echo "</tbody>
          </table>
          </div>";

    // Pagination links
    echo "<div class='pagination flex justify-between mt-4'>";
    if ($page > 1) {
        echo "<a href='../PHP/view_tasks.php?page=" . ($page - 1) . "' class='text-blue-500 hover:underline'>Previous</a>";
    }
    if ($result->num_rows == $items_per_page) {
        echo "<a href='../PHP/view_tasks.php?page=" . ($page + 1) . "' class='text-blue-500 hover:underline'>Next</a>";
    }
    echo "</div>";
} else {
    echo "<p class='mt-4'>No tasks found. <a href='../HTML/create_task.html' class='text-blue-500 hover:underline'>Create a new task</a>.</p>";
}

$stmt->close();
$conn->close();
?>
