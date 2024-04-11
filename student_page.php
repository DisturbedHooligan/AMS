<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "user_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

if (!isset($_SESSION['user_name'])) {
    header('location:index.php');
    exit();
}

$student_name = $_SESSION['user_name'];
$sql_student_info = "SELECT * FROM add_std WHERE student_name = '$student_name'";
$result_student_info = $conn->query($sql_student_info);
$student_data = $result_student_info->fetch_assoc();

$sql_attendance = "SELECT status FROM attendance WHERE student_id = (SELECT student_id FROM add_std WHERE student_name = '$student_name')";
$result_attendance = $conn->query($sql_attendance);

$present_count = 0;
$absent_count = 0;

if ($result_attendance->num_rows > 0) {
    while ($row = $result_attendance->fetch_assoc()) {
        if ($row['status'] == 'present') {
            $present_count++;
        } elseif ($row['status'] == 'absent') {
            $absent_count++;
        }
    }
}

$semester = $student_data['semester'];
$sql_other_students = "SELECT student_name FROM add_std WHERE semester = '$semester' AND student_name != '$student_name'";
$result_other_students = $conn->query($sql_other_students);

$other_students = [];
if ($result_other_students->num_rows > 0) {
    while ($row = $result_other_students->fetch_assoc()) {
        $other_students[] = $row['student_name'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Include Font Awesome CSS -->
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .student-header {
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

        .student-header h1 {
            font-size: 20px;
            margin: 0;
            cursor: pointer;
            display: flex;
            align-items: center; 
            color: #fff; 
            text-decoration: none; 
        }

        .student-header h1:hover {
            color: #f0f0f0; 
        }

        .student-header i {
            margin-right: 5px; 
            color: #fff; 
        }
        .student-header a {
            text-decoration: none;
        }

        .student-links {
            display: flex;
            align-items: center;
        }

        .student-links a {
            margin-left: 20px;
            text-decoration: none;
            color: #fff;
            font-size: 16px;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .student-links a:hover {
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
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 80px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            margin: 0 0 20px 0;
        }

        .student-info {
            text-align: left;
            max-width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }

        .student-info p {
            margin: 0 0 10px 0;
        }

        .student-info p strong {
            font-weight: bold;
        }

        .chart-container {
            width: 400px;
            height: 200px;
            margin-bottom: 20px;
        }

        .other-students {
            max-width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            margin-bottom: 20px;
        }

        .other-students ul {
            list-style-type: none;
            padding: 0;
        }

        .other-students ul li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="student-header">
        <div style="display: flex; align-items: center; cursor: pointer;">
            <a href="student_profile.php">
                <i class="fa fa-user" style="margin-right: 10px;"></i>
            </a>
            <a href="student_page.php">
                <h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
            </a>
        </div>
        <div class="student-links">
            <a href="Attendance.php">Attendance</a>
            <a href="teachers.php">Teachers</a>
            <button class="logout-btn" onclick="location.href='logout.php';">Logout</button>
        </div>
    </div>

    <div class="content">
        <h1>Student Information</h1>
        <div class="student-info">
            <p><strong>Name:</strong> <?php echo $_SESSION['user_name']; ?></p>
            <?php
            if ($student_data) {
                echo "<p><strong>Department:</strong> {$student_data['department']}</p>";
                echo "<p><strong>Semester:</strong> {$student_data['semester']}</p>";
                echo "<p><strong>Roll:</strong> {$student_data['roll']}</p>";
            } else {
                echo "<p>No information available.</p>";
            }
            ?>
        </div>
        <h1>Attendance Status</h1>
        <div class="chart-container">
            <canvas id="attendanceChart"></canvas>
        </div>

        <h1>Other Students in the Same Semester</h1>
        <div class="other-students">
            <ul>
                <?php foreach ($other_students as $student) : ?>
                    <li><?php echo $student; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('attendanceChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Present', 'Absent'],
                datasets: [{
                    label: 'Attendance Status',
                    data: [<?php echo $present_count; ?>, <?php echo $absent_count; ?>],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)', 
                        'rgba(255, 99, 132, 0.2)'  
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',   
                        'rgba(255, 99, 132, 1)'   
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</body>
</html>

