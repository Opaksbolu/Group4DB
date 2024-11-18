<?php
include 'db_connect.php';
session_start();

if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $theme = $_POST["theme"];
    $language = $_POST["language"];
    $timezone = $_POST["timezone"];

    $stmt = $conn->prepare("UPDATE Settings SET theme_preferences = ?, language = ?, timezone = ? WHERE user_id = ?");
    $stmt->bind_param("sssi", $theme, $language, $timezone, $user_id);

    if ($stmt->execute()) {
        echo "Settings updated successfully.";
    } else {
        echo "Error updating settings: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "You need to log in to update settings.";
}

$conn->close();
?>
