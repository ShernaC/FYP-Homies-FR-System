<?php
$config = require_once '../config/config.php';
$config = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '123456',
    'dbname' => 'FaceRecognition'
];
$conn = new mysqli($config['host'], $config['user'], $config['password'], $config['dbname']);

if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS FaceRecognition";
/*if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: ". $conn->error;
}*/

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

$sql_profile = "CREATE TABLE IF NOT EXISTS profile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userProfile VARCHAR(255) NOT NULL,
    description TEXT
)";

// if ($conn->query($sql_sysadmin) === TRUE && $conn->query($sql_businessowner) === TRUE && $conn->query($sql_profile) === TRUE) {
//     echo "Tables sysadmin, businessowner, and profile created successfully";
// } else {
//     echo "Error creating tables: ". $conn->error;
// }

$sql_sample_data = "
INSERT INTO sysadmin (userName, name, email, profile, password, suspend_status)
VALUES 
('admin1', 'Admin One', 'admin1@example.com', 'System Admin','482c811da5d5b4bc6d497ffa98491e38',  0),
('admin2', 'Admin Two', 'admin2@example.com', 'System Admin','35d39bc00e38141675dc1202e322fb77',  0),
('admin3', 'Admin Three', 'admin3@example.com', 'System Admin','96b33694c4bb7dbd07391e0be54745fb',  0)
ON DUPLICATE KEY UPDATE userName=userName;

INSERT INTO businessowner (userName, name, email, password, profile, company, suspend_status) VALUES 
('owner1', 'Owner One', 'owner1@example.com', '482c811da5d5b4bc6d497ffa98491e38', 'Business Owner', 'Company One', 0),
('owner2', 'Owner Two', 'owner2@example.com', '35d39bc00e38141675dc1202e322fb77', 'Business Owner', 'Company Two', 0),
('owner3', 'Owner Three', 'owner3@example.com', '96b33694c4bb7dbd07391e0be54745fb', 'Business Owner', 'Company Three', 0)
ON DUPLICATE KEY UPDATE userName=userName;

INSERT INTO profile (userProfile, description)
VALUES
    ('System Admin', 'Profile for system administrators.'),
    ('Business Owner', 'Profile for business owners.')
ON DUPLICATE KEY UPDATE userProfile=userProfile;
";

 //if ($conn->multi_query($sql_sample_data) === TRUE) {
     //echo "Sample data inserted successfully";
 //} else {
    // echo "Error inserting data: " . $conn->error;
 //}

// $conn->close();

return $conn;

?>