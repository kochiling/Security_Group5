<?php
include('../assets/config.php');
require __DIR__ . "/../vendor/autoload.php";
include('../assets/monolog_config.php');
session_start();

$log->info('Student logged out', ['uid' => $_SESSION['uid']]);

session_unset();
header('Location: ../index.php');
