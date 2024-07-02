<?php
include("config.php");


$errorMessage = "";
$successMessage = "";

// Initialize variables
$studentnumber = isset($_SESSION['studentnumber']) ? $_SESSION['studentnumber'] : "";
$fname = isset($_SESSION['fname']) ? $_SESSION['fname'] : "";
$coursecode = isset($_SESSION['coursecode']) ? $_SESSION['coursecode'] : "";
$school = isset($_SESSION['school']) ? $_SESSION['school'] : "";
$selected_subjects = isset($_SESSION['selected_subjects']) ? $_SESSION['selected_subjects'] : [];

// Fetching courses data from the database
$coursesQuery = "SELECT * FROM xy";
$coursesResult = $conn->query($coursesQuery);
$courses = [];
if ($coursesResult->num_rows > 0) {
    while ($row = $coursesResult->fetch_assoc()) {
        $courses[] = $row;
    }
} else {
    $errorMessage .= " NO INFORMATION HAS BEEN RECORDED for courses.";
}

// Fetch all available subjects
$subjectsQuery = "SELECT * FROM astre";
$subjectsResult = $conn->query($subjectsQuery);
$subjects = [];
if ($subjectsResult) {
    while ($row = $subjectsResult->fetch_assoc()) {
        $subjects[] = $row;
    }
} else {
    $errorMessage .= "Error fetching subjects: " . $conn->error;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enroll'])) {
    $studentnumber = $_POST['studentnumber'];
    $coursecode = $_POST['coursecode'];
    $school = $_POST['school'];
    $selected_subjects = isset($_POST['subjects']) ? $_POST['subjects'] : [];

    // Fetch student information including active status
    $verifyQuery = "SELECT fname, active FROM users WHERE studentnumber = '$studentnumber'";
    $verifyResult = $conn->query($verifyQuery);

    if (!$verifyResult) {
        $errorMessage .= "Error executing query: " . $conn->error;
    } elseif ($verifyResult->num_rows > 0) {
        $row = $verifyResult->fetch_assoc();
        $fname = $row['fname'];
        $active = $row['active'];

        // Check if the student is active
        if ($active == 0) {
            $errorMessage .= "The student is not active. Enrollment is not allowed.";
        } else {
            // Convert selected subjects array to a comma-separated string
            $subjectsString = implode(",", $selected_subjects);

            // Insert the enrollment data into the database
            $insertQuery = "INSERT INTO enrollments (studentnumber, fname, coursecode, school, subjects) VALUES ('$studentnumber', '$fname', '$coursecode', '$school', '$subjectsString')";
            if ($conn->query($insertQuery) === TRUE) {
                // Store data in session
                $_SESSION['studentnumber'] = $studentnumber;
                $_SESSION['fname'] = $fname;
                $_SESSION['coursecode'] = $coursecode;
                $_SESSION['school'] = $school;
                $_SESSION['selected_subjects'] = $selected_subjects;

                // Redirect to assessment.php
                header("Location: assessment.php");
                exit();
            } else {
                $errorMessage .= "Error enrolling student: " . $conn->error;
            }
        }
    } else {
        $errorMessage .= "No record found for the provided Student Number.<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Enrollment Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include("homepage.php"); ?>
<div class="container my-5">
    <h2>Enrollment Form</h2>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><?php echo $errorMessage; ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong><?php echo $successMessage; ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form method="post" action="enrollment.php">
        <div class="mb-3">
            <label for="studentnumber" class="form-label">Student Number</label>
            <input type="text" class="form-control" id="studentnumber" name="studentnumber" value="<?php echo htmlspecialchars($studentnumber); ?>" required>
        </div>
        <div class="mb-3">
            <label for="fname" class="form-label">Name</label>
            <input type="text" class="form-control" id="fname" name="fname" value="<?php echo htmlspecialchars($fname); ?>" required readonly>
        </div>

        <div class="mb-3">
            <label for="coursecode" class="form-label">Course</label>
            <select class="form-select" id="coursecode" name="coursecode" required>
                <option value="">Select Course</option>
                <?php foreach ($courses as $course): ?>
                    <option value="<?php echo htmlspecialchars($course['coursecode']); ?>" <?php if ($coursecode == $course['coursecode']) echo 'selected="selected"'; ?>>
                        <?php echo htmlspecialchars($course['coursecode']); ?> (<?php echo htmlspecialchars($course['coursedes']); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="school" class="form-label">School Year</label>
            <input type="text" class="form-control" id="school" name="school" value="<?php echo htmlspecialchars($school); ?>" required>
        </div>

        <h2>Enrolled Subjects</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Description</th>
                    <th>Units</th>
                    <th>Prerequisite</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM astre";
                $result = $conn->query($sql);

                if (!$result) {
                    die("Invalid query: " . $conn->error);
                }
                while ($row = $result->fetch_assoc()) {
                    $isChecked = in_array($row['subjectcode'], $selected_subjects);
                    echo "
                    <tr>
                        <td>{$row['subjectcode']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['units']}</td>
                        <td>{$row['prere']}</td>
                        <td>
                            <div class='form-check'>
                                <input class='form-check-input' type='checkbox' name='subjects[]' value='{$row['subjectcode']}' " . ($isChecked ? "checked" : "") . ">
                            </div>
                        </td>
                    </tr>
                    ";
                }
                ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary" name="enroll">Submit</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('#studentnumber').on('input', function() {
            var studentnumber = $(this).val();
            if (studentnumber.length > 0) {
                $.ajax({
                    url: 'get_student_info.php',
                    type: 'GET',
                    data: { studentnumber: studentnumber },
                    success: function(data) {
                        var result = JSON.parse(data);
                        $('#fname').val(result.fname);
                        if (result.active === 0) {
                            alert('The student is not active. Enrollment is not allowed.');
                        }
                    },
                    error: function() {
                        console.error('An error occurred while fetching the student information.');
                    }
                });
            } else {
                $('#fname').val('');
            }
        });
    });
</script>
</body>
</html>