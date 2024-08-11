<?php
include_once '../db/db.php'; 

class Company{
    private $companyName;
    private $password;
    private $subsciption_name;

    public function __construct($companyName="", $password="", $subsciption_name=""){
        $this->companyName = $companyName;
        $this->password = $password;
        $this->subsciption_name = $subsciption_name;
    }

    public function getCompanyName(){
        return $this->companyName;
    }

    public function getSubscriptionName(){
        return $this->subsciption_name;
    }

    public function setCompanyName($companyName){
        $this->companyName = $companyName;
    }

    public function setSubscriptionName($subsciption_name){
        $this->subsciption_name = $subsciption_name;
    }

    public function createCompanyDetails($companyName, $password, $subsciption_name){
        global $conn;
        $response = ['success' => false, 'message' => ''];

        try{
            $hashedPassword = md5($password);

            $stmt = $conn->prepare("INSERT INTO company (name, password, subscription_name) VALUES (?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }

            $stmt->bind_param("sss", $companyName, $hashedPassword, $subsciption_name);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $response['success'] = true;
                $response['message'] = 'Company details created successfully';
                error_log("Company details created successfully.");
            } else {
                $response['message'] = 'Failed to create company details';
                error_log("Failed to create company details.");
            }

            //$stmt->close();
        } catch (Exception $e) {
            error_log("Error in createCompanyDetails: " . $e->getMessage());
            $response['message'] = 'Database Error: ' . $e->getMessage();
        }

        return json_encode($response);
    }

    public function login($companyName, $password){
        global $conn;
        $response = ['success' => false, 'message' => ''];

        try {
            $hashedPassword = md5($password);

            $stmt = $conn->prepare("SELECT * FROM businessowner WHERE company = ? AND company_code = ?");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }

            $stmt->bind_param("ss", $companyName, $hashedPassword);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $response['success'] = true;
                $response['message'] = 'Login successful';
                error_log("Login successful.");
            } else {
                $response['message'] = 'Invalid username or password';
                error_log("Invalid username or password.");
            }

            //$stmt->close();
        } catch (Exception $e) {
            error_log("Error in login: " . $e->getMessage());
            $response['message'] = 'Database Error: ' . $e->getMessage();
        }

        return json_encode($response);
    }

}

?>