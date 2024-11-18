<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: ../HTML/login.html");
    exit();
}

$user_id = $_SESSION["user_id"];
$theme = $_POST['theme'];
$language = $_POST['language'];

// Save the preferences in the database
$stmt = $conn->prepare("UPDATE Users SET theme_preference = ?, language_preference = ? WHERE id = ?");
$stmt->bind_param("ssi", $theme, $language, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Preferences saved successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to save preferences."]);
}

$stmt->close();
$conn->close();
?>
