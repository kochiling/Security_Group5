<?php
session_start();
include("config.php");
include("../assets/monolog_config.php");

$response = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Generate unique ID for the student
    $uniqueId = "S" . time();

    // Sanitize inputs
    function sanitize_input($conn, $input, $filter = FILTER_SANITIZE_STRING) {
        return mysqli_real_escape_string($conn, filter_var(trim($input), $filter));
    }

    $fname = sanitize_input($conn, $_POST["fname"]);
    $lname = sanitize_input($conn, $_POST["lname"]);
    $father = sanitize_input($conn, $_POST["father"]);
    $dobString = $_POST["dob"];
    $timestamp = strtotime($dobString);
    $dob = date('d-m-Y', $timestamp);

    $gender = sanitize_input($conn, $_POST["gender"]);
    $class = sanitize_input($conn, $_POST["class"]);
    $section = sanitize_input($conn, $_POST["section"]);
    $phone = sanitize_input($conn, $_POST["phone"], FILTER_SANITIZE_NUMBER_INT);
    $email = sanitize_input($conn, $_POST["email"], FILTER_SANITIZE_EMAIL);
    $address = sanitize_input($conn, $_POST["address"]);
    $city = sanitize_input($conn, $_POST["city"]);
    $zip = sanitize_input($conn, $_POST["zip"]);
    $state = sanitize_input($conn, $_POST["state"]);
    $guardian = sanitize_input($conn, $_POST["guardian"]);
    $gphone = sanitize_input($conn, $_POST["gphone"], FILTER_SANITIZE_NUMBER_INT);
    $gaddress = sanitize_input($conn, $_POST["gaddress"]);
    $gcity = sanitize_input($conn, $_POST["gcity"]);
    $gzip = sanitize_input($conn, $_POST["gzip"]);
    $relation = sanitize_input($conn, $_POST["relation"]);

    // Encryption setup
    $key = ENCRYPTION_KEY;
    $method = "AES-256-CBC";
    $iv = substr(hash('sha256', $key), 0, 16);

    // Encrypt sensitive data
    $phone_encrypted = openssl_encrypt($phone, $method, $key, 0, $iv);
    $address_encrypted = openssl_encrypt($address, $method, $key, 0, $iv);
    $gphone_encrypted = openssl_encrypt($gphone, $method, $key, 0, $iv);
    $gaddress_encrypted = openssl_encrypt($gaddress, $method, $key, 0, $iv);

    // File upload validation
    $uploadDone = true;
    $invalidFormat = false;
    $imageName = "1701517055user.png";
    $allowedExtensions = ['png', 'jpeg', 'jpg'];

    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $filename = $_FILES["image"]["name"];
        $tempname = $_FILES["image"]["tmp_name"];
        $fileInfo = pathinfo($filename);
        $fileExtension = strtolower($fileInfo['extension']);

        if (in_array($fileExtension, $allowedExtensions)) {
            $newName = $uniqueId . time() . "." . $fileExtension;
            $folder = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "studentUploads" . DIRECTORY_SEPARATOR . $newName;

            if (!move_uploaded_file($tempname, $folder)) {
                $uploadDone = false;
            }
            $imageName = $newName;
        } else {
            $response = "Invalid image format! (jpg, png, jpeg)";
            $invalidFormat = true;
        }
    }

    if (!$invalidFormat) {
        // Check if the email already exists
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $response = "Email already exists!";
        } else {
            // Insert student details
            $addStudentQuery = "INSERT INTO `students` (`s_no`, `id`, `fname`, `lname`, `father`, `gender`, `class`, `section`, `dob`, `image`, `phone`, `email`, `address`, `city`, `zip`, `state`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $addStudentQuery);
            mysqli_stmt_bind_param($stmt, "sssssssssssssss", $uniqueId, $fname, $lname, $father, $gender, $class, $section, $dob, $imageName, $phone_encrypted, $email, $address_encrypted, $city, $zip, $state);
            mysqli_stmt_execute($stmt);

            // Insert guardian details
            $addGuardianQuery = "INSERT INTO `student_guardian` (`s_no`, `id`, `gname`, `gphone`, `gaddress`, `gcity`, `gzip`, `relation`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $addGuardianQuery);
            mysqli_stmt_bind_param($stmt, "sssssss", $uniqueId, $guardian, $gphone_encrypted, $gaddress_encrypted, $gcity, $gzip, $relation);
            mysqli_stmt_execute($stmt);

            // Hash password based on DOB
            $password = str_replace("-", "", $dob);
            $passwordHash = password_hash($password, PASSWORD_ARGON2ID);

            // Insert user details
            $addUserQuery = "INSERT INTO `users` (`s_no`, `id`, `email`, `password_hash`, `role`, `theme`) VALUES (NULL, ?, ?, ?, 'student', 'light')";
            $stmt = mysqli_prepare($conn, $addUserQuery);
            mysqli_stmt_bind_param($stmt, "sss", $uniqueId, $email, $passwordHash);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $response = "success";
                $log->info("Student added successfully", ["fname" => $fname, "lname" => $lname, "email" => $email]);

                if (!$uploadDone) {
                    $response = "Image upload failed! (Student successfully added)";
                }
            } else {
                $response = "Error - Unable to add student";
            }
        }
    }
} else {
    $response = "Invalid request!";
}

echo $response;
?>
