<?php
session_start();
include('config.php');

$data = array();
$data['status'] = "error"; // Default status

if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];

    $sql = "SELECT * FROM logs";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $data['status'] = "success";
        $data['logs'] = array(); // Prepare to hold log entries

        while ($row = mysqli_fetch_assoc($result)) {
            $data['logs'][] = array(
                'level' => htmlspecialchars($row['level']),
                'message' => htmlspecialchars($row['message']),
                'time' => htmlspecialchars($row['timestamp']),
                'context' => htmlspecialchars($row['context'])
            );
        }
    } else {
        $data['status'] = "No logs found";
    }

    mysqli_stmt_close($stmt);
} else {
    $data['status'] = "User session not set";
}

header('Content-Type: application/json');
echo json_encode($data);
