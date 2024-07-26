<?php
include_once '../db/db.php';

if (!$conn || !$conn instanceof mysqli) {
    die("Profile: Database connection not established.");
}
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class subscription
{
    private int $id;
    private string $name;
    private float $price;
    private string $description;

    public function __construct($id=0, $name = "", $price = 0.0, $description = "")
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->description = $description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    // View subscription
    public function viewSubscription($id)
    {
        global $conn;

        try {
            // SQL query to view all subscriptions
            $viewAllSubscriptions = "(SELECT * FROM subscription WHERE id=?);";
            $stmt = mysqli_prepare($conn, $viewAllSubscriptions);
            if (!$stmt) {
                throw new Exception("Error: " . mysqli_error($conn));
            }

            // Bind the parameters
            mysqli_stmt_bind_param($stmt, "i", $id);

            // Execute the statement
            // Set parameter and execute
            mysqli_stmt_execute($stmt);

            // Get result
            $result = mysqli_stmt_get_result($stmt);
            $subscription= mysqli_fetch_assoc($result);

            //mysqli_close($conn);
        } catch (mysqli_sql_exception $e) {
            echo "Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        return $subscription;

    }

    // View all subscriptions
    public function viewAllSubscriptions()
    {
        global $conn;

        $response = array(
            'success' => false, // False by default
            'message' => '',
            'subscriptions' => []
        );

        try {
            // SQL query to view all subscriptions
            $viewAllSubscriptions = "(SELECT * FROM subscription);";
            $stmt = mysqli_prepare($conn, $viewAllSubscriptions);
            if (!$stmt) {
                throw new Exception("Error: " . mysqli_error($conn));
            }

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Fetch all rows into an array
                $result = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($result)) {
                    $response['subscriptions'][] = $row;
                }
                $response['success'] = true;
                $response['message'] = "Successfully retrieved all subscriptions.";
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

}

class subscriptionDetails
{
    private int $id;
    private int $owner_id;
    private int $subscription_id;
    private $startDate;
    private $endDate;

    public function __construct($id=0, $subscription_id = 0, $owner_id = 0, $startDate = "", $endDate = "")
    {
        $this->id = $id;
        $this->subscription_id = $subscription_id;
        $this->owner_id = $owner_id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOwnerId()
    {
        return $this->owner_id;
    }

    public function getSubscriptionId()
    {
        return $this->subscription_id;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setOwnerId($owner_id)
    {
        $this->owner_id = $owner_id;
    }

    public function setSubscriptionId($subscription_id)
    {
        $this->subscription_id = $subscription_id;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    // Add subscription details
    function createSubscriptionDetails($username, $subscriptionId)
    {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO subscription_details (username, subscription_id, startDate, endDate) VALUES (?, ?, ?, ?);");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        // Bind the parameters
        $stmt->bind_param("siss",  $username, $subscriptionId, "1999-01-01", "2199-01-01");

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $success = true;
        }

        return $success;
    }


    // View subscription details
    function getSubscriptionDetails($username, $subscriptionId)
    {
        global $conn;

        try {
            // SQL query to view all subscriptions
            $viewAllSubscriptions = "(SELECT * FROM subscription_details WHERE username=? AND subscription_id=?);";
            $stmt = mysqli_prepare($conn, $viewAllSubscriptions);
            if (!$stmt) {
                throw new Exception("Error: " . mysqli_error($conn));
            }

            // Bind the parameters
            mysqli_stmt_bind_param($stmt, "si", $username, $subscriptionId);

            // Execute the statement
            // Set parameter and execute
            mysqli_stmt_execute($stmt);

            // Get result
            $result = mysqli_stmt_get_result($stmt);
            $subscription= mysqli_fetch_assoc($result);

        } catch (mysqli_sql_exception $e) {
            $response['message'] = "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            $response['message'] = "Error: " . $e->getMessage();
        }

        return $subscription;
    }

    // Update subscription details
    function updateSubscriptionDetails($username, $subscriptionId)
    {
        global $conn;

        $success = false;

        $stmt = $conn->prepare("UPDATE subscription_details SET subscription_id = ?, startDate = ?, endDate = ? WHERE username = ?;");
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $currentDate = date('Y-m-d');
        $nextYearDate = date('Y-m-d', strtotime('+1 year', strtotime($currentDate)));

        // Bind the parameters
        $stmt->bind_param("isss", $subscriptionId, $currentDate, $nextYearDate,  $username);

        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $success = true;
        }

        return $success;
    }
}

?>