<?php
header('Content-Type: application/json');

// Get the JSON input from the frontend
$data = json_decode(file_get_contents('php://input'), true);
if ($data && isset($data['image'])) {
    $imageData = $data['image'];
    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $imageData = base64_decode($imageData);

    // Define the upload directory and file path
    $uploadDir = '../temps/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $uploadFile = $uploadDir . 'captured_image.png';

    // Save the uploaded image
    if (file_put_contents($uploadFile, $imageData)) {
        // Prepare the command to execute the Python script
        $pythonScriptPath = 'C:\xampp\htdocs\ImageCheckingSystem\Scripts\WebcamCode.py';
        $command = escapeshellcmd("python $pythonScriptPath $uploadFile");

        // Log the command for debugging
        error_log("Executing command: $command");

        // Execute the Python script using popen
        $process = popen($command, 'r');
        $output = '';
        if ($process) {
            while (!feof($process)) {
                $output .= fgets($process);
            }
            pclose($process);
        }

        // Debug: Print the raw output from the Python script
        error_log("Python script output: '$output'");

        // Decode the JSON response from the Python script
        $response = json_decode($output, true);

        // Log the raw output and the decoded response for debugging
        file_put_contents('php_output.log', $output);
        file_put_contents('php_response.log', print_r($response, true));

        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("JSON error: " . json_last_error_msg());
            echo json_encode(['success' => false, 'message' => 'Failed to parse JSON response.']);
        } else {
            // Send the response back to the frontend
            echo json_encode($response);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No image data found.']);
}
?>
