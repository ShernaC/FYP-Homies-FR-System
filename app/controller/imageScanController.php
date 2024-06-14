<?php
// controllers/CaptureController.php

require_once '../model/image.php';

$action = $_POST['action']?? null;
echo 'action : ', $action;   

switch ($action) {
    case 'saveImage':
        $controller = new CaptureController();
        $controller->saveImage();
        break;
    default:
        echo "Invalid action.";
}

class CaptureController {
    public function saveImage() {
        if(isset($_POST['image'])) {
            $data = $_POST['image'];

            // Remove the "data:image/png;base64," part
            $data = str_replace('data:image/png;base64,', '', $data);
            $data = str_replace(' ', '+', $data);

            // Decode the base64 string
            $data = base64_decode($data);

            // Save the image using the model
            $image = new Image();
            $file = $image->saveImage($data);

            echo "Image saved as " . $file;
        } else {
            echo "No image data received.";
        }
    }
}
?>
