<?php

require_once '../model/account.php';

class LoginController {
    public function login($username, $password, $profile) {
    include_once '../db/db.php';        
    include_once '../model/account.php'; 

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userName = $_POST['username'];
        $password = $_POST['password'];
        $profile = $_POST['data-profile'];

        $response = array('success' => false, 'data' => '');
        if ($profile == 'System Admin') {
            $sysAdmin = new SysAdmin();
            $user = $sysAdmin->validateLogin($userName, $password);
        } else if ($profile == 'Business Owner') {
            $businessOwner = new BusinessOwner();
            $user = $businessOwner->validateLogin($userName, $password);
        } else {
            $response['data'] = 'Invalid profile type';
            echo json_encode($response);
            exit;
    }
    if ($user) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $_SESSION['userID'] = $user['id'];
        $_SESSION['userName'] = $user['userName'];
        $_SESSION['userProfile'] = $profile;   
        $response['success'] = true;
        $response['data'] = $user['userName']; 
    } else {
        $response['data'] = 'Invalid username or password';
    }

    echo json_encode($response);
}
}
}
?>