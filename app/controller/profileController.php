<?php
include_once '../model/profile.php';

// Start the session if not already started
if (!isset($_SESSION)) {
    session_start();
}

$action = isset($_POST['action'])? $_POST['action'] : '';
if ($action === 'update'){
    $userProfile = $_POST['userProfile'];
    $description = $_POST['description'];
    $updateProfile = new updateProfileController();
    $result = $updateProfile->updateProfile($userProfile, $description);
    echo $result;
}


// Define the viewProfilePageController class
class viewProfilePageController
{
    // Method to handle the view profile functionality
    public function viewProfile()
    {
        // Create an instance of the Profile class
        $profile = new Profile();

        // Call the viewProfile method of the Profile class
        $view = $profile->viewProfile();
        $view = json_decode($view, true);

        // Initialize the response array
        $response = array(
            "success" => false,
            "message" => "Failed to retrieve profiles",
            "profiles" => []
        );

        // Check if the decoded view is valid and contains the 'profiles' key
        if ($view && isset($view['profiles'])) {
            $response['success'] = true;
            $response['profiles'] = $view['profiles'];
            $response['message'] = "Successfully retrieved all profiles";
        } else {
            // Optionally, you can also handle specific error messages from the $view
            if (isset($view['message'])) {
                $response['message'] = $view['message'];
            }
        }

        // Encode the response as JSON and send it back
        return json_encode($response);
    }

    // Method to handle the view single profile functionality
    public function viewSingleProfile($userProfile)
    {
        // Create an instance of the Profile class
        $profile = new Profile();

        // Call the getProfileByUser method of the Profile class
        $view = $profile->getProfileByUser($userProfile);
        $view = json_decode($view, true);

        // Initialize the response array
        $response = array(
            "success" => false,
            "message" => "Failed to retrieve profile",
            "profile" => null
        );

        // Check if the decoded view is valid and contains the 'profile' key
        if ($view && isset($view['profile'])) {
            $response['success'] = true;
            $response['profile'] = $view['profile'];
            $response['message'] = "Successfully retrieved profile";
        } else {
            // Optionally, you can also handle specific error messages from the $view
            if (isset($view['message'])) {
                $response['message'] = $view['message'];
            }
        }

        // Encode the response as JSON and send it back
        return json_encode($response);
    }

}

// Define the updateProfileController class
class updateProfileController {
    // Method to handle the update profile functionality
    public function updateProfile($userProfile, $description)
    {
            // Create an instance of the Profile class
            $profile = new Profile();

            // Encode the response as JSON and send it back
            $result = $profile->updateProfile($userProfile, $description);
            return $result;
    
    } 
}