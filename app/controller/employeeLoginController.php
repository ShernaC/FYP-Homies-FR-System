<?php
include_once '../model/company.php';

if (!isset($_SESSION)) {
    session_start();
}
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action == "login"){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username or password cannot be empty']);
        return;
    }

    $company = new Company();
    $response=$company->login($username, $password);

    echo $response;
}