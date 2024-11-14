<?php 

session_start();
include("../assets/config.php");
include("../assets/monolog_config.php");
$response = ""; 

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $subject = $_POST['subject'];
    $subjectId = $_POST['subject_id'];

    $query = "UPDATE subjects SET subject_name = ? WHERE subject_id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $subject, $subjectId);
    mysqli_stmt_execute($stmt);

    if (mysqli_affected_rows($conn) > 0) {
        $response = "success";
        $log->info('Subject edited', ['subject_id' => $subjectId]);
    } else {
        $response = "Unable to edit subject!";
        $log->error('Unable to edit subject', ['subject_id' => $subjectId]);
    }

    mysqli_stmt_close($stmt);
} else {
    $response = "Invalid request!";
}

echo $response;

?>
