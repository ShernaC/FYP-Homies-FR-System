<?php

require_once '../Model/Profile.php';

class ProfileCreationController {
    public function createProfile($profileName, $description) {
        return Profile::createProfile($profileName, $description);
    }
}

?>