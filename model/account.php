<?php
$conn = require_once '../db/db.php'; 

class SysAdmin{

    private int $id;
    private String $password; 
    private String $userName;
    private String $name;
    private String $email;

    private $suspend_status;

    public function __construct($id = 0, $userName = "", $name = "", $email = "", $password = "")
    {
        $this->id = $id;
        $this->name = $name;
        $this->userName = $userName;

        // $hash = password_hash($password, PASSWORD_DEFAULT);
        // $this->password = $hash;
        $this->password = $password;
        
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
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $hash;
    }


    public function createSysAdminAccount($profile) {
        global $conn;
        $response = ['success' => false, 'message' => ''];
    
        try {
            error_log("Original password: " . $this->password);
            $hashedPassword = md5($this->password);
            error_log("MD5 hashed password: " . $hashedPassword);
            $stmt = $conn->prepare("INSERT INTO sysadmin (userName, name, email, password, profile) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("sssss", $this->userName, $this->name, $this->email, $hashedPassword, $profile);
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
    public function searchAccount($username)
    {
        $users = array();
        try {
            global $conn;
        
            $search = "SELECT * FROM userAccount WHERE username=?";
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

            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }

            mysqli_close($conn);
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return $users;
    }
    /*if (!$conn instanceof mysqli) {
        $conn = new mysqli('localhost', 'root', '123456', 'FaceRecognition');
    }
        */
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
    //private Face faceData;
    private $company;
    //private $subscription;
    private String $password;


    public function __construct($id = 0, $userName = "", $name = "", $email = "", $password = "")
    {
        $this->id = $id;
        $this->name = $name;
        $this->userName = $userName;

        // $hash = password_hash($password, PASSWORD_DEFAULT);
        // $this->password = $hash;
        $this->password = $password;
        
        $this->email = $email;

        
        $this->suspend_status = false;
       
       
        //$company = new Company($companyname);
        

        //$this->company = $company;
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
    public function setEmail($email){
        $this->email = $email;
    }

    public function setCompany($company){
        $this->company = $company;
    }

    public function createBusinessOwnerAccount($profile) {
        global $conn;
        $response = ['success' => false, 'message' => ''];
    
        try {

            error_log("Original password: " . $this->password);
            $hashedPassword = md5($this->password);
            error_log("MD5 hashed password: " . $hashedPassword);
            $stmt = $conn->prepare("INSERT INTO businessowner (userName, name, email, password, profile, company) VALUES (?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
    
            $stmt->bind_param("ssssss", $this->userName, $this->name, $this->email, $hashedPassword, $profile, $this->company);
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
            $response['message'] = 'Database Error: ' . $e->getMessage();
        }
    
        return json_encode($response);
    }

    // add subscription getset later
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

    /*if (!$conn instanceof mysqli) {
        $conn = new mysqli('localhost', 'root', '123456', 'FaceRecognition');
    }
        */
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

}
?>