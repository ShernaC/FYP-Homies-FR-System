<?php
    $config = require_once 'config.php';

    $host = $config['host'];
    $port = $config['port'];
    $dbname = $config['dbname'];
    $user = $config['user'];
    $password = $config['password'];

    // Connect to PostgreSQL database
    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

    // Check connection
    if (!$conn) {
        die("Error connecting to the database: " . pg_last_error());
    }

    // Redirect to main HTML page
    header("Location: mainpage.html");

    // Close connection
    pg_close($conn);
?> 