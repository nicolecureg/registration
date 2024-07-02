<?php
include("config.php");

$studentnumber = "";
$ffname = "";
$mname = "";
$lname = "";
$gender = "";
$address = "";
$original_studentnumber = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["studentnumber"])) {
        header("Location: student.php");
        exit;
    }

    $original_studentnumber = $_GET["studentnumber"];

    $sql = "SELECT * FROM users WHERE studentnumber='$original_studentnumber'";
    $result = $conn->query($sql);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: student.php");
        exit;
    }

    $studentnumber = $row["studentnumber"];
    $ffname = $row["ffname"];
    $mname = $row["mname"];
    $lname = $row["lname"];
    $gender = $row["gender"];
    $address = $row["address"];
} else {
    $original_studentnumber = $_POST["original_studentnumber"];
    $studentnumber = $_POST["studentnumber"];
    $ffname = $_POST["ffname"];
    $mname = $_POST["mname"];
    $lname = $_POST["lname"];
    $gender = $_POST["gender"];
    $address = $_POST["address"];

    do {
        if (empty($studentnumber) || empty($ffname) || empty($mname) || empty($lname) || empty($gender) || empty($address)) {
            $errorMessage = "All fields are required";
            break;
        }

        if (!is_numeric($studentnumber) || strlen($studentnumber) != 7) {
            $errorMessage = "Student number must be exactly 7 digits long.";
            break;
        }

        $studentnumber = (int)$studentnumber;

        // Check if the student number already exists (excluding the current student)
        $check_sql = "SELECT * FROM users WHERE studentnumber='$studentnumber' AND studentnumber != '$original_studentnumber'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $errorMessage = "Student number $studentnumber is already in use.";
            break;
        }

        // Update user table
        $fullname = trim("$ffname $mname $lname");
        $sql = "UPDATE users SET studentnumber='$studentnumber', ffname='$ffname', mname='$mname', lname='$lname', fname='$fname', gender='$gender', address='$address' WHERE studentnumber='$original_studentnumber'";
        $result = $conn->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $conn->error;
            break;
        }

        // Update enrollments table with the new student number
        $enroll_sql = "UPDATE enrollments SET studentnumber='$studentnumber' WHERE studentnumber='$original_studentnumber'";
        $enroll_result = $conn->query($enroll_sql);

        if (!$enroll_result) {
            $errorMessage = "Error updating enrollments table: " . $conn->error;
            break;
        }

        $successMessage = "Information has been updated successfully";
        header("Location: student.php");
        exit;
    } while (false);
}

// Function to format student number
function formatStudentNumber($studentnumber) {
    return substr($studentnumber, 0, 2) . '-' . substr($studentnumber, 2);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Edit Student</title>
</head>
<body>
<?php include("homepage.php"); ?>
<div class="container my-5">
    <div class="box form-box">
        <h2>EDIT STUDENT</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>

        <form method="post">
            <input type="hidden" name="original_studentnumber" value="<?php echo htmlspecialchars($original_studentnumber); ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Student Number</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="studentnumber" id="studentnumber" value="<?php echo htmlspecialchars($studentnumber); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">First Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="ffname" id="ffname" value="<?php echo htmlspecialchars($ffname); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Middle Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="mname" id="mname" value="<?php echo htmlspecialchars($mname); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Last Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="lname" id="lname" value="<?php echo htmlspecialchars($lname); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Gender</label>
                <div class="col-sm-6">
                    <select class="form-control" name="gender" id="gender">
                        <option value="" <?php echo empty($gender) ? 'selected' : ''; ?>>Select Gender</option>
                        <option value="Male" <?php echo $gender == 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $gender == 'Female' ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Address</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="address" id="address" value="<?php echo htmlspecialchars($address); ?>">
                </div>
            </div>

            <?php
            if (!empty($successMessage)) {
                echo "
                <div class='row mb-3'>
                    <div class='offset-sm-3 col-sm-6'>
                        <div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong>$successMessage</strong>
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                    </div>
                </div>
                ";
            }
            ?>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="student.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>