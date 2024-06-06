<?php
$conn = require_once 'db/db.php'; 
session_start();

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

    }

    
    public function createBusinessOwnerAccount($BusinessOwner)
    {

    }

    // System Admin - View (Read) user account (join, where for id)
    public function viewAccount($id)
    {

    }

    // System Admin - Delete user account
    public function suspendAccount($user_id)
    {
        global $conn;
        $response = array(
            'success' => false, // False by default
            'message' => ''
        );
        $temp = true;
        try {
            // SQL query to delete the account based on user ID
            $delete = "UPDATE userAccount SET suspend=? WHERE user_id=?";
            $stmt = mysqli_prepare($conn, $delete);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . mysqli_error($conn));
            }

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "ii", $temp, $user_id); // Assuming userId is an integer

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
