<?php
include("config.php");

$search_coursecode = "";
$course_info = null;
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_coursecode = $_POST['search_coursecode'];

    if (!empty($search_coursecode)) {
        $sql = "SELECT * FROM xy WHERE coursecode='$search_coursecode'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $course_info = $result->fetch_assoc();
        } else {
            $error_message = "NO INFORMATION HAS BEEN RECORDED";
        }
    } else {
        $error_message = "Please enter a course name to search.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>COURSE</title>
</head>
<body>
<?php include("homepage.php"); ?>
<div class="container my-5">
    <?php
    // Include success and error messages if set in session
    if (isset($_SESSION['success_message'])) {
        echo "
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>{$_SESSION['success_message']}</strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close' onclick='redirectToCoursePage()'></button>
        </div>
        ";
        unset($_SESSION['success_message']);
    }

    if (isset($_SESSION['error_message'])) {
        echo "
        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
            <strong>{$_SESSION['error_message']}</strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>
        ";
        unset($_SESSION['error_message']);
    }
    ?>
    <div class="d-flex justify-content-end mb-3">
        <!-- Link to addcourse.php for adding a new course -->
        <a href="addcourse.php" class="btn" style="background-color: #FFA500; color: white;">ADD</a>
    </div>
    <h2>COURSES</h2>
    <br>

    <!-- Search Bar Form -->
    <form method="post" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="search_coursecode" placeholder="Enter course name" value="<?php echo htmlspecialchars($search_coursecode); ?>">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>

    <?php if ($course_info): ?>
        <!-- Display Course Info if found -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Course Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($course_info['coursecode']); ?></td>
                    <td><?php echo htmlspecialchars($course_info['coursedes']); ?></td>
                    <td>
                        <!-- Edit and Delete Buttons for Course -->
                        <a class='btn btn-primary btn-sm me-2' href='editcourse.php?coursecode=<?php echo htmlspecialchars($course_info['coursecode']); ?>'><i class="fa-solid fa-pen-to-square"></i></a>
                        <button class='btn btn-danger btn-sm' onclick='confirmDeleteCourse("<?php echo htmlspecialchars($course_info['coursecode']); ?>")'><i class="fa-solid fa-trash-can"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <!-- Display All Courses Info if no search performed or no match found -->
        <table class="table table-bordered">
            <thead>
                <tr>
                <th>Course Name</th>
                    <th>Course Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query all courses if no search criteria or no matching results
                $sql = "SELECT * FROM xy";
                $result = $conn->query($sql);

                if (!$result) {
                    die("Invalid query: " . $conn->error);
                }

                while ($row = $result->fetch_assoc()) {
                    echo "
                    <tr>
                        <td>{$row['coursecode']}</td>
                        <td>{$row['coursedes']}</td>
                        <td>
                            <!-- Edit and Delete Buttons for Each Course -->
                            <a class='btn btn-primary btn-sm me-2' href='editcourse.php?coursecode={$row['coursecode']}'><i class='fa-solid fa-pen-to-square'></i></a>
                            <button class='btn btn-danger btn-sm' onclick='confirmDeleteCourse(\"{$row['coursecode']}\")'><i class='fa-solid fa-trash-can'></i></button>
                        </td>
                    </tr>
                    ";
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal for Course -->
<div class="modal fade" id="deleteCourseModal" tabindex="-1" aria-labelledby="deleteCourseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                Are you sure you want to delete this course?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCourseButton">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- No Information Modal -->
<div class="modal fade" id="noInfoModal" tabindex="-1" aria-labelledby="noInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                NO INFORMATION HAS BEEN RECORDED.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JavaScript Bundle (Popper included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript for Custom Functions -->
<script>
    // Function to confirm deletion of a course
    function confirmDeleteCourse(coursecode) {
        const confirmDeleteCourseButton = document.getElementById('confirmDeleteCourseButton');
        confirmDeleteCourseButton.onclick = function() {
            window.location.href = 'deletecourse.php?coursecode=' + coursecode;
        };
        const deleteCourseModal = new bootstrap.Modal(document.getElementById('deleteCourseModal'));
        deleteCourseModal.show();
    }

    // Function to redirect to the course.php page
    function redirectToCoursePage() {
        window.location.href = 'course.php';
    }

    // Show modal if there is an error message
    <?php if (!empty($error_message)): ?>
    const noInfoModal = new bootstrap.Modal(document.getElementById('noInfoModal'));
    noInfoModal.show();
    <?php endif; ?>
</script>

</body>
</html>