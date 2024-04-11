<?php
@include 'config.php';
error_reporting(0);
session_start();

if (!isset($_SESSION['user_name'])) {
    header('location:index.php');
}

$host = "localhost";
$user = "root";
$password = "";
$db = "user_db";

$data = mysqli_connect($host, $user, $password, $db);

$sql = "SELECT * FROM user_form WHERE user_type = 'Teacher'";
$result = mysqli_query($data, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student page</title>
    <?php
    include 'admin_css.php';
    ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <style type="text/css">
        .table_th {
            padding: 20px;
            font-size: 20px;
        }

        .table_td {
            padding: 20px;
        }

        .student-header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .student-header h1 {
            font-size: 20px;
            margin: 0;
            cursor: pointer;
        }

        .student-header h1:hover {
            color: #fff;
        }

        .student-links {
            display: flex;
            align-items: center;
        }

        .student-links a {
            margin-left: 20px;
            text-decoration: none;
            color: #fff;
            font-size: 16px;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .student-links a:hover {
            background-color: #0056b3;
            color: #fff;
        }

        .logout-btn {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .center-form {
            display: flex;
            justify-content: center ;
            padding-right: 300px;
        }

        .content {
            text-align: center;
            padding-right: 275px; 
        }

        .content h1 {
            margin-bottom: 20px;
        }
        .student-header a h1{
            color: #000; 

        }
        .student-header a h1:hover{
            color: #fff; /* Set the link color on hover */
        background-color: #007bff; /* Set background color on hover */
        transition: color 0.3s ease, background-color 0.3s ease; /* Add a smooth transition effect */
   

        }
        .student-header a{
            color: #000; 

        }
        .student-header a{
            color: #fff; /* Set the link color on hover */
        background-color: #007bff; /* Set background color on hover */
        transition: color 0.3s ease, background-color 0.3s ease; /* Add a smooth transition effect */
   
        }
    </style>

</head>

<body>
<div class="student-header">
    <div style="display: flex; align-items: center; cursor: pointer;">
        <a href="student_profile.php">
            <i class="fa fa-user" style="margin-right: 10px;"></i>
        </a>
        <a href="student_page.php">
            <h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
        </a>
    </div>
    <div class="student-links">
            <a href="Attendance.php">Attendance</a>
            <a href="teachers.php">Teachers</a>
            <button class="logout-btn" onclick="location.href='logout.php';">Logout</button>
        </div>
</div>


    <div class="content">
        <center>
            <h1>Teacher Data</h1>

            <?php
            if ($_SESSION['message']) {
                echo $_SESSION['message'];
            }

            unset($_SESSION['message']);
            ?>
            <table border="1px">
                <tr>
                    <th class="table_th">Name</th>
                    <th class="table_th">Email</th>
                </tr>

                <?php
                while ($info = $result->fetch_assoc()) {
                ?>

                    <tr>
                        <td class="table_td">
                            <?php echo "{$info['name']}"; ?>
                        </td>
                        <td class="table_td">
                            <?php echo "{$info['email']}"; ?>
                        </td>
                       
                        
                    </tr>

                <?php
                }
                ?>
            </table>
        </center>
    </div>

</body>

</html>


