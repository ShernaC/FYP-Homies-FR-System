<?php
include 'account.php'; //to decide on the naming

// Start the session if not already started
if (!isset($_SESSION)) {
    session_start();
}

class viewUserAccount {
    public function viewAccount($user_id) {
        global $conn;
        $sysAdmin = new SysAdmin($conn);
        $result = $sysAdmin->viewAccount($user_id);
        return $result;
    }
}

class updateUserAccount{
    
    public function updateAccount($user_id){
        global $conn;
        $sysAdmin = new SysAdmin($conn);

        $result = $sysAdmin->updateAccount($user_id);
        return $result;
        
    }
}
?>