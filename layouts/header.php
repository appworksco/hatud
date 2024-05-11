<?php

// Start session management and output buffering
session_start();
ob_start();

// Array to store invalid and success messages
$invalid = array();
$success = array();
$errors = [];

include(__DIR__ . '/../utils/db/connector.php');
include(__DIR__ . '/../utils/models/users-facade.php');

// Initialize the facade classes
$usersFacade = new UsersFacade();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Transak POS">
    <meta name="author" content="Appworks Co.">
    <link rel="stylesheet" href="assets/css/style.css?<?= time() ?>">
    <link rel="stylesheet" href="vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="vendor/datatables/dataTables.bootstrap5.min.css">
    <title>Hatud</title>
</head>

<body>