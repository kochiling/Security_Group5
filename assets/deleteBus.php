<?php
session_start();
include("../assets/config.php");
include("../assets/monolog_config.php");
$response = array();

if (isset($_SESSION['uid']) && $_SERVER["REQUEST_METHOD"] == "POST") {

    $busId = $_POST['busId'];

    $delBusQuery = "DELETE FROM `buses` WHERE `bus_id` = ?";
    $stmt1 = mysqli_prepare($conn, $delBusQuery);
    mysqli_stmt_bind_param($stmt1, "s", $busId);
    
    $delStaffQuery = "DELETE FROM `bus_staff` WHERE `bus_id` = ?";
    $stmt2 = mysqli_prepare($conn, $delStaffQuery);
    mysqli_stmt_bind_param($stmt2, "s", $busId);

    $delRootQuery = "DELETE FROM `bus_root` WHERE `bus_id` = ?";
    $stmt3 = mysqli_prepare($conn, $delRootQuery);
    mysqli_stmt_bind_param($stmt3, "s", $busId);


    if (mysqli_stmt_execute($stmt1)
        && mysqli_stmt_execute($stmt2)
        && mysqli_stmt_execute($stmt3)
    ) { 
        $response['status'] = "success";
        $response['message'] = 'Bus removed successfully!';
        $log->info('Bus removed', ['bus_id' => $busId]);
    }else{
        $response['status'] = "ERROR";
        $response['message'] = 'Something went wrong while deleting bus!';
        $log->error('Something went wrong while deleting bus', ['bus_id' => $busId]);
    }
} else {
    $response['status'] = 'ERROR';
    $response['message'] = 'Invalid Request!';
}
echo json_encode($response);
