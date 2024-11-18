<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Injection Experiment - Update</title>
</head>
<body>
    <h2>SQL Injection Experiment - Update Query</h2>
    <form action="update_query.php" method="post">
        <label for="idField">Enter User ID:</label>
        <input type="text" name="idField" id="idField" required>
        <label for="updateField">Enter New Username:</label>
        <input type="text" name="updateField" id="updateField" required>
        <input type="submit" value="Update">
    </form>
</body>
</html>
<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['idField'];
    $newUsername = $_POST['updateField'];

    $sql = "UPDATE Users SET username = '$newUsername' WHERE id = '$id'";

    echo "<p>Generated Query: $sql</p>";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
