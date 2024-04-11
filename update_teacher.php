<?php
@include 'config.php';

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:index.php');
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$db = "user_db";

$data = mysqli_connect($host, $user, $password, $db);

$teacher_id = $_GET['teacher_id'];

$sql = "SELECT * FROM add_tch WHERE teacher_id='$teacher_id'";
$result = mysqli_query($data, $sql);

$info = $result->fetch_assoc();

if (!$info) {
    echo "No data found for teacher ID: $teacher_id";
}

if (isset($_POST['update'])) {
    $name = $_POST['teacher_name'];
    $department = $_POST['department'];
    $semester = $_POST['semester'];

    $query = "UPDATE add_tch SET teacher_name=?, department=?, semester=? WHERE teacher_id=?";
    $stmt = mysqli_prepare($data, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssi", $name, $department, $semester, $teacher_id);
        $result2 = mysqli_stmt_execute($stmt);

        if ($result2) {
            $rowsAffected = mysqli_stmt_affected_rows($stmt);
            if ($rowsAffected > 0) {
                header("location:view_teacher.php");
                exit();
            } else {
                echo "No rows updated.";
            }
        } else {
            echo "Error updating record: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($data);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page - Update Teacher</title>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
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
            justify-content: center;
            padding-right: 300px;
        }

        .content {
            text-align: center;
        }

        .content h1 {
            padding-left:30px;
            margin-bottom: -30px;
            padding-top:50px
        }

        .div_deg {
            width: 400px;
            padding-bottom: 70px;
            padding-top: 70px;
            margin: 0 auto; 
        }

        label {
            display: inline-block;
            width: 100px;
            text-align: right;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        input {
            padding: 8px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }    </style>
</head>

<body>
    <div class="admin-header">
        <h1 onclick="location.href='admin_page.php';">Welcome, <?php echo $_SESSION['admin_name']; ?>!</h1>
        <div class="admin-links">
            <a href="add_student.php">Add Student</a>
            <a href="view_student.php">View Students</a>
            <a href="add_teacher.php">Add Teacher</a>
            <a href="view_teacher.php">View Teachers</a>
            <button class="logout-btn" onclick="location.href='logout.php';">Logout</button>
        </div>
    </div>

    <div class="content">
        <center>
            <h1>Update Teacher</h1>

            <div class="div_deg">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?teacher_id=" . $teacher_id); ?>" method="POST"> 
    <div>
        <label>Name</label>
        <input type="text" name="teacher_name" value="<?php echo htmlspecialchars($info['teacher_name']); ?>">
    </div>
    <div>
        <label>Department</label>
        <input type="text" name="department" value="<?php echo htmlspecialchars($info['department']); ?>">
    </div>
    <div>
        <label>Semester</label>
        <input type="text" name="semester" value="<?php echo htmlspecialchars($info['semester']); ?>">
    </div>
    <div>
        <input type="submit" name="update" value="Update">
    </div>
</form>

            </div>
        </center>
    </div>

</body>

</html>
