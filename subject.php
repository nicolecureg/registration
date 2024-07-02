<?php
include("config.php");

$search_subjectcode = "";
$subject_info = null;
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_subjectcode = $_POST['search_subjectcode'];

    if (!empty($search_subjectcode)) {
        $sql = "SELECT * FROM astre WHERE subjectcode='$search_subjectcode'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $subject_info = $result->fetch_assoc();
        } else {
            $error_message = "NO INFORMATION HAS BEEN RECORDED";
        }
    } else {
        $error_message = "Please enter a subject code to search.";
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
    <title>SUBJECTS</title>
</head>
<body>
<?php include("homepage.php"); ?>
<div class="container my-5">
    <?php
    if (isset($_SESSION['success_message'])) {
        echo "
        <div class='alert alert-success alert-dismissible fade show' role='alert'>
            <strong>{$_SESSION['success_message']}</strong>
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
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
    <a href="addsubject.php" class="btn" style="background-color: #FFA500; color: white;">ADD</a>
    </div>
    <h2>SUBJECTS INFORMATION</h2>
    <br>

    <!-- Search Bar -->
    <form method="post" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="search_subjectcode" placeholder="Enter subject code" value="<?php echo htmlspecialchars($search_subjectcode); ?>">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>

    <?php if ($subject_info): ?>
        <!-- Display Subject Info if found -->
        <table class="table">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Description</th>
                    <th>Units</th>
                    <th>Prerequisite</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($subject_info['subjectcode']); ?></td>
                    <td><?php echo htmlspecialchars($subject_info['description']); ?></td>
                    <td><?php echo htmlspecialchars($subject_info['units']); ?></td>
                    <td><?php echo htmlspecialchars($subject_info['prere']); ?></td>
                    <td>
                        <a class='btn btn-primary btn-sm me-2' href='editsubject.php?subjectcode=<?php echo htmlspecialchars($subject_info['subjectcode']); ?>'><i class='fa-solid fa-pen-to-square'></i></a>
                        <button class='btn btn-danger btn-sm' onclick='confirmDelete("<?php echo htmlspecialchars($subject_info['subjectcode']); ?>")'><i class='fa-solid fa-trash-can'></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <!-- Display All Subjects Info if no search performed or no match found -->
        <table class="table">
            <thead>
                <tr>
                <th>Subject Code</th>
                    <th>Subject Description</th>
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
                    echo "
                    <tr>
                        <td>{$row['subjectcode']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['units']}</td>
                        <td>{$row['prere']}</td>
                        <td>
                            <a class='btn btn-primary btn-sm me-2' href='editsubject.php?subjectcode={$row['subjectcode']}'><i class='fa-solid fa-pen-to-square'></i></a>
                            <button class='btn btn-danger btn-sm' onclick='confirmDelete(\"{$row['subjectcode']}\")'><i class='fa-solid fa-trash-can'></i></button>
                        </td>
                    </tr>
                    ";
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                Are you sure you want to delete this subject?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes</button>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmDelete(subjectcode) {
        const confirmDeleteButton = document.getElementById('confirmDeleteButton');
        confirmDeleteButton.onclick = function() {
            window.location.href = 'deletesubject.php?subjectcode=' + subjectcode;
        };
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    <?php if (!empty($error_message)): ?>
    const noInfoModal = new bootstrap.Modal(document.getElementById('noInfoModal'));
    noInfoModal.show();
    <?php endif; ?>
</script>
</body>
</html>