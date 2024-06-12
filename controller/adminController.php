<?php
include_once '../model/account.php';

// Start the session if not already started
if (!isset($_SESSION)) {
    session_start();
}

// Determine the action based on the AJAX request
$action = isset($_POST['action'])? $_POST['action'] : '';

switch ($action) {
    case 'create':
        handleCreateAccount();
        break;
    case 'suspend':
        echo "suspend account action";
        $accountId = $_POST['accountId'];
        $profile = $_POST['profile'];
        $suspendAccount = new suspendUserAccount();
        $result = $suspendAccount->suspendAccount($accountId, $profile);
        break;
    case 'search':
        $searchAccount = new searchUserAccount();
        $userData = $searchAccount->handleSearchRequest($_POST['search']);
        break;
    default:
        //echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
    }
    function handleCreateAccount() {
        $profile = $_POST['profile'];
        $username = $_POST['username'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $company = isset($_POST['company']) ? $_POST['company'] : '';
        $password = $_POST['password'];
    
        if ($profile === 'System Admin') {
            $sysAdmin = new SysAdmin(0, $username, $name, $email, $password);
            $response = $sysAdmin->createSysAdminAccount($profile);
        } elseif ($profile === 'Business Owner') {
            $businessOwner = new BusinessOwner(0, $username, $name, $email, $password);
            $businessOwner->setCompany($company);
            $response = $businessOwner->createBusinessOwnerAccount($profile);
        } else {
            $response = json_encode(['success' => false, 'message' => 'Invalid profile type']);
        }
    
        echo $response;
    }


// Define the viewAccountPageController class
class viewAccountController
{
    // Method to handle the view account functionality
    public function viewAccount()
    {
        // Create an instance of the SysAdmin class
        $sysAdmin = new SysAdmin();

        // Call the get_accounts method of the SysAdmin class
        $result = $sysAdmin->viewAccounts();
        $result = json_decode($result, true);
        //echo $result;

        // Check the accounts result
        if (!empty($result)) {
            $response = array(
                "success" => true,
                "message" => "Successfully retrieved all accounts",
                "accounts" => $result['accounts']
            );
        } else {
            $response = array(
                "success" => false,
                "message" => "Failed to retrieve accounts"
            );
        }
        
        // Encode the response as JSON and send it back
        //echo json_encode($response);
        return json_encode($response);
    }

}

class suspendUserAccount {
    public function suspendAccount($accountId, $profile) {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            global $conn;
            echo "suspend account in controller";
            $sysAdmin = new SysAdmin();
            $result = $sysAdmin->suspendAccount($accountId, $profile);        
            return $result;
        }  
    }
}

class searchUserAccount{
    private $entity;

    public function handleSearchRequest($search){
        global $conn;
        $sysAdmin = new SysAdmin($conn);

        if (isset($_POST['search'])){
            $userData = $sysAdmin->searchAccount($search);
            return $userData;
        }
    }
}


?>