<?php

// Starting output buffering and session
ob_start();
session_start();

// Unsetting and destroying the session
session_unset();
session_destroy();

// Redirecting to index page
header("Location: index");