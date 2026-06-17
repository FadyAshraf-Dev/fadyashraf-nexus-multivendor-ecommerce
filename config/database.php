<?php
// config/database.php

// Ensure session starts globally on any file that requires database access
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli("localhost", "root", "", "nexus");

if ($conn->connect_error) {
    die("Database failure: " . $conn->connect_error);
}

// Automatically bind your master DRY functions file to the database bootstrap
require_once __DIR__ . '/global-functions.php';