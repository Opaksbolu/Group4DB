<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if fields are empty
    if (empty($email) || empty($password)) {
        header("Location: ../HTML/login.html?error=empty_fields");
        exit();
    }

    // Get user details from the database
    $sql = "SELECT user_id, password FROM Users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Password matches, set session and redirect to the menu page
            $_SESSION["user_id"] = $user_id;
            header("Location: ../HTML/menu.html");
            exit();
        } else {
            // Incorrect password
            header("Location: ../HTML/login.html?error=invalid_password");
            exit();
        }
    } else {
        // User not found
        header("Location: ../HTML/login.html?error=user_not_found");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
