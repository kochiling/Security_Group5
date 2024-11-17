<?php

session_start();
include("../assets/config.php");
include("../assets/monolog_config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $json_data = file_get_contents("php://input");
    $dataObject = json_decode($json_data, true);
    
    $id = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["id"]));
    $fname = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["fname"]));
    $lname = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["lname"]));
    $_class = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["class"]));
    $_section = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["section"]));

    $subject = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["subject"]));
    $gender = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["gender"]));

    $dobString = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["dob"]));
    $timestamp = strtotime($dobString);
    $dob = date('d-m-Y', $timestamp);

    $phone = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["phone"]));
    $email = htmlspecialchars(mysqli_real_escape_string($conn, $dataObject["email"]));
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
   
    $sql = "SELECT * FROM teachers WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0){
        $query = "UPDATE teachers SET fname=?, lname=?, class=?, section=?, subject=?, gender=?, dob=?, phone=?, email=?, address=?, city=?, zip=?, state=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssssssssssssss", $fname, $lname, $_class, $_section, $subject, $gender, $dob, $phone, $email, $address, $city, $zip, $state, $id);
      
        $query2 = "UPDATE teacher_guardian SET gname=?, gphone=?, gaddress=?, gcity=?, gzip=?, relation=? WHERE id=?";
        $stmt2 = mysqli_prepare($conn, $query2);
        mysqli_stmt_bind_param($stmt2, "sssssss", $guardian, $gphone, $gaddress, $gcity, $gzip, $relation, $id);
     

        $query3 = "UPDATE users SET email=? WHERE id=?";
        $stmt3 = mysqli_prepare($conn, $query3);
        mysqli_stmt_bind_param($stmt3, "ss", $email, $id);
        

        if (mysqli_stmt_execute($stmt) && mysqli_stmt_execute($stmt2) && mysqli_stmt_execute($stmt3)) {
            echo 'success';
            $log->info('Teacher edited', ['id' => $id]);
        } else {
            echo "something went wrong! database";
            $log->error('Unable to edit teacher', ['id' => $id]);
        }
        
        mysqli_stmt_close($stmt);
        mysqli_stmt_close($stmt2);
        mysqli_stmt_close($stmt3);

    } else {
        echo 'something went wrong!';
    }

    mysqli_close($conn);

} else {
    echo "something went wrong!";
}
?>
