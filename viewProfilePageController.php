<?php
// Include the Userprofile class
//require_once('../model/Profile.php');

// Define the viewProfilePageController class
class viewProfilePageController
{
    // Method to handle the view profile functionality
    public function viewProfile()
    {
        // Check if the form is submitted and if the username is set
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve the submitted username
            $userProfile = $_POST['userProfile'];

            // Create an instance of the profile class
            $profile = new Profile();

            // Call the view profile method of the profile class
            $view = $profile->viewProfile($userProfile);

            // Check the view result
                $response = array(
                    "success" => true,
                    "data" => $view
                );

            // Encode the response as JSON and send it back
            echo json_encode($response);
        }
    }
}

// Instantiate the viewProfilePageController class
$viewProfilePageController = new viewProfilePageController();

// Call the viewprofile method (from Profile.php)
$viewProfilePageController->viewProfile();
