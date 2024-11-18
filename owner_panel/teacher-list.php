<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="../images/1.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>ERP</title>
</head>
<body>
    <div class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">SCHOOL MANAGEMENT</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="notices.php">Notice</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">Fee Pay</a>
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
                        <input class="form-control me-2" type="search" placeholder="Search" id="search-teacher" aria-label="Search">
                        <button class="btn btn-outline-success" type="button">Search</button>
                    </form>
                </div>
            </div>
        </nav>
    </div>
    <div class="teacher-list">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Sr_NO</th>
                    <th scope="col">NAME</th>
                    <th scope="col">Gender</th>
                    <th scope="col">MORE DETAILS</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="tb">
                <!-- Content populated by AJAX -->
            </tbody>
        </table>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            function load_table() {
                console.log("Loading table...");
                $.ajax({
                    url: "fetch-data/fetch-teachers.php",
                    method: "POST",
                    success: function(data){
                        $("#tb").html(data);
                        console.log("Table loaded successfully.");
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading table data:", error);
                    }
                });
            }

            load_table();

            $("#search-teacher").on("keyup", function(){
                var search = $(this).val();
                $.ajax({
                    url: "fetch-data/search-teacher.php",
                    type: "POST",
                    data: {search: search},
                    success: function(data){
                        $("#tb").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("Search AJAX error:", error);
                    }
                });
            });

            // Handle role change to admin
            $(document).on("click", ".change-role-btn", function(){
                var teacherId = $(this).data("id");
                console.log("Button clicked for teacher ID:", teacherId); // Log to check if click is detected

                if (!teacherId) {
                    console.error("No teacher ID found on button click.");
                    return;
                }

                $.ajax({
                    url: "../assets/changeTeacherRole.php",
                    type: "POST",
                    data: {id: teacherId},
                    success: function(response) {
                        console.log("AJAX success response:", response);
                        alert(response); // Show response from changeTeacherRole.php
                        load_table(); // Reload table to reflect changes
                    },
                    error: function(xhr, status, error) {
                        console.error("Error in AJAX call to change role:", error);
                    }
                });
            });
        });
    </script>
</body>
</html>

