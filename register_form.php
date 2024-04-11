<?php
@include 'config.php';

if(isset($_POST['submit'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $user_type = $_POST['user_type'];

    $status = ($user_type == 'student') ? 'approved' : 'pending';

    if ($user_type === 'student') {
        $roll_number = mysqli_real_escape_string($conn, $_POST['roll_number']);
        $department = mysqli_real_escape_string($conn, $_POST['department']);
        $semester = mysqli_real_escape_string($conn, $_POST['semester']);
    }

    $select = "SELECT * FROM user_form WHERE name = ? AND email = ? AND status = 'pending' AND user_type = ?";
    $stmt = mysqli_prepare($conn, $select);
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $user_type);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0){
        $registrationError = 'You have already applied and your registration is pending approval.';
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $hashed_cpassword = password_hash($cpassword, PASSWORD_BCRYPT);

        $select = "SELECT * FROM user_form WHERE email = ?";
        $stmt = mysqli_prepare($conn, $select);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result) > 0){
            $registrationError = 'User already exists!';
        } else {
            if(!password_verify($cpassword, $hashed_cpassword)) {
                $registrationError = 'Passwords do not match!';
            } else {
                $insertUser = "INSERT INTO user_form(name, email, password, user_type, status) VALUES (?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $insertUser);
                mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $hashed_password, $user_type, $status);
                mysqli_stmt_execute($stmt);

                if ($user_type === 'student') {
                    $insertStudent = "INSERT INTO add_std(student_name, roll, department, semester) VALUES (?, ?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $insertStudent);
                    mysqli_stmt_bind_param($stmt, "ssss", $name, $roll_number, $department, $semester);
                    mysqli_stmt_execute($stmt);
                }

                if (in_array($user_type, ['admin', 'Teacher'])) {
                    $registrationMessage = 'Your registration is sent for approval. Please wait for administrator confirmation.';
                } else {
                    header('location:index.php');
                    exit();
                }
            }
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
    <title>Register Form</title>
    <link rel="stylesheet" href="register.css">

    <style>
        .error-msg,
        .success-msg {
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .error-msg {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .success-msg {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .student-fields {
            display: none;
        }
    </style>
</head>

<body>
    <div class="bubbles">
    </div>

    <div class="form-container">
        <form action="" method="post">
            <h3>Register Now</h3>
            <?php
            if (isset($registrationError)) {
                echo '<div class="error-msg">' . $registrationError . '</div>';
            } elseif (isset($registrationMessage)) {
                echo '<div class="success-msg">' . $registrationMessage . '</div>';
            }
            ?>
            <input type="text" name="name" required placeholder="Enter your name">
            <input type="email" name="email" required placeholder="Enter your email">
            <input type="password" name="password" required placeholder="Enter your password">
            <input type="password" name="cpassword" required placeholder="Confirm your password">
            <select name="user_type" id="user_type" onchange="toggleStudentFields()">
                <option value="Teacher">Teacher</option>
                <option value="admin">Admin</option>
                <option value="student">Student</option>
            </select>

            <div class="student-fields">
                <input type="text" name="roll_number" placeholder="Enter your roll number">
                <input type="text" name="department" placeholder="Enter your department">
                <select name="semester">
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

            <input type="submit" name="submit" value="Register Now" class="form-btn">
            <p>Already have an account? <a href="index.php">Login Now</a></p>
        </form>
    </div>

    <script>
        function toggleStudentFields() {
            var userType = document.getElementById('user_type').value;
            var studentFields = document.querySelector('.student-fields');
            
            if (userType === 'student') {
                studentFields.style.display = 'block';
            } else {
                studentFields.style.display = 'none';
            }
        }
    </script>
</body>

</html>

