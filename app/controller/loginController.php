<?php
include_once '../model/account.php';

// 开始会话
if (!isset($_SESSION)) {
    session_start();
}
$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    case 'login':
        handleLogin();
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

function handleLogin() {
    $profile = $_POST['profile'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username or password cannot be empty']);
        return;
    }

    if ($profile === 'System Admin') {
        $sysAdmin = new SysAdmin();
        $response = $sysAdmin->login($username, $password);
    } elseif ($profile === 'Business Owner') {
        $businessOwner = new BusinessOwner();
        $response = $businessOwner->login($username, $password);
    } else {
        $response = json_encode(['success' => false, 'message' => 'Invalid profile type']);
    }

    echo $response;
}
?>