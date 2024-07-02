<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Assessment</title>
    <style>
        .form-inline {
            display: flex;
            align-items: center;
        }
        .form-inline select {
            flex-grow: 1;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
<?php include("homepage.php"); ?>
<div class="container my-5">

    <?php
    include("config.php");

    $errorMessage = "";

    $schoolsQuery = "SELECT DISTINCT school FROM enrollments";
    $schoolsResult = $conn->query($schoolsQuery);
    $schools = [];
    if ($schoolsResult->num_rows > 0) {
        while ($row = $schoolsResult->fetch_assoc()) {
            $schools[] = $row['school'];
        }
    } else {
        $errorMessage .= "No schools found.";
    }
    ?>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger" role="alert">
            <strong><?php echo $errorMessage; ?></strong>
        </div>
    <?php endif; ?>

    <div class="mb-3 form-inline">
        <label for="schoolSelect" class="form-label me-2">School Year:</label>
        <select class="form-select me-2" id="schoolSelect" name="schoolSelect" required>
            <option value="">Select School</option>
            <?php foreach ($schools as $school): ?>
                <option value="<?php echo htmlspecialchars($school); ?>"><?php echo htmlspecialchars($school); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="button" class="btn btn-primary" id="printButton">Print</button>
    </div>

    <div id="printableArea">

        <div id="assessmentDetails" style="display: none;">
        <h2>Assessment Form</h2>
            <div class="mb-3">
                <label for="studentnumber" class="form-label">Student Number</label>
                <input type="text" class="form-control" id="studentnumber" readonly>
            </div>
            <div class="mb-3">
                <label for="fname" class="form-label">Name</label>
                <input type="text" class="form-control" id="fname" readonly>
            </div>
            <div class="mb-3">
                <label for="coursecode" class="form-label">Course</label>
                <input type="text" class="form-control" id="coursecode" readonly>
            </div>
            <div class="mb-3">
                <label for="schoolYear" class="form-label">School Year</label>
                <input type="text" class="form-control" id="schoolYear" readonly>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Description</th>
                        <th>Units</th>
                    </tr>
                </thead>
                <tbody id="subjectsTableBody"></tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-end"><strong>Total Units:</strong></td>
                        <td><strong id="totalUnits"></strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#schoolSelect').change(function() {
        const selectedSchool = $(this).val();
        if (selectedSchool) {
            $.ajax({
                url: 'fetch_assessment.php',
                type: 'POST',
                data: { school: selectedSchool },
                success: function(response) {
                    const data = JSON.parse(response);
                    if (data.errorMessage) {
                        alert(data.errorMessage);
                    } else {
                        $('#studentnumber').val(data.studentnumber);
                        $('#fname').val(data.fname);
                        $('#coursecode').val(data.coursecode);
                        $('#schoolYear').val(data.schoolYear);

                        let subjectsHtml = '';
                        data.selected_subjects.forEach(subject => {
                            subjectsHtml += `
                                <tr>
                                    <td>${subject.subjectcode}</td>
                                    <td>${subject.description}</td>
                                    <td>${subject.units}</td>
                                </tr>
                            `;
                        });
                        $('#subjectsTableBody').html(subjectsHtml);
                        $('#totalUnits').text(data.totalUnits);
                        $('#assessmentDetails').show();
                    }
                },
                error: function() {
                    alert('An error occurred while fetching the data.');
                }
            });
        } else {
            $('#assessmentDetails').hide();
        }
    });

    $('#printButton').click(function() {
        window.print();
    });
});
</script>

</body>
</html>