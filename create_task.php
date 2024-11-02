<?php
$conn = new mysqli("localhost", "root", "", "task_management");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_POST['user_id'];
$title = $_POST['title'];
$description = $_POST['description'];
$due_date = $_POST['due_date'];
$priority = $_POST['priority'];
$status = $_POST['status'];

$sql = "INSERT INTO Tasks (user_id, title, description, due_date, priority, status) 
        VALUES ('$user_id', '$title', '$description', '$due_date', '$priority', '$status')";

if ($conn->query($sql) === TRUE) {
    echo "Task created successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
