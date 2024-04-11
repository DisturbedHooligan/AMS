<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "user_db";

$connection = mysqli_connect($host, $user, $password, $db);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$allSemesters = array("1st", "2nd", "3rd", "4th", "5th", "6th", "7th", "8th");

$students = [];
$attendanceData = [];
$selectedSemester = isset($_POST['semester']) ? $_POST['semester'] : (isset($_SESSION['selectedSemester']) ? $_SESSION['selectedSemester'] : 'default');

if ($selectedSemester !== 'default') {
    $query = "SELECT * FROM add_std WHERE semester = '$selectedSemester'";
    $result = mysqli_query($connection, $query);
    $students = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $selectedDate = date("Y-m-d");
    $attendanceQuery = "SELECT student_id, status FROM attendance WHERE attendance_date = '$selectedDate' AND semester = '$selectedSemester'";
    $attendanceResult = mysqli_query($connection, $attendanceQuery);

    while ($row = mysqli_fetch_assoc($attendanceResult)) {
        $attendanceData[$row['student_id']] = $row['status'];
    }
}

$attendanceSubmitted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitAttendance'])) {
    foreach ($students as $student) {
        $studentID = $student['student_id'];
        $attendanceStatus = '';
        
        if (isset($_POST['attendance'][$studentID])) {
            $attendanceStatus = $_POST['attendance'][$studentID];
        }

        $checkQuery = "SELECT * FROM attendance WHERE student_id = '$studentID' AND semester = '$selectedSemester' AND attendance_date = '$selectedDate'";
        $checkResult = mysqli_query($connection, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            $updateQuery = "UPDATE attendance SET status = '$attendanceStatus' WHERE student_id = '$studentID' AND semester = '$selectedSemester' AND attendance_date = '$selectedDate'";
            mysqli_query($connection, $updateQuery);
        } else {
            $insertQuery = "INSERT INTO attendance (student_id, semester, attendance_date, status) VALUES ('$studentID', '$selectedSemester', '$selectedDate', '$attendanceStatus')";
            mysqli_query($connection, $insertQuery);
        }
    }

    $attendanceSubmitted = true;
}

$_SESSION['selectedSemester'] = $selectedSemester;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Page</title>
    <link rel="stylesheet" href="attendance_tchstyles.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            max-width: 500px; 
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="student-header">
        <h1 onclick="location.href='teacher_page.php';">Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
        <div class="student-links">
            <a href="attendance_tch.php?semester=default">Attendance</a>
            <a href="view_std_from_tch_panel.php">Students</a>
            <button class="logout-btn" onclick="location.href='logout.php';">Logout</button>
        </div>
    </div>

    <div class="content">
        <form method="post">
            <label for="semester">Select Semester:</label>
            <select name="semester" id="semester">
                <option value="default" <?php echo ($selectedSemester === 'default') ? 'selected' : ''; ?>>---None---</option>
                <?php foreach ($allSemesters as $semesterOption) : ?>
                    <option value="<?php echo $semesterOption; ?>" <?php echo ($selectedSemester === $semesterOption) ? 'selected' : ''; ?>><?php echo $semesterOption; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="filterButton">Filter</button>
            <?php if ($selectedSemester !== 'default') : ?>
                <?php if ($students) : ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Present</th>
                                <th>Absent</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student) : ?>
                                <tr>
                                    <td><?php echo $student['student_name']; ?></td>
                                    <td>
                                        <input type="radio" name="attendance[<?php echo $student['student_id']; ?>]" value="present" id="present_<?php echo $student['student_id']; ?>" <?php echo isset($attendanceData[$student['student_id']]) && $attendanceData[$student['student_id']] === 'present' ? 'checked' : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="radio" name="attendance[<?php echo $student['student_id']; ?>]" value="absent" id="absent_<?php echo $student['student_id']; ?>" <?php echo isset($attendanceData[$student['student_id']]) && $attendanceData[$student['student_id']] === 'absent' ? 'checked' : ''; ?>>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button type="submit" name="submitAttendance">Submit Attendance</button>
                <?php else : ?>
                    <p>No data found for the selected semester.</p>
                <?php endif; ?>
            <?php endif; ?>
        </form>
        <button class="goback-button" onclick="location.href='teacher_page.php';">Go Back</button>

    </div>
    

    <?php if ($attendanceSubmitted) : ?>
        <div id="successModal" class="modal">
            <div class="modal-content">
                <p>Attendance submitted successfully!</p>
                <button id="reloadBtn" class="reload-btn">Reload</button>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var modal = document.getElementById('successModal');
                var reloadBtn = document.getElementById('reloadBtn');
                
                modal.style.display = 'flex';

                reloadBtn.onclick = function () {
                    var currentSemester = document.getElementById('semester').value;
                    location.href = 'attendance_tch.php?semester=' + currentSemester;
                };

                window.onclick = function(event) {
                    if (event.target == modal) {
                        return false;
                    }
                }
            });
        </script>
    <?php endif; ?>
</body>

</html>