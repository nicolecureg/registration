<?php
include("config.php");

$coursecode = "";
$coursedes = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_GET["coursecode"])) {
        header("Location: course.php");
        exit;
    }

    $coursecode = $_GET["coursecode"];
    $sql = "SELECT * FROM xy WHERE coursecode='$coursecode'";
    $result = $conn->query($sql);

    if (!$result) {
        $errorMessage = "Invalid query: " . $conn->error;
    } else {
        $row = $result->fetch_assoc();

        if (!$row) {
            $errorMessage = "Course not found";
        } else {
            $coursecode = $row["coursecode"];
            $coursedes = $row["coursedes"];
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_coursecode = $_POST["old_coursecode"];
    $new_coursecode = $_POST["new_coursecode"];
    $coursedes = $_POST["coursedes"];

    if (empty($new_coursecode) || empty($coursedes)) {
        $errorMessage = "All fields are required";
    } else {
        $sql = "UPDATE xy SET coursecode='$new_coursecode', coursedes='$coursedes' WHERE coursecode='$old_coursecode'";
        $result = $conn->query($sql);

        if (!$result) {
            $errorMessage = "Update failed: " . $conn->error;
        } else {
            $successMessage = "Course information updated successfully";
            // Redirect to course.php after successful update
            header("Location: course.php");
            exit;
        }
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
    <title>EDIT COURSE</title>
</head>
<body>
<?php include("homepage.php"); ?>
<div class="container my-5">
    <div class="box form-box">
        <h2>EDIT COURSE</h2>

        <?php if (!empty($errorMessage)): ?>
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong><?php echo $errorMessage; ?></strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="old_coursecode" value="<?php echo htmlspecialchars($coursecode); ?>">

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Course Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="new_coursecode" id="new_coursecode" value="<?php echo htmlspecialchars($coursecode); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Course Description</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="coursedes" id="coursedes" value="<?php echo htmlspecialchars($coursedes); ?>">
                </div>
            </div>

            <?php if (!empty($successMessage)): ?>
                <div class='row mb-3'>
                    <div class='offset-sm-3 col-sm-6'>
                        <div class='alert alert-success alert-dismissible fade show' role='alert'>
                            <strong><?php echo $successMessage; ?></strong>
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

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
</body>
</html>