<?php
//$conn = require_once '../db/db.php'; 
include_once '../db/db.php'; 
include_once '../model/subscription.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!$conn || !$conn instanceof mysqli) {
    die("Account: Database connection not established.");
}

class SysAdmin{

    private int $id;
    private String $password; 
    private String $userName;
    private String $name;
    private String $profile;
    private String $email;
    private int $otp;
    private $suspend_status;

    public function __construct($id = 0, $userName = "", $name = "", $email = "", $password = "", $otp=00000)
    {
        $this->id = $id;
        $this->name = $name;
        $this->userName = $userName;
        $this->password = $password;
        $this->profile = "System Admin";
        $this->email = $email;
        $this->otp = $otp;
        $this->suspend_status = false;
    }

    public function getID(){
        return $this->id;
    }

    public function getUsername(){
        return $this->userName;
    }

    public function getName(){
        return $this->name;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getProfile(){
        return $this->profile;
    }

    public function getOTP(){
        return $this->otp;
    }

    public function setID($id){
        $this->id = $id;
    }

    public function setUsername($userName){
        $this->userName = $userName;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setEmail($email){
        $this->email = $email;
    }


    function getSuspendStatus(){
        return $this->suspend_status;
    }


    function setSuspendStatus($status){
        $this->suspend_status = $status;
    }

    function setPassword($password){
        $this->password = $password;
    }

    function setOTP($otp){
        $this->otp = $otp;
    }

    public function createSysAdminAccount($profile)
    {
        global $conn;

        $response = array(
            'success' => false,
            'message' => ''
        );

        try {
            // SQL query to insert a new SysAdmin account
            error_log("Original password: " . $this->password);
            $hashedPassword = md5($this->password);
            error_log("MD5 hashed password: " . $hashedPassword);
            $stmt = $conn->prepare("INSERT INTO sysadmin (userName, name, email, profile, password) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }

            // Bind parameters
            $stmt->bind_param("sssss", $this->userName, $this->name, $this->email, $profile, $hashedPassword);
            error_log("Binding parameters: Username={$this->userName}, Name={$this->name}, Email={$this->email}, Password=******, Profile={$profile}");
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response['success'] = true;
                    $response['message'] = 'SysAdmin account created successfully';
                    error_log("SysAdmin account created successfully.");
                } else {
                    throw new Exception("No rows affected, insert failed.");
                }
            } else {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
    
            //$stmt->close();
        } catch (Exception $e) {
            error_log("Error creating SysAdmin account: " . $e->getMessage());
            $response['message'] = 'Database Error: ' . $e->getMessage();
        }

        // Return JSON response
        return json_encode($response);
    }

    // System Admin - View (Read) user account
    public function viewAccounts()
    {
        global $conn;

        $response = array(
            'success' => false,
            'message' => '',
            'accounts' => []
        );

        try {
            // SQL query to view all accounts
            $viewAllAccounts = "(SELECT * FROM sysadmin WHERE suspend_status=0) 
                                    UNION (SELECT id, userName, name, email, profile, password, suspend_status FROM businessowner 
                                    WHERE suspend_status=0);";
            $stmt = mysqli_prepare($conn, $viewAllAccounts);
            if (!$stmt) {
                throw new Exception("Error: ". mysqli_error($conn));
            }

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Fetch all rows into an array
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                    $response['accounts'][] = $row;
                }
                $response['success'] = true;
                $response['message'] = "Successfully retrieved all accounts.";
            } else {
                throw new Exception("Error executing statement: ". mysqli_error($conn));
            }

        } catch (mysqli_sql_exception $e) {
            $response['message'] = "Database Error: ". $e->getMessage();
        } catch (Exception $e) {
            $response['message'] = "Error: ". $e->getMessage();
        }

        // Return JSON response
        return json_encode($response);

    }

