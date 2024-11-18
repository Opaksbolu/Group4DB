<?php
include 'db_connect.php';
include 'header.php';

session_start();

if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $theme = $_POST["theme"];
    $language = $_POST["language"];

    $stmt = $conn->prepare("UPDATE Settings SET theme_preferences = ?, language = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $theme, $language, $user_id);

    if ($stmt->execute()) {
        echo "Settings updated successfully. Redirecting..";
        echo "<script>
            setTimeout(function() {
                window.location.href = '../HTML/settings.html';
            }, 2000);
            </script>";
    } else {
        echo "Error updating settings: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "You need to log in to update settings.";
}

$conn->close();
?>
