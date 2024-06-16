
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
        $createAccount = new createAccountController();
        $result = $createAccount -> handleCreate();

        break;

    case 'update':
        // put logic for updating account here (calling function from model)
        echo "update account action";
        $accountId = $_POST['accountId'];
        $profile = $_POST['profile'];
        $username = $_POST['username'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $company = $_POST['company'];
        $password = $_POST['password'];
        
        break;

    case 'suspend':
        $accountId = $_POST['accountId'];
        $profile = $_POST['profile'];
        $suspendAccount = new suspendUserAccount();
        $result = $suspendAccount->suspendAccount($accountId, $profile);
        break;

    /*case 'search':
        $searchAccount = new searchUserAccount();
        $userData = $searchAccount->handleSearchRequest($accountId, $profile);
        break;*/

    default:
        //echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}

class createAccountController{
    function handleCreate() {
        $profile = $_POST['profile'];
        $username = $_POST['username'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $company = $_POST['company'];
        $password = $_POST['password'];
    
        $response = ['success' => false, 'message' => ''];
    
        try {
            if ($profile == 'System Admin') {
                $newAccount = new SysAdmin(0, $username, $name, $email, $password);
                $result = $newAccount->createSysAdminAccount($profile);
            } elseif ($profile == 'Business Owner') {
                $newAccount = new BusinessOwner(0, $username, $name, $email, $password);
                $newAccount->setCompany($company); 
                $result = $newAccount->createBusinessOwnerAccount($profile);
            } else {
                $response['message'] = 'Invalid profile type';
                throw new Exception('Invalid profile type');
            }
    
            $result = json_decode($result, true);
    
            if ($result['success']) {
                $response['success'] = true;
                $response['message'] = 'Account created successfully';
            } else {
                $response['message'] = isset($result['message']) ? $result['message'] : 'Account creation failed';
            }
        } catch (Exception $e) {
            error_log("Error during account creation: " . $e->getMessage());
            $response['message'] = 'Server error. Please try again later.';
        }
    
        return json_encode($response);
    }
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

    public function viewSystemAdminController(){
        $sysAdmin = new SysAdmin();
        $result = $sysAdmin->viewSysAdminAccounts();
        $result = json_decode($result, true);

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

    public function viewBusinessOwnerController(){
        $sysAdmin = new SysAdmin();
        $result = $sysAdmin->viewBusinessOwnerAccounts();
        $result = json_decode($result, true);

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

// Define the updateAccountPageController class
class updateAccountPageController{
    // Method to handle the update account functionality
    public function updateAccount($username)
    {
        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Extract form data
            $user_id = $_POST["id"];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $company = $_POST['company'];
            //$faceData = $_POST['faceData'];  // this also?
            $profile = trim($_POST['profile']);

            // Create an instance of the class depending on profile type
            if ($profile == "System Admin") {
                $userAccount = new SysAdmin();
            } else {
                $userAccount = new BusinessOwner();
            }
            
            // Encode the response as JSON and send it back
            return $userAccount->updateAccount($user_id, $username, $password, $name, $email, $company, $profile);
        }
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
    public function handleSearchRequest($accountId, $profile) {
        $sysAdmin = new SysAdmin();
        $userData = $sysAdmin->searchAccount($accountId, $profile);
        return $userData;
    }
}


?>
