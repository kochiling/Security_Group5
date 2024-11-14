<?php
session_start();
include("../assets/config.php");
include("../assets/monolog_config.php");
$response = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and escape user input
    $uniqueId = "S" . time();

    // Sanitize each POST field
    $fname = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["fname"]))));
    $lname = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["lname"]))));
    $father = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["father"]))));

    // Sanitize and validate the date of birth
    $dobString = $_POST["dob"];
    $timestamp = strtotime($dobString);
    $dob = date('d-m-Y', $timestamp);

    // Sanitize other fields
    $gender = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["gender"]))));
    $class = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["class"]))));
    $section = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["section"]))));
    $imageName = "1701517055user.png";
    $allowedExtensions = ['png', 'jpeg', 'jpg'];

    // Sanitize phone, email, address
    $phone = mysqli_real_escape_string($conn, filter_var(strip_tags(trim($_POST["phone"])), FILTER_SANITIZE_NUMBER_INT));
    $email = mysqli_real_escape_string($conn, filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL));
    $address = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["address"]))));
    $city = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["city"]))));
    $zip = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["zip"]))));
    $state = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["state"]))));
    $guardian = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["guardian"]))));
    $gphone = mysqli_real_escape_string($conn, filter_var(strip_tags(trim($_POST["gphone"])), FILTER_SANITIZE_NUMBER_INT));
    $gaddress = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["gaddress"]))));
    $gcity = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["gcity"]))));
    $gzip = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["gzip"]))));
    $relation = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($_POST["relation"]))));
    $uploadDone = true;
    $invalidFormat = false;

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo 'Email already exists!';
    } else {

        if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {

            $filename = $_FILES["image"]["name"];
            $tempname = $_FILES["image"]["tmp_name"];

            $fileInfo = pathinfo($filename);
            $fileExtension = strtolower($fileInfo['extension']);

            if (in_array($fileExtension, $allowedExtensions)) {
                $newName = $uniqueId . time() . "." . $fileExtension;

                $folder =  __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "studentUploads" . DIRECTORY_SEPARATOR .  $newName;

                if (move_uploaded_file($tempname, $folder)) {
                    $uploadDone = true;
                    $imageName = $newName;
                } else {
                    $uploadDone = false;
                }
                $invalidFormat = false;
            } else {
                $response =  "Invalid image format! (jpg, png, jpeg)";
                $invalidFormat = true;
            }
        }

        if (!$invalidFormat) {
            $addStudentDetailQuery = "INSERT INTO `students` (`s_no`, `id`, `fname`, `lname`, `father`, `gender`, `class`, `section`, `dob`, `image`, `phone`, `email`, `address`, `city`, `zip`, `state`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $addStudentDetailQuery);
            mysqli_stmt_bind_param($stmt, "sssssssssssssss", $uniqueId, $fname, $lname, $father, $gender, $class, $section, $dob, $imageName, $phone, $email, $address, $city, $zip, $state);
            mysqli_stmt_execute($stmt);

            $addGuardianDetailQuery = "INSERT INTO `student_guardian` (`s_no`, `id`, `gname`, `gphone`, `gaddress`, `gcity`, `gzip`, `relation`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $addGuardianDetailQuery);
            mysqli_stmt_bind_param($stmt, "sssssss", $uniqueId, $guardian, $gphone, $gaddress, $gcity, $gzip, $relation);
            mysqli_stmt_execute($stmt);

            $password = str_replace("-", "", $dob);
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $addUserDetailQuery = "INSERT INTO `users` (`s_no`, `id`, `email`, `password_hash`, `role`, `theme`) VALUES (NULL, ?, ?, ?, 'student', 'light')";

            $stmt = mysqli_prepare($conn, $addUserDetailQuery);
            mysqli_stmt_bind_param($stmt, "sss", $uniqueId, $email, $passwordHash);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $response = 'success';
                $log->info('Student added succesfully', ['fname' => $fname, 'lname' => $lname, 'email' => $email]);

                if (!$uploadDone) {
                    $response = "Image upload failed! (Student successfully added)";
                }
            } else {
                $response = 'Error - Unable to add student';
            }
        }
    }
} else {
    $response  = "Invalid request!";
}

echo $response;
?>
