<?php
// Profile.php (Model)

//require_once('database.php');
class Profile
{
    private String $userProfile;
    private String $description;

    private $jdbcurl;
    private $jdbcname;
    private $jdbcpass;
    private $dbname;
    private $db;

    public function __construct($userProfile = "", $description = "")
    {
        $this->userProfile = $userProfile;
        $this->description = $description;

       // $this->db = new database();
        $this->jdbcurl = $this->db->getURL();
        $this->jdbcname = $this->db->getName();
        $this->jdbcpass = $this->db->getPass();
        $this->dbname = $this->db->getDbname();
    }

    public function getuserProfile()
    {
        return $this->userProfile;
    }

    // System Admin - Create user profile
    public function createProfile()
    {
        $response = array(
            'success' => false, // False by default
            'message' => ''
        );
/*
        try {
            // Establish connection
            $con = mysqli_connect($this->jdbcurl, $this->jdbcname, $this->jdbcpass, $this->dbname);
            if (!$con) {
                throw new Exception("Connection failed: " . mysqli_connect_error());
            }

            $check = "SELECT userProfile FROM userProfile where userProfile = ?";
            $ch = mysqli_prepare($con,$check);
            mysqli_stmt_bind_param($ch,"s", $this->userProfile);
            mysqli_stmt_execute($ch);
            mysqli_stmt_store_result($ch);
            mysqli_stmt_bind_result($ch, $tempname);
            mysqli_stmt_fetch($ch);           

            if(mysqli_stmt_num_rows($ch) > 0){ //check if name taken
                $response['message'] = "Create unsuccessful, Profile Name Exist.";
                return json_encode($response);
            }

            // Insert new value
            $insert = "INSERT INTO userProfile (userProfile, description) VALUES (?, ?)";
            $stmt = mysqli_prepare($con, $insert);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . mysqli_error($con));
            }

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "ss", $this->userProfile, $this->description);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $response['success'] = true;
                $response['message'] = "Profile created successfully!";
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
*/
        // Return JSON response
        return json_encode($response);
    }

    // System Admin - View (read) user profile
    public function viewProfile($userProfile)
    {
        $profiles = array();
        /*try {
            // Establish connection
            $con = mysqli_connect($this->jdbcurl, $this->jdbcname, $this->jdbcpass, $this->dbname);
            if (!$con) {
                throw new Exception("Connection failed: " . mysqli_connect_error());
            }

            $view = "SELECT * FROM useraccount 
                JOIN userProfile ON useraccount.userProfile = userProfile.userProfile
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
        $response = array(
            'success' => false, // False by default
            'message' => ''
        );
/*
        try {
            // Establish connection
            $con = mysqli_connect($this->jdbcurl, $this->jdbcname, $this->jdbcpass, $this->dbname);
            if (!$con) {
                throw new Exception("Connection failed: " . mysqli_connect_error());
            }

            $check = "SELECT userProfile FROM userProfile where userProfile = ?";
            $ch = mysqli_prepare($con,$check);
            mysqli_stmt_bind_param($ch,"s", $this->userProfile);
            mysqli_stmt_execute($ch);
            mysqli_stmt_store_result($ch);
            mysqli_stmt_bind_result($ch, $tempname);
            mysqli_stmt_fetch($ch);           

            if($userProfile != $tempname){//same -151 diff- 144
                if(mysqli_stmt_num_rows($ch) > 0){ //check if name taken
                    $response['message'] = "Update unsuccessful, Profile Name Exist.";
                    return json_encode($response);
                }
            }

            // Update statement
            $update = "UPDATE userProfile SET userProfile=?, description=? WHERE userProfile=?";
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

            // Close statement and connection
            mysqli_stmt_close($stmt);
            mysqli_close($con);
        } catch (mysqli_sql_exception $e) {
            $response['message'] = "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
        }
*/
        // Return JSON response
        return json_encode($response);
    }


    // System Admin - Delete user profile
    public function suspendProfile($userProfile)
    {
        $response = array(
            'success' => false, // False by default
            'message' => ''
        );
        $temp = 1;
        try {
            // Establish connection
            $con = mysqli_connect($this->jdbcurl, $this->jdbcname, $this->jdbcpass, $this->dbname);
            if (!$con) {
                throw new Exception("Connection failed: " . mysqli_connect_error());
            }

            // SQL query to delete the profile based on user ID
            $delete = "UPDATE userProfile SET suspend=? WHERE userProfile=?";
            $stmt = mysqli_prepare($con, $delete);
            if (!$stmt) {
                throw new Exception("Error preparing statement: " . mysqli_error($con));
            }

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "is",$temp, $userProfile);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Check if any row was affected
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    $response['success'] = true;
                    $response['message'] = "Profile suspended successfully!";
                } else {
                    $response['message'] = "Profile not found or already suspended.";
                }
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
    }

/*
    // System Admin - Search user profile
    public function searchProfile($userProfile)
    {
        $users = array();
        try {
            // Establish connection
            $con = mysqli_connect($this->jdbcurl, $this->jdbcname, $this->jdbcpass, $this->dbname);
            if (!$con) {
                throw new Exception("Connection failed: " . mysqli_connect_error());
            }

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

            mysqli_close($con);
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        return $users;
    }
    */
}
