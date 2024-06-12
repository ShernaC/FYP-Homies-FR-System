<?php

require_once '../model/account.php';

class LoginController {
    public function login($username, $password, $profile) {
    include_once '../db/db.php';        // 引入数据库连接
    include_once '../model/account.php'; // 引入模型文件

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $userName = $_POST['username'];
        $password = $_POST['password'];
        $profile = $_POST['data-profile'];

        $response = array('success' => false, 'data' => '');

        // 检查用户类型并调用相应的验证方法
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

    // 验证结果处理
    if ($user) {
        // 启动会话
        if (!isset($_SESSION)) {
            session_start();
        }
        
        // 设置会话变量
        $_SESSION['userID'] = $user['id'];
        $_SESSION['userName'] = $user['userName'];
        $_SESSION['userProfile'] = $profile;
        
        $response['success'] = true;
        $response['data'] = $user['userName']; // 返回用户名
    } else {
        $response['data'] = 'Invalid username or password';
    }

    echo json_encode($response);
}
}
}
?>