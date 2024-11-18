<?php
include 'db_connect.php';
include 'header.php';

session_start();

// Secure SELECT form and execution
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['select_action'])) {
    $search_input = trim($_POST['search_input']);
    $stmt = $conn->prepare("SELECT id, email FROM Users WHERE email LIKE ?");
    $like_input = "%" . $search_input . "%";
    $stmt->bind_param("s", $like_input);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h3>Results:</h3>";
    while ($row = $result->fetch_assoc()) {
        echo "User ID: " . htmlspecialchars($row['id']) . " - Email: " . htmlspecialchars($row['email']) . "<br>";
    }
    $stmt->close();
}

// Secure UPDATE form and execution
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_action'])) {
    $user_id = intval($_POST['user_id']);
    $new_email = trim($_POST['new_email']);

    $stmt = $conn->prepare("UPDATE Users SET email = ? WHERE id = ?");
    $stmt->bind_param("si", $new_email, $user_id);

    if ($stmt->execute()) {
        echo "<p>Update successful for user ID: " . htmlspecialchars($user_id) . "</p>";
    } else {
        echo "<p>Update failed: " . htmlspecialchars($stmt->error) . "</p>";
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Injection Experiment</title>
</head>
<body>
    <h2>SQL Injection Experiment</h2>

    <!-- SELECT form -->
    <form method="POST" action="">
        <h3>Search for Users</h3>
        <input type="text" name="search_input" placeholder="Enter email to search" required>
        <button type="submit" name="select_action">Search</button>
    </form>

    <!-- UPDATE form -->
    <form method="POST" action="">
        <h3>Update User Email</h3>
        <input type="number" name="user_id" placeholder="Enter user ID" required>
        <input type="email" name="new_email" placeholder="Enter new email" required>
        <button type="submit" name="update_action">Update Email</button>
    </form>
</body>
</html>
