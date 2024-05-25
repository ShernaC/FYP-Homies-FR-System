<?php
require_once 'user.php';
session_start();

class suspendUserAccount {
    public function suspendAccount($user) {
        try{
            $user->setSuspendStatus(true);
            return true;
        }
        catch(Exception $e){
            return false;
        }
    }
}

class searchUserAccount{
    private $entity;

    public function __construct($conn){
        $this->entity = new Account($conn);
    }

    public function handleSearchRequest($search){
        if (isset($_POST['search'])){
            
            $userData = $this->entity->searchUsers($search);
            if (empty($search)){
                $_SESSION['error_message'] = "Search field is empty";
                header("Location: ../Boundary/sysViewProfileBoundary.php?userData=" . urlencode(json_encode($userData)));
            } else {
                if ($userData !== false){
                    header("Location: ../Boundary/sysViewProfileBoundary.php?userData=" . urlencode(json_encode($userData)));
                    exit();
                } else {
                    $_SESSION['error_message'] = "No records found";
                    header("Location: ../Boundary/sysViewProfileBoundary.php?userData=" . urlencode(json_encode($userData)));
                    exit();
                }
            }
        }
    }
    
}
?>