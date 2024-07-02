<?php
include("config.php");

$coursecode  = "";
$coursedes = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $coursecode  = isset($_POST["coursecode"]) ? $_POST["coursecode"] : "";
    $coursedes = isset($_POST["coursedes"]) ? $_POST["coursedes"] : "";

    if (empty($coursecode) || empty($coursedes)) {
        $errorMessage = "All information is required to be filled.";
    } else {
        // Check if the course code already exists
        $check_sql = "SELECT * FROM xy WHERE coursecode = '$coursecode'";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            header("Location: course.php");
            exit;
        } else {
            // Proceed with inserting the record
            $sql = "INSERT INTO xy (coursecode, coursedes) VALUES ('$coursecode', '$coursedes')";
            $result = $conn->query($sql);

            if (!$result) {
                $errorMessage = "Invalid query: " . $conn->error;
            } else {
                header("Location: course.php");
                exit;
            }
        }
    }
}

// Function to format course code
function formatCourseCode($coursecode) {
    return substr($coursecode, 0, 2) . '-' . substr($coursecode, 2);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>ADD COURSE</title>
</head>
<body>
<?php include("homepage.php"); ?>
    <div class="container my-5">
        <div class="box form-box">
            <h2>ADD COURSE</h2>

            <?php
            if (!empty($errorMessage)) {
                echo "
                <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    <strong>$errorMessage</strong>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick='redirectToCoursePage()'></button>
                </div>
                ";
            }
            ?>

            <form method="post">
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Course Name</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="coursecode" id="coursecode" value="<?php echo htmlspecialchars($coursecode); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Course Description</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="coursedes" id="coursedes" value="<?php echo htmlspecialchars($coursedes); ?>">
                    </div>
                </div>

                <?php
                if (!empty($successMessage)) {
                    echo "
                    <div class='row mb-3'>
                        <div class='offset-sm-3 col-sm-6'>
                            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>$successMessage</strong>
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick='redirectToCoursePage()'></button>
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
                        <a class="btn btn-outline-primary" href="course.php" role="button">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function redirectToStudentPage() {
            window.location.href = 'course.php';
        }
    </script>
</body>
</html>