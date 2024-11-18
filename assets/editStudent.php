<?php

session_start();
include("config.php");
include("../assets/monolog_config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get POST data
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["id"]));
    $fname = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["fname"]));
    $lname = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["lname"]));
    $father = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["father"]));
    $class = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["class"]));
    $section = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["section"]));
    $gender = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["gender"]));
    $dobString = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["dob"]));
    $timestamp = strtotime($dobString);
    $dob = date('d-m-Y', $timestamp) . "";

    $phone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["phone"]));
    $email = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["email"]));
    $address = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["address"]));
    $city = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["city"]));
    $zip = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["zip"]));
    $state = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["state"]));
    $guardian = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["guardian"]));
    $gphone = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["gphone"]));
    $gaddress = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["gaddress"]));
    $gcity = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["gcity"]));
    $gzip = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["gzip"]));
    $relation = htmlspecialchars(mysqli_real_escape_string($conn, $_POST["relation"]));

    // Encryption key and method 
    $key = ENCRYPTION_KEY;
    $method = "AES-256-CBC";
    $iv = substr(hash('sha256', $key), 0, 16);

    // Encrypt sensitive data
    $phone_encrypted = openssl_encrypt($phone, $method, $key, 0, $iv);
    $address_encrypted = openssl_encrypt($address, $method, $key, 0, $iv);
    $gphone_encrypted = openssl_encrypt($gphone, $method, $key, 0, $iv);
    $gaddress_encrypted = openssl_encrypt($gaddress, $method, $key, 0, $iv);

    // Check if the student exists
    $sql = "SELECT * FROM students WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {

        // Update student information
        $query = "UPDATE `students` SET `fname`=?, `lname`=?, `father`=?, `class`=?, `section`=?, `gender`=?, `dob`=?, `phone`=?, `email`=?, `address`=?, `city`=?, `zip`=?, `state`=? WHERE `id`=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssssssss", $fname, $lname, $father, $class, $section, $gender, $dob, $phone_encrypted, $email, $address_encrypted, $city, $zip, $state, $id);

        // Update guardian information
        $query2 = "UPDATE student_guardian SET gname=?, gphone=?, gaddress=?, gcity=?, gzip=?, relation=? WHERE id=?";
        $stmt2 = mysqli_prepare($conn, $query2);
        mysqli_stmt_bind_param($stmt2, "sssssss", $guardian, $gphone_encrypted, $gaddress_encrypted, $gcity, $gzip, $relation, $id);

        // Update user email (not encrypted)
        $query3 = "UPDATE users SET email=? WHERE id=?";
        $stmt3 = mysqli_prepare($conn, $query3);
        mysqli_stmt_bind_param($stmt3, "ss", $email, $id);

        // Execute queries
        if (mysqli_stmt_execute($stmt) && mysqli_stmt_execute($stmt2) && mysqli_stmt_execute($stmt3)) {
            echo 'success';
            $log->info('Student updated', ['id' => $id]);
        } else {
            echo "something went wrong! database";
            $log->error('Error updating student', ['id' => $id]);
        }

        // Close statements
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($stmt2);
        mysqli_stmt_close($stmt3);

    } else {
        echo 'Student not found';
        $log->error('Student not found', ['id' => $id]);
    }

} else {
    echo "Invalid request method";
    $log->error('Invalid request method');
}

?>
