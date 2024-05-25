<?php
// Profile.php (Model)

$con = require_once('index.php');
class Profile
{
    private int $id;
    private String $userProfile;
    private String $description;

    /*
    private $jdbcurl;
    private $jdbcname;
    private $jdbcpass;
    private $dbname;
    private $db;
    */

    public function __construct($userProfile = "", $description = "")
    {
        $this->userProfile = $userProfile;
        $this->description = $description;

       /*
        $this->db = new database();
        $this->jdbcurl = $this->db->getURL();
        $this->jdbcname = $this->db->getName();
        $this->jdbcpass = $this->db->getPass();
        $this->dbname = $this->db->getDbname();
        */
    }

    public function getuserProfile()
    {
        return $this->userProfile;
    }


    // System Admin - View (read) user profile
    public function viewProfile($userProfile)
    {
        $profiles = array();
        /*try {
            
            $view = "SELECT * FROM useraccount 
                JOIN userProfile ON useraccount.userProfile = userProfile.userProfile // need to change (JOIN and WHERE no need, only for search)
                WHERE userProfile.userProfile=?";
            $stmt = mysqli_prepare($con, $view);
            if (!$stmt) {
                throw new Exception("Error: " . mysqli_error($con));
            }

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "s", $userProfile);

            // Set parameter and execute
            mysqli_stmt_execute($stmt);

            // Get result
            $result = mysqli_stmt_get_result($stmt);

            while ($row = mysqli_fetch_assoc($result)) {
                $profiles[] = $row;
            }

            mysqli_close($con);
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        */
        return $profiles;
    }

    // System Admin - Update user profile
    public function updateProfile($userProfile)
    {
        /*
        $response = array(
            'success' => false, // False by default
            'message' => ''
        );
        */

/*
        try {

            $check = "SELECT userProfile FROM userProfile where userProfile = ?";
            $ch = mysqli_prepare($con,$check);
            mysqli_stmt_bind_param($ch,"s", $this->userProfile);
            mysqli_stmt_execute($ch);
            mysqli_stmt_store_result($ch);
            mysqli_stmt_bind_result($ch, $tempname);
            mysqli_stmt_fetch($ch);           

        

            // Update statement
            $update = "UPDATE userProfile SET description=? WHERE userProfile=?";
            $stmt = mysqli_prepare($con, $update);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . mysqli_error($con));
            }

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "sss", $this->userProfile, $this->description, $userProfile);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Check if any row was affected
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    $response['success'] = true;
                    $response['message'] = "Profile updated successfully!";
                }
            } else {
                throw new Exception("Error executing statement: " . mysqli_error($con));
            }

            
        } catch (mysqli_sql_exception $e) {
            $response['message'] = "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
        }
*/
        // Return JSON response
        return json_encode($response);
    }



    // System Admin - Search user profile
    public function searchProfile($userProfile)
    {
        $users = array();
        /*
        try {
            
            $search = "SELECT * FROM userProfile WHERE userProfile=?";
            $stmt = mysqli_prepare($con, $search);
            if (!$stmt) {
                throw new Exception("Error: " . mysqli_error($con));
            }

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "s", $userProfile);

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
        */
        return $users;

    }
    
}
