<?php
include('config.php');
require __DIR__ . "/../vendor/autoload.php";
include('monolog_config.php');
session_start();

$log->info('Owner logged out', ['uid' => $_SESSION['uid']]);

session_unset();
header('Location: ../index.php');
?>