    public function viewSysAdminAccounts()
    {
        global $conn;

        $response = array(
            'success' => false,
            'message' => '',
            'accounts' => []
        );
    
        try {
            // SQL query to view all system admin accounts
            
            $viewAdmin = "SELECT * FROM sysadmin WHERE suspend_status=0";
            $stmt = mysqli_prepare($conn, $viewAdmin);
            if (!$stmt) {
                throw new Exception("Error: ". mysqli_error($conn));
            }

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Fetch all rows into an array
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                    $response['accounts'][] = $row;
                }
                $response['success'] = true;
                $response['message'] = "Successfully retrieved all accounts.";
            } else {
                throw new Exception("Error executing statement: ". mysqli_error($conn));
            }

        } catch (mysqli_sql_exception $e) {
            $response['message'] = "Database Error: ". $e->getMessage();
        } catch (Exception $e) {
            $response['message'] = "Error: ". $e->getMessage();
        }
    
        // Return JSON response
        return json_encode($response);
    }

    public function viewBusinessOwnerAccounts()
    {
        global $conn;

        $response = array(
            'success' => false,
            'message' => '',
            'accounts' => []
        );

        try {
            // SQL query to view all accounts
            $viewBusinessOwners = "SELECT * FROM businessowner WHERE suspend_status=0";
            $result = mysqli_query($conn, $viewBusinessOwners);
            
            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $response['accounts'][] = $row;
                }
                $response['success'] = true;
                $response['message'] = "Successfully retrieved all system admin accounts.";
            } else {
                throw new Exception("Error executing query: ". mysqli_error($conn));
            }
    
        } catch (mysqli_sql_exception $e) {
            $response['message'] = "Database Error: ". $e->getMessage();
        } catch (Exception $e) {
            $response['message'] = "Error: ". $e->getMessage();
        }
    
        // Return JSON response
        return json_encode($response);
    }

    // System Admin - Update user account
    function updateAccount($user_id, $userName, $name, $email, $profile, $company, $new_password)
    {
        global $conn;
        
        $response = array(
        'success' => false, // False by default
        'message' => ''
        );

        try {
            // Check if username and email already exist
            if ($profile=='System Admin'){
                $check_username = "SELECT id, username FROM sysadmin WHERE username = ?";
            }
            else{
                $check_username = "SELECT id, userName FROM businessowner WHERE userName = ?";
            }
        
            // Check username
            $stmt = mysqli_prepare($conn, $check_username);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . mysqli_error($conn));
            }

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "s", $userName);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            // Get the id from the result set
            mysqli_stmt_bind_result($stmt, $id, $curr_username);
            mysqli_stmt_fetch($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                if ($user_id != $id){
                    echo 'Username taken! </br>';
                    $response['message'] = "Username taken!";
                    return json_encode($response);
                }
            }
            
            // Check email
            if ($profile=='System Admin'){
                $check_email = "SELECT id, email FROM sysadmin WHERE email = ?";
            }
            else{
                $check_email = "SELECT id, email FROM businessowner WHERE email = ?";
            }

            // Prepare statement
            $stmt = mysqli_prepare($conn, $check_email);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . mysqli_error($conn));
            }

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            // Get the id from the result set
            mysqli_stmt_bind_result($stmt, $id, $curr_email);
            mysqli_stmt_fetch($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0 && $user_id !=$id) {
                    $response['message'] = "Email taken!";
                    return json_encode($response);
            }

            // Check if new password is the same as the previous one
            if ($new_password != "" or $new_password != null){
                // Hash new password
                $new_password_hash = md5($new_password);

                // Query db to get current password
                if ($profile=='System Admin')
                    $prev_password_query = "SELECT password FROM sysadmin WHERE id = ?";
                else
                    $prev_password_query = "SELECT password FROM businessowner WHERE id = ?";

                // Prepare statement
                $stmt = mysqli_prepare($conn, $prev_password_query);
                if (!$stmt) {
                    throw new Exception("Error preparing statement: " . mysqli_error($conn));
                }

                // Bind parameters
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                
                // Check if there are any changes to the password
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    // Get the current password hash
                    mysqli_stmt_bind_result($stmt, $prev_password_hash);
                    mysqli_stmt_fetch($stmt);
                }

                if ($profile=='System Admin')
                {
                    $update = "UPDATE sysadmin SET username=?, name=?, email=?, password=? WHERE id=?";
                    $stmt = mysqli_prepare($conn, $update);

                    mysqli_stmt_bind_param($stmt, "ssssi", $userName, $name, $email, $new_password_hash, $user_id);
                }
                else
                {
                    $update = "UPDATE businessowner SET username=?, name=?, email=?, password=?, company=? WHERE id=?";
                    $stmt = mysqli_prepare($conn, $update);
                    mysqli_stmt_bind_param($stmt, "sssssi", $userName, $name, $email, $new_password_hash, $company, $user_id);
                }
            } else {
                if ($profile=='System Admin')
                {
                    $update = "UPDATE sysadmin SET username=?, name=?, email=? WHERE id=?";
                    $stmt = mysqli_prepare($conn, $update);
                    mysqli_stmt_bind_param($stmt, "sssi", $userName, $name, $email, $user_id);
                }
                else
                {
                    echo 'Here: Business Owner 2';
                    $update = "UPDATE businessowner SET username=?, name=?, email=?, company=? WHERE id=?";
                    $stmt = mysqli_prepare($conn, $update);
                    mysqli_stmt_bind_param($stmt, "ssssi", $userName, $name, $email, $company, $user_id);
                }
            }
            
            // Update statement
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    $response['success'] = true;
                    $response['message'] = "Account updated successfully!";
                    
                } else {
                    $response['message'] = "Account not found.";
                }

                $response['test'] = $profile;
                    $response['test2'] = $company;
                    $response['msg'] = "hah";
                    $response['msg2'] = "Update Account in Controller: </br>";
                    $response['ID'] = "Account ID: " . $user_id ."</br>";
                    $response['username'] = "Username: " . $userName ."</br>";
                    $response['name'] = "Name: " . $name ."</br>";
                    $response['email'] = "Email: " . $email ."</br>";
                    $response['profile'] = "Profile: " . $profile ."</br>";
                    $response['company'] = "Company: " . $company ."</br>";
                    $response['password'] = "Password: " . $new_password ."</br>";
            } else {
                throw new Exception("Error executing statement: " . mysqli_error($conn));
            }

        } catch (mysqli_sql_exception $e) {
            $response['message'] = "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
        }

    return json_encode($response);
    }


    // System Admin - Suspend user account
    public function suspendAccount($accountId, $profile)
    {
        global $conn;
        $response = array(
            'success' => false, // False by default
            'message' => ''
        );
        //$temp = true;
        echo "suspend account in db";
        try {
            // SQL query to delete the account based on user ID
            if ($profile == 'System Admin'){
                $delete = "UPDATE sysadmin SET suspend_status=1 WHERE id=?";
            } else {
                $delete = "UPDATE businessowner SET suspend_status=1 WHERE id=?";
            }
            
            $stmt = mysqli_prepare($conn, $delete);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . mysqli_error($conn));
            }

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "i", $accountId); // Assuming userId is an integer

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Check if any row was affected
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    $response['success'] = true;
                    $response['message'] = "Account suspended successfully!";
                } else {
                    $response['message'] = "Account not found or already suspended.";
                }
            } else {
                throw new Exception("Error executing statement: " . mysqli_error($conn));
            }
            
        } catch (mysqli_sql_exception $e) {
            $response['message'] = "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
        }

        // Return JSON response
        return json_encode($response);
    }

    // System Admin - Search user account
    public function searchAccount($accountId, $profile)
    {
        try {
            global $conn;
        
            if ($profile == 'System Admin'){
                $search = "SELECT * FROM sysadmin WHERE id=?";
            } else if ($profile == 'Business Owner'){
                $search = "SELECT * FROM businessowner WHERE id=?";
            }

            $stmt = mysqli_prepare($conn, $search);
            if (!$stmt) {
                throw new Exception("Error: " . mysqli_error($conn));
            }

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "s", $accountId);

            // Set parameter and execute
            mysqli_stmt_execute($stmt);

            // Get result
            $result = mysqli_stmt_get_result($stmt);
            $user= mysqli_fetch_assoc($result);

            //mysqli_close($conn);
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        return $user;
    }

    public function login($username, $password) {
        global $conn;
        if (!$conn instanceof mysqli) {
            $conn = new mysqli('localhost', 'root', '123456', 'FaceRecognition');
        }
        $response = ['success' => false, 'message' => ''];

        try {
            error_log("Attempting to login for SysAdmin...");
            $hashedPassword = md5($password);
            error_log("Entered password: $password");
            error_log("Hashed password: $hashedPassword");

            $stmt = $conn->prepare("SELECT * FROM sysadmin WHERE userName = ? AND password = ?");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }

            $stmt->bind_param("ss", $username, $hashedPassword);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                error_log("Database password: " . $user['password']);
                $response['success'] = true;
                $response['message'] = 'Login successful';
                $response['data'] = $user; 
                error_log("Login successful for SysAdmin.");
            } else {
                $response['message'] = 'Invalid username or password';
                error_log("Invalid username or password for SysAdmin.");
            }

            //$stmt->close();
        } catch (Exception $e) {
            error_log("Error in SysAdmin login: " . $e->getMessage());
            $response['message'] = 'Database Error: ' . $e->getMessage();
        }

        return json_encode($response);
    }

}



