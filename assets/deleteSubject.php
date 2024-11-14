<?php
session_start();
include("../assets/config.php");
include("../assets/monolog_config.php");
$response = "";

if (isset($_POST['subjectId'])) {
    $subID = $_POST['subjectId'];

    // Use prepared statement to delete subject
    $sql = "DELETE FROM `subjects` WHERE `subject_id` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $subID);

    if (mysqli_stmt_execute($stmt)) {
        $response = "success";
        $log->info('Subject deleted', ['subject_id' => $subID]);
    } else {
        $response = "Unable to delete subject";
        $log->error('Unable to delete subject', ['subject_id' => $subID]);
    }

} else {
    $response = 'Something went wrong!';
}

echo $response;
?>
