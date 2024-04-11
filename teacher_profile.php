<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "user_db";

$data = mysqli_connect($host, $user, $password, $db);

if (!$data) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

if (!isset($_SESSION['user_name'])) {
    header('location:index.php');
    exit();
}

$errors = array();
$updateSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $newName = $_POST['new_name'];
        $newEmail = $_POST['new_email'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword === $confirmPassword) {
            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];

                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $updateQuery = "UPDATE user_form SET name = '$newName', email = '$newEmail', password = '$hashedPassword' WHERE id = $userId AND user_type = 'Teacher'";
                mysqli_query($data, $updateQuery);

                if (mysqli_affected_rows($data) > 0) {
                    $_SESSION['name'] = $newName;
                    $_SESSION['email'] = $newEmail;
                    $updateSuccess = true;
                } else {
                    $errors[] = "Update failed. Please check your input and try again.";
                }
            } else {
                $errors[] = "User ID is not set in the session.";
            }
        } else {
            $errors[] = "Passwords do not match. Please enter matching passwords.";
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
    <title>Teacher Profile</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .content {
            width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .main-content {
            text-align: center;
        }

        .profile-form {
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        #successMessage {
            display: <?php echo $updateSuccess ? 'block' : 'none'; ?>;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 20px;
            border-radius: 5px;
            z-index: 1000;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            width: 400px;
            height: 100px;
        }

        #successMessage p {
            margin: 0 0 15px;
        }

        #successMessage a {
            display: inline-block;
            background-color: #45a049;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        #successMessage a:hover {
            background-color: #398140;
        }

        #successMessage a:not(:last-child) {
            pointer-events: none;
        }

        .content:not(#successMessage) input,
        .content:not(#successMessage) button {
            pointer-events: auto;
        }

        .back-arrow {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 20px;
            cursor: pointer;
            color: #333;
        }
    </style>

</head>

<body>
    <div class="content">
        <span class="back-arrow" onclick="history.back()">&larr;</span>
        <section class="main-content">
            <?php if ($updateSuccess): ?>
                <div id="successMessage">
                    <p style="color: green;">Changes were made successfully.</p>
                    <p><a href="logout.php" style="color: white;">Logout</a></p>
                </div>
            <?php else: ?>
                <h2>Teacher Profile</h2>
                <div class="profile-form">
                    <form method="post" action="">
                        <label for="new_name">New Name:</label>
                        <input type="text" id="new_name" name="new_name" value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>" required>

                        <label for="new_email">New Email:</label>
                        <input type="email" id="new_email" name="new_email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" required>

                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" required>

                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>

                        <button type="submit" name="update_profile">Update Profile</button>
                    </form>

                    <?php if (!empty($errors)): ?>
                        <div class="error-message">
                            <?php foreach ($errors as $error): ?>
                                <p><?php echo $error; ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>

</html>
