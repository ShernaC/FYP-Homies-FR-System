<?php
// profile.php (Model)

$con = require_once('../view/index.php');

class profile
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

    //test
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
}
?>