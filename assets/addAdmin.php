<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the AJAX request to add a new admin
    $json_data = file_get_contents("php://input");
    $dataObject = json_decode($json_data, true);

    $uniqueId = "A" . time();
    $email = $dataObject["email"];
    $password = $dataObject["password"];
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Check if the email already exists
    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo 'Email already exists!';
    } else {
        // Insert the new admin user
        $addUserQuery = "INSERT INTO `users` (`s_no`, `id`, `email`, `password_hash`, `role`, `theme`) VALUES (NULL, ?, ?, ?, 'admin', 'light')";
        $stmt = mysqli_prepare($conn, $addUserQuery);
        mysqli_stmt_bind_param($stmt, "sss", $uniqueId, $email, $passwordHash);
        mysqli_stmt_execute($stmt);
        echo 'success';
    }
    exit;
}
?>

<?php include('partials/_header.php'); ?>

<!-- Modal for Adding Admin -->
<div class="modal" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAdminModalLabel">Add Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addAdminForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('addAdminForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    fetch('', { // Same page handling PHP and form processing
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: email, password: password })
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        if (data === 'success') {
            alert('Admin added successfully!');
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
});
</script>

<?php include('partials/_footer.php'); ?>
