<?php
// Include the UserProfile class
require_once('../model/Profile.php');

// Define the updateProfilePageController class
class updateProfilePageController
{
    // Method to handle the update Profile functionality
    public function updateProfile()
    {
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Extract form data
            $userProfile = trim($_POST['userProfile']);
            $updatedUserProfile = trim($_POST['updatedUserProfile']);
            $updatedDescription = $_POST['updatedDescription'];

            // Create an instance of the Profile class
            $profile = new Profile($updatedUserProfile, $updatedDescription);

            // Encode the response as JSON and send it back
            echo $profile->updateProfile($userProfile);
        }
    }
}

// Instantiate the updateProfilePageController class
$updateProfilePageController = new updateProfilePageController();

// Call the updateProfile method
$updateProfilePageController->updateProfile();
?>
