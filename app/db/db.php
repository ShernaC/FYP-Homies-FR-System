<?php
    $config = require_once '../config/config.php';

    $conn = new mysqli($config['host'], $config['user'], $config['password'], $config['dbname']);
    
    if ($conn->connect_error) {
        die("Connection failed: ". $conn->connect_error);
    }
    
    //echo "Connected successfully";

    $sql = "CREATE DATABASE IF NOT EXISTS FaceRecognition";
    /*if ($conn->query($sql) === TRUE) {
        echo "Database created successfully";
    } else {
        echo "Error creating database: ". $conn->error;
    }*/
    
    $sql_profile = 'CREATE TABLE IF NOT EXISTS profile (
        id INT AUTO_INCREMENT PRIMARY KEY,
        userProfile VARCHAR(255) NOT NULL, 
        description VARCHAR(255) NOT NULL
    )';

    $sql_profile_data = "INSERT INTO profile (userProfile, description) VALUES ('System Admin', 'System Administrator'), ('Business Owner', 'Business Owner')";

    $sql_sysadmin = "CREATE TABLE IF NOT EXISTS sysadmin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        userName VARCHAR(255) NOT NULL,
        name VARCHAR(255),
        email VARCHAR(255) UNIQUE,
        profile VARCHAR(255),
        password VARCHAR(255) NOT NULL,
        suspend_status BOOLEAN DEFAULT FALSE
    )";
    
    $sql_businessowner = "CREATE TABLE IF NOT EXISTS businessowner (
        id INT AUTO_INCREMENT PRIMARY KEY,
        userName VARCHAR(255) NOT NULL,
        name VARCHAR(255),
        email VARCHAR(255) UNIQUE,
        profile VARCHAR(255),
        password VARCHAR(255) NOT NULL,
        company VARCHAR(255),
        suspend_status BOOLEAN DEFAULT FALSE
    )";

    $sql_subscription = "CREATE TABLE IF NOT EXISTS subscription (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        description VARCHAR(255) NOT NULL
    )";

    $sql_subscription_details = "CREATE TABLE IF NOT EXISTS subscription_details (
        id INT AUTO_INCREMENT PRIMARY KEY,
        subscription_id INT NOT NULL,
        businessowner_id INT NOT NULL,
        startDate DATE NOT NULL,
        endDate DATE NOT NULL,
        FOREIGN KEY (subscription_id) REFERENCES subscription(id),
        FOREIGN KEY (businessowner_id) REFERENCES businessowner(id)
    )";

    $sql_transaction = "CREATE TABLE IF NOT EXISTS transaction (
        id INT AUTO_INCREMENT PRIMARY KEY,
        subscription_id INT NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        status VARCHAR(255) NOT NULL,
        transaction_date DATE NOT NULL,
        FOREIGN KEY (subscription_id) REFERENCES subscription(id)
    )";

    // Execute the table creation queries
    /*if ($conn->query($sql_sysadmin) === TRUE && $conn->query($sql_businessowner) === TRUE && $conn->query($sql_profile) === TRUE) {
        echo "Tables sysadmin and businessowner created successfully";
    } else {
        echo "Error creating tables: ". $conn->error;
    }*/
    
    // Optionally, close the connection if you're done with it
    //$conn->close();
    
    return $conn;

?>