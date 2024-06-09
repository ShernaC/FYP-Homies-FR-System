<?php

require_once '../Model/Admin.php';

class SystemController {
    public function createTroubleshootRequest($description) {
        $conn = require '../db/db.php';

        $stmt = $conn->prepare("INSERT INTO troubleshoot_requests (description) VALUES (?)");
        $stmt->bind_param("s", $description);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        return "Troubleshoot request submitted.";
    }
}

?>