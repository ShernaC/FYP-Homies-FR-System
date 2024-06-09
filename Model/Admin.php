<?php

require_once 'Profile.php';

class Admin extends Profile {
    private $username;
    private $name;
    private $email;
    private $password;
    private $suspend_status;

    public function __construct($id, $userProfile, $description, $username, $name, $email, $password, $suspend_status = 0) {
        parent::__construct($id, $userProfile, $description);
        $this->username = $username;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
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

    public function getSuspendStatus() {
        return $this->suspend_status;
    }

    public function setSuspendStatus($suspend_status) {
        $this->suspend_status = $suspend_status;
    }

    public static function createSysAdminAccount($sysAdmin) {
        $conn = require '../db/db.php';

        if (!$conn instanceof mysqli) {
            die("Database connection failed.");
        }

        $stmt = $conn->prepare("INSERT INTO sysadmin (username, name, email, password, suspend_status) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $username = $sysAdmin->getUsername();
        $name = $sysAdmin->getName();
        $email = $sysAdmin->getEmail();
        $password = $sysAdmin->getPassword();
        $suspend_status = $sysAdmin->getSuspendStatus();

        $stmt->bind_param("ssssi", $username, $name, $email, $password, $suspend_status);
        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    public static function findByUsername($username) {
        $conn = require '../db/db.php';

        if (!$conn instanceof mysqli) {
            die("Database connection failed.");
        }

        $query = "SELECT * FROM sysadmin WHERE username = ?";
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
            return new self($user['id'], 'Admin', '', $user['username'], $user['name'], $user['email'], $user['password'], $user['suspend_status']);
        }

        return null;
    }
}

?>