class BusinessOwner{
 
    private $suspend_status;

    private int $id;
    private String $userName;
    private String $name;
    private String $email;
    private String $profile;
    //private Face faceData;
    private String $company;
    //private $subscription;
    private String $password;


    public function __construct($id = 0, $userName = "", $name = "", $email = "", $password = "", $company= "")
    {
        $this->id = $id;
        $this->name = $name;
        $this->userName = $userName;
        $this->password = $password;
        $this->email = $email;
        $this->profile = "Business Owner";
        $this->suspend_status = false;
        $this->company = $company;

        //$this->subscription = $subscription;
    }
    
    public function getSuspendStatus(){
        return $this->suspend_status;
    }

    public function getID(){
        return $this->id;
    }

    public function getUsername(){
        return $this->userName;
    }

    public function getName(){
        return $this->name;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getProfile(){
        return $this->profile;
    }

    public function getCompany(){
        return $this->company;
    }

    public function setID($id){
        $this->id = $id;
    }

    public function setName($name){
        $this->name = $name;
    }

    function setUserName($userName){
        $this->userName = $userName;
    }

    function setPassword($password){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $hash;
    }

    function setSuspendStatus($status){
        $this->suspend_status = $status;

    }

    function setEmail($email){
        $this->email = $email;
    }

    function setCompany($company){
        $this->company = $company;
    }

    // add subscription getset later

    public function createBusinessOwnerAccount($profile) {
        global $conn;
        $response = ['success' => false, 'message' => ''];
    
        try {
            // Check if the username already exists
            $checkStmt = $conn->prepare("SELECT COUNT(*) as count FROM businessowner WHERE userName = ?");
            if (!$checkStmt) {
                throw new Exception("Prepare statement failed for checking existence: " . $conn->error);
            }
    
            $checkStmt->bind_param("s", $this->userName);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            $row = $checkResult->fetch_assoc();
            
            if ($row['count'] > 0) {
                throw new Exception("Username already exists");
            }
    
            //$checkStmt->close();
    
            // Hash the password using MD5
            error_log("Original password: " . $this->password);
            $hashedPassword = md5($this->password);
            error_log("MD5 hashed password: " . $hashedPassword);
    
            $stmt = $conn->prepare("INSERT INTO businessowner (userName, name, email, profile, password, company) VALUES (?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
    
            $stmt->bind_param("ssssss", $this->userName, $this->name, $this->email, $profile, $hashedPassword, $this->company);
            error_log("Binding parameters: Username={$this->userName}, Name={$this->name}, Email={$this->email}, Password=******, Profile={$profile}, Company={$this->company}");
    
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response['success'] = true;
                    $response['message'] = 'BusinessOwner account created successfully';
                    error_log("BusinessOwner account created successfully.");
                } else {
                    throw new Exception("No rows affected, insert failed.");
                }
            } else {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            //$stmt->close();
        } catch (Exception $e) {
            error_log("Error creating BusinessOwner account: " . $e->getMessage());
            $response['message'] = $e->getMessage() == 'Username already exists' ? 'Username already exists' : 'Database Error: ' . $e->getMessage();
        }
    
        return json_encode($response);
    }

    function searchAccount($username)
    {
        try {
            global $conn;

            $search = "SELECT * FROM businessowner WHERE username=?";
            

            $stmt = mysqli_prepare($conn, $search);
            if (!$stmt) {
                throw new Exception("Error: " . mysqli_error($conn));
            }

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "s", $username);

            // Set parameter and execute
            mysqli_stmt_execute($stmt);

            // Get result
            $result = mysqli_stmt_get_result($stmt);
            $user= mysqli_fetch_assoc($result);

            //mysqli_close($conn);
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        return $user;
    }


    public function login($username, $password) {
        global $conn;
        if (!$conn instanceof mysqli) {
            $conn = new mysqli('localhost', 'root', '123456', 'FaceRecognition');
        }
        $response = ['success' => false, 'message' => ''];

        try {
            error_log("Attempting to login...");
            $hashedPassword = md5($password);
            error_log("Entered password: $password");
            error_log("Hashed password: $hashedPassword");

            $stmt = $conn->prepare("SELECT * FROM businessowner WHERE userName = ? AND password = ?");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }

            $stmt->bind_param("ss", $username, $hashedPassword);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                error_log("Database password: " . $user['password']);
                $response['success'] = true;
                $response['message'] = 'Login successful';
                $response['data'] = $user; 
                error_log("Login successful.");
            } else {
                $response['message'] = 'Invalid username or password';
                error_log("Invalid username or password.");
            }

            //$stmt->close();
        } catch (Exception $e) {
            error_log("Error logging in: " . $e->getMessage());
            $response['message'] = 'Database Error: ' . $e->getMessage();
        }

