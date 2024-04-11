<?php
@include 'config.php';

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

if (isset($_POST['add_student'])) {
    $std_name = trim($_POST['student_name']);
    $std_roll = trim($_POST['roll']);
    $std_department = trim($_POST['department']);
    $std_semester = trim($_POST['semester']);

    if (empty($std_name)) {
        $errors[] = "Name is required";
    }

    if (empty($std_roll)) {
        $errors[] = "Roll is required";
    }

    if (empty($std_department)) {
        $errors[] = "Department is required";
    }

    if (empty($std_semester)) {
        $errors[] = "Semester is required";
    }

    if (empty($errors)) {
        $check_query = "SELECT * FROM add_std WHERE student_name='$std_name'";
        $check_result = mysqli_query($data, $check_query);
        $row_count = mysqli_num_rows($check_result);

        if ($row_count > 0) {
            echo "<script type='text/javascript'>
                        alert('Student with this name already exists');
                      </script>";
        } else {
            $insert_query = "INSERT INTO add_std (student_name, roll, department, semester) VALUES('$std_name','$std_roll','$std_department','$std_semester')";
            $insert_result = mysqli_query($data, $insert_query);

            if ($insert_result) {
                echo "<script type='text/javascript'>
                        alert('Student added successfully');
                      </script>";
            } else {
                echo "Data Invalid";
            }
        }
    } else {
        foreach ($errors as $error) {
            echo "<script type='text/javascript'>
                    alert('{$error}');
                  </script>";
        }
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


    <style type="text/css">
        label {
            display: inline-block;
            text-align: right;
            width: 100px;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        

        .form-container {
            width: 400px;
            margin: 0 auto;
            padding-top: 70px;
            padding-bottom: 70px;
        }

        .form-group {
            margin-bottom: 15px;
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
            display: flex;
            align-items: center;
        }

        .admin-header h1:hover {
            color: #fff;
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
        

        .admin-links {
            display: flex;
            align-items: center;
        }

        .admin-links a {
            margin-left: 20px;
            text-decoration: none;
            color: #fff;
            font-size: 16px;
            padding: 8px 15px;
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








    <div class="form-container">
        <center>
            <h1>Add Student</h1>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="student_name" required>
                </div>
                <div class="form-group">
                    <label>Roll</label>
                    <input type="number" name="roll" required>
                </div>
                <div class="form-group">
                    <label>Department</label>
                    <input type="text" name="department" required>
                </div>
                <div class="form-group">
                    <label>Semester</label>
                    <select name="semester" required style="width: 200px;">
                        <option value="1st">1st</option>
                        <option value="2nd">2nd</option>
                        <option value="3rd">3rd</option>
                        <option value="4th">4th</option>
                        <option value="5th">5th</option>
                        <option value="6th">6th</option>
                        <option value="7th">7th</option>
                        <option value="8th">8th</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" name="add_student" value="Add Student">
                </div>
            </form>
        </center>
    </div>
</body>

</html>
