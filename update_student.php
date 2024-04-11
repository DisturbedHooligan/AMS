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

$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : null;

if (!$student_id) {
    echo "Error: Missing 'student_id' parameter in the URL.";
    exit();
}

$sql = "SELECT * FROM add_std WHERE student_id='$student_id'";
$result = mysqli_query($data, $sql);

if (!$result) {
    echo "Error retrieving student information: " . mysqli_error($data);
    exit();
}

$info = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $name = $_POST['student_name']; 
    $roll = $_POST['roll'];
    $department = $_POST['department'];
    $semester = $_POST['semester'];

    $query = "UPDATE add_std SET student_name=?, roll=?, department=?, semester=? WHERE student_id=?";
    $stmt = mysqli_prepare($data, $query);

    if (!$stmt) {
        echo "Error preparing update statement: " . mysqli_error($data);
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssssi", $name, $roll, $department, $semester, $student_id);

    $result2 = mysqli_stmt_execute($stmt);

    if ($result2) {
        header("location:view_student.php");
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($data);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page - Update Student</title>
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
            text-align: center; 
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
        }
    </style>
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
        <h1>Update Student</h1>

        <div class="div_deg">
            <form action="" method="POST">
                <div>
                    <label>Name</label>
                    <input type="text" name="student_name" value="<?php echo htmlspecialchars($info['student_name']); ?>">
                </div>
                <div>
                    <label>Roll Number</label>
                    <input type="number" name="roll" value="<?php echo htmlspecialchars($info['roll']); ?>">
                </div>
                <div>
                    <label>Department</label>
                    <input type="text" name="department" value="<?php echo htmlspecialchars($info['department']); ?>">
                </div>
                <div>
    <label>Semester</label>
    <select name="semester" required style="width: 190px; padding: 8px;">
        <option value="1st" <?php if ($info['semester'] == '1st') echo 'selected'; ?>>1st</option>
        <option value="2nd" <?php if ($info['semester'] == '2nd') echo 'selected'; ?>>2nd</option>
        <option value="3rd" <?php if ($info['semester'] == '3rd') echo 'selected'; ?>>3rd</option>
        <option value="4th" <?php if ($info['semester'] == '4th') echo 'selected'; ?>>4th</option>
        <option value="5th" <?php if ($info['semester'] == '5th') echo 'selected'; ?>>5th</option>
        <option value="6th" <?php if ($info['semester'] == '6th') echo 'selected'; ?>>6th</option>
        <option value="7th" <?php if ($info['semester'] == '7th') echo 'selected'; ?>>7th</option>
        <option value="8th" <?php if ($info['semester'] == '8th') echo 'selected'; ?>>8th</option>
    </select>
</div>



    </select>
</div>

                <div>
                    <input type="submit" name="update" value="Update">
                </div>
            </form>
        </div>
    </div>
</body>

</html>
