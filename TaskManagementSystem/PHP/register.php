<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "<p>Email and password are required.</p>";
        exit();
    }

    // Check if the email already exists
    $checkStmt = $conn->prepare("SELECT id FROM Users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "<p>This email is already registered. Please use another or log in.</p>";
        $checkStmt->close();
        exit();
    }

    $checkStmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO Users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<p>Registration successful, redirecting...</p>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = '../HTML/menu.html';
                }, 2000);
              </script>";
        exit();
    } else {
        echo "<p>Error: " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>
