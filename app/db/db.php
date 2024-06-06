<?php
    $config = require_once '../config/config.php';

    $conn = new mysqli($config['host'], $config['user'], $config['password'], $config['dbname']);
    
    if ($conn->connect_error) {
        die("Connection failed: ". $conn->connect_error);
    }
    
    echo "Connected successfully";

    $sql = "CREATE DATABASE IF NOT EXISTS FaceRecognition";
    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully";
    } else {
        echo "Error creating database: ". $conn->error;
    }
    
    $sql_sysadmin = "CREATE TABLE IF NOT EXISTS sysadmin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        userName VARCHAR(255) NOT NULL,
        name VARCHAR(255),
        email VARCHAR(255) UNIQUE,
        password VARCHAR(255) NOT NULL,
        suspend_status BOOLEAN DEFAULT FALSE
    )";
    
    $sql_businessowner = "CREATE TABLE IF NOT EXISTS businessowner (
        id INT AUTO_INCREMENT PRIMARY KEY,
        userName VARCHAR(255) NOT NULL,
        name VARCHAR(255),
        email VARCHAR(255) UNIQUE,
        password VARCHAR(255) NOT NULL,
        suspend_status BOOLEAN DEFAULT FALSE
    )";
    
    // Execute the table creation queries
    if ($conn->query($sql_sysadmin) === TRUE && $conn->query($sql_businessowner) === TRUE) {
        echo "Tables sysadmin and businessowner created successfully";
    } else {
        echo "Error creating tables: ". $conn->error;
    }
    
    // Optionally, close the connection if you're done with it
    //$conn->close();
    
    return $conn;

?>