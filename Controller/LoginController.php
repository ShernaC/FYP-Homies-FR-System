<?php

require_once '../Model/Admin.php';
require_once '../Model/BusinessOwner.php';
require_once '../Model/Authenticator.php';

class LoginController {
    public function login($username, $password, $profile) {
        if ($profile === 'Admin') {
            $user = Admin::findByUsername($username);
        } else if ($profile === 'BusinessOwner') {
            $user = BusinessOwner::findByUsername($username);
        } else {
            return "User type not recognized.";
        }

        if ($user && $user->getPassword() === $password) {
            $_SESSION['user'] = serialize($user);
            $_SESSION['userType'] = $profile;
            $this->generateOTP();
            return true;
        } else {
            return "Invalid username or password.";
        }
    }

    public function generateOTP() {
        $authenticator = new Authenticator();
        $otp = $authenticator->generateOTP();
        $_SESSION['otp'] = $otp;
        return $otp; // 返回 OTP
    }

    public function validateOTP($inputOtp) {
        $authenticator = new Authenticator();
        return $authenticator->validateOTP($inputOtp);
    }

    public function logout() {
        session_unset();
        session_destroy();
    }
}

?>