<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json; charset=utf-8'); // Ensure JSON content type


include_once(__DIR__ . '/../model/profile.php');

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determine the action based on the AJAX request
$action = isset($_POST['action']) ? $_POST['action'] : '';

// Debugging output to check the action value
echo "Action received: " . $action . "\n";

switch ($action) {
    case 'update':
        $id = $_POST['id'];
        $userProfile = $_POST['userProfile'];
        $description = $_POST['description'];

        $updateProfile = new updateProfileController();
        $result = $updateProfile->updateProfile($id, $userProfile, $description);
        
        echo json_encode($result);
        break;

    /*case 'suspend':
        $accountId = $_POST['accountId'];
        $profile = $_POST['profile'];
        $suspendAccount = new suspendUserAccount();
        $result = $suspendAccount->suspendAccount($accountId, $profile);
        break;
    */

    /*case 'search':
        $searchAccount = new searchUserAccount();
        $userData = $searchAccount->handleSearchRequest($accountId, $profile);
        break;
    */

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action (switch statement)']);
        break;
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
    public function updateProfile($id, $userProfile, $description)
    {
        $profile = new Profile();
        // Encode the response as JSON and send it back
        $result = $profile->updateProfile($id, $userProfile, $description);
        return $result; 
    } 
}
