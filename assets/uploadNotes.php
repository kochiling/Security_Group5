<?php
session_start();
include("../assets/config.php");
include("../assets/monolog_config.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = "";

    // Sanitize input fields
    $comment = htmlspecialchars(mysqli_real_escape_string($conn, filter_var($_POST["comment"], FILTER_SANITIZE_STRING)));
    $class = htmlspecialchars(mysqli_real_escape_string($conn, filter_var($_POST["class"], FILTER_SANITIZE_STRING)));
    $subject = htmlspecialchars(mysqli_real_escape_string($conn, filter_var($_POST["subject"], FILTER_SANITIZE_STRING)));
    $title = htmlspecialchars(mysqli_real_escape_string($conn, filter_var($_POST["title"], FILTER_SANITIZE_STRING)));
    $senderId = $_SESSION['uid'];

    // Check if file is uploaded and there are no errors
    if (
        isset($_FILES["file"]) && $_FILES["file"]["error"] == 0 &&
        isset($title) && isset($class) && isset($subject) && isset($comment)
    ) {

        $filename = $_FILES["file"]["name"];
        $tempname = $_FILES["file"]["tmp_name"];
        $fileInfo = pathinfo($filename);
        $fileExtension = strtolower($fileInfo['extension']);

        // Validate file extension (e.g., allow PDF, DOCX, JPG, PNG)
        $allowedExtensions = ['pdf', 'docx', 'jpg', 'jpeg', 'png', 'pptx'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            $response = "Invalid file type! Only PDF, DOCX, JPG, PNG are allowed.";
            echo $response;
            exit();
        }

        // Get file MIME type for further validation
        $fileMimeType = mime_content_type($tempname);
        $allowedMimeTypes = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/png', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];

        if (!in_array($fileMimeType, $allowedMimeTypes)) {
            $response = "Invalid file type! The uploaded file's MIME type is not allowed.";
            echo $response;
            exit();
        }

        // Generate a new filename using the sender ID and current timestamp
        $newName = $senderId . time() . "." . $fileExtension;
        $folder = __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "notesUploads" . DIRECTORY_SEPARATOR . $newName;

        // Move the uploaded file to the destination folder
        if (move_uploaded_file($tempname, $folder)) {

            // Insert note information into the database
            $query = "INSERT INTO `notes` (`s_no`, `sender_id`, `editor_id`, `class`, `subject`, `title`, `comment`, `file`, `timestamp`) 
                      VALUES (NULL,?,?,?,?,?,?,?, current_timestamp());";

            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sssssss", $senderId, $senderId, $class, $subject, $title, $comment, $newName);

            if (mysqli_stmt_execute($stmt)) {
                $response = "success";
                $log->info('Note created', ['title' => $title, 'class' => $class, 'subject' => $subject, 'senderId' => $senderId]);
            } else {
                $response = "Unable to create note!";
            }

            mysqli_stmt_close($stmt);
        } else {
            $response = "File upload failed!";
            $log->error('File upload failed', ['senderId' => $senderId]);
        }
    } else {
        $response = "Missing required fields or file upload error!";
    }
} else {
    $response = "Invalid request method!";
}

echo $response;
mysqli_close($conn);
