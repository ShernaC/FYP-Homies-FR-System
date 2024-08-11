<?php
set_time_limit(600); // Set execution time limit to 600 seconds (10 minutes)

// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include Composer's autoload file
require '../vendor/autoload.php';

// Set headers to return JSON response
header('Content-Type: application/json');

// Define directories and files
define('UPLOAD_DIR', 'C:/xampp/htdocs/otp_test/FYP-Homies-FR-System/app/ImageCheckingSystem/uploads/');
define('EXCEL_DIR', 'C:/xampp/htdocs/otp_test/FYP-Homies-FR-System/app/ImageCheckingSystem/uploadsExcel/');
define('EXCEL_FILE', EXCEL_DIR . 'companyNameList.xlsx');

// Ensure directories exist
if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}
if (!is_dir(EXCEL_DIR)) {
    mkdir(EXCEL_DIR, 0777, true);
}

// Function to send JSON response
function sendJsonResponse($status, $message, $data = null) {
    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    exit;
}

// Process uploaded files
if (isset($_FILES['zipFile']) && $_FILES['zipFile']['error'] === UPLOAD_ERR_OK) {
    $zipFilePath = UPLOAD_DIR . basename($_FILES['zipFile']['name']);
    if (!move_uploaded_file($_FILES['zipFile']['tmp_name'], $zipFilePath)) {
        sendJsonResponse('error', 'Failed to move uploaded ZIP file.');
    }

    if (isset($_FILES['excelFile']) && $_FILES['excelFile']['error'] === UPLOAD_ERR_OK) {
        if (!move_uploaded_file($_FILES['excelFile']['tmp_name'], EXCEL_FILE)) {
            sendJsonResponse('error', 'Failed to move uploaded Excel file.');
        }
    } else {
        sendJsonResponse('error', 'Excel file upload failed.');
    }

    // Capture tierDropdown value
    $tier = isset($_POST['tierDropdown']) ? $_POST['tierDropdown'] : 'None';

    // Define the Python script path
    $script_path = 'C:\\xampp\\htdocs\\otp_test\\FYP-Homies-FR-System\\app\\ImageCheckingSystem\\Scripts\\ImageCheckingSystem.py';

    // Prepare command
    $command = escapeshellcmd("python") . " " . escapeshellarg($script_path) . " " . escapeshellarg($zipFilePath) . " " . escapeshellarg(EXCEL_FILE) . " " . escapeshellarg($tier);
    exec($command . ' 2>&1', $output, $return_var);

    if ($return_var === 0) {
        // Ensure result directory exists before saving output
        $result_file_path = 'C:\\xampp\\htdocs\\otp_test\\FYP-Homies-FR-System\\app\\ImageCheckingSystem\\Scripts\\result.txt';
        if (file_put_contents($result_file_path, implode("\n", $output)) === false) {
            sendJsonResponse('error', 'Failed to save Python script output.');
        }

        sendJsonResponse('success', 'Files processed successfully.', ['output' => $output]);
    } else {
        sendJsonResponse('error', 'Error executing Python script.', ['output' => $output]);
    }

} else {
    sendJsonResponse('error', 'ZIP file upload failed.');
}
?>

<html>
<head></head>
<body>
    <script>
        console.log('Currently in upload_and_process.php');
    </script>
</body>
</html>