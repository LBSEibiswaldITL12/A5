<?php

class UploadController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        Auth::checkAuthentication();
    }

    public function index()
    {
        $this->View->render('upload/index');
    }

    public function upload()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
            $uploadDir = dirname(__DIR__) . '/private_uploads/';

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = basename($_FILES['image']['name']);
            $fileName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $fileName);

            $uniqueFileName = uniqid() . '_' . $fileName;

            $uploadFile = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                echo json_encode(['success' => true, 'fileId' => $uniqueFileName]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        }
    }

    public function listImages()
    {
        $uploadDir = dirname(__DIR__) . '/private_uploads/';

        $files = array_diff(scandir($uploadDir), ['.', '..']);
        $imageUrls = [];

        foreach ($files as $file) {
            $imageUrls[] = [
                'fileId' => $file,
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($imageUrls);
    }

    public function serveImage($fileId)
    {
        $uploadDir = dirname(__DIR__) . '/private_uploads/';
        $filePath = $uploadDir . $fileId;

        if (file_exists($filePath)) {
            $mimeType = mime_content_type($filePath);
            header('Content-Type: ' . $mimeType);
            readfile($filePath);
        } else {
            http_response_code(404);
            echo 'File not found.';
        }
    }
}
