<?php

namespace Helper;

class Uploads
{
    protected $allowedTypes = [];
    protected $maxSize = 2 * 1024 * 1024;
    protected $uploadPath;

    public function __construct($uploadPath = '/public/uploads/')
    {
        $this->uploadPath = ROOT_DIR . trim($uploadPath, '/');
        if (!file_exists($this->uploadPath)) {
            mkdir($this->uploadPath, 0777, true);
        }
    }

    public function setAllowedTypes(array $types)
    {
        $this->allowedTypes = $types;
        return $this;
    }

    public function setMaxSize($bytes)
    {
        $this->maxSize = $bytes;
        return $this;
    }

    public function upload($file)
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['status' => false, 'message' => 'No file uploaded or upload error.'];
        }

        $fileName = $file['name'];
        $fileTmp = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileType = mime_content_type($fileTmp);
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

        if (!in_array($fileExt, $this->allowedTypes)) {
            return ['status' => false, 'message' => 'Invalid file type. Allowed types: ' . implode(', ', $this->allowedTypes)];
        }

        if ($fileSize > $this->maxSize) {
            return ['status' => false, 'message' => 'File size exceeds limit (' . $this->maxSize . ' bytes).'];
        }

        $uniqueName = time() . '_' . $fileName;
        $targetPath = $this->uploadPath . '/' . $uniqueName;

        if (move_uploaded_file($fileTmp, $targetPath)) {
            return ['status' => true, 'file_path' => $targetPath, 'file_name' => $uniqueName];
        }

        return ['status' => false, 'message' => 'Failed to upload the file.'];
    }
}
