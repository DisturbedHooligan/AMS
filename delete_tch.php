<?php
@include 'config.php';
error_reporting(0);
session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:index.php');
}

$host = "localhost";
$user = "root";
$password = "";
$db = "user_db";

$data = mysqli_connect($host, $user, $password, $db);

if (isset($_GET['teacher_id'])) {
    $teacherId = mysqli_real_escape_string($data, $_GET['teacher_id']);
    
    $sql = "DELETE FROM user_form WHERE id = '$teacherId'";
    
    if (mysqli_query($data, $sql)) {
        $_SESSION['message'] = "<div class='alert alert-success'>Teacher data deleted successfully!</div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger'>Error deleting teacher data: " . mysqli_error($data) . "</div>";
    }
    
    header('Location: view_teacher.php');
    exit();
} else {
    header('Location: view_teacher.php');
    exit();
}
?>
