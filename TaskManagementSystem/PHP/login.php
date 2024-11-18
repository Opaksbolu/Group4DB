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

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT user_id, password FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if any row was returned
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $user_id;
            echo "<p>Login successful, redirecting...</p>";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = '../HTML/menu.html';
                    }, 2000);
                  </script>";
            exit();
        } else {
            echo "<p>Invalid password. Please try again.</p>";
        }
    } else {
        echo "<p>No user found with that email. Please register or check your input.</p>";
    }

    $stmt->close();
}

$conn->close();
?>
