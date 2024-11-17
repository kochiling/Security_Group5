<?php
session_start();
include('../assets/config.php');
include('../assets/monolog_config.php');
$response = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $jsonData = file_get_contents('php://input');
    $decodedData = json_decode($jsonData, true);


    foreach ($decodedData as $studentId => $value) {

        $examId = mysqli_real_escape_string($conn, $value['examId']);
        if (isset($value['marks']) && is_array($value['marks'])) {
            $marks = filter_var_array($value['marks'], FILTER_SANITIZE_NUMBER_INT);
        } else {
            $response["status"] = "error";
            $response["message"] = "Invalid marks data!";
            break;
        }

        // I WANT TO DO MY LOGIC HERE

        foreach ($marks as $subject => $mark) {

            $subject = mysqli_real_escape_string($conn, $subject);
            $mark = mysqli_real_escape_string($conn, $mark);
            $studentId = mysqli_real_escape_string($conn, $studentId);

            $query = "INSERT INTO `marks` (`s_no`, `exam_id`, `subject`,  `student_id`, `marks`) VALUES (NULL, ?, ?, ?, ?);";

            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssss", $examId, $subject, $studentId, $mark);

            if (mysqli_stmt_execute($stmt)) {
                $response = "success";
                $log->info('Marks uploaded for student ID', ['examId' => $examId, 'subject' => $subject, 'studentId' => $studentId]);
            } else {
                $response = "Something went wrong while saving!!";
                break;
            }

              mysqli_stmt_close($stmt);
        }
    }
} else {
    $response = "Something went wrong!";
}
mysqli_close($conn);
echo json_encode($response);
