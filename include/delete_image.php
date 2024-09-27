<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
    error_log('Non-POST request attempted');
    exit();
}

require_once '../config.php';

if (!isset($_POST['csrf_Token']) || $_POST['csrf_Token'] !== $_SESSION['csrf_Token']) {
    die('CSRF TOKEN ERROR!');
}

try {
    require_once '../dbl.php';

    $fileName = isset($_POST['file_name']) ? $_POST['file_name'] : '';

    if ($fileName) {
        // Set the file path
        $filePath = 'upload_file/' . $fileName;
    
        // Delete the file from the file system
        if (file_exists($filePath)) {
            unlink($filePath); // This will delete the file from the folder
        }
    
        // Remove the file from the database
        $stmt = $pdo->prepare("DELETE FROM upload WHERE file_name = :file_name");
        $stmt->execute(['file_name' => $fileName]);
    
        // Remove the file from the session (if stored there)
        if (isset($_SESSION['uploaded_files'][$fileName])) {
            unset($_SESSION['uploaded_files'][$fileName]);
        }
    
        header('Location: ../index.php');
        exit;
    } else {
        echo "File not found.";
    }
    
} catch (PDOException $e) {
    die('Query failed: '.$e->getMessage());
}