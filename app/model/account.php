<?php
//$conn = require_once '../db/db.php'; 
include_once '../db/db.php'; 
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

    private $suspend_status;

    public function __construct($id = 0, $userName = "", $name = "", $email = "", $password = "")
    {
        $this->id = $id;
        $this->name = $name;
        $this->userName = $userName;
        $this->password = $password;
        $this->profile = "System Admin";
        $this->email = $email;
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
    
            $stmt->close();
        } catch (Exception $e) {
            error_log("Error creating SysAdmin account: " . $e->getMessage());
            $response['message'] = 'Database Error: ' . $e->getMessage();
        }

        // Return JSON response
        return json_encode($response);
    }

    
    public function createBusinessOwnerAccount($BusinessOwner)
    {
        global $conn;

        $response = array(
            'success' => false,
            'message' => ''
        );

        try {
            // SQL query to insert a new BusinessOwner account
            $insertBusinessOwner = "INSERT INTO businessowner (userName, name, email, type, password, company) VALUES (?, ?, ?, 'Business Owner', ?, ?)";
            $stmt = mysqli_prepare($conn, $insertBusinessOwner);
            if (!$stmt) {
                throw new Exception("Error preparing statement: ". mysqli_error($conn));
            }

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "sssss", $BusinessOwner->getUsername(), $BusinessOwner->getName(), $BusinessOwner->getEmail(), $BusinessOwner->getPassword(), $BusinessOwner->getCompany());

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = "BusinessOwner account created successfully!";
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

    // System Admin - View (Read) user account (join, where for id)
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
    public function updateAccount($user_id, $username, $password, $name, $email, $company, $profile)
    {
        global $conn;
        $response = array(
            'success' => false, // False by default
            'message' => ''
        );
        //$temp = true;
        try {
            // SQL query to update account
            $check = "SELECT username FROM userAccount where user_id = ?";
        
            $stmt = mysqli_prepare($conn, $check);

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "ii", $temp, $user_id); // Assuming userId is an integer

            // Execute the statement
            mysqli_stmt_execute($stmt);

            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $tempname);
            mysqli_stmt_fetch($stmt);  


            $check2 = "SELECT userName FROM userAccount where userName = ?";

            $stmt2 = mysqli_prepare($conn, $check);

            // Bind parameters
            mysqli_stmt_bind_param($stmt2, "jj", $temp2, $this->userName); // Assuming userId is an integer

            // Execute the statement
            mysqli_stmt_execute($stmt2);

            mysqli_stmt_store_result($stmt2);
            mysqli_stmt_bind_result($stmt2, $tempname2);
            mysqli_stmt_fetch($stmt2);


            if($tempname2 != $tempname){//admin2 and s - 201 admin2 and admin2 - 207 IGNORE
                if(mysqli_stmt_num_rows($stmt2) > 0){ //s>0 username taken -207,s=0 -202 IGNORE
                    $response['message'] = "Username taken";
                    return json_encode($response);
                }
            }


            // Update statement
            $update = "UPDATE userAccount SET userName=?, password=?, name=?, email=?, profile=? WHERE user_id=?";
            $stmt = mysqli_prepare($conn, $update);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . mysqli_error($conn));
            }

            // Bind parameters
            //mysqli_stmt_bind_param($stmt, "sssssssi", $this->userName, $this->password, $this->name, $this->email, $this->profile, $user_id);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Check if any row was affected
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    $response['success'] = true;
                    $response['message'] = "Account updated successfully!";
                } else {
                    $response['message'] = "Account not found.";
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
        $users = array();
        try {
            global $conn;
        
            if ($profile == 'System Admin'){
                $search = "SELECT * FROM sysadmin WHERE id=?";
            } else {
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

            //while ($row = mysqli_fetch_assoc($result)) {
            //    $users[] = $row;
            //}

            mysqli_close($conn);
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

            $stmt->close();
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
    
            $checkStmt->close();
    
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
            $stmt->close();
        } catch (Exception $e) {
            error_log("Error creating BusinessOwner account: " . $e->getMessage());
            $response['message'] = $e->getMessage() == 'Username already exists' ? 'Username already exists' : 'Database Error: ' . $e->getMessage();
        }
    
        return json_encode($response);
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

            $stmt->close();
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


    public function updateSubscriptionDetails(){

        return 0;

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