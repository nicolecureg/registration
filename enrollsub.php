<?php
include("config.php");

$studentnumber = isset($_GET['studentnumber']) ? $_GET['studentnumber'] : "";
$fname = isset($_GET['fname']) ? $_GET['fname'] : "";
$coursecode = isset($_GET['coursecode']) ? $_GET['coursecode'] : "";
$school = isset($_GET['school']) ? $_GET['school'] : "";

$error_message = "";

// Fetch selected subjects for the student
$selected_subjects = [];
$selectedSubjectsQuery = "SELECT subjectcode FROM enrollments WHERE studentnumber = '$studentnumber'";
$selectedSubjectsResult = $conn->query($selectedSubjectsQuery);
if ($selectedSubjectsResult) {
    while ($row = $selectedSubjectsResult->fetch_assoc()) {
        $selected_subjects[] = $row['subjectcode'];
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
    <title>Subjects
    <title>Subjects</title>
</head>
<body>
<div class="container my-5">
    <h2>Subjects Information</h2>
    <br>
    <?php if (!empty($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><?php echo $error_message; ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Display All Subjects Info -->
    <form method="post" action="enrollment.php">
        <input type="hidden" name="studentnumber" value="<?php echo htmlspecialchars($studentnumber); ?>">
        <input type="hidden" name="fname" value="<?php echo htmlspecialchars($fname); ?>">
        <input type="hidden" name="coursecode" value="<?php echo htmlspecialchars($coursecode); ?>">
        <input type="hidden" name="school" value="<?php echo htmlspecialchars($school); ?>">

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
        <button type="submit" class="btn btn-primary btn-sm" name="enroll">Add Selected to Enrollment</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>