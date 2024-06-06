<?php
include 'account.php';

// Start the session if not already started
if (!isset($_SESSION)) {
    session_start();
}

class suspendUserAccount {
    public function suspendAccount($user_id) {
        global $conn;
        $sysAdmin = new SysAdmin($conn);
        $result = $sysAdmin->suspendAccount($user_id);
        return $result;
    }
}

class searchUserAccount{
    private $entity;

    public function handleSearchRequest($search){
        global $conn;
        $sysAdmin = new SysAdmin($conn);

        if (isset($_POST['search'])){
            $userData = $sysAdmin->searchAccount($search);
            return $userData;
        }
    }
}
?>