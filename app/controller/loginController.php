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
    case 'send_otp':
        handleSendOtp();
        break;
    case 'validate_otp':
        handleValidateOtp();
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

function handleSendOtp() {
    $email = $_POST['email'];

    // Ensure the email is provided
    if (empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Email cannot be empty']);
        return;
    }

    // Include the send_otp script
    include '../model/send_otp.php';
}

function handleValidateOtp() {
    $otp = $_POST['otp'];

    // Ensure the OTP is provided
    if (empty($otp)) {
        echo json_encode(['success' => false, 'message' => 'OTP cannot be empty']);
        return;
    }

    // Include the validate_otp script
    include '../model/validate_otp.php';
}

?>