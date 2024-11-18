<?php

session_start();
include("../assets/config.php");
include("../assets/monolog_config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Parse JSON input
    $json_data = file_get_contents("php://input");
    $dataObject = json_decode($json_data, true);

    // Encryption setup
    $key = ENCRYPTION_KEY;
    $method = "AES-256-CBC";
    $iv = substr(hash('sha256', $key), 0, 16);

    // Sanitize inputs and encrypt sensitive data
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["id"]));
    $fname = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["fname"]));
    $lname = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["lname"]));
    $_class = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["class"]));
    $_section = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["section"]));
    $subject = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["subject"]));
    $gender = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["gender"]));

    // Handle date conversion
    $dobString = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["dob"]));
    $timestamp = strtotime($dobString);
    $dob = date('d-m-Y', $timestamp);

    // Encrypt sensitive fields
    $phone = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["phone"]));
    $phone_encrypted = openssl_encrypt($phone, $method, $key, 0, $iv);

    $email = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["email"]));
    
    $address = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["address"]));
    $address_encrypted = openssl_encrypt($address, $method, $key, 0, $iv);

    $city = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["city"]));
    $zip = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["zip"]));
    $state = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["state"]));

    $guardian = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["guardian"]));
    $gphone = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["gphone"]));
    $gphone_encrypted = openssl_encrypt($gphone, $method, $key, 0, $iv);

    $gaddress = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["gaddress"]));
    $gaddress_encrypted = openssl_encrypt($gaddress, $method, $key, 0, $iv);

    $gcity = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["gcity"]));
    $gzip = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["gzip"]));
    $relation = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["relation"]));

    // Check if teacher exists
    $sql = "SELECT * FROM teachers WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Update teacher information
        $query = "UPDATE teachers SET fname=?, lname=?, class=?, section=?, subject=?, gender=?, dob=?, phone=?, email=?, address=?, city=?, zip=?, state=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssssssss", $fname, $lname, $_class, $_section, $subject, $gender, $dob, $phone_encrypted, $email, $address_encrypted, $city, $zip, $state, $id);

        // Update guardian information
        $query2 = "UPDATE teacher_guardian SET gname=?, gphone=?, gaddress=?, gcity=?, gzip=?, relation=? WHERE id=?";
        $stmt2 = mysqli_prepare($conn, $query2);
        mysqli_stmt_bind_param($stmt2, "sssssss", $guardian, $gphone_encrypted, $gaddress_encrypted, $gcity, $gzip, $relation, $id);

        // Update user email
        $query3 = "UPDATE users SET email=? WHERE id=?";
        $stmt3 = mysqli_prepare($conn, $query3);
        mysqli_stmt_bind_param($stmt3, "ss", $email, $id);

        if (mysqli_stmt_execute($stmt) && mysqli_stmt_execute($stmt2) && mysqli_stmt_execute($stmt3)) {
            echo 'success';
            $log->info('Teacher edited', ['id' => $id]);
        } else {
            echo "something went wrong! database update failed";
            $log->error('Unable to edit teacher', ['id' => $id]);
        }

        // Close statements
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($stmt2);
        mysqli_stmt_close($stmt3);

    } else {
        echo 'something went wrong! teacher not found or invalid ID';
        $log->warning('Teacher not found', ['id' => $id]);
    }

    mysqli_close($conn);

} else {
    echo "something went wrong! invalid request method";
    $log->error('Invalid request method');
}

?>
