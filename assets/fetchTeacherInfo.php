<?php

include('config.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $data = array(
        'id' => $id
    );

    // Decryption settings
    $key = ENCRYPTION_KEY;
    $method = "AES-256-CBC";
    $iv = substr(hash('sha256', $key), 0, 16); // Generate the IV from the encryption key

    $sql = "SELECT *
            FROM teachers
            INNER JOIN teacher_guardian ON teachers.id = teacher_guardian.id  
            WHERE teachers.id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result)) {
        while ($row = mysqli_fetch_assoc($result)) {

            // Teacher's basic information
            $data["fname"] = $row["fname"];
            $data["lname"] = $row["lname"];
            $data["class"] = $row["class"];
            $data["section"] = $row["section"];
            $data["subject"] = $row["subject"];
            $data["gender"] = $row["gender"];

            // Decrypting DOB
            $dobString = $row["dob"];
            $timestamp = strtotime($dobString);
            $data["dob"] = date('Y-m-d', $timestamp);

            // Decrypting sensitive data
            $data["phone"] = openssl_decrypt($row["phone"], $method, $key, 0, $iv);
            $data["email"] = $row["email"];
            $data["address"] = openssl_decrypt($row["address"], $method, $key, 0, $iv);
            $data["city"] = $row["city"];
            $data["zip"] = $row["zip"];
            $data["state"] = $row["state"];

            // Decrypting guardian data
            $data["guardian"] = $row["gname"];
            $data["gphone"] = openssl_decrypt($row["gphone"], $method, $key, 0, $iv);
            $data["gaddress"] = openssl_decrypt($row["gaddress"], $method, $key, 0, $iv);
            $data["gcity"] = $row["gcity"];
            $data["gzip"] = $row["gzip"];
            $data["relation"] = $row["relation"];
        }
    }

    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData;

    mysqli_stmt_close($stmt);
}
?>
