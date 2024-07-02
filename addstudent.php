<?php
include("config.php");

$studentnumber = "";
$ffname = "";
$mname = "";
$lname = "";
$gender = "";
$address = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentnumber = isset($_POST["studentnumber"]) ? $_POST["studentnumber"] : "";
    $ffname = isset($_POST["ffname"]) ? $_POST["ffname"] : "";
    $mname = isset($_POST["mname"]) ? $_POST["mname"] : "";
    $lname = isset($_POST["lname"]) ? $_POST["lname"] : "";
    $gender = isset($_POST["gender"]) ? $_POST["gender"] : "";
    $address = isset($_POST["address"]) ? $_POST["address"] : "";

    if (empty($studentnumber) || empty($ffname)||  empty($mname)||  empty($lname)|| empty($gender) || empty($address)) {
        $errorMessage = "All information is required to be filled.";
    } elseif (!is_numeric($studentnumber) || strlen($studentnumber) != 7) {
        $errorMessage = "Student number must be exactly 7 digits long.";
    } else {
        $studentnumber = (int)$studentnumber;
        // Check if the student number already exists
        $check_sql = "SELECT * FROM users WHERE studentnumber = '$studentnumber'";
        $check_result = $conn->query($check_sql);

                // Update user table
                $fullname = trim("$ffname $mname $lname");
                $sql = "UPDATE users SET studentnumber='$studentnumber', ffname='$ffname', mname='$mname', lname='$lname', gender='$gender', address='$address' WHERE studentnumber='$studentnumber'";
                $result = $conn->query($sql);

        if ($check_result->num_rows > 0) {
            $errorMessage = "Student number $studentnumber has already been recorded.";
        } else {
            // Proceed with inserting the record
            $user_sql = "INSERT INTO users (studentnumber, ffname,mname,lname, gender, address) VALUES ('$studentnumber', '$ffname','$mname','$lname', '$gender', '$address')";


            $user_result = $conn->query($user_sql);
            header("Location: student.php");
            exit;
    
        }
    }
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
    <title>Add Student</title>
</head>
<body>
<?php include("homepage.php"); ?>
    <div class="container my-5">
        <div class="box form-box">
            <h2>ADD STUDENT</h2>

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
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">STUDENT NUMBER</label>
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
                    <label class="col-sm-3 col-form-label">GENDER</label>
                    <div class="col-sm-6">
                        <select class="form-control" name="gender" id="gender">
                            <option value="" <?php echo empty($gender) ? 'selected' : ''; ?>>Select Gender</option>
                            <option value="Male" <?php echo $gender == 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $gender == 'Female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">ADDRESS</label>
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
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick='redirectToStudentPage()'></button>
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
    <script>
        function redirectToStudentPage() {
            window.location.href = 'student.php';
        }
    </script>
</body>
</html>