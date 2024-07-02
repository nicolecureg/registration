<?php
include("config.php");

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['school'])) {
    $selectedSchool = $_POST['school'];

    // Fetch the enrollment details including the subjects column
    $assessmentQuery = $conn->prepare("SELECT * FROM enrollments WHERE school = ?");
    $assessmentQuery->bind_param('s', $selectedSchool);
    $assessmentQuery->execute();
    $result = $assessmentQuery->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response['studentnumber'] = $row['studentnumber'];
        $response['fname'] = $row['fname'];
        $response['coursecode'] = $row['coursecode'];
        $response['schoolYear'] = $row['school'];

        // Get the subjects from the subjects column
        $subjectCodes = explode(',', $row['subjects']);
        $placeholders = implode(',', array_fill(0, count($subjectCodes), '?'));
        $types = str_repeat('s', count($subjectCodes));

        // Fetch subjects related to the enrollment from the subjects table
        $subjectsQuery = $conn->prepare("SELECT subjectcode, description, units FROM astre WHERE subjectcode IN ($placeholders)");
        $subjectsQuery->bind_param($types, ...$subjectCodes);
        $subjectsQuery->execute();
        $subjectsResult = $subjectsQuery->get_result();

        $response['selected_subjects'] = [];
        $totalUnits = 0;
        while ($subjectRow = $subjectsResult->fetch_assoc()) {
            $response['selected_subjects'][] = $subjectRow;
            $totalUnits += $subjectRow['units'];
        }
        $response['totalUnits'] = $totalUnits;
    } else {
        $response['errorMessage'] = "No assessment data found for the selected school.";
    }
    echo json_encode($response);
}
?>