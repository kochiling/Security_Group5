<?php
include('config.php');
require __DIR__ . "/../vendor/autoload.php";
include('monolog_config.php');
session_start();

$log->info('Admin logged out', ['uid' => $_SESSION['uid']]);

unset($_SESSION);
session_destroy();


$response = array(
    'status' => 'success',
    'message' => 'Logout successful'
);
echo json_encode($response);
