<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['imgFile'])) {
    $uploadDir = 'C:/xampp/htdocs/ImageCheckingSystem/TestUpload/';
    $uploadFile = $uploadDir . basename($_FILES['imgFile']['name']);

    if (move_uploaded_file($_FILES['imgFile']['tmp_name'], $uploadFile)) {
        // Adjust the command path to your environment
        $command = escapeshellcmd('python C:\\xampp\\htdocs\\ImageCheckingSystem\\Scripts\\ImageCheckingSystem_UploadToCheck.py');
        $output = shell_exec($command);
        echo "File is successfully uploaded and processed.<br>";
        echo "<pre>$output</pre>";
    } else {
        echo "File upload failed!";
    }
} else {
    echo "No file was uploaded!";
}
?>
