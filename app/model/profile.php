
<?php

//$conn = require_once '../db/db.php'; 
include_once '../db/db.php';

if (!$conn || !$conn instanceof mysqli) {
    die("Profile: Database connection not established.");
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Profile
{
    private int $id;
    private String $userProfile;
    private String $description;

    public function __construct($id = 0, $userProfile = "", $description = "")
    {
        $this->id = $id;
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
    public function viewProfile()
    {
        global $conn;

        $response = array(
            'success' => false, // False by default
            'message' => '',
            'profiles' => []
        );

        try {
            // SQL query to view all accounts
            $viewAllProfiles = "(SELECT * FROM profile);";
            $stmt = mysqli_prepare($conn, $viewAllProfiles);
            if (!$stmt) {
                throw new Exception("Error: ". mysqli_error($conn));
            }

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Fetch all rows into an array
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                    $response['profiles'][] = $row;
                }
                $response['success'] = true;
                $response['message'] = "Successfully retrieved all profiles.";
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

    // Get profile object by userProfile
    public static function getProfileByUser($userProfile)
    {
        global $conn;

        $response = array(
            'success' => false, // False by default
            'message' => '',
            'profile' => null
        );

        try {
            // SQL query to get profile by userProfile
            $getProfileQuery = "SELECT * FROM profile WHERE userProfile = ?";
            $stmt = mysqli_prepare($conn, $getProfileQuery);
            if (!$stmt) {
                throw new Exception("Error: " . mysqli_error($conn));
            }

            // Bind parameter
            mysqli_stmt_bind_param($stmt, "s", $userProfile);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Fetch the profile row
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);

                if ($row) {
                    $response['success'] = true;
                    $response['message'] = "Profile retrieved successfully.";
                    $response['profile'] = $row;
                } else {
                    $response['message'] = "Profile not found.";
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
                throw new Exception("Error preparing statement: " . mysqli_error($conn));
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
                throw new Exception("Error executing statement: " . mysqli_error($conn));
            }

            
        } catch (mysqli_sql_exception $e) {
            $response['message'] = "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
        }
*/
        // Return JSON response
        //return json_encode($response);
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
                throw new Exception("Error: " . mysqli_error($conn));
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
