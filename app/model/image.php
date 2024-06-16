
<?php

class Image {
    public function saveImage($data) {
        $file = 'uploads/' . uniqid() . '.png';
        file_put_contents($file, $data);
        return $file;
    }
}
?>
