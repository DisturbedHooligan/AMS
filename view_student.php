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

$sql = "SELECT * FROM add_std";
$result = mysqli_query($data, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
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

        .admin-header {
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

        .admin-header h1 {
            font-size: 20px;
            margin: 0;
            cursor: pointer;
        }

        .admin-header h1:hover {
            color: #fff;
        }

        .admin-links {
            display: flex;
            align-items: center;
        }

        .admin-links a {
            margin-left: 20px;
            text-decoration: none;
            color: #fff;
            font-size: 16px;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .admin-links a:hover {
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
        }

        .content h1 {
            padding-right: 300px;

            margin-bottom: 20px;
        }
        .admin-header a h1{
            color: #000; 

        }
        .admin-header a h1:hover{
            color: #fff;
        background-color: #007bff;
        transition: color 0.3s ease, background-color 0.3s ease; 
   

        }
        .admin-header a{
            color: #000; 

        }
        .admin-header a{
            color: #fff;
        background-color: #007bff; 
        transition: color 0.3s ease, background-color 0.3s ease;
   
        }
    </style>

</head>

<body>
    <div class="admin-header">
        <div style="display: flex; align-items: center; cursor: pointer;">
            <a href="admin_profile.php">
                <i class="fa fa-user" style="margin-right: 10px;"></i>
            </a>
            <a href="admin_page.php">
                <h1>Welcome, <?php echo $_SESSION['admin_name']; ?>!</h1>
            </a>
        </div>
        <div class="admin-links">
        <a href="view_student.php">View Students</a>
            <a href="view_teacher.php">View Teachers</a>
            <a href="view_pending_registrations.php">Pending Registrations</a>

            <button class="logout-btn" onclick="location.href='logout.php';">Logout</button>
        </div>
    </div>

    <div class="content">
        <h1>Student Data</h1>

        <?php
        if ($_SESSION['message']) {
            echo $_SESSION['message'];
        }

        unset($_SESSION['message']);
        ?>
        <div class="center-form">
            <table border="1px">
                <tr>
                    <th class="table_th">Name</th>
                    <th class="table_th">Roll</th>
                    <th class="table_th">Department</th>
                    <th class="table_th">Semester</th>
                    <th class="table_th">Actions</th>
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
                            <a class='btn btn-primary' href='update_student.php?student_id=<?php echo $info['student_id']; ?>'>Edit</a>
                            <a onclick="return confirm('Are you sure to delete this student?');" class='btn btn-danger' href='delete_std.php?student_id=<?php echo $info['student_id']; ?>'>Delete</a>
                        </td>
                    </tr>

                <?php
                }
                ?>
            </table>
        </div>
    </div>

</body>

</html>