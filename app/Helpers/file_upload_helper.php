<?php
// app/Helpers/file_helper.php

use CodeIgniter\Files\File;

/**
 * Upload a file to a specified directory and return the file path.
 *
 * @param object $file Uploaded file object
 * @param string $folder Target folder inside 'uploads/'
 * @return string|false File path on success, false on failure
 */
function uploadFile($file, $folder, $fileId)
{
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];
    $maxSize = 5 * 1024 * 1024;
    if (!$file->isValid()) {
        return false;
    }
    $extension = $file->getExtension();
    if (!in_array(strtolower($extension), $allowedExtensions)) {
        return false;
    }
    if ($file->getSize() > $maxSize) {
        return false;
    }
    $uploadPath = ROOTPATH . 'uploads/' . $folder;
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }
    $newName = $fileId . '.' . $extension;
    if ($file->move($uploadPath, $newName)) {
        return 'uploads/' . $folder . '/' . $newName; // Return relative file path
    }

    return false;
}
