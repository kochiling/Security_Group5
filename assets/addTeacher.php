<?php
session_start();
include("../assets/config.php");
include("../assets/monolog_config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $json_data = file_get_contents("php://input");
    $dataObject = json_decode($json_data, true);

    $uniqueId = "T" . time(); 

    // Sanitize and validate input data
    $fname = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["fname"])); 
    $lname = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["lname"])); 
    $_class = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["class"])); 
    $_section = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["section"])); 
    $subject = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["subject"])); 
    $gender = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["gender"])); 

    $dobString = htmlspecialchars($dataObject["dob"]); 
    $timestamp = strtotime($dobString);
    $dob = date('d-m-Y', $timestamp);

    $phone = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["phone"]), FILTER_SANITIZE_NUMBER_INT); 
    $email = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["email"]), FILTER_SANITIZE_EMAIL); 
    $address = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["address"])); 
    $city = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["city"])); 
    $zip = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["zip"])); 
    $state = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["state"])); 
    $guardian = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["guardian"])); 
    $gphone = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["gphone"])); 
    $gaddress = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["gaddress"])); 
    $gcity = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["gcity"])); 
    $gzip = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["gzip"])); 
    $relation = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["relation"])); 


    // Use prepared statement to check if the email already exists
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo 'Email already exists!';
    } else {

        // Use prepared statements for inserting teacher details
        $addTeacherDetailQuery = "INSERT INTO `teachers` (`s_no`, `id`, `fname`, `lname`, `class`,`section`, `subject`, `gender`, `dob`, `phone`, `email`, `address`, `city`, `zip`, `state`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $addTeacherDetailQuery);
        mysqli_stmt_bind_param($stmt, "ssssssssssssss", $uniqueId, $fname, $lname, $_class , $_section, $subject, $gender, $dob, $phone, $email, $address, $city, $zip, $state);
        mysqli_stmt_execute($stmt);

        // Use prepared statements for inserting guardian details
        $addGuardianDetailQuery = "INSERT INTO `teacher_guardian` (`s_no`, `id`, `gname`, `gphone`, `gaddress`, `gcity`, `gzip`, `relation`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $addGuardianDetailQuery);
        mysqli_stmt_bind_param($stmt, "sssssss", $uniqueId, $guardian, $gphone, $gaddress, $gcity, $gzip, $relation);
        mysqli_stmt_execute($stmt);

        // Use prepared statements for inserting user details
        $password = str_replace("-", "", $dob); 
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $addUserDetailQuery = "INSERT INTO `users` (`s_no`, `id`, `email`, `password_hash`, `role`, `theme`,`data_policy`) VALUES (NULL, ?, ?, ?, 'teacher', 'light','0')";
        $stmt = mysqli_prepare($conn, $addUserDetailQuery);
        mysqli_stmt_bind_param($stmt, "sss", $uniqueId, $email, $passwordHash);
        mysqli_stmt_execute($stmt);

        echo 'success';
        $log->info('New teacher added', ['email' => $email]);
    }

} else {
    echo "Invalid request!";
}
?>
