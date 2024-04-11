<?php
@include 'config.php';

session_start();

if (!isset($_SESSION['admin_name'])) {
    header('location:index.php');
}

$sqlSemester = "SELECT semester, COUNT(*) as total_students FROM add_std GROUP BY semester";
$resultSemester = mysqli_query($conn, $sqlSemester);

$semesters = [];
$totalStudentsBySemester = [];

while ($rowSemester = mysqli_fetch_assoc($resultSemester)) {
    $semesters[] = "Semester " . $rowSemester['semester'];
    $totalStudentsBySemester[] = $rowSemester['total_students'];
}

$sqlDepartment = "SELECT department, COUNT(*) as total_students FROM add_std GROUP BY department";
$resultDepartment = mysqli_query($conn, $sqlDepartment);

$departments = [];
$totalStudentsByDepartment = [];

while ($rowDepartment = mysqli_fetch_assoc($resultDepartment)) {
    $departments[] = $rowDepartment['department'];
    $totalStudentsByDepartment[] = $rowDepartment['total_students'];
}

$sqlAttendance = "SELECT status, COUNT(*) as total_students FROM add_std GROUP BY status";
$resultAttendance = mysqli_query($conn, $sqlAttendance);

$attendanceLabels = [];
$totalStudentsByAttendance = [];
$presentStudents = 0;
$absentStudents = 0;

while ($rowAttendance = mysqli_fetch_assoc($resultAttendance)) {
    $attendanceLabels[] = ucfirst($rowAttendance['status']);
    $totalStudentsByAttendance[] = $rowAttendance['total_students'];

    if ($rowAttendance['status'] == 'present') {
        $presentStudents += $rowAttendance['total_students'];
    } elseif ($rowAttendance['status'] == 'absent') {
        $absentStudents += $rowAttendance['total_students'];
    }
}

$totalStudents = array_sum($totalStudentsBySemester);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            overflow-x: hidden;
        }

        .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0 auto;
        }

        .header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .header h1 {
            font-size: 20px;
            margin: 0;
            cursor: pointer;
            display: flex;
            align-items: center; 
        }

        .header h1 i {
            margin-right: 10px; 
        }

        .header h1:hover {
            color: #fff;
        }

        .header-links {
            display: flex;
            align-items: center;
        }

        .header-links a {
            margin-left: 20px;
            text-decoration: none;
            color: #fff;
            font-size: 16px;
            padding: 8px 12px;
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
            padding: 8px 12px;
            background-color: #dc3545; 
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #c82333; 
        }

        .main-content,
        .sidebar {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            width: 100%;
        }

        .sidebar {
            background-color: #333;
            color: #fff;
            padding: 10px;
            border-radius: 8px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar li {
            margin-bottom: 5px;
        }

        .main-content h2 {
            text-align: center;
        }
        .chart-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .chart-container canvas {
        margin: 10px; 
    }

    .doughnut-container {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
    }
    .doughnut-container canvas {
        max-width: 400px; 
        margin: 10px; 
    }

        .chart-container {
            max-width: 700px; 
            margin: 0 auto;
            margin-bottom: 50px;
        }
        .total-students-box {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px; 
    text-align: center;
}

.total-students-box h3 {
    color: #007bff;
    margin-bottom: 10px;
}

.total-students-box p {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin: 0;
}
.total-students-box {
    display: flex;
    justify-content: space-around;
    align-items: center;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    text-align: center;
}

.total-students-box div {
    flex: 1;
    margin: 0 10px;
}

.total-students-box h3 {
    color: #007bff;
    margin-bottom: 10px;
}

.total-students-box p {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin: 0;
}



    </style>
</head>

<body>
    <div class="content">
        <header class="header">
            <h1><i class="fa fa-user" onclick="location.href='admin_profile.php';"></i>Welcome, <?php echo $_SESSION['admin_name']; ?>!</h1>
            <div class="header-links">
                <a href="view_student.php">View Students</a>
                <a href="view_teacher.php">View Teachers</a>
                <a href="attendance_admin.php">Attendance</a>
                <a href="view_pending_registrations.php">Pending Registrations</a>
            </div>
            <div class="Logout">
                <a href="logout.php" class="btn btn-primary">Logout</a>
            </div>
        </header>

        <section class="main-content">
            <h2>Admin Dashboard</h2>

            <div class="chart-container">
                <canvas id="semesterTotalChart" width="300" height="150"></canvas>

                <div class="doughnut-container">
                    <canvas id="totalStudentsDepartmentChart" width="300" height="250"></canvas>

                    <canvas id="attendanceStatusChart" width="300" height="250"></canvas>
                </div>
            </div>
<div class="total-students-box">
    <div>
        <h3>Total Number of Students</h3>
        <p><?php echo $totalStudents; ?></p>
    </div>
    <div>
        <h3>Present Students</h3>
        <p><?php echo $presentStudents; ?></p>
    </div>
    <div>
        <h3>Absent Students</h3>
        <p><?php echo $absentStudents; ?></p>
    </div>
</div>


        </section>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var initialBarChartData = {
                labels: <?php echo json_encode($semesters); ?>,
                datasets: [{
                    label: 'Total Students',
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    data: <?php echo json_encode($totalStudentsBySemester); ?>,
                }]
            };

            var barChartOptions = {
                scales: {
                    x: {
                        type: 'category',
                        labels: <?php echo json_encode($semesters); ?>
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            };

            var ctx = document.getElementById('semesterTotalChart').getContext('2d');
            var semesterTotalChart = new Chart(ctx, {
                type: 'bar',
                data: initialBarChartData,
                options: barChartOptions
            });

            var initialDoughnutChartData = {
                labels: <?php echo json_encode($departments); ?>,
                datasets: [{
                    backgroundColor: generateRandomColors(<?php echo count($departments); ?>),
                    borderColor: 'transparent',
                    borderWidth: 2,
                    data: <?php echo json_encode($totalStudentsByDepartment); ?>,
                }]
            };

            var doughnutCtx = document.getElementById('totalStudentsDepartmentChart').getContext('2d');
            var totalStudentsDepartmentDoughnutChart = new Chart(doughnutCtx, {
                type: 'doughnut',
                data: initialDoughnutChartData,
                options: {
                    cutout: '70%',
                    radius: '100%',
                    maintainAspectRatio: false,
                    responsive: false,
                }
            });

            var initialAttendanceChartData = {
                labels: <?php echo json_encode($attendanceLabels); ?>,
                datasets: [{
                    backgroundColor: generateRandomColors(<?php echo count($attendanceLabels); ?>),
                    borderColor: 'transparent',
                    borderWidth: 2,
                    data: <?php echo json_encode($totalStudentsByAttendance); ?>,
                }]
            };

            var attendanceStatusDoughnutCtx = document.getElementById('attendanceStatusChart').getContext('2d');
            var attendanceStatusDoughnutChart = new Chart(attendanceStatusDoughnutCtx, {
                type: 'doughnut',
                data: initialAttendanceChartData,
                options: {
                    cutout: '70%',
                    radius: '100%',
                    maintainAspectRatio: false,
                    responsive: false,
                }
            });
        });

        function generateRandomColors(numColors) {
            var colors = [];
            for (var i = 0; i < numColors; i++) {
                colors.push(getRandomColor());
            }
            return colors;
        }

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>

</body>
</html>
