<?php

require_once '../Model/Admin.php';
require_once '../Model/BusinessOwner.php';

class AccountCreationController {
    public function createSysAdminAccount($sysAdmin) {
        return Admin::createSysAdminAccount($sysAdmin);
    }

    public function createBusinessOwnerAccount($businessOwner) {
        return BusinessOwner::createBusinessOwnerAccount($businessOwner);
    }
}

?>