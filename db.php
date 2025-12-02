<?php
// db.php – database connection only

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = ''; 
$DB_NAME = 'college_db';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($mysqli->connect_error) {
    die('DB connection error: ' . $mysqli->connect_error);
}

// Utility functions
function e($s) { return htmlspecialchars($s, ENT_QUOTES); }
function is_student() { return isset($_SESSION['student_id']); }
function is_faculty() { return isset($_SESSION['faculty_id']); }
function require_student() { if(!is_student()) { header('Location: index.php'); exit; } }
function require_faculty() { if(!is_faculty()) { header('Location: index.php'); exit; } }
?>
