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

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action_accept'])) {
        $action = 'accept';
        $userId = $_POST['id'];
        $status = 'approved';
        $updateQuery = "UPDATE user_form SET status = '$status' WHERE id = $userId";
        mysqli_query($conn, $updateQuery);
    } elseif (isset($_POST['action_reject'])) {
        $action = 'reject';
        $userId = $_POST['id'];
        $status = 'rejected';
        $updateQuery = "UPDATE user_form SET status = '$status' WHERE id = $userId";
        mysqli_query($conn, $updateQuery);
    }
}

$query = "SELECT * FROM user_form WHERE status = 'pending'";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Pending Registrations</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
        }

        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            margin: 0 auto;
            max-width: 800px;
        }

        .header {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
            cursor: pointer;
        }

        .header h1:hover {
            color: #fff;
        }

        .header-links {
            display: flex;
            align-items: center;
            text-align: center;
        }

        .header-links a {
            margin-left: 20px;
            text-decoration: none;
            color: #fff;
            font-size: 18px;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .header-links a:hover {
            background-color: #0056b3;
            color: #fff;
        }

        .Logout {
            margin-left: auto;
        }

        .btn {
            padding: 10px;
            background-color: #dc3545; 
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #c82333; 
        }

        .main-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 100%;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin-bottom: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        p {
            font-size: 18px;
            color: #555;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .action-button {
            padding: 8px;
            background-color: #28a745; 
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .action-button.reject {
            background-color: #dc3545; 
        }

        .action-button:hover {
            background-color: #218838; 
        }

        .action-button.reject:hover {
            background-color: #c82333; 
        }

        .go-back-btn {
            margin-top: 20px;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .go-back-btn:hover {
            background-color: #0056b3;
            color: #fff;
        }

        .view-approved-btn,
        .view-rejected-btn {
            margin-top: 20px;
            padding: 10px;
            background-color: #28a745; 
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .view-approved-btn:hover,
        .view-rejected-btn:hover {
            background-color: #218838; 
        }

        .view-rejected-btn {
            background-color: #dc3545; 
        }

        .view-rejected-btn:hover {
            background-color: #c82333; 
        } 
        .header a h1{
            color: #000;
            text-decoration:none; 

        }
        .header a h1:hover{
            color: #fff;
        background-color: #007bff;
        transition: color 0.3s ease, background-color 0.3s ease; 
   

        }
        .header a{
            color: #000; 
            text-decoration:none; 


        }
        .header a{
            color: #fff;
        background-color: #007bff; 
        transition: color 0.3s ease, background-color 0.3s ease;
   
        }
        .Logout a.btn {
    background-color: #dc3545; 
    color: #fff;
    transition: background-color 0.3s ease;
}

.Logout a.btn:hover {
    background-color: #cc0000; 
}



    </style>

</head>

<body>
    <div class="content">
    <div class="header">
    <div style="display: flex; align-items: center; cursor: pointer;">
        <a href="admin_profile.php">
            <i class="fa fa-user" style="margin-right: 10px;"></i>
        </a>
        <a href="admin_page.php">
            <h1>Welcome, <?php echo $_SESSION['admin_name']; ?>!</h1>
        </a>
    </div>
    <div class="header-links">
        <a href="view_student.php">View Students</a>
        <a href="view_teacher.php">View Teachers</a>
        <a href="view_pending_registrations.php">Pending Registrations</a>

        <div class="Logout">
                <a href="logout.php" class="btn btn-primary">Logout</a>
            </div>
            </div>
</div>

        <section class="main-content">
            <h2>View Pending Registrations</h2>

            <?php
            if (mysqli_num_rows($result) > 0) {
                echo '<ul>';
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<li>';
                    echo '<p>' . $row['name'] . ' - ' . $row['email'] . '</p>';
                    echo '<div class="action-buttons">';
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                    echo '<button type="submit" name="action_accept" class="action-button">Accept</button>';
                    echo '</form>';
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
                    echo '<button type="submit" name="action_reject" class="action-button reject">Reject</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No pending registrations.</p>';
            }
            ?>
        </section>

        <a href="admin_page.php" class="go-back-btn">Go Back</a>

        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <a href="view_approved_registrations.php" class="view-approved-btn">View Approved Registrations</a>

            <a href="view_rejected_registrations.php" class="view-rejected-btn">View Rejected Registrations</a>
        </div>
    </div>
</body>

</html>
