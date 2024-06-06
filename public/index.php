<?php
    include ('app/db/db.php');

    echo $conn->host_info . "\n";

    // Redirect to main HTML page
    header("Location: ../app/view/mainpage.html");

    // Close connection
    exit();
?>