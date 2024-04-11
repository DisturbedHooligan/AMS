<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "user_db";

$data = mysqli_connect($host, $user, $password, $db);

if (isset($_GET['student_id'])) {
    $student_id = mysqli_real_escape_string($data, $_GET['student_id']); 

    $sql = "DELETE FROM add_std WHERE student_id='$student_id'";
    $result = mysqli_query($data, $sql);

    if ($result) {
        $_SESSION['message'] = 'Student deletion successful';
        header("location:view_student.php");
        exit();
    } else {
        echo "Error deleting student record: " . mysqli_error($data);
    }
}
?>
