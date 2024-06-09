<?php

class Profile {
    private $id;
    private $userProfile;
    private $description;

    public function __construct($id, $userProfile, $description) {
        $this->id = $id;
        $this->userProfile = $userProfile;
        $this->description = $description;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUserProfile() {
        return $this->userProfile;
    }

    public function setUserProfile($userProfile) {
        $this->userProfile = $userProfile;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public static function createProfile($userProfile, $description) {
        $conn = require '../db/db.php';
        if (!$conn instanceof mysqli) {
            die("Database connection failed.");
        }

        $stmt = $conn->prepare("INSERT INTO profiles (userProfile, description) VALUES (?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ss", $userProfile, $description);
        $stmt->execute();

        $result = $stmt->affected_rows > 0;
        $stmt->close();
        $conn->close();

        return $result ? "Profile created successfully." : "Error creating profile.";
    }
}

?>