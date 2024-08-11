<?php
    $config = require_once '../config/config.php';

    $conn = new mysqli($config['host'], $config['user'], $config['password'], $config['dbname']);
    
    if ($conn->connect_error) {
        die("Connection failed: ". $conn->connect_error);
    }
    
    //echo "Connected successfully";

    $sql = "CREATE DATABASE IF NOT EXISTS FaceRecognition";

    $sql_profile = 'CREATE TABLE IF NOT EXISTS profile (
        id INT AUTO_INCREMENT PRIMARY KEY,
        userProfile VARCHAR(255) NOT NULL, 
        description VARCHAR(255) NOT NULL
    )';

    // $sql_profile_data = "INSERT INTO profile (userProfile, description) VALUES ('System Admin', 'System Administrator'), ('Business Owner', 'Business Owner')";

    $sql_sysadmin = "CREATE TABLE IF NOT EXISTS sysadmin (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        userName VARCHAR(255) NOT NULL,
        name VARCHAR(255),
        email VARCHAR(255) UNIQUE,
        profile VARCHAR(255),
        password VARCHAR(255) NOT NULL,
        otp VARCHAR(255),
        suspend_status BOOLEAN DEFAULT FALSE
    )";
    
    $sql_businessowner = "CREATE TABLE IF NOT EXISTS businessowner (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        userName VARCHAR(255) NOT NULL,
        name VARCHAR(255),
        email VARCHAR(255) UNIQUE,
        profile VARCHAR(255),
        password VARCHAR(255) NOT NULL,
        company VARCHAR(255),
        subscription_id INT DEFAULT 1,
        company_code VARCHAR(255) NOT NULL,
        otp VARCHAR(255),
        suspend_status BOOLEAN DEFAULT FALSE
    )";

    $sql_business_owner_trigger = "CREATE TRIGGER IF NOT EXISTS insert_businessowner_id
        BEFORE INSERT ON businessowner
        FOR EACH ROW BEGIN
            IF NEW.id IS NULL OR NEW.id = '' THEN
                SET NEW.id = UUID_SHORT();
            END IF;
        END";

    $sql_subscription = "CREATE TABLE IF NOT EXISTS subscription (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        description VARCHAR(255) NOT NULL
    )";

    $sql_subscription_details = "CREATE TABLE IF NOT EXISTS subscription_details (
        id INT AUTO_INCREMENT PRIMARY KEY,
        subscription_id INT NOT NULL,
        userName VARCHAR(255) NOT NULL,
        startDate DATE NOT NULL,
        endDate DATE NOT NULL,
        FOREIGN KEY (subscription_id) REFERENCES subscription(id)
       /* FOREIGN KEY (businessowner_id) REFERENCES businessowner(id)*/
    )";

    // $sql_subscription_data = "INSERT INTO subscription (name, price, description) VALUES 
    //                             ('Free trial', 0.00, 'One-month access to 3 facial slots for trial users.'), 
    //                             ('Small Business Plan', 50.00, 'Supports up to 50 face datasets.'), 
    //                             ('Medium-Sized Business Plan', 100.00, 'Supports up to 100 face datasets'), 
    //                             ('Large Enterprise Plan', 125.00, 'Supports unlimited face datasets.');";    

    
    $sql_transaction = "CREATE TABLE IF NOT EXISTS transaction (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        businessowner_id BIGINT UNSIGNED NOT NULL, 
        subscription_id INT NOT NULL,
        paid_amount FLOAT(10, 2) NOT NULL,
        payment_status VARCHAR(255) NOT NULL,
        transaction_date DATE NOT NULL,
        FOREIGN KEY (subscription_id) REFERENCES subscription(id),
        FOREIGN KEY (businessowner_id) REFERENCES businessowner(id)
    )";

    $sql_company = "CREATE TABLE IF NOT EXISTS company (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        subscription_name VARCHAR(255) NOT NULL DEFAULT 'Free trial'
    )";

    // KIV - Face Data
    /*$sql_face_data = "CREATE TABLE IF NOT EXISTS face_data (
        id INT AUTO_INCREMENT PRIMARY KEY,
        businessowner_id INT NOT NULL,
        employee_name VARCHAR(255) NOT NULL,
        faceData BLOB NOT NULL,
        FOREIGN KEY (businessowner_id) REFERENCES businessowner(id)
    )";*/

    // $sql_sysadmin_add = "INSERT INTO sysadmin (userName, name, email, profile, password) VALUES ('testAdmin', 'Test Admin', 'fyp24s215@gmail.com', 'System Admin', '0cef1fb10f60529028a71f58e54ed07b')";
    // $sql_businessowner_add = "INSERT INTO businessowner (userName, name, email, profile, password, company) VALUES ('testOwner', 'Test Owner', 'fyp24s215@gmail.com, 'Business Owner', '0cef1fb10f60529028a71f58e54ed07b', 'Test Company')";

    // Execute the table creation queries
    // $conn->query($sql);
    $conn->query($sql_sysadmin);
    $conn->query($sql_businessowner);
    // $conn->query($sql_sysadmin_add);
    // $conn->query($sql_businessowner_add);
    $conn->query($sql_profile);
    $conn->query($sql_business_owner_trigger);
    // $conn->query($sql_profile_data);
    $conn->query($sql_subscription);
    $conn->query($sql_subscription_details);
    // $conn->query($sql_subscription_data);
    $conn->query($sql_transaction);
    //$conn->query($sql_face_data);
    $conn->query($sql_company);

    /*if ($conn->query($sql_sysadmin) === TRUE && $conn->query($sql_businessowner) === TRUE && $conn->query($sql_profile) === TRUE) {
        echo "Tables sysadmin and businessowner created successfully";
    } else {
        echo "Error creating tables: ". $conn->error;
    }*/
    
    // Optionally, close the connection if you're done with it
    //$conn->close();
    
    return $conn;

?>