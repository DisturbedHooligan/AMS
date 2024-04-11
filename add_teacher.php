<?php
session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location: index.php');
    exit();
}

$host = "localhost";
$user = "root";
$password = "";
$db = "user_db";

$data = mysqli_connect($host, $user, $password, $db);

$errors = array();

if (isset($_POST['add_teacher'])) {
    $teacher_name = trim($_POST['teacher_name']);
    $teacher_department = trim($_POST['department']);
    $teacher_semester = trim($_POST['semester']);

    if (empty($teacher_name)) {
        $errors[] = "Name is required";
    }

    if (empty($teacher_department)) {
        $errors[] = "Department is required";
    }

    if (empty($teacher_semester)) {
        $errors[] = "Semester is required";
    }

    if (empty($errors)) {
        $check_query = "SELECT * FROM add_tch WHERE teacher_name='$teacher_name'";
        $check_result = mysqli_query($data, $check_query);
        $row_count = mysqli_num_rows($check_result);
    
        if ($row_count > 0) {
            echo "<script type='text/javascript'>
                        alert('Teacher with this name already exists');
                      </script>";
        } else {
            $insert_query = "INSERT INTO add_tch (teacher_name, department, semester) VALUES('$teacher_name','$teacher_department','$teacher_semester')";
            $insert_result = mysqli_query($data, $insert_query);
    
            if ($insert_result) {
                echo "<script type='text/javascript'>
                        alert('Teacher added successfully');
                      </script>";
            } else {
                echo "Data Invalid: " . mysqli_error($data);
            }
        }
    }
    else {
        foreach ($errors as $error) {
            echo "<script type='text/javascript'>
                    alert('{$error}');
                  </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style type="text/css">
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

        label {
            display: inline-block;
            text-align: right;
            width: 100px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .div_deg {
            width: 400px;
            padding-top: 70px;
            padding-bottom: 70px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: center; 
        }

        .form-group input[type="submit"] {
            display: inline-block;
        }

        .center-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .content h1{
            position: absolute;
            padding-left: 120px;
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
    <?php
    include 'admin_css.php'; 
    ?>
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
        <a href="add_student.php">Add Student</a>
        <a href="view_student.php">View Students</a>
        <a href="view_teacher.php">View Teachers</a>
        <a href="view_pending_registrations.php">Pending Registrations</a>

        <button class="logout-btn" onclick="location.href='logout.php';">Logout</button>
    </div>
</div>


    <div class="content">
        <div class="center-container">
            <h1>Add Teacher</h1>
            <div class="div_deg">
                <form action="" method="POST">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="teacher_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Department</label>
                        <input type="text" name="department" required>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <input type="text" name="semester" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" name="add_teacher" value="Add Teacher">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
