<?php
session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 'a') {
        header("location: ../login.php");
    }
} else {
    header("location: ../login.php");
}

if ($_POST) {
    include("../connection.php");
    
    // Sanitize user input to prevent SQL injection and XSS
    $title = htmlspecialchars(stripslashes($_POST["title"]), ENT_QUOTES);
    $docid = htmlspecialchars(stripslashes($_POST["docid"]), ENT_QUOTES);
    $nop = htmlspecialchars(stripslashes($_POST["nop"]), ENT_QUOTES);
    $day = htmlspecialchars(stripslashes($_POST["scheduleday"]), ENT_QUOTES);
    $timerange = htmlspecialchars(stripslashes($_POST["timerange"]), ENT_QUOTES);

    // Extract start and end times from the timerange string
    // Assuming the format is "HH:mm AM - HH:mm PM" or similar
    list($start_time_str, $end_time_str) = explode(' - ', $timerange);

    // Convert time strings to a format suitable for database
    // For example, '08:00 AM' to '08:00:00'
    $start_time = date('H:i:s', strtotime($start_time_str));
    $end_time = date('H:i:s', strtotime($end_time_str));

    // SQL INSERT statement with all required fields
    // Using prepared statements is highly recommended for security.
    $sql = "INSERT INTO schedule (docid, title, scheduleday, timerange, nop, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare the statement
    $stmt = $database->prepare($sql);
    
    // Bind the parameters
    $stmt->bind_param("isssiss", $docid, $title, $day, $timerange, $nop, $start_time, $end_time);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        header("location: schedule.php?action=session-added&title=".urlencode($title));
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $database->close();
}
?>