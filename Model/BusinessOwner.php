<?php

require_once 'Profile.php';

class BusinessOwner extends Profile {
    private $username;
    private $name;
    private $email;
    private $password;
    private $company;
    private $subscription;
    private $faceData;
    private $suspend_status;

    public function __construct($id, $userProfile, $description, $username, $name, $email, $password, $company, $subscription, $faceData, $suspend_status = 0) {
        parent::__construct($id, $userProfile, $description);
        $this->username = $username;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->company = $company;
        $this->subscription = $subscription;
        $this->faceData = $faceData;
        $this->suspend_status = $suspend_status;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getCompany() {
        return $this->company;
    }

    public function setCompany($company) {
        $this->company = $company;
    }

    public function getSubscription() {
        return $this->subscription;
    }

    public function setSubscription($subscription) {
        $this->subscription = $subscription;
    }

    public function getFaceData() {
        return $this->faceData;
    }

    public function setFaceData($faceData) {
        $this->faceData = $faceData;
    }

    public function getSuspendStatus() {
        return $this->suspend_status;
    }

    public function setSuspendStatus($suspend_status) {
        $this->suspend_status = $suspend_status;
    }

    public static function createBusinessOwnerAccount($businessOwner) {
        $conn = require '../db/db.php';

        if (!$conn instanceof mysqli) {
            die("Database connection failed.");
        }

        $stmt = $conn->prepare("INSERT INTO businessowner (username, name, email, password, company, subscription, faceData, suspend_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $username = $businessOwner->getUsername();
        $name = $businessOwner->getName();
        $email = $businessOwner->getEmail();
        $password = $businessOwner->getPassword();
        $company = $businessOwner->getCompany();
        $subscription = $businessOwner->getSubscription();
        $faceData = $businessOwner->getFaceData();
        $suspend_status = $businessOwner->getSuspendStatus();

        $stmt->bind_param("sssssssi", $username, $name, $email, $password, $company, $subscription, $faceData, $suspend_status);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    public static function findByUsername($username) {
        $conn = require '../db/db.php';

        if (!$conn instanceof mysqli) {
            die("Database connection failed.");
        }

        $query = "SELECT * FROM businessowner WHERE username = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        $conn->close();

        if ($user) {
            return new self($user['id'], 'BusinessOwner', '', $user['username'], $user['name'], $user['email'], $user['password'], $user['company'], $user['subscription'], $user['faceData'], $user['suspend_status']);
        }

        return null;
    }
}

?>