        return json_encode($response);
    }

    public function uploadFaceData($face){

        return 0;

    }

    public function viewSubscriptionDetails(){

        return 0;

    }


    function updateSubscriptionDetails($ownerId, $subscriptionId){
        global $conn;
        $response = ['success' => false, 'message' => ''];
        
        try {
            $stmt = $conn->prepare("UPDATE businessowner SET subscription_id = ? WHERE id = ?");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }

            $stmt->bind_param("ii", $subscriptionId, $ownerId);

            $subscriptionDetails = new subscriptionDetails();
            $result = $subscriptionDetails->updateSubscriptionDetails($ownerId, $subscriptionId);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0 && $result) {
                    $response['success'] = true;
                    $response['message'] = 'Subscription details updated successfully';
                } else {
                    $response['message'] = 'No rows affected, update failed';
                }
            } else {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            
        } catch (Exception $e) {
            $response['message'] = 'Database Error: ' . $e->getMessage();
        }
            
        return json_encode($response);
    }
    
    public function cancelSubscriptionDetails(){

        return 0;

    }



}

/*
class Users{
    private $id;
    private $username;
    private $name;
    private $email;
    private $phoneno;

    // Constructor
    public function __construct($id, $username, $name, $email, $phoneno){
        $this->id = $id;
        $this->username = $username;
        $this->name = $name;
        $this->email = $email;
        $this->phoneno = $phoneno;
    }

    public function getID(){
        return $this->id;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getName(){
        return $this->name;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getPhoneno(){
        return $this->phoneno;
    }

    public function setID($id){
        $this->id = $id;
    }

    public function setUsername($username){
        $this->username = $username;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function setPhoneno($phoneno){
        $this->phoneno = $phoneno;
    }
}

class BusinessOwner{
    private $company;
    //private $subscription;
    private $user;
    private $password;
    private $suspend_status;

    public function __construct($companyname, $id, $username, $name, $email, $phoneno, $password){
        $user = new Users($id, $username, $name, $email, $phoneno);
        $company = new Company($companyname);
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $this->company = $company;
        //$this->subscription = $subscription;
        $this->user = $user;
        $this->password = $hash;
        $this->suspend_status = false;
    }

    function getCompany(){
        return $this->company;
    }

    function getUser(){
        return $this->user;
    }
    
    function getSuspendStatus(){
        return $this->suspend_status;
    }

    function setCompany($company){
        $this->company = $company;
    }

    function setUser($user){
        $this->user = $user;
    }

    function setPassword($password){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $hash;
    }

    function setSuspendStatus($status){
        $this->suspend_status = $status;
    }
}

class EndUser{
    private $name;
    private $company;
    private $suspend_status;

    public function __construct($name, $companyname){
        $company = new Company($companyname);

        $this->name = $name;
        $this->company = $company;
        $this->suspend_status = false;
    }

    function getName(){
        return $this->name;
    }

    function getCompany(){
        return $this->company;
    }

    function getSuspendStatus(){
        return $this->suspend_status;
    }

    function setName($name){
        $this->name = $name;
    }

    function setCompany($company){
        $this->company = $company;
    }

    function setSuspendStatus($status){
        $this->suspend_status = $status;
    }
}

class Company{
    private $name;

    public function __construct($name){
        $this->name = $name;
    }

    function getName(){
        return $this->name;
    }

    function setName($name){
        $this->name = $name;
    }
}

class Subscription{
    private $name;
    private $price;
    private $description;

    public function __construct($name, $price, $description){
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }

    function getName(){
        return $this->name;
    }

    function getPrice(){
        return $this->price;
    }

    function getDescription(){
        return $this->description;
    }

    function setName($name){
        $this->name = $name;
    }

    function setPrice($price){
        $this->price = $price;
    }

    function setDescription($description){
        $this->description = $description;
    }
}
*/
?>