<?php
include("config.php");

$query = "SELECT id, email, role FROM users WHERE role IN ('admin', 'admin_block');";
$resultOutput = array();

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $adminId = $row["id"];
        $email = $row["email"];
        $role = $row["role"];
        
        $resultOutput[] = [
            'id' => $adminId,
            'email' => $email,
            'role' => $role
        ];
    }
    echo json_encode($resultOutput);
} else {
    echo json_encode("No_Record");
}
?>
