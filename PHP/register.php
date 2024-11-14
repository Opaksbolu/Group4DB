<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO Users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        $user_id = $conn->insert_id;

        $stmt_settings = $conn->prepare("INSERT INTO Settings (user_id) VALUES (?)");
        $stmt_settings->bind_param("i", $user_id);
        $stmt_settings->execute();
        $stmt_settings->close();

        echo "Registration successful. <a href='../HTML/login.html'>Login</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>
