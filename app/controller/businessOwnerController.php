<?php
include_once '../model/subscription.php';
include_once '../model/account.php';

// Start the session if not already started
if (!isset($_SESSION)) {
    session_start();
}

$action = isset($_POST['action'])? $_POST['action'] : '';

if ($action == 'update') {
    $username = $_POST['username'];
    $subscriptionId = $_POST['subscriptionId'];

    $updateSubscriptionDetails = new updateSubscriptionDetailsController();
    $result = $updateSubscriptionDetails->updateSubscriptionDetails($username, $subscriptionId);
    echo $result;
}

class searchBusinessOwnerAccount{
    public function handleSearchRequest($username) {
        $businessOwner = new BusinessOwner();
        $userData = $businessOwner->searchAccount($username);
        return $userData;
    }
}

class updateSubscriptionDetailsController
{
    function updateSubscriptionDetails($username, $subscriptionId)
    {
        $businessOwner = new BusinessOwner();
        $result = $businessOwner->updateSubscriptionDetails($username, $subscriptionId);
        return $result;
    }  
    
}
?>