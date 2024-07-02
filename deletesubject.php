<?php
include("config.php");

// Check if the studentnumber parameter is set in the URL
if (!isset($_GET['subjectcode'])) {
    die("Error: ROW is not specified.");
}

// Get the student number from the URL and sanitize it
$subjectcode = $conn->real_escape_string($_GET['subjectcode']);

// Prepare the SQL statement
$sql = "DELETE FROM astre WHERE subjectcode = '$subjectcode'";

// Execute the SQL statement
if ($conn->query($sql) === TRUE) {

} else {
    $_SESSION['error_message'] = "Error deleting subject: " . $conn->error;
}

// Close the database connection
$conn->close();

// Redirect back to the main page
header("Location: subject.php");
exit;
?>