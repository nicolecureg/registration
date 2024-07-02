<?php
include("config.php");

if (isset($_GET['studentnumber'])) {
    $studentnumber = $_GET['studentnumber'];

    $query = "SELECT fname, active FROM users WHERE studentnumber = '$studentnumber'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['fname' => $row['fname'], 'active' => $row['active']]);
    } else {
        echo json_encode(['fname' => '', 'active' => 0]);
    }
}
?>