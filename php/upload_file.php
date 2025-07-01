    <?php


    // Handle File Uploads
    function uploadFile($file, $targetDir)
    {
        if (!isset($_FILES[$file]) || $_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
            return '';
        }
        $filePath = $targetDir . basename($_FILES[$file]['name']);
        if (move_uploaded_file($_FILES[$file]['tmp_name'], $filePath)) {
            return $filePath;
        }
        return '';
    }
