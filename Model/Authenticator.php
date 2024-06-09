<?php

class Authenticator {
    private $otp;
    private $phoneNumber;

    public function __construct() {
        $this->otp = null;
        $this->phoneNumber = null;
    }

    public function setOtp($otp) {
        $this->otp = $otp;
    }

    public function getOtp() {
        return $this->otp;
    }

    public function setPhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
    }

    public function getPhoneNumber() {
        return $this->phoneNumber;
    }
    public function generateOtp() {
        $this->otp = rand(100000, 999999);
        $_SESSION['generated_otp'] = $this->otp;
        return $this->otp;
    }

    public function validateOtp($inputOtp) {
        if (isset($_SESSION['generated_otp'])) {
            return $inputOtp == $_SESSION['generated_otp']; 
        }
        return false;
    }

    public function resetPasswordByPhoneOtp($otp, $phone) {
        if ($otp == $this->otp && $phone == $this->phoneNumber) {
            return true;
        }
        return false;
    }

    public function phoneVerificationByOtp($otp, $phone) {
        if ($otp == $this->otp && $phone == $this->phoneNumber) {
            return true;
        }
        return false;
    }
}

?>