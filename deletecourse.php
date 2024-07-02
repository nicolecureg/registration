<?php
include("config.php");

// Check if the coursecode parameter is set in the URL
if (!isset($_GET['coursecode'])) {
    die("Error: Course code is not specified.");
}

// Get the course code from the URL and sanitize it
$coursecode = $conn->real_escape_string($_GET['coursecode']);

// Prepare the SQL statement
$sql = "DELETE FROM xy WHERE coursecode = '$coursecode'";

// Execute the SQL statement
if ($conn->query($sql) === TRUE) {
} else {
    $_SESSION['error_message'] = "Error deleting course: " . $conn->error;
}

// Close the database connection
$conn->close();

// Redirect back to the main page
header("Location: course.php");
exit;
?>