<?php
include("config.php");

if (isset($_POST['adminId'])) {
    $adminId = $_POST['adminId']; // Do not cast as integer

    // Use a prepared statement to update the user's role
    $query = "UPDATE users SET role = 'admin_block' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        // Bind $adminId as a string
        mysqli_stmt_bind_param($stmt, "s", $adminId);
        if (mysqli_stmt_execute($stmt)) {
            echo "success";
        } else {
            echo "error: " . mysqli_error($conn); // Add error details for debugging
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "error: " . mysqli_error($conn); // Error preparing statement
    }
} else {
    echo "Invalid request";
}
?>
