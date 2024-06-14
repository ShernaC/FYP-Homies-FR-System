<?php
    include ('app/db/db.php');

    echo $conn->host_info . "\n";

    // Redirect to main HTML page
    header("Location: ../app/view/login.php");

    // Close connection
    exit();
?>