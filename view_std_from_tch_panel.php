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

// Fetch data from add_std and attendance tables
$sql = "SELECT a.*, b.status, b.attendance_date FROM add_std a LEFT JOIN attendance b ON a.student_id = b.student_id";
$result = mysqli_query($data, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student page</title>
    <?php include 'admin_css.php'; ?>
    <style type="text/css">
        .table_th {
            padding: 20px;
            font-size: 20px;
            text-align: center;
        }

        .table_td {
            padding: 20px;
            text-align: center;
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

        .content {
            text-align: center;
            padding-right: 275px;
        }

        .content h1 {
            margin-bottom: 20px;
        }

        .go-back-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }

        .go-back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="content">
        <center>
            <h1>Student Data</h1>

            <?php
            if ($_SESSION['message']) {
                echo $_SESSION['message'];
            }

            unset($_SESSION['message']);
            ?>
            <table border="1px">
                <tr>
                    <th class="table_th">Name</th>
                    <th class="table_th">Roll Number</th>
                    <th class="table_th">Semester</th>
                    <th class="table_th">Date</th>
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
                            <?php echo "{$info['semester']}"; ?>
                        </td>
                        <td class="table_td">
                            <?php echo "{$info['attendance_date']}"; ?>
                        </td>
                        <td class="table_td">
                            <?php echo "{$info['status']}"; ?>
                        </td>
                    </tr>

                <?php
                }
                ?>
            </table>
            <a href="teacher_page.php" class="go-back-btn">Go Back</a>
        </center>
    </div>
</body>

</html>
