<?php
class Troubleshoot {
    private $id;
    private $admin_id;
    private $details;

    public function __construct($admin_id = null, $details = null) {
        $this->admin_id = $admin_id;
        $this->details = $details;
    }

    public function create() {
        global $conn;
        $response = ['success' => false, 'message' => ''];

        try {
            $stmt = $conn->prepare("INSERT INTO troubleshoot (admin_id, details) VALUES (?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }

            $stmt->bind_param("is", $this->admin_id, $this->details);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response['success'] = true;
                    $response['message'] = 'Troubleshoot request created successfully';
                } else {
                    throw new Exception("No rows affected, insert failed.");
                }
            } else {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }

            $stmt->close();
        } catch (Exception $e) {
            error_log("Error creating Troubleshoot request: " . $e->getMessage());
            $response['message'] = 'Database Error: ' . $e->getMessage();
        }

        return json_encode($response);
    }
}
?>