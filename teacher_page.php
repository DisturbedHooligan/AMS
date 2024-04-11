<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Include Font Awesome CSS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style type="text/css">
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .teacher-header {
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

        .teacher-header h1 {
            font-size: 20px;
            margin: 0;
            cursor: pointer;
            display: flex;
            align-items: center; 
        }

        .teacher-header i {
            margin-right: 10px; 
            color: #fff;
        }

        .teacher-header h1:hover {
            color: #fff;
        }

        .teacher-header a {
            text-decoration: none; 
        }

        .teacher-links {
            display: flex;
            align-items: center;
        }

        .teacher-links a {
            margin-left: 20px;
            text-decoration: none;
            color: #fff;
            font-size: 16px;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .teacher-links a:hover {
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
            margin-top: 20px; 
            padding: 20px;
            background-color: #fff;
            width: 80%;
            max-width: 800px; 
            margin: 0 auto; 
            box-sizing: border-box; 
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 0; 
        }
        table {
            border-collapse: collapse;
            width: 800px; 
            margin: 0 auto; 
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }
        .student-overview {
            text-align: center;
            margin-top: 20px;
        }

        .student-overview p {
            margin: 5px 0;
        }

        #studentPieChart {
            width: 400px;
            height: 400px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<?php
@include 'config.php';

session_start();

if (!isset($_SESSION['user_name'])) {
    header('location:index.php');
}

$servername = "localhost";
$username = "root";
$password = "";
$database = "user_db";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_total_students = "SELECT COUNT(*) AS total_students FROM add_std";
$result_total_students = mysqli_query($conn, $sql_total_students);

if (!$result_total_students) {
    die("Error: " . mysqli_error($conn));
}

$row_total_students = mysqli_fetch_assoc($result_total_students);
$total_students = $row_total_students['total_students'];

$sql_absent_students = "SELECT COUNT(*) AS total_absent FROM add_std WHERE status = 'absent'";
$result_absent_students = mysqli_query($conn, $sql_absent_students);

if (!$result_absent_students) {
    die("Error: " . mysqli_error($conn));
}

$row_absent_students = mysqli_fetch_assoc($result_absent_students);
$total_absent = $row_absent_students['total_absent'];

$total_present = $total_students - $total_absent;

$sql_students = "SELECT semester, COUNT(*) AS total_students FROM add_std GROUP BY semester";
$result_students = mysqli_query($conn, $sql_students);

if (!$result_students) {
    die("Error: " . mysqli_error($conn));
}

$student_data = [];
while ($row = mysqli_fetch_assoc($result_students)) {
    $semester = $row['semester'];
    $total_students = $row['total_students'];
    $student_data[$semester] = $total_students;
}

$sql = "SELECT name, email FROM user_form WHERE user_type = 'Teacher'";
$result = $conn->query($sql);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

$conn->close();
?>
<div class="teacher-header">
    <div style="display: flex; align-items: center; cursor: pointer;">
        <a href="teacher_profile.php">
            <i class="fa fa-user" style="margin-right: 10px;"></i>
        </a>
        <a href="teacher_page.php">
            <h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
        </a>
    </div>
    <div class="teacher-links">
        <a href="attendance_tch.php">Attendance</a>
        <a href="view_std_from_tch_panel.php">Students</a>
        <button class="logout-btn" onclick="location.href='logout.php';">Logout</button>
    </div>
</div>

<div class="content">
    <h1>Student Overview</h1>
    <canvas id="semesterChart"></canvas>
</div>
<div style="text-align: center;">
    <h2>Registered Teachers</h2>
    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["name"] . "</td><td>" . $row["email"] . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No teachers found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
    
</div>
<div class="student-overview">
    <h1>Student Overview</h1>
    <p>Total Present Students: <?php echo $total_present; ?></p>
    <p>Total Absent Students: <?php echo $total_absent; ?></p>
    <canvas id="studentPieChart"></canvas>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('semesterChart').getContext('2d');
        var pieCtx = document.getElementById('studentPieChart').getContext('2d');

        <?php
        $semesterData = json_encode(array_keys($student_data));
        $studentCountData = json_encode(array_values($student_data));
        ?>

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        var semesterChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo $semesterData; ?>,
                datasets: [{
                    label: 'Total Students',
                    data: <?php echo $studentCountData; ?>,
                    borderColor: getRandomColor(), 
                    backgroundColor: 'rgba(0, 0, 0, 0)', 
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
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            var value = tooltipItem.yLabel;
                            return label + ': ' + value;
                        }
                    }
                }
            }
        });

        var studentPieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Total Present Students', 'Total Absent Students'],
                datasets: [{
                    label: 'Student Overview',
                    data: [<?php echo $total_present; ?>, <?php echo $total_absent; ?>],
                    backgroundColor: [
                        'transparent',
                        'transparent',
                    ],
                    borderColor: [
                        getRandomColor(), 
                        getRandomColor()  
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    });
</script>

</body>
</html>
