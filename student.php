<?php
include("config.php");


$search_studentnumber = "";
$student_info = null;
$error_message = "";

// Handle search by student number
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_studentnumber'])) {
    $search_studentnumber = $_POST['search_studentnumber'];

    if (!empty($search_studentnumber)) {
        $sql = "SELECT * FROM users WHERE studentnumber='$search_studentnumber'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $student_info = $result->fetch_assoc();
        } else {
            $error_message = "Student not found.";
        }
    } else {
        $error_message = "Please enter a student number to search.";
    }
}

// Handle updating active status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_status'])) {
    $active_students = isset($_POST['active_students']) ? $_POST['active_students'] : [];

    // Retrieve all student numbers from the database
    $all_students = [];
    $sql = "SELECT studentnumber FROM users";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $all_students[] = $row['studentnumber'];
    }

    // Update each student's active status
    foreach ($all_students as $studentnumber) {
        $isActive = in_array($studentnumber, $active_students) ? 1 : 0;
        $updateQuery = "UPDATE users SET active = '$isActive' WHERE studentnumber = '$studentnumber'";
        if (!$conn->query($updateQuery)) {
            echo "Error updating active status: " . $conn->error;
        }
    }

    // Update session with selected checkboxes
    $_SESSION['selected_students'] = $active_students;
}

// Retrieve selected checkboxes from session or initialize an empty array
$selected_students = isset($_SESSION['selected_students']) ? $_SESSION['selected_students'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="student.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Student Information</title>
</head>
<body>
<?php include("homepage.php"); ?>
<div class="container my-5">
    <?php
    // Display success message if set
    if (isset($_SESSION['success_message'])) {
        echo "
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>{$_SESSION['success_message']}</strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
        ";
        unset($_SESSION['success_message']);
    }

    // Display error message if set
    if (!empty($error_message)) {
        echo "
        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
            <strong>{$error_message}</strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
        ";
    }
    ?>

    <div class="d-flex justify-content-end mb-3">
    <a href="addstudent.php" class="btn" style="background-color: #FFA500; color: white;"><i class="fa-solid fa-user-plus"></i></a>
    </div>
    <h2>STUDENTS INFORMATION</h2>

    <!-- Search Bar -->
    <form method="post" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="search_studentnumber" placeholder="Enter student number" value="<?php echo htmlspecialchars($search_studentnumber); ?>">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>

    <form method="post">
        <?php if ($student_info): ?>
            <!-- Display Student Info if found -->
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Student Number</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo htmlspecialchars($student_info['studentnumber']); ?></td>
                        <td><?php echo htmlspecialchars($student_info['fname']); ?></td>
                        <td><?php echo htmlspecialchars($student_info['gender']); ?></td>
                        <td><?php echo htmlspecialchars($student_info['address']); ?></td>
                        <td>
                            <!-- Edit link -->
                            <a class='btn btn-primary btn-sm me-2' href='editstudent.php?studentnumber=<?php echo htmlspecialchars($student_info['studentnumber']); ?>'><i class='fa-solid fa-pen-to-square'></i></a>
                            <!-- Checkbox for active status -->
                            <input type="checkbox" class="form-check-input" name="active_students[]" value="<?php echo htmlspecialchars($student_info['studentnumber']); ?>" <?php echo $student_info['active'] == 1 ? 'checked' : ''; ?>>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <!-- Display All Students Info if no search performed or no match found -->
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Student Number</th>
                        <th>Fullname</th>
                        <th>Gender</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM users";
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['studentnumber']); ?></td>
                                <td><?php echo htmlspecialchars($row['fname']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                <td>
                                    <!-- Edit link -->
                                    <a class='btn btn-primary btn-sm me-2' href='editstudent.php?studentnumber=<?php echo htmlspecialchars($row['studentnumber']); ?>'><i class='fa-solid fa-pen-to-square'></i></a>
                                    <!-- Checkbox for active status -->
                                    <input type="checkbox" class="form-check-input" name="active_students[]" value="<?php echo htmlspecialchars($row['studentnumber']); ?>" <?php echo in_array($row['studentnumber'], $selected_students) && $row['active'] == 1 ? 'checked' : ''; ?>>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='5'>No records found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary" name="save_status">Save</button>
        <?php endif; ?>
    </form>
</div>

<!-- No Information Modal -->
<div class="modal fade" id="noInfoModal" tabindex="-1" aria-labelledby="noInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                NO INFORMATION HAS BEEN RECORDED.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>