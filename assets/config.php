<?php
$server = "localhost";

$user = "root";
$password = "";
$db = "_sms";

$conn = mysqli_connect($server, $user, $password, $db);

if (!$conn) {
    header('Location: ../errors/error.html');
    exit();
}

// Check if connection is successful, then set a cookie
if ($conn) {
    // Set a cookie to track session status or user authentication
    setcookie(
        "session_id",           // Cookie name
        session_id(),           // Cookie value (session ID)
        time() + 3600,          // Expiration time (1 hour from now)
        "/school-management-system", // Path (app directory)
        "localhost",            // Domain (localhost)
        false,                  // Secure flag (true for HTTPS, false for HTTP)
        true                    // HttpOnly flag to prevent JavaScript access
    );
}
?>
