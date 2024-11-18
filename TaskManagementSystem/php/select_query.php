<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SQL Injection Experiment - Select</title>
</head>
<body>
    <h2>SQL Injection Experiment - Select Query</h2>
    <form action="select_query.php" method="post">
        <label for="inputField">Enter Username:</label>
        <input type="text" name="inputField" id="inputField" required>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
<?php
include 'db_connect.php';
include 'header.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = $_POST['inputField'];
    $sql = "SELECT * FROM Users WHERE username = '$input'";

    echo "<p>Generated Query: $sql</p>";

    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "ID: " . $row['id'] . " - Username: " . $row['username'] . "<br>";
        }
    } else {
        echo "No results found.";
    }
}

$conn->close();
?>
