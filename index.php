<?php
@include 'config.php';

session_start();

if(isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $entered_password = $_POST['password'];

    $select = "SELECT * FROM user_form WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if ($row = mysqli_fetch_assoc($result)) {
        $stored_password = $row['password'];

        if (password_verify($entered_password, $stored_password)) {

            if ($row['status'] == 'approved') {
                if ($row['user_type'] == 'admin') {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['admin_name'] = $row['name'];
                    header('location: admin_page.php');
                    exit();
                } elseif ($row['user_type'] == 'Teacher') {
                    $_SESSION['user_id'] = $row['id']; 
                    $_SESSION['user_name'] = $row['name'];
                    header('location: teacher_page.php');
                    exit();
                } elseif ($row['user_type'] == 'student') {
                    $_SESSION['user_id'] = $row['id']; 
                    $_SESSION['user_name'] = $row['name'];
                    header('location: student_page.php');
                    exit();
                }
            } else {
                $error[] = 'Your registration is pending or rejected. Please contact the administrator.';
            }
        } else {
            $error[] = 'Incorrect email or password!';
        }
    } else {
        $error[] = 'Incorrect email or password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="form-container">
        <form action="" method="post">
            <h3>Login Now</h3>
            <?php
            if (isset($error)) {
                foreach ($error as $error) {
                    echo '<span class="error-msg">' . $error . '</span>';
                };
            };
            ?>
            <input type="email" name="email" required placeholder="Enter your email">
            <input type="password" name="password" required placeholder="Enter your password">
            <input type="submit" name="submit" value="Login Now" class="form-btn">
            <p>Don't have an account? <a href="register_form.php">Register Now</a></p>
        </form>
    </div>
</body>

</html>
