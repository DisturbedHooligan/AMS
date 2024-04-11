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

$sql = "SELECT * FROM add_std";

$result = mysqli_query($data, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student page</title>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .content {
            margin-top: 20px;
            text-align: center;
        }

        .content .btn {
            background-color: #007bff; 
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            text-decoration: none;
        }

        .content .btn:hover {
            background-color: #0056b3; 
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        .table_th,
        .table_td {
            padding: 15px;
            font-size: 16px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .table_th {
            background-color: #333;
            color: #fff;
        }

        .table_td {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="content">
        <h1>Student data</h1>

        <?php
        if ($_SESSION['message']) {
            echo $_SESSION['message'];
        }

        unset($_SESSION['message']);
        ?>
        <table border="1px">
            <tr>
                <th class="table_th">Name</th>
                <th class="table_th">Roll number</th>
                <th class="table_th">Department</th>
                <th class="table_th">Semester</th>
                <th class="table_th">Status</th>
            </tr>

            <?php
            while ($info = $result->fetch_assoc()) {
            ?>

                <tr>
                    <td class="table_td">
                        <?php echo "{$info['student_name']}"; ?>
                    </td>
                    <td class="table_td">
                        <?php echo "{$info['roll']}"; ?>
                    </td>
                    <td class="table_td">
                        <?php echo "{$info['department']}"; ?>
                    </td>
                    <td class="table_td">
                        <?php echo "{$info['semester']}"; ?>
                    </td>
                    <td class="table_td">
                        <?php echo "{$info['status']}"; ?>
                    </td>
                </tr>

            <?php
            }
            ?>
        </table>

        <a href="student_page.php" class="btn">Go Back</a>
    </div>
</body>

</html>
