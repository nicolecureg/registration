<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <title>Homepage</title>
</head>
<body>
    <div class="navbar">
        <ul class="left-items">
            <li class="dropdown">
                <a href="#"><i class="fa-solid fa-info-circle"></i> Setup</a>
                <div class="dropdown-content">
                    <a href="course.php">Course</a>
                    <a href="subject.php">Subjects</a>
                    <a href="student.php">Students</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="#"><i class="fa-solid fa-book"></i> Transaction</a>
                <div class="dropdown-content">
                    <a href="enrollment.php">Enrollment</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="#"><i class="fa-solid fa-chart-bar"></i> Reports</a>
                <div class="dropdown-content">
                    <a href="print.php">Assessment Form</a>
                </div>
            </li>
            <li class="dropdown">
                <a href="about.php"><i class="fa-solid fa-user"></i> About</a>
            </li>
        </ul>
        
        <ul class="right-items">
            <li class="user">
                <a href="logout.php"><i class="fa-solid fa-user"></i> Logout</a>
            </li>
        </ul>
    </div>
</body>
</html>