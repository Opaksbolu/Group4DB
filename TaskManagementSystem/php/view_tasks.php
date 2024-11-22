<?php
include 'db_connect.php';
include 'header.php';

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Tasks</title>
    <link rel="stylesheet" href="../CSS/style.css"> <!-- Assuming Tailwind CSS or custom CSS -->
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen flex flex-col items-center bg-gray-100">
        <header class="w-full bg-blue-600 py-4 text-white text-center">
            <h1 class="text-2xl font-bold">Your Tasks</h1>
        </header>
        <main class="w-full max-w-4xl mt-8 p-6 bg-white shadow-md rounded-lg">
            <div class="mb-4 flex justify-between items-center">
                <form class="w-full max-w-md flex space-x-2">
                    <input type="text" name="search" placeholder="Search tasks" class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Search</button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border-collapse border border-gray-200 rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border text-left">Title</th>
                            <th class="px-4 py-2 border text-left">Description</th>
                            <th class="px-4 py-2 border text-left">Due Date</th>
                            <th class="px-4 py-2 border text-left">Priority</th>
                            <th class="px-4 py-2 border text-left">Status</th>
                            <th class="px-4 py-2 border text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="border px-4 py-2"><?= htmlspecialchars($row['title']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($row['description']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($row['due_date']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($row['priority']) ?></td>
                                    <td class="border px-4 py-2"><?= htmlspecialchars($row['status']) ?></td>
                                    <td class="border px-4 py-2">
                                        <a href="../PHP/edit_task.php?task_id=<?= $row['task_id'] ?>" class="text-blue-500 hover:underline">Edit</a> |
                                        <a href="../PHP/delete_task.php?task_id=<?= $row['task_id'] ?>" class="text-red-500 hover:underline" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a> |
                                        <a href="../PHP/toggle_completion.php?task_id=<?= $row['task_id'] ?>" class="text-green-500 hover:underline"><?= $row['status'] === 'Pending' ? 'Mark as Completed' : 'Mark as Pending' ?></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">No tasks found. <a href="../HTML/create_task.html" class="text-blue-500 hover:underline">Create a new task</a>.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 flex justify-between">
                <?php if ($page > 1): ?>
                    <a href="../PHP/view_tasks.php?page=<?= $page - 1 ?>" class="text-blue-500 hover:underline">Previous</a>
                <?php endif; ?>
                <?php if ($result->num_rows == $items_per_page): ?>
                    <a href="../PHP/view_tasks.php?page=<?= $page + 1 ?>" class="text-blue-500 hover:underline">Next</a>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
