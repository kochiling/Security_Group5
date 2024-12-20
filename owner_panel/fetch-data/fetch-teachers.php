<?php
include("../../assets/config.php");

$sql = "SELECT * FROM teachers";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <th scope='row'>" . $row['s_no'] . "</th>
                <td>" . $row['fname'] . " " . $row['lname'] . "</td>
                <td>" . $row['gender'] . "</td>
                <td><a href='modal-teacher.php?id=" . $row['id'] . "'>
                    <button id='view-more' data-id='" . $row['id'] . "' style='height: 35px; width: 100px; background-color: skyblue; color: white; border: none; border-radius: 8px;'>View More</button>
                </a></td>
                <td>
                    <button class='btn btn-primary change-role-btn' data-id='" . $row['id'] . "' 
                    style='height: 35px; width: 150px; background-color: skyblue; color: white; border: none; border-radius: 8px;'>Change to Admin</button>
                </td>
            </tr>";
    }
} else {
    echo "<tr><td colspan='5' class='text-center'>No teachers found.</td></tr>";
}

mysqli_close($conn);
?>
