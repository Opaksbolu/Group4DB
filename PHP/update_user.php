<?php
include 'db_connect.php';
session_start();

if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $new_username = $_POST["new_username"];
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];

    $stmt = $conn->prepare("SELECT password FROM Users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($current_password, $hashed_password)) {
        if (!empty($new_username)) {
            $stmt = $conn->prepare("UPDATE Users SET username = ? WHERE user_id = ?");
            $stmt->bind_param("si", $new_username, $user_id);
            $stmt->execute();
            $stmt->close();
            echo "Username updated successfully.<br>";
        }

        if (!empty($new_password)) {
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE Users SET password = ? WHERE user_id = ?");
            $stmt->bind_param("si", $hashed_new_password, $user_id);
            $stmt->execute();
            $stmt->close();
            echo "Password updated successfully.<br>";
        }
    } else {
        echo "Current password is incorrect.";
    }
} else {
    echo "You need to log in to update your account.";
}

$conn->close();
?>
