<?php
include("../assets/noSessionRedirect.php"); 
include('./fetch-data/verfyRoleRedirect.php');
include("../../assets/config.php");

error_reporting(0);
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
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <title>ERP</title>
    <style type="text/css">
         .card{
                
                position: absolute;
                margin-top: 5%;
         }
         .detail{
         	height: auto;
         	width: 100%;
         	display: flex;
         	justify-content: center;
         	flex-direction: row;

         }
         .card{
         	width: 40%;
         }
         @media (max-width: 700px){
         	.card{
         		width: 80%;
         	}
         }
    </style>
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
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="notices.php">Notice</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Fee Pay
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="make-payment.php">Make Payment</a></li>
            <li><a class="dropdown-item" href="see-payment.php">See Payment</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="change-password.php">Change-Password</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">Logout</a>
        </li>
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>
    </div>
	<div class="detail">
    <?php
  
  $id = $_GET['id'];

  // Fetch student data by ID
   $sql = "SELECT * FROM students WHERE id = ?";
   $stmt = mysqli_prepare($conn, $sql);
   mysqli_stmt_bind_param($stmt, "s", $id);
   mysqli_stmt_execute($stmt);
   $result = mysqli_stmt_get_result($stmt);
 
   // Decryption key and method
   $key = ENCRYPTION_KEY; // Your encryption key
   $method = "AES-256-CBC"; // Decryption method
   $iv = substr(hash('sha256', $key), 0, 16); // Ensure the same IV is used
 
   $data = "";
   if (mysqli_num_rows($result) > 0) {
     while ($row = mysqli_fetch_assoc($result)) {
       // Decrypt sensitive data
       $phone_decrypted = openssl_decrypt($row['phone'], $method, $key, 0, $iv);
       $address_decrypted = openssl_decrypt($row['address'], $method, $key, 0, $iv);
 
       // Escape output to prevent XSS
       $fname = htmlspecialchars($row['fname'], ENT_QUOTES, 'UTF-8');
       $lname = htmlspecialchars($row['lname'], ENT_QUOTES, 'UTF-8');
       $email = htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8');
       $father = htmlspecialchars($row['father'], ENT_QUOTES, 'UTF-8');
       $gender = htmlspecialchars($row['gender'], ENT_QUOTES, 'UTF-8');
       $dob = htmlspecialchars($row['dob'], ENT_QUOTES, 'UTF-8');
       $city = htmlspecialchars($row['city'], ENT_QUOTES, 'UTF-8');
       $state = htmlspecialchars($row['state'], ENT_QUOTES, 'UTF-8');
       $image = htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8');
 
       // Construct the student details card
       $data .= "<div class='card'>
             <img src='../studentUploads/" . $image . "' class='card-img-top' alt='profile image of student'/>
             <div class='card-body'>
               <h5 class='card-title'></h5>
               <p class='card-text'>Some quick example text to build on the card title and make up the bulk of the card's content.</p>
             </div>
             <ul class='list-group list-group-light list-group-small'>
               <li class='list-group-item px-4'>Name: " . $fname . " " . $lname . "</li>
               <li class='list-group-item px-4'>Email: " . $email . "</li>
               <li class='list-group-item px-4'>Father's Name: " . $father . "</li>
               <li class='list-group-item px-4'>Gender: " . $gender . "</li>
               <li class='list-group-item px-4'>Phone: " . htmlspecialchars($phone_decrypted, ENT_QUOTES, 'UTF-8') . "</li>
               <li class='list-group-item px-4'>D-O-B: " . $dob . "</li>
               <li class='list-group-item px-4'>Address: " . htmlspecialchars($address_decrypted, ENT_QUOTES, 'UTF-8') . "</li>
               <li class='list-group-item px-4'>City: " . $city . "</li>
               <li class='list-group-item px-4'>State: " . $state . "</li>
             </ul>
             <div class='card-body'>
               <a href='student-attendence.php?id=" . $row['id'] . "' class='card-link'>
                 <button id='fee' data-id='" . $row['id'] . "' style='height: 35px; width: 100px; background-color: green; color: white; border: none; border-radius: 8px; text-decoration: none;'>Fee Status</button>
               </a>
               <a href='student-attendence.php?id=" . $row['id'] . "' class='card-link'>
                 <button id='attendence' data-id='" . $row['id'] . "' style='height: 35px; width: 100px; background-color: #4f4446; color: white; border: none; border-radius: 8px; text-decoration: none;'>Attendance</button>
               </a>
             </div>
           </div>";
     }
     echo $data;
   } else {
     echo "No student found.";
   }
 
   mysqli_stmt_close($stmt);
  ?>
</div>
<br><br>
</body>
</html>
