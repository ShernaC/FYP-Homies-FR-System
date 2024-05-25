<?php
// Include the UserAccount class
require_once('../model/Account.php');

// Define the viewAccountPageController class
class viewAccountPageController
{
    // Method to handle the view account functionality
    public function viewAccount()
    {
        // Check if the form is submitted and if the username is set
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve the submitted username
            $id = $_POST['id'];

            // Create an instance of the Account class
            $userAccount = new Account();

            // Call the view account method of the Account class
            $view = $userAccount->viewAccount($id);

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

// Instantiate the viewAccountController class
$viewAccountPageController = new viewAccountPageController();

// Call the viewAccount method
$viewAccountPageController->viewAccount();
