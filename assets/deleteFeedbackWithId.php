<?php
session_start();
include("../assets/config.php");
include("../assets/monolog_config.php");
$response = array();

if (isset($_POST["feedbackid"])) {
    $feedbackid = (int) $_POST["feedbackid"];

    $sql = "DELETE FROM `feedback` WHERE `feedback`.`s_no` = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $feedbackid);
    if(mysqli_stmt_execute($stmt)){
        $response['status'] = "success";
        $response['message'] = "Feedback deleted successfully!";
        $log->info('Feedback deleted', ['s_no' => $feedbackid]);
    }else{
        $response['status'] = "error";
        $response['message'] = "Unable to delete feedback!";
        $log->error('Unable to delete feedback', ['s_no' => $feedbackid]);
    }
} else {
    $response['status'] = "success";
    $response['message'] = "Invalid request!";
}

echo json_encode($response);

