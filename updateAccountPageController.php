<?php
// Include the UserAccount class
require_once('../model/Account.php'); // ?

// Define the updateAccountPageController class
class updateAccountPageController
{
    // Method to handle the update account functionality
    public function updateAccount()
    {
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Extract form data
            $user_id = $_POST["id"];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $firstName = $_POST['name'];
            $email = $_POST['email'];
            $faceData = $_POST['faceData'];  // this also?
            $profile = trim($_POST['profile']);

            // Create an instance of the Account class
            $userAccount = new Account(0, $username, $name, $email, $profile, $faceData, $password, $phoneNo);

            // Encode the response as JSON and send it back
            echo $userAccount->updateAccount($user_id);
        }
    }
}

// Instantiate the updateAccountPageController class
$updateAccountPageController = new updateAccountPageController();

// Call the updateAccount method
$updateAccountPageController->updateAccount();
?>
