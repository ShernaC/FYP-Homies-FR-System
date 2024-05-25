<?php

$con = require_once('index.php');

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

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $hash;
        
        $this->email = $email;

        $this->suspend_status = false;

       // $this->db = new database();
       
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


    public function createSysAdminAccount($SysAdmin)
    {
        /*
        $response = array(
            'success' => false, // False by default
            'message' => ''
        );

        // sql thing not settled yet
        try {
            // Establish connection
            $con = mysqli_connect($this->jdbcurl, $this->jdbcname, $this->jdbcpass, $this->dbname);
            if (!$con) {
                throw new Exception("Connection failed: " . mysqli_connect_error());
            }
        
            $check = "SELECT username FROM userAccount where username = ?";
            $ch = mysqli_prepare($con,$check);
            mysqli_stmt_bind_param($ch,"s", $this->userName);
            mysqli_stmt_execute($ch);
            mysqli_stmt_store_result($ch);
            
            if(mysqli_stmt_num_rows($ch) > 0){
                $response['message'] = "Username taken";
                return json_encode($response);
            }

            $insert = "INSERT INTO userAccount (username, password, name, userProfile) VALUES (?, ?, ?, ?, ?, ?, ?)"; //need edit later
            $stmt = mysqli_prepare($con, $insert);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . mysqli_error($con));
            }

            // Bind parameters  //need edit later
            mysqli_stmt_bind_param($stmt, "sssssss", $this->userName, $this->password, $this->firstName, $this->lastName, $this->dateofBirth, $this->address, $this->userProfile);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = "Account created successfully!";
            } else {
                throw new Exception("Error executing statement: " . mysqli_error($con));
            }
            
            // Close statement and connection
            mysqli_stmt_close($stmt);

            mysqli_close($con);

        } catch (mysqli_sql_exception $e) {
            $response['message'] = "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
        }

        // Return JSON response
        return json_encode($response);
        */

    }

    
    public function createBusinessOwnerAccount($BusinessOwner)
    {
        /*
        $response = array(
            'success' => false, // False by default
            'message' => ''
        );

        // sql thing not settled yet
        try {
            // Establish connection
            $con = mysqli_connect($this->jdbcurl, $this->jdbcname, $this->jdbcpass, $this->dbname);
            if (!$con) {
                throw new Exception("Connection failed: " . mysqli_connect_error());
            }
        
            $check = "SELECT username FROM userAccount where username = ?";
            $ch = mysqli_prepare($con,$check);
            mysqli_stmt_bind_param($ch,"s", $this->userName);
            mysqli_stmt_execute($ch);
            mysqli_stmt_store_result($ch);
            
            if(mysqli_stmt_num_rows($ch) > 0){
                $response['message'] = "Username taken";
                return json_encode($response);
            }

            $insert = "INSERT INTO userAccount (username, password, name, userProfile) VALUES (?, ?, ?, ?, ?, ?, ?)"; //need edit later
            $stmt = mysqli_prepare($con, $insert);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . mysqli_error($con));
            }

            // Bind parameters  //need edit later
            mysqli_stmt_bind_param($stmt, "sssssss", $this->userName, $this->password, $this->firstName, $this->lastName, $this->dateofBirth, $this->address, $this->userProfile);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = "Account created successfully!";
            } else {
                throw new Exception("Error executing statement: " . mysqli_error($con));
            }
            
            // Close statement and connection
            mysqli_stmt_close($stmt);

            mysqli_close($con);

        } catch (mysqli_sql_exception $e) {
            $response['message'] = "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
        }

        // Return JSON response
        return json_encode($response);
        */

    }

    // System Admin - View (Read) user account (join, where for id)
    public function viewAccount($id)
    {
        $users = array();
        try {
            

            $view = "SELECT * FROM userAccount WHERE user_id=?";
            $stmt = mysqli_prepare($con, $view);
            if (!$stmt) {
                throw new Exception("Error: " . mysqli_error($con));
            }

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "s", $user_id);

            // Set parameter and execute
            mysqli_stmt_execute($stmt);

            // Get result
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }

            
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return $users;
    }

    // System Admin - Update user account
    public function updateAccount($id)
    {
        $response = array(
            'success' => false, // False by default
            'message' => ''
        );

        try {
            
            $check = "SELECT username FROM userAccount where user_id = ?";
            $ch = mysqli_prepare($con,$check);
            mysqli_stmt_bind_param($ch,"i", $user_id);
            mysqli_stmt_execute($ch);
            mysqli_stmt_store_result($ch);
            mysqli_stmt_bind_result($ch, $tempname);
            mysqli_stmt_fetch($ch);           
            
            $check2 = "SELECT username FROM userAccount where username = ?";
            $ch2 = mysqli_prepare($con,$check2);
            mysqli_stmt_bind_param($ch2,"s", $this->userName);
            mysqli_stmt_execute($ch2);
            mysqli_stmt_store_result($ch2);
            mysqli_stmt_bind_result($ch2, $tempname2); 
            mysqli_stmt_fetch($ch2);

            if($tempname2 != $tempname){//admin2 and s - 201 admin2 and admin2 - 207
                if(mysqli_stmt_num_rows($ch2) > 0){ //s>0 username taken -207,s=0 -202
                    $response['message'] = "Username taken";
                    return json_encode($response);
                }
            }

            // Update statement  //need edit later
            $update = "UPDATE userAccount SET username=?, password=?, firstName=?, lastName=?, dateofBirth=?, address=?, userProfile=? WHERE user_id=?";
            $stmt = mysqli_prepare($con, $update);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . mysqli_error($con));
            }

            // Bind parameters  //need edit later
            mysqli_stmt_bind_param($stmt, "sssssssi", $this->userName, $this->password, $this->firstName, $this->lastName, $this->dateofBirth, $this->address, $this->userProfile, $user_id);

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
                throw new Exception("Error executing statement: " . mysqli_error($con));
            }

        
        } catch (mysqli_sql_exception $e) {
            $response['message'] = "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
        }

        // Return JSON response
        return json_encode($response);
    }

    // System Admin - Delete user account
    public function suspendAccount($user_id)
    {
        $response = array(
            'success' => false, // False by default
            'message' => ''
        );
        $temp = 1;
        try {
            

            // SQL query to delete the account based on user ID
            $delete = "UPDATE userAccount SET suspend=? WHERE user_id=?";
            $stmt = mysqli_prepare($con, $delete);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . mysqli_error($con));
            }

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "ii",$temp, $user_id); // Assuming userId is an integer

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
                throw new Exception("Error executing statement: " . mysqli_error($con));
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
            // Establish connection
            $con = mysqli_connect($this->jdbcurl, $this->jdbcname, $this->jdbcpass, $this->dbname);
            if (!$con) {
                throw new Exception("Connection failed: " . mysqli_connect_error());
            }

            $search = "SELECT * FROM userAccount WHERE username=?";
            $stmt = mysqli_prepare($con, $search);
            if (!$stmt) {
                throw new Exception("Error: " . mysqli_error($con));
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

            mysqli_close($con);
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return $users;
    }


    /* ignore
    public function authentication($userName, $password, $type) {  
        $id = null;
        try {
            // Establish connection
            $con = mysqli_connect($this->jdbcurl, $this->jdbcname, $this->jdbcpass, $this->dbname);
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }
    
            // Prepare SQL statement
            $search = "SELECT user_id FROM useraccount WHERE username=? AND password=? AND userProfile=? AND suspend IS NULL";
            $ps = mysqli_prepare($con, $search);
            if (!$ps) {
                die("Preparation failed: " . mysqli_error($con));
            }
    
            // Bind parameters
            mysqli_stmt_bind_param($ps, "sss", $userName, $password, $type);
    
            // Execute statement
            mysqli_stmt_execute($ps);
    
            // Get result
            $result = mysqli_stmt_get_result($ps);
    
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $id = $row['user_id'];
            }
    
            // Close statement and connection
            mysqli_stmt_close($ps);
            mysqli_close($con);
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        }
    
        return $id;
    }
    */
}



class BusinessOwner{
 
    private $suspend_status;

    private int $id;
    private String $userName;
    private String $name;
    private String $email;
    //private Face faceData;
    //private $company;
    //private $subscription;
    private String $password;


    public function __construct($id = 0, $userName = "", $name = "", $email = "", $password = "")
    {
        $this->id = $id;
        $this->name = $name;
        $this->userName = $userName;

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $hash;
        
        $this->email = $email;

        
        $this->suspend_status = false;
       
       
        //$company = new Company($companyname);
        

        //$this->company = $company;
        //$this->subscription = $subscription;
       
    }

    
/*
    function getCompany(){
        return $this->company;
    }
*/

    
function getSuspendStatus(){
    return $this->suspend_status;
}

    /*
    function setCompany($company){
        $this->company = $company;
    }
*/

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



}





?>