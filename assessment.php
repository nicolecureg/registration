<?php
include("config.php");

$errorMessage = "";
$successMessage = "";

$studentnumber = isset($_SESSION['studentnumber']) ? $_SESSION['studentnumber'] : "";
$fname = isset($_SESSION['fname']) ? $_SESSION['fname'] : "";
$coursecode = isset($_SESSION['coursecode']) ? $_SESSION['coursecode'] : "";
$school = isset($_SESSION['school']) ? $_SESSION['school'] : "";
$selected_subjects = isset($_SESSION['selected_subjects']) ? $_SESSION['selected_subjects'] : [];

// Fetching courses data from the database (if needed)
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

// Fetch all available subjects (if needed)
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

// Calculate total units of selected subjects
$totalUnits = 0;
foreach ($selected_subjects as $subjectCode) {
    // Query to fetch units for each subject
    $unitQuery = "SELECT units FROM astre WHERE subjectcode = '$subjectCode'";
    $unitResult = $conn->query($unitQuery);
    if ($unitResult->num_rows > 0) {
        $row = $unitResult->fetch_assoc();
        $totalUnits += $row['units'];
    }
}

// Output HTML for assessment details
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Assessment</title>
</head>
<body>
<?php include("homepage.php"); ?>
    <div class="container my-5">
        <h2>Assessment Details</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger" role="alert">
                <strong><?php echo $errorMessage; ?></strong>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="studentnumber" class="form-label">Student Number</label>
            <input type="text" class="form-control" id="studentnumber" value="<?php echo htmlspecialchars($studentnumber); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="fname" class="form-label">Name</label>
            <input type="text" class="form-control" id="fname" value="<?php echo htmlspecialchars($fname); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="coursecode" class="form-label">Course</label>
            <input type="text" class="form-control" id="coursecode" value="<?php echo htmlspecialchars($coursecode); ?>" readonly>
        </div>
        <div class="mb-3">
            <label for="school" class="form-label">School Year</label>
            <input type="text" class="form-control" id="school" value="<?php echo htmlspecialchars($school); ?>" readonly>
        </div>


        <table class="table">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Description</th>
                    <th>Units</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($selected_subjects as $subjectCode): ?>
                    <?php
                    // Query to fetch subject details
                    $subjectQuery = "SELECT * FROM astre WHERE subjectcode = '$subjectCode'";
                    $subjectResult = $conn->query($subjectQuery);
                    if ($subjectResult->num_rows > 0) {
                        $row = $subjectResult->fetch_assoc();
                        echo "
                        <tr>
                            <td>{$row['subjectcode']}</td>
                            <td>{$row['description']}</td>
                            <td>{$row['units']}</td>
                        </tr>
                        ";
                    }
                    ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-end"><strong>Total Units:</strong></td>
                    <td><strong><?php echo $totalUnits; ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>