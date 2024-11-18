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

    // Encryption key definition (FIXED)
    define('ENCRYPTION_KEY', '4d521ae25b55e70aad440f293a900d851bb08dcd84a9bc9f75b21d6ca1930c07')

?>