<?php
session_start();
include("../assets/config.php");
include("../assets/monolog_config.php");
$response = array();

if (isset($_SESSION['uid']) && $_SERVER["REQUEST_METHOD"] == "POST") {

    $s_no = (int) $_POST['s_no'];

    $delBusQuery = "DELETE FROM `bus_root` WHERE `s_no` = ?";
    $stmt1 = mysqli_prepare($conn, $delBusQuery);
    mysqli_stmt_bind_param($stmt1, "i", $s_no);

    if (mysqli_stmt_execute($stmt1)
    ) { 
        $response['status'] = "success";
        $response['message'] = 'Bus stop removed successfully!';
        $log->info('Bus stop removed', ['s_no' => $s_no]);
    }else{
        $response['status'] = "ERROR";
        $response['message'] = 'Something went wrong while deleting bus stop!';
        $log->error('Something went wrong while deleting bus stop', ['s_no' => $s_no]);
    }
} else {
    $response['status'] = 'ERROR';
    $response['message'] = 'Invalid Request!';
}
echo json_encode($response);
