<?php
include('config.php');

// Check if the ID is set in the POST request
if (isset($_POST['id'])) {
    $teacherId = $_POST['id'];  // Get teacher ID from AJAX request

    // Debugging statement to confirm the received ID
    echo "Received teacher ID: " . $teacherId . "<br>";

    // Begin transaction to ensure both actions succeed or fail together
    mysqli_begin_transaction($conn);

    // First query: Update the user's role in the users table
    $query = "UPDATE users SET role = 'admin' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $teacherId);
        
        if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) === 1) {
            echo "Role updated to admin successfully for teacher ID: " . $teacherId . "<br>";
            mysqli_stmt_close($stmt);

            // Second query: Delete the user from the teachers table
            $deleteQuery = "DELETE FROM teachers WHERE id = ?";
            $deleteStmt = mysqli_prepare($conn, $deleteQuery);

            if ($deleteStmt) {
                mysqli_stmt_bind_param($deleteStmt, "s", $teacherId);

                if (mysqli_stmt_execute($deleteStmt) && mysqli_stmt_affected_rows($deleteStmt) === 1) {
                    echo "User deleted from teachers table successfully for teacher ID: " . $teacherId;
                    mysqli_commit($conn);  // Commit transaction if both actions are successful
                } else {
                    echo "Error deleting user from teachers table or user not found.";
                    mysqli_rollback($conn);  // Rollback if delete failed
                }
                
                mysqli_stmt_close($deleteStmt);
            } else {
                echo "Error preparing delete statement.";
                mysqli_rollback($conn);  // Rollback if delete statement preparation failed
            }
        } else {
            echo "Error updating role or no user was updated. Check if the ID exists in the users table.";
            mysqli_stmt_close($stmt);
            mysqli_rollback($conn);  // Rollback if role update failed
        }
    } else {
        echo "Error preparing update statement.";
    }
} else {
    echo "Invalid request: No teacher ID provided.";
}

// Close the database connection
mysqli_close($conn);
?>
