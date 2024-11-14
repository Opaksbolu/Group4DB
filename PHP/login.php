<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Additional server-side email format validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Please enter a valid email address.";
        exit();
    }

    // Prepare and execute the query to check for email
    $stmt = $conn->prepare("SELECT user_id, password FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();  // Store result to check if email exists

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // Verify password and set session
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            header("Location: ../PHP/view_tasks.php"); // Redirect to view tasks page
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "The email address is not registered.";
    }

    $stmt->close();
}
$conn->close();
?>
