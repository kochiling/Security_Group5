<?php
include("../assets/noSessionRedirect.php"); 
include('./fetch-data/verfyRoleRedirect.php');

error_reporting(0);
session_start();
$uid = $_SESSION['id'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="../images/1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>ERP - owner</title>
</head>
<body>
    <div class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">SCHOOL MANAGEMENT</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="notices.php">Notice</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Fee Pay</a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="make-payment.php">PAYROLL</a></li>
                                <li><a class="dropdown-item" href="see-payment.php">See Payment</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="change-password.php">Change-Password</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    </ul>
                    <form class="d-flex">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </nav>
    </div>

    <div class="main">
        <?php
        // Fetch admin count
        $sql_admin = "SELECT COUNT(*) as total_admins FROM users WHERE role='admin'";
        $result_admin = mysqli_query($conn, $sql_admin);
        if (mysqli_num_rows($result_admin) > 0) {
            $admin_row = mysqli_fetch_assoc($result_admin);
        ?>
        <div class="card1">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="img/admin.png" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Total Admins: <?php echo $admin_row['total_admins']; ?></h5>
                    <p class="card-text">Quick overview of the total admin count.</p>
                    <a href="admin.php" class="btn btn-primary">See Admins List</a>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php
        // Fetch teacher count
        $sql_teacher = "SELECT COUNT(*) as total_teachers FROM users WHERE role='teacher'";
        $result_teacher = mysqli_query($conn, $sql_teacher);
        if (mysqli_num_rows($result_teacher) > 0) {
            $teacher_row = mysqli_fetch_assoc($result_teacher);
        ?>
        <div class="card1">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="img/teacher.png" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Total Teachers: <?php echo $teacher_row['total_teachers']; ?></h5>
                    <p class="card-text">Quick overview of the total teacher count.</p>
                    <a href="teacher-list.php" class="btn btn-primary">See Teachers List</a>
                </div>
            </div>
        </div>
        <?php } ?>

        <?php
        // Fetch student count
        $sql_student = "SELECT COUNT(*) as total_students FROM users WHERE role='student'";
        $result_student = mysqli_query($conn, $sql_student);
        if (mysqli_num_rows($result_student) > 0) {
            $student_row = mysqli_fetch_assoc($result_student);
        ?>
        <div class="card2">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="img/student.png" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">Total Students: <?php echo $student_row['total_students']; ?></h5>
                    <p class="card-text">Quick overview of the total student count.</p>
                    <a href="student-list.php" class="btn btn-primary">See Students List</a>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <footer class="text-center bg-body-tertiary">
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            © <?php echo date('Y'); ?> Copyright:
            <a class="text-body" target="_blank" href="https://www.github.com/ProjectsAndPrograms">ProjectsAndPrograms</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
