<?php
include 'header.php';

session_start();
session_unset();
session_destroy();

echo "<p>You have been logged out. Redirecting to the welcome page...</p>";
echo "<script>
        setTimeout(function() {
            window.location.href = '../HTML/welcome.html';
        }, 3000); // 3-second delay before redirection
      </script>";
?>
