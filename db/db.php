<?php
$config = require_once '../config/config.php';
$config = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '123456',
    'dbname' => 'FaceRecognition'
];
$conn = new mysqli($config['host'], $config['user'], $config['password'], $config['dbname']);

if (!is_array($config)) {
    die("Configuration is not an array.");
}
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS sysadmin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255) NOT NULL,
    suspend_status BOOLEAN DEFAULT FALSE
)";

if ($conn->query($sql) === TRUE) {
    echo "Table sysadmin created successfully";
} else {
    echo "Error creating sysadmin table: " . $conn->error;
}

$sql = "CREATE TABLE IF NOT EXISTS businessowner (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255) NOT NULL,
    company VARCHAR(255),
    subscription VARCHAR(255),
    faceData TEXT,
    suspend_status BOOLEAN DEFAULT FALSE
)";
$sql = "CREATE TABLE IF NOT EXISTS profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userProfile VARCHAR(255) NOT NULL,
    description TEXT
)";
if ($conn->query($sql) === TRUE) {
    echo "Table businessowner created successfully";
} else {
    echo "Error creating businessowner table: " . $conn->error;
}

// $conn->close();
return $conn;
?>