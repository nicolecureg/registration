<?php
include("config.php");

$subjectcode = "";
$description = "";
$units = "";
$prere = "";
$original_subjectcode = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!isset($_GET["subjectcode"])) {
        header("Location: subject.php");
        exit;
    }

    $original_subjectcode = $_GET["subjectcode"];

    $sql = "SELECT * FROM astre WHERE subjectcode='$original_subjectcode'";
    $result = $conn->query($sql);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: subject.php");
        exit;
    }

    $subjectcode = $row["subjectcode"];
    $description = $row["description"];
    $units = $row["units"];
    $prere = $row["prere"];
} else {
    $original_subjectcode = $_POST["original_subjectcode"];
    $subjectcode = $_POST["subjectcode"];
    $description = $_POST["description"];
    $units = $_POST["units"];
    $prere = $_POST["prere"];

    do {
        if (empty($subjectcode) || empty($description) || empty($units) || empty($prere)) {
            $errorMessage = "All the fields are required";
            break;
        }

        $sql = "UPDATE astre SET subjectcode='$subjectcode', description='$description', units='$units', prere='$prere' WHERE subjectcode='$original_subjectcode'";
        $result = $conn->query($sql);

        if (!$result) {
            $errorMessage = "Invalid query: " . $conn->error;
            break;
        }

        $successMessage = "Subject has been updated successfully";
        header("Location: subject.php");
        exit;
    } while (false);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Edit Subject</title>

</head>
<body>
<?php include("homepage.php"); ?>
    <div class="container my-5">
        <div class="box form-box">
            <h2>EDIT SUBJECT</h2>

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
                <input type="hidden" name="original_subjectcode" value="<?php echo htmlspecialchars($original_subjectcode); ?>">
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Subject Code</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="subjectcode" id="subjectcode" value="<?php echo htmlspecialchars($subjectcode); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Subject Description</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="description" id="description" value="<?php echo htmlspecialchars($description); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Units</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="units" id="units" value="<?php echo htmlspecialchars($units); ?>">
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Prerequisite</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="prere" id="prere" value="<?php echo htmlspecialchars($prere); ?>">
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
                        <a class="btn btn-outline-primary" href="subject.php" role="button">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>