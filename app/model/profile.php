<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = require_once(__DIR__ .'/../db/db.php'); 

if (!$conn || !$conn instanceof mysqli) {
    die("Profile: Database connection not established.");
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
    public function updateProfile($id, $userProfile, $description)
    {
       
        global $conn;
        
        $response = array(
        'success' => false, // False by default
        'message' => ''
        );

        try {
             // Debugging output
            echo $id;
            echo $userProfile;
            echo $description, '<br/>';



            // Prepare the update statement
            $update = "UPDATE profile SET userProfile=?, description=? WHERE id=?";
            $stmt = mysqli_prepare($conn, $update);

            if (!$stmt) {
                throw new Exception("Error preparing statement: " . mysqli_error($conn));
            }    

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "ssssi", $userProfile, $description, $id);
        
            
            
            // Execute the update statement
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    $response['success'] = true;
                    $response['message'] = "Description updated successfully!";
                } else {
                    $response['message'] = "---------------";
                